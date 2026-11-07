<?php

namespace App\Services;


use App\Models\VillageDecree;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VillageDecreeService
{

    public function getData(Request $request)
    {
        $query = VillageDecree::query()
            ->select([
                'id',
                'nomor_keputusan',
                'tgl_keputusan',
                'nama_keputusan',
                'uraian',
                'nomor_dilaporkan',
                'tgl_dilaporkan',
                'catatan',
                'dokumen',
                'status',
                'created_by',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);

        // Filter status hanya untuk tabel.
        $query->when(
            $request->filled('status')
                && $request->input('status') !== 'all',
            fn($query) => $query->where(
                'status',
                $request->input('status')
            )
        );

        // Filter tahun keputusan.
        $query->when(
            $request->filled('year')
                && $request->input('year') !== 'all',
            fn($query) => $query->whereYear(
                'tgl_keputusan',
                $request->input('year')
            )
        );

        // Filter status data berdasarkan SoftDeletes.
        $query->when(
            $request->input('deleted') === 'trashed',
            fn($query) => $query->onlyTrashed()
        );

        $query->latest('created_at');

        // Query statistik keputusan.
        $summaryQuery = VillageDecree::query()
            ->when(
                $request->filled('tahun')
                    && $request->input('tahun') !== 'all',
                fn($query) => $query->whereYear(
                    'tgl_keputusan',
                    $request->input('tahun')
                )
            );

        // Filter status data untuk statistik.
        $summaryQuery->when(
            $request->input('deleted') === 'trashed',
            fn($query) => $query->onlyTrashed()
        );

        $summary = $summaryQuery
            ->selectRaw('
            COUNT(*) as total,
            SUM(
                CASE
                    WHEN nomor_dilaporkan IS NOT NULL
                    AND nomor_dilaporkan != ""
                    THEN 1 ELSE 0
                END
            ) as reported,
            SUM(
                CASE
                    WHEN nomor_dilaporkan IS NULL
                    OR nomor_dilaporkan = ""
                    THEN 1 ELSE 0
                END
            ) as unreported
        ')
            ->first();

        return DataTables::eloquent($query)
            ->with('summary', [
                'total' => (int) $summary->total,
                'reported' => (int) $summary->reported,
                'unreported' => (int) $summary->unreported,
            ])

            ->addIndexColumn()

            ->editColumn(
                'nomor_keputusan',
                fn($row) => $row->nomor_keputusan ?: '-'
            )
            ->editColumn(
                'tgl_keputusan',
                fn($row) => $this->formatDate($row->tgl_keputusan)
            )
            ->editColumn(
                'nama_keputusan',
                fn($row) => $this->tooltip($row->nama_keputusan)
            )
            ->editColumn(
                'uraian',
                fn($row) => $this->tooltip($row->uraian)
            )
            ->editColumn(
                'nomor_dilaporkan',
                fn($row) => $row->nomor_dilaporkan ?: '-'
            )
            ->editColumn(
                'tgl_dilaporkan',
                fn($row) => $this->formatDate($row->tgl_dilaporkan)
            )
            ->editColumn('dokumen', function ($row) {
                if (! $row->dokumen) {
                    return '-';
                }

                return view('components.view-pdf', [
                    'src' => $row->dokumen,
                    'title' => 'Dokumen Keputusan Kepala Desa',
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
                fn($row) => view('pages.villagedecree.comp.actions', [
                    'row' => $row,
                ])->render()
            )

            ->rawColumns([
                'nama_keputusan',
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

    public function getStore(array $data)
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
                        'village-decrees',
                        'public'
                    );

                    $data['dokumen'] = $documentPath;
                }
                $data['created_by'] = \Auth::id() ?? 1;
                VillageDecree::create($data);
            });
            return response()->json([
                'success' => true,
                'message' => 'Keputusan kepala desa berhasil ditambahkan.',
            ], 201);
        } catch (\Throwable $e) {
            // Hapus dokumen jika proses insert gagal.
            if ($documentPath) {
                \Storage::disk('public')->delete($documentPath);
            }
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan keputusan kepala desa.',
            ], 500);
        }
    }

    public function getUpdate(VillageDecree $decree, array $data): \Illuminate\Http\JsonResponse
    {
        $documentPath = null;
        $oldDocumentPath = $decree->dokumen;
        try {
            \DB::transaction(function () use ($decree, $data, &$documentPath) {
                if (isset($data['dokumen']) && $data['dokumen'] instanceof \Illuminate\Http\UploadedFile) {
                    $documentPath = $data['dokumen']->store('village-decrees', 'public');
                    $data['dokumen'] = $documentPath;
                } else {
                    unset($data['dokumen']);
                }
                $decree->update($data);
            });
            if ($documentPath && $oldDocumentPath) {
                \Storage::disk('public')->delete($oldDocumentPath);
            }

            return response()->json([
                'success' => true,
                'message' => 'Keputusan kepala desa berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            // Bersihkan dokumen baru jika proses update gagal.
            if ($documentPath) {
                \Storage::disk('public')->delete($documentPath);
            }

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui keputusan kepala desa.',
            ], 500);
        }
    }

    public function getDestroy(Request $request, VillageDecree $decree)
    {
        try {
            $forceDelete = $request->boolean('confirm_delete');
            if ($decree->trashed() && !$forceDelete) {
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

            $documentPath = $decree->dokumen;

            $deleted = \DB::transaction(function () use ($decree, $forceDelete) {
                return $forceDelete ? $decree->forceDelete() : $decree->delete();
            });

            if (!$deleted) {
                throw new \RuntimeException('Keputusan kepala desa gagal dihapus.');
            }
            if ($forceDelete && filled($documentPath)) {
                \Storage::disk('public')->delete($documentPath);
            }

            return response()->json([
                'success' => true,
                'message' => $forceDelete
                    ? 'Keputusan kepala desa dan dokumennya berhasil dihapus permanen.'
                    : 'Keputusan kepala desa berhasil dipindahkan ke sampah.',
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus keputusan kepala desa.',
            ], 500);
        }
    }
}
