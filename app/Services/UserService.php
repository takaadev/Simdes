<?php

namespace App\Services;

use App\Enums\RoleAccessType;
use App\Models\User;
use App\Models\VillageOfficial;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class UserService
{
    public function getData(Request $request)
    {
        $isTrashed = $request->input('deleted') === 'trashed';
        $activeCutoff = now()->subMinutes(15)->timestamp;

        $hasActiveSession = fn($q) => $q->whereExists(function ($sub) use ($activeCutoff) {
            $sub->select(DB::raw(1))
                ->from('sessions')
                ->whereColumn('sessions.user_id', 'users.id')
                ->where('sessions.last_activity', '>=', $activeCutoff);
        });

        $hasNoActiveSession = fn($q) => $q->whereNotExists(function ($sub) use ($activeCutoff) {
            $sub->select(DB::raw(1))
                ->from('sessions')
                ->whereColumn('sessions.user_id', 'users.id')
                ->where('sessions.last_activity', '>=', $activeCutoff);
        });

        // Base query dari tabel users yang terhubung ke village_officials.
        $baseQuery = User::query()
            ->whereHas('villageOfficial', fn($q) => $q->when($isTrashed, fn($sub) => $sub->withTrashed()))
            // 1. Filter Status Data (SoftDeletes pada users).
            ->when($isTrashed, fn($q) => $q->onlyTrashed())
            // 2. Filter Role Type.
            ->when(
                $request->filled('role') && $request->input('role') !== 'all',
                fn($q) => $q->where('users.role', $request->input('role'))
            )
            // 3. Filter Status Login (berdasarkan tabel sessions + last_login_at).
            ->when(
                $request->filled('login_status') && $request->input('login_status') !== 'all',
                function ($q) use ($request, $hasActiveSession, $hasNoActiveSession) {
                    match ($request->input('login_status')) {
                        'online' => $hasActiveSession($q),
                        'offline' => $hasNoActiveSession($q->whereNotNull('users.last_login_at')),
                        'never' => $hasNoActiveSession($q->whereNull('users.last_login_at')),
                        default => null,
                    };
                }
            );

        // 2 Summary: Total User & Total Sedang Login (Online).
        $onlineExistsSql = 'EXISTS (SELECT 1 FROM sessions WHERE sessions.user_id = users.id AND sessions.last_activity >= ?)';

        $summary = (clone $baseQuery)
            ->reorder()
            ->selectRaw("
                COUNT(*) AS total_user,
                COALESCE(SUM(CASE WHEN {$onlineExistsSql} THEN 1 ELSE 0 END), 0) AS total_online
            ", [$activeCutoff])
            ->first();

        // Query utama DataTables.
        $query = (clone $baseQuery)
            ->with([
                'villageOfficial' => fn($q) => $q
                    ->when($isTrashed, fn($sub) => $sub->withTrashed())
                    ->with('position:id,kode,nama'),
            ])
            ->select([
                'users.id',
                'users.name',
                'users.username',
                'users.email',
                'users.role',
                'users.avatar_url',
                'users.last_login_at',
                'users.created_at',
                'users.updated_at',
                'users.deleted_at',
            ])
            ->selectRaw("{$onlineExistsSql} AS is_online", [$activeCutoff])
            ->when(
                !$request->filled('order'),
                fn($q) => $q->latest('users.created_at')
            );

        return DataTables::eloquent($query)
            ->with('summary', [
                'total_user' => (int) ($summary?->total_user ?? 0),
                'total_online' => (int) ($summary?->total_online ?? 0),
            ])
            ->filter(function ($query) use ($request, $isTrashed) {
                $search = trim((string) $request->input('search.value', ''));

                if ($search !== '') {
                    $query->where(function ($q) use ($search, $isTrashed) {
                        $q->where('users.name', 'like', "%{$search}%")
                            ->orWhere('users.username', 'like', "%{$search}%")
                            ->orWhere('users.email', 'like', "%{$search}%")
                            ->orWhere('users.role', 'like', "%{$search}%")
                            ->orWhereHas('villageOfficial', function ($sub) use ($search, $isTrashed) {
                                if ($isTrashed) {
                                    $sub->withTrashed();
                                }

                                $sub->where('nama', 'like', "%{$search}%")
                                    ->orWhere('niap', 'like', "%{$search}%")
                                    ->orWhere('telpon', 'like', "%{$search}%")
                                    ->orWhereHas('position', fn($pos) => $pos->where('nama', 'like', "%{$search}%"));
                            });
                    });
                }
            })
            ->addIndexColumn()
            ->addColumn(
                'nama',
                fn($row) => $this->tooltip($row->villageOfficial?->nama ?: $row->name ?: '-')
            )
            ->editColumn('username', fn($row) => $row->username ?: '-')
            ->editColumn('email', fn($row) => $row->email ?: '-')
            ->editColumn('role', function ($row) {
                $role = $row->role instanceof RoleAccessType
                    ? $row->role
                    : RoleAccessType::tryFrom((string) $row->role);

                return view('components.badge', [
                    'color' => $role?->color() ?? 'secondary',
                    'slot' => $role?->label() ?? ($row->role ?: '-'),
                ])->render();
            })
            ->addColumn(
                'position',
                fn($row) => $row->villageOfficial?->position?->nama ?? '-'
            )
            ->addColumn(
                'telpon',
                fn($row) => $row->villageOfficial?->telpon ?: '-'
            )
            ->addColumn(
                'status',
                fn($row) => view('components.badge', [
                    'color' => $row->villageOfficial?->status?->color() ?? 'secondary',
                    'slot' => $row->villageOfficial?->status?->label() ?? '-',
                ])->render()
            )
            ->editColumn(
                'created_at',
                fn($row) => $this->formatDate($row->created_at)
            )
            ->addColumn(
                'last_login',
                fn($row) => $row->last_login_at
                    ? Carbon::parse($row->last_login_at)->locale('id')->translatedFormat('d M Y, H:i')
                    : '-'
            )
            ->addColumn('login_status', function ($row) {
                if ((bool) $row->is_online) {
                    return view('components.badge', [
                        'color' => 'success',
                        'slot' => 'Online',
                    ])->render();
                }

                return view('components.badge', [
                    'color' => 'secondary',
                    'slot' => 'Offline',
                ])->render();
            })
            ->addColumn(
                'action',
                fn($row) => view('pages.settings.user.comp.actions', [
                    'row' => $row,
                ])->render()
            )
            ->rawColumns([
                'nama',
                'role',
                'status',
                'login_status',
                'action',
            ])
            ->toJson();
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
