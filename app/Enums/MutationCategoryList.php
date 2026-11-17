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

    /**
     * Mengambil opsi kategori mutasi.
     *
     * @param string|null $only Kategori yang ingin ditampilkan.
     */
    public static function options(?string $only = null): array
    {
        $allowed = $only !== null
            ? array_map('trim', explode(',', strtoupper($only)))
            : null;

        return collect(self::cases())
            ->filter(fn (self $item) =>
                $allowed === null || in_array($item->name, $allowed, true)
            )
            ->mapWithKeys(fn (self $item) => [
                $item->value => $item->label(),
            ])
            ->toArray();
    }
}
