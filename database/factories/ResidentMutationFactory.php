<?php

namespace Database\Factories;

use App\Enums\MutationCategoryList;
use App\Enums\MutationTypeList;
use App\Models\Resident;
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

        return [
            // Identitas Tambahan
            'nomor_identitas' => null,

            // Informasi Mutasi
            'tipe_mutasi' => $tipe->value,
            'kategori_mutasi' => $kategori->value,
            'tgl_mutasi' => fake()
                ->dateTimeBetween('-5 years', 'now')
                ->format('Y-m-d'),

            // Asal / Tujuan Mutasi
            'tujuan_mutasi' => match ($kategori) {
                MutationCategoryList::INCOMING =>
                'Dari ' . fake()->city(),

                MutationCategoryList::OUTGOING =>
                'Ke ' . fake()->city(),

                default => null,
            },

            // Relasi
            'resident_id' => Resident::factory(),
            'recorded_by' => 1,
        ];
    }

    /**
     * Penduduk datang.
     */
    public function incoming(): static
    {
        return $this->state(fn() => [
            'tipe_mutasi' => MutationTypeList::ADD->value,
            'kategori_mutasi' => MutationCategoryList::INCOMING->value,
            'tujuan_mutasi' => 'Dari ' . fake()->city(),
        ]);
    }

    /**
     * Penduduk keluar.
     */
    public function outgoing(): static
    {
        return $this->state(fn() => [
            'tipe_mutasi' => MutationTypeList::MINUS->value,
            'kategori_mutasi' => MutationCategoryList::OUTGOING->value,
            'tujuan_mutasi' => 'Ke ' . fake()->city(),
        ]);
    }

    /**
     * Kelahiran.
     */
    public function birth(): static
    {
        return $this->state(fn() => [
            'tipe_mutasi' => MutationTypeList::ADD->value,
            'kategori_mutasi' => MutationCategoryList::BIRTH->value,
            'tujuan_mutasi' => null,
        ]);
    }

    /**
     * Kematian.
     */
    public function death(): static
    {
        return $this->state(fn() => [
            'tipe_mutasi' => MutationTypeList::MINUS->value,
            'kategori_mutasi' => MutationCategoryList::DEATH->value,
            'tujuan_mutasi' => null,
        ]);
    }
}
