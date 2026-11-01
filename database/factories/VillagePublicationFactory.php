<?php

namespace Database\Factories;

use App\Enums\RegulationTypeList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VillagePublication>
 */
class VillagePublicationFactory extends Factory
{
    public function definition(): array
    {
        $stipulationDate = fake()->dateTimeBetween('-5 years', 'now');

        return [
            'regulation_type' => fake()->randomElement(RegulationTypeList::cases()),
            'regulation_number' => fake()->unique()->numerify('## Tahun 202#'),
            'stipulation_date' => $stipulationDate,
            'subject' => fake()->randomElement([
                'Anggaran Pendapatan dan Belanja Desa',
                'Rencana Pembangunan Jangka Menengah Desa',
                'Rencana Kerja Pemerintah Desa',
                'Pembentukan Tim Pelaksana Kegiatan',
                'Penetapan Perangkat Desa',
                'Pengelolaan Aset Desa',
                'Kewenangan Berdasarkan Hak Asal Usul Desa',
            ]),
            'promulgation_date' => fake()->optional(0.8)->dateTimeBetween(
                $stipulationDate,
                'now'
            ),
            'notes' => fake()->optional()->sentence(),
            'created_by' => User::factory(),
        ];
    }
}
