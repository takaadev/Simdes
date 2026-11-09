<?php

namespace App\Services;

use App\Enums\RegulationStatusList;
use App\Models\VillagePublication;
use App\Models\VillageRegulation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VillagePublicationService
{

    public function getData(Request $request)
    {
        // Filter bersama untuk tabel dan statistik.
        $applyFilters = function ($query) use ($request) {
            $query
                ->when(
                    $request->filled('jenis_peraturan')
                        && $request->input('jenis_peraturan') !== 'all',
                    fn($query) => $query->whereHas(
                        'regulation',
                        fn($regulation) => $regulation->where(
                            'jenis_peraturan',
                            $request->input('jenis_peraturan')
                        )
                    )
                )
                ->when(
                    $request->filled('year')
                        && $request->input('year') !== 'all',
                    fn($query) => $query->whereYear(
                        'tgl_diundang',
                        $request->input('year')
                    )
                )
                ->when(
                    $request->input('deleted') === 'trashed',
                    fn($query) => $query->onlyTrashed()
                );

            return $query;
        };

        // Query utama.
        $query = $applyFilters(
            VillagePublication::query()
        )
            ->select([
                'id',
                'regulation_id',
                'nomor_diundang',
                'tgl_diundang',
                'nomor_berita',
                'tgl_berita',
                'status',
                'created_by',
                'created_at',
                'updated_at',
                'deleted_at',
            ])
            ->with([
                'regulation' => fn($query) => $query->select([
                    'id',
                    'jenis_peraturan',
                    'nomor_peraturan',
                    'tgl_ditetapkan',
                    'nama_peraturan',
                    'status',
                ]),
            ])
            ->latest('created_at');

        // Filter status hanya untuk tabel.
        $query->when(
            $request->filled('status')
                && $request->input('status') !== 'all',
            fn($query) => $query->where(
                'status',
                $request->input('status')
            )
        );

        // Query statistik tanpa filter status.
        $summaryQuery = $applyFilters(
            VillagePublication::query()
        );

        $summaryQuery->selectRaw('COUNT(*) as total');

        foreach (RegulationStatusList::cases() as $status) {
            $summaryQuery->selectRaw(
                'SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as ' . $status->value,
                [$status->value]
            );
        }

        $summary = $summaryQuery->first();

        return DataTables::eloquent($query)

            // Statistik.
            ->with('summary', [
                'total' => (int) ($summary->total ?? 0),

                ...collect(RegulationStatusList::cases())
                    ->mapWithKeys(fn($status) => [
                        $status->value => (int) ($summary->{$status->value} ?? 0),
                    ])
                    ->all(),
            ])

            ->addIndexColumn()

            // Informasi Peraturan.
            ->addColumn(
                'jenis_peraturan',
                fn($row) => $row->regulation?->jenis_peraturan?->label() ?? '-'
            )

            ->addColumn(
                'nomor_peraturan',
                fn($row) => $row->regulation?->nomor_peraturan ?: '-'
            )

            ->addColumn(
                'tgl_ditetapkan',
                fn($row) => $this->formatDate(
                    $row->regulation?->tgl_ditetapkan
                )
            )

            ->addColumn(
                'nama_peraturan',
                fn($row) => $this->tooltip(
                    $row->regulation?->nama_peraturan
                )
            )

            // Pengundangan.
            ->editColumn(
                'nomor_diundang',
                fn($row) => $row->nomor_diundang ?: '-'
            )

            ->editColumn(
                'tgl_diundang',
                fn($row) => $this->formatDate($row->tgl_diundang)
            )

            // Berita Desa.
            ->editColumn(
                'nomor_berita',
                fn($row) => $row->nomor_berita ?: '-'
            )

            ->editColumn(
                'tgl_berita',
                fn($row) => $this->formatDate($row->tgl_berita)
            )

            // Keterangan dari relasi VillageRegulation.
            ->addColumn(
                'catatan',
                fn($row) => $this->tooltip($row->regulation?->catatan)
            )

            // Status Pengundangan.
            ->editColumn('status', function ($row) {
                $status = $row->status;

                $color = match ($status?->value) {
                    'draft'   => 'gray',
                    'active'  => 'green',
                    'revoked' => 'red',
                    'amended' => 'orange',
                    default   => 'blue',
                };

                return view('components.badge', [
                    'color' => $color,
                    'slot' => $status?->label() ?? '-',
                ])->render();
            })

            // Action.
            ->addColumn(
                'action',
                fn($row) => view(
                    'pages.villagepublication.comp.actions',
                    ['row' => $row]
                )->render()
            )

            ->rawColumns([
                'nama_peraturan',
                'catatan',
                'status',
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
            \DB::transaction(function () use ($data) {
                $regulation = VillageRegulation::findOrFail($data['regulation_id']);
                $status = $data['status'];
                $data['created_by'] = \Auth::id() ?? 1;
                VillagePublication::create($data);
                $regulation->update([
                    'status' => $status,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Publikasi peraturan desa berhasil ditambahkan.',
            ], 201);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan publikasi peraturan desa.',
            ], 500);
        }
    }

    public function getUpdate(VillagePublication $publication, array $data): \Illuminate\Http\JsonResponse
    {
        try {
            \DB::transaction(function () use ($publication, $data) {
                $regulation = $publication->regulation;

                $status = $data['status'];

                $publication->update($data);

                // Sinkronkan status peraturan desa.
                $regulation->update([
                    'status' => $status,
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Publikasi peraturan desa berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui publikasi peraturan desa.',
            ], 500);
        }
    }

    public function getDestroy(Request $request, VillagePublication $publication)
    {
        try {
            $forceDelete = $request->boolean('confirm_delete');

            // Data Trash wajib dikonfirmasi.
            if ($publication->trashed() && !$forceDelete) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data sudah berada di sampah. Centang konfirmasi untuk menghapus permanen.',
                    'errors' => [
                        'confirm_delete' => [
                            'Data sudah berada di sampah. Centang konfirmasi untuk menghapus permanen.',
                        ],
                    ],
                ], 422);
            }

            $deleted = \DB::transaction(function () use (
                $publication,
                $forceDelete
            ) {
                $regulation = $publication->regulation;

                $deleted = $forceDelete
                    ? $publication->forceDelete()
                    : $publication->delete();

                if ($deleted && $regulation) {
                    $regulation->update([
                        'status' => \App\Enums\RegulationStatusList::DRAFT->value,
                    ]);
                }

                return $deleted;
            });

            if (!$deleted) {
                throw new \RuntimeException('Publikasi peraturan desa gagal dihapus.');
            }

            return response()->json([
                'success' => true,
                'message' => $forceDelete
                    ? 'Publikasi peraturan desa berhasil dihapus permanen.'
                    : 'Publikasi peraturan desa berhasil dipindahkan ke sampah.',
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus publikasi peraturan desa.',
            ], 500);
        }
    }
}
