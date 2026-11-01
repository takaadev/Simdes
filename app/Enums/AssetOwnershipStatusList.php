<?php

namespace App\Enums;

enum AssetOwnershipStatusList: string
{
    case OWNED = 'owned';
    case BORROWED = 'borrowed';
    case GRANT = 'grant';
    case DONATION = 'donation';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::OWNED => 'Milik Desa',
            self::BORROWED => 'Pinjaman',
            self::GRANT => 'Hibah',
            self::DONATION => 'Sumbangan',
            self::OTHER => 'Lainnya',
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
