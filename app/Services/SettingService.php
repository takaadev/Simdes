<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\User;
use App\Models\VillageOfficial;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class SettingService
{
    public function getData(): ?AppSetting
    {
        return AppSetting::query()->first();
    }

    public function getUpdateProfile(AppSetting $setting, array $data): JsonResponse
    {
        try {
            $data['updated_by'] = Auth::id();
            $setting->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Profil desa berhasil diperbarui.',
                'data'    => $setting->fresh(),
            ], 200);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui profil desa', [
                'setting_id' => $setting->id,
                'error'      => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui profil desa.',
            ], 500);
        }
    }

    public function getUpdateSystem(AppSetting $setting, array $data): JsonResponse
    {
        try {
            if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
                if ($setting->logo && Storage::disk('public')->exists($setting->logo)) {
                    Storage::disk('public')->delete($setting->logo);
                }

                $data['logo'] = $data['logo']->store('settings', 'public');
            } else {
                unset($data['logo']);
            }

            $data['updated_by'] = Auth::id();
            $setting->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Pengaturan sistem berhasil diperbarui.',
                'data'    => $setting->fresh(),
            ], 200);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui pengaturan sistem', [
                'setting_id' => $setting->id,
                'error'      => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui pengaturan sistem.',
            ], 500);
        }
    }

    private function formatDate($date): string
    {
        return $date
            ? Carbon::parse($date)->locale('id')->translatedFormat('d F Y')
            : '-';
    }

    private function tooltip(?string $value): string
    {
        return view('components.tooltip', [
            'value' => $value,
        ])->render();
    }

    public function getStore(array $data): JsonResponse
    {
        try {
            $user = DB::transaction(function () use ($data) {
                // Ambil perangkat desa yang belum memiliki akun.
                $villageOfficial = VillageOfficial::query()
                    ->whereNull('auth_id')
                    ->lockForUpdate()
                    ->findOrFail($data['official_id']);

                // Buat akun pengguna.
                $user = User::create([
                    'name' => $villageOfficial->nama,
                    'username' => $villageOfficial->niap,
                    'email' => $data['email'] ?? null,
                    'role' => $data['role'],
                    'password' => Hash::make($data['password'] ?: '@pemdes2026'),
                ]);

                // Hubungkan akun dengan perangkat desa.
                $villageOfficial->update([
                    'auth_id' => $user->id,
                ]);

                return $user;
            });

            return response()->json([
                'success' => true,
                'message' => 'Akun pengguna berhasil dibuat.',
                'data' => $user,
            ], 201);
        } catch (Throwable $e) {
            Log::error('Gagal membuat akun pengguna.', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Akun pengguna gagal dibuat.',
            ], 500);
        }
    }

    public function getUpdatePassword(User $user, array $data): JsonResponse
    {
        try {
            DB::transaction(function () use ($user, $data) {
                $user->update([
                    'password' => Hash::make($data['password']),
                ]);

                // Putus sesi lain milik user agar login ulang dengan kata sandi baru.
                if ($user->id !== Auth::id()) {
                    DB::table('sessions')->where('user_id', $user->id)->delete();
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Kata sandi pengguna berhasil diperbarui.',
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui kata sandi pengguna.', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Kata sandi pengguna gagal diperbarui.',
            ], 500);
        }
    }

    public function getUpdate(User $user, array $data): JsonResponse
    {
        try {
            DB::transaction(function () use ($user, $data) {
                $user->update($data);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data akun pengguna berhasil diperbarui.',
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui data akun pengguna.', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data akun pengguna.',
            ], 500);
        }
    }

    public function getDestroy(Request $request, User $user): JsonResponse
    {
        try {
            if ($user->id === Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak dapat menghapus akun yang sedang digunakan.',
                ], 422);
            }

            $forceDelete = $request->boolean('confirm_delete');

            // Data Trash wajib dikonfirmasi.
            if ($user->trashed() && !$forceDelete) {
                return response()->json([
                    'success' => false,
                    'message' => 'Centang konfirmasi untuk menghapus permanen.',
                    'errors' => [
                        'confirm_delete' => [
                            'Data sudah berada di sampah. Centang konfirmasi untuk menghapus permanen.',
                        ],
                    ],
                ], 422);
            }

            $deleted = DB::transaction(function () use ($user, $forceDelete) {
                // Hapus seluruh sesi aktif user.
                DB::table('sessions')->where('user_id', $user->id)->delete();

                if ($forceDelete) {
                    // Lepas tautan auth_id agar perangkat desa bisa dibuatkan akun baru nanti.
                    VillageOfficial::withTrashed()
                        ->where('auth_id', $user->id)
                        ->update(['auth_id' => null]);

                    return $user->forceDelete();
                }

                return $user->delete();
            });

            if (!$deleted) {
                throw new RuntimeException('Akun pengguna gagal dihapus.');
            }

            return response()->json([
                'success' => true,
                'message' => $forceDelete
                    ? 'Akun pengguna berhasil dihapus permanen.'
                    : 'Akun pengguna berhasil dipindahkan ke sampah.',
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus akun pengguna.',
            ], 500);
        }
    }
}
