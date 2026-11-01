<?php

namespace Database\Factories;

use App\Enums\VillagePositionCategoryList;
use App\Models\VillagePosition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VillagePosition>
 */
class VillagePositionFactory extends Factory
{
    protected $model = VillagePosition::class;

    public function definition(): array
    {
        $category = fake()->randomElement(
            VillagePositionCategoryList::cases()
        );

        return [
            'name' => fake()->jobTitle(),
            'code' => fake()->unique()->numerify('###'),
            'category' => $category,
            'is_active' => true,
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function category(VillagePositionCategoryList $category): static
    {
        return $this->state(fn(array $attributes) => [
            'category' => $category,
        ]);
    }
}
