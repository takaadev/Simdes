<?php

namespace App\Models;

use App\Enums\VillagePositionCategoryList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class VillagePosition extends Model
{
    /** @use HasFactory<\Database\Factories\VillagePositionFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'category' => VillagePositionCategoryList::class,
            'is_active' => 'boolean',
        ];
    }

    public function villageOfficials(): HasMany
    {
        return $this->hasMany(VillageOfficial::class, 'village_position_id');
    }
}
