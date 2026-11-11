<?php

namespace App\Services;

use App\Enums\AgendaCategoryList;
use App\Enums\AgendaTypeList;
use App\Models\AgendaEntry;
use App\Models\AgendaNumberSequence;
use App\Models\VillageAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Yajra\DataTables\Facades\DataTables;

class AgendaEntryService
{

    public function getData(Request $request)
    {
        $incomingType = AgendaTypeList::INCOMING->value;
        $outgoingType = AgendaTypeList::OUTGOING->value;
        $filterTipe   = $request->input('tipe_agenda', 'all');
        $filterDelete = $request->input('deleted');
        $filterYear   = $request->input('year');

        // ==========================================
        // 1. FILTER DASAR SURAT MASUK & KELUAR
        // ==========================================
        $incoming = AgendaEntry::query()->where('tipe_agenda', $incomingType);
        $outgoing = AgendaEntry::query()->where('tipe_agenda', $outgoingType);

        // Filter SoftDeletes
        if ($filterDelete === 'trashed') {
            $incoming->onlyTrashed();
            $outgoing->onlyTrashed();
        } elseif ($filterDelete === 'all') {
            $incoming->withTrashed();
            $outgoing->withTrashed();
        }

        // Filter Tahun
        if ($request->filled('year') && $filterYear !== 'all') {
            $incoming->whereYear('tgl_surat', $filterYear);
            $outgoing->whereYear('tgl_surat', $filterYear);
        }

        // ==========================================
        // 2. SUMMARY AGENDA (Sebelum Filter Tipe Agenda)
        // ==========================================
        $summaryQuery = AgendaEntry::query();

        if ($filterDelete === 'trashed') {
            $summaryQuery->onlyTrashed();
        } elseif ($filterDelete === 'all') {
            $summaryQuery->withTrashed();
        }

        if ($request->filled('year') && $filterYear !== 'all') {
            $summaryQuery->whereYear('created_at', $filterYear);
        }

        $summary = $summaryQuery
            ->reorder()
            ->selectRaw("
            COUNT(*) AS total_agenda,
            COALESCE(SUM(CASE WHEN tipe_agenda = ? THEN 1 ELSE 0 END), 0) AS total_incoming,
            COALESCE(SUM(CASE WHEN tipe_agenda = ? THEN 1 ELSE 0 END), 0) AS total_outgoing
        ", [$incomingType, $outgoingType])
            ->first();

        // ==========================================
        // 3. FILTER TIPE AGENDA (Kosongkan Sisi Sebelahnya)
        // ==========================================
        if ($request->filled('tipe_agenda') && $filterTipe !== 'all') {
            if ($filterTipe === $incomingType) {
                $outgoing->whereRaw('1 = 0');
            } elseif ($filterTipe === $outgoingType) {
                $incoming->whereRaw('1 = 0');
            }
        }

        // ==========================================
        // 4. INDEXING SURAT MASUK & SURAT KELUAR
        // ==========================================
        $incomingSub = $incoming->selectRaw("
            ROW_NUMBER() OVER (ORDER BY id ASC) AS urutan_agenda,
            id AS incoming_id,
            tanggal_terima,
            nomor_surat AS incoming_nomor,
            tgl_surat AS incoming_tanggal,
            pengirim,
            isi_surat AS incoming_ket,
            catatan AS incoming_catatan,
            dokumen AS incoming_dokumen,
            deleted_at AS incoming_deleted_at
        ")->toBase();

        $outgoingSub = $outgoing->selectRaw("
            ROW_NUMBER() OVER (ORDER BY id ASC) AS urutan_agenda,
            id AS outgoing_id,
            kategori_agenda AS outgoing_kategori,
            tanggal_kirim,
            nomor_surat AS outgoing_nomor,
            tgl_surat AS outgoing_tanggal,
            penerima,
            isi_surat AS outgoing_ket,
            catatan AS outgoing_catatan,
            dokumen AS outgoing_dokumen,
            deleted_at AS outgoing_deleted_at
        ")->toBase();

        // ==========================================
        // 5. GABUNG SURAT MASUK DAN SURAT KELUAR (FULL OUTER JOIN)
        // ==========================================
        $incomingQuery = DB::query()
            ->fromSub(clone $incomingSub, 'i')
            ->leftJoinSub(clone $outgoingSub, 'o', function ($join) {
                $join->on('i.urutan_agenda', '=', 'o.urutan_agenda');
            })
            ->select([
                'i.urutan_agenda',
                'i.incoming_id',
                'o.outgoing_id',

                // Surat Masuk
                'i.tanggal_terima',
                'i.incoming_nomor',
                'i.incoming_tanggal',
                'i.pengirim',
                'i.incoming_ket',
                'i.incoming_catatan',
                'i.incoming_dokumen',
                'i.incoming_deleted_at',

                // Surat Keluar
                'o.outgoing_kategori',
                'o.tanggal_kirim',
                'o.outgoing_nomor',
                'o.outgoing_tanggal',
                'o.penerima',
                'o.outgoing_ket',
                'o.outgoing_catatan',
                'o.outgoing_dokumen',
                'o.outgoing_deleted_at',
            ]);

        $outgoingOnlyQuery = DB::query()
            ->fromSub(clone $outgoingSub, 'o')
            ->leftJoinSub(clone $incomingSub, 'i', function ($join) {
                $join->on('i.urutan_agenda', '=', 'o.urutan_agenda');
            })
            ->whereNull('i.incoming_id')
            ->selectRaw("
                o.urutan_agenda,
                NULL AS incoming_id,
                o.outgoing_id,

                NULL AS tanggal_terima,
                NULL AS incoming_nomor,
                NULL AS incoming_tanggal,
                NULL AS pengirim,
                NULL AS incoming_ket,
                NULL AS incoming_catatan,
                NULL AS incoming_dokumen,
                NULL AS incoming_deleted_at,

                o.outgoing_kategori,
                o.tanggal_kirim,
                o.outgoing_nomor,
                o.outgoing_tanggal,
                o.penerima,
                o.outgoing_ket,
                o.outgoing_catatan,
                o.outgoing_dokumen,
                o.outgoing_deleted_at
            ");

        $combinedQuery = $incomingQuery->unionAll($outgoingOnlyQuery);

        $query = DB::query()
            ->fromSub($combinedQuery, 'agenda')
            ->select('agenda.*');

        // ==========================================
        // 6. DATATABLES RESPONSE
        // ==========================================
        return DataTables::query($query)
            ->order(function ($q) use ($request) {
                if (! $request->has('order')) {
                    $q->orderBy('agenda.urutan_agenda', 'asc');
                }
            })
            ->with('summary', [
                'total_agenda'   => (int) ($summary->total_agenda ?? 0),
                'total_incoming' => (int) ($summary->total_incoming ?? 0),
                'total_outgoing' => (int) ($summary->total_outgoing ?? 0),
            ])
            ->addIndexColumn()

            // ==========================================
            // INCOMING - SURAT MASUK
            // ==========================================
            ->editColumn('incoming_nomor', fn($row) => $row->incoming_nomor ?: '-')
            ->editColumn('incoming_tanggal', fn($row) => $row->incoming_tanggal ? $this->formatDate($row->incoming_tanggal) : '-')
            ->addColumn('incoming_pengirim', fn($row) => $row->pengirim ? $this->tooltip($row->pengirim) : '-')
            ->addColumn('incoming_tanggal_terima', fn($row) => $row->tanggal_terima ? $this->formatDate($row->tanggal_terima) : '-')
            ->editColumn('incoming_ket', fn($row) => $row->incoming_ket ? $this->tooltip($row->incoming_ket) : '-')

            // ==========================================
            // OUTGOING - SURAT KELUAR
            // ==========================================
            ->editColumn('outgoing_nomor', fn($row) => $row->outgoing_nomor ?: '-')
            ->editColumn('outgoing_tanggal', fn($row) => $row->outgoing_tanggal ? $this->formatDate($row->outgoing_tanggal) : '-')
            ->addColumn('outgoing_tujuan', fn($row) => $row->penerima ? $this->tooltip($row->penerima) : '-')
            ->addColumn('outgoing_tanggal_kirim', fn($row) => $row->tanggal_kirim ? $this->formatDate($row->tanggal_kirim) : '-')
            ->editColumn('outgoing_ket', fn($row) => $row->outgoing_ket ? $this->tooltip($row->outgoing_ket) : '-')

            // ==========================================
            // DOKUMEN
            // ==========================================
            ->addColumn('dokumen', function ($row) {
                $html = '';

                foreach ([$row->incoming_dokumen, $row->outgoing_dokumen] as $document) {
                    if ($document) {
                        $html .= view('components.view-pdf', [
                            'src'   => $document,
                            'title' => 'Dokumen Agenda Surat',
                        ])->render();
                    }
                }

                return $html ?: '-';
            })

            // ==========================================
            // ACTION SURAT MASUK
            // ==========================================
            ->addColumn('incoming_action', function ($row) {
                if (! $row->incoming_id) {
                    return '-';
                }

                $actionRow = (new AgendaEntry())->forceFill([
                    'id'             => $row->incoming_id,
                    'tipe_agenda'    => AgendaTypeList::INCOMING,
                    'nomor_surat'    => $row->incoming_nomor,
                    'tanggal_terima' => $row->tanggal_terima,
                    'tgl_surat'      => $row->incoming_tanggal,
                    'pengirim'       => $row->pengirim,
                    'isi_surat'      => $row->incoming_ket,
                    'catatan'        => $row->incoming_catatan,
                    'dokumen'        => $row->incoming_dokumen,
                    'deleted_at'     => $row->incoming_deleted_at,
                ]);

                return view('pages.agenda.comp.actions-incoming', [
                    'row' => $actionRow,
                ])->render();
            })

            // ==========================================
            // ACTION SURAT KELUAR
            // ==========================================
            ->addColumn('outgoing_action', function ($row) {
                if (! $row->outgoing_id) {
                    return '-';
                }

                $actionRow = (new AgendaEntry())->forceFill([
                    'id'             => $row->outgoing_id,
                    'tipe_agenda'    => AgendaTypeList::OUTGOING,
                    'kategori_agenda' => $row->outgoing_kategori,
                    'nomor_surat'    => $row->outgoing_nomor,
                    'tanggal_kirim'  => $row->tanggal_kirim,
                    'tgl_surat'      => $row->outgoing_tanggal,
                    'penerima'       => $row->penerima,
                    'isi_surat'      => $row->outgoing_ket,
                    'catatan'        => $row->outgoing_catatan,
                    'dokumen'        => $row->outgoing_dokumen,
                    'deleted_at'     => $row->outgoing_deleted_at,
                ]);

                return view('pages.agenda.comp.actions-outgoing', [
                    'row' => $actionRow,
                ])->render();
            })

            ->rawColumns([
                'incoming_pengirim',
                'incoming_ket',
                'outgoing_tujuan',
                'outgoing_ket',
                'dokumen',
                'incoming_action',
                'outgoing_action',
            ])
            ->toJson();
    }

    public function getGenerateNumber(AgendaCategoryList $category, string $villageCode = 'DS', bool $useLegacyCode = false): string
    {
        return DB::transaction(function () use ($category, $villageCode, $useLegacyCode) {
            $date = now();
            $year = (int) $date->format('Y');
            $month = (int) $date->format('n');
            DB::table('agenda_number_sequences')->insertOrIgnore([
                'category' => $category->value,
                'year' => $year,
                'last_number' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Kunci baris agar tidak digunakan bersamaan.
            $sequence = AgendaNumberSequence::query()
                ->where('category', $category->value)
                ->where('year', $year)
                ->lockForUpdate()
                ->first();

            if (! $sequence) {
                throw new RuntimeException(
                    'Gagal mengambil nomor urut surat.'
                );
            }

            $nextNumber = $sequence->last_number + 1;

            $sequence->update([
                'last_number' => $nextNumber,
            ]);

            return $category->formatNumber(
                sequence: $nextNumber,
                year: $year,
                month: $month,
                villageCode: $villageCode,
                useLegacyCode: $useLegacyCode
            );
        });
    }

    private function formatDate($date): string
    {
        if (empty($date)) {
            return '-';
        }

        return \Illuminate\Support\Carbon::parse($date)
            ->locale('id')
            ->translatedFormat('d F Y');
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
            \DB::transaction(function () use (&$data, &$documentPath) {
                // Upload dokumen surat.
                if (
                    isset($data['dokumen']) &&
                    $data['dokumen'] instanceof \Illuminate\Http\UploadedFile
                ) {
                    $documentPath = $data['dokumen']->store(
                        'agenda-documents',
                        'public'
                    );

                    $data['dokumen'] = $documentPath;
                }

                // Informasi pembuat data.
                $data['created_by'] = \Auth::id();

                // Simpan agenda surat.
                \App\Models\AgendaEntry::create($data);
            });

            return response()->json([
                'success' => true,
                'message' => 'Agenda surat berhasil ditambahkan.',
            ], 201);
        } catch (\Throwable $e) {
            // Hapus dokumen jika proses penyimpanan gagal.
            if ($documentPath) {
                \Storage::disk('public')->delete($documentPath);
            }

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan agenda surat.',
            ], 500);
        }
    }

    public function getUpdate(AgendaEntry $agenda, array $data): \Illuminate\Http\JsonResponse
    {
        $documentPath = null;
        $oldDocumentPath = $agenda->dokumen;

        try {
            \DB::transaction(function () use (
                $agenda,
                &$data,
                &$documentPath
            ) {
                // Upload dokumen baru jika tersedia.
                if (
                    isset($data['dokumen']) &&
                    $data['dokumen'] instanceof \Illuminate\Http\UploadedFile
                ) {
                    $documentPath = $data['dokumen']->store(
                        'agenda-documents',
                        'public'
                    );

                    $data['dokumen'] = $documentPath;
                } else {
                    // Pertahankan dokumen lama.
                    unset($data['dokumen']);
                }

                // Update data agenda surat.
                $agenda->update($data);
            });

            // Hapus dokumen lama setelah transaksi berhasil.
            if ($documentPath && $oldDocumentPath) {
                \Storage::disk('public')->delete($oldDocumentPath);
            }

            return response()->json([
                'success' => true,
                'message' => 'Agenda surat berhasil diperbarui.',
            ]);
        } catch (\Throwable $e) {
            // Hapus dokumen baru jika proses update gagal.
            if ($documentPath) {
                \Storage::disk('public')->delete($documentPath);
            }

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui agenda surat.',
            ], 500);
        }
    }

    public function getDestroy(Request $request, AgendaEntry $agenda): \Illuminate\Http\JsonResponse
    {
        try {
            $forceDelete = $request->boolean('confirm_delete');

            // Data Trash wajib dikonfirmasi untuk dihapus permanen.
            if ($agenda->trashed() && ! $forceDelete) {
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

            $documentPath = $agenda->dokumen;

            $deleted = \DB::transaction(function () use ($agenda, $forceDelete) {
                return $forceDelete
                    ? $agenda->forceDelete()
                    : $agenda->delete();
            });

            if (! $deleted) {
                throw new \RuntimeException('Data agenda surat gagal dihapus.');
            }

            // Hapus dokumen setelah force delete berhasil.
            if ($forceDelete && filled($documentPath)) {
                \Storage::disk('public')->delete($documentPath);
            }

            return response()->json([
                'success' => true,
                'message' => $forceDelete
                    ? 'Agenda surat dan dokumennya berhasil dihapus permanen.'
                    : 'Agenda surat berhasil dipindahkan ke sampah.',
            ]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus agenda surat.',
            ], 500);
        }
    }
}
