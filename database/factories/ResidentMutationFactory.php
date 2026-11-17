<?php

namespace Database\Factories;

use App\Enums\BloodList;
use App\Enums\CitizenshipList;
use App\Enums\EducationList;
use App\Enums\GenderList;
use App\Enums\MaritalStatusList;
use App\Enums\MutationCategoryList;
use App\Enums\MutationTypeList;
use App\Enums\OccupationList;
use App\Enums\RelationshipList;
use App\Enums\ReligionList;
use App\Enums\RtList;
use App\Enums\RwList;
use App\Models\ResidentMutation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ResidentMutation>
 */
class ResidentMutationFactory extends Factory
{
    protected $model = ResidentMutation::class;

    public function definition(): array
    {
        $kategori = fake()->randomElement(
            MutationCategoryList::cases()
        );

        $tipe = match ($kategori) {
            MutationCategoryList::BIRTH,
            MutationCategoryList::INCOMING => MutationTypeList::ADD,

            MutationCategoryList::DEATH,
            MutationCategoryList::OUTGOING => MutationTypeList::MINUS,
        };

        $isAdd = $tipe === MutationTypeList::ADD;

        return [
            // A. Identitas Penduduk
            'nomor_identitas' => null,
            'nik' => fake()->numerify('################'),
            'kk' => fake()->numerify('################'),
            'nama_lengkap' => fake()->name(),
            'jenis_kelamin' => fake()->randomElement(
                array_column(GenderList::cases(), 'value')
            ),
            'tempat_lahir' => fake()->city(),
            'tgl_lahir' => fake()
                ->dateTimeBetween('-80 years', '-1 year')
                ->format('Y-m-d'),

            // B. Data Kependudukan
            'dapat_membaca' => fake()->boolean(),
            'status_perkawinan' => fake()->randomElement(
                array_column(MaritalStatusList::cases(), 'value')
            ),
            'status_dalam_keluarga' => fake()->randomElement(
                array_column(RelationshipList::cases(), 'value')
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

            // C. Kewarganegaraan
            'kewarganegaraan' => fake()->randomElement(
                array_column(CitizenshipList::cases(), 'value')
            ),
            'kebangsaan' => 'Indonesia',

            // D. Data Tambahan
            'gol_darah' => fake()->randomElement(
                array_column(BloodList::cases(), 'value')
            ),
            'ayah' => fake()->name('male'),
            'ibu' => fake()->name('female'),

            // E. Informasi Mutasi
            'tipe_mutasi' => $tipe->value,
            'kategori_mutasi' => $kategori->value,
            'tgl_mutasi' => fake()
                ->dateTimeBetween('-5 years', 'now')
                ->format('Y-m-d'),

            // F. Asal / Tujuan Mutasi
            'tujuan_mutasi' => match ($kategori) {
                MutationCategoryList::INCOMING =>
                    'Dari ' . fake()->city(),

                MutationCategoryList::OUTGOING =>
                    'Ke ' . fake()->city(),

                default => null,
            },

            // G. Alamat
            'rw_tujuan' => $isAdd
                ? fake()->randomElement(
                    array_column(RwList::cases(), 'value')
                )
                : null,

            'rt_tujuan' => $isAdd
                ? fake()->randomElement(
                    array_column(RtList::cases(), 'value')
                )
                : null,

            'alamat' => fake()->streetAddress(),

            // H. Keterangan
            'catatan' => fake()->optional()->sentence(),

            // I. Relasi
            'resident_id' => null,
            'recorded_by' => User::factory(),
        ];
    }

    /**
     * Penduduk datang.
     */
    public function incoming(): static
    {
        return $this->state(fn () => [
            'tipe_mutasi' => MutationTypeList::ADD->value,
            'kategori_mutasi' => MutationCategoryList::INCOMING->value,
            'tujuan_mutasi' => 'Dari ' . fake()->city(),
            'rw_tujuan' => fake()->randomElement(
                array_column(RwList::cases(), 'value')
            ),
            'rt_tujuan' => fake()->randomElement(
                array_column(RtList::cases(), 'value')
            ),
        ]);
    }

    /**
     * Penduduk keluar.
     */
    public function outgoing(): static
    {
        return $this->state(fn () => [
            'tipe_mutasi' => MutationTypeList::MINUS->value,
            'kategori_mutasi' => MutationCategoryList::OUTGOING->value,
            'tujuan_mutasi' => 'Ke ' . fake()->city(),
            'rw_tujuan' => null,
            'rt_tujuan' => null,
        ]);
    }

    /**
     * Kelahiran.
     */
    public function birth(): static
    {
        return $this->state(fn () => [
            'tipe_mutasi' => MutationTypeList::ADD->value,
            'kategori_mutasi' => MutationCategoryList::BIRTH->value,
            'tujuan_mutasi' => null,
            'rw_tujuan' => fake()->randomElement(
                array_column(RwList::cases(), 'value')
            ),
            'rt_tujuan' => fake()->randomElement(
                array_column(RtList::cases(), 'value')
            ),
        ]);
    }

    /**
     * Kematian.
     */
    public function death(): static
    {
        return $this->state(fn () => [
            'tipe_mutasi' => MutationTypeList::MINUS->value,
            'kategori_mutasi' => MutationCategoryList::DEATH->value,
            'tujuan_mutasi' => null,
            'rw_tujuan' => null,
            'rt_tujuan' => null,
        ]);
    }
}
