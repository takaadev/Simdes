<?php

namespace App\Enums;

enum AgendaCategoryList: string
{
    // =========================================================================
    // A. PRODUK HUKUM & NASKAH DINAS ARAHAN DESA (Permendagri No. 111 Tahun 2014)
    // =========================================================================
    case PERDES = 'perdes';
    case PERMAKADES = 'permakades';
    case PERKADES = 'perkades';
    case SK_KADES = 'sk_kades';
    case SK_BPD = 'sk_bpd';
    case LEMBARAN_DESA = 'lembaran_desa';
    case BERITA_DESA = 'berita_desa';

        // =========================================================================
        // B. SURAT PELAYANAN KEPENDUDUKAN & SIPIL
        // =========================================================================
    case SUKET_UMUM = 'suket_umum';
    case SUKET_DOMISILI = 'suket_domisili';
    case PENGANTAR_RT_RW = 'pengantar_rt_rw';
    case PENGANTAR_KTP_KK = 'pengantar_ktp_kk';
    case SUKET_PINDAH_DATANG = 'suket_pindah_datang';
    case SUKET_KELAHIRAN = 'suket_kelahiran';
    case SUKET_KEMATIAN = 'suket_kematian';
    case SUKET_BELUM_MENIKAH = 'suket_belum_menikah';
    case PENGANTAR_NIKAH = 'pengantar_nikah';
    case SUKET_BEDA_NAMA = 'suket_beda_nama';

        // =========================================================================
        // C. SURAT PELAYANAN SOSIAL, EKONOMI & UMKM
        // =========================================================================
    case SKTM = 'sktm';
    case SUKET_PENGHASILAN = 'suket_penghasilan';
    case SUKET_USAHA = 'suket_usaha';
    case SUKET_DOMISILI_USAHA = 'suket_domisili_usaha';

        // =========================================================================
        // D. SURAT PERTANAHAN, HUKUM, KEPERDATAAN & KEAMANAN
        // =========================================================================
    case SUKET_TANAH = 'suket_tanah';
    case SUKET_AHLI_WARIS = 'suket_ahli_waris';
    case PENGANTAR_SKCK = 'pengantar_skck';
    case SUKET_KEHILANGAN = 'suket_kehilangan';
    case IZIN_KERAMAIAN = 'izin_keramaian';

        // =========================================================================
        // E. NASKAH DINAS INTERNAL, KORESPONDENSI, KEPEGAWAIAN & KEUANGAN DESA
        // =========================================================================
    case SURAT_UNDANGAN = 'surat_undangan';
    case SURAT_TUGAS = 'surat_tugas';
    case SPPD = 'sppd';
    case SURAT_CUTI = 'surat_cuti';
    case SURAT_KEPEGAWAIAN_DESA = 'surat_kepegawaian_desa';
    case SURAT_KEUANGAN_DESA = 'surat_keuangan_desa';
    case SURAT_ASET_DESA = 'surat_aset_desa';
    case SURAT_PEMBANGUNAN_DESA = 'surat_pembangunan_desa';
    case BERITA_ACARA = 'berita_acara';
    case SURAT_EDARAN = 'surat_edaran';
    case SURAT_PERJANJIAN_SPK = 'surat_perjanjian_spk';
    case SURAT_KUASA = 'surat_kuasa';

