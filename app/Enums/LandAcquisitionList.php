<?php

namespace App\Enums;

enum LandAcquisitionList: string
{
    case VILLAGE_OWNED = 'village_owned';
    case GOVERNMENT = 'government';
    case ASSISTANCE = 'assistance';
    case PROVINCE = 'province';
    case REGENCY_CITY = 'regency_city';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::VILLAGE_OWNED => 'Asli Milik Desa',
            self::GOVERNMENT => 'Pemerintah',
            self::ASSISTANCE => 'Bantuan',
            self::PROVINCE => 'Provinsi',
            self::REGENCY_CITY => 'Kabupaten/Kota',
            self::OTHER => 'Lain-lain',
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
