<?php

namespace App\Models;

use App\Enums\GenderList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemporaryResident extends Model
{
    /** @use HasFactory<\Database\Factories\TemporaryResidentFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'gender' => GenderList::class,
            'birth_date' => 'date',
            'arrival_date' => 'date',
            'departure_date' => 'date',
            'age' => 'integer',
        ];
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function getStayStatusAttribute(): string
    {
        return $this->departure_date ? 'Sudah Pergi' : 'Masih Tinggal';
    }
}