    /**
     * Label nama dokumen/surat.
     */
    public function label(): string
    {
        return match ($this) {
            // A. Produk Hukum Desa
            self::PERDES => 'Peraturan Desa (Perdes)',
            self::PERMAKADES => 'Peraturan Bersama Kepala Desa (Permakades)',
            self::PERKADES => 'Peraturan Kepala Desa (Perkades)',
            self::SK_KADES => 'Keputusan Kepala Desa (SK Kepala Desa)',
            self::SK_BPD => 'Keputusan BPD (SK BPD)',
            self::LEMBARAN_DESA => 'Lembaran Desa',
            self::BERITA_DESA => 'Berita Desa',

            // B. Pelayanan Kependudukan & Sipil
            self::SUKET_UMUM => 'Surat Keterangan Umum',
            self::SUKET_DOMISILI => 'Surat Keterangan Domisili Tempat Tinggal',
            self::PENGANTAR_RT_RW => 'Surat Pengantar RT / RW',
            self::PENGANTAR_KTP_KK => 'Surat Pengantar KTP / Kartu Keluarga',
            self::SUKET_PINDAH_DATANG => 'Surat Keterangan Pindah / Datang Penduduk',
            self::SUKET_KELAHIRAN => 'Surat Keterangan Kelahiran',
            self::SUKET_KEMATIAN => 'Surat Keterangan Kematian',
            self::SUKET_BELUM_MENIKAH => 'Surat Keterangan Belum Menikah / Lajang',
            self::PENGANTAR_NIKAH => 'Surat Pengantar Nikah (N1 - N4)',
            self::SUKET_BEDA_NAMA => 'Surat Keterangan Beda Nama',

            // C. Pelayanan Sosial, Ekonomi & UMKM
            self::SKTM => 'Surat Keterangan Tidak Mampu (SKTM)',
            self::SUKET_PENGHASILAN => 'Surat Keterangan Penghasilan Orang Tua',
            self::SUKET_USAHA => 'Surat Keterangan Usaha (SKU)',
            self::SUKET_DOMISILI_USAHA => 'Surat Keterangan Domisili Usaha / Lembaga',

            // D. Pertanahan, Hukum, Keperdataan & Keamanan
            self::SUKET_TANAH => 'Surat Keterangan Tanah (SKT / Sporadik)',
            self::SUKET_AHLI_WARIS => 'Surat Keterangan Ahli Waris',
            self::PENGANTAR_SKCK => 'Surat Pengantar SKCK',
            self::SUKET_KEHILANGAN => 'Surat Keterangan Pengantar Kehilangan',
            self::IZIN_KERAMAIAN => 'Surat Pengantar / Rekomendasi Izin Keramaian',

            // E. Naskah Dinas Internal, Korespondensi & Keuangan
            self::SURAT_UNDANGAN => 'Surat Undangan Rapat / Musdes',
            self::SURAT_TUGAS => 'Surat Perintah Tugas (SPT)',
            self::SPPD => 'Surat Perintah Perjalanan Dinas (SPPD)',
            self::SURAT_CUTI => 'Surat Izin Cuti Perangkat Desa',
            self::SURAT_KEPEGAWAIAN_DESA => 'Surat Administrasi Pamong / Perangkat Desa',
            self::SURAT_KEUANGAN_DESA => 'Surat Administrasi Keuangan & Pencairan (SPP/APBDes)',
            self::SURAT_ASET_DESA => 'Surat Pengelolaan Aset & Inventaris Desa',
            self::SURAT_PEMBANGUNAN_DESA => 'Surat Administrasi Pembangunan & Pemberdayaan',
            self::BERITA_ACARA => 'Berita Acara (BA)',
            self::SURAT_EDARAN => 'Surat Edaran (SE) / Pengumuman',
            self::SURAT_PERJANJIAN_SPK => 'Surat Perjanjian Kerjasama / SPK',
            self::SURAT_KUASA => 'Surat Kuasa / Mandat Kedinasan',
        };
    }

    /**
     * Kategori rumpun dokumen.
     */
    public function category(): string
    {
        return match ($this) {
            self::PERDES,
            self::PERMAKADES,
            self::PERKADES,
            self::SK_KADES,
            self::SK_BPD,
            self::LEMBARAN_DESA,
            self::BERITA_DESA => 'Produk Hukum & Penetapan Desa',

            self::SUKET_UMUM,
            self::SUKET_DOMISILI,
            self::PENGANTAR_RT_RW,
            self::PENGANTAR_KTP_KK,
            self::SUKET_PINDAH_DATANG,
            self::SUKET_KELAHIRAN,
            self::SUKET_KEMATIAN,
            self::SUKET_BELUM_MENIKAH,
            self::PENGANTAR_NIKAH,
            self::SUKET_BEDA_NAMA => 'Pelayanan Kependudukan & Sipil',

            self::SKTM,
            self::SUKET_PENGHASILAN,
            self::SUKET_USAHA,
            self::SUKET_DOMISILI_USAHA => 'Pelayanan Sosial, Ekonomi & UMKM',

            self::SUKET_TANAH,
            self::SUKET_AHLI_WARIS,
            self::PENGANTAR_SKCK,
            self::SUKET_KEHILANGAN,
            self::IZIN_KERAMAIAN => 'Pertanahan, Keperdataan & Keamanan',

            self::SURAT_UNDANGAN,
            self::SURAT_TUGAS,
            self::SPPD,
            self::SURAT_CUTI,
            self::SURAT_KEPEGAWAIAN_DESA,
            self::SURAT_KEUANGAN_DESA,
            self::SURAT_ASET_DESA,
            self::SURAT_PEMBANGUNAN_DESA,
            self::BERITA_ACARA,
            self::SURAT_EDARAN,
            self::SURAT_PERJANJIAN_SPK,
            self::SURAT_KUASA => 'Naskah Dinas Internal & Korespondensi',
        };
    }

