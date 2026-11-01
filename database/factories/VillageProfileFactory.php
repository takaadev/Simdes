<?php

namespace Database\Factories;

use App\Models\VillageProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VillageProfile>
 */
class VillageProfileFactory extends Factory
{
    protected $model = VillageProfile::class;

    public function definition(): array
    {
        return [
            'village_name' => 'Desa ' . fake()->city(),
            'district' => fake()->city(),
            'regency' => fake()->city(),
            'province' => 'Jambi',
            'postal_code' => fake()->numerify('#####'),
            'office_address' => fake()->address(),
            'phone' => fake()->numerify('08##########'),
            'email' => fake()->optional(0.7)->safeEmail(),
            'village_head_name' => fake()->name(),
            'village_logo' => null,
        ];
    }
}
