<?php

namespace App\Enums;

enum RelationshipList: string
{
    case HEAD = 'Kepala Keluarga';
    case WIFE = 'Istri';
    case HUSBAND = 'Suami';
    case CHILD = 'Anak';
    case SON_IN_LAW = 'Menantu';
    case GRANDCHILD = 'Cucu';
    case PARENT = 'Orang Tua';
    case PARENT_IN_LAW = 'Mertua';
    case OTHER_FAMILY = 'Famili Lain';

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
