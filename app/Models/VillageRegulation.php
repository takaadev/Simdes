<?php

namespace App\Models;

use App\Contracts\Restorable;
use App\Enums\RegulationStatusList;
use App\Enums\RegulationTypeList;
use App\Models\Concerns\RestoresSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class VillageRegulation extends Model implements Restorable
{
    /** @use HasFactory<\Database\Factories\VillageRegulationFactory> */
    use HasFactory, SoftDeletes, RestoresSoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'jenis_peraturan' => RegulationTypeList::class,
            'status' => RegulationStatusList::class,
            'tgl_ditetapkan' => 'date',
            'tgl_kesepakatan' => 'date',
            'tgl_dilaporkan' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function publication(): HasOne
    {
        return $this->hasOne(VillagePublication::class, 'regulation_id');
    }
}
