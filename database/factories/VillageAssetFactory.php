<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\VillageAsset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VillageAsset>
 */
class VillageAssetFactory extends Factory
{
    protected $model = VillageAsset::class;

    public function definition(): array
    {
        $assets = [
            'Komputer Desktop',
            'Laptop',
            'Printer',
            'Meja Kerja',
            'Kursi Kantor',
            'Lemari Arsip',
            'Kendaraan Operasional',
            'Mesin Potong Rumput',
            'Proyektor',
            'Peralatan Kebersihan',
            'Peralatan Pertanian',
            'Peralatan Pembangunan',
        ];

        $totalAwal = fake()->numberBetween(1, 20);

        $kondisiAwalRusak = fake()->numberBetween(
            0,
            min(5, $totalAwal)
        );

        $kondisiAwalBaik = $totalAwal - $kondisiAwalRusak;

        $hapusRusak = fake()->numberBetween(
            0,
            $kondisiAwalRusak
        );

        $hapusDijual = fake()->numberBetween(
            0,
            $kondisiAwalBaik
        );

        $hapusDisumbangkan = fake()->numberBetween(
            0,
            $kondisiAwalBaik - $hapusDijual
        );

        $kondisiAkhirRusak = $kondisiAwalRusak - $hapusRusak;

        $kondisiAkhirBaik = $kondisiAwalBaik
            - $hapusDijual
            - $hapusDisumbangkan;

        $tanggalHapus = (
            $hapusRusak + $hapusDijual + $hapusDisumbangkan
        ) > 0
            ? fake()->dateTimeBetween('-3 years', 'now')->format('Y-m-d')
            : null;

        return [
            'nama_asset' => fake()->randomElement($assets),

            // Asal perolehan
            'asal_beli' => fake()->numberBetween(0, $totalAwal),
            'asal_pemerintah' => 0,
            'asal_provinsi' => 0,
            'asal_kabupaten' => 0,
            'asal_sumbangan' => 0,

            // Kondisi awal
            'kondisi_awal_baik' => $kondisiAwalBaik,
            'kondisi_awal_rusak' => $kondisiAwalRusak,

            // Penghapusan
            'hapus_rusak' => $hapusRusak,
            'hapus_dijual' => $hapusDijual,
            'hapus_disumbangkan' => $hapusDisumbangkan,
            'tgl_hapus' => $tanggalHapus,

            // Kondisi akhir
            'kondisi_ahir_baik' => $kondisiAkhirBaik,
            'kondisi_ahir_rusak' => $kondisiAkhirRusak,

            'catatan' => fake()->optional()->sentence(),

            'dokumen' => null,

            'created_by' => User::factory(),
        ];
    }

    public function good(): static
    {
        return $this->state(fn() => [
            'kondisi_awal_rusak' => 0,
            'kondisi_ahir_rusak' => 0,
        ]);
    }

    public function damaged(): static
    {
        return $this->state(fn() => [
            'kondisi_awal_rusak' => fake()->numberBetween(1, 5),
        ]);
    }
}
