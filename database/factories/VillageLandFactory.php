<?php

namespace Database\Factories;

use App\Models\VillageLand;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VillageLand>
 */
class VillageLandFactory extends Factory
{
    protected $model = VillageLand::class;

    public function definition(): array
    {
        return [
            'owner_name' => fake()->company(),

            'total_area' => fake()->randomFloat(2, 100, 10000),

            // Status Hak Tanah
            'hm' => fake()->randomFloat(2, 0, 5000),
            'hgb' => fake()->randomFloat(2, 0, 3000),
            'hp' => fake()->randomFloat(2, 0, 2000),
            'hgu' => fake()->randomFloat(2, 0, 2000),
            'hpl' => fake()->randomFloat(2, 0, 1000),
            'ma' => fake()->randomFloat(2, 0, 1000),
            'vi' => fake()->randomFloat(2, 0, 1000),
            'tn' => fake()->randomFloat(2, 0, 1000),

            // Peruntukan Tanah
            'housing' => fake()->randomFloat(2, 0, 3000),
            'trade_services' => fake()->randomFloat(2, 0, 2000),
            'office' => fake()->randomFloat(2, 0, 1000),
            'industry' => fake()->randomFloat(2, 0, 2000),
            'public_facilities' => fake()->randomFloat(2, 0, 1000),

            // Penggunaan Tanah
            'paddy_field' => fake()->randomFloat(2, 0, 3000),
            'dry_field' => fake()->randomFloat(2, 0, 2000),
            'plantation' => fake()->randomFloat(2, 0, 3000),
            'livestock_fishery' => fake()->randomFloat(2, 0, 1000),
            'shrub_forest' => fake()->randomFloat(2, 0, 2000),
            'protected_forest' => fake()->randomFloat(2, 0, 3000),

            // Mutasi dan Luas Lainnya
            'land_mutation' => fake()->randomFloat(2, 0, 1000),
            'vacant_land' => fake()->randomFloat(2, 0, 2000),
            'other_area' => fake()->randomFloat(2, 0, 1000),

            'notes' => fake()->optional()->sentence(),

            'created_by' => null,
        ];
    }
}
