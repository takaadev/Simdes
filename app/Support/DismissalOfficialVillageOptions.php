<?php

namespace App\Support;

use App\Enums\VillageOfficialStatusList;
use App\Models\VillageOfficial;

class DismissalOfficialVillageOptions
{
    public static function options(): array
    {
        return VillageOfficial::query()
            ->orderBy('nama')
            ->where('status', VillageOfficialStatusList::ACTIVE)
            ->get(['id', 'niap', 'nama'])
            ->mapWithKeys(fn($official) => [
                $official->id => $official->niap
                    . ' - '
                    . $official->nama,
            ])
            ->all();
    }
}
