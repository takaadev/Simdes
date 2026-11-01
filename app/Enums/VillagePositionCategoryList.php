<?php

namespace App\Enums;

enum VillagePositionCategoryList: string
{
    case PEMDES = '10';
    case KAUR = '20';
    case BPD = '30';
    case LAD = '40';

    public function label(): string
    {
        return match ($this) {
            self::PEMDES => 'Pemerintah Desa',
            self::KAUR => 'Kepala Urusan (KAUR)',
            self::BPD => 'Badan Permusyawaratan Desa (BPD)',
            self::LAD => 'Lembaga Adat Desa (LAD)',
        };
    }

    public static function options(): array
    {
        return array_column(
            array_map(
                fn(self $case) => [
                    'value' => $case->value,
                    'label' => $case->label(),
                ],
                self::cases()
            ),
            'label',
            'value'
        );
    }
}
