<?php

namespace App\Services;

use App\Http\Requests\Resident\ImportExcelResidentRequest;
use App\Imports\ResidentImport;
use App\Models\Resident;
use App\Models\ResidentMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ResidentMutationService
{




    public function getData(Request $request)
    {
        // Filter data mutasi penduduk.
        $applyFilters = function ($query) use ($request) {
            // Filter jenis kelamin.
            $query->when(
                $request->filled('jenis_kelamin')
                    && $request->input('jenis_kelamin') !== 'all',
                fn($query) => $query->where(
                    'jenis_kelamin',
                    $request->input('jenis_kelamin')
                )
            );

            // Filter kewarganegaraan.
            $query->when(
                $request->filled('kewarganegaraan')
                    && $request->input('kewarganegaraan') !== 'all',
                fn($query) => $query->where(
                    'kewarganegaraan',
                    $request->input('kewarganegaraan')
                )
            );

            // Filter kategori mutasi.
            $query->when(
                $request->filled('kategori_mutasi')
                    && $request->input('kategori_mutasi') !== 'all',
                fn($query) => $query->where(
                    'kategori_mutasi',
                    $request->input('kategori_mutasi')
                )
            );

            // Filter tipe mutasi.
            $query->when(
                $request->filled('tipe_mutasi')
                    && $request->input('tipe_mutasi') !== 'all',
                fn($query) => $query->where(
                    'tipe_mutasi',
                    $request->input('tipe_mutasi')
                )
            );

            // Filter data terhapus.
            $query->when(
                $request->input('deleted') === 'trashed',
                fn($query) => $query->onlyTrashed()
            );

            return $query;
        };

        // Query utama mutasi penduduk.
        $query = $applyFilters(
            ResidentMutation::query()->select([
                'id',
                'nama_lengkap',
                'tempat_lahir',
                'tgl_lahir',
                'jenis_kelamin',
                'kewarganegaraan',
                'kebangsaan',
                'tipe_mutasi',
                'kategori_mutasi',
                'tgl_mutasi',
                'tujuan_mutasi',
                'catatan',
                'resident_id',
                'recorded_by',
                'created_at',
                'updated_at',
                'deleted_at',
            ])
        );

        // Pengurutan data.
        $query->when(
            in_array($request->input('sort_name'), ['asc', 'desc'], true),
            fn($query) => $query->orderBy(
                'nama_lengkap',
                $request->input('sort_name')
            ),
            fn($query) => $query->latest('tgl_mutasi')
        );

        // Query statistik mutasi.
        $summary = $applyFilters(
            ResidentMutation::query()
        )
            ->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN jenis_kelamin = 'L' THEN 1 ELSE 0 END) as male,
            SUM(CASE WHEN jenis_kelamin = 'P' THEN 1 ELSE 0 END) as female,
            SUM(CASE WHEN kewarganegaraan = 'WNI' THEN 1 ELSE 0 END) as wni,
            SUM(CASE WHEN kewarganegaraan = 'WNA' THEN 1 ELSE 0 END) as wna,
            SUM(CASE WHEN tipe_mutasi = 'add' THEN 1 ELSE 0 END) as addition,
            SUM(CASE WHEN tipe_mutasi = 'minus' THEN 1 ELSE 0 END) as reduction,
            SUM(CASE WHEN kategori_mutasi = 'death' THEN 1 ELSE 0 END) as death,
            SUM(CASE WHEN kategori_mutasi = 'birth' THEN 1 ELSE 0 END) as birth,
            SUM(CASE WHEN kategori_mutasi = 'incoming' THEN 1 ELSE 0 END) as incoming,
            SUM(CASE WHEN kategori_mutasi = 'outgoing' THEN 1 ELSE 0 END) as outgoing
        ")
            ->first();

        return DataTables::eloquent($query)
            ->with('summary', [
                'total' => (int) $summary->total,
                'male' => (int) $summary->male,
                'female' => (int) $summary->female,
                'wni' => (int) $summary->wni,
                'wna' => (int) $summary->wna,
                'addition' => (int) $summary->addition,
                'reduction' => (int) $summary->reduction,
                'death' => (int) $summary->death,
                'birth' => (int) $summary->birth,
                'incoming' => (int) $summary->incoming,
                'outgoing' => (int) $summary->outgoing,
            ])
            ->addIndexColumn()

            ->editColumn(
                'nama_lengkap',
                fn($row) => $this->tooltip($row->nama_lengkap)
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
                'jenis_kelamin',
                fn($row) => $row->jenis_kelamin?->label() ?? '-'
            )

            ->editColumn(
                'kewarganegaraan',
                fn($row) => $row->kewarganegaraan?->label() ?? '-'
            )

            ->editColumn(
                'kebangsaan',
                fn($row) => $row->kebangsaan ?: '-'
            )

            // Kolom Datang Dari.
            ->addColumn('datang_dari', function ($row) {
                if ($row->tipe_mutasi !== \App\Enums\MutationTypeList::ADD) {
                    return '-';
                }

                return match ($row->kategori_mutasi) {
                    \App\Enums\MutationCategoryList::BIRTH => 'Kelahiran',
                    \App\Enums\MutationCategoryList::INCOMING => $row->tujuan_mutasi ?: '-',
                    default => '-',
                };
            })

            // Tanggal Penambahan.
            ->addColumn('tanggal_penambahan', function ($row) {
                return $row->tipe_mutasi === \App\Enums\MutationTypeList::ADD
                    ? $this->formatDate($row->tgl_mutasi)
                    : '-';
            })

            // Kolom Pindah Ke.
            ->addColumn('pindah_ke', function ($row) {
                return (
                    $row->tipe_mutasi === \App\Enums\MutationTypeList::MINUS
                    && $row->kategori_mutasi === \App\Enums\MutationCategoryList::OUTGOING
                )
                    ? ($row->tujuan_mutasi ?: '-')
                    : '-';
            })

            // Tanggal Pindah.
            ->addColumn('tanggal_pindah', function ($row) {
                return (
                    $row->tipe_mutasi === \App\Enums\MutationTypeList::MINUS
                    && $row->kategori_mutasi === \App\Enums\MutationCategoryList::OUTGOING
                )
                    ? $this->formatDate($row->tgl_mutasi)
                    : '-';
            })

            // Keterangan Meninggal.
            ->addColumn('meninggal', function ($row) {
                return (
                    $row->tipe_mutasi === \App\Enums\MutationTypeList::MINUS
                    && $row->kategori_mutasi === \App\Enums\MutationCategoryList::DEATH
                )
                    ? 'Meninggal'
                    : '-';
            })

            // Tanggal Meninggal.
            ->addColumn('tanggal_meninggal', function ($row) {
                return (
                    $row->tipe_mutasi === \App\Enums\MutationTypeList::MINUS
                    && $row->kategori_mutasi === \App\Enums\MutationCategoryList::DEATH
                )
                    ? $this->formatDate($row->tgl_mutasi)
                    : '-';
            })

            ->editColumn(
                'tipe_mutasi',
                fn($row) => view('components.badge', [
                    'color' => match ($row->tipe_mutasi) {
                        \App\Enums\MutationTypeList::ADD => 'success',
                        \App\Enums\MutationTypeList::MINUS => 'warning',
                        default => 'secondary',
                    },
                    'slot' => $row->tipe_mutasi?->label() ?? '-',
                ])->render()
            )

            ->editColumn(
                'kategori_mutasi',
                fn($row) => $row->kategori_mutasi?->label() ?? '-'
            )

            ->editColumn(
                'tgl_mutasi',
                fn($row) => $this->formatDate($row->tgl_mutasi)
            )

            ->editColumn(
                'tujuan_mutasi',
                fn($row) => $row->tujuan_mutasi ?: '-'
            )

            ->editColumn(
                'catatan',
                fn($row) => $this->tooltip($row->catatan)
            )

            ->addColumn(
                'action',
                fn($row) => view('pages.mutation.comp.actions', [
                    'row' => $row,
                ])->render()
            )

            ->rawColumns([
                'nama_lengkap',
                'tipe_mutasi',
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

    public function getStore(array $data): \Illuminate\Http\JsonResponse
    {
        try {
            $mutation = \DB::transaction(function () use ($data) {
                $data['recorded_by'] = auth()->id();

                return ResidentMutation::create($data);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data mutasi penduduk berhasil ditambahkan.',
                'data' => $mutation,
            ], 201);
        } catch (\Throwable $e) {
            \Log::error('Gagal menambahkan data mutasi penduduk.', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data mutasi penduduk gagal ditambahkan.',
            ], 500);
        }
    }

    public function getUpdate(ResidentMutation $mutation, array $data): \Illuminate\Http\JsonResponse
    {
        try {
            \DB::transaction(function () use ($mutation, $data) {
                $mutation->update($data);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data mutasi penduduk berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            \Log::error('Gagal memperbarui data mutasi penduduk.', [
                'mutation_id' => $mutation->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data mutasi penduduk.',
            ], 500);
        }
    }

    public function getDestroy(Request $request, ResidentMutation $mutation): \Illuminate\Http\JsonResponse
    {
        try {
            $forceDelete = $request->boolean('confirm_delete');

            if ($mutation->trashed() && !$forceDelete) {
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
                $mutation,
                $forceDelete
            ) {
                return $forceDelete
                    ? $mutation->forceDelete()
                    : $mutation->delete();
            });

            if (!$deleted) {
                throw new \RuntimeException('Data mutasi penduduk gagal dihapus.');
            }

            return response()->json([
                'success' => true,
                'message' => $forceDelete
                    ? 'Data mutasi penduduk berhasil dihapus permanen.'
                    : 'Data mutasi penduduk berhasil dipindahkan ke sampah.',
            ]);
        } catch (\Throwable $e) {
            \Log::error('Gagal menghapus data mutasi penduduk.', [
                'mutation_id' => $mutation->id,
                'force_delete' => $forceDelete ?? false,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data mutasi penduduk.',
            ], 500);
        }
    }

    public function getImportExcel(ImportExcelResidentRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $import = new ResidentImport();
            Excel::import($import, $request->file('file'));
            $errors = $import->getErrors();
            // Batalkan seluruh proses jika ditemukan kesalahan validasi.
            if (!empty($errors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Import dibatalkan. Terdapat ' . count($errors) . ' kesalahan pada data Excel.',
                    'total_errors' => count($errors),
                    'errors' => $errors,
                ], 422);
            }

            $rows = $import->getValidRows();

            // Pastikan terdapat data yang dapat diimport.
            if (empty($rows)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data penduduk yang dapat diimport.',
                ], 422);
            }

            // Simpan seluruh data dalam satu transaksi.
            DB::transaction(function () use ($rows) {
                foreach ($rows as $row) {
                    Resident::forceCreate($row);
                }
            });

            return response()->json([
                'success' => true,
                'message' => count($rows) . ' data penduduk berhasil diimport.',
                'total_imported' => count($rows),
            ]);
        } catch (\Throwable $e) {
            report($e);

            logger()->error('Import Excel Penduduk Gagal', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Import gagal. Tidak ada data yang berhasil disimpan.',
            ], 500);
        }
    }
}
