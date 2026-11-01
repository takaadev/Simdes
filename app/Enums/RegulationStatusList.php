<?php

namespace App\Enums;

enum RegulationStatusList: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case REVOKED = 'revoked';
    case AMENDED = 'amended';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::ACTIVE => 'Berlaku',
            self::REVOKED => 'Dicabut',
            self::AMENDED => 'Diubah',
        };
    }

    public static function options(): array
    {
        return array_column(
            array_map(
                fn(self $status) => [
                    'value' => $status->value,
                    'label' => $status->label(),
                ],
                self::cases()
            ),
            'label',
            'value'
        );
    }
}
