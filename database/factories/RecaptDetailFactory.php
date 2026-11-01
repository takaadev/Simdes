<?php

namespace Database\Factories;

use App\Models\Dusun;
use App\Models\Recapt;
use App\Models\RecaptDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecaptDetail>
 */
class RecaptDetailFactory extends Factory
{
    protected $model = RecaptDetail::class;

    public function definition(): array
    {
        $maleCount = fake()->numberBetween(50, 300);
        $femaleCount = fake()->numberBetween(50, 300);

        return [
            'recapt_id' => Recapt::factory(),
            'dusun_id' => Dusun::factory(),

            'total_population' => $maleCount + $femaleCount,
            'male_count' => $maleCount,
            'female_count' => $femaleCount,

            'family_count' => fake()->numberBetween(20, 150),

            'birth_count' => fake()->numberBetween(0, 10),
            'death_count' => fake()->numberBetween(0, 5),
            'incoming_count' => fake()->numberBetween(0, 10),
            'outgoing_count' => fake()->numberBetween(0, 10),

            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function villageTotal(): static
    {
        return $this->state(fn() => [
            'dusun_id' => null,
        ]);
    }
}
