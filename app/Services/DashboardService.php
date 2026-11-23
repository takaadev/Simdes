<?php

namespace App\Services;

use App\Enums\RtList;
use App\Models\AgendaEntry;
use App\Models\AppSetting;
use App\Models\ExpeditionEntry;
use App\Models\FamilyCard;
use App\Models\Resident;
use App\Models\VillageAsset;
use App\Models\VillageDecree;
use App\Models\VillageLand;
use App\Models\VillageOfficial;
use App\Models\VillagePublication;
use App\Models\VillageRegulation;
use App\Models\VillageTreasuryLand;
use Illuminate\Support\Carbon;

class DashboardService
{
    public function getDataWidget(): array
    {
        $now          = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth   = $now->copy()->endOfMonth();

        // 1. Total Penduduk (is_aktif = 1)
        $residentStats = Resident::query()
            ->where('is_aktif', true)
            ->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN jenis_kelamin IN ('L', 'Laki-laki') THEN 1 ELSE 0 END) as laki_laki,
            SUM(CASE WHEN jenis_kelamin IN ('P', 'Perempuan') THEN 1 ELSE 0 END) as perempuan,
            SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as bulan_ini
        ", [$startOfMonth, $endOfMonth])
            ->first();

        // 2. Kepala Keluarga (KK) & Cakupan Wilayah
        $totalKk = FamilyCard::count();
        $setting = AppSetting::select('total_rw', 'total_rt')->first();

        // 3. Arsip Administrasi Umum (9 Buku Register) — cukup 1 query per model
        $adminModels = [
            'village_regulations'    => VillageRegulation::class,
            'village_decrees'        => VillageDecree::class,
            'village_assets'         => VillageAsset::class,
            'village_officials'      => VillageOfficial::class,
            'village_treasury_lands' => VillageTreasuryLand::class,
            'village_lands'          => VillageLand::class,
            'agenda_entries'         => AgendaEntry::class,
            'expedition_entries'     => ExpeditionEntry::class,
            'village_publications'   => VillagePublication::class,
        ];

        $totalArsip    = 0;
        $arsipBulanIni = 0;
        $bukuAktif     = 0;
        $countsByKey   = [];

        foreach ($adminModels as $key => $modelClass) {
            $stat = $modelClass::query()
                ->selectRaw('COUNT(*) as total, SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as bulan_ini', [
                    $startOfMonth,
                    $endOfMonth,
                ])
                ->first();

            $count             = (int) ($stat->total ?? 0);
            $countsByKey[$key] = $count;
            $totalArsip       += $count;
            $arsipBulanIni    += (int) ($stat->bulan_ini ?? 0);

            if ($count > 0) {
                $bukuAktif++;
            }
        }

        // 4. Aset & Tanah Kas Desa (ambil dari hasil loop di atas, tanpa query ulang)
        $totalInventaris = $countsByKey['village_assets'] ?? 0;
        $totalTanahKas   = $countsByKey['village_treasury_lands'] ?? 0;

        return [
            'penduduk' => [
                'total'     => (int) ($residentStats->total ?? 0),
                'laki_laki' => (int) ($residentStats->laki_laki ?? 0),
                'perempuan' => (int) ($residentStats->perempuan ?? 0),
                'bulan_ini' => (int) ($residentStats->bulan_ini ?? 0),
            ],
            'kk' => [
                'total'    => $totalKk,
                'total_rw' => (int) ($setting?->total_rw ?? 0),
                'total_rt' => (int) ($setting?->total_rt ?? 0),
            ],
            'arsip_umum' => [
                'total'      => $totalArsip,
                'buku_aktif' => $bukuAktif,
                'bulan_ini'  => $arsipBulanIni,
                'label_bln'  => $now->locale('id')->translatedFormat('M Y'),
            ],
            'aset_desa' => [
                'total'      => $totalInventaris + $totalTanahKas,
                'inventaris' => $totalInventaris,
                'tanah_kas'  => $totalTanahKas,
            ],
        ];
    }

    public function getDataPieCart(): array
    {
        $palette = [
            '#174C3A',
            '#477568',
            '#D9AE52',
            '#10382D',
            '#2E7D62',
            '#8FA89B',
        ];

        $counts = Resident::query()
            ->where('is_aktif', true)
            ->whereNotNull('rt')
            ->selectRaw("
            TRIM(rt) as rt_key,
            COUNT(*) as jiwa,
            COUNT(DISTINCT family_card_id) as kk
        ")
            ->groupBy('rt_key')
            ->get()
            ->keyBy('rt_key');

        $items = collect(RtList::cases())->values()->map(function (RtList $rt, int $index) use ($counts, $palette) {
            $stat = $counts->get($rt->value);

            return [
                'id'    => 'rt-' . $rt->value,
                'code'  => $rt->value,
                'name'  => $rt->label(),
                'jiwa'  => (int) ($stat->jiwa ?? 0),
                'kk'    => (int) ($stat->kk ?? 0),
                'color' => $palette[$index % count($palette)],
            ];
        });

        return [
            'total_jiwa' => (int) $items->sum('jiwa'),
            'total_rt'   => $items->count(),
            'items'      => $items->all(),
        ];
    }
}
