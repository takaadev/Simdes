<?php

namespace Database\Factories;

use App\Enums\CitizenshipList;
use App\Enums\GenderList;
use App\Enums\OccupationList;
use App\Models\TemporaryResident;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TemporaryResident>
 */
class TemporaryResidentFactory extends Factory
{
    protected $model = TemporaryResident::class;

    public function definition(): array
    {
        $tglDatang = fake('id_ID')->dateTimeBetween('-6 months', 'now');
        $tglLahir = fake('id_ID')->dateTimeBetween('-70 years', '-18 years');

        // Mayoritas WNI, sebagian kecil WNA
        $kewarganegaraan = fake()->randomElement([
            CitizenshipList::WNI,
            CitizenshipList::WNI,
            CitizenshipList::WNI,
            CitizenshipList::WNA,
        ]);

        return [
            'nama_lengkap' => fake('id_ID')->name(),
            'jenis_kelamin' => fake()->randomElement(GenderList::cases())->value,
            'nomor_identitas' => $kewarganegaraan === CitizenshipList::WNI
                ? fake('id_ID')->nik()
                : fake()->bothify('??#######'),
            'tempat_lahir' => fake('id_ID')->city(),
            'tgl_lahir' => $tglLahir->format('Y-m-d'),
             'umur' => Carbon::parse($tglLahir)->age,
            'pekerjaan' => fake()->randomElement(OccupationList::cases())->value,
            'kewarganegaraan' => $kewarganegaraan->value,
            'kebangsaan' => $kewarganegaraan === CitizenshipList::WNI
                ? 'Indonesia'
                : fake()->country(),
            'keturunan' => fake()->randomElement([
                'Melayu',
                'Jawa',
                'Minangkabau',
                'Batak',
                'Sunda',
                'Tionghoa',
                'Arab',
                'Lainnya',
            ]),
            'datang_dari' => fake('id_ID')->city(),
            'tujuan_kedatangan' => fake()->randomElement([
                'Bekerja',
                'Mengunjungi keluarga',
                'Keperluan keluarga',
                'Urusan pekerjaan',
                'Keperluan lainnya',
            ]),
            'alamat_tujuan' => fake('id_ID')->address(),
            'tgl_datang' => $tglDatang->format('Y-m-d'),
            'tgl_pergi' => fake()->optional(0.5)
                ->dateTimeBetween($tglDatang, 'now')
                ?->format('Y-m-d'),
            'catatan' => fake('id_ID')->optional()->sentence(),
            'recorded_by' => User::factory(),
        ];
    }

    public function stillStaying(): static
    {
        return $this->state(fn() => [
            'tgl_pergi' => null,
        ]);
    }

    public function departed(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'tgl_pergi' => fake()
                    ->dateTimeBetween($attributes['tgl_datang'], 'now')
                    ->format('Y-m-d'),
            ];
        });
    }
}
