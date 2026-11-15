<?php

namespace App\Models;

use App\Enums\RelationshipList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyCardMutation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'hubungan' => RelationshipList::class,
            'tgl_mutasi' => 'date',
        ];
    }

    /**
     * Penduduk yang mengalami mutasi.
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * Kartu Keluarga asal.
     */
    public function familyCardAsal(): BelongsTo
    {
        return $this->belongsTo(FamilyCard::class, 'kk_asal');
    }

    /**
     * Kartu Keluarga tujuan.
     */
    public function familyCardTujuan(): BelongsTo
    {
        return $this->belongsTo(FamilyCard::class, 'kk_tujuan');
    }
}
