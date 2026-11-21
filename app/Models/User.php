<?php

namespace App\Models;

use App\Contracts\Restorable;
use App\Enums\RoleAccessType;
use App\Models\Concerns\RestoresSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements Restorable
{
    use HasFactory, Notifiable, SoftDeletes, RestoresSoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => RoleAccessType::class,
            'last_login_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Village Official
    |--------------------------------------------------------------------------
    */

    public function villageOfficial(): HasOne
    {
        return $this->hasOne(VillageOfficial::class, 'auth_id', 'id');
    }

    public function createdVillageOfficials(): HasMany
    {
        return $this->hasMany(VillageOfficial::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Population
    |--------------------------------------------------------------------------
    */

    public function recordedResidentMutations(): HasMany
    {
        return $this->hasMany(ResidentMutation::class, 'recorded_by');
    }

    public function recordedTemporaryResidents(): HasMany
    {
        return $this->hasMany(TemporaryResident::class, 'recorded_by');
    }

    public function recapts(): HasMany
    {
        return $this->hasMany(Recapt::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Village Administration
    |--------------------------------------------------------------------------
    */

    public function villageRegulations(): HasMany
    {
        return $this->hasMany(VillageRegulation::class, 'created_by');
    }

    public function villageDecrees(): HasMany
    {
        return $this->hasMany(VillageDecree::class, 'created_by');
    }

    public function villagePublications(): HasMany
    {
        return $this->hasMany(VillagePublication::class, 'created_by');
    }

    public function villageAssets(): HasMany
    {
        return $this->hasMany(VillageAsset::class, 'created_by');
    }

    public function villageLands(): HasMany
    {
        return $this->hasMany(VillageLand::class, 'created_by');
    }

    public function villageTreasuryLands(): HasMany
    {
        return $this->hasMany(VillageTreasuryLand::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Correspondence
    |--------------------------------------------------------------------------
    */

    public function agendaEntries(): HasMany
    {
        return $this->hasMany(AgendaEntry::class, 'created_by');
    }

    public function expeditionEntries(): HasMany
    {
        return $this->hasMany(ExpeditionEntry::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Application Settings
    |--------------------------------------------------------------------------
    */

    public function appSettings(): HasMany
    {
        return $this->hasMany(AppSetting::class, 'updated_by');
    }
}
