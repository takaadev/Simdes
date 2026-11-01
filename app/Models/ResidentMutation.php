<?php

namespace App\Models;

use App\Enums\MutationTypeList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResidentMutation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'mutation_date' => 'date',
            'mutation_type' => MutationTypeList::class,
        ];
    }

    // Relasi ke Penduduk
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    // Relasi ke Dusun Asal
    public function originDusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class, 'origin_dusun_id');
    }

    // Relasi ke Dusun Tujuan
    public function destinationDusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class, 'destination_dusun_id');
    }

    // Relasi ke Petugas Pencatat
    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
