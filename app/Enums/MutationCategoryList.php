<?php

namespace App\Enums;

enum MutationCategoryList: string
{
    case BIRTH = 'birth';
    case DEATH = 'death';
    case INCOMING = 'incoming';
    case OUTGOING = 'outgoing';

    public function label(): string
    {
        return match ($this) {
            self::BIRTH => 'Kelahiran',
            self::DEATH => 'Kematian',
            self::INCOMING => 'Penduduk Masuk',
            self::OUTGOING => 'Penduduk Keluar',
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
