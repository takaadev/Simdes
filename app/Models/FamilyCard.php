<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FamilyCard extends Model
{
    /** @use HasFactory<\Database\Factories\FamilyCardFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'issued_date' => 'date',
        ];
    }

    public function headOfFamily(): BelongsTo
    {
        return $this->belongsTo(Resident::class, 'head_of_family_id');
    }

    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(FamilyCardMember::class);
    }

    public function activeMembers(): HasMany
    {
        return $this->hasMany(FamilyCardMember::class)
            ->whereNull('left_at');
    }
}
