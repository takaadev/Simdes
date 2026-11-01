<?php

namespace Database\Factories;

use App\Models\Dusun;
use App\Models\Resident;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Resident>
 */
class ResidentFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Identitas Penduduk
            'nik' => fake()->unique()->numerify('################'),
            'name' => fake()->name(),
            'gender' => fake()->randomElement(['male', 'female']),

            // Data Kelahiran
            'birth_place' => fake()->city(),
            'birth_date' => fake()->dateTimeBetween('-80 years', '-17 years'),
            'blood_type' => fake()->optional()->randomElement([
                'A',
                'B',
                'AB',
                'O',
            ]),

            // Data Pribadi
            'religion' => fake()->randomElement([
                'Islam',
                'Kristen',
                'Katolik',
                'Hindu',
                'Buddha',
                'Konghucu',
            ]),
            'education' => fake()->randomElement([
                'Tidak/Belum Sekolah',
                'SD',
                'SMP',
                'SMA',
                'Diploma',
                'S1',
                'S2',
                'S3',
            ]),
            'occupation' => fake()->jobTitle(),
            'marital_status' => fake()->randomElement([
                'menikah',
                'cerai hidup',
                'belum menikah',
                'cerai mati',
            ]),
            'citizenship' => 'WNI',
            'nationality' => 'Indonesia',

            // Data Orang Tua
            'father_name' => fake()->name('male'),
            'mother_name' => fake()->name('female'),

            // Alamat dan Wilayah
            'dusun_id' => Dusun::factory(),
            'address' => fake()->streetAddress(),
            'rt' => fake()->randomElement([
                '000',
                '001',
                '002',
                '003',
                '004',
            ]),
            'rw' => fake()->randomElement([
                '000',
                '001',
                '002',
                '003',
                '004',
            ]),
            'postal_code' => fake()->numerify('#####'),

            // Riwayat Domisili
            'moved_in_date' => fake()->optional()->date(),
            'moved_out_date' => null,

            // Status Kependudukan
            'resident_status' => 'active',
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
