<?php

namespace App\Services;

use App\Models\TemporaryResident;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Yajra\DataTables\Facades\DataTables;
use Throwable;

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

    public function getStore(array $data): JsonResponse
    {
        try {
            $temporaryResident = DB::transaction(function () use ($data) {
                $data['created_by'] = auth()->id();
                return TemporaryResident::create($data);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data penduduk sementara berhasil ditambahkan.',
                'data' => $temporaryResident,
            ], 201);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan data penduduk sementara.', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data penduduk sementara gagal ditambahkan.',
            ], 500);
        }
    }

    public function getUpdate(TemporaryResident $temporary, array $data): JsonResponse
    {
        try {
            DB::transaction(function () use ($temporary, $data) {
                $temporary->update($data);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data penduduk sementara berhasil diperbarui.',
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui data penduduk sementara.', [
                'temporary_resident_id' => $temporary->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data penduduk sementara.',
            ], 500);
        }
    }

    public function getOutgoingConfirmed($id, array $data): JsonResponse
    {
        try {
            $temporary = TemporaryResident::findOrFail($id);
            if ($temporary->tgl_pergi !== null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Penduduk ini sudah tercatat meninggalkan desa.',
                ], 422);
            }

            DB::transaction(function () use ($temporary, $data) {
                // Isi tanggal kepergian secara langsung.
                $temporary->tgl_pergi = $data['tgl_pergi'];

                // Simpan perubahan ke database.
                if (! $temporary->save()) {
                    throw new RuntimeException(
                        'Data kepergian penduduk gagal disimpan.'
                    );
                }

                // Pastikan perubahan benar-benar tersimpan.
                $temporary->refresh();

                if (
                    $temporary->tgl_pergi === null ||
                    $temporary->tgl_pergi->format('Y-m-d') !== $data['tgl_pergi']
                ) {
                    throw new RuntimeException(
                        'Tanggal kepergian tidak berhasil diperbarui.'
                    );
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Kepergian penduduk berhasil dikonfirmasi.',
                'data' => [
                    'id' => $temporary->id,
                    'tgl_pergi' => $temporary->tgl_pergi->format('Y-m-d'),
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal mencatat kepergian penduduk sementara.', [
                'temporary_resident_id' => $temporary->id,
                'tgl_pergi' => $data['tgl_pergi'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mencatat kepergian penduduk.',
            ], 500);
        }
    }

    public function getDestroy(Request $request, TemporaryResident $temporary): JsonResponse
    {
        try {
            $forceDelete = $request->boolean('confirm_delete');

            if ($temporary->trashed() && !$forceDelete) {
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

            $deleted = DB::transaction(function () use ($temporary, $forceDelete) {
                return $forceDelete ? $temporary->forceDelete() : $temporary->delete();
            });

            if (!$deleted) {
                throw new RuntimeException('Data penduduk sementara gagal dihapus.');
            }

            return response()->json([
                'success' => true,
                'message' => $forceDelete ? 'Data penduduk sementara berhasil dihapus permanen.' : 'Data penduduk sementara berhasil dipindahkan ke sampah.',
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal menghapus data penduduk sementara.', [
                'temporary_resident_id' => $temporary->id,
                'force_delete' => $forceDelete ?? false,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data penduduk sementara.',
            ], 500);
        }
    }
}
