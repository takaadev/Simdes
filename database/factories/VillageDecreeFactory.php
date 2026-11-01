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
        $stipulationDate = fake()->dateTimeBetween('-5 years', 'now');

        return [
            'register_number' => fake()->unique()->numerify('####/SK/####'),

            'decree_number' => fake()->numerify('## Tahun ####'),

            'decree_title' => 'Keputusan Kepala Desa tentang ' . fake()->sentence(4),

            'stipulation_date' => $stipulationDate->format('Y-m-d'),

            'subject' => fake()->optional()->sentence(),

            'related_person' => fake()->optional()->name(),

            'document_file' => null,

            'status' => fake()->randomElement(
                DecreeStatusList::cases()
            ),

            'notes' => fake()->optional()->sentence(),

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

    public function expired(): static
    {
        return $this->state(fn() => [
            'status' => DecreeStatusList::EXPIRED,
        ]);
    }
}
