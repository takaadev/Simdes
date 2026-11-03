<?php

namespace App\Models;

use App\Enums\RegulationStatusList;
use App\Enums\RegulationTypeList;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VillageRegulation extends Model
{
    /** @use HasFactory<\Database\Factories\VillageRegulationFactory> */
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            // Enum
            'jenis_peraturan' => RegulationTypeList::class,
            'status' => RegulationStatusList::class,

            // Tanggal
            'tgl_ditetapkan' => 'date',
            'tgl_kesepakatan' => 'date',
            'tgl_dilaporkan' => 'date',
            'tgl_diundang' => 'date',
            'tgl_berita' => 'date',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
