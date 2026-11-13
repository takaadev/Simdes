<?php

namespace App\Models;

use App\Contracts\Restorable;
use App\Enums\BloodList;
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
use Illuminate\Database\Eloquent\Relations\HasOne;
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
            'tgl_dikeluarkan' => 'date',
            'jenis_kelamin' => GenderList::class,
            'status_perkawinan' => MaritalStatusList::class,
            'agama' => ReligionList::class,
            'pendidikan' => EducationList::class,
            'pekerjaan' => OccupationList::class,
            'dapat_membaca' => 'boolean',
            'kewarganegaraan' => CitizenshipList::class,
            'gol_darah' => BloodList::class,
            'tgl_mulai_tinggal' => 'date'
        ];
    }

    /**
     * Relasi ke kartu keluarga.
     */
    public function familyCard(): BelongsTo
    {
        return $this->belongsTo(FamilyCard::class, 'family_card_id');
    }

    /**
     * Relasi ke mutasi penduduk.
     */
    public function mutation(): HasOne
    {
        return $this->hasOne(ResidentMutation::class);
    }
}
