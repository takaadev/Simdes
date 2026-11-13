<?php

namespace Database\Factories;

use App\Models\FamilyCard;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FamilyCard>
 */
class FamilyCardFactory extends Factory
{
    protected $model = FamilyCard::class;

    public function definition(): array
    {
        return [
            'nomor_kk' => fake()->unique()->numerify('################'),
        ];
    }
}
