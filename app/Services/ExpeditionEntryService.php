<?php

namespace App\Services;

use App\Models\AgendaEntry;
use App\Models\ExpeditionEntry;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ExpeditionEntryService
{
    public function getData(Request $request)
    {
        $query = ExpeditionEntry::query()
            ->with([
                'agendaEntry:id,dokumen',
            ])
            ->select([
                'id',
                'agenda_entry_id',
                'tanggal_kirim',
                'nomor_surat',
                'tgl_surat',
                'penerima',
                'isi_surat',
                'catatan',
                'created_by',
                'created_at',
                'updated_at',
                'deleted_at',
            ]);

        // Filter tahun.
        $query->when(
            $request->filled('year')
                && $request->input('year') !== 'all',
            fn($query) => $query->whereYear(
                'tanggal_kirim',
                $request->input('year')
            )
        );

        // Filter status SoftDeletes.
        $query->when(
            $request->input('deleted') === 'trashed',
            fn($query) => $query->onlyTrashed()
        );

        // Urutkan data terbaru.
        $query->orderByDesc('tanggal_kirim')
            ->orderByDesc('id');

        // Query statistik.
        $summaryQuery = ExpeditionEntry::query()
            ->when(
                $request->filled('year')
                    && $request->input('year') !== 'all',
                fn($query) => $query->whereYear(
                    'tanggal_kirim',
                    $request->input('year')
                )
            )
            ->when(
                $request->input('deleted') === 'trashed',
                fn($query) => $query->onlyTrashed()
            );

        $summary = $summaryQuery
            ->selectRaw('
            COUNT(*) as total_entries,
            COUNT(DISTINCT agenda_entry_id) as total_linked_agendas
        ')
            ->first();

        return DataTables::eloquent($query)

            ->with('summary', [
                'total_entries' => (int) $summary->total_entries,
                'total_linked_agendas' => (int) $summary->total_linked_agendas,
            ])

            ->addIndexColumn()

            ->editColumn(
                'tanggal_kirim',
                fn($row) => $this->formatDate($row->tanggal_kirim)
            )

            ->editColumn(
                'tgl_surat',
                fn($row) => $this->formatDate($row->tgl_surat)
            )

            ->editColumn(
                'nomor_surat',
                fn($row) => $this->tooltip($row->nomor_surat)
            )

            ->editColumn(
                'penerima',
                fn($row) => $this->tooltip($row->penerima)
            )

            ->editColumn(
                'isi_surat',
                fn($row) => $this->tooltip($row->isi_surat)
            )

            ->editColumn(
                'catatan',
                fn($row) => $this->tooltip($row->catatan)
            )

            ->editColumn('dokumen', function ($row) {
                if (! $row->agendaEntry?->dokumen) {
                    return '-';
                }

                return view('components.view-pdf', [
                    'src' => $row->agendaEntry?->dokumen,
                    'title' => 'Dokumen Ekspedisi',
                ])->render();
            })


            ->addColumn('action', function ($row) {
                return view('pages.expedition.comp.actions', [
                    'row' => $row,
                ])->render();
            })

            ->rawColumns([
                'nomor_surat',
                'penerima',
                'isi_surat',
                'catatan',
                'dokumen',
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

                // Ambil agenda surat keluar.
                $agenda = AgendaEntry::query()
                    ->whereKey($data['agenda_entry_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                // Pastikan belum tercatat dalam Buku Ekspedisi.
                $alreadyExists = ExpeditionEntry::query()
                    ->where('agenda_entry_id', $agenda->id)
                    ->exists();

                if ($alreadyExists) {
                    throw new \RuntimeException(
                        'Surat tersebut sudah tercatat dalam Buku Ekspedisi.'
                    );
                }

                // Siapkan data ekspedisi.
                $expeditionData = [
                    'agenda_entry_id' => $agenda->id,
                    'tanggal_kirim'   => $agenda->tanggal_kirim,
                    'nomor_surat'     => $agenda->nomor_surat,
                    'tgl_surat'       => $agenda->tgl_surat,
                    'penerima'        => $agenda->penerima,
                    'isi_surat'       => filled($data['isi_surat'] ?? null)
                        ? $data['isi_surat']
                        : $agenda->isi_surat,
                    'catatan'         => filled($data['catatan'] ?? null)
                        ? $data['catatan']
                        : $agenda->catatan,
                    'created_by'      => \Auth::id() ?? 1,
                ];

                ExpeditionEntry::create($expeditionData);
            });

            return response()->json([
                'success' => true,
                'message' => 'Buku Ekspedisi berhasil ditambahkan.',
            ], 201);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan Buku Ekspedisi.',
            ], 500);
        }
    }

    public function getUpdate(ExpeditionEntry $expedition, array $data): \Illuminate\Http\JsonResponse
    {
        try {
            \DB::transaction(function () use ($expedition, $data) {

                // Ambil agenda surat keluar.
                $agenda = AgendaEntry::query()
                    ->whereKey($data['agenda_entry_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                // Pastikan agenda tidak digunakan ekspedisi lain.
                $alreadyExists = ExpeditionEntry::query()
                    ->where('agenda_entry_id', $agenda->id)
                    ->where('id', '!=', $expedition->getKey())
                    ->exists();

                if ($alreadyExists) {
                    throw new \RuntimeException(
                        'Surat tersebut sudah tercatat dalam Buku Ekspedisi.'
                    );
                }

                // Siapkan data pembaruan.
                $expeditionData = [
                    'agenda_entry_id' => $agenda->id,
                    'tanggal_kirim'   => $agenda->tanggal_kirim,
                    'nomor_surat'     => $agenda->nomor_surat,
                    'tgl_surat'       => $agenda->tgl_surat,
                    'penerima'        => $agenda->penerima,
                    'isi_surat'       => filled($data['isi_surat'] ?? null)
                        ? $data['isi_surat']
                        : $agenda->isi_surat,
                    'catatan'         => filled($data['catatan'] ?? null)
                        ? $data['catatan']
                        : $agenda->catatan,
                ];

                $expedition->update($expeditionData);
            });

            return response()->json([
                'success' => true,
                'message' => 'Buku Ekspedisi berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui Buku Ekspedisi.',
            ], 500);
        }
    }

    public function getDestroy(Request $request, ExpeditionEntry $expedition): \Illuminate\Http\JsonResponse
    {
        try {
            $forceDelete = $request->boolean('confirm_delete');

            // Data Trash wajib dikonfirmasi.
            if ($expedition->trashed() && ! $forceDelete) {
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
                $expedition,
                $forceDelete
            ) {
                return $forceDelete
                    ? $expedition->forceDelete()
                    : $expedition->delete();
            });

            if (! $deleted) {
                throw new \RuntimeException(
                    'Data Buku Ekspedisi gagal dihapus.'
                );
            }

            return response()->json([
                'success' => true,
                'message' => $forceDelete
                    ? 'Buku Ekspedisi berhasil dihapus permanen.'
                    : 'Buku Ekspedisi berhasil dipindahkan ke sampah.',
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus Buku Ekspedisi.',
            ], 500);
        }
    }
}
