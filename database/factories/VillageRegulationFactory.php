<?php

namespace Database\Factories;

use App\Enums\RegulationStatusList;
use App\Enums\RegulationTypeList;
use App\Models\User;
use App\Models\VillageRegulation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VillageRegulation>
 */
class VillageRegulationFactory extends Factory
{
    protected $model = VillageRegulation::class;

    public function definition(): array
    {
        $tglDitetapkan = fake()->dateTimeBetween('-5 years', 'now');

        $tglKesepakatan = fake()->optional(0.7)
            ->dateTimeBetween('-5 years', $tglDitetapkan);

        $tglDilaporkan = fake()->optional(0.8)
            ->dateTimeBetween($tglDitetapkan, 'now');

        return [
            // Identitas Peraturan
            'jenis_peraturan' => fake()->randomElement(
                RegulationTypeList::cases()
            ),
            'nomor_peraturan' => fake()->unique()->numerify('## Tahun 20##'),
            'tgl_ditetapkan' => $tglDitetapkan->format('Y-m-d'),
            'nama_peraturan' => 'Peraturan tentang ' . fake()->unique()->sentence(4),

            // Uraian Peraturan
            'uraian' => fake()->optional()->paragraphs(3, true),

            // Kesepakatan
            'tgl_kesepakatan' => $tglKesepakatan?->format('Y-m-d'),

            // Pelaporan
            // Pelaporan
            'nomor_dilaporkan' => fake()->optional(0.8, null)
                ->randomElement([
                    fake()->unique()->numerify('##/LAP/20##'),
                ]),
            'tgl_dilaporkan' => $tglDilaporkan?->format('Y-m-d'),
            // Keterangan
            'catatan' => fake()->optional()->sentence(),

            // Dokumen
            'dokumen' => null,

            // Status
            'status' => 'draft',
            // 'status' => fake()->randomElement(
            //     RegulationStatusList::cases()
            // ),

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

    public function active(): static
    {
        return $this->state(fn() => [
            'status' => RegulationStatusList::ACTIVE,
        ]);
    }

    public function revoked(): static
    {
        return $this->state(fn() => [
            'status' => RegulationStatusList::REVOKED,
        ]);
    }

    public function amended(): static
    {
        return $this->state(fn() => [
            'status' => RegulationStatusList::AMENDED,
        ]);
    }
}
