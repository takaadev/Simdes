<?php

namespace App\Models;

use App\Enums\PeriodTypeList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Recapt extends Model
{
    /** @use HasFactory<\Database\Factories\RecaptFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'period_type' => PeriodTypeList::class,
            'period_start' => 'date',
            'period_end' => 'date',
            'snapshot_date' => 'date',
        ];
    }

    public function details(): HasMany

    {
        return $this->hasMany(RecaptDetail::class, 'recapt_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
