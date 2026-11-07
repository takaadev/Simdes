<?php

namespace App\Services;

use App\Enums\RegulationStatusList;
use App\Models\VillageRegulation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VillageRegulationService
{

    public function getData(Request $request)
    {
        $query = VillageRegulation::query()
            ->select([
                'id',
                'jenis_peraturan',
                'nomor_peraturan',
                'tgl_ditetapkan',
                'nama_peraturan',
                'uraian',
                'tgl_kesepakatan',
                'nomor_dilaporkan',
                'tgl_dilaporkan',
                'nomor_diundang',
                'tgl_diundang',
                'nomor_berita',
                'tgl_berita',
                'catatan',
                'dokumen',
                'status',
                'created_by',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);

        // Filter jenis peraturan.
        $query->when(
            $request->filled('jenis_peraturan')
                && $request->input('jenis_peraturan') !== 'all',
            fn($query) => $query->where(
                'jenis_peraturan',
                $request->input('jenis_peraturan')
            )
        );

        // Filter status hanya untuk tabel.
        $query->when(
            $request->filled('status')
                && $request->input('status') !== 'all',
            fn($query) => $query->where(
                'status',
                $request->input('status')
            )
        );

        // Filter tahun.
        $query->when(
            $request->filled('year')
                && $request->input('year') !== 'all',
            fn($query) => $query->whereYear(
                'tgl_ditetapkan',
                $request->input('year')
            )
        );

        // Filter status data berdasarkan SoftDeletes.
        $query->when(
            $request->input('deleted') === 'trashed',
            fn($query) => $query->onlyTrashed()
        );

        $query->latest('created_at');

        // Query statistik: tanpa filter status.
        $summaryQuery = VillageRegulation::query()
            ->when(
                $request->filled('jenis_peraturan')
                    && $request->input('jenis_peraturan') !== 'all',
                fn($query) => $query->where(
                    'jenis_peraturan',
                    $request->input('jenis_peraturan')
                )
            )
            ->when(
                $request->filled('tahun')
                    && $request->input('tahun') !== 'all',
                fn($query) => $query->whereYear(
                    'tgl_ditetapkan',
                    $request->input('tahun')
                )
            );

        // Filter status data untuk statistik.
        $summaryQuery->when(
            $request->input('deleted') === 'trashed',
            fn($query) => $query->onlyTrashed()
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
            ->with('summary', [
                'total' => (int) $summary->total,
                ...collect(RegulationStatusList::cases())
                    ->mapWithKeys(fn($status) => [
                        $status->value => (int) $summary->{$status->value},
                    ])
                    ->all(),
            ])

            ->addIndexColumn()

            ->editColumn(
                'jenis_peraturan',
                fn($row) => $row->jenis_peraturan?->label() ?? '-'
            )

            ->editColumn(
                'nomor_peraturan',
                fn($row) => $row->nomor_peraturan ?: '-'
            )

            ->editColumn(
                'tgl_ditetapkan',
                fn($row) => $this->formatDate($row->tgl_ditetapkan)
            )

            ->editColumn(
                'nama_peraturan',
                fn($row) => $this->tooltip($row->nama_peraturan)
            )

            ->editColumn(
                'uraian',
                fn($row) => $this->tooltip($row->uraian)
            )

            ->editColumn(
                'tgl_kesepakatan',
                fn($row) => $this->formatDate($row->tgl_kesepakatan)
            )

            ->editColumn(
                'nomor_dilaporkan',
                fn($row) => $row->nomor_dilaporkan ?: '-'
            )

            ->editColumn(
                'tgl_dilaporkan',
                fn($row) => $this->formatDate($row->tgl_dilaporkan)
            )

            ->editColumn(
                'nomor_diundang',
                fn($row) => $row->nomor_diundang ?: '-'
            )

            ->editColumn(
                'tgl_diundang',
                fn($row) => $this->formatDate($row->tgl_diundang)
            )

            ->editColumn(
                'nomor_berita',
                fn($row) => $row->nomor_berita ?: '-'
            )

            ->editColumn(
                'tgl_berita',
                fn($row) => $this->formatDate($row->tgl_berita)
            )

            ->editColumn('dokumen', function ($row) {
                if (! $row->dokumen) {
                    return '-';
                }

                return view('components.view-pdf', [
                    'src' => $row->dokumen,
                    'title' => 'Dokumen Peraturan Desa',
                ])->render();
            })

            ->editColumn(
                'catatan',
                fn($row) => $this->tooltip($row->catatan)
            )

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

            ->addColumn(
                'action',
                fn($row) => view('pages.villageregulation.comp.actions', [
                    'row' => $row,
                ])->render()
            )

            ->rawColumns([
                'nama_peraturan',
                'uraian',
                'catatan',
                'status',
                'action',
                'dokumen',
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
                        'village-regulations',
                        'public'
                    );

                    $data['dokumen'] = $documentPath;
                }

                // Informasi pembuat data.
                $data['created_by'] = \Auth::id() ?? 1;

                // Insert data.
                VillageRegulation::create($data);
            });

            return response()->json([
                'success' => true,
                'message' => 'Peraturan desa berhasil ditambahkan.',
            ], 201);
        } catch (\Throwable $e) {
            // Hapus dokumen jika proses insert gagal.
            if ($documentPath) {
                \Storage::disk('public')->delete($documentPath);
            }

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan peraturan desa.',
            ], 500);
        }
    }

    public function getUpdate(VillageRegulation $regulation, array $data): \Illuminate\Http\JsonResponse
    {
        $documentPath = null;
        $oldDocumentPath = $regulation->dokumen;
        try {
            \DB::transaction(function () use (
                $regulation,
                $data,
                &$documentPath
            ) {
                // Upload dokumen baru jika tersedia.
                if (
                    isset($data['dokumen']) &&
                    $data['dokumen'] instanceof \Illuminate\Http\UploadedFile
                ) {
                    $documentPath = $data['dokumen']->store(
                        'village-regulations',
                        'public'
                    );

                    $data['dokumen'] = $documentPath;
                } else {
                    // Pertahankan dokumen lama.
                    unset($data['dokumen']);
                }

                // Update data peraturan.
                $regulation->update($data);
            });

            // Hapus dokumen lama setelah transaksi berhasil.
            if ($documentPath && $oldDocumentPath) {
                \Storage::disk('public')->delete($oldDocumentPath);
            }

            return response()->json([
                'success' => true,
                'message' => 'Peraturan desa berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            // Bersihkan dokumen baru jika proses update gagal.
            if ($documentPath) {
                \Storage::disk('public')->delete($documentPath);
            }

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui peraturan desa.',
            ], 500);
        }
    }

    public function getDestroy(Request $request, VillageRegulation $regulation)
    {
        try {
            $forceDelete = $request->boolean('confirm_delete');

            // Data Trash wajib dikonfirmasi.
            if ($regulation->trashed() && !$forceDelete) {
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

            $documentPath = $regulation->dokumen;

            $deleted = \DB::transaction(function () use (
                $regulation,
                $forceDelete
            ) {
                return $forceDelete
                    ? $regulation->forceDelete()
                    : $regulation->delete();
            });

            if (!$deleted) {
                throw new \RuntimeException('Peraturan desa gagal dihapus.');
            }

            // Hapus dokumen hanya jika force delete berhasil.
            if ($forceDelete && filled($documentPath)) {
                \Storage::disk('public')->delete($documentPath);
            }

            return response()->json([
                'success' => true,
                'message' => $forceDelete
                    ? 'Peraturan desa dan dokumennya berhasil dihapus permanen.'
                    : 'Peraturan desa berhasil dipindahkan ke sampah.',
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus peraturan desa.',
            ], 500);
        }
    }
}
