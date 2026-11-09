<?php

namespace Database\Factories;

use App\Enums\LandClassList;
use App\Enums\RegionLandList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\VillageTreasuryLand>
 */
class VillageTreasuryLandFactory extends Factory
{
    public function definition(): array
    {
        $luasTanah = fake()->randomFloat(2, 100, 50000);

        // Asal perolehan tanah
        $acquisitionColumns = [
            'lt_asli_desa',
            'lt_pemerintah',
            'lt_provinsi',
            'lt_kabupaten',
            'lt_lainnya',
        ];

        $acquisition = array_fill_keys($acquisitionColumns, 0);

        $acquisition[fake()->randomElement($acquisitionColumns)] = $luasTanah;

        // Jenis tanah
        $typeColumns = [
            'lt_tipe_sawah',
            'lt_tipe_tegal',
            'lt_tipe_kebun',
            'lt_tipe_tambak',
            'lt_tipe_kering',
        ];

        $landTypes = array_fill_keys($typeColumns, 0);

        $landTypes[fake()->randomElement($typeColumns)] = $luasTanah;

        return [
            'asal_tanah' => fake()->randomElement(
                RegionLandList::cases()
            )->value,

            'nomor_sertifikat' => fake()->unique()->numerify('####/####/####'),

            'kelas_tanah' => fake()->randomElement(
                LandClassList::cases()
            )->value,

            'luas_tanah' => $luasTanah,

            ...$acquisition,

            'tgl_perolehan' => fake()->optional(0.8)->dateTimeBetween(
                '-20 years',
                'now'
            )?->format('Y-m-d'),

            ...$landTypes,

            'patok_tanah' => fake()->boolean(70),

            'papan_nama' => fake()->boolean(60),

            'lokasi' => fake()->randomElement([
                'Dusun I, dekat kantor desa',
                'Dusun II, pinggir jalan utama',
                'Dusun III, area perkebunan',
                'Dusun IV, dekat permukiman warga',
            ]),

            'peruntukan' => fake()->optional(0.8)->randomElement([
                'Tanah Pertanian',
                'Perkebunan Desa',
                'Lapangan Desa',
                'Fasilitas Umum',
                'Tanah Kas Desa',
            ]),

            'mutasi' => fake()->optional(0.3)->randomElement([
                'Pengukuran ulang luas tanah.',
                'Pemasangan patok tanda batas tanah.',
                'Perubahan peruntukan tanah.',
                'Pembaruan dokumen kepemilikan.',
            ]),

            'catatan' => fake()->optional(0.3)->sentence(),

            'created_by' => User::factory(),
        ];
    }

    public function withMarker(): static
    {
        return $this->state(fn() => [
            'patok_tanah' => true,
        ]);
    }

    public function withPlate(): static
    {
        return $this->state(fn() => [
            'papan_nama' => true,
        ]);
    }
}
