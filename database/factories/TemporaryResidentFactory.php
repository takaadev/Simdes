<?php

namespace Database\Factories;

use App\Enums\GenderList;
use App\Models\TemporaryResident;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TemporaryResident>
 */
class TemporaryResidentFactory extends Factory
{
    protected $model = TemporaryResident::class;

    public function definition(): array
    {
        $arrivalDate = fake()->dateTimeBetween('-6 months', 'now');

        $birthDate = fake()->dateTimeBetween('-70 years', '-18 years');

        return [
            'registration_number' => fake()->unique()->numerify('PS-####-####'),

            'full_name' => fake()->name(),

            'gender' => fake()->randomElement(GenderList::cases()),

            'nationality' => fake()->country(),
            'ethnicity' => fake()->randomElement([
                'Melayu',
                'Jawa',
                'Minangkabau',
                'Batak',
                'Sunda',
                'Lainnya',
            ]),

            'birth_place' => fake()->city(),
            'birth_date' => $birthDate->format('Y-m-d'),
            'age' => $birthDate->diff(new \DateTime())->y,

            'occupation' => fake()->randomElement([
                'Wiraswasta',
                'Karyawan Swasta',
                'Petani',
                'Pelajar/Mahasiswa',
                'PNS',
                'Lainnya',
            ]),

            'citizenship' => 'WNI',

            'origin_address' => fake()->address(),
            'visit_purpose' => fake()->randomElement([
                'Bekerja',
                'Mengunjungi keluarga',
                'Keperluan keluarga',
                'Urusan pekerjaan',
                'Keperluan lainnya',
            ]),

            'destination_name' => fake()->name(),
            'destination_address' => fake()->address(),

            'arrival_date' => $arrivalDate->format('Y-m-d'),

            'departure_date' => fake()->optional(0.5)
                ->dateTimeBetween($arrivalDate, 'now')
                ?->format('Y-m-d'),

            'notes' => fake()->optional()->sentence(),

            'recorded_by' => User::factory(),
        ];
    }

    public function stillStaying(): static
    {
        return $this->state(fn() => [
            'departure_date' => null,
        ]);
    }

    public function departed(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'departure_date' => fake()
                    ->dateTimeBetween($attributes['arrival_date'], 'now')
                    ->format('Y-m-d'),
            ];
        });
    }
}
