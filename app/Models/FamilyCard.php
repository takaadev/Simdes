<?php

namespace App\Models;

use App\Contracts\Restorable;
use App\Models\Concerns\RestoresSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamilyCard extends Model implements Restorable
{
    /** @use HasFactory<\Database\Factories\FamilyCardFactory> */
    use HasFactory, SoftDeletes, RestoresSoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [];
    }

    /**
     * Relasi ke seluruh penduduk dalam kartu keluarga.
     */
    public function residents(): HasMany
    {
        return $this->hasMany(Resident::class, 'family_card_id');
    }

    /**
     * Riwayat mutasi penduduk yang keluar dari KK.
     */
    public function mutationsAsal(): HasMany
    {
        return $this->hasMany(FamilyCardMutation::class, 'kk_asal');
    }

    /**
     * Riwayat mutasi penduduk yang masuk ke KK.
     */
    public function mutationsTujuan(): HasMany
    {
        return $this->hasMany(FamilyCardMutation::class, 'kk_tujuan');
    }
}
