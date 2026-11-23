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
        ['kode' => 'A1', 'nama' => 'Kepala Desa'],
        ['kode' => 'A2', 'nama' => 'Sekretaris Desa'],
        ['kode' => 'A3', 'nama' => 'Kaur Keuangan'],
        ['kode' => 'A4', 'nama' => 'Kaur Tata Usaha dan Umum'],
        ['kode' => 'A5', 'nama' => 'Kaur Perencanaan'],

        // BPD
        ['kode' => 'B1', 'nama' => 'Ketua BPD'],
        ['kode' => 'B2', 'nama' => 'Wakil Ketua BPD'],
        ['kode' => 'B3', 'nama' => 'Sekretaris BPD'],
        ['kode' => 'B4', 'nama' => 'Anggota BPD'],

        // LAD
        ['kode' => 'C1', 'nama' => 'Ketua LAD'],
        ['kode' => 'C2', 'nama' => 'Wakil Ketua LAD'],
        ['kode' => 'C3', 'nama' => 'Sekretaris LAD'],
        ['kode' => 'C4', 'nama' => 'Anggota LAD'],
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
