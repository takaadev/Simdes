<?php

namespace App\Services;

use App\Enums\RtList;
use App\Models\Recapt;
use App\Models\RecaptDetail;
use App\Models\Resident;
use App\Models\ResidentMutation;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class ResidentRecaptService
{
    public function getData(Request $request)
    {
        // Default tahun & bulan sekarang jika filter kosong atau bernilai 'all'.
        $rawTahun = $request->input('tahun');
        $rawBulan = $request->input('bulan');

        $tahun = (filled($rawTahun) && $rawTahun !== 'all')
            ? (int) $rawTahun
            : now()->year;

        $bulan = (filled($rawBulan) && $rawBulan !== 'all')
            ? (int) $rawBulan
            : now()->month;

        $targetDate = Carbon::create($tahun, $bulan, 1)->toDateString();

        // Base query detail rekap berdasarkan lingkungan (RT) pada periode terpilih.
        $baseQuery = RecaptDetail::query()
            ->join('recapts', 'recapts.id', '=', 'recapt_details.recapt_id')
            ->where('recapts.tahun', (string) $tahun)
            ->whereDate('recapts.bulan', $targetDate);

        // Hitung total seluruh RT untuk baris JUMLAH (tfoot) & widget ringkasan.
        $totals = (clone $baseQuery)
            ->selectRaw("
                COALESCE(SUM(recapt_details.awb_wni_l), 0) as awb_wni_l,
                COALESCE(SUM(recapt_details.awb_wni_p), 0) as awb_wni_p,
                COALESCE(SUM(recapt_details.awb_wna_l), 0) as awb_wna_l,
                COALESCE(SUM(recapt_details.awb_wna_p), 0) as awb_wna_p,
                COALESCE(SUM(recapt_details.awb_jml_kk), 0) as awb_jml_kk,
                COALESCE(SUM(recapt_details.awb_jml_anggota_kk), 0) as awb_jml_anggota_kk,
                COALESCE(SUM(recapt_details.awb_jml_penduduk), 0) as awb_jml_penduduk,

                COALESCE(SUM(recapt_details.tbl_wna_l), 0) as tbl_wna_l,
                COALESCE(SUM(recapt_details.tbl_wna_p), 0) as tbl_wna_p,
                COALESCE(SUM(recapt_details.tbl_wni_l), 0) as tbl_wni_l,
                COALESCE(SUM(recapt_details.tbl_wni_p), 0) as tbl_wni_p,

                COALESCE(SUM(recapt_details.tbd_wna_l), 0) as tbd_wna_l,
                COALESCE(SUM(recapt_details.tbd_wna_p), 0) as tbd_wna_p,
                COALESCE(SUM(recapt_details.tbd_wni_l), 0) as tbd_wni_l,
                COALESCE(SUM(recapt_details.tbd_wni_p), 0) as tbd_wni_p,

                COALESCE(SUM(recapt_details.pbm_wna_l), 0) as pbm_wna_l,
                COALESCE(SUM(recapt_details.pbm_wna_p), 0) as pbm_wna_p,
                COALESCE(SUM(recapt_details.pbm_wni_l), 0) as pbm_wni_l,
                COALESCE(SUM(recapt_details.pbm_wni_p), 0) as pbm_wni_p,

                COALESCE(SUM(recapt_details.pbp_wna_l), 0) as pbp_wna_l,
                COALESCE(SUM(recapt_details.pbp_wna_p), 0) as pbp_wna_p,
                COALESCE(SUM(recapt_details.pbp_wni_l), 0) as pbp_wni_l,
                COALESCE(SUM(recapt_details.pbp_wni_p), 0) as pbp_wni_p,

                COALESCE(SUM(recapt_details.ahb_wna_l), 0) as ahb_wna_l,
                COALESCE(SUM(recapt_details.ahb_wna_p), 0) as ahb_wna_p,
                COALESCE(SUM(recapt_details.ahb_wni_l), 0) as ahb_wni_l,
                COALESCE(SUM(recapt_details.ahb_wni_p), 0) as ahb_wni_p,
                COALESCE(SUM(recapt_details.ahb_jml_kk), 0) as ahb_jml_kk,
                COALESCE(SUM(recapt_details.ahb_jml_anggota_kk), 0) as ahb_jml_anggota_kk,
                COALESCE(SUM(recapt_details.ahb_jml_penduduk), 0) as ahb_jml_penduduk
            ")
            ->first();

        // Query baris per lingkungan (RT) + eager load recapt induk (hindari N+1).
        $query = (clone $baseQuery)
            ->select('recapt_details.*')
            ->with('recapt:id,tahun,bulan')
            ->when(
                !$request->filled('order'),
                fn($q) => $q->orderBy('recapt_details.lingkungan')
            );

        // Catat recapt_id yang tombol hapusnya sudah dirender agar hanya tampil 1 kali.
        $renderedRecaptIds = [];

        return DataTables::eloquent($query)
            ->with([
                'periode' => [
                    'tahun' => (string) $tahun,
                    'bulan' => str_pad((string) $bulan, 2, '0', STR_PAD_LEFT),
                ],
                'totals' => $totals ? array_map('intval', $totals->toArray()) : [],
            ])
            ->filter(function ($query) use ($request) {
                $search = trim((string) $request->input('search.value', ''));

                if ($search !== '') {
                    $query->where(function ($q) use ($search) {
                        $q->where('recapt_details.lingkungan', 'like', "%{$search}%")
                            ->orWhere('recapt_details.catatan', 'like', "%{$search}%");
                    });
                }
            })
            ->addIndexColumn()
            ->editColumn('catatan', fn($row) => $row->catatan ?: '-')
            ->addColumn('action', function ($row) use (&$renderedRecaptIds) {
                $showDelete = !in_array($row->recapt_id, $renderedRecaptIds, true);

                if ($showDelete) {
                    $renderedRecaptIds[] = $row->recapt_id;
                }

                return view('pages.recapt.comp.actions', [
                    'row' => $row,
                    'recapt' => $row->recapt,
                    'showDelete' => $showDelete,
                ])->render();
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function generate(int $tahun, int $bulan, ?int $userId = null): Recapt
    {
        $startOfMonth = Carbon::create($tahun, $bulan, 1)->startOfDay();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $prevMonth = $startOfMonth->copy()->subMonthNoOverflow();

        return DB::transaction(function () use ($tahun, $startOfMonth, $endOfMonth, $prevMonth, $userId) {
            // 1. Buat atau ambil header rekap untuk periode tahun & bulan terpilih.
            $recapt = Recapt::query()->firstOrNew([
                'tahun' => (string) $tahun,
                'bulan' => $startOfMonth->toDateString(),
            ]);

            $recapt->created_by = $userId ?? $recapt->created_by;
            $recapt->save();

            // Simpan catatan lama per lingkungan agar tidak hilang saat generate ulang.
            $existingNotes = $recapt->details()->pluck('catatan', 'lingkungan');

            // 2. Cek apakah rekap bulan sebelumnya sudah ada (untuk saldo awal bulan).
            $prevRecapt = Recapt::query()
                ->with('details')
                ->where('tahun', (string) $prevMonth->year)
                ->whereDate('bulan', $prevMonth->toDateString())
                ->first();

            $prevByLingkungan = $prevRecapt
                ? $prevRecapt->details->keyBy('lingkungan')
                : collect();

            // 3. Ambil agregasi penduduk aktif per RT saat ini (untuk KK & inisialisasi bulan pertama).
            $activeResidents = Resident::query()
                ->where('is_aktif', true)
                ->selectRaw("
                    COALESCE(NULLIF(TRIM(rt), ''), 'Tanpa RT') as rt_key,
                    SUM(CASE WHEN kewarganegaraan = 'WNI' AND jenis_kelamin = 'L' THEN 1 ELSE 0 END) as wni_l,
                    SUM(CASE WHEN kewarganegaraan = 'WNI' AND jenis_kelamin = 'P' THEN 1 ELSE 0 END) as wni_p,
                    SUM(CASE WHEN kewarganegaraan = 'WNA' AND jenis_kelamin = 'L' THEN 1 ELSE 0 END) as wna_l,
                    SUM(CASE WHEN kewarganegaraan = 'WNA' AND jenis_kelamin = 'P' THEN 1 ELSE 0 END) as wna_p,
                    COUNT(DISTINCT family_card_id) as jml_kk
                ")
                ->groupBy('rt_key')
                ->get()
                ->keyBy('rt_key');

            // 4. Ambil agregasi mutasi bulan ini per RT (include soft-deleted residents).
            $mutations = ResidentMutation::query()
                ->join('residents', 'residents.id', '=', 'resident_mutations.resident_id')
                ->whereBetween('resident_mutations.tgl_mutasi', [
                    $startOfMonth->toDateString(),
                    $endOfMonth->toDateString(),
                ])
                ->selectRaw("
                    COALESCE(NULLIF(TRIM(residents.rt), ''), 'Tanpa RT') as rt_key,
                    SUM(CASE WHEN resident_mutations.kategori_mutasi = 'birth'    AND residents.kewarganegaraan = 'WNI' AND residents.jenis_kelamin = 'L' THEN 1 ELSE 0 END) as tbl_wni_l,
                    SUM(CASE WHEN resident_mutations.kategori_mutasi = 'birth'    AND residents.kewarganegaraan = 'WNI' AND residents.jenis_kelamin = 'P' THEN 1 ELSE 0 END) as tbl_wni_p,
                    SUM(CASE WHEN resident_mutations.kategori_mutasi = 'birth'    AND residents.kewarganegaraan = 'WNA' AND residents.jenis_kelamin = 'L' THEN 1 ELSE 0 END) as tbl_wna_l,
                    SUM(CASE WHEN resident_mutations.kategori_mutasi = 'birth'    AND residents.kewarganegaraan = 'WNA' AND residents.jenis_kelamin = 'P' THEN 1 ELSE 0 END) as tbl_wna_p,
                    SUM(CASE WHEN resident_mutations.kategori_mutasi = 'incoming' AND residents.kewarganegaraan = 'WNI' AND residents.jenis_kelamin = 'L' THEN 1 ELSE 0 END) as tbd_wni_l,
                    SUM(CASE WHEN resident_mutations.kategori_mutasi = 'incoming' AND residents.kewarganegaraan = 'WNI' AND residents.jenis_kelamin = 'P' THEN 1 ELSE 0 END) as tbd_wni_p,
                    SUM(CASE WHEN resident_mutations.kategori_mutasi = 'incoming' AND residents.kewarganegaraan = 'WNA' AND residents.jenis_kelamin = 'L' THEN 1 ELSE 0 END) as tbd_wna_l,
                    SUM(CASE WHEN resident_mutations.kategori_mutasi = 'incoming' AND residents.kewarganegaraan = 'WNA' AND residents.jenis_kelamin = 'P' THEN 1 ELSE 0 END) as tbd_wna_p,
                    SUM(CASE WHEN resident_mutations.kategori_mutasi = 'death'    AND residents.kewarganegaraan = 'WNI' AND residents.jenis_kelamin = 'L' THEN 1 ELSE 0 END) as pbm_wni_l,
                    SUM(CASE WHEN resident_mutations.kategori_mutasi = 'death'    AND residents.kewarganegaraan = 'WNI' AND residents.jenis_kelamin = 'P' THEN 1 ELSE 0 END) as pbm_wni_p,
                    SUM(CASE WHEN resident_mutations.kategori_mutasi = 'death'    AND residents.kewarganegaraan = 'WNA' AND residents.jenis_kelamin = 'L' THEN 1 ELSE 0 END) as pbm_wna_l,
                    SUM(CASE WHEN resident_mutations.kategori_mutasi = 'death'    AND residents.kewarganegaraan = 'WNA' AND residents.jenis_kelamin = 'P' THEN 1 ELSE 0 END) as pbm_wna_p,
                    SUM(CASE WHEN resident_mutations.kategori_mutasi = 'outgoing' AND residents.kewarganegaraan = 'WNI' AND residents.jenis_kelamin = 'L' THEN 1 ELSE 0 END) as pbp_wni_l,
                    SUM(CASE WHEN resident_mutations.kategori_mutasi = 'outgoing' AND residents.kewarganegaraan = 'WNI' AND residents.jenis_kelamin = 'P' THEN 1 ELSE 0 END) as pbp_wni_p,
                    SUM(CASE WHEN resident_mutations.kategori_mutasi = 'outgoing' AND residents.kewarganegaraan = 'WNA' AND residents.jenis_kelamin = 'L' THEN 1 ELSE 0 END) as pbp_wna_l,
                    SUM(CASE WHEN resident_mutations.kategori_mutasi = 'outgoing' AND residents.kewarganegaraan = 'WNA' AND residents.jenis_kelamin = 'P' THEN 1 ELSE 0 END) as pbp_wna_p
                ")
                ->groupBy('rt_key')
                ->get()
                ->keyBy('rt_key');

            // 5. Gabungkan daftar RT dari Enum RtList + RT yang muncul di data.
            $rtKeys = collect(array_keys(RtList::options()))
                ->merge($activeResidents->keys())
                ->merge($mutations->keys())
                ->unique()
                ->sort()
                ->values();

            // Hapus detail lama jika rekap bulan ini di-generate ulang.
            $recapt->details()->delete();

            $rows = [];
            $now = now();

            foreach ($rtKeys as $rtKey) {
                $lingkungan = $rtKey === 'Tanpa RT' ? 'Tanpa RT' : "RT {$rtKey}";
                $mut = $mutations->get($rtKey);
                $act = $activeResidents->get($rtKey);
                $prev = $prevByLingkungan->get($lingkungan);

                $tblWniL = (int) ($mut->tbl_wni_l ?? 0);
                $tblWniP = (int) ($mut->tbl_wni_p ?? 0);
                $tblWnaL = (int) ($mut->tbl_wna_l ?? 0);
                $tblWnaP = (int) ($mut->tbl_wna_p ?? 0);

                $tbdWniL = (int) ($mut->tbd_wni_l ?? 0);
                $tbdWniP = (int) ($mut->tbd_wni_p ?? 0);
                $tbdWnaL = (int) ($mut->tbd_wna_l ?? 0);
                $tbdWnaP = (int) ($mut->tbd_wna_p ?? 0);

                $pbmWniL = (int) ($mut->pbm_wni_l ?? 0);
                $pbmWniP = (int) ($mut->pbm_wni_p ?? 0);
                $pbmWnaL = (int) ($mut->pbm_wna_l ?? 0);
                $pbmWnaP = (int) ($mut->pbm_wna_p ?? 0);

                $pbpWniL = (int) ($mut->pbp_wni_l ?? 0);
                $pbpWniP = (int) ($mut->pbp_wni_p ?? 0);
                $pbpWnaL = (int) ($mut->pbp_wna_l ?? 0);
                $pbpWnaP = (int) ($mut->pbp_wna_p ?? 0);

                $currentKk = (int) ($act->jml_kk ?? 0);

                if ($prev) {
                    // Lanjutan dari akhir bulan sebelumnya
                    $awbWniL = (int) $prev->ahb_wni_l;
                    $awbWniP = (int) $prev->ahb_wni_p;
                    $awbWnaL = (int) $prev->ahb_wna_l;
                    $awbWnaP = (int) $prev->ahb_wna_p;
                    $awbJmlKk = (int) $prev->ahb_jml_kk;

                    $ahbWniL = max(0, $awbWniL + $tblWniL + $tbdWniL - $pbmWniL - $pbpWniL);
                    $ahbWniP = max(0, $awbWniP + $tblWniP + $tbdWniP - $pbmWniP - $pbpWniP);
                    $ahbWnaL = max(0, $awbWnaL + $tblWnaL + $tbdWnaL - $pbmWnaL - $pbpWnaL);
                    $ahbWnaP = max(0, $awbWnaP + $tblWnaP + $tbdWnaP - $pbmWnaP - $pbpWnaP);
                    $ahbJmlKk = $currentKk;
                } else {
                    // Bulan pertama: akhir bulan dari residents aktif, awal bulan dihitung mundur
                    $ahbWniL = (int) ($act->wni_l ?? 0);
                    $ahbWniP = (int) ($act->wni_p ?? 0);
                    $ahbWnaL = (int) ($act->wna_l ?? 0);
                    $ahbWnaP = (int) ($act->wna_p ?? 0);
                    $ahbJmlKk = $currentKk;

                    $awbWniL = max(0, $ahbWniL - ($tblWniL + $tbdWniL) + ($pbmWniL + $pbpWniL));
                    $awbWniP = max(0, $ahbWniP - ($tblWniP + $tbdWniP) + ($pbmWniP + $pbpWniP));
                    $awbWnaL = max(0, $ahbWnaL - ($tblWnaL + $tbdWnaL) + ($pbmWnaL + $pbpWnaL));
                    $awbWnaP = max(0, $ahbWnaP - ($tblWnaP + $tbdWnaP) + ($pbmWnaP + $pbpWnaP));
                    $awbJmlKk = $currentKk;
                }

                $awbJmlPenduduk = $awbWniL + $awbWniP + $awbWnaL + $awbWnaP;
                $awbJmlKk = min($awbJmlKk, $awbJmlPenduduk);
                $awbJmlAnggotaKk = max(0, $awbJmlPenduduk - $awbJmlKk);

                $ahbJmlPenduduk = $ahbWniL + $ahbWniP + $ahbWnaL + $ahbWnaP;
                $ahbJmlKk = min($ahbJmlKk, $ahbJmlPenduduk);
                $ahbJmlAnggotaKk = max(0, $ahbJmlPenduduk - $ahbJmlKk);

                $rows[] = [
                    'recapt_id' => $recapt->id,
                    'lingkungan' => $lingkungan,
                    // Awal bulan
                    'awb_wni_l' => $awbWniL,
                    'awb_wni_p' => $awbWniP,
                    'awb_wna_l' => $awbWnaL,
                    'awb_wna_p' => $awbWnaP,
                    'awb_jml_kk' => $awbJmlKk,
                    'awb_jml_anggota_kk' => $awbJmlAnggotaKk,
                    'awb_jml_penduduk' => $awbJmlPenduduk,
                    // Tambahan bulan ini
                    'tbl_wni_l' => $tblWniL,
                    'tbl_wni_p' => $tblWniP,
                    'tbl_wna_l' => $tblWnaL,
                    'tbl_wna_p' => $tblWnaP,
                    'tbd_wni_l' => $tbdWniL,
                    'tbd_wni_p' => $tbdWniP,
                    'tbd_wna_l' => $tbdWnaL,
                    'tbd_wna_p' => $tbdWnaP,
                    // Pengurangan bulan ini
                    'pbm_wni_l' => $pbmWniL,
                    'pbm_wni_p' => $pbmWniP,
                    'pbm_wna_l' => $pbmWnaL,
                    'pbm_wna_p' => $pbmWnaP,
                    'pbp_wni_l' => $pbpWniL,
                    'pbp_wni_p' => $pbpWniP,
                    'pbp_wna_l' => $pbpWnaL,
                    'pbp_wna_p' => $pbpWnaP,
                    // Akhir bulan
                    'ahb_wni_l' => $ahbWniL,
                    'ahb_wni_p' => $ahbWniP,
                    'ahb_wna_l' => $ahbWnaL,
                    'ahb_wna_p' => $ahbWnaP,
                    'ahb_jml_kk' => $ahbJmlKk,
                    'ahb_jml_anggota_kk' => $ahbJmlAnggotaKk,
                    'ahb_jml_penduduk' => $ahbJmlPenduduk,
                    'catatan' => $existingNotes->get($lingkungan),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if (!empty($rows)) {
                RecaptDetail::insert($rows);
            }

            return $recapt->load('details');
        });
    }

    // Di ResidentRecaptService
    public function getUpdateNote(RecaptDetail $detail, array $data): JsonResponse
    {
        if (!$detail->exists) {
            return response()->json([
                'success' => false,
                'message' => 'Data detail rekapitulasi tidak ditemukan.',
            ], 404);
        }

        try {
            $catatan = filled(trim((string) ($data['catatan'] ?? '')))
                ? trim((string) $data['catatan'])
                : null;

            DB::transaction(function () use ($detail, $catatan) {
                $detail->catatan = $catatan;
                $detail->save();
            });

            return response()->json([
                'success' => true,
                'message' => 'Catatan rekapitulasi berhasil disimpan.',
                'data' => [
                    'id' => $detail->id,
                    'catatan' => $detail->catatan,
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal menyimpan catatan rekapitulasi.', [
                'recapt_detail_id' => $detail->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Catatan rekapitulasi gagal disimpan.',
            ], 500);
        }
    }

    public function getDestroy(Recapt $recapt): JsonResponse
    {
        try {
            DB::transaction(function () use ($recapt) {
                $recapt->details()->delete();
                $recapt->delete();
            });

            return response()->json([
                'success' => true,
                'message' => 'Rekapitulasi berhasil dihapus.',
            ]);
        } catch (Throwable $e) {
            Log::error('Gagal menghapus rekapitulasi penduduk.', [
                'recapt_id' => $recapt->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Rekapitulasi gagal dihapus.',
            ], 500);
        }
    }
}
