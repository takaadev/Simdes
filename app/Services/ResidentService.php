<?php

namespace App\Services;

use App\Models\Resident;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ResidentService
{

    public function getData(Request $request)
    {
        // Filter data penduduk.
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

            // Filter status perkawinan.
            $query->when(
                $request->filled('status_perkawinan')
                    && $request->input('status_perkawinan') !== 'all',
                fn($query) => $query->where(
                    'status_perkawinan',
                    $request->input('status_perkawinan')
                )
            );

            // Filter pendidikan.
            $query->when(
                $request->filled('pendidikan')
                    && $request->input('pendidikan') !== 'all',
                fn($query) => $query->where(
                    'pendidikan',
                    $request->input('pendidikan')
                )
            );

            // Filter agama.
            $query->when(
                $request->filled('agama')
                    && $request->input('agama') !== 'all',
                fn($query) => $query->where(
                    'agama',
                    $request->input('agama')
                )
            );

            // Filter pekerjaan.
            $query->when(
                $request->filled('pekerjaan')
                    && $request->input('pekerjaan') !== 'all',
                fn($query) => $query->where(
                    'pekerjaan',
                    $request->input('pekerjaan')
                )
            );

            // Filter status dalam keluarga.
            $query->when(
                $request->filled('status_dalam_keluarga')
                    && $request->input('status_dalam_keluarga') !== 'all',
                fn($query) => $query->where(
                    'status_dalam_keluarga',
                    $request->input('status_dalam_keluarga')
                )
            );

            // Filter dusun.
            $query->when(
                $request->filled('dusun_id')
                    && $request->input('dusun_id') !== 'all',
                fn($query) => $query->where(
                    'dusun_id',
                    $request->input('dusun_id')
                )
            );

            // Filter tahun lahir.
            $query->when(
                $request->filled('year')
                    && $request->input('year') !== 'all',
                fn($query) => $query->whereYear(
                    'tgl_lahir',
                    $request->input('year')
                )
            );

            // Filter data terhapus.
            $query->when(
                $request->input('deleted') === 'trashed',
                fn($query) => $query->onlyTrashed()
            );

            return $query;
        };

        // Query utama penduduk.
        $query = $applyFilters(
            Resident::query()
                ->with('dusun')
                ->select([
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
                    'kebangsaan',
                    'status_dalam_keluarga',
                    'nik',
                    'kk',
                    'rt',
                    'rw',
                    'dusun_id',
                    'catatan',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ])
        );

        $query->when(
            in_array($request->input('sort_name'), ['asc', 'desc'], true),
            fn($query) => $query->orderBy(
                'nama_lengkap',
                $request->input('sort_name')
            ),
            fn($query) => $query->latest('created_at')
        );

        // Query statistik penduduk.
        $summary = $applyFilters(
            Resident::query()
        )
            ->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN jenis_kelamin = 'L' THEN 1 ELSE 0 END) as male,
            SUM(CASE WHEN jenis_kelamin = 'P' THEN 1 ELSE 0 END) as female,
            SUM(CASE WHEN kewarganegaraan = 'WNI' THEN 1 ELSE 0 END) as wni,
            SUM(CASE WHEN kewarganegaraan = 'WNA' THEN 1 ELSE 0 END) as wna,
            SUM(CASE WHEN dapat_membaca = 1 THEN 1 ELSE 0 END) as literate
        ")
            ->first();

        return DataTables::eloquent($query)
            ->with('summary', [
                'total' => (int) $summary->total,
                'male' => (int) $summary->male,
                'female' => (int) $summary->female,
                'wni' => (int) $summary->wni,
                'wna' => (int) $summary->wna,
                'literate' => (int) $summary->literate,
            ])
            ->addIndexColumn()

            ->editColumn(
                'nama_lengkap',
                fn($row) => $this->tooltip($row->nama_lengkap)
            )

            ->editColumn(
                'nik',
                fn($row) => $row->nik ?: '-'
            )

            ->editColumn(
                'kk',
                fn($row) => $row->kk ?: '-'
            )

            ->editColumn(
                'jenis_kelamin',
                fn($row) => $row->jenis_kelamin?->label() ?? '-'
            )

            ->editColumn(
                'status_perkawinan',
                fn($row) => view('components.badge', [
                    'color' => $row->status_perkawinan?->color() ?? 'secondary',
                    'slot' => $row->status_perkawinan?->label() ?? '-',
                ])->render()
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
                fn($row) => view('components.badge', [
                    'color' => $row->dapat_membaca ? 'success' : 'secondary',
                    'slot' => $row->dapat_membaca ? 'Ya' : 'Tidak',
                ])->render()
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
                'status_dalam_keluarga',
                fn($row) => $row->status_dalam_keluarga ?: '-'
            )

            ->addColumn(
                'dusun',
                fn($row) => $row->dusun?->name ?? '-'
            )

            ->editColumn(
                'rt',
                fn($row) => $row->rt ?: '-'
            )

            ->editColumn(
                'rw',
                fn($row) => $row->rw ?: '-'
            )

            ->editColumn(
                'catatan',
                fn($row) => $this->tooltip($row->catatan)
            )

            ->addColumn(
                'action',
                fn($row) => view('pages.resident.comp.actions', [
                    'row' => $row,
                ])->render()
            )

            ->rawColumns([
                'nama_lengkap',
                'dapat_membaca',
                'status_perkawinan',
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
}
