<?php

namespace App\Services;

use App\Models\VillagePosition;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class VillagePositionService
{
    /**
     * Menampilkan data jabatan desa.
     */
    public function getData(Request $request)
    {
        $query = VillagePosition::query()
            ->select([
                'id',
                'kode',
                'nama',
                'is_active',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);

        // Filter status jabatan.
        $query->when(
            $request->filled('status')
                && $request->input('status') !== 'all',
            fn($query) => $query->where(
                'is_active',
                $request->input('status') === 'active'
            )
        );

        // Filter data terhapus.
        $query->when(
            $request->input('deleted') === 'trashed',
            fn($query) => $query->onlyTrashed()
        );

        $query->latest('created_at');

        return DataTables::eloquent($query)
            ->addIndexColumn()

            ->editColumn(
                'kode',
                fn($row) => $row->kode ?: '-'
            )

            ->editColumn(
                'nama',
                fn($row) => $this->tooltip($row->nama)
            )

            ->editColumn(
                'is_active',
                fn($row) => view('components.badge', [
                    'color' => $row->is_active ? 'success' : 'secondary',
                    'slot' => $row->is_active ? 'Aktif' : 'Tidak Aktif',
                ])->render()
            )

            ->addColumn(
                'action',
                fn($row) => view('pages.datamaster.villageposition.comp.actions', [
                    'row' => $row,
                ])->render()
            )

            ->rawColumns([
                'nama',
                'is_active',
                'action',
            ])
            ->toJson();
    }

    /**
     * Menampilkan tooltip.
     */
    private function tooltip(?string $value): string
    {
        return view('components.tooltip', [
            'value' => $value,
        ])->render();
    }

    /**
     * Menambahkan jabatan desa.
     */
    public function getStore(array $data): JsonResponse
    {
        try {
            $villagePosition = DB::transaction(function () use ($data) {
                return VillagePosition::create([
                    'kode' => $data['kode'],
                    'nama' => $data['nama'],
                    'is_active' => $data['is_active'],
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data jabatan berhasil ditambahkan.',
                'data' => $villagePosition,
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Gagal menambahkan data jabatan.', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data jabatan gagal ditambahkan.',
            ], 500);
        }
    }

    /**
     * Memperbarui jabatan desa.
     */
    public function getUpdate(VillagePosition $villagePosition, array $data): JsonResponse
    {
        try {
            DB::transaction(function () use ($villagePosition, $data) {
                $villagePosition->update([
                    'kode' => $data['kode'],
                    'nama' => $data['nama'],
                    'is_active' => $data['is_active'],
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data jabatan berhasil diperbarui.',
                'data' => $villagePosition->fresh(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal memperbarui data jabatan.', [
                'village_position_id' => $villagePosition->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data jabatan gagal diperbarui.',
            ], 500);
        }
    }

    /**
     * Menghapus jabatan desa.
     */
    public function getDestroy(Request $request, VillagePosition $villagePosition): JsonResponse
    {
        $forceDelete = $request->boolean('confirm_delete');
        try {
            if ($villagePosition->trashed() && ! $forceDelete) {
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

            DB::transaction(function () use ($villagePosition, $forceDelete) {
                $deleted = $forceDelete
                    ? $villagePosition->forceDelete()
                    : $villagePosition->delete();

                if (! $deleted) {
                    throw new \RuntimeException(
                        'Data jabatan gagal dihapus.'
                    );
                }
            });

            return response()->json([
                'success' => true,
                'message' => $forceDelete
                    ? 'Data jabatan berhasil dihapus permanen.'
                    : 'Data jabatan berhasil dipindahkan ke sampah.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal menghapus data jabatan.', [
                'village_position_id' => $villagePosition->id,
                'force_delete' => $forceDelete,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data jabatan gagal dihapus.',
            ], 500);
        }
    }

    /**
     * Mengubah status aktif jabatan.
     */
    public function getToggleStatus(VillagePosition $villagePosition): JsonResponse
    {
        try {
            DB::transaction(function () use ($villagePosition) {
                $villagePosition->update([
                    'is_active' => ! $villagePosition->is_active,
                ]);
            });

            $villagePosition->refresh();

            return response()->json([
                'success' => true,
                'message' => $villagePosition->is_active
                    ? 'Jabatan berhasil diaktifkan.'
                    : 'Jabatan berhasil dinonaktifkan.',
                'data' => $villagePosition,
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal mengubah status jabatan.', [
                'village_position_id' => $villagePosition->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Status jabatan gagal diperbarui.',
            ], 500);
        }
    }
}
