<?php

namespace App\Models;

use App\Enums\LandAcquisitionList;
use App\Enums\LandTypeList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VillageTreasuryLand extends Model
{
    /** @use HasFactory<\Database\Factories\VillageTreasuryLandFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'acquisition' => LandAcquisitionList::class,
            'land_type' => LandTypeList::class,
            'land_area' => 'decimal:2',
            'acquisition_date' => 'date',
            'has_marker' => 'boolean',
            'has_plate' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
