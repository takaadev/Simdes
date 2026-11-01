<?php

namespace App\Models;

use App\Enums\AssetConditionList;
use App\Enums\AssetOwnershipStatusList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VillageAsset extends Model
{
    /** @use HasFactory<\Database\Factories\VillageAssetFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'acquisition_date' => 'date',
            'acquisition_value' => 'decimal:2',
            'quantity' => 'decimal:2',
            'condition' => AssetConditionList::class,
            'ownership_status' => AssetOwnershipStatusList::class,
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
