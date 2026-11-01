<?php

namespace Database\Factories;

use App\Models\FamilyCard;
use App\Models\Resident;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\FamilyCardMember>
 */
class FamilyCardMemberFactory extends Factory
{
    public function definition(): array
    {
        return [
            'family_card_id' => FamilyCard::factory(),
            'resident_id' => Resident::factory(),
            'relationship' => fake()->randomElement([
                'Kepala Keluarga',
                'Istri',
                'Suami',
                'Anak',
                'Menantu',
                'Cucu',
                'Orang Tua',
                'Mertua',
                'Famili Lain',
            ]),
            'is_head' => false,
            'joined_at' => fake()->optional()->date(),
            'left_at' => null,
            'notes' => fake()->optional()->sentence(),
        ];
    }

    // Status sebagai kepala keluarga
    public function head(): static
    {
        return $this->state(fn() => [
            'relationship' => 'Kepala Keluarga',
            'is_head' => true,
        ]);
    }

    // Anggota keluarga aktif
    public function active(): static
    {
        return $this->state(fn() => [
            'left_at' => null,
        ]);
    }
}
