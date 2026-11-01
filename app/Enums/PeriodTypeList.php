<?php

namespace App\Enums;

enum PeriodTypeList: string
{
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';
    case CUSTOM = 'custom';

    public function label(): string
    {
        return match ($this) {
            self::MONTHLY => 'Bulanan',
            self::YEARLY => 'Tahunan',
            self::CUSTOM => 'Kustom',
        };
    }

    public static function options(): array
    {
        return array_column(
            array_map(
                fn(self $period) => [
                    'value' => $period->value,
                    'label' => $period->label(),
                ],
                self::cases()
            ),
            'label',
            'value'
        );
    }
}
