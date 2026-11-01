<?php

namespace App\Enums;

enum EducationList: string
{
    case NOT_SCHOOL = 'Tidak/Belum Sekolah';
    case SD = 'SD';
    case SMP = 'SMP';
    case SMA = 'SMA';
    case DIPLOMA = 'Diploma';
    case S1 = 'S1';
    case S2 = 'S2';
    case S3 = 'S3';

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
