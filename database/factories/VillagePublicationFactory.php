<?php

namespace Database\Factories;

use App\Enums\PublicationStatusList;
use App\Enums\RegulationStatusList;
use App\Models\User;
use App\Models\VillagePublication;
use App\Models\VillageRegulation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VillagePublication>
 */
class VillagePublicationFactory extends Factory
{
    protected $model = VillagePublication::class;

    public function definition(): array
    {
        $tglDiundang = fake()->dateTimeBetween('-5 years', 'now');

        $tglBerita = fake()->optional(0.8)
            ->dateTimeBetween($tglDiundang, 'now');

        return [
            // Relasi Peraturan
            'regulation_id' => VillageRegulation::factory(),

            // Pengundangan
            'nomor_diundang' => fake()->unique()->numerify('##/UND/20##'),
            'tgl_diundang' => $tglDiundang->format('Y-m-d'),

            // Berita Desa
            'nomor_berita' => fake()->unique()->numerify('##/20##'),
            'tgl_berita' => $tglBerita?->format('Y-m-d'),

            // Status
            'status' => fake()->randomElement(
                RegulationStatusList::cases()
            ),

            // Relasi Pengguna
            'created_by' => User::factory(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn() => [
            'status' => RegulationStatusList::DRAFT,
        ]);
    }

    public function published(): static
    {
        return $this->state(fn() => [
            'status' => RegulationStatusList::PUBLISHED,
        ]);
    }
}
