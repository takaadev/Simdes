<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dusun extends Model
{
    /** @use HasFactory<\Database\Factories\DusunFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function familyCards(): HasMany
    {
        return $this->hasMany(FamilyCard::class);
    }

    // Mutasi penduduk dari dusun ini
    public function outgoingMutations(): HasMany
    {
        return $this->hasMany(ResidentMutation::class, 'origin_dusun_id');
    }

    // Mutasi penduduk menuju dusun ini
    public function incomingMutations(): HasMany
    {
        return $this->hasMany(ResidentMutation::class, 'destination_dusun_id');
    }

    public function populationRecapitulationDetails(): HasMany
    {
        return $this->hasMany(PopulationRecapitulationDetail::class);
    }
}
