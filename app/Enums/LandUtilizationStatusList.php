<?php

namespace App\Enums;

enum LandUtilizationStatusList: string
{
    case UTILIZED = 'utilized';
    case LEASED = 'leased';
    case COOPERATION = 'cooperation';
    case UNUSED = 'unused';

    public function label(): string
    {
        return match ($this) {
            self::UTILIZED => 'Dimanfaatkan Sendiri',
            self::LEASED => 'Disewakan',
            self::COOPERATION => 'Kerja Sama Pemanfaatan',
            self::UNUSED => 'Belum Dimanfaatkan',
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
