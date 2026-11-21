<?php

namespace Database\Factories;

use App\Models\VillageLand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VillageLand>
 */
class VillageLandFactory extends Factory
{
    protected $model = VillageLand::class;

    public function definition(): array
    {
        return [
            'owner_name' => fake()->company(),

            // Jumlah Luas Tanah
            'jml' => fake()->randomFloat(2, 100, 10000),

            // Status Hak Tanah (M2)
            // Sudah Bersertifikat
            'hm' => fake()->randomFloat(2, 0, 5000),
            'hgb' => fake()->randomFloat(2, 0, 3000),
            'hp' => fake()->randomFloat(2, 0, 2000),
            'hgu' => fake()->randomFloat(2, 0, 2000),
            'hpl' => fake()->randomFloat(2, 0, 1000),

            // Belum Bersertifikat
            'ma' => fake()->randomFloat(2, 0, 1000),
            'vi' => fake()->randomFloat(2, 0, 1000),
            'tn' => fake()->randomFloat(2, 0, 1000),

            // Penggunaan Tanah - Non Pertanian
            'perumahan' => fake()->randomFloat(2, 0, 3000),
            'dagang_jasa' => fake()->randomFloat(2, 0, 2000),
            'perkantoran' => fake()->randomFloat(2, 0, 1000),
            'industri' => fake()->randomFloat(2, 0, 2000),
            'fasilitas_umum' => fake()->randomFloat(2, 0, 1000),

            // Penggunaan Tanah - Pertanian
            'sawah' => fake()->randomFloat(2, 0, 3000),
            'tegalan' => fake()->randomFloat(2, 0, 2000),
            'perkebunan' => fake()->randomFloat(2, 0, 3000),
            'peternakan_perikanan' => fake()->randomFloat(2, 0, 1000),
            'hutan_belukar' => fake()->randomFloat(2, 0, 2000),
            'hutan_lebat' => fake()->randomFloat(2, 0, 3000),

            // Mutasi dan Luas Lainnya
            'mutasi_tanah' => fake()->randomFloat(2, 0, 1000),
            'tanah_kosong' => fake()->randomFloat(2, 0, 2000),
            'lainnya' => fake()->randomFloat(2, 0, 1000),

            // Keterangan
            'catatan' => fake()->optional()->sentence(),

            // Audit
            'created_by' => null,
        ];
    }
}
