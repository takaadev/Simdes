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
        // =====================================================
        // QUERY DASAR
        // =====================================================
        $baseQuery = Resident::query()
            ->join(
                'family_cards',
                'family_cards.id',
                '=',
                'residents.family_card_id'
            )
            ->whereNull('family_cards.deleted_at');

        // =====================================================
        // FILTER DATA PENDUDUK
        // =====================================================
        $applyFilters = function ($query) use ($request) {
            $query->when(
                $request->filled('jenis_kelamin')
                    && $request->input('jenis_kelamin') !== 'all',
                fn($query) => $query->where(
                    'residents.jenis_kelamin',
                    $request->input('jenis_kelamin')
                )
            );

            $query->when(
                $request->filled('kewarganegaraan')
                    && $request->input('kewarganegaraan') !== 'all',
                fn($query) => $query->where(
                    'residents.kewarganegaraan',
                    $request->input('kewarganegaraan')
                )
            );

            $query->when(
                $request->input('deleted') === 'trashed',
                fn($query) => $query->onlyTrashed()
            );

            return $query;
        };

        // =====================================================
        // PENCARIAN DATATABLE
        // =====================================================
        $applySearch = function ($query) use ($request) {
            $keyword = trim((string) $request->input('search.value'));

            if ($keyword !== '') {
                $query->where(function ($query) use ($keyword) {
                    $query->where('family_cards.nomor_kk', 'like', "%{$keyword}%")
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
        // HELPER MENGHITUNG JUMLAH KELOMPOK KK
        // =====================================================
        $countGroups = function ($query) {
            $groups = (clone $query)
                ->reorder()
                ->select('family_cards.id')
                ->distinct()
                ->toBase();

            return DB::query()
                ->fromSub($groups, 'family_groups')
                ->count();
        };

        // =====================================================
        // TOTAL KK
        // =====================================================
        $recordsTotal = $countGroups($baseQuery);

        // =====================================================
        // FILTER KK
        // =====================================================
        $filteredQuery = $applyFilters(clone $baseQuery);

        // =====================================================
        // PENCARIAN KK
        // =====================================================
        $searchedQuery = $applySearch(clone $filteredQuery);

        $recordsFiltered = $countGroups($searchedQuery);

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
            ])
            ->groupBy(
                'family_cards.id',
                'family_cards.nomor_kk'
            )
            ->orderBy('family_cards.nomor_kk')
            ->orderBy('family_cards.id');

        if ($length !== -1) {
            $groupQuery
                ->offset($start)
                ->limit(max(1, $length));
        }

        $familyCardIds = $groupQuery
            ->pluck('family_cards.id');

        // =====================================================
        // AMBIL SELURUH ANGGOTA DARI KK TERPILIH
        // =====================================================
        $query = $applyFilters(
            Resident::query()
                ->join(
                    'family_cards',
                    'family_cards.id',
                    '=',
                    'residents.family_card_id'
                )
                ->whereNull('family_cards.deleted_at')
                ->whereIn(
                    'residents.family_card_id',
                    $familyCardIds
                )
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
        );

        // Terapkan pencarian agar anggota yang ditampilkan
        // sesuai dengan hasil pencarian.
        $query = $applySearch($query);

        // =====================================================
        // PENGURUTAN ANGGOTA KELUARGA
        // =====================================================
        $query
            ->orderBy('family_cards.nomor_kk')
            ->orderByRaw("
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
        ")
            ->orderBy('residents.nama_lengkap');

        $residents = $query->get();

        // =====================================================
        // RINGKASAN BUKU KARTU KELUARGA
        // =====================================================
        $summaryQuery = $applyFilters(clone $baseQuery);

        $summary = $summaryQuery
            ->select([])
            ->selectRaw("
            COUNT(residents.id) AS total_penduduk,
            SUM(
                CASE
                    WHEN residents.jenis_kelamin = 'L' THEN 1
                    ELSE 0
                END
            ) AS laki_laki,
            SUM(
                CASE
                    WHEN residents.jenis_kelamin = 'P' THEN 1
                    ELSE 0
                END
            ) AS perempuan,
            COUNT(DISTINCT family_cards.id) AS total_kk
        ")
            ->first();

        // =====================================================
        // FORMAT DATA RESPONSE
        // =====================================================
        // =====================================================
        // AMBIL DATA KK BESERTA SELURUH ANGGOTA
        // =====================================================
        $familyCards = FamilyCard::query()
            ->with(['residents' => function ($query) {
                $query->orderByRaw("
            CASE status_dalam_keluarga
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
            ")->orderBy('nama_lengkap');
            }])->whereIn('id', $familyCardIds)->get()->keyBy('id');
        $lastFamilyCardId = null;

        $data = $residents
            ->values()
            ->map(
                function ($row, $index) use (&$lastFamilyCardId, $familyCards) {
                    $alamat = collect([
                        $row->alamat,
                        $row->rt ? "RT {$row->rt}" : null,
                        $row->rw ? "RW {$row->rw}" : null,
                    ])->filter()->implode(', ');

                    // Aksi hanya ditampilkan satu kali untuk setiap KK.
                    $isFirstMember = $lastFamilyCardId !== $row->family_card_id;

                    $lastFamilyCardId = $row->family_card_id;

                    return [
                        'DT_RowIndex' => $index + 1,

                        'id' => $row->id,
                        'family_card_id' => $row->family_card_id,
                        'nomor_kk' => $row->nomor_kk ?: '-',

                        'nama_lengkap' => $this->tooltip(
                            $row->nama_lengkap
                        ),

                        'nik' => $row->nik ?: '-',

                        'jenis_kelamin' =>
                        $row->jenis_kelamin?->label() ?? '-',

                        'tempat_lahir' => $row->tempat_lahir ?: '-',

                        'tgl_lahir' => $this->formatDate(
                            $row->tgl_lahir
                        ),

                        'gol_darah' => $row->gol_darah ?: '-',

                        'agama' => $row->agama?->label() ?? '-',

                        'pendidikan' => $row->pendidikan?->label() ?? '-',

                        'pekerjaan' => $row->pekerjaan?->label() ?? '-',

                        'alamat' => $this->tooltip($alamat ?: '-'),

                        'status_perkawinan' =>
                        $row->status_perkawinan?->label() ?? '-',

                        'tempat_dikeluarkan' =>
                        $row->tempat_dikeluarkan ?: '-',

                        'tgl_dikeluarkan' => $this->formatDate(
                            $row->tgl_dikeluarkan
                        ),

                        'status_dalam_keluarga' =>
                        $row->status_dalam_keluarga ?: '-',

                        'kewarganegaraan' =>
                        $row->kewarganegaraan?->label() ?? '-',

                        'ayah' => $row->ayah ?: '-',

                        'ibu' => $row->ibu ?: '-',

                        'tgl_mulai_tinggal' => $this->formatDate(
                            $row->tgl_mulai_tinggal
                        ),

                        // Aksi KK hanya ditampilkan pada satu anggota.
                        'action' => $isFirstMember
                            ? view('pages.familycard.comp.actions', [
                                'row' => $row,
                                'familyCard' => $familyCards->get($row->family_card_id),
                            ])->render()
                            : '',
                    ];
                }
            );

        // =====================================================
        // RESPONSE DATATABLE
        // =====================================================
        return response()->json([
            'draw' => (int) $request->input('draw'),

            // Total kelompok KK.
            'recordsTotal' => $recordsTotal,

            // Total kelompok KK setelah filter dan pencarian.
            'recordsFiltered' => $recordsFiltered,

            // Semua anggota dari kelompok KK pada halaman aktif.
            'data' => $data,

            // Ringkasan.
            'summary' => [
                'total_penduduk' => (int) ($summary->total_penduduk ?? 0),
                'laki_laki' => (int) ($summary->laki_laki ?? 0),
                'perempuan' => (int) ($summary->perempuan ?? 0),
                'total_kk' => (int) ($summary->total_kk ?? 0),
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
}
