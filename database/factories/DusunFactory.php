<?php

namespace Database\Factories;

use App\Models\Dusun;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dusun>
 */
class DusunFactory extends Factory
{
    protected $model = Dusun::class;

    public function definition(): array
    {
        return [
            'nama' => fake()->unique()->word(),
            'total_rt' => fake()->numberBetween(1, 5),
            'total_rw' => fake()->numberBetween(1, 3),
            'is_active' => true,
        ];
    }
}