<?php

namespace App\Services;

use App\Enums\RegulationStatusList;
use App\Models\VillageRegulation;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VillageRegulationService
{
    /**
     * Get village regulation DataTables with filtering.
     */
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
            $request->filled('tahun')
                && $request->input('tahun') !== 'all',
            fn($query) => $query->whereYear(
                'tgl_ditetapkan',
                $request->input('tahun')
            )
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
            )
            ->selectRaw('COUNT(*) as total');

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
    public function getStore(array $data): VillageRegulation
    {
        $documentPath = null;

        try {
            return \DB::transaction(function () use ($data, &$documentPath) {
                if (isset($data['dokumen'])) {
                    $documentPath = $data['dokumen']->store(
                        'village-regulations',
                        'public'
                    );

                    $data['dokumen'] = $documentPath;
                }
                $data['created_by'] = \Auth::id() ?? 1;
                return VillageRegulation::create($data);
            });
        } catch (\Throwable $e) {
            if ($documentPath) {
                \Storage::disk('public')->delete($documentPath);
            }

            report($e);

            throw $e;
        }
    }

    public function getUpdate(VillageRegulation $regulation, array $data): VillageRegulation
    {
        $documentPath = null;
        $oldDocumentPath = $regulation->dokumen;

        try {
            $updatedRegulation = \DB::transaction(function () use (
                $regulation,
                $data,
                &$documentPath
            ) {
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
                    unset($data['dokumen']);
                }

                $regulation->update($data);

                return $regulation->refresh();
            });

            // Hapus dokumen lama setelah transaksi berhasil.
            if ($documentPath && $oldDocumentPath) {
                \Storage::disk('public')->delete($oldDocumentPath);
            }

            return $updatedRegulation;
        } catch (\Throwable $e) {
            // Bersihkan dokumen baru jika proses update gagal.
            if ($documentPath) {
                \Storage::disk('public')->delete($documentPath);
            }

            report($e);

            throw $e;
        }
    }

    public function getDestroy(VillageRegulation $regulation, bool $forceDelete = false): bool
    {
        $documentPath = $regulation->dokumen;

        try {
            $deleted = \DB::transaction(function () use (
                $regulation,
                $forceDelete
            ) {
                if ($forceDelete) {
                    return $regulation->forceDelete();
                }

                return $regulation->delete();
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
