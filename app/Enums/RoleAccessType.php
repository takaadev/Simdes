<?php

namespace App\Enums;

enum RoleAccessType: string
{
    case ADMIN = 'admin';
    case KADES = 'kades';
    case SEKDES = 'sekdes';
    case KAUR_UMUM = 'kaur_umum';
    case KAUR_KEU_REN = 'kaur_keu_ren';
    case KASI_PEM = 'kasi_pem';
    case KASI_PELAYANAN = 'kasi_pelayanan';
    case KADUS = 'kadus';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::KADES => 'Kepala Desa',
            self::SEKDES => 'Sekretaris Desa',
            self::KAUR_UMUM => 'Kaur Umum',
            self::KAUR_KEU_REN => 'Kaur Keuangan dan Perencanaan',
            self::KASI_PEM => 'Kasi Pemerintahan',
            self::KASI_PELAYANAN => 'Kasi Pelayanan',
            self::KADUS => 'Kepala Dusun',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ADMIN => 'dark',
            self::KADES => 'danger',
            self::SEKDES => 'primary',
            self::KAUR_UMUM => 'info',
            self::KAUR_KEU_REN => 'success',
            self::KASI_PEM => 'warning',
            self::KASI_PELAYANAN => 'secondary',
            self::KADUS => 'dark',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn(self $role) => [
                $role->value => $role->label(),
            ])
            ->all();
    }
}
