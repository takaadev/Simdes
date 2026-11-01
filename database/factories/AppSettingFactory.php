<?php

namespace Database\Factories;

use App\Models\AppSetting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AppSetting>
 */
class AppSettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'key' => fake()->unique()->slug(3),
            'value' => fake()->sentence(),
            'type' => 'string',
            'group' => 'general',
            'description' => fake()->sentence(),
            'updated_by' => User::factory(),
        ];
    }
}
