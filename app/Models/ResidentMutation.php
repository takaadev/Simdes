<?php

namespace App\Models;

use App\Contracts\Restorable;
use App\Enums\CitizenshipList;
use App\Enums\GenderList;
use App\Enums\MutationCategoryList;
use App\Enums\MutationTypeList;
use App\Models\Concerns\RestoresSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResidentMutation extends Model implements Restorable
{
    use HasFactory, SoftDeletes, RestoresSoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'tgl_lahir' => 'date',
            'tgl_mutasi' => 'date',
            'jenis_kelamin' => GenderList::class,
            'kewarganegaraan' => CitizenshipList::class,
            'tipe_mutasi' => MutationTypeList::class,
            'kategori_mutasi' => MutationCategoryList::class,
        ];
    }

    /**
     * Relasi ke penduduk.
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * Relasi ke petugas pencatat.
     */
    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
