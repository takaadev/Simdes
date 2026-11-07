<?php

namespace Database\Factories;

use App\Models\VillagePosition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VillagePosition>
 */
class VillagePositionFactory extends Factory
{
    protected $model = VillagePosition::class;

    private const POSITIONS = [
        // Pemerintah Desa
        ['kode' => '101', 'nama' => 'Kepala Desa', 'kategori' => '10'],
        ['kode' => '102', 'nama' => 'Sekretaris Desa', 'kategori' => '10'],
        ['kode' => '103', 'nama' => 'Bendahara Desa', 'kategori' => '10'],

        // KAUR
        ['kode' => '201', 'nama' => 'Kepala KAUR Keuangan', 'kategori' => '20'],
        ['kode' => '202', 'nama' => 'Kepala KAUR Tata Usaha dan Umum', 'kategori' => '20'],
        ['kode' => '203', 'nama' => 'Kepala KAUR Perencanaan', 'kategori' => '20'],

        // BPD
        ['kode' => '301', 'nama' => 'Ketua BPD', 'kategori' => '30'],
        ['kode' => '302', 'nama' => 'Wakil Ketua BPD', 'kategori' => '30'],
        ['kode' => '303', 'nama' => 'Sekretaris BPD', 'kategori' => '30'],
        ['kode' => '304', 'nama' => 'Anggota BPD', 'kategori' => '30'],

        // LAD
        ['kode' => '401', 'nama' => 'Ketua LAD', 'kategori' => '40'],
        ['kode' => '402', 'nama' => 'Wakil Ketua LAD', 'kategori' => '40'],
        ['kode' => '403', 'nama' => 'Sekretaris LAD', 'kategori' => '40'],
        ['kode' => '404', 'nama' => 'Anggota LAD', 'kategori' => '40'],
    ];

    public function definition(): array
    {
        return [
            ...fake()->randomElement(self::POSITIONS),
            'is_active' => true,
        ];
    }

    /**
     * Get master position data.
     */
    public static function positions(): array
    {
        return self::POSITIONS;
    }
}
