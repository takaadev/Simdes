<?php

namespace App\Enums;

enum RegulationTypeList: string
{
    case PERDES = 'perdes';
    case PERKADES = 'perkades';
    case SK_KEPALA_DESA = 'sk_kepala_desa';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::PERDES => 'Peraturan Desa',
            self::PERKADES => 'Peraturan Kepala Desa',
            self::SK_KEPALA_DESA => 'Keputusan Kepala Desa',
            self::OTHER => 'Lainnya',
        };
    }

    public static function options(): array
    {
        return array_column(
            array_map(
                fn(self $type) => [
                    'value' => $type->value,
                    'label' => $type->label(),
                ],
                self::cases()
            ),
            'label',
            'value'
        );
    }
}
