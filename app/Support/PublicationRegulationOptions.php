<?php

namespace App\Support;

use App\Enums\RegulationStatusList;
use App\Models\VillageRegulation;

class PublicationRegulationOptions
{
    public static function options(): array
    {
        return VillageRegulation::query()
            ->where('status', RegulationStatusList::DRAFT->value)
            ->whereDoesntHave('publication', function ($query) {
                $query->withTrashed();
            })
            ->orderBy('nomor_peraturan')
            ->get(['id', 'nomor_peraturan', 'nama_peraturan'])
            ->mapWithKeys(fn($regulation) => [
                $regulation->id => $regulation->nomor_peraturan
                    . ' - '
                    . $regulation->nama_peraturan,
            ])
            ->all();
    }
}
