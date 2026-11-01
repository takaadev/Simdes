<?php

namespace App\Models;

use App\Enums\BloodList;
use App\Enums\CitizenshipList;
use App\Enums\EducationList;
use App\Enums\GenderList;
use App\Enums\MaritalStatusList;
use App\Enums\OccupationList;
use App\Enums\ReligionList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resident extends Model
{
    /** @use HasFactory<\Database\Factories\ResidentFactory> */
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'moved_in_date' => 'date',
            'moved_out_date' => 'date',
            'blood_type' => BloodList::class,
            'religion' => ReligionList::class,
            'education' => EducationList::class,
            'occupation' => OccupationList::class,
            'marital_status' => MaritalStatusList::class,
            'citizenship' => CitizenshipList::class,
            'gender' => GenderList::class,

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
