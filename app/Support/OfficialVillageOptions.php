<?php

namespace App\Support;

use App\Models\VillageOfficial;

class OfficialVillageOptions
{
    public static function options(): array
    {
        return VillageOfficial::query()
            ->whereNull('auth_id')
            ->orderBy('nama')
            ->get(['id', 'niap', 'nama'])
            ->mapWithKeys(fn($official) => [
                $official->id => $official->niap
                    . ' - '
                    . $official->nama,
            ])
            ->all();
    }
}
