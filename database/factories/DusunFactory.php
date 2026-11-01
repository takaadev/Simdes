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
        $number = fake()->unique()->numberBetween(1, 9999);

        return [
            'name' => 'Dusun ' . $number,
            'code' => 'DSN-' . str_pad($number, 4, '0', STR_PAD_LEFT),
            'description' => fake()->optional()->sentence(),
            'is_active' => true,
        ];
    }
}
