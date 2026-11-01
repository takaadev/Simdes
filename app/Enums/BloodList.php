<?php

namespace App\Enums;

enum BloodList: string
{
    case A = 'A';
    case B = 'B';
    case AB = 'AB';
    case O = 'O';

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
