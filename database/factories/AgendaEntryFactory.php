<?php

namespace Database\Factories;

use App\Enums\AgendaCategoryList;
use App\Enums\AgendaTypeList;
use App\Models\AgendaEntry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AgendaEntry>
 */
class AgendaEntryFactory extends Factory
{
    public function definition(): array
    {
        $agendaType = fake()->randomElement(AgendaTypeList::cases());

        $letterDate = fake()->dateTimeBetween('-1 year', 'now');

        $receivedDate = $agendaType === AgendaTypeList::INCOMING
            ? fake()->dateTimeBetween($letterDate, 'now')
            : null;

        $sentDate = $agendaType === AgendaTypeList::OUTGOING
            ? fake()->dateTimeBetween($letterDate, 'now')
            : null;

        $category = $agendaType === AgendaTypeList::OUTGOING
            ? fake()->randomElement(AgendaCategoryList::cases())
            : null;

        // Nomor surat masuk berasal dari instansi pengirim.
        // Nomor surat keluar mengikuti format kategori agenda.
        $letterNumber = $agendaType === AgendaTypeList::OUTGOING
            ? $category->formatNumber(
                sequence: fake()->unique()->numberBetween(1, 999999),
                year: (int) $letterDate->format('Y'),
                month: (int) $letterDate->format('n'),
                villageCode: 'DS'
            )
            : fake()->unique()->numerify('###/###/####');

        $disposition = $agendaType === AgendaTypeList::INCOMING
            ? fake()->optional(0.6)->randomElement([
                'Segera ditindaklanjuti.',
                'Koordinasikan dengan Sekretaris Desa.',
                'Pelajari dan siapkan laporan.',
                'Hadiri undangan sesuai jadwal.',
                'Arsipkan untuk dokumentasi.',
            ])
            : null;

        return [
            'tipe_agenda' => $agendaType,

            'kategori_agenda' => $category,

            'tanggal_terima' => $receivedDate,

            'tanggal_kirim' => $sentDate,

            'nomor_surat' => $letterNumber,

            'tgl_surat' => $letterDate,

            'pengirim' => $agendaType === AgendaTypeList::INCOMING
                ? fake()->randomElement([
                    'Kantor Kecamatan Muara Siau',
                    'Dinas Pemberdayaan Masyarakat dan Desa',
                    'Kantor Bupati Merangin',
                    'Dinas Kependudukan dan Pencatatan Sipil',
                    'Puskesmas',
                    'Kepolisian Sektor',
                    'Masyarakat',
                ])
                : null,

            'penerima' => $agendaType === AgendaTypeList::OUTGOING
                ? fake()->randomElement([
                    'Camat Muara Siau',
                    'Bupati Merangin',
                    'Kepala Dinas PMD',
                    'Kepala Puskesmas',
                    'Ketua BPD',
                    'Kepala Kepolisian Sektor',
                    'Masyarakat',
                ])
                : null,

            'isi_surat' => fake()->randomElement([
                'Undangan rapat koordinasi pemerintahan desa.',
                'Permohonan bantuan sarana dan prasarana desa.',
                'Penyampaian laporan kegiatan pemerintahan desa.',
                'Pemberitahuan pelaksanaan kegiatan desa.',
                'Pengajuan proposal pembangunan infrastruktur.',
                'Penyampaian laporan pertanggungjawaban.',
                'Permohonan rekomendasi administrasi.',
                'Penerbitan surat keterangan administrasi penduduk.',
            ]),

            'disposisi' => $disposition,

            'tgl_disposisi' => $disposition
                ? fake()->dateTimeBetween($receivedDate, 'now')
                : null,

            'dokumen' => null,

            'catatan' => fake()->optional(0.3)->sentence(),

            'created_by' => User::factory(),
        ];
    }

    /**
     * Factory khusus surat masuk.
     */
    public function incoming(): static
    {
        return $this->state(fn() => [
            'tipe_agenda' => AgendaTypeList::INCOMING,

            'kategori_agenda' => null,

            'tanggal_terima' => fake()->dateTimeBetween('-6 months', 'now'),

            'tanggal_kirim' => null,

            'pengirim' => fake()->company(),

            'penerima' => null,

            'disposisi' => null,

            'tgl_disposisi' => null,
        ]);
    }

    /**
     * Factory khusus surat keluar.
     */
    public function outgoing(): static
    {
        return $this->state(function () {
            $letterDate = fake()->dateTimeBetween('-6 months', 'now');

            $category = fake()->randomElement(AgendaCategoryList::cases());

            return [
                'tipe_agenda' => AgendaTypeList::OUTGOING,

                'kategori_agenda' => $category,

                'tanggal_terima' => null,

                'tanggal_kirim' => fake()->dateTimeBetween($letterDate, 'now'),

                'tgl_surat' => $letterDate,

                'nomor_surat' => $category->formatNumber(
                    sequence: fake()->unique()->numberBetween(1, 999999),
                    year: (int) $letterDate->format('Y'),
                    month: (int) $letterDate->format('n'),
                    villageCode: 'DS'
                ),

                'pengirim' => null,

                'penerima' => fake()->company(),

                'disposisi' => null,

                'tgl_disposisi' => null,
            ];
        });
    }
}
