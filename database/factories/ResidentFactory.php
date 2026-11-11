<?php

namespace Database\Factories;

use App\Enums\CitizenshipList;
use App\Enums\EducationList;
use App\Enums\GenderList;
use App\Enums\MaritalStatusList;
use App\Enums\OccupationList;
use App\Enums\ReligionList;
use App\Enums\RtList;
use App\Enums\RwList;
use App\Models\Dusun;
use App\Models\Resident;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Resident>
 */
class ResidentFactory extends Factory
{
    protected $model = Resident::class;

    public function definition(): array
    {
        return [
            // Identitas Penduduk
            'nama_lengkap' => fake()->name(),
            'jenis_kelamin' => fake()->randomElement(
                array_column(GenderList::cases(), 'value')
            ),
            'nik' => fake()->unique()->numerify(str_repeat('#', 16)),
            'kk' => fake()->unique()->numerify(str_repeat('#', 16)),
            // Data Kelahiran
            'tempat_lahir' => fake()->city(),
            'tgl_lahir' => fake()->dateTimeBetween('-80 years', '-17 years'),

            // Data Pribadi
            'status_perkawinan' => fake()->randomElement(
                array_column(MaritalStatusList::cases(), 'value')
            ),
            'agama' => fake()->randomElement(
                array_column(ReligionList::cases(), 'value')
            ),
            'pendidikan' => fake()->randomElement(
                array_column(EducationList::cases(), 'value')
            ),
            'pekerjaan' => fake()->randomElement(
                array_column(OccupationList::cases(), 'value')
            ),
            'dapat_membaca' => fake()->boolean(),

            // Kewarganegaraan dan Keluarga
            'kewarganegaraan' => fake()->randomElement(
                array_column(CitizenshipList::cases(), 'value')
            ),
            'kebangsaan' => 'Indonesia',
            'status_dalam_keluarga' => 'Anak',

            // Alamat dan Wilayah
            'dusun_id' => 1,
            'rt' => fake()->randomElement(
                array_column(RtList::cases(), 'value')
            ),
            'rw' => fake()->randomElement(
                array_column(RwList::cases(), 'value')
            ),
            'catatan' => fake()->optional()->sentence(),
        ];
    }
}
