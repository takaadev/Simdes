<?php

namespace Database\Factories;

use App\Enums\LandAcquisitionList;
use App\Enums\LandTypeList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\VillageTreasuryLand>
 */
class VillageTreasuryLandFactory extends Factory
{
    public function definition(): array
    {
        return [
            'land_origin' => fake()->randomElement([
                'Dusun I',
                'Dusun II',
                'Dusun III',
                'Dusun IV',
            ]),

            'certificate_number' => fake()->optional(0.7)->numerify('###/####/####'),

            'land_area' => fake()->randomFloat(2, 100, 50000),

            'land_class' => fake()->optional()->randomElement([
                'I',
                'II',
                'III',
                'IV',
                'V',
            ]),

            'acquisition' => fake()->randomElement(LandAcquisitionList::cases()),

            'acquisition_date' => fake()->optional(0.8)->dateTimeBetween('-20 years', 'now'),

            'land_type' => fake()->randomElement(LandTypeList::cases()),

            'has_marker' => fake()->boolean(70),

            'has_plate' => fake()->boolean(60),

            'land_location' => fake()->randomElement([
                'Dusun I, dekat kantor desa',
                'Dusun II, pinggir jalan utama',
                'Dusun III, area perkebunan',
                'Dusun IV, dekat permukiman warga',
            ]),

            'land_use' => fake()->optional(0.8)->randomElement([
                'Kantor Desa',
                'Tanah Pertanian',
                'Perkebunan Desa',
                'Lapangan Desa',
                'Fasilitas Umum',
                'Disewakan',
            ]),

            'mutation' => fake()->optional(0.3)->sentence(),

            'notes' => fake()->optional(0.3)->sentence(),

            'created_by' => User::factory(),
        ];
    }

    public function withMarker(): static
    {
        return $this->state(fn() => [
            'has_marker' => true,
        ]);
    }

    public function withPlate(): static
    {
        return $this->state(fn() => [
            'has_plate' => true,
        ]);
    }
}
