<?php

namespace Database\Factories;

use App\Enums\MutationTypeList;
use App\Models\Dusun;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\ResidentMutation>
 */
class ResidentMutationFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Identitas Penduduk
            'resident_id' => Resident::factory(),

            // Informasi Pencatatan
            'mutation_number' => fake()->unique()->numerify('MUT-########'),
            'mutation_type' => fake()->randomElement(
                array_column(MutationTypeList::cases(), 'value')
            ),
            'mutation_date' => fake()->date(),

            // Alamat Asal
            'origin_address' => fake()->optional()->address(),
            'origin_dusun_id' => Dusun::factory(),

            // Alamat Tujuan
            'destination_address' => fake()->optional()->address(),
            'destination_dusun_id' => Dusun::factory(),

            // Keterangan Mutasi
            'reason' => fake()->optional()->sentence(),
            'document_number' => fake()->optional()->numerify('DOC-########'),
            'notes' => fake()->optional()->sentence(),

            // Petugas Pencatat
            'recorded_by' => User::factory(),
        ];
    }

    public function incoming(): static
    {
        return $this->state(fn() => [
            'mutation_type' => 'incoming',
        ]);
    }

    public function outgoing(): static
    {
        return $this->state(fn() => [
            'mutation_type' => 'outgoing',
        ]);
    }

    public function birth(): static
    {
        return $this->state(fn() => [
            'mutation_type' => 'birth',
            'origin_address' => null,
            'origin_dusun_id' => null,
        ]);
    }

    public function death(): static
    {
        return $this->state(fn() => [
            'mutation_type' => 'death',
            'destination_address' => null,
            'destination_dusun_id' => null,
        ]);
    }
}
