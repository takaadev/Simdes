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

        $category = fake()->randomElement(AgendaCategoryList::cases());

        $letterDate = fake()->dateTimeBetween('-1 year', 'now');

        $receivedDate = $agendaType === AgendaTypeList::INCOMING
            ? fake()->dateTimeBetween($letterDate, 'now')
            : null;

        $sentDate = $agendaType === AgendaTypeList::OUTGOING
            ? fake()->dateTimeBetween($letterDate, 'now')
            : null;

        // Nomor surat masuk berasal dari instansi pengirim.
        // Nomor surat keluar dibuat berdasarkan kategori agenda.
        $letterNumber = $agendaType === AgendaTypeList::OUTGOING
            ? $category->formatNumber(
                sequence: fake()->numberBetween(1, 999),
                year: (int) $letterDate->format('Y'),
                month: (int) $letterDate->format('n'),
                villageCode: 'DS'
            )
            : fake()->numerify('###/###/####');

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

    public function incoming(): static
    {
        return $this->state(fn() => [
            'tipe_agenda' => AgendaTypeList::INCOMING,

            'tanggal_terima' => fake()->dateTimeBetween('-6 months', 'now'),

            'tanggal_kirim' => null,

            'pengirim' => fake()->company(),

            'penerima' => null,

            'disposisi' => null,

            'tgl_disposisi' => null,
        ]);
    }

    public function outgoing(): static
    {
        return $this->state(fn() => [
            'tipe_agenda' => AgendaTypeList::OUTGOING,

            'tanggal_terima' => null,

            'tanggal_kirim' => fake()->dateTimeBetween('-6 months', 'now'),

            'pengirim' => null,

            'penerima' => fake()->company(),

            'disposisi' => null,

            'tgl_disposisi' => null,
        ]);
    }
}
