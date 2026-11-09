<?php

namespace App\Enums;

enum RegionLandList: string
{
    case TANAH_ULAYAT = 'tanah_ulayat';
    case PEMBELIAN_DESA = 'pembelian_desa';
    case TANAH_BENGKOK = 'tanah_bengkok';
    case TANAH_PENGAREM_AREM = 'tanah_pengarem_arem';
    case TANAH_YASAN = 'tanah_yasan';
    case BANTUAN_PUSAT = 'bantuan_pusat';
    case BANTUAN_PROVINSI = 'bantuan_provinsi';
    case BANTUAN_KABUPATEN_KOTA = 'bantuan_kabupaten_kota';
    case HIBAH_WAKAF = 'hibah_wakaf';
    case SUMBANGAN_CSR = 'sumbangan_csr';

    public function label(): string
    {
        return match ($this) {
            self::TANAH_ULAYAT => 'Tanah Ulayat / Tanah Adat',
            self::PEMBELIAN_DESA => 'Tanah Pembelian Desa',
            self::TANAH_BENGKOK => 'Tanah Bengkok / Eks-Bengkok',
            self::TANAH_PENGAREM_AREM => 'Tanah Pengarem-arem',
            self::TANAH_YASAN => 'Konversi Hak Adat / Tanah Yasan',
            self::BANTUAN_PUSAT => 'Bantuan Pemerintah Pusat',
            self::BANTUAN_PROVINSI => 'Bantuan Pemerintah Provinsi',
            self::BANTUAN_KABUPATEN_KOTA => 'Bantuan Pemerintah Kabupaten/Kota',
            self::HIBAH_WAKAF => 'Hibah / Wakaf dari Masyarakat',
            self::SUMBANGAN_CSR => 'Sumbangan / Corporate Social Responsibility (CSR)',
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
