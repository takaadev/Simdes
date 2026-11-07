<?php

namespace App\Models;

use App\Contracts\Restorable;
use App\Enums\DecreeStatusList;
use App\Models\Concerns\RestoresSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VillageDecree extends Model implements Restorable
{
    /** @use HasFactory<\Database\Factories\VillageDecreeFactory> */
    use HasFactory, SoftDeletes, RestoresSoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'tgl_keputusan' => 'date',
            'tgl_dilaporkan' => 'date',
            'status' => DecreeStatusList::class,
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
