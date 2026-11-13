<?php

namespace App\Enums;

enum CitizenshipList: string
{
    case WNI = 'WNI';
    case WNA = 'WNA';

    public function label(): string
    {
        return match ($this) {
            self::WNI => 'WNI',
            self::WNA => 'WNA',
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
