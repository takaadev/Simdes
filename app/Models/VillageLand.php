<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VillageLand extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'total_area' => 'decimal:2',

            'hm' => 'decimal:2',
            'hgb' => 'decimal:2',
            'hp' => 'decimal:2',
            'hgu' => 'decimal:2',
            'hpl' => 'decimal:2',
            'ma' => 'decimal:2',
            'vi' => 'decimal:2',
            'tn' => 'decimal:2',

            'housing' => 'decimal:2',
            'trade_services' => 'decimal:2',
            'office' => 'decimal:2',
            'industry' => 'decimal:2',
            'public_facilities' => 'decimal:2',

            'paddy_field' => 'decimal:2',
            'dry_field' => 'decimal:2',
            'plantation' => 'decimal:2',
            'livestock_fishery' => 'decimal:2',
            'shrub_forest' => 'decimal:2',
            'protected_forest' => 'decimal:2',

            'land_mutation' => 'decimal:2',
            'vacant_land' => 'decimal:2',
            'other_area' => 'decimal:2',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
