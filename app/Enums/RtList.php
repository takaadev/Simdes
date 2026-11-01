<?php

namespace App\Enums;

enum RtList: string
{
    case RT_000 = '000';
    case RT_001 = '001';
    case RT_002 = '002';
    case RT_003 = '003';
    case RT_004 = '004';

    public function label(): string
    {
        return 'RT ' . $this->value;
    }

    public static function options(): array
    {
        return array_column(
            array_map(
                fn(self $rt) => [
                    'value' => $rt->value,
                    'label' => $rt->label(),
                ],
                self::cases()
            ),
            'label',
            'value'
        );
    }
}
