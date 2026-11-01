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
        $stipulationDate = fake()->dateTimeBetween('-5 years', 'now');

        return [
            'register_number' => fake()->unique()->numerify('####/REG/####'),

            'regulation_number' => fake()->numerify('## Tahun ####'),

            'regulation_type' => fake()->randomElement(
                RegulationTypeList::cases()
            ),

            'regulation_title' => 'Peraturan tentang ' . fake()->sentence(4),

            'stipulation_date' => $stipulationDate->format('Y-m-d'),

            'promulgation_date' => fake()->optional(0.8)
                ->dateTimeBetween($stipulationDate, 'now')
                ?->format('Y-m-d'),

            'subject' => fake()->optional()->sentence(),

            'document_file' => null,

            'status' => fake()->randomElement(
                RegulationStatusList::cases()
            ),

            'notes' => fake()->optional()->sentence(),

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
