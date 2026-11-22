<?php

namespace App\Services;

use App\Models\AppSetting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getDataWidget(): array
    {
        $now         = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth   = $now->copy()->endOfMonth();

        // 1. Total Penduduk (is_aktif = 1)
        $residentStats = DB::table('residents')
            ->where('is_aktif', true)
            ->selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN jenis_kelamin IN ('L', 'Laki-laki') THEN 1 ELSE 0 END) as laki_laki,
                SUM(CASE WHEN jenis_kelamin IN ('P', 'Perempuan') THEN 1 ELSE 0 END) as perempuan,
                SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as bulan_ini
            ", [$startOfMonth, $endOfMonth])
            ->first();

        // 2. Kepala Keluarga (KK) & Cakupan Wilayah
        $totalKk = DB::table('family_cards')->count();
        $setting = AppSetting::select('total_rw', 'total_rt')->first();

        // 3. Arsip Administrasi Umum (9 Buku Register)
        $adminTables = [
            'village_regulations',
            'village_decrees',
            'village_assets',
            'village_officials',
            'village_treasury_lands',
            'village_lands',
            'agenda_entries',
            'expedition_entries',
            'village_publications',
        ];

        $totalArsip         = 0;
        $arsipBulanIni      = 0;
        $bukuAktif          = 0;

        foreach ($adminTables as $table) {
            $count = DB::table($table)->count();
            $totalArsip += $count;

            if ($count > 0) {
                $bukuAktif++;
            }

            $arsipBulanIni += DB::table($table)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
        }

        // 4. Aset & Tanah Kas Desa
        $totalInventaris = DB::table('village_assets')->count();
        $totalTanahKas   = DB::table('village_treasury_lands')->count();

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
                'label_bln'  => $now->translatedFormat('M Y'),
            ],
            'aset_desa' => [
                'total'      => $totalInventaris + $totalTanahKas,
                'inventaris' => $totalInventaris,
                'tanah_kas'  => $totalTanahKas,
            ],
        ];
    }
}