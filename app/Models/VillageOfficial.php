<?php

namespace App\Models;

use App\Contracts\Restorable;
use App\Enums\EducationList;
use App\Enums\GenderList;
use App\Enums\VillageOfficialStatusList;
use App\Models\Concerns\RestoresSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VillageOfficial extends Model implements Restorable
{
    /** @use HasFactory<\Database\Factories\VillageOfficialFactory> */
    use HasFactory, SoftDeletes, RestoresSoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'jenis_kelamin' => GenderList::class,
            'status' => VillageOfficialStatusList::class,
            'pendidikan' => EducationList::class,
            'tgl_lahir' => 'date',
            'tgl_pengangkatan' => 'date',
            'tgl_pemberhentian' => 'date',
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
}
