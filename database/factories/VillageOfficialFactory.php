<?php

namespace Database\Factories;

use App\Enums\EducationList;
use App\Enums\GenderList;
use App\Enums\VillageOfficialStatusList;
use App\Models\User;
use App\Models\VillageOfficial;
use App\Models\VillagePosition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VillageOfficial>
 */
class VillageOfficialFactory extends Factory
{
    protected $model = VillageOfficial::class;

    /**
     * Mencegah sinkronisasi jabatan berulang.
     */
    private static bool $positionsEnsured = false;

    /**
     * Memastikan master jabatan tersedia.
     */
    private function ensurePositions(): void
    {
        if (self::$positionsEnsured) {
            return;
        }

        foreach (VillagePositionFactory::positions() as $position) {
            VillagePosition::withTrashed()->updateOrCreate(
                ['kode' => $position['kode']],
                [
                    'nama' => $position['nama'],
                    'kategori' => $position['kategori'],
                    'is_active' => true,
                    'deleted_at' => null,
                ]
            );
        }

        self::$positionsEnsured = true;
    }

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $this->ensurePositions();

        $faker = fake();

        // Identitas tanggal lahir.
        $birthDate = $faker->dateTimeBetween('-60 years', '-25 years');

        // Pengangkatan minimal pada usia 18 tahun.
        $minimumAppointmentDate = (clone $birthDate)
            ->modify('+18 years');

        $appointmentDate = $faker->dateTimeBetween(
            $minimumAppointmentDate,
            'now'
        );

        return [
            // A. Identitas Aparat
            'nama' => $faker->name(),

            'niap' => $faker->unique()->numerify('###############'),

            'nip' => $faker->optional(0.3)->passthrough(
                $faker->unique()->numerify('################')
            ),

            'jenis_kelamin' => $faker->randomElement(
                array_map(
                    fn($case) => $case->value,
                    GenderList::cases()
                )
            ),

            'tempat_lahir' => $faker->city(),

            'tgl_lahir' => $birthDate->format('Y-m-d'),

            // B. Pendidikan, Kepangkatan, dan Jabatan
            'pendidikan' => $faker->randomElement(
                array_map(
                    fn($case) => $case->value,
                    EducationList::cases()
                )
            ),

            'pangkat_golongan' => $faker->optional()->randomElement([
                'Penata Muda (III/a)',
                'Penata Muda Tingkat I (III/b)',
                'Pengatur (II/c)',
                'Pengatur Tingkat I (II/d)',
            ]),

            'village_position_id' => fn() => VillagePosition::query()
                ->where('is_active', true)
                ->inRandomOrder()
                ->value('id'),

            // C. Keputusan Pengangkatan
            'nomor_pengangkatan' => $faker->unique()->numerify('###/SK/DS/####'),

            'tgl_pengangkatan' => $appointmentDate->format('Y-m-d'),

            // D. Keputusan Pemberhentian
            'nomor_pemberhentian' => null,

            'tgl_pemberhentian' => null,

            // E. Status dan Kontak
            'status' => VillageOfficialStatusList::ACTIVE->value,

            'telpon' => $faker->unique()->numerify('08##########'),

            'alamat' => $faker->address(),

            // F. Keterangan
            'notes' => $faker->optional()->sentence(),

            // Metadata
            'created_by' => User::factory(),
        ];
    }

    /**
     * Status aktif.
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => VillageOfficialStatusList::ACTIVE->value,
            'nomor_pemberhentian' => null,
            'tgl_pemberhentian' => null,
        ]);
    }

    /**
     * Status tidak aktif.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => VillageOfficialStatusList::INACTIVE->value,
            'nomor_pemberhentian' => null,
            'tgl_pemberhentian' => null,
        ]);
    }

    /**
     * Status pensiun.
     */
    public function retired(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => VillageOfficialStatusList::RETIRED->value,
            ...$this->terminationData($attributes),
        ]);
    }

    /**
     * Status diberhentikan.
     */
    public function dismissed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => VillageOfficialStatusList::DISMISSED->value,
            ...$this->terminationData($attributes),
        ]);
    }

    /**
     * Status mengundurkan diri.
     */
    public function resigned(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => VillageOfficialStatusList::RESIGNED->value,
            ...$this->terminationData($attributes),
        ]);
    }

    /**
     * Membuat data keputusan pemberhentian.
     */
    private function terminationData(array $attributes): array
    {
        $terminationDate = fake()->dateTimeBetween(
            $attributes['tgl_pengangkatan'],
            'now'
        );

        return [
            'nomor_pemberhentian' => fake()
                ->unique()
                ->numerify('###/SK/DS/####'),

            'tgl_pemberhentian' => $terminationDate->format('Y-m-d'),
        ];
    }
}
