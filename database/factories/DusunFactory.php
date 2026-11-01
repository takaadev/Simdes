<?php

namespace Database\Factories;

use App\Models\Dusun;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dusun>
 */
class DusunFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Dusun ' . fake()->unique()->numberBetween(1, 99),
            'code' => fake()->unique()->numerify('DSN-####'),
            'description' => fake()->optional()->sentence(),
            'is_active' => true,
        ];
    }
}
