<?php

namespace App\Services;

use App\Models\VillageAsset;
use App\Models\VillageTreasuryLand;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VillageTreasuryLandService
{

    public function getData(Request $request)
    {
        $query = VillageTreasuryLand::query()
            ->select([
                'id',
                'asal_tanah',
                'nomor_sertifikat',
                'kelas_tanah',
                'luas_tanah',
                'lt_asli_desa',
                'lt_pemerintah',
                'lt_provinsi',
                'lt_kabupaten',
                'lt_lainnya',
                'tgl_perolehan',
                'lt_tipe_sawah',
                'lt_tipe_tegal',
                'lt_tipe_kebun',
                'lt_tipe_tambak',
                'lt_tipe_kering',
                'patok_tanah',
                'papan_nama',
                'lokasi',
                'peruntukan',
                'mutasi',
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
                'tgl_perolehan',
                $request->input('year')
            )
        );

        $query->when(
            $request->filled('kelas') && $request->input('kelas') !== 'all',
            fn($query) => $query->where(
                'kelas_tanah',
                $request->input('kelas')
            )
        );

        // Filter data berdasarkan SoftDeletes.
        $query->when(
            $request->input('deleted') === 'trashed',
            fn($query) => $query->onlyTrashed()
        );

        $query->latest('created_at');

        // Query statistik tanah kas desa.
        $summaryQuery = VillageTreasuryLand::query()
            ->when(
                $request->filled('year')
                    && $request->input('year') !== 'all',
                fn($query) => $query->whereYear(
                    'tgl_perolehan',
                    $request->input('year')
                )
            );

        // Filter status data untuk statistik.
        $summaryQuery->when(
            $request->input('deleted') === 'trashed',
            fn($query) => $query->onlyTrashed()
        );

        $summary = $summaryQuery
            ->selectRaw('
                COUNT(*) as total_records,
                COALESCE(SUM(lt_asli_desa +lt_pemerintah + lt_provinsi + lt_kabupaten + lt_lainnya + lt_tipe_sawah + lt_tipe_tegal + lt_tipe_kebun + lt_tipe_tambak + lt_tipe_kering), 0) as total_area,
                COALESCE(SUM(CASE WHEN papan_nama = 1 THEN 1 ELSE 0 END), 0) as total_named,
                COALESCE(SUM(CASE WHEN patok_tanah = 1 THEN 1 ELSE 0 END), 0) as total_marked
            ')
            ->first();

        return DataTables::eloquent($query)
            ->with('summary', [
                'total_records' => (int) $summary->total_records,
                'total_area' => (float) $summary->total_area,
                'total_named' => (int) $summary->total_named,
                'total_marked' => (int) $summary->total_marked,
            ])
            ->addIndexColumn()
            ->editColumn(
                'asal_tanah',
                fn($row) => $this->tooltip(
                    $row->asal_tanah?->label() ?? '-'
                )
            )
            ->editColumn(
                'kelas_tanah',
                fn($row) => $this->tooltip(
                    $row->kelas_tanah?->code() ?? '-'
                )
            )
            ->editColumn(
                'lokasi',
                fn($row) => $this->tooltip($row->lokasi)
            )
            ->editColumn(
                'peruntukan',
                fn($row) => $this->tooltip($row->peruntukan)
            )

            // Luas tanah
            ->editColumn(
                'luas_tanah',
                fn($row) => (float) $row->luas_tanah > 0
                    ? number_format((float) $row->luas_tanah, 0, ',', '.')
                    : '-'
            )
            ->editColumn(
                'lt_asli_desa',
                fn($row) => (float) $row->lt_asli_desa > 0
                    ? number_format((float) $row->lt_asli_desa, 0, ',', '.')
                    : '-'
            )
            ->editColumn(
                'lt_pemerintah',
                fn($row) => (float) $row->lt_pemerintah > 0
                    ? number_format((float) $row->lt_pemerintah, 0, ',', '.')
                    : '-'
            )
            ->editColumn(
                'lt_provinsi',
                fn($row) => (float) $row->lt_provinsi > 0
                    ? number_format((float) $row->lt_provinsi, 0, ',', '.')
                    : '-'
            )
            ->editColumn(
                'lt_kabupaten',
                fn($row) => (float) $row->lt_kabupaten > 0
                    ? number_format((float) $row->lt_kabupaten, 0, ',', '.')
                    : '-'
            )
            ->editColumn(
                'lt_lainnya',
                fn($row) => (float) $row->lt_lainnya > 0
                    ? number_format((float) $row->lt_lainnya, 0, ',', '.')
                    : '-'
            )

            // Tanggal perolehan
            ->editColumn(
                'tgl_perolehan',
                fn($row) => $this->formatDate($row->tgl_perolehan)
            )

            // Jenis penggunaan tanah
            ->editColumn(
                'lt_tipe_sawah',
                fn($row) => (float) $row->lt_tipe_sawah > 0
                    ? number_format((float) $row->lt_tipe_sawah, 0, ',', '.')
                    : '-'
            )
            ->editColumn(
                'lt_tipe_tegal',
                fn($row) => (float) $row->lt_tipe_tegal > 0
                    ? number_format((float) $row->lt_tipe_tegal, 0, ',', '.')
                    : '-'
            )
            ->editColumn(
                'lt_tipe_kebun',
                fn($row) => (float) $row->lt_tipe_kebun > 0
                    ? number_format((float) $row->lt_tipe_kebun, 0, ',', '.')
                    : '-'
            )
            ->editColumn(
                'lt_tipe_tambak',
                fn($row) => (float) $row->lt_tipe_tambak > 0
                    ? number_format((float) $row->lt_tipe_tambak, 0, ',', '.')
                    : '-'
            )
            ->editColumn(
                'lt_tipe_kering',
                fn($row) => (float) $row->lt_tipe_kering > 0
                    ? number_format((float) $row->lt_tipe_kering, 0, ',', '.')
                    : '-'
            )

            // Kelengkapan tanah
            ->editColumn('patok_tanah', function ($row) {
                return $row->patok_tanah
                    ? '<i class="fa-solid fa-circle-check text-success"></i>'
                    : '-';
            })
            ->addColumn('patok_tanah_tidak', function ($row) {
                return ! $row->patok_tanah
                    ? '<i class="fa-solid fa-circle-xmark text-danger"></i>'
                    : '-';
            })
            ->editColumn('papan_nama', function ($row) {
                return $row->papan_nama
                    ? '<i class="fa-solid fa-circle-check text-success"></i>'
                    : '-';
            })
            ->addColumn('papan_nama_tidak', function ($row) {
                return ! $row->papan_nama
                    ? '<i class="fa-solid fa-circle-xmark text-danger"></i>'
                    : '-';
            })

            // Mutasi dan catatan
            ->editColumn(
                'mutasi',
                fn($row) => $this->tooltip($row->mutasi)
            )
            ->editColumn(
                'catatan',
                fn($row) => $this->tooltip($row->catatan)
            )

            // Dokumen
            ->editColumn('dokumen', function ($row) {
                if (! $row->dokumen) {
                    return '-';
                }

                return view('components.view-pdf', [
                    'src' => $row->dokumen,
                    'title' => 'Dokumen Tanah Kas Desa',
                ])->render();
            })

            // Aksi
            ->addColumn(
                'action',
                fn($row) => view('pages.villagetreasuryland.comp.actions', [
                    'row' => $row,
                ])->render()
            )

            ->rawColumns([
                'patok_tanah',
                'patok_tanah_tidak',
                'papan_nama',
                'papan_nama_tidak',
                'dokumen',
                'action',
                'lokasi',
                'mutasi',
                'catatan',
                'peruntukan',
                'asal_tanah',
                'kelas_tanah'
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
                        'village-treasury-lands',
                        'public'
                    );

                    $data['dokumen'] = $documentPath;
                }

                // Hitung total luas berdasarkan asal perolehan.
                $sourceFields = [
                    'lt_asli_desa',
                    'lt_pemerintah',
                    'lt_provinsi',
                    'lt_kabupaten',
                    'lt_lainnya',
                ];

                $data['luas_tanah'] = collect($sourceFields)
                    ->sum(fn($field) => (int) round((float) $data[$field] * 100)) / 100;
                $data['created_by'] = \Auth::id() ?? 1;
                VillageTreasuryLand::create($data);
            });

            return response()->json([
                'success' => true,
                'message' => 'Tanah kas desa berhasil ditambahkan.',
            ], 201);
        } catch (\Throwable $e) {
            if ($documentPath) {
                \Storage::disk('public')->delete($documentPath);
            }

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan tanah kas desa.',
            ], 500);
        }
    }

    public function getUpdate(VillageTreasuryLand $land, array $data): \Illuminate\Http\JsonResponse
    {
        $documentPath = null;
        $oldDocumentPath = $land->dokumen;

        try {
            \DB::transaction(function () use ($land, $data, &$documentPath) {
                // Upload dokumen baru jika tersedia.
                if (
                    isset($data['dokumen']) &&
                    $data['dokumen'] instanceof \Illuminate\Http\UploadedFile
                ) {
                    $documentPath = $data['dokumen']->store(
                        'village-treasury-lands',
                        'public'
                    );

                    $data['dokumen'] = $documentPath;
                } else {
                    // Pertahankan dokumen lama.
                    unset($data['dokumen']);
                }

                // Hitung ulang total luas berdasarkan asal perolehan.
                $sourceFields = [
                    'lt_asli_desa',
                    'lt_pemerintah',
                    'lt_provinsi',
                    'lt_kabupaten',
                    'lt_lainnya',
                ];

                $data['luas_tanah'] = collect($sourceFields)
                    ->sum(fn($field) => (int) round((float) $data[$field] * 100)) / 100;

                $land->update($data);
            });

            // Hapus dokumen lama setelah transaksi berhasil.
            if ($documentPath && $oldDocumentPath) {
                \Storage::disk('public')->delete($oldDocumentPath);
            }

            return response()->json([
                'success' => true,
                'message' => 'Tanah kas desa berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            // Bersihkan dokumen baru jika proses update gagal.
            if ($documentPath) {
                \Storage::disk('public')->delete($documentPath);
            }

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui tanah kas desa.',
            ], 500);
        }
    }

    public function getDestroy(Request $request, VillageTreasuryLand $land): \Illuminate\Http\JsonResponse
    {
        try {
            $forceDelete = $request->boolean('confirm_delete');

            // Data trash wajib dikonfirmasi.
            if ($land->trashed() && !$forceDelete) {
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

            $documentPath = $land->dokumen;
            $deleted = \DB::transaction(function () use ($land, $forceDelete) {
                return $forceDelete
                    ? $land->forceDelete()
                    : $land->delete();
            });

            if (!$deleted) {
                throw new \RuntimeException('Data tanah kas desa gagal dihapus.');
            }

            // Hapus dokumen setelah force delete berhasil.
            if ($forceDelete && filled($documentPath)) {
                \Storage::disk('public')->delete($documentPath);
            }

            return response()->json([
                'success' => true,
                'message' => $forceDelete
                    ? 'Tanah kas desa dan dokumennya berhasil dihapus permanen.'
                    : 'Tanah kas desa berhasil dipindahkan ke sampah.',
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus tanah kas desa.',
            ], 500);
        }
    }
}
