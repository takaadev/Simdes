<?php

namespace App\Services;

use App\Enums\DecreeStatusList;
use App\Models\VillageDecree;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VillageDecreeService
{
    /**
     * Get village regulation DataTables with filtering.
     */
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
            $request->filled('tahun')
                && $request->input('tahun') !== 'all',
            fn($query) => $query->whereYear(
                'tgl_keputusan',
                $request->input('tahun')
            )
        );

        $query->latest('created_at');

        // Query statistik: tanpa filter status.
        $summaryQuery = VillageDecree::query()
            ->when(
                $request->filled('tahun')
                    && $request->input('tahun') !== 'all',
                fn($query) => $query->whereYear(
                    'tgl_keputusan',
                    $request->input('tahun')
                )
            )
            ->selectRaw('COUNT(*) as total');

        foreach (DecreeStatusList::cases() as $status) {
            $summaryQuery->selectRaw(
                'SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as ' . $status->value,
                [$status->value]
            );
        }

        $summary = $summaryQuery->first();

        return DataTables::eloquent($query)
            ->with('summary', [
                'total' => (int) $summary->total,
                ...collect(DecreeStatusList::cases())
                    ->mapWithKeys(fn($status) => [
                        $status->value => (int) $summary->{$status->value},
                    ])
                    ->all(),
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

    /**
     * Format date to Indonesian.
     */
    private function formatDate($date): string
    {
        return $date
            ?->locale('id')
            ->translatedFormat('d F Y') ?? '-';
    }

    /**
     * Render tooltip component.
     */
    private function tooltip(?string $value): string
    {
        return view('components.tooltip', [
            'value' => $value,
        ])->render();
    }

    /**
     * Store village regulation.
     */
    public function getStore(array $data): VillageDecree
    {
        $documentPath = null;

        try {
            return \DB::transaction(function () use ($data, &$documentPath) {
                if (isset($data['dokumen']) && $data['dokumen']) {
                    $documentPath = $data['dokumen']->store(
                        'village-decrees',
                        'public'
                    );

                    $data['dokumen'] = $documentPath;
                }

                $data['created_by'] = \Auth::id() ?? 1;

                return VillageDecree::create($data);
            });
        } catch (\Throwable $e) {
            if ($documentPath) {
                \Storage::disk('public')->delete($documentPath);
            }

            report($e);

            throw $e;
        }
    }

    public function getUpdate(VillageDecree $decree, array $data): VillageDecree
    {
        $documentPath = null;
        $oldDocumentPath = $decree->dokumen;

        try {
            $updatedDecree = \DB::transaction(function () use (
                $decree,
                $data,
                &$documentPath
            ) {
                if (
                    isset($data['dokumen']) &&
                    $data['dokumen'] instanceof \Illuminate\Http\UploadedFile
                ) {
                    $documentPath = $data['dokumen']->store(
                        'village-decrees',
                        'public'
                    );

                    $data['dokumen'] = $documentPath;
                } else {
                    unset($data['dokumen']);
                }

                $decree->update($data);

                return $decree->refresh();
            });

            // Hapus dokumen lama setelah transaksi berhasil.
            if ($documentPath && $oldDocumentPath) {
                \Storage::disk('public')->delete($oldDocumentPath);
            }

            return $updatedDecree;
        } catch (\Throwable $e) {
            // Bersihkan dokumen baru jika proses update gagal.
            if ($documentPath) {
                \Storage::disk('public')->delete($documentPath);
            }

            report($e);

            throw $e;
        }
    }

    public function getDestroy(VillageDecree $decree, bool $forceDelete = false): bool
    {
        $documentPath = $decree->dokumen;

        try {
            $deleted = \DB::transaction(function () use (
                $decree,
                $forceDelete
            ) {
                if ($forceDelete) {
                    return $decree->forceDelete();
                }

                return $decree->delete();
            });

            // Hapus dokumen hanya jika force delete berhasil.
            if ($deleted && $forceDelete && $documentPath) {
                \Storage::disk('public')->delete($documentPath);
            }

            return (bool) $deleted;
        } catch (\Throwable $e) {
            report($e);

            throw $e;
        }
    }
}
