<?php

namespace Database\Factories;

use App\Enums\GenderList;
use App\Enums\VillageOfficialStatusList;
use App\Models\User;
use App\Models\VillageOfficial;
use App\Models\VillagePosition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VillageOfficial>
 */
class VillageOfficialFactory extends Factory
{
    protected $model = VillageOfficial::class;

    public function definition(): array
    {
        $birthDate = fake()->dateTimeBetween('-60 years', '-25 years');

        $startDate = fake()->dateTimeBetween('-10 years', 'now');

        return [
            'register_number' => fake()->unique()->numerify('VO-####-####'),

            'name' => fake()->name(),
            'nik' => fake()->unique()->numerify('15##############'),

            'gender' => fake()->randomElement(GenderList::cases()),

            'birth_place' => fake()->city(),
            'birth_date' => $birthDate,

            'village_position_id' => VillagePosition::factory(),

            'appointment_number' => fake()->optional()->numerify('###/SK/DS/####'),
            'appointment_date' => $startDate,
            'start_date' => $startDate,
            'end_date' => null,

            'education' => fake()->randomElement([
                'SD',
                'SMP',
                'SMA',
                'Diploma',
                'S1',
                'S2',
            ]),

            'address' => fake()->address(),
            'phone' => fake()->numerify('08##########'),

            'status' => VillageOfficialStatusList::ACTIVE,

            'notes' => fake()->optional()->sentence(),

            'created_by' => User::factory(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => VillageOfficialStatusList::ACTIVE,
            'end_date' => null,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => VillageOfficialStatusList::INACTIVE,
        ]);
    }

    public function retired(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => VillageOfficialStatusList::RETIRED,
        ]);
    }

    public function dismissed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => VillageOfficialStatusList::DISMISSED,
        ]);
    }

    public function resigned(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => VillageOfficialStatusList::RESIGNED,
        ]);
    }
}
