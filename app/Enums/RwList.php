<?php

namespace App\Enums;

enum RwList: string
{
    case RW_000 = '000';
    case RW_001 = '001';
    case RW_002 = '002';
    case RW_003 = '003';
    case RW_004 = '004';

    public function label(): string
    {
        return 'RW ' . $this->value;
    }

    public static function options(): array
    {
        return array_column(
            array_map(
                fn(self $rw) => [
                    'value' => $rw->value,
                    'label' => $rw->label(),
                ],
                self::cases()
            ),
            'label',
            'value'
        );
    }
}
