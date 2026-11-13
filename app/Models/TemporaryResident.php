<?php

namespace App\Models;

use App\Contracts\Restorable;
use App\Enums\CitizenshipList;
use App\Enums\GenderList;
use App\Enums\OccupationList;
use App\Models\Concerns\RestoresSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemporaryResident extends Model implements Restorable
{
    /** @use HasFactory<\Database\Factories\TemporaryResidentFactory> */
    use HasFactory, SoftDeletes, RestoresSoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'jenis_kelamin' => GenderList::class,
            'kewarganegaraan' => CitizenshipList::class,
            'pekerjaan' => OccupationList::class,
            'tgl_lahir' => 'date',
            'tgl_datang' => 'date',
            'tgl_pergi' => 'date',
            'umur' => 'integer'
        ];
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function getStayStatusAttribute(): string
    {
        return $this->tgl_pergi ? 'Sudah Pergi' : 'Masih Tinggal';
    }

}
