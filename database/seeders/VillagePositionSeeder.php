<?php

namespace Database\Seeders;

use App\Models\VillagePosition;
use Illuminate\Database\Seeder;

class VillagePositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            // Pemerintah Desa
            ['code' => '101', 'name' => 'Kepala Desa', 'category' => '10'],
            ['code' => '102', 'name' => 'Sekretaris Desa', 'category' => '10'],
            ['code' => '103', 'name' => 'Bendahara Desa', 'category' => '10'],

            // KAUR
            ['code' => '201', 'name' => 'Kepala KAUR Keuangan', 'category' => '20'],
            ['code' => '202', 'name' => 'Kepala KAUR Tata Usaha dan Umum', 'category' => '20'],
            ['code' => '203', 'name' => 'Kepala KAUR Perencanaan', 'category' => '20'],

            // BPD
            ['code' => '301', 'name' => 'Ketua BPD', 'category' => '30'],
            ['code' => '302', 'name' => 'Wakil Ketua BPD', 'category' => '30'],
            ['code' => '303', 'name' => 'Sekretaris BPD', 'category' => '30'],
            ['code' => '304', 'name' => 'Anggota BPD', 'category' => '30'],

            // LAD
            ['code' => '401', 'name' => 'Ketua LAD', 'category' => '40'],
            ['code' => '402', 'name' => 'Wakil Ketua LAD', 'category' => '40'],
            ['code' => '403', 'name' => 'Sekretaris LAD', 'category' => '40'],
            ['code' => '404', 'name' => 'Anggota LAD', 'category' => '40'],
        ];

        foreach ($positions as $position) {
            VillagePosition::withTrashed()->updateOrCreate(
                ['code' => $position['code']],
                [
                    ...$position,
                    'is_active' => true,
                    'deleted_at' => null,
                ]
            );
        }
    }
}
