<?php

namespace App\Models;

use App\Contracts\Restorable;
use App\Models\Concerns\RestoresSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class VillagePosition extends Model implements Restorable
{
    /** @use HasFactory<\Database\Factories\VillagePositionFactory> */
    use HasFactory, SoftDeletes, RestoresSoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function villageOfficials(): HasMany
    {
        return $this->hasMany(VillageOfficial::class, 'village_position_id');
    }
}
