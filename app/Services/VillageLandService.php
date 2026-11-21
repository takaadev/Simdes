<?php

namespace App\Services;

use App\Models\VillageLand;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class VillageLandService
{
    public function getData(Request $request)
    {
        // Mapping kolom Status Hak Tanah.
        $hakCertifiedCols = ['hm', 'hgb', 'hp', 'hgu', 'hpl'];
        $hakUncertifiedCols = ['ma', 'vi', 'tn'];
        $hakAllowedCols = array_merge($hakCertifiedCols, $hakUncertifiedCols);

        // Mapping nilai option Penggunaan Tanah (camelCase & snake_case) ke nama kolom DB.
        $usageNonAgriCols = ['perumahan', 'dagang_jasa', 'perkantoran', 'industri', 'fasilitas_umum'];
        $usageAgriCols = ['sawah', 'tegalan', 'perkebunan', 'peternakan_perikanan', 'hutan_belukar', 'hutan_lebat'];
        $usageOtherCols = ['tanah_kosong', 'lainnya'];

        $usageColumnMap = [
            'perumahan' => 'perumahan',
            'perdaganganJasa' => 'dagang_jasa',
            'dagang_jasa' => 'dagang_jasa',
            'perkantoran' => 'perkantoran',
            'industri' => 'industri',
            'fasilitasUmum' => 'fasilitas_umum',
            'fasilitas_umum' => 'fasilitas_umum',
            'sawah' => 'sawah',
            'tegalan' => 'tegalan',
            'perkebunan' => 'perkebunan',
            'peternakanPerikanan' => 'peternakan_perikanan',
            'peternakan_perikanan' => 'peternakan_perikanan',
            'hutanBelukar' => 'hutan_belukar',
            'hutan_belukar' => 'hutan_belukar',
            'hutanLebatLindung' => 'hutan_lebat',
            'hutan_lebat' => 'hutan_lebat',
            'tanahKosong' => 'tanah_kosong',
            'tanah_kosong' => 'tanah_kosong',
            'lainLain' => 'lainnya',
            'lainnya' => 'lainnya',
        ];

        $statusHak = (string) $request->input('status_hak', 'ALL');
        $penggunaan = (string) $request->input('penggunaan', 'ALL');
        $deleted = (string) $request->input('deleted', 'active');

        // Base query agar filter tabel & summary selalu sinkron.
        $baseQuery = VillageLand::query()
            // 1. Filter Status Data (SoftDeletes).
            ->when(
                $deleted === 'trashed',
                fn($q) => $q->onlyTrashed()
            )
            ->when(
                $deleted === 'all',
                fn($q) => $q->withTrashed()
            )
            // 2. Filter Status Hak Tanah.
            ->when(
                $statusHak !== '' && strtoupper($statusHak) !== 'ALL',
                function ($q) use ($statusHak, $hakCertifiedCols, $hakUncertifiedCols, $hakAllowedCols) {
                    if ($statusHak === 'GROUP_SUDAH') {
                        $q->where(function ($sub) use ($hakCertifiedCols) {
                            foreach ($hakCertifiedCols as $col) {
                                $sub->orWhere($col, '>', 0);
                            }
                        });
                    } elseif ($statusHak === 'GROUP_BELUM') {
                        $q->where(function ($sub) use ($hakUncertifiedCols) {
                            foreach ($hakUncertifiedCols as $col) {
                                $sub->orWhere($col, '>', 0);
                            }
                        });
                    } elseif (in_array($statusHak, $hakAllowedCols, true)) {
                        $q->where($statusHak, '>', 0);
                    }
                }
            )
            // 3. Filter Penggunaan Tanah.
            ->when(
                $penggunaan !== '' && strtoupper($penggunaan) !== 'ALL',
                function ($q) use ($penggunaan, $usageNonAgriCols, $usageAgriCols, $usageOtherCols, $usageColumnMap) {
                    if ($penggunaan === 'GROUP_NON_PERTANIAN') {
                        $q->where(function ($sub) use ($usageNonAgriCols) {
                            foreach ($usageNonAgriCols as $col) {
                                $sub->orWhere($col, '>', 0);
                            }
                        });
                    } elseif ($penggunaan === 'GROUP_PERTANIAN') {
                        $q->where(function ($sub) use ($usageAgriCols) {
                            foreach ($usageAgriCols as $col) {
                                $sub->orWhere($col, '>', 0);
                            }
                        });
                    } elseif ($penggunaan === 'GROUP_LAINNYA') {
                        $q->where(function ($sub) use ($usageOtherCols) {
                            foreach ($usageOtherCols as $col) {
                                $sub->orWhere($col, '>', 0);
                            }
                        });
                    } elseif (isset($usageColumnMap[$penggunaan])) {
                        $q->where($usageColumnMap[$penggunaan], '>', 0);
                    }
                }
            );

        // Hitung ringkasan dari baseQuery yang sama.
        $summary = (clone $baseQuery)
            ->reorder()
            ->selectRaw("
            COUNT(*) AS subjek_kepemilikan,
            COALESCE(SUM(jml), 0) AS jumlah_luas_tercatat,
            COALESCE(SUM(hm + hgb + hp + hgu + hpl), 0) AS sudah_bersertifikat,
            COALESCE(SUM(sawah + tegalan + perkebunan + peternakan_perikanan + hutan_belukar + hutan_lebat), 0) AS penggunaan_pertanian
        ")
            ->first();

        // Query utama DataTables.
        $query = (clone $baseQuery)
            ->select([
                'id',
                'owner_name',
                'jml',
                'hm',
                'hgb',
                'hp',
                'hgu',
                'hpl',
                'ma',
                'vi',
                'tn',
                'perumahan',
                'dagang_jasa',
                'perkantoran',
                'industri',
                'fasilitas_umum',
                'sawah',
                'tegalan',
                'perkebunan',
                'peternakan_perikanan',
                'hutan_belukar',
                'hutan_lebat',
                'mutasi_tanah',
                'tanah_kosong',
                'lainnya',
                'catatan',
                'created_by',
                'created_at',
                'updated_at',
                'deleted_at',
            ])
            ->when(
                !$request->filled('order'),
                fn($q) => $q->latest('created_at')
            );

        // Helper format angka luas (tampilkan '-' jika 0 agar tabel tidak penuh nol).
        $formatArea = fn($value) => (float) $value > 0
            ? number_format((float) $value, 2, ',', '.')
            : '-';

        $areaColumns = [
            'jml',
            'hm',
            'hgb',
            'hp',
            'hgu',
            'hpl',
            'ma',
            'vi',
            'tn',
            'perumahan',
            'dagang_jasa',
            'perkantoran',
            'industri',
            'fasilitas_umum',
            'sawah',
            'tegalan',
            'perkebunan',
            'peternakan_perikanan',
            'hutan_belukar',
            'hutan_lebat',
            'tanah_kosong',
            'lainnya',
            'mutasi_tanah',
        ];

        $dataTable = DataTables::eloquent($query)
            ->with('summary', [
                'subjek_kepemilikan' => (int) ($summary?->subjek_kepemilikan ?? 0),
                'jumlah_luas_tercatat' => round((float) ($summary?->jumlah_luas_tercatat ?? 0), 2),
                'sudah_bersertifikat' => round((float) ($summary?->sudah_bersertifikat ?? 0), 2),
                'penggunaan_pertanian' => round((float) ($summary?->penggunaan_pertanian ?? 0), 2),
            ])
            ->filter(function ($query) use ($request) {
                $search = trim((string) $request->input('search.value', ''));

                if ($search !== '') {
                    $query->where(function ($q) use ($search) {
                        $q->where('owner_name', 'like', "%{$search}%")
                            ->orWhere('catatan', 'like', "%{$search}%");
                    });
                }
            })
            ->addIndexColumn()
            ->editColumn('owner_name', fn($row) => $this->tooltip($row->owner_name ?: '-'))
            ->editColumn('catatan', fn($row) => $this->tooltip($row->catatan ?: '-'));

        foreach ($areaColumns as $col) {
            $dataTable->editColumn($col, fn($row) => $formatArea($row->{$col}));
        }

        return $dataTable
            ->addColumn(
                'action',
                fn($row) => view('pages.villageland.comp.actions', ['row' => $row])->render()
            )
            ->rawColumns([
                'owner_name',
                'catatan',
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
        try {
            DB::transaction(function () use ($data) {
                // Hitung jumlah luas berdasarkan status hak tanah.
                $sourceFields = [
                    'hm',
                    'hgb',
                    'hp',
                    'hgu',
                    'hpl',
                    'ma',
                    'vi',
                    'tn',
                ];

                $data['jml'] = collect($sourceFields)
                    ->sum(fn($field) => (float) ($data[$field] ?? 0));

                $data['created_by'] = Auth::id();

                VillageLand::create($data);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data tanah desa berhasil ditambahkan.',
            ], 201);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data tanah desa.',
            ], 500);
        }
    }

    public function getUpdate(VillageLand $land, array $data): JsonResponse
    {
        try {
            DB::transaction(function () use ($land, $data) {
                // Hitung ulang jumlah luas berdasarkan status hak tanah.
                $sourceFields = [
                    'hm',
                    'hgb',
                    'hp',
                    'hgu',
                    'hpl',
                    'ma',
                    'vi',
                    'tn',
                ];

                $data['jml'] = collect($sourceFields)
                    ->sum(fn($field) => (float) ($data[$field] ?? 0));

                $land->update($data);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data tanah desa berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data tanah desa.',
            ], 500);
        }
    }

    public function getDestroy(Request $request, VillageLand $land): JsonResponse
    {
        try {
            $forceDelete = $request->boolean('confirm_delete');

            // Data trash wajib dikonfirmasi untuk penghapusan permanen.
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

            $deleted = DB::transaction(function () use ($land, $forceDelete) {
                return $forceDelete
                    ? $land->forceDelete()
                    : $land->delete();
            });

            if (!$deleted) {
                throw new \RuntimeException('Data tanah desa gagal dihapus.');
            }

            return response()->json([
                'success' => true,
                'message' => $forceDelete
                    ? 'Data tanah desa berhasil dihapus permanen.'
                    : 'Data tanah desa berhasil dipindahkan ke sampah.',
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data tanah desa.',
            ], 500);
        }
    }


    public function getPrintData(Request $request)
    {
        $hakCertifiedCols = ['hm', 'hgb', 'hp', 'hgu', 'hpl'];
        $hakUncertifiedCols = ['ma', 'vi', 'tn'];
        $hakAllowedCols = array_merge($hakCertifiedCols, $hakUncertifiedCols);

        $usageNonAgriCols = ['perumahan', 'dagang_jasa', 'perkantoran', 'industri', 'fasilitas_umum'];
        $usageAgriCols = ['sawah', 'tegalan', 'perkebunan', 'peternakan_perikanan', 'hutan_belukar', 'hutan_lebat'];
        $usageOtherCols = ['tanah_kosong', 'lainnya'];

        $usageColumnMap = [
            'perumahan' => 'perumahan',
            'perdaganganJasa' => 'dagang_jasa',
            'dagang_jasa' => 'dagang_jasa',
            'perkantoran' => 'perkantoran',
            'industri' => 'industri',
            'fasilitasUmum' => 'fasilitas_umum',
            'fasilitas_umum' => 'fasilitas_umum',
            'sawah' => 'sawah',
            'tegalan' => 'tegalan',
            'perkebunan' => 'perkebunan',
            'peternakanPerikanan' => 'peternakan_perikanan',
            'peternakan_perikanan' => 'peternakan_perikanan',
            'hutanBelukar' => 'hutan_belukar',
            'hutan_belukar' => 'hutan_belukar',
            'hutanLebatLindung' => 'hutan_lebat',
            'hutan_lebat' => 'hutan_lebat',
            'tanahKosong' => 'tanah_kosong',
            'tanah_kosong' => 'tanah_kosong',
            'lainLain' => 'lainnya',
            'lainnya' => 'lainnya',
        ];

        $statusHak = (string) $request->input('status_hak', 'ALL');
        $penggunaan = (string) $request->input('penggunaan', 'ALL');
        $deleted = (string) $request->input('deleted', 'active');
        $search = trim((string) $request->input('search', ''));

        return VillageLand::query()
            ->when($deleted === 'trashed', fn($q) => $q->onlyTrashed())
            ->when(
                $statusHak !== '' && strtoupper($statusHak) !== 'ALL',
                function ($q) use ($statusHak, $hakCertifiedCols, $hakUncertifiedCols, $hakAllowedCols) {
                    if ($statusHak === 'GROUP_SUDAH') {
                        $q->where(function ($sub) use ($hakCertifiedCols) {
                            foreach ($hakCertifiedCols as $col) {
                                $sub->orWhere($col, '>', 0);
                            }
                        });
                    } elseif ($statusHak === 'GROUP_BELUM') {
                        $q->where(function ($sub) use ($hakUncertifiedCols) {
                            foreach ($hakUncertifiedCols as $col) {
                                $sub->orWhere($col, '>', 0);
                            }
                        });
                    } elseif (in_array($statusHak, $hakAllowedCols, true)) {
                        $q->where($statusHak, '>', 0);
                    }
                }
            )
            ->when(
                $penggunaan !== '' && strtoupper($penggunaan) !== 'ALL',
                function ($q) use ($penggunaan, $usageNonAgriCols, $usageAgriCols, $usageOtherCols, $usageColumnMap) {
                    if ($penggunaan === 'GROUP_NON_PERTANIAN') {
                        $q->where(function ($sub) use ($usageNonAgriCols) {
                            foreach ($usageNonAgriCols as $col) {
                                $sub->orWhere($col, '>', 0);
                            }
                        });
                    } elseif ($penggunaan === 'GROUP_PERTANIAN') {
                        $q->where(function ($sub) use ($usageAgriCols) {
                            foreach ($usageAgriCols as $col) {
                                $sub->orWhere($col, '>', 0);
                            }
                        });
                    } elseif ($penggunaan === 'GROUP_LAINNYA') {
                        $q->where(function ($sub) use ($usageOtherCols) {
                            foreach ($usageOtherCols as $col) {
                                $sub->orWhere($col, '>', 0);
                            }
                        });
                    } elseif (isset($usageColumnMap[$penggunaan])) {
                        $q->where($usageColumnMap[$penggunaan], '>', 0);
                    }
                }
            )
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('owner_name', 'like', "%{$search}%")
                        ->orWhere('catatan', 'like', "%{$search}%");
                });
            })
            ->orderBy('owner_name')
            ->get();
    }

    public function getFilterSummary(Request $request): ?string
    {
        $parts = [];

        if ($request->filled('status_hak') && strtoupper($request->input('status_hak')) !== 'ALL') {
            $parts[] = 'Status Hak: ' . $request->input('status_hak');
        }
        if ($request->filled('penggunaan') && strtoupper($request->input('penggunaan')) !== 'ALL') {
            $parts[] = 'Penggunaan: ' . $request->input('penggunaan');
        }
        if ($request->input('deleted') === 'trashed') {
            $parts[] = 'Status Data: Dihapus';
        }

        return $parts ? implode(' | ', $parts) : null;
    }
}
