<?php

namespace App\Models;

use App\Contracts\Restorable;
use App\Enums\CitizenshipList;
use App\Enums\EducationList;
use App\Enums\GenderList;
use App\Enums\MaritalStatusList;
use App\Enums\OccupationList;
use App\Enums\ReligionList;
use App\Models\Concerns\RestoresSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resident extends Model implements Restorable
{
    /** @use HasFactory<\Database\Factories\ResidentFactory> */
    use HasFactory, SoftDeletes, RestoresSoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'tgl_lahir' => 'date',
            'jenis_kelamin' => GenderList::class,
            'status_perkawinan' => MaritalStatusList::class,
            'agama' => ReligionList::class,
            'pendidikan' => EducationList::class,
            'pekerjaan' => OccupationList::class,
            'dapat_membaca' => 'boolean',
            'kewarganegaraan' => CitizenshipList::class,
        ];
    }

    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class);
    }

    public function familyCardsAsHead(): HasMany
    {
        return $this->hasMany(FamilyCard::class, 'head_of_family_id');
    }

    public function familyCardMemberships(): HasMany
    {
        return $this->hasMany(FamilyCardMember::class);
    }

    public function mutations(): HasMany
    {
        return $this->hasMany(ResidentMutation::class);
    }

    public function activeFamilyCardMemberships(): HasMany
    {
        return $this->hasMany(FamilyCardMember::class)
            ->whereNull('left_at');
    }
}
