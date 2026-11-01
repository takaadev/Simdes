<?php

namespace App\Models;

use App\Enums\EducationList;
use App\Enums\GenderList;
use App\Enums\VillageOfficialStatusList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VillageOfficial extends Model
{
    /** @use HasFactory<\Database\Factories\VillageOfficialFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'gender' => GenderList::class,
            'status' => VillageOfficialStatusList::class,
            'education' => EducationList::class,
            'birth_date' => 'date',
            'appointment_date' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(VillagePosition::class, 'village_position_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function authUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auth');
    }
}
