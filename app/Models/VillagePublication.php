<?php

namespace App\Models;

use App\Contracts\Restorable;
use App\Enums\RegulationStatusList;
use App\Models\Concerns\RestoresSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VillagePublication extends Model implements Restorable
{
    /** @use HasFactory<\Database\Factories\VillagePublicationFactory> */
    use HasFactory, SoftDeletes, RestoresSoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => RegulationStatusList::class,
            'tgl_diundang' => 'date',
            'tgl_berita' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function regulation(): BelongsTo
    {
        return $this->belongsTo(VillageRegulation::class, 'regulation_id');
    }
}
