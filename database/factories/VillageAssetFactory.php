<?php

namespace Database\Factories;

use App\Enums\AssetConditionList;
use App\Enums\AssetOwnershipStatusList;
use App\Models\User;
use App\Models\VillageAsset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VillageAsset>
 */
class VillageAssetFactory extends Factory
{
    protected $model = VillageAsset::class;

    public function definition(): array
    {
        $assets = [
            ['name' => 'Komputer Desktop', 'category' => 'Peralatan Komputer', 'unit' => 'Unit'],
            ['name' => 'Laptop', 'category' => 'Peralatan Komputer', 'unit' => 'Unit'],
            ['name' => 'Printer', 'category' => 'Peralatan Komputer', 'unit' => 'Unit'],
            ['name' => 'Meja Kerja', 'category' => 'Perabot Kantor', 'unit' => 'Unit'],
            ['name' => 'Kursi Kantor', 'category' => 'Perabot Kantor', 'unit' => 'Unit'],
            ['name' => 'Lemari Arsip', 'category' => 'Perabot Kantor', 'unit' => 'Unit'],
            ['name' => 'Kendaraan Operasional', 'category' => 'Kendaraan', 'unit' => 'Unit'],
        ];

        $asset = fake()->randomElement($assets);

        return [
            'register_number' => fake()->unique()->numerify('####/INV/####'),
            'asset_code' => fake()->optional()->bothify('AST-####-??'),

            'asset_name' => $asset['name'],
            'asset_category' => $asset['category'],

            'acquisition_date' => fake()->dateTimeBetween('-5 years', 'now')
                ->format('Y-m-d'),

            'acquisition_source' => fake()->randomElement([
                'APBDes',
                'Bantuan Pemerintah',
                'Hibah',
                'Sumbangan',
                'Lainnya',
            ]),

            'acquisition_value' => fake()->numberBetween(500000, 50000000),

            'quantity' => fake()->randomElement([1, 2, 3, 5, 10]),
            'unit' => $asset['unit'],

            'condition' => fake()->randomElement(
                AssetConditionList::cases()
            ),

            'location' => fake()->randomElement([
                'Kantor Desa',
                'Balai Desa',
                'Gudang Desa',
                'Ruang Pelayanan',
            ]),

            'ownership_status' => fake()->randomElement(
                AssetOwnershipStatusList::cases()
            ),

            'notes' => fake()->optional()->sentence(),

            'created_by' => User::factory(),
        ];
    }

    public function good(): static
    {
        return $this->state(fn() => [
            'condition' => AssetConditionList::GOOD,
        ]);
    }

    public function damaged(): static
    {
        return $this->state(fn() => [
            'condition' => AssetConditionList::LIGHTLY_DAMAGED,
        ]);
    }

    public function heavilyDamaged(): static
    {
        return $this->state(fn() => [
            'condition' => AssetConditionList::HEAVILY_DAMAGED,
        ]);
    }
}
