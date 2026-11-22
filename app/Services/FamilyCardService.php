<?php

namespace App\Services;

use App\Models\FamilyCard;
use App\Models\FamilyCardMutation;
use App\Models\Resident;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class FamilyCardService
{
    public function getData(Request $request)
    {
        $isTrashed = $request->input('deleted') === 'trashed';
        $sortDate = in_array($request->input('sort_date'), ['asc', 'desc'], true)
            ? $request->input('sort_date')
            : 'desc';

        // =====================================================
        // QUERY DASAR
        // =====================================================
        $baseQuery = Resident::query()
            ->join('family_cards', 'family_cards.id', '=', 'residents.family_card_id')
            ->whereNull('family_cards.deleted_at')
            ->when($isTrashed, fn($q) => $q->onlyTrashed());

        // =====================================================
        // FILTER TAHUN DIMASUKKAN
        // =====================================================
        $applyFilters = function ($query) use ($request) {
            $query->when(
                $request->filled('year') && $request->input('year') !== 'all',
                fn($q) => $q->whereYear('family_cards.created_at', $request->input('year'))
            );

            return $query;
        };

        // =====================================================
        // PENCARIAN DATATABLE
        // =====================================================
        $applySearch = function ($query) use ($request) {
            $keyword = trim((string) $request->input('search.value', ''));

            if ($keyword !== '') {
                $query->where(function ($q) use ($keyword) {
                    $q->where('family_cards.nomor_kk', 'like', "%{$keyword}%")
                        ->orWhere('residents.nama_lengkap', 'like', "%{$keyword}%")
                        ->orWhere('residents.nik', 'like', "%{$keyword}%")
                        ->orWhere('residents.tempat_lahir', 'like', "%{$keyword}%")
                        ->orWhere('residents.agama', 'like', "%{$keyword}%")
                        ->orWhere('residents.pendidikan', 'like', "%{$keyword}%")
                        ->orWhere('residents.pekerjaan', 'like', "%{$keyword}%")
                        ->orWhere('residents.alamat', 'like', "%{$keyword}%")
                        ->orWhere('residents.status_dalam_keluarga', 'like', "%{$keyword}%")
                        ->orWhere('residents.ayah', 'like', "%{$keyword}%")
                        ->orWhere('residents.ibu', 'like', "%{$keyword}%");
                });
            }

            return $query;
        };

        // =====================================================
        // HITUNG JUMLAH KELOMPOK KK
        // =====================================================
        $countGroups = fn($query) => (int) (clone $query)
            ->reorder()
            ->distinct('family_cards.id')
            ->count('family_cards.id');

        $recordsTotal = $countGroups($baseQuery);

        $filteredQuery = $applyFilters(clone $baseQuery);
        $searchedQuery = $applySearch(clone $filteredQuery);

        $recordsFiltered = $countGroups($searchedQuery);

        // =====================================================
        // RINGKASAN BUKU KARTU KELUARGA
        // =====================================================
        $summary = (clone $filteredQuery)
            ->reorder()
            ->selectRaw("
            COUNT(residents.id) AS total_penduduk,
            COALESCE(SUM(CASE WHEN residents.jenis_kelamin = 'L' THEN 1 ELSE 0 END), 0) AS laki_laki,
            COALESCE(SUM(CASE WHEN residents.jenis_kelamin = 'P' THEN 1 ELSE 0 END), 0) AS perempuan,
            COUNT(DISTINCT family_cards.id) AS total_kk
        ")
            ->first();

        // =====================================================
        // PAGINATION BERDASARKAN KELOMPOK KK
        // =====================================================
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 15);

        $groupQuery = (clone $searchedQuery)
            ->reorder()
            ->select([
                'family_cards.id',
                'family_cards.nomor_kk',
                'family_cards.created_at',
            ])
            ->groupBy('family_cards.id', 'family_cards.nomor_kk', 'family_cards.created_at')
            ->orderBy('family_cards.created_at', $sortDate)
            ->orderBy('family_cards.id', $sortDate);

        if ($length !== -1) {
            $groupQuery->offset($start)->limit(max(1, $length));
        }

        $familyCardIds = $groupQuery->pluck('family_cards.id');

        if ($familyCardIds->isEmpty()) {
            return response()->json([
                'draw' => (int) $request->input('draw'),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => [],
                'summary' => [
                    'total_penduduk' => (int) ($summary?->total_penduduk ?? 0),
                    'laki_laki' => (int) ($summary?->laki_laki ?? 0),
                    'perempuan' => (int) ($summary?->perempuan ?? 0),
                    'total_kk' => (int) ($summary?->total_kk ?? 0),
                ],
            ]);
        }

        $statusOrderSql = "
        CASE %s
            WHEN 'Kepala Keluarga' THEN 1
            WHEN 'Istri' THEN 2
            WHEN 'Anak' THEN 3
            WHEN 'Menantu' THEN 4
            WHEN 'Cucu' THEN 5
            WHEN 'Orang Tua' THEN 6
            WHEN 'Mertua' THEN 7
            WHEN 'Famili Lain' THEN 8
            WHEN 'Pembantu' THEN 9
            ELSE 10
        END ASC
    ";

        // =====================================================
        // AMBIL ANGGOTA DARI KK TERPILIH
        // =====================================================
        $orderedIds = $familyCardIds->map(fn($id) => (int) $id)->implode(',');

        $residents = $applySearch($applyFilters(clone $baseQuery))
            ->whereIn('residents.family_card_id', $familyCardIds)
            ->select([
                'residents.id',
                'residents.family_card_id',
                'family_cards.nomor_kk',
                'residents.nama_lengkap',
                'residents.nik',
                'residents.jenis_kelamin',
                'residents.tempat_lahir',
                'residents.tgl_lahir',
                'residents.gol_darah',
                'residents.agama',
                'residents.pendidikan',
                'residents.pekerjaan',
                'residents.alamat',
                'residents.rt',
                'residents.rw',
                'residents.status_perkawinan',
                'residents.tempat_dikeluarkan',
                'residents.tgl_dikeluarkan',
                'residents.status_dalam_keluarga',
                'residents.kewarganegaraan',
                'residents.ayah',
                'residents.ibu',
                'residents.tgl_mulai_tinggal',
                'residents.deleted_at',
            ])
            ->orderByRaw("FIELD(residents.family_card_id, {$orderedIds})")
            ->orderByRaw(sprintf($statusOrderSql, 'residents.status_dalam_keluarga'))
            ->orderBy('residents.nama_lengkap', 'asc')
            ->get();

        // =====================================================
        // AMBIL DATA KK BESERTA ANGGOTANYA UNTUK AKSI / MODAL
        // =====================================================
        $familyCards = FamilyCard::query()
            ->with([
                'residents' => fn($q) => $q
                    ->when($isTrashed, fn($sub) => $sub->withTrashed())
                    ->orderByRaw(sprintf($statusOrderSql, 'status_dalam_keluarga'))
                    ->orderBy('nama_lengkap'),
            ])
            ->whereIn('id', $familyCardIds)
            ->get()
            ->keyBy('id');

        $enumLabel = function ($field): string {
            if ($field instanceof \BackedEnum) {
                return method_exists($field, 'label') ? $field->label() : (string) $field->value;
            }

            return filled($field) ? (string) $field : '-';
        };

        $enumValue = fn($field): ?string => $field instanceof \BackedEnum
            ? (string) $field->value
            : (filled($field) ? (string) $field : null);

        // =====================================================
        // FORMAT DATA RESPONSE
        // =====================================================
        $lastFamilyCardId = null;

        $data = $residents->values()->map(
            function ($row, $index) use (&$lastFamilyCardId, $familyCards, $enumLabel, $enumValue) {
                $rt = $enumValue($row->rt);
                $rw = $enumValue($row->rw);

                $alamat = collect([
                    $row->alamat,
                    $rt ? "RT {$rt}" : null,
                    $rw ? "RW {$rw}" : null,
                ])->filter()->implode(', ');

                $isFirstMember = $lastFamilyCardId !== $row->family_card_id;
                $lastFamilyCardId = $row->family_card_id;

                return [
                    'DT_RowIndex' => $index + 1,
                    'id' => $row->id,
                    'family_card_id' => $row->family_card_id,
                    'nomor_kk' => $row->nomor_kk ?: '-',
                    'nama_lengkap' => $this->tooltip($row->nama_lengkap),
                    'nik' => $row->nik ?: '-',
                    'jenis_kelamin' => $enumLabel($row->jenis_kelamin),
                    'tempat_lahir' => $row->tempat_lahir ?: '-',
                    'tgl_lahir' => $this->formatDate($row->tgl_lahir),
                    'gol_darah' => $enumLabel($row->gol_darah),
                    'agama' => $enumLabel($row->agama),
                    'pendidikan' => $enumLabel($row->pendidikan),
                    'pekerjaan' => $enumLabel($row->pekerjaan),
                    'alamat' => $this->tooltip($alamat ?: '-'),
                    'status_perkawinan' => $enumLabel($row->status_perkawinan),
                    'tempat_dikeluarkan' => $row->tempat_dikeluarkan ?: '-',
                    'tgl_dikeluarkan' => $this->formatDate($row->tgl_dikeluarkan),
                    'status_dalam_keluarga' => $enumLabel($row->status_dalam_keluarga),
                    'kewarganegaraan' => $enumLabel($row->kewarganegaraan),
                    'ayah' => $row->ayah ?: '-',
                    'ibu' => $row->ibu ?: '-',
                    'tgl_mulai_tinggal' => $this->formatDate($row->tgl_mulai_tinggal),
                    'action' => $isFirstMember
                        ? view('pages.familycard.comp.actions', [
                            'row' => $row,
                            'familyCard' => $familyCards->get($row->family_card_id),
                        ])->render()
                        : '',
                ];
            }
        );

        return response()->json([
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
            'summary' => [
                'total_penduduk' => (int) ($summary?->total_penduduk ?? 0),
                'laki_laki' => (int) ($summary?->laki_laki ?? 0),
                'perempuan' => (int) ($summary?->perempuan ?? 0),
                'total_kk' => (int) ($summary?->total_kk ?? 0),
            ],
        ]);
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
            DB::transaction(function () use ($data) {
                $resident = Resident::query()
                    ->lockForUpdate()
                    ->findOrFail($data['resident_id']);

                // Pastikan penduduk bukan Kepala Keluarga.
                if ($resident->status_dalam_keluarga === 'Kepala Keluarga') {
                    throw new \RuntimeException(
                        'Penduduk masih berstatus Kepala Keluarga.'
                    );
                }

                $familyCard = FamilyCard::create([
                    'nomor_kk' => $data['kk'],
                ]);

                // Tetapkan penduduk sebagai Kepala Keluarga.
                $resident->update([
                    'family_card_id' => $familyCard->id,
                    'kk' => $familyCard->nomor_kk,
                    'status_dalam_keluarga' => 'Kepala Keluarga',
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Data Kartu Keluarga berhasil ditambahkan.',
            ], 201);
        } catch (Throwable $e) {
            Log::error('Gagal menambahkan data Kartu Keluarga.', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Data Kartu Keluarga gagal ditambahkan.',
            ], 500);
        }
    }

    public function getUpdate(FamilyCard $familyCard, array $data): JsonResponse
    {
        try {
            DB::transaction(function () use ($familyCard, $data) {
                // Perbarui nomor Kartu Keluarga.
                $familyCard->update([
                    'nomor_kk' => $data['kk'],
                ]);

                // Sinkronkan nomor KK seluruh penduduk yang terhubung.
                Resident::withTrashed()
                    ->where('family_card_id', $familyCard->id)
                    ->update([
                        'kk' => $familyCard->nomor_kk,
                    ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Nomor Kartu Keluarga berhasil diperbarui.',
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal memperbarui data Kartu Keluarga.', [
                'family_card_id' => $familyCard->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Nomor Kartu Keluarga gagal diperbarui.',
            ], 500);
        }
    }

    public function getDestroy(Request $request, FamilyCard $familyCard): JsonResponse
    {
        try {
            $forceDelete = $request->boolean('confirm_delete');

            if ($familyCard->trashed() && !$forceDelete) {
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

            $deleted = DB::transaction(function () use (
                $familyCard,
                $forceDelete
            ) {
                if ($forceDelete) {
                    // Lepaskan hubungan seluruh penduduk dengan KK.
                    Resident::withTrashed()
                        ->where('family_card_id', $familyCard->id)
                        ->update([
                            'family_card_id' => null,
                        ]);

                    // Hapus KK secara permanen.
                    return $familyCard->forceDelete();
                }

                // Hapus KK ke sampah (soft delete).
                return $familyCard->delete();
            });

            if (!$deleted) {
                throw new \RuntimeException(
                    'Data Kartu Keluarga gagal dihapus.'
                );
            }

            return response()->json([
                'success' => true,
                'message' => $forceDelete
                    ? 'Data Kartu Keluarga berhasil dihapus permanen. Hubungan penduduk dengan KK telah dilepaskan.'
                    : 'Data Kartu Keluarga berhasil dipindahkan ke sampah.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Gagal menghapus data Kartu Keluarga.', [
                'family_card_id' => $familyCard->id,
                'force_delete' => $forceDelete ?? false,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data Kartu Keluarga.',
            ], 500);
        }
    }

    public function getMutation(array $data): JsonResponse
    {
        try {
            DB::transaction(function () use ($data) {
                // Cari dan kunci data penduduk.
                $resident = Resident::query()
                    ->where('nik', $data['nik'])
                    ->lockForUpdate()
                    ->firstOrFail();
                if (
                    $resident->status_dalam_keluarga === 'Kepala Keluarga'
                ) {
                    throw new RuntimeException(
                        'Penduduk masih berstatus Kepala Keluarga. Silakan ubah hubungan dalam keluarga melalui Buku Induk Penduduk terlebih dahulu.'
                    );
                }

                // Pastikan penduduk memiliki KK asal.
                if (!$resident->family_card_id) {
                    throw new RuntimeException(
                        'Penduduk belum terhubung dengan Kartu Keluarga.'
                    );
                }

                // Cari dan kunci KK tujuan.
                $familyCardTujuan = FamilyCard::query()
                    ->where('nomor_kk', $data['kk_tujuan'])
                    ->lockForUpdate()
                    ->firstOrFail();

                // Pastikan KK asal dan tujuan berbeda.
                if ($resident->family_card_id === $familyCardTujuan->id) {
                    throw new RuntimeException(
                        'Penduduk sudah terdaftar dalam Kartu Keluarga tersebut.'
                    );
                }

                // Periksa hubungan Kepala Keluarga.
                if ($data['hubungan'] === 'Kepala Keluarga') {
                    $hasHead = Resident::query()
                        ->where('family_card_id', $familyCardTujuan->id)
                        ->where('status_dalam_keluarga', 'Kepala Keluarga')
                        ->exists();
                    if ($hasHead) {
                        throw new RuntimeException(
                            'Kartu Keluarga tujuan sudah memiliki Kepala Keluarga.'
                        );
                    }
                }

                // Simpan riwayat mutasi.
                FamilyCardMutation::create([
                    'resident_id' => $resident->id,
                    'kk_asal' => $resident->family_card_id,
                    'kk_tujuan' => $familyCardTujuan->id,
                    'hubungan' => $data['hubungan'],
                    'tgl_mutasi' => $data['tgl_mutasi'],
                    'catatan' => $data['catatan'] ?? null,
                ]);

                // Perbarui data penduduk.
                $resident->update([
                    'family_card_id' => $familyCardTujuan->id,
                    'kk' => $familyCardTujuan->nomor_kk,
                    'status_dalam_keluarga' => $data['hubungan'],
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Mutasi Kartu Keluarga berhasil diproses.',
            ], 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data penduduk atau Kartu Keluarga tujuan tidak ditemukan.',
            ], 404);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (Throwable $e) {
            Log::error('Gagal memproses mutasi Kartu Keluarga.', [
                'nik' => $data['nik'] ?? null,
                'kk_tujuan' => $data['kk_tujuan'] ?? null,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses mutasi Kartu Keluarga.',
            ], 500);
        }
    }

    public function getPrintData(Request $request): array
    {
        $isTrashed = $request->input('deleted') === 'trashed';
        $sortDate = in_array($request->input('sort_date'), ['asc', 'desc'], true)
            ? $request->input('sort_date')
            : 'desc';
        $search = trim((string) $request->input('search', ''));

        $statusOrderSql = "
        CASE residents.status_dalam_keluarga
            WHEN 'Kepala Keluarga' THEN 1
            WHEN 'Istri' THEN 2
            WHEN 'Anak' THEN 3
            WHEN 'Menantu' THEN 4
            WHEN 'Cucu' THEN 5
            WHEN 'Orang Tua' THEN 6
            WHEN 'Mertua' THEN 7
            WHEN 'Famili Lain' THEN 8
            WHEN 'Pembantu' THEN 9
            ELSE 10
        END ASC
    ";

        $residents = Resident::query()
            ->join('family_cards', 'family_cards.id', '=', 'residents.family_card_id')
            ->whereNull('family_cards.deleted_at')
            ->when($isTrashed, fn($q) => $q->onlyTrashed())
            ->when(
                $request->filled('year') && $request->input('year') !== 'all',
                fn($q) => $q->whereYear('family_cards.created_at', $request->input('year'))
            )
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('family_cards.nomor_kk', 'like', "%{$search}%")
                        ->orWhere('residents.nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('residents.nik', 'like', "%{$search}%")
                        ->orWhere('residents.alamat', 'like', "%{$search}%")
                        ->orWhere('residents.ayah', 'like', "%{$search}%")
                        ->orWhere('residents.ibu', 'like', "%{$search}%");
                });
            })
            ->select([
                'residents.id',
                'residents.family_card_id',
                'family_cards.nomor_kk',
                'family_cards.created_at as kk_created_at',
                'residents.nama_lengkap',
                'residents.nik',
                'residents.jenis_kelamin',
                'residents.tempat_lahir',
                'residents.tgl_lahir',
                'residents.gol_darah',
                'residents.agama',
                'residents.pendidikan',
                'residents.pekerjaan',
                'residents.alamat',
                'residents.rt',
                'residents.rw',
                'residents.status_perkawinan',
                'residents.tempat_dikeluarkan',
                'residents.tgl_dikeluarkan',
                'residents.status_dalam_keluarga',
                'residents.kewarganegaraan',
                'residents.ayah',
                'residents.ibu',
                'residents.tgl_mulai_tinggal',
                'residents.catatan',
            ])
            ->orderBy('family_cards.created_at', $sortDate)
            ->orderBy('family_cards.id', $sortDate)
            ->orderByRaw($statusOrderSql)
            ->orderBy('residents.nama_lengkap', 'asc')
            ->get();

        // Kelompokkan per family_card_id agar rowspan di view cetak rapi.
        $groups = $residents->groupBy('family_card_id');

        $parts = [];
        if ($request->filled('year') && $request->input('year') !== 'all') {
            $parts[] = 'Tahun: ' . $request->input('year');
        }
        if ($isTrashed) {
            $parts[] = 'Status Data: Dihapus';
        }
        $parts[] = 'Total KK: ' . $groups->count();

        return [
            'groups' => $groups,
            'totalResidents' => $residents->count(),
            'filterSummary' => implode(' | ', $parts),
        ];
    }
}
