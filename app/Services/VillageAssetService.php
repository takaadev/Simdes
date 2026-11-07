<?php

namespace App\Services;

use App\Models\VillageAsset;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VillageAssetService
{

    public function getData(Request $request)
    {
        $query = VillageAsset::query()
            ->select([
                'id',
                'nama_asset',
                'asal_beli',
                'asal_pemerintah',
                'asal_provinsi',
                'asal_kabupaten',
                'asal_sumbangan',
                'kondisi_awal_baik',
                'kondisi_awal_rusak',
                'hapus_rusak',
                'hapus_dijual',
                'hapus_disumbangkan',
                'tgl_hapus',
                'kondisi_ahir_baik',
                'kondisi_ahir_rusak',
                'catatan',
                'dokumen',
                'created_by',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);

        // Filter tahun hanya untuk tabel.
        $query->when(
            $request->filled('year')
                && $request->input('year') !== 'all',
            fn($query) => $query->whereYear(
                'created_at',
                $request->input('year')
            )
        );

        // Filter status data berdasarkan SoftDeletes.
        $query->when(
            $request->input('deleted') === 'trashed',
            fn($query) => $query->onlyTrashed()
        );

        $query->latest('created_at');

        // Query statistik inventaris.
        $summaryQuery = VillageAsset::query()
            ->when(
                $request->filled('tahun')
                    && $request->input('tahun') !== 'all',
                fn($query) => $query->whereYear(
                    'created_at',
                    $request->input('tahun')
                )
            );

        // Filter status data untuk statistik.
        $summaryQuery->when(
            $request->input('deleted') === 'trashed',
            fn($query) => $query->onlyTrashed()
        );

        $summary = $summaryQuery
            ->selectRaw('
                COUNT(*) as total_types,
                COALESCE(SUM(kondisi_ahir_baik), 0) as kondisi_baik,
                COALESCE(SUM(kondisi_ahir_rusak), 0) as kondisi_rusak,
                COALESCE(SUM(
                    hapus_rusak +
                    hapus_dijual +
                    hapus_disumbangkan
                ), 0) as total_dihapus
            ')
            ->first();

        return DataTables::eloquent($query)
            ->with('summary', [
                'total_types' => (int) $summary->total_types,
                'good' => (int) $summary->kondisi_baik,
                'damaged' => (int) $summary->kondisi_rusak,
                'deleted' => (int) $summary->total_dihapus,
            ])

            ->addIndexColumn()

            ->editColumn(
                'nama_asset',
                fn($row) => $this->tooltip($row->nama_asset)
            )

            // Asal perolehan
            ->editColumn(
                'asal_beli',
                fn($row) => number_format($row->asal_beli, 0, ',', '.')
            )
            ->editColumn(
                'asal_pemerintah',
                fn($row) => number_format($row->asal_pemerintah, 0, ',', '.')
            )
            ->editColumn(
                'asal_provinsi',
                fn($row) => number_format($row->asal_provinsi, 0, ',', '.')
            )
            ->editColumn(
                'asal_kabupaten',
                fn($row) => number_format($row->asal_kabupaten, 0, ',', '.')
            )
            ->editColumn(
                'asal_sumbangan',
                fn($row) => number_format($row->asal_sumbangan, 0, ',', '.')
            )

            // Kondisi awal
            ->editColumn(
                'kondisi_awal_baik',
                fn($row) => number_format($row->kondisi_awal_baik, 0, ',', '.')
            )
            ->editColumn(
                'kondisi_awal_rusak',
                fn($row) => number_format($row->kondisi_awal_rusak, 0, ',', '.')
            )

            // Penghapusan
            ->editColumn(
                'hapus_rusak',
                fn($row) => number_format($row->hapus_rusak, 0, ',', '.')
            )
            ->editColumn(
                'hapus_dijual',
                fn($row) => number_format($row->hapus_dijual, 0, ',', '.')
            )
            ->editColumn(
                'hapus_disumbangkan',
                fn($row) => number_format($row->hapus_disumbangkan, 0, ',', '.')
            )
            ->editColumn(
                'tgl_hapus',
                fn($row) => $this->formatDate($row->tgl_hapus)
            )

            // Kondisi akhir
            ->editColumn(
                'kondisi_ahir_baik',
                fn($row) => number_format($row->kondisi_ahir_baik, 0, ',', '.')
            )
            ->editColumn(
                'kondisi_ahir_rusak',
                fn($row) => number_format($row->kondisi_ahir_rusak, 0, ',', '.')
            )

            ->editColumn(
                'catatan',
                fn($row) => $this->tooltip($row->catatan)
            )

            ->editColumn('dokumen', function ($row) {
                if (! $row->dokumen) {
                    return '-';
                }

                return view('components.view-pdf', [
                    'src' => $row->dokumen,
                    'title' => 'Dokumen Inventaris dan Kekayaan Desa',
                ])->render();
            })

            ->addColumn(
                'action',
                fn($row) => view('pages.villageasset.comp.actions', [
                    'row' => $row,
                ])->render()
            )

            ->rawColumns([
                'nama_asset',
                'catatan',
                'dokumen',
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
        $documentPath = null;

        try {
            \DB::transaction(function () use ($data, &$documentPath) {
                // Upload dokumen.
                if (
                    isset($data['dokumen']) &&
                    $data['dokumen'] instanceof \Illuminate\Http\UploadedFile
                ) {
                    $documentPath = $data['dokumen']->store(
                        'village-assets',
                        'public'
                    );

                    $data['dokumen'] = $documentPath;
                }

                // Informasi pembuat data.
                $data['created_by'] = \Auth::id() ?? 1;

                // Insert data aset.
                VillageAsset::create($data);
            });

            return response()->json([
                'success' => true,
                'message' => 'Inventaris dan kekayaan desa berhasil ditambahkan.',
            ], 201);
        } catch (\Throwable $e) {
            // Hapus dokumen jika proses insert gagal.
            if ($documentPath) {
                \Storage::disk('public')->delete($documentPath);
            }

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan inventaris dan kekayaan desa.',
            ], 500);
        }
    }

    public function getUpdate(VillageAsset $asset, array $data): \Illuminate\Http\JsonResponse
    {
        $documentPath = null;
        $oldDocumentPath = $asset->dokumen;

        try {
            \DB::transaction(function () use (
                $asset,
                $data,
                &$documentPath
            ) {
                // Upload dokumen baru jika tersedia.
                if (
                    isset($data['dokumen']) &&
                    $data['dokumen'] instanceof \Illuminate\Http\UploadedFile
                ) {
                    $documentPath = $data['dokumen']->store(
                        'village-assets',
                        'public'
                    );

                    $data['dokumen'] = $documentPath;
                } else {
                    // Pertahankan dokumen lama.
                    unset($data['dokumen']);
                }

                // Update data aset.
                $asset->update($data);
            });

            // Hapus dokumen lama setelah transaksi berhasil.
            if ($documentPath && $oldDocumentPath) {
                \Storage::disk('public')->delete($oldDocumentPath);
            }

            return response()->json([
                'success' => true,
                'message' => 'Inventaris dan kekayaan desa berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            // Bersihkan dokumen baru jika proses update gagal.
            if ($documentPath) {
                \Storage::disk('public')->delete($documentPath);
            }

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui inventaris dan kekayaan desa.',
            ], 500);
        }
    }

    public function getDestroy(Request $request, VillageAsset $asset)
    {
        try {
            $forceDelete = $request->boolean('confirm_delete');

            // Data Trash wajib dikonfirmasi.
            if ($asset->trashed() && !$forceDelete) {
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

            $documentPath = $asset->dokumen;

            $deleted = \DB::transaction(function () use ($asset, $forceDelete) {
                return $forceDelete
                    ? $asset->forceDelete()
                    : $asset->delete();
            });

            if (!$deleted) {
                throw new \RuntimeException('Data inventaris gagal dihapus.');
            }

            // Hapus dokumen setelah force delete berhasil.
            if ($forceDelete && filled($documentPath)) {
                \Storage::disk('public')->delete($documentPath);
            }

            return response()->json([
                'success' => true,
                'message' => $forceDelete
                    ? 'Inventaris dan dokumennya berhasil dihapus permanen.'
                    : 'Inventaris berhasil dipindahkan ke sampah.',
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus inventaris dan kekayaan desa.',
            ], 500);
        }
    }
}
