<?php

namespace App\Services;

use App\Enums\VillageOfficialStatusList;
use App\Models\VillageOfficial;
use Illuminate\Http\Request;
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

    public function getStore(array $data): \Illuminate\Http\JsonResponse
    {
        try {
            $villageOfficial = \DB::transaction(function () use ($data) {
                $data['created_by'] = auth()->id();

                return VillageOfficial::create($data);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data aparat desa berhasil ditambahkan.',
                'data' => $villageOfficial,
            ], 201);
        } catch (\Throwable $e) {
            \Log::error('Gagal menambahkan data aparat desa.', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data aparat desa gagal ditambahkan.',
            ], 500);
        }
    }

    public function getUpdate(VillageOfficial $villageOfficial, array $data): \Illuminate\Http\JsonResponse
    {
        try {
            \DB::transaction(function () use ($villageOfficial, $data) {
                $villageOfficial->update($data);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data aparat desa berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            \Log::error('Gagal memperbarui data aparat desa.', [
                'village_official_id' => $villageOfficial->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data aparat desa.',
            ], 500);
        }
    }

    public function getDestroy(Request $request, VillageOfficial $villageOfficial): \Illuminate\Http\JsonResponse
    {
        try {
            $forceDelete = $request->boolean('confirm_delete');

            // Data Trash wajib dikonfirmasi.
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

            $deleted = \DB::transaction(function () use (
                $villageOfficial,
                $forceDelete
            ) {
                return $forceDelete
                    ? $villageOfficial->forceDelete()
                    : $villageOfficial->delete();
            });

            if (!$deleted) {
                throw new \RuntimeException('Data aparat desa gagal dihapus.');
            }

            return response()->json([
                'success' => true,
                'message' => $forceDelete
                    ? 'Data aparat desa berhasil dihapus permanen.'
                    : 'Data aparat desa berhasil dipindahkan ke sampah.',
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data aparat desa.',
            ], 500);
        }
    }
}
