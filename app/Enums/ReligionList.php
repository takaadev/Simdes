<?php

namespace App\Enums;

enum ReligionList: string
{
    case ISLAM = 'Islam';
    case KRISTEN = 'Kristen';
    case KATOLIK = 'Katolik';
    case HINDU = 'Hindu';
    case BUDDHA = 'Buddha';
    case KONGHUCU = 'Konghucu';

    public function label(): string
    {
        return $this->value;
    }

    public static function options(): array
    {
        return array_column(
            array_map(fn(self $item) => [
                'value' => $item->value,
                'label' => $item->label(),
            ], self::cases()),
            'label',
            'value'
        );
    }
}
