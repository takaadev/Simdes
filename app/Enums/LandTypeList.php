<?php

namespace App\Enums;

enum LandTypeList: string
{
    case RICE_FIELD = 'rice_field';
    case DRY_FIELD = 'dry_field';
    case PLANTATION = 'plantation';
    case POND = 'pond';
    case DRY_LAND = 'dry_land';
    case BUILDING_LAND = 'building_land';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::RICE_FIELD => 'Sawah',
            self::DRY_FIELD => 'Tegal',
            self::PLANTATION => 'Kebun',
            self::POND => 'Tambak/Kolam',
            self::DRY_LAND => 'Tanah Kering/Darat',
            self::BUILDING_LAND => 'Tanah Bangunan',
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
