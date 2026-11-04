<?php

namespace App\Services;

use App\Models\VillageRegulation;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\Facades\DataTables;

class VillageRegulationService
{
    /**
     * Get village regulation query.
     */
    public function getData(): Builder
    {
        return VillageRegulation::query()
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
            ])
            ->latest('tgl_ditetapkan');
    }

    /**
     * Get village regulation DataTables.
     */
    public function getDataTable()
    {
        return DataTables::eloquent($this->getData())
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
                fn($row) => view('pages.villageregulation.actions', [
                    'row' => $row,
                ])->render()
            )

            ->rawColumns([
                'nama_peraturan',
                'uraian',
                'catatan',
                'status',
                'action',
                'dokumen'
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
}
