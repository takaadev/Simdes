<?php

namespace App\Enums;

enum AssetConditionList: string
{
    case GOOD = 'good';
    case LIGHTLY_DAMAGED = 'lightly_damaged';
    case HEAVILY_DAMAGED = 'heavily_damaged';

    public function label(): string
    {
        return match ($this) {
            self::GOOD => 'Baik',
            self::LIGHTLY_DAMAGED => 'Rusak Ringan',
            self::HEAVILY_DAMAGED => 'Rusak Berat',
        };
    }

    public static function options(): array
    {
        return array_column(
            array_map(
                fn(self $condition) => [
                    'value' => $condition->value,
                    'label' => $condition->label(),
                ],
                self::cases()
            ),
            'label',
            'value'
        );
    }
}
