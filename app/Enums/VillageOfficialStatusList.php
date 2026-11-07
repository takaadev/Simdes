<?php

namespace App\Enums;

enum VillageOfficialStatusList: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case RETIRED = 'retired';
    case DISMISSED = 'dismissed';
    case RESIGNED = 'resigned';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Aktif',
            self::INACTIVE => 'Tidak Aktif',
            self::RETIRED => 'Pensiun',
            self::DISMISSED => 'Diberhentikan',
            self::RESIGNED => 'Mengundurkan Diri',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'secondary',
            self::RETIRED => 'info',
            self::DISMISSED => 'danger',
            self::RESIGNED => 'warning',
        };
    }

    public static function options(): array
    {
        return array_column(
            array_map(
                fn(self $status) => [
                    'value' => $status->value,
                    'label' => $status->label(),
                ],
                self::cases()
            ),
            'label',
            'value'
        );
    }
}
