<?php

namespace Database\Factories;

use App\Models\ExpeditionEntry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExpeditionEntry>
 */
class ExpeditionEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sent_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'letter_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'letter_number' => fake()->unique()->numerify('###/###/###/####'),

            'summary' => fake()->randomElement([
                'Undangan rapat koordinasi tingkat kecamatan.',
                'Penyampaian laporan kegiatan pemerintahan desa.',
                'Permohonan bantuan sarana dan prasarana desa.',
                'Pemberitahuan pelaksanaan kegiatan desa.',
                'Pengajuan proposal pembangunan infrastruktur.',
                'Laporan pertanggungjawaban kegiatan desa.',
                'Permohonan rekomendasi administrasi.',
                'Penyampaian data kependudukan desa.',
            ]),

            'recipient' => fake()->randomElement([
                'Camat',
                'Bupati',
                'Kepala Dinas Pemberdayaan Masyarakat dan Desa',
                'Kepala Dinas Kependudukan dan Pencatatan Sipil',
                'Kepala Desa',
                'Ketua BPD',
                'Kepala Puskesmas',
            ]),

            'notes' => fake()->optional(0.4)->sentence(),

            'created_by' => User::factory(),
        ];
    }
}
