<?php

namespace Database\Factories;

use App\Enums\DecreeStatusList;
use App\Models\User;
use App\Models\VillageDecree;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VillageDecree>
 */
class VillageDecreeFactory extends Factory
{
    protected $model = VillageDecree::class;

    public function definition(): array
    {
        $decreeDate = fake()->dateTimeBetween('-5 years', 'now');

        return [
            'nomor_keputusan' => fake()->unique()->numerify('###/SK/####'),

            'tgl_keputusan' => $decreeDate->format('Y-m-d'),

            'nama_keputusan' => 'Keputusan Kepala Desa tentang ' . fake()->sentence(4),

            'uraian' => fake()->optional()->paragraph(),

            'nomor_dilaporkan' => fake()->optional(0.7)->passthrough(
                fake()->unique()->numerify('####/LAP/####')
            ),

            'tgl_dilaporkan' => fake()->optional()->dateTimeBetween(
                $decreeDate,
                'now'
            )?->format('Y-m-d'),

            'dokumen' => null,

            'status' => fake()->randomElement(
                DecreeStatusList::cases()
            ),

            'catatan' => fake()->optional()->sentence(),

            'created_by' => User::factory(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn() => [
            'status' => DecreeStatusList::DRAFT,
        ]);
    }

    public function active(): static
    {
        return $this->state(fn() => [
            'status' => DecreeStatusList::ACTIVE,
        ]);
    }

    public function revoked(): static
    {
        return $this->state(fn() => [
            'status' => DecreeStatusList::REVOKED,
        ]);
    }

    public function amended(): static
    {
        return $this->state(fn() => [
            'status' => DecreeStatusList::AMENDED,
        ]);
    }
}
