<?php

namespace App\Services;

use App\Enums\MutationCategoryList;
use App\Enums\MutationTypeList;
use App\Http\Requests\Resident\ImportExcelResidentRequest;
use App\Imports\ResidentImport;
use App\Models\Resident;
use App\Models\ResidentMutation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ResidentMutationService
{
    public function getData(Request $request)
    {
        $baseQuery = ResidentMutation::query()
            ->leftJoin('residents', 'residents.id', '=', 'resident_mutations.resident_id')
            ->when(
                $request->filled('jenis_kelamin') && $request->input('jenis_kelamin') !== 'all',
                fn($q) => $q->where('residents.jenis_kelamin', $request->input('jenis_kelamin'))
            )
            ->when(
                $request->filled('kewarganegaraan') && $request->input('kewarganegaraan') !== 'all',
                fn($q) => $q->where('residents.kewarganegaraan', $request->input('kewarganegaraan'))
            )
            ->when(
                $request->filled('kategori_mutasi') && $request->input('kategori_mutasi') !== 'all',
                fn($q) => $q->where('resident_mutations.kategori_mutasi', $request->input('kategori_mutasi'))
            )
            ->when(
                $request->filled('tipe_mutasi') && $request->input('tipe_mutasi') !== 'all',
                fn($q) => $q->where('resident_mutations.tipe_mutasi', $request->input('tipe_mutasi'))
            )
            ->when(
                $request->input('deleted') === 'trashed',
                fn($q) => $q->onlyTrashed()
            );

        // Hitung ringkasan dari baseQuery yang sama (hindari duplikasi filter).
        $summary = (clone $baseQuery)
            ->selectRaw("
            COUNT(DISTINCT resident_mutations.id) as total,
            SUM(CASE WHEN residents.jenis_kelamin = 'L' THEN 1 ELSE 0 END) as male,
            SUM(CASE WHEN residents.jenis_kelamin = 'P' THEN 1 ELSE 0 END) as female,
            SUM(CASE WHEN residents.kewarganegaraan = 'WNI' THEN 1 ELSE 0 END) as wni,
            SUM(CASE WHEN residents.kewarganegaraan = 'WNA' THEN 1 ELSE 0 END) as wna,
            SUM(CASE WHEN resident_mutations.tipe_mutasi = 'add' THEN 1 ELSE 0 END) as addition,
            SUM(CASE WHEN resident_mutations.tipe_mutasi = 'minus' THEN 1 ELSE 0 END) as reduction,
            SUM(CASE WHEN resident_mutations.kategori_mutasi = 'death' THEN 1 ELSE 0 END) as death,
            SUM(CASE WHEN resident_mutations.kategori_mutasi = 'birth' THEN 1 ELSE 0 END) as birth,
            SUM(CASE WHEN resident_mutations.kategori_mutasi = 'incoming' THEN 1 ELSE 0 END) as incoming,
            SUM(CASE WHEN resident_mutations.kategori_mutasi = 'outgoing' THEN 1 ELSE 0 END) as outgoing
        ")
            ->first();

        // Query utama DataTables (semua kolom diberi prefix tabel agar tidak ambigu).
        $query = (clone $baseQuery)
            ->select([
                'resident_mutations.id',
                'resident_mutations.nomor_identitas',
                'resident_mutations.tipe_mutasi',
                'resident_mutations.kategori_mutasi',
                'resident_mutations.tgl_mutasi',
                'resident_mutations.tujuan_mutasi',
                'resident_mutations.catatan',
                'resident_mutations.resident_id',
                'resident_mutations.recorded_by',
                'resident_mutations.created_at',
                'resident_mutations.updated_at',
                'resident_mutations.deleted_at',
            ])
            ->with([
                'resident' => fn($q) => $q->withTrashed()->select([
                    'id',
                    'nik',
                    'nama_lengkap',
                    'jenis_kelamin',
                    'tempat_lahir',
                    'tgl_lahir',
                    'status_perkawinan',
                    'agama',
                    'pendidikan',
                    'pekerjaan',
                    'status_dalam_keluarga',
                    'dapat_membaca',
                    'gol_darah',
                    'kewarganegaraan',
                    'kebangsaan',
                    'ayah',
                    'ibu',
                    'tempat_dikeluarkan',
                    'tgl_dikeluarkan',
                    'tgl_mulai_tinggal',
                    'rt',
                    'rw',
                    'alamat',
                    'is_aktif',
                ]),
            ]);

        // Pengurutan custom sort_name atau default terbaru jika DataTables tidak mengirim order.
        if (in_array($request->input('sort_name'), ['asc', 'desc'], true)) {
            $query->orderBy('residents.nama_lengkap', $request->input('sort_name'));
        } elseif (!$request->filled('order')) {
            $query->orderByDesc('resident_mutations.tgl_mutasi');
        }

        return DataTables::eloquent($query)
            ->with('summary', [
                'total' => (int) ($summary?->total ?? 0),
                'male' => (int) ($summary?->male ?? 0),
                'female' => (int) ($summary?->female ?? 0),
                'wni' => (int) ($summary?->wni ?? 0),
                'wna' => (int) ($summary?->wna ?? 0),
                'addition' => (int) ($summary?->addition ?? 0),
                'reduction' => (int) ($summary?->reduction ?? 0),
                'death' => (int) ($summary?->death ?? 0),
                'birth' => (int) ($summary?->birth ?? 0),
                'incoming' => (int) ($summary?->incoming ?? 0),
                'outgoing' => (int) ($summary?->outgoing ?? 0),
            ])
            // Global search lintas tabel residents & resident_mutations.
            ->filter(function ($query) use ($request) {
                $search = trim((string) $request->input('search.value', ''));

                if ($search !== '') {
                    $query->where(function ($q) use ($search) {
                        $q->where('residents.nama_lengkap', 'like', "%{$search}%")
                            ->orWhere('residents.nik', 'like', "%{$search}%")
                            ->orWhere('residents.tempat_lahir', 'like', "%{$search}%")
                            ->orWhere('residents.kebangsaan', 'like', "%{$search}%")
                            ->orWhere('resident_mutations.nomor_identitas', 'like', "%{$search}%")
                            ->orWhere('resident_mutations.tujuan_mutasi', 'like', "%{$search}%")
                            ->orWhere('resident_mutations.catatan', 'like', "%{$search}%")
                            ->orWhere('resident_mutations.tgl_mutasi', 'like', "%{$search}%");
                    });
                }
            })
            // Mapping sorting untuk kolom hasil join & kolom virtual.
            ->orderColumn('nama_lengkap', 'residents.nama_lengkap $1')
            ->orderColumn('tempat_lahir', 'residents.tempat_lahir $1')
            ->orderColumn('tgl_lahir', 'residents.tgl_lahir $1')
            ->orderColumn('jenis_kelamin', 'residents.jenis_kelamin $1')
            ->orderColumn('kewarganegaraan', 'residents.kewarganegaraan $1')
            ->orderColumn('kebangsaan', 'residents.kebangsaan $1')
            ->orderColumn('tgl_mutasi', 'resident_mutations.tgl_mutasi $1')
            ->orderColumn('tanggal_penambahan', 'resident_mutations.tgl_mutasi $1')
            ->orderColumn('tanggal_pindah', 'resident_mutations.tgl_mutasi $1')
            ->orderColumn('tanggal_meninggal', 'resident_mutations.tgl_mutasi $1')
            ->orderColumn('tipe_mutasi', 'resident_mutations.tipe_mutasi $1')
            ->orderColumn('kategori_mutasi', 'resident_mutations.kategori_mutasi $1')
            ->orderColumn('tujuan_mutasi', 'resident_mutations.tujuan_mutasi $1')
            ->addIndexColumn()
            ->addColumn(
                'nama_lengkap',
                fn($row) => $this->tooltip($row->resident?->nama_lengkap ?? '-')
            )
            ->addColumn(
                'tempat_lahir',
                fn($row) => $row->resident?->tempat_lahir ?: '-'
            )
            ->addColumn(
                'tgl_lahir',
                fn($row) => $this->formatDate($row->resident?->tgl_lahir)
            )
            ->addColumn(
                'jenis_kelamin',
                fn($row) => $row->resident?->jenis_kelamin?->label() ?? '-'
            )
            ->addColumn(
                'kewarganegaraan',
                fn($row) => $row->resident?->kewarganegaraan?->label() ?? '-'
            )
            ->addColumn(
                'kebangsaan',
                fn($row) => $row->resident?->kebangsaan ?: '-'
            )
            ->addColumn('datang_dari', function ($row) {
                if ($row->tipe_mutasi !== MutationTypeList::ADD) {
                    return '-';
                }

                return match ($row->kategori_mutasi) {
                    MutationCategoryList::BIRTH => 'Kelahiran',
                    MutationCategoryList::INCOMING => $row->tujuan_mutasi ?: '-',
                    default => '-',
                };
            })
            ->addColumn(
                'tanggal_penambahan',
                fn($row) => $row->tipe_mutasi === MutationTypeList::ADD
                    ? $this->formatDate($row->tgl_mutasi)
                    : '-'
            )
            ->addColumn(
                'pindah_ke',
                fn($row) => (
                    $row->tipe_mutasi === MutationTypeList::MINUS
                    && $row->kategori_mutasi === MutationCategoryList::OUTGOING
                ) ? ($row->tujuan_mutasi ?: '-') : '-'
            )
            ->addColumn(
                'tanggal_pindah',
                fn($row) => (
                    $row->tipe_mutasi === MutationTypeList::MINUS
                    && $row->kategori_mutasi === MutationCategoryList::OUTGOING
                ) ? $this->formatDate($row->tgl_mutasi) : '-'
            )
            ->addColumn(
                'meninggal',
                fn($row) => (
                    $row->tipe_mutasi === MutationTypeList::MINUS
                    && $row->kategori_mutasi === MutationCategoryList::DEATH
                ) ? 'Meninggal' : '-'
            )
            ->addColumn(
                'tanggal_meninggal',
                fn($row) => (
                    $row->tipe_mutasi === MutationTypeList::MINUS
                    && $row->kategori_mutasi === MutationCategoryList::DEATH
                ) ? $this->formatDate($row->tgl_mutasi) : '-'
            )
            ->editColumn(
                'tipe_mutasi',
                fn($row) => view('components.badge', [
                    'color' => match ($row->tipe_mutasi) {
                        MutationTypeList::ADD => 'success',
                        MutationTypeList::MINUS => 'warning',
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
            ->addColumn(
                'action',
                fn($row) => view('pages.mutation.comp.actions', ['row' => $row])->render()
            )
            ->rawColumns([
                'nama_lengkap',
                'tipe_mutasi',
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
            $mutation = DB::transaction(function () use ($data) {
                // 1. Simpan data penduduk baru
                $resident = Resident::create([
                    'family_card_id'       => null,
                    'nama_lengkap'         => $data['nama_lengkap'],
                    'jenis_kelamin'        => $data['jenis_kelamin'],
                    'nik'                  => $data['nomor_identitas'],
                    'kk'                   => null,
                    'tempat_lahir'         => $data['tempat_lahir'],
                    'tgl_lahir'            => $data['tgl_lahir'],
                    'status_perkawinan'    => $data['status_perkawinan'],
                    'agama'                => $data['agama'],
                    'pendidikan'           => $data['pendidikan'],
                    'pekerjaan'            => $data['pekerjaan'],
                    'dapat_membaca'        => $data['dapat_membaca'],
                    'kewarganegaraan'      => $data['kewarganegaraan'],
                    'status_dalam_keluarga' => $data['status_dalam_keluarga'],
                    'kebangsaan'           => $data['kebangsaan'],
                    'gol_darah'            => $data['gol_darah'] ?? null,
                    'ayah'                 => $data['ayah'] ?? null,
                    'ibu'                  => $data['ibu'] ?? null,
                    'tempat_dikeluarkan'   => $data['tempat_dikeluarkan'] ?? null,
                    'tgl_dikeluarkan'      => $data['tgl_dikeluarkan'] ?? null,
                    'tgl_mulai_tinggal'    => $data['tgl_mulai_tinggal'] ?? null,
                    'rt'                   => $data['rt'] ?? null,
                    'rw'                   => $data['rw'] ?? null,
                    'alamat'               => $data['alamat'] ?? null,
                    'is_aktif'             => true,
                ]);

                // 2. Simpan riwayat mutasi masuk
                return ResidentMutation::create([
                    'nomor_identitas' => $resident->nik,
                    'tipe_mutasi'     => MutationTypeList::ADD->value,
                    'kategori_mutasi' => $data['kategori_mutasi'],
                    'tgl_mutasi'      => $data['tgl_mutasi'],
                    'tujuan_mutasi'   => $data['tujuan_mutasi'] ?? null,
                    'catatan'         => $data['catatan_mutasi'] ?? null,
                    'resident_id'     => $resident->id,
                    'recorded_by'     => Auth::id(),
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data penduduk dan mutasi berhasil ditambahkan.',
                'data'    => $mutation,
            ], 201);
        } catch (\Throwable $e) {
            Log::error('Gagal menambahkan penduduk melalui mutasi masuk.', [
                'error' => $e->getMessage(),
                'data'  => $data,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data penduduk dan mutasi gagal ditambahkan.',
            ], 500);
        }
    }


    public function getUpdate(ResidentMutation $mutation, array $data): JsonResponse
    {
        try {
            DB::transaction(function () use ($mutation, $data) {
                $mutation->update([
                    'kategori_mutasi' => $data['kategori_mutasi'],
                    'tgl_mutasi' => $data['tgl_mutasi'],
                    'tujuan_mutasi' => $data['tujuan_mutasi'] ?? null,
                    'catatan' => $data['catatan'] ?? null,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data mutasi penduduk berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal memperbarui data mutasi penduduk.', [
                'mutation_id' => $mutation->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data mutasi penduduk.',
            ], 500);
        }
    }

    public function getDestroy(Request $request, ResidentMutation $mutation): JsonResponse
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

            $deleted = DB::transaction(function () use (
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
            Log::error('Gagal menghapus data mutasi penduduk.', [
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

    public function getMutationOutgoing(array $data): JsonResponse
    {
        try {
            return DB::transaction(function () use ($data) {
                $resident = Resident::query()
                    ->whereKey($data['resident_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$resident) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data penduduk tidak ditemukan di Buku Induk Penduduk.',
                    ], 422);
                }

                if (!$resident->is_aktif) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Penduduk ini sudah tidak berstatus aktif.',
                    ], 422);
                }

                $kategori = $data['kategori_mutasi'];

                $allowedCategories = [
                    MutationCategoryList::DEATH->value,
                    MutationCategoryList::OUTGOING->value,
                ];

                if (!in_array($kategori, $allowedCategories, true)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kategori mutasi tidak valid.',
                    ], 422);
                }

                if (
                    $kategori === MutationCategoryList::OUTGOING->value &&
                    empty($data['tujuan_mutasi'])
                ) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tujuan mutasi wajib diisi untuk penduduk keluar.',
                    ], 422);
                }

                $tanggalMutasi = \Carbon\Carbon::parse($data['tgl_mutasi']);

                if (
                    $resident->tgl_lahir &&
                    $tanggalMutasi->lt($resident->tgl_lahir)
                ) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tanggal mutasi tidak boleh sebelum tanggal lahir penduduk.',
                    ], 422);
                }

                ResidentMutation::create([
                    'tipe_mutasi'     => MutationTypeList::MINUS,
                    'kategori_mutasi' => $kategori,
                    'tgl_mutasi'      => $tanggalMutasi->toDateString(),
                    'tujuan_mutasi'   => $data['tujuan_mutasi'] ?? null,
                    'catatan'         => $data['catatan'] ?? null,
                    'resident_id'     => $resident->id,
                    'recorded_by'     => auth()->id(),
                ]);

                $resident->update([
                    'is_aktif' => false,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Mutasi penduduk berhasil disimpan.',
                ]);
            });
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses mutasi penduduk.',
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
