<?php

namespace Database\Factories;

use App\Enums\BloodList;
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
            'nik' => fake()->unique()->numerify('################'),
            'name' => fake()->name(),
            'gender' => fake()->randomElement(
                array_column(GenderList::cases(), 'value')
            ),

            // Data Kelahiran
            'birth_place' => fake()->city(),
            'birth_date' => fake()->dateTimeBetween('-80 years', '-17 years'),
            'blood_type' => fake()->randomElement(
                array_column(BloodList::cases(), 'value')
            ),

            // Data Pribadi
            'religion' => fake()->randomElement(
                array_column(ReligionList::cases(), 'value')
            ),
            'education' => fake()->randomElement(
                array_column(EducationList::cases(), 'value')
            ),
            'occupation' => fake()->randomElement(
                array_column(OccupationList::cases(), 'value')
            ),
            'marital_status' => fake()->randomElement(
                array_column(MaritalStatusList::cases(), 'value')
            ),
            'citizenship' => fake()->randomElement(
                array_column(CitizenshipList::cases(), 'value')
            ),
            'nationality' => 'Indonesia',

            // Data Orang Tua
            'father_name' => fake()->name('male'),
            'mother_name' => fake()->name('female'),

            // Alamat dan Wilayah
            'dusun_id' => Dusun::factory(),
            'address' => fake()->streetAddress(),
            'rt' => fake()->randomElement(
                array_column(RtList::cases(), 'value')
            ),
            'rw' => fake()->randomElement(
                array_column(RwList::cases(), 'value')
            ),
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