    /**
     * Kode Klasifikasi Arsip berdasarkan Permendagri Nomor 83 Tahun 2022.
     */
    public function classificationCode(): ?string
    {
        return match ($this) {
            self::PERDES,
            self::PERMAKADES,
            self::PERKADES => '100.3.2',
            self::SK_KADES => '100.3.3',
            self::SK_BPD => '100.1.4',
            self::LEMBARAN_DESA,
            self::BERITA_DESA => null,

            self::SUKET_UMUM => '400.7.22.1',
            self::SUKET_DOMISILI,
            self::PENGANTAR_RT_RW,
            self::SUKET_BEDA_NAMA => '400.12',
            self::PENGANTAR_KTP_KK,
            self::SUKET_PINDAH_DATANG => '400.12.2',
            self::SUKET_KELAHIRAN,
            self::SUKET_KEMATIAN,
            self::SUKET_BELUM_MENIKAH,
            self::PENGANTAR_NIKAH => '400.12.3',

            self::SKTM,
            self::SUKET_PENGHASILAN => '400.9',
            self::SUKET_USAHA,
            self::SUKET_DOMISILI_USAHA => '500.3',

            self::SUKET_TANAH => '500.17',
            self::SUKET_AHLI_WARIS => '100.3',
            self::PENGANTAR_SKCK,
            self::SUKET_KEHILANGAN,
            self::IZIN_KERAMAIAN => '300',

            self::SURAT_UNDANGAN => '000.1.5',
            self::SURAT_TUGAS => '800.1.11.1',
            self::SPPD => '000.1.2.3',
            self::SURAT_CUTI => '800.1.11',
            self::SURAT_KEPEGAWAIAN_DESA => '100.1.4',
            self::SURAT_KEUANGAN_DESA => '900.1.3.1',
            self::SURAT_ASET_DESA => '000.2',
            self::SURAT_PEMBANGUNAN_DESA => '400.10',
            self::BERITA_ACARA,
            self::SURAT_EDARAN => '100.1.4',
            self::SURAT_PERJANJIAN_SPK => '600',
            self::SURAT_KUASA => '800.1.11.1',
        };
    }

    /**
     * Kode Klasifikasi Konvensional (3 digit, Permendagri No. 78 Tahun 2012)
     * apabila pemerintah kabupaten/desa masih menggunakan kode indeks lama.
     */
    public function legacyCode(): ?string
    {
        return match ($this) {
            self::PERDES,
            self::PERMAKADES,
            self::PERKADES,
            self::SK_KADES,
            self::SK_BPD => '140',
            self::LEMBARAN_DESA,
            self::BERITA_DESA => null,

            self::SUKET_UMUM,
            self::SUKET_DOMISILI,
            self::PENGANTAR_RT_RW,
            self::SUKET_BEDA_NAMA => '470',
            self::PENGANTAR_KTP_KK => '474',
            self::SUKET_PINDAH_DATANG => '475',
            self::SUKET_KELAHIRAN => '474.1',
            self::SUKET_BELUM_MENIKAH,
            self::PENGANTAR_NIKAH => '474.2',
            self::SUKET_KEMATIAN => '474.3',

            self::SKTM => '460',
            self::SUKET_PENGHASILAN => '422.5',
            self::SUKET_USAHA,
            self::SUKET_DOMISILI_USAHA => '500',

            self::SUKET_TANAH => '590',
            self::SUKET_AHLI_WARIS => '180',
            self::PENGANTAR_SKCK,
            self::SUKET_KEHILANGAN => '331',
            self::IZIN_KERAMAIAN => '300',

            self::SURAT_UNDANGAN => '005',
            self::SURAT_TUGAS => '094',
            self::SPPD => '090',
            self::SURAT_CUTI => '850',
            self::SURAT_KEPEGAWAIAN_DESA => '141',
            self::SURAT_KEUANGAN_DESA => '900',
            self::SURAT_ASET_DESA => '143',
            self::SURAT_PEMBANGUNAN_DESA => '410',
            self::BERITA_ACARA,
            self::SURAT_EDARAN => '140',
            self::SURAT_PERJANJIAN_SPK => '600',
            self::SURAT_KUASA => '800',
        };
    }

