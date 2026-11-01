<?php

namespace App\Enums;

enum DecreeStatusList: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case REVOKED = 'revoked';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::ACTIVE => 'Berlaku',
            self::REVOKED => 'Dicabut',
            self::EXPIRED => 'Berakhir',
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
