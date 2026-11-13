<?php

namespace App\Services;

use App\Http\Requests\Resident\ImportExcelResidentRequest;
use App\Imports\ResidentImport;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ResidentService
{

    public function getData(Request $request)
    {
        // Filter data penduduk.
        $applyFilters = function ($query) use ($request) {
            $query->when(
                $request->filled('jenis_kelamin')
                    && $request->input('jenis_kelamin') !== 'all',
                fn($query) => $query->where(
                    'jenis_kelamin',
                    $request->input('jenis_kelamin')
                )
            );

            $query->when(
                $request->filled('kewarganegaraan')
                    && $request->input('kewarganegaraan') !== 'all',
                fn($query) => $query->where(
                    'kewarganegaraan',
                    $request->input('kewarganegaraan')
                )
            );

            $query->when(
                $request->input('deleted') === 'trashed',
                fn($query) => $query->onlyTrashed()
            );

            return $query;
        };

        // Query utama penduduk.
        $query = $applyFilters(
            Resident::query()->select([
                'id',
                'nama_lengkap',
                'jenis_kelamin',
                'status_perkawinan',
                'tempat_lahir',
                'tgl_lahir',
                'agama',
                'pendidikan',
                'pekerjaan',
                'dapat_membaca',
                'kewarganegaraan',
                'status_dalam_keluarga',
                'nik',
                'kk',
                'alamat',
                'rt',
                'rw',
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
            fn($query) => $query->orderBy('nama_lengkap')
        );

        // Query statistik penduduk.
        $summary = $applyFilters(Resident::query())
            ->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN jenis_kelamin = 'L' THEN 1 ELSE 0 END) as male,
            SUM(CASE WHEN jenis_kelamin = 'P' THEN 1 ELSE 0 END) as female,
            SUM(CASE WHEN kewarganegaraan = 'WNI' THEN 1 ELSE 0 END) as wni,
            SUM(CASE WHEN kewarganegaraan = 'WNA' THEN 1 ELSE 0 END) as wna
        ")
            ->first();

        return DataTables::eloquent($query)
            ->with('summary', [
                'total' => (int) $summary->total,
                'male' => (int) $summary->male,
                'female' => (int) $summary->female,
                'wni' => (int) $summary->wni,
                'wna' => (int) $summary->wna,
            ])
            ->addIndexColumn()

            ->editColumn(
                'nama_lengkap',
                fn($row) => $this->tooltip($row->nama_lengkap)
            )

            ->editColumn(
                'jenis_kelamin',
                fn($row) => $row->jenis_kelamin?->label() ?? '-'
            )

            ->editColumn(
                'status_perkawinan',
                fn($row) => $row->status_perkawinan?->label() ?? '-'
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
                'agama',
                fn($row) => $row->agama?->label() ?? '-'
            )

            ->editColumn(
                'pendidikan',
                fn($row) => $row->pendidikan?->label() ?? '-'
            )

            ->editColumn(
                'pekerjaan',
                fn($row) => $row->pekerjaan?->label() ?? '-'
            )

            ->editColumn(
                'dapat_membaca',
                fn($row) => $row->dapat_membaca ? 'Ya' : 'Tidak'
            )

            ->editColumn(
                'kewarganegaraan',
                fn($row) => $row->kewarganegaraan?->label() ?? '-'
            )

            ->editColumn(
                'status_dalam_keluarga',
                fn($row) => $row->status_dalam_keluarga ?: '-'
            )

            ->editColumn(
                'nik',
                fn($row) => $row->nik ?: '-'
            )

            ->editColumn(
                'kk',
                fn($row) => $row->kk ?: '-'
            )

            // Alamat beserta RT dan RW.
            ->editColumn('alamat', function ($row) {
                $alamat = collect([
                    $row->alamat,
                    $row->rt ? "RT {$row->rt}" : null,
                    $row->rw ? "RW {$row->rw}" : null,
                ])->filter()->implode(', ');

                return $this->tooltip($alamat ?: '-');
            })

            // Action.
            ->addColumn(
                'action',
                fn($row) => view('pages.resident.comp.actions', [
                    'row' => $row,
                ])->render()
            )

            ->rawColumns([
                'nama_lengkap',
                'alamat',
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
            $resident = \DB::transaction(function () use ($data) {
                $data['created_by'] = auth()->id();

                return Resident::create($data);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data penduduk berhasil ditambahkan.',
                'data' => $resident,
            ], 201);
        } catch (\Throwable $e) {
            \Log::error('Gagal menambahkan data penduduk.', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data penduduk gagal ditambahkan.',
            ], 500);
        }
    }

    public function getUpdate(Resident $resident, array $data): \Illuminate\Http\JsonResponse
    {
        try {
            \DB::transaction(function () use ($resident, $data) {
                $resident->update($data);
            });
            return response()->json([
                'success' => true,
                'message' => 'Data penduduk berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            \Log::error('Gagal memperbarui data penduduk.', [
                'resident_id' => $resident->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data penduduk.',
            ], 500);
        }
    }

    public function getDestroy(Request $request, Resident $resident): \Illuminate\Http\JsonResponse
    {
        try {
            $forceDelete = $request->boolean('confirm_delete');
            if ($resident->trashed() && !$forceDelete) {
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
                $resident,
                $forceDelete
            ) {
                return $forceDelete
                    ? $resident->forceDelete()
                    : $resident->delete();
            });

            if (!$deleted) {
                throw new \RuntimeException('Data penduduk gagal dihapus.');
            }

            return response()->json([
                'success' => true,
                'message' => $forceDelete
                    ? 'Data penduduk berhasil dihapus permanen.'
                    : 'Data penduduk berhasil dipindahkan ke sampah.',
            ]);
        } catch (\Throwable $e) {
            \Log::error('Gagal menghapus data penduduk.', [
                'resident_id' => $resident->id,
                'force_delete' => $forceDelete ?? false,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data penduduk.',
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
