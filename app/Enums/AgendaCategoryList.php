<?php

namespace App\Enums;

enum AgendaCategoryList: string
{
    // Surat Keterangan
    case SKTM = 'sktm';
    case SKCK = 'skck';
    case SK_USAHA = 'sk_usaha';
    case SK_DOMISILI = 'sk_domisili';
    case SK_KELAHIRAN = 'sk_kelahiran';
    case SK_KEMATIAN = 'sk_kematian';
    case SK_TIDAK_MAMPU = 'sk_tidak_mampu';
    case SK_BELUM_MENIKAH = 'sk_belum_menikah';
    case SK_PENGHASILAN = 'sk_penghasilan';
    case SK_KEPEMILIKAN_TANAH = 'sk_kepemilikan_tanah';
    case SK_KEHILANGAN = 'sk_kehilangan';
    case SK_LAINNYA = 'sk_lainnya';

        // Surat Keputusan
    case SK_KEPALA_DESA = 'sk_kepala_desa';
    case SK_PENGANGKATAN_PERANGKAT_DESA = 'sk_pengangkatan_perangkat_desa';
    case SK_PEMBERHENTIAN_PERANGKAT_DESA = 'sk_pemberhentian_perangkat_desa';
    case SK_PEMBENTUKAN_PANITIA = 'sk_pembentukan_panitia';
    case SK_PENETAPAN_TIM = 'sk_penetapan_tim';

        // Surat Tugas dan Perjalanan Dinas
    case SURAT_TUGAS = 'surat_tugas';
    case SURAT_PERJALANAN_DINAS = 'surat_perjalanan_dinas';
    case SURAT_PERINTAH = 'surat_perintah';

        // Undangan dan Pemberitahuan
    case UNDANGAN = 'undangan';
    case PEMBERITAHUAN = 'pemberitahuan';
    case PANGGILAN = 'panggilan';

        // Permohonan dan Rekomendasi
    case PERMOHONAN = 'permohonan';
    case REKOMENDASI = 'rekomendasi';
    case PENGANTAR = 'pengantar';

        // Pemerintahan dan Administrasi Desa
    case SURAT_EDARAN = 'surat_edaran';
    case SURAT_PENGANTAR = 'surat_pengantar';
    case LAPORAN = 'laporan';
    case BERITA_ACARA = 'berita_acara';
    case NOTULEN = 'notulen';
    case PROPOSAL = 'proposal';

        // Peraturan Desa
    case PERATURAN_DESA = 'peraturan_desa';
    case PERATURAN_KEPALA_DESA = 'peraturan_kepala_desa';
    case KEPUTUSAN_KEPALA_DESA = 'keputusan_kepala_desa';

        // Lainnya
    case LAINNYA = 'lainnya';

    public function label(): string
    {
        return match ($this) {
            self::SKTM => 'Surat Keterangan Tidak Mampu (SKTM)',
            self::SKCK => 'Surat Pengantar SKCK',
            self::SK_USAHA => 'Surat Keterangan Usaha',
            self::SK_DOMISILI => 'Surat Keterangan Domisili',
            self::SK_KELAHIRAN => 'Surat Keterangan Kelahiran',
            self::SK_KEMATIAN => 'Surat Keterangan Kematian',
            self::SK_TIDAK_MAMPU => 'Surat Keterangan Tidak Mampu',
            self::SK_BELUM_MENIKAH => 'Surat Keterangan Belum Menikah',
            self::SK_PENGHASILAN => 'Surat Keterangan Penghasilan',
            self::SK_KEPEMILIKAN_TANAH => 'Surat Keterangan Kepemilikan Tanah',
            self::SK_KEHILANGAN => 'Surat Keterangan Kehilangan',
            self::SK_LAINNYA => 'Surat Keterangan Lainnya',

            self::SK_KEPALA_DESA => 'Surat Keputusan Kepala Desa',
            self::SK_PENGANGKATAN_PERANGKAT_DESA => 'SK Pengangkatan Perangkat Desa',
            self::SK_PEMBERHENTIAN_PERANGKAT_DESA => 'SK Pemberhentian Perangkat Desa',
            self::SK_PEMBENTUKAN_PANITIA => 'SK Pembentukan Panitia',
            self::SK_PENETAPAN_TIM => 'SK Penetapan Tim',

            self::SURAT_TUGAS => 'Surat Tugas',
            self::SURAT_PERJALANAN_DINAS => 'Surat Perjalanan Dinas',
            self::SURAT_PERINTAH => 'Surat Perintah',

            self::UNDANGAN => 'Surat Undangan',
            self::PEMBERITAHUAN => 'Surat Pemberitahuan',
            self::PANGGILAN => 'Surat Panggilan',

            self::PERMOHONAN => 'Surat Permohonan',
            self::REKOMENDASI => 'Surat Rekomendasi',
            self::PENGANTAR => 'Surat Pengantar',

            self::SURAT_EDARAN => 'Surat Edaran',
            self::SURAT_PENGANTAR => 'Surat Pengantar',
            self::LAPORAN => 'Laporan',
            self::BERITA_ACARA => 'Berita Acara',
            self::NOTULEN => 'Notulen Rapat',
            self::PROPOSAL => 'Proposal',

            self::PERATURAN_DESA => 'Peraturan Desa (Perdes)',
            self::PERATURAN_KEPALA_DESA => 'Peraturan Kepala Desa (Perkades)',
            self::KEPUTUSAN_KEPALA_DESA => 'Keputusan Kepala Desa',

            self::LAINNYA => 'Lain-lain',
        };
    }

    public static function options(): array
    {
        return array_column(
            array_map(
                fn(self $case) => [
                    'value' => $case->value,
                    'label' => $case->label(),
                ],
                self::cases()
            ),
            'label',
            'value'
        );
    }
}
