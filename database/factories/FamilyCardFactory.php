<?php

namespace Database\Factories;

use App\Models\Dusun;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\FamilyCard>
 */
class FamilyCardFactory extends Factory
{
    public function definition(): array
    {
        return [
            'family_card_number' => fake()->unique()->numerify('####-########-####'),
            'head_of_family_id' => null,
            'dusun_id' => Dusun::factory(),
            'address' => fake()->streetAddress(),
            'rt' => fake()->randomElement(['000', '001', '002', '003', '004']),
            'rw' => fake()->randomElement(['000', '001', '002', '003', '004']),
            'issued_place' => fake()->city(),
            'issued_date' => fake()->optional()->date(),
            'status' => 'active',
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
