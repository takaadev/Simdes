<?php

namespace App\Services;

use App\Http\Requests\Resident\ImportExcelResidentRequest;
use App\Imports\ResidentImport;
use App\Models\Resident;
use App\Models\TemporaryResident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class TemporaryResidentService
{
    public function getData(Request $request)
    {
        $applyFilters = function ($query) use ($request) {
            $query->when(
                $request->filled('jenis_kelamin') && $request->input('jenis_kelamin') !== 'all',
                fn($query) => $query->where('jenis_kelamin', $request->input('jenis_kelamin'))
            );

            // Filter kewarganegaraan
            $query->when(
                $request->filled('kewarganegaraan') && $request->input('kewarganegaraan') !== 'all',
                fn($query) => $query->where('kewarganegaraan', $request->input('kewarganegaraan'))
            );

            // Filter pekerjaan
            $query->when(
                $request->filled('pekerjaan') && $request->input('pekerjaan') !== 'all',
                fn($query) => $query->where('pekerjaan', $request->input('pekerjaan'))
            );

            // Filter status tinggal (Masih Tinggal / Sudah Pergi)
            $query->when(
                $request->filled('status_tinggal') && $request->input('status_tinggal') !== 'all',
                function ($query) use ($request) {
                    if ($request->input('status_tinggal') === 'staying') {
                        $query->whereNull('tgl_pergi');
                    } elseif ($request->input('status_tinggal') === 'departed') {
                        $query->whereNotNull('tgl_pergi');
                    }
                }
            );

            // Filter tahun datang
            $query->when(
                $request->filled('year') && $request->input('year') !== 'all',
                fn($query) => $query->whereYear('tgl_datang', $request->input('year'))
            );

            // Filter data terhapus
            $query->when(
                $request->input('deleted') === 'trashed',
                fn($query) => $query->onlyTrashed()
            );

            return $query;
        };

        $query = $applyFilters(
            TemporaryResident::query()
                ->select([
                    'id',
                    'nama_lengkap',
                    'jenis_kelamin',
                    'nomor_identitas',
                    'tempat_lahir',
                    'tgl_lahir',
                    'umur', // Tambahkan ini
                    'pekerjaan',
                    'kewarganegaraan',
                    'kebangsaan',
                    'keturunan',
                    'datang_dari',
                    'tujuan_kedatangan',
                    'alamat_tujuan',
                    'tgl_datang',
                    'tgl_pergi',
                    'catatan',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ])
        );

        $query->when(
            in_array($request->input('sort_name'), ['asc', 'desc'], true),
            fn($query) => $query->orderBy('nama_lengkap', $request->input('sort_name')),
            fn($query) => $query->latest('created_at')
        );

        // Query statistik penduduk sementara
        $summary = $applyFilters(TemporaryResident::query())
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN jenis_kelamin = 'L' THEN 1 ELSE 0 END) as male,
                SUM(CASE WHEN jenis_kelamin = 'P' THEN 1 ELSE 0 END) as female,
                SUM(CASE WHEN tgl_pergi IS NULL THEN 1 ELSE 0 END) as staying,
                SUM(CASE WHEN tgl_pergi IS NOT NULL THEN 1 ELSE 0 END) as departed
            ")
            ->first();

        return DataTables::eloquent($query)
            ->with('summary', [
                'total' => (int) ($summary->total ?? 0),
                'male' => (int) ($summary->male ?? 0),
                'female' => (int) ($summary->female ?? 0),
                'staying' => (int) ($summary->staying ?? 0),
                'departed' => (int) ($summary->departed ?? 0),
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
                'nomor_identitas',
                fn($row) => $row->nomor_identitas ?: '-'
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
                'umur',
                fn($row) => $row->umur !== null ? $row->umur . ' Thn' : '-'
            )

            ->editColumn(
                'pekerjaan',
                fn($row) => $row->pekerjaan?->label() ?? '-'
            )

            ->editColumn(
                'kewarganegaraan',
                fn($row) => $row->kewarganegaraan?->label() ?? '-'
            )

            ->editColumn(
                'kebangsaan',
                fn($row) => $row->kebangsaan ?: '-'
            )

            ->editColumn(
                'keturunan',
                fn($row) => $row->keturunan ?: '-'
            )

            ->editColumn(
                'datang_dari',
                fn($row) => $this->tooltip($row->datang_dari)
            )

            ->editColumn(
                'tujuan_kedatangan',
                fn($row) => $this->tooltip($row->tujuan_kedatangan)
            )

            ->editColumn(
                'alamat_tujuan',
                fn($row) => $this->tooltip($row->alamat_tujuan)
            )

            ->editColumn(
                'tgl_datang',
                fn($row) => $this->formatDate($row->tgl_datang)
            )

            ->editColumn(
                'tgl_pergi',
                fn($row) => $row->tgl_pergi ? $this->formatDate($row->tgl_pergi) : '-'
            )

            ->addColumn(
                'status_tinggal',
                fn($row) => view('components.badge', [
                    'color' => $row->tgl_pergi ? 'secondary' : 'success',
                    'slot' => $row->stay_status, // Menggunakan accessor dari model ('Sudah Pergi' / 'Masih Tinggal')
                ])->render()
            )

            ->editColumn(
                'catatan',
                fn($row) => $this->tooltip($row->catatan)
            )

            ->addColumn(
                'action',
                fn($row) => view('pages.temporary.comp.actions', [
                    'row' => $row,
                ])->render()
            )

            ->rawColumns([
                'nama_lengkap',
                'datang_dari',
                'tujuan_kedatangan',
                'alamat_tujuan',
                'status_tinggal',
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
