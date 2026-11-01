<?php

namespace App\Enums;

enum GenderList: string
{
    case L = 'L';
    case P = 'P';

    public function label(): string
    {
        return match ($this) {
            self::L => 'Laki-laki',
            self::P => 'Perempuan',
        };
    }

    public static function options(): array
    {
        return array_column(
            array_map(
                fn(self $gender) => [
                    'value' => $gender->value,
                    'label' => $gender->label(),
                ],
                self::cases()
            ),
            'label',
            'value'
        );
    }
}
