<?php

namespace App\Enums;

enum MutationTypeList: string
{
    case ADD = 'add';
    case MINUS = 'minus';

    public function label(): string
    {
        return match ($this) {
            self::ADD => 'Penambahan',
            self::MINUS => 'Pengurangan',
        };
    }

    public static function options(): array
    {
        return array_column(
            array_map(
                fn(self $item) => [
                    'value' => $item->value,
                    'label' => $item->label(),
                ],
                self::cases()
            ),
            'label',
            'value'
        );
    }
}
