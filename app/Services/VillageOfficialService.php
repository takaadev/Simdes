<?php

namespace App\Services;

use App\Enums\VillageOfficialStatusList;
use App\Models\User;
use App\Models\VillageOfficial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class VillageOfficialService
{

    public function getData(Request $request)
    {
        $query = VillageOfficial::query()
            ->with('position:id,kode,nama')
            ->select([
                'id',
                'nama',
                'niap',
                'nip',
                'jenis_kelamin',
                'tempat_lahir',
                'tgl_lahir',
                'agama',
                'pangkat_golongan',
                'village_position_id',
                'pendidikan',
                'nomor_pengangkatan',
                'tgl_pengangkatan',
                'nomor_pemberhentian',
                'tgl_pemberhentian',
                'alamat',
                'telpon',
                'status',
                'notes',
                'created_by',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);

        // Filter jenis kelamin.
        $query->when(
            $request->filled('jenis_kelamin')
                && $request->input('jenis_kelamin') !== 'all',
            fn($query) => $query->where(
                'jenis_kelamin',
                $request->input('jenis_kelamin')
            )
        );

        // Filter status aparat desa.
        $query->when(
            $request->filled('status')
                && $request->input('status') !== 'all',
            fn($query) => $query->where(
                'status',
                $request->input('status')
            )
        );

        // Filter tahun pengangkatan.
        $query->when(
            $request->filled('year')
                && $request->input('year') !== 'all',
            fn($query) => $query->whereYear(
                'tgl_pengangkatan',
                $request->input('year')
            )
        );

        // Filter data terhapus.
        $query->when(
            $request->input('deleted') === 'trashed',
            fn($query) => $query->onlyTrashed()
        );

        $query->latest('created_at');

        // Query statistik aparat desa.
        $summaryQuery = VillageOfficial::query()
            ->when(
                $request->filled('jenis_kelamin')
                    && $request->input('jenis_kelamin') !== 'all',
                fn($query) => $query->where(
                    'jenis_kelamin',
                    $request->input('jenis_kelamin')
                )
            )
            ->when(
                $request->filled('status')
                    && $request->input('status') !== 'all',
                fn($query) => $query->where(
                    'status',
                    $request->input('status')
                )
            )
            ->when(
                $request->filled('position_id')
                    && $request->input('position_id') !== 'all',
                fn($query) => $query->where(
                    'village_position_id',
                    $request->input('position_id')
                )
            );

        // Filter status data untuk statistik.
        $summaryQuery->when(
            $request->input('deleted') === 'trashed',
            fn($query) => $query->onlyTrashed()
        );

        $summary = $summaryQuery
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN jenis_kelamin = 'L' THEN 1 ELSE 0 END) as male,
                SUM(CASE WHEN jenis_kelamin = 'P' THEN 1 ELSE 0 END) as female,
                SUM(CASE
                    WHEN status IN ('inactive', 'retired', 'dismissed', 'resigned')
                    THEN 1
                    ELSE 0
                END) as inactive
            ")
            ->first();

        return DataTables::eloquent($query)
            ->with('summary', [
                'total' => (int) $summary->total,
                'active' => (int) $summary->active,
                'male' => (int) $summary->male,
                'female' => (int) $summary->female,
                'inactive' => (int) $summary->inactive,
            ])
            ->addIndexColumn()

            ->editColumn(
                'nama',
                fn($row) => $this->tooltip($row->nama)
            )

            ->editColumn(
                'niap',
                fn($row) => $row->niap ?: '-'
            )

            ->editColumn(
                'nip',
                fn($row) => $row->nip ?: '-'
            )

            ->editColumn(
                'jenis_kelamin',
                fn($row) => $row->jenis_kelamin?->label() ?? '-'
            )

            ->editColumn(
                'tempat_lahir',
                fn($row) => $row->tempat_lahir ?: '-'
            )

            ->editColumn(
                'tempat_lahir',
                fn($row) => $row->agama ?: '-'
            )

            ->editColumn(
                'tgl_lahir',
                fn($row) => $this->formatDate($row->tgl_lahir)
            )

            ->editColumn(
                'pangkat_golongan',
                fn($row) => $row->pangkat_golongan ?: '-'
            )

            ->addColumn(
                'position',
                fn($row) => $row->position?->nama ?? '-'
            )

            ->editColumn(
                'pendidikan',
                fn($row) => $row->pendidikan?->label() ?? '-'
            )

            ->editColumn(
                'nomor_pengangkatan',
                fn($row) => $row->nomor_pengangkatan ?: '-'
            )

            ->editColumn(
                'tgl_pengangkatan',
                fn($row) => $this->formatDate($row->tgl_pengangkatan)
            )

            ->editColumn(
                'nomor_pemberhentian',
                fn($row) => $row->nomor_pemberhentian ?: '-'
            )

            ->editColumn(
                'tgl_pemberhentian',
                fn($row) => $this->formatDate($row->tgl_pemberhentian)
            )

            ->editColumn(
                'alamat',
                fn($row) => $this->tooltip($row->alamat)
            )

            ->editColumn(
                'telpon',
                fn($row) => $row->telpon ?: '-'
            )

            ->editColumn(
                'status',
                fn($row) => view('components.badge', [
                    'color' => $row->status?->color() ?? 'secondary',
                    'slot' => $row->status?->label() ?? '-',
                ])->render()
            )

            ->editColumn(
                'notes',
                fn($row) => $this->tooltip($row->notes)
            )

            ->addColumn(
                'action',
                fn($row) => view('pages.villageofficial.comp.actions', [
                    'row' => $row,
                ])->render()
            )

            ->rawColumns([
                'nama',
                'alamat',
                'notes',
                'status',
                'action',
            ])
            ->toJson();
    }

    private function formatDate($date): string
    {
        return $date
            ?->locale('id')
            ->translatedFormat('d F Y') ?? '-';
    }

    private function tooltip(?string $value): string
    {
        return view('components.tooltip', [
            'value' => $value,
        ])->render();
    }


    public function getStore(array $data): JsonResponse
    {
        $hasUser = (bool) ($data['has_user'] ?? false);

        try {
            $villageOfficial = DB::transaction(function () use ($data, $hasUser) {

                $role = $data['role'] ?? null;

                // Hapus field khusus akun login.
                unset(
                    $data['has_user'],
                    $data['role'],
                    $data['password']
                );

                // Informasi pembuat data.
                $data['created_by'] = auth()->id();

                // Simpan data aparat desa.
                $villageOfficial = VillageOfficial::create($data);

                // Buat akun login jika diaktifkan.
                if ($hasUser) {
                    $user = User::create([
                        'name' => $villageOfficial->nama,
                        'username' => $villageOfficial->niap,
                        'email' => null,
                        'role' => $role,
                        'password' => Hash::make('@pemdes2026'),
                    ]);

                    // Hubungkan akun dengan aparat desa.
                    $villageOfficial->update([
                        'auth_id' => $user->id,
                    ]);
                }

                return $villageOfficial;
            });

            return response()->json([
                'success' => true,
                'message' => 'Data aparat desa berhasil ditambahkan.'
                    . ($hasUser ? ' Akun login juga berhasil dibuat.' : ''),
                'data' => $villageOfficial->fresh('user'),
            ], 201);
        } catch (\Throwable $e) {

            Log::error('Gagal menambahkan data aparat desa.', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data aparat desa gagal ditambahkan.',
            ], 500);
        }
    }


    public function getUpdate(VillageOfficial $villageOfficial, array $data): JsonResponse
    {
        try {
            DB::transaction(function () use ($villageOfficial, $data) {
                $oldNiap = $villageOfficial->niap;

                // Perbarui data aparat desa.
                $villageOfficial->update($data);

                // Jika NIAP berubah dan aparat memiliki akun login.
                if (
                    $oldNiap !== $villageOfficial->niap &&
                    $villageOfficial->auth_id
                ) {
                    $user = User::query()->whereKey($villageOfficial->auth_id)->first();
                    if ($user) {
                        $user->update([
                            'username' => $villageOfficial->niap,
                        ]);
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Data aparat desa berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal memperbarui data aparat desa.', [
                'village_official_id' => $villageOfficial->id,
                'auth_id' => $villageOfficial->auth_id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data aparat desa.',
            ], 500);
        }
    }

    public function getDestroy(Request $request, VillageOfficial $villageOfficial): JsonResponse
    {
        try {
            $forceDelete = $request->boolean('confirm_delete');
            if ($villageOfficial->trashed() && !$forceDelete) {
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

            DB::transaction(function () use ($villageOfficial, $forceDelete) {
                // Cari akun pengguna yang terhubung.
                $user = $villageOfficial->auth_id ? User::withTrashed()->find($villageOfficial->auth_id) : null;
                if ($forceDelete) {
                    $deleted = $villageOfficial->forceDelete();
                    if (!$deleted) {
                        throw new \RuntimeException(
                            'Data aparat desa gagal dihapus permanen.'
                        );
                    }
                    if ($user) {
                        $user->forceDelete();
                    }
                } else {
                    if ($user && !$user->trashed()) {
                        $user->delete();
                    }
                    $deleted = $villageOfficial->delete();
                    if (!$deleted) {
                        throw new \RuntimeException(
                            'Data aparat desa gagal dipindahkan ke sampah.'
                        );
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => $forceDelete
                    ? 'Data aparat desa dan akun login berhasil dihapus permanen.'
                    : 'Data aparat desa dan akun login berhasil dipindahkan ke sampah.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal menghapus data aparat desa.', [
                'village_official_id' => $villageOfficial->id,
                'auth_id' => $villageOfficial->auth_id,
                'force_delete' => $forceDelete ?? false,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data aparat desa.',
            ], 500);
        }
    }

    public function getDismissal(array $data): JsonResponse
    {
        try {
            DB::transaction(function () use ($data) {

                $villageOfficial = VillageOfficial::query()
                    ->where('status', VillageOfficialStatusList::ACTIVE->value)
                    ->lockForUpdate()
                    ->findOrFail($data['official_id']);

                // Perbarui data pemberhentian aparat.
                $villageOfficial->update([
                    'nomor_pemberhentian' => $data['nomor_pemberhentian'],
                    'tgl_pemberhentian' => $data['tgl_pemberhentian'],
                    'notes' => $data['notes'] ?? null,
                    'status' => $data['status'],
                ]);

                // Soft delete akun login jika tersedia.
                if ($villageOfficial->auth_id) {
                    $user = User::query()
                        ->lockForUpdate()
                        ->find($villageOfficial->auth_id);

                    if ($user) {
                        $user->delete();
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Data pemberhentian aparat desa berhasil disimpan.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal melakukan pemberhentian aparat desa.', [
                'official_id' => $data['official_id'] ?? null,
                'status' => $data['status'] ?? null,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses pemberhentian aparat desa.',
            ], 500);
        }
    }

    public function getPrintData(Request $request)
    {
        $isTrashed = $request->input('deleted') === 'trashed';
        $search = trim((string) $request->input('search', ''));

        return VillageOfficial::query()
            ->with('position:id,kode,nama')
            ->select([
                'id',
                'nama',
                'niap',
                'nip',
                'jenis_kelamin',
                'tempat_lahir',
                'tgl_lahir',
                'agama',
                'pendidikan',
                'pangkat_golongan',
                'village_position_id',
                'nomor_pengangkatan',
                'tgl_pengangkatan',
                'nomor_pemberhentian',
                'tgl_pemberhentian',
                'status',
                'telpon',
                'alamat',
                'notes',
            ])
            ->when($isTrashed, fn($q) => $q->onlyTrashed())
            ->when(
                $request->filled('jenis_kelamin') && $request->input('jenis_kelamin') !== 'all',
                fn($q) => $q->where('jenis_kelamin', $request->input('jenis_kelamin'))
            )
            ->when(
                $request->filled('status') && $request->input('status') !== 'all',
                fn($q) => $q->where('status', $request->input('status'))
            )
            ->when(
                $request->filled('year') && $request->input('year') !== 'all',
                fn($q) => $q->whereYear('tgl_pengangkatan', $request->input('year'))
            )
            ->when(
                $request->filled('position_id') && $request->input('position_id') !== 'all',
                fn($q) => $q->where('village_position_id', $request->input('position_id'))
            )
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('nama', 'like', "%{$search}%")
                        ->orWhere('niap', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%")
                        ->orWhere('nomor_pengangkatan', 'like', "%{$search}%")
                        ->orWhere('notes', 'like', "%{$search}%")
                        ->orWhereHas('position', fn($pos) => $pos->where('nama', 'like', "%{$search}%"));
                });
            })
            ->orderBy('village_position_id')
            ->orderBy('nama')
            ->get();
    }

    public function getFilterSummary(Request $request): ?string
    {
        $parts = [];

        if ($request->filled('jenis_kelamin') && $request->input('jenis_kelamin') !== 'all') {
            $parts[] = 'JK: ' . $request->input('jenis_kelamin');
        }
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $parts[] = 'Status: ' . $request->input('status');
        }
        if ($request->filled('year') && $request->input('year') !== 'all') {
            $parts[] = 'Tahun Pengangkatan: ' . $request->input('year');
        }
        if ($request->input('deleted') === 'trashed') {
            $parts[] = 'Status Data: Dihapus';
        }

        return $parts ? implode(' | ', $parts) : null;
    }
}
