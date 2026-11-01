<?php

namespace Database\Factories;

use App\Enums\AgendaCategoryList;
use App\Enums\AgendaTypeList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\AgendaEntry>
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

        return [
            'register_number' => fake()->unique()->numerify('####/2026'),

            'agenda_type' => $agendaType,

            'agenda_category' => fake()->randomElement(
                AgendaCategoryList::cases()
            ),

            'letter_number' => fake()->optional(0.9)
                ->numerify('###/###/####'),

            'letter_date' => $letterDate,

            'received_date' => $receivedDate,

            'sender' => $agendaType === AgendaTypeList::INCOMING
                ? fake()->randomElement([
                    'Kantor Kecamatan',
                    'Dinas Pemberdayaan Masyarakat dan Desa',
                    'Kantor Bupati',
                    'Dinas Kependudukan dan Pencatatan Sipil',
                    'Puskesmas',
                    'Kepolisian Sektor',
                    'Masyarakat',
                ])
                : null,

            'recipient' => $agendaType === AgendaTypeList::OUTGOING
                ? fake()->randomElement([
                    'Camat',
                    'Bupati',
                    'Kepala Dinas PMD',
                    'Kepala Puskesmas',
                    'Ketua BPD',
                    'Kepala Kepolisian Sektor',
                    'Masyarakat',
                ])
                : null,

            'subject' => fake()->randomElement([
                'Undangan rapat koordinasi pemerintahan desa.',
                'Permohonan bantuan sarana dan prasarana desa.',
                'Penyampaian laporan kegiatan pemerintahan desa.',
                'Pemberitahuan pelaksanaan kegiatan desa.',
                'Pengajuan proposal pembangunan infrastruktur.',
                'Penyampaian laporan pertanggungjawaban.',
                'Permohonan rekomendasi administrasi.',
                'Penerbitan surat keterangan administrasi penduduk.',
            ]),

            'disposition' => $agendaType === AgendaTypeList::INCOMING
                ? fake()->optional(0.6)->randomElement([
                    'Segera ditindaklanjuti.',
                    'Koordinasikan dengan Sekretaris Desa.',
                    'Pelajari dan siapkan laporan.',
                    'Hadiri undangan sesuai jadwal.',
                    'Arsipkan untuk dokumentasi.',
                ])
                : null,

            'disposition_date' => $agendaType === AgendaTypeList::INCOMING
                ? fake()->optional(0.6)->dateTimeBetween($receivedDate, 'now')
                : null,

            'document_file' => null,

            'notes' => fake()->optional(0.3)->sentence(),

            'created_by' => User::factory(),
        ];
    }

    public function incoming(): static
    {
        return $this->state(fn() => [
            'agenda_type' => AgendaTypeList::INCOMING,
            'sender' => fake()->company(),
            'recipient' => null,
            'received_date' => fake()->dateTimeBetween('-6 months', 'now'),
        ]);
    }

    public function outgoing(): static
    {
        return $this->state(fn() => [
            'agenda_type' => AgendaTypeList::OUTGOING,
            'sender' => null,
            'recipient' => fake()->company(),
            'received_date' => null,
            'disposition' => null,
            'disposition_date' => null,
        ]);
    }
}
