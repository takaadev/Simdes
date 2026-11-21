<?php

namespace App\Services;

use App\Http\Requests\Resident\ImportExcelResidentRequest;
use App\Imports\ResidentImport;
use App\Models\FamilyCard;
use App\Models\Resident;
use BackedEnum;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use RuntimeException;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class ResidentService
{
    private function applyFilters($query, Request $request)
    {
        $exactFilters = [
            'jenis_kelamin' => 'residents.jenis_kelamin',
            'kewarganegaraan' => 'residents.kewarganegaraan',
            'status_perkawinan' => 'residents.status_perkawinan',
            'pendidikan' => 'residents.pendidikan',
            'agama' => 'residents.agama',
            'pekerjaan' => 'residents.pekerjaan',
            'rt' => 'residents.rt',
        ];

        foreach ($exactFilters as $param => $column) {
            $query->when(
                $request->filled($param) && $request->input($param) !== 'all',
                fn($q) => $q->where($column, $request->input($param))
            );
        }

        // Filter Tahun Kelahiran
        $query->when(
            $request->filled('year') && $request->input('year') !== 'all',
            fn($q) => $q->whereYear('residents.tgl_lahir', $request->input('year'))
        );

        // Filter Status Aktif (opsional)
        $query->when(
            $request->filled('is_aktif') && $request->input('is_aktif') !== 'all',
            fn($q) => $q->where('residents.is_aktif', (bool) $request->input('is_aktif'))
        );

        // Filter Status Data (SoftDeletes)
        $query->when(
            $request->input('deleted') === 'trashed',
            fn($q) => $q->onlyTrashed()
        );

        return $query;
    }

    public function getData(Request $request)
    {
        $baseQuery = $this->applyFilters(Resident::query(), $request);

        // Query statistik penduduk (sinkron dengan seluruh filter aktif).
        $summary = (clone $baseQuery)
            ->selectRaw("
                COUNT(*) as total,
                COALESCE(SUM(CASE WHEN jenis_kelamin = 'L' THEN 1 ELSE 0 END), 0) as male,
                COALESCE(SUM(CASE WHEN jenis_kelamin = 'P' THEN 1 ELSE 0 END), 0) as female,
                COALESCE(SUM(CASE WHEN kewarganegaraan = 'WNI' THEN 1 ELSE 0 END), 0) as wni,
                COALESCE(SUM(CASE WHEN kewarganegaraan = 'WNA' THEN 1 ELSE 0 END), 0) as wna,
                COALESCE(SUM(CASE WHEN is_aktif = 1 THEN 1 ELSE 0 END), 0) as active,
                COALESCE(SUM(CASE WHEN is_aktif = 0 THEN 1 ELSE 0 END), 0) as inactive
            ")
            ->first();

        // Query utama penduduk.
        $query = (clone $baseQuery)->select([
            'id',
            'family_card_id',
            'nama_lengkap',
            'jenis_kelamin',
            'nik',
            'kk',
            'tempat_lahir',
            'tgl_lahir',
            'status_perkawinan',
            'agama',
            'pendidikan',
            'pekerjaan',
            'dapat_membaca',
            'kewarganegaraan',
            'status_dalam_keluarga',
            'kebangsaan',
            'gol_darah',
            'ayah',
            'ibu',
            'rt',
            'rw',
            'alamat',
            'tempat_dikeluarkan',
            'tgl_dikeluarkan',
            'tgl_mulai_tinggal',
            'is_aktif',
            'catatan',
            'created_at',
            'updated_at',
            'deleted_at',
        ]);

        // Pengurutan data.
        if (in_array($request->input('sort_name'), ['asc', 'desc'], true)) {
            $query->orderBy('nama_lengkap', $request->input('sort_name'));
        } elseif (!$request->filled('order')) {
            $query->orderBy('nama_lengkap', 'asc');
        }

        return DataTables::eloquent($query)
            ->with('summary', [
                'total' => (int) ($summary?->total ?? 0),
                'male' => (int) ($summary?->male ?? 0),
                'female' => (int) ($summary?->female ?? 0),
                'wni' => (int) ($summary?->wni ?? 0),
                'wna' => (int) ($summary?->wna ?? 0),
                'active' => (int) ($summary?->active ?? 0),
                'inactive' => (int) ($summary?->inactive ?? 0),
            ])
            ->addIndexColumn()
            ->editColumn('nama_lengkap', fn($row) => $this->tooltip($row->nama_lengkap))
            ->editColumn('jenis_kelamin', fn($row) => $row->jenis_kelamin?->label() ?? '-')
            ->editColumn('status_perkawinan', fn($row) => $row->status_perkawinan?->label() ?? '-')
            ->editColumn('tempat_lahir', fn($row) => $row->tempat_lahir ?: '-')
            ->editColumn('tgl_lahir', fn($row) => $this->formatDate($row->tgl_lahir))
            ->editColumn('agama', fn($row) => $row->agama?->label() ?? '-')
            ->editColumn('pendidikan', fn($row) => $row->pendidikan?->label() ?? '-')
            ->editColumn('pekerjaan', fn($row) => $row->pekerjaan?->label() ?? '-')
            ->editColumn('dapat_membaca', fn($row) => $row->dapat_membaca ? 'Ya' : 'Tidak')
            ->editColumn('kewarganegaraan', fn($row) => $row->kewarganegaraan?->label() ?? '-')
            ->editColumn('status_dalam_keluarga', fn($row) => $row->status_dalam_keluarga instanceof BackedEnum
                ? $row->status_dalam_keluarga->label()
                : ($row->status_dalam_keluarga ?: '-'))
            ->editColumn('nik', fn($row) => $row->nik ?: '-')
            ->editColumn('kk', fn($row) => $row->kk ?: '-')
            ->editColumn('is_aktif', fn($row) => view('components.badge', [
                'color' => $row->is_aktif ? 'success' : 'secondary',
                'slot' => $row->is_aktif ? 'Aktif' : 'Tidak Aktif',
            ])->render())
            ->editColumn('alamat', function ($row) {
                $rt = $row->rt instanceof BackedEnum ? $row->rt->value : $row->rt;
                $rw = $row->rw instanceof BackedEnum ? $row->rw->value : $row->rw;

                $alamat = collect([
                    $row->alamat,
                    $rt ? "RT {$rt}" : null,
                    $rw ? "RW {$rw}" : null,
                ])->filter()->implode(', ');

                return $this->tooltip($alamat ?: '-');
            })
            ->addColumn('action', fn($row) => view('pages.resident.comp.actions', [
                'row' => $row,
            ])->render())
            ->rawColumns([
                'nama_lengkap',
                'alamat',
                'action',
                'is_aktif',
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
            $resident = DB::transaction(function () use ($data) {
                // 1. Cari atau buat data Kartu Keluarga
                $familyCard = FamilyCard::withTrashed()->firstOrCreate([
                    'nomor_kk' => $data['kk'],
                ]);

                // 2. Pulihkan data KK jika sebelumnya di-soft-delete
                if ($familyCard->trashed()) {
                    $familyCard->restore();
                }

                // 3. Hubungkan penduduk dengan Kartu Keluarga
                $data['family_card_id'] = $familyCard->id;

                // 4. Simpan data penduduk
                return Resident::create($data);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data penduduk berhasil ditambahkan.',
                'data' => $resident,
            ], 201);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan data penduduk.', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data penduduk gagal ditambahkan.',
            ], 500);
        }
    }

    public function getUpdate(Resident $resident, array $data): JsonResponse
    {
        try {
            DB::transaction(function () use ($resident, $data) {
                $familyCard = FamilyCard::withTrashed()->firstOrCreate([
                    'nomor_kk' => $data['kk'],
                ]);

                if ($familyCard->trashed()) {
                    $familyCard->restore();
                }

                $data['family_card_id'] = $familyCard->id;
                $resident->update($data);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data penduduk berhasil diperbarui.',
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui data penduduk.', [
                'resident_id' => $resident->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data penduduk.',
            ], 500);
        }
    }

    public function getDestroy(Request $request, Resident $resident): JsonResponse
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

            $deleted = DB::transaction(function () use ($resident, $forceDelete) {
                return $forceDelete
                    ? $resident->forceDelete()
                    : $resident->delete();
            });

            if (!$deleted) {
                throw new RuntimeException('Data penduduk gagal dihapus.');
            }

            return response()->json([
                'success' => true,
                'message' => $forceDelete
                    ? 'Data penduduk berhasil dihapus permanen.'
                    : 'Data penduduk berhasil dipindahkan ke sampah.',
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal menghapus data penduduk.', [
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

    public function getImportExcel(ImportExcelResidentRequest $request): JsonResponse
    {
        try {
            $import = new ResidentImport();
            Excel::import($import, $request->file('file'));
            $errors = $import->getErrors();

            if (!empty($errors)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Import dibatalkan. Terdapat ' . count($errors) . ' kesalahan pada data Excel.',
                    'total_errors' => count($errors),
                    'errors' => $errors,
                ], 422);
            }

            $rows = $import->getValidRows();

            if (empty($rows)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data penduduk yang dapat diimport.',
                ], 422);
            }

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
        } catch (Throwable $e) {
            report($e);

            Log::error('Import Excel Penduduk Gagal', [
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

    public function getPrintData(Request $request)
    {
        $search = trim((string) $request->input('search', ''));
        $sortDir = in_array($request->input('sort_name'), ['asc', 'desc'], true)
            ? $request->input('sort_name')
            : 'asc';

        $query = $this->applyFilters(
            Resident::query()
                ->leftJoin('family_cards', 'family_cards.id', '=', 'residents.family_card_id')
                ->select([
                    'residents.id',
                    'residents.nama_lengkap',
                    'residents.jenis_kelamin',
                    'residents.status_perkawinan',
                    'residents.tempat_lahir',
                    'residents.tgl_lahir',
                    'residents.agama',
                    'residents.pendidikan',
                    'residents.pekerjaan',
                    'residents.dapat_membaca',
                    'residents.kewarganegaraan',
                    'residents.alamat',
                    'residents.rt',
                    'residents.rw',
                    'residents.status_dalam_keluarga',
                    'residents.nik',
                    'residents.kk',
                    'family_cards.nomor_kk',
                    'residents.is_aktif',
                    'residents.catatan',
                ]),
            $request
        );

        if ($search !== '') {
            $query->where(function ($sub) use ($search) {
                $sub->where('residents.nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('residents.nik', 'like', "%{$search}%")
                    ->orWhere('residents.kk', 'like', "%{$search}%")
                    ->orWhere('family_cards.nomor_kk', 'like', "%{$search}%")
                    ->orWhere('residents.alamat', 'like', "%{$search}%")
                    ->orWhere('residents.pekerjaan', 'like', "%{$search}%");
            });
        }

        return $query
            ->orderBy('residents.nama_lengkap', $sortDir)
            ->get();
    }

    public function getFilterSummary(Request $request): ?string
    {
        $labels = [
            'jenis_kelamin' => 'JK',
            'kewarganegaraan' => 'Warga',
            'status_perkawinan' => 'Status Kawin',
            'pendidikan' => 'Pendidikan',
            'agama' => 'Agama',
            'pekerjaan' => 'Pekerjaan',
            'rt' => 'RT',
            'year' => 'Tahun Lahir',
        ];

        $parts = [];

        foreach ($labels as $param => $label) {
            if ($request->filled($param) && $request->input($param) !== 'all') {
                $parts[] = "{$label}: " . $request->input($param);
            }
        }

        if ($request->input('deleted') === 'trashed') {
            $parts[] = 'Status Data: Dihapus';
        }

        return $parts ? implode(' | ', $parts) : null;
    }
}
