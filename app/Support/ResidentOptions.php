<?php

namespace App\Support;

use App\Models\Resident;

class ResidentOptions
{
    public static function options(): array
    {
        return Resident::query()
            ->where('is_aktif', true)
            ->orderBy('nama_lengkap')
            ->get(['id', 'nik', 'nama_lengkap'])
            ->mapWithKeys(fn ($resident) => [
                $resident->id => $resident->nik
                    . ' - '
                    . $resident->nama_lengkap,
            ])
            ->all();
    }
}
