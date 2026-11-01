<?php

namespace App\Enums;

enum CitizenshipList: string
{
    case WNI = 'WNI';
    case WNA = 'WNA';
    case OTHER = 'Lainnya';

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
