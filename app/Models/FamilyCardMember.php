<?php

namespace App\Models;

use App\Enums\RelationshipList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class FamilyCardMember extends Model
{
    /** @use HasFactory<\Database\Factories\FamilyCardMemberFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_head' => 'boolean',
            'joined_at' => 'date',
            'left_at' => 'date',
            'relationship' => RelationshipList::class,
        ];
    }

    // Relasi ke Kartu Keluarga
    public function familyCard(): BelongsTo
    {
        return $this->belongsTo(FamilyCard::class);
    }

    // Relasi ke Penduduk
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }
}