    /**
     * Pola format penomoran resmi masing-masing kategori.
     */
    public function numberPattern(): string
    {
        return match ($this) {
            self::PERDES,
            self::PERMAKADES,
            self::PERKADES,
            self::SK_KADES => 'NOMOR {urut} TAHUN {tahun}',

            self::SK_BPD => 'NOMOR {urut}/BPD/{tahun}',

            self::LEMBARAN_DESA,
            self::BERITA_DESA => 'TAHUN {tahun} NOMOR {urut}',

            self::BERITA_ACARA => '{kode}/{urut}/BA/{kode_desa}/{bulan_romawi}/{tahun}',

            self::SURAT_EDARAN => '{kode}/{urut}/SE/{tahun}',

            self::SURAT_PERJANJIAN_SPK => '{kode}/{urut}/SPK/{kode_desa}/{tahun}',

            default => '{kode}/{urut}/{kode_desa}/{bulan_romawi}/{tahun}',
        };
    }

    /**
     * Contoh hasil penomoran resmi untuk ditampilkan sebagai placeholder/hint di form.
     */
    public function exampleNumber(): string
    {
        return $this->formatNumber(
            sequence: 15,
            year: (int) date('Y'),
            month: 4,
            villageCode: 'DS'
        );
    }

    /**
     * Generator nomor surat/SK otomatis sesuai pola resmi.
     */
    public function formatNumber(
        int $sequence,
        ?int $year = null,
        ?int $month = null,
        string $villageCode = 'DS',
        bool $useLegacyCode = false
    ): string {
        $year ??= (int) date('Y');
        $month ??= (int) date('n');

        $isRegulation = in_array($this, [
            self::PERDES,
            self::PERMAKADES,
            self::PERKADES,
            self::SK_KADES,
            self::SK_BPD,
            self::LEMBARAN_DESA,
            self::BERITA_DESA,
        ], true);

        $paddedSequence = str_pad((string) $sequence, $isRegulation ? 2 : 3, '0', STR_PAD_LEFT);
        $code = ($useLegacyCode ? $this->legacyCode() : $this->classificationCode()) ?? '';
        $romanMonth = self::toRomanMonth($month);

        return strtr($this->numberPattern(), [
            '{kode}' => $code,
            '{urut}' => $paddedSequence,
            '{kode_desa}' => $villageCode,
            '{bulan_romawi}' => $romanMonth,
            '{tahun}' => (string) $year,
        ]);
    }

    /**
     * Konversi angka bulan (1-12) ke angka Romawi (I-XII).
     */
    public static function toRomanMonth(int $month): string
    {
        return match ($month) {
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
            default => 'I',
        };
    }

    /**
     * Daftar opsi flat [value => label] untuk komponen <x-input-select>.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }

    /**
     * Daftar opsi dikelompokkan berdasarkan kategori [kategori => [value => label]].
     *
     * @return array<string, array<string, string>>
     */
    public static function groupedOptions(): array
    {
        $grouped = [];

        foreach (self::cases() as $case) {
            $grouped[$case->category()][$case->value] = $case->label();
        }

        return $grouped;
    }
}
