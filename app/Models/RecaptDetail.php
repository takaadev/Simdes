<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecaptDetail extends Model
{
    /** @use HasFactory<\Database\Factories\RecaptDetailFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'total_population' => 'integer',
            'male_count' => 'integer',
            'female_count' => 'integer',
            'family_count' => 'integer',
            'birth_count' => 'integer',
            'death_count' => 'integer',
            'incoming_count' => 'integer',
            'outgoing_count' => 'integer',
        ];
    }

    public function recapt(): BelongsTo
    {
        return $this->belongsTo(Recapt::class, 'recapt_id');
    }

    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class);
    }
}
