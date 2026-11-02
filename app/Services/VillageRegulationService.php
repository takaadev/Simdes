<?php

namespace App\Services;

use App\Models\VillageRegulation;
use Illuminate\Database\Eloquent\Builder;

class VillageRegulationService
{
   public function getData(): Builder
    {
        return VillageRegulation::query()
            ->select([
                'id',
                'register_number',
                'regulation_number',
                'regulation_type',
                'regulation_title',
                'stipulation_date',
                'promulgation_date',
                'subject',
                'status',
                'notes',
            ])
            ->latest('stipulation_date');
    }
    public function getStore(){}
    public function getUpdate(){}
    public function getDestroy(){}
}
