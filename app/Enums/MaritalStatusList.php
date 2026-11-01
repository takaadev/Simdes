<?php

namespace App\Enums;

enum MaritalStatusList: string
{
    case MARRIED = 'menikah';
    case UNMARRIED = 'belum menikah';
    case DIVORCED = 'cerai hidup';
    case WIDOWED = 'cerai mati';

    public function label(): string
    {
        return ucfirst($this->value);
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
