<?php

namespace App\Models;

use App\Enums\RegulationTypeList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VillagePublication extends Model
{
    /** @use HasFactory<\Database\Factories\VillagePublicationFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'regulation_type' => RegulationTypeList::class,
            'stipulation_date' => 'date',
            'promulgation_date' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
