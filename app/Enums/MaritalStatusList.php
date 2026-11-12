<?php

namespace App\Enums;

enum MaritalStatusList: string
{
    case MARRIED = 'Menikah';
    case UNMARRIED = 'Belum menikah';
    case DIVORCED = 'Cerai hidup';
    case WIDOWED = 'Cerai mati';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function color(): string
    {
        return match ($this) {
            self::MARRIED => 'success',
            self::UNMARRIED => 'primary',
            self::DIVORCED => 'warning',
            self::WIDOWED => 'secondary',
        };
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
