<?php

namespace Database\Factories;

use App\Enums\MutationCategoryList;
use App\Enums\MutationTypeList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\ResidentMutation>
 */
class ResidentMutationFactory extends Factory
{
    public function definition(): array
    {
        $kategori = fake()->randomElement(MutationCategoryList::cases());

        $tipe = match ($kategori) {
            MutationCategoryList::BIRTH,
            MutationCategoryList::INCOMING => MutationTypeList::ADD,

            MutationCategoryList::DEATH,
            MutationCategoryList::OUTGOING => MutationTypeList::MINUS,
        };

        return [
            // Identitas Penduduk
            'nama_lengkap' => fake()->name(),
            'tempat_lahir' => fake()->city(),
            'tgl_lahir' => fake()->dateTimeBetween('-80 years', '-1 year')->format('Y-m-d'),
            'jenis_kelamin' => fake()->randomElement(['L', 'P']),
            'kewarganegaraan' => 'WNI',
            'kebangsaan' => 'Indonesia',

            // Informasi Mutasi
            'tipe_mutasi' => $tipe->value,
            'kategori_mutasi' => $kategori->value,
            'tgl_mutasi' => fake()->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),

            // Tujuan Mutasi
            'tujuan_mutasi' => match ($kategori) {
                MutationCategoryList::INCOMING => 'Dari ' . fake()->city(),
                MutationCategoryList::OUTGOING => 'Ke ' . fake()->city(),
                default => null,
            },

            // Keterangan
            'catatan' => fake()->optional()->sentence(),

            // Relasi
            'resident_id' => null,
            'recorded_by' => User::factory(),
        ];
    }

    public function incoming(): static
    {
        return $this->state(fn() => [
            'tipe_mutasi' => MutationTypeList::ADD->value,
            'kategori_mutasi' => MutationCategoryList::INCOMING->value,
            'tujuan_mutasi' => 'Dari ' . fake()->city(),
        ]);
    }

    public function outgoing(): static
    {
        return $this->state(fn() => [
            'tipe_mutasi' => MutationTypeList::MINUS->value,
            'kategori_mutasi' => MutationCategoryList::OUTGOING->value,
            'tujuan_mutasi' => 'Ke ' . fake()->city(),
        ]);
    }

    public function birth(): static
    {
        return $this->state(fn() => [
            'tipe_mutasi' => MutationTypeList::ADD->value,
            'kategori_mutasi' => MutationCategoryList::BIRTH->value,
            'tujuan_mutasi' => null,
        ]);
    }

    public function death(): static
    {
        return $this->state(fn() => [
            'tipe_mutasi' => MutationTypeList::MINUS->value,
            'kategori_mutasi' => MutationCategoryList::DEATH->value,
            'tujuan_mutasi' => null,
        ]);
    }
}
