<?php

namespace App\Models;

use App\Contracts\Restorable;
use App\Enums\LandClassList;
use App\Enums\RegionLandList;
use App\Models\Concerns\RestoresSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VillageTreasuryLand extends Model implements Restorable
{
    /** @use HasFactory<\Database\Factories\VillageTreasuryLandFactory> */
    use HasFactory, SoftDeletes, RestoresSoftDeletes;

    protected $guarded = ['id'];


    protected function casts(): array
    {
        return [
            'asal_tanah' => RegionLandList::class,
            'kelas_tanah' => LandClassList::class,
            'luas_tanah' => 'decimal:2',
            'lt_asli_desa' => 'decimal:2',
            'lt_pemerintah' => 'decimal:2',
            'lt_provinsi' => 'decimal:2',
            'lt_kabupaten' => 'decimal:2',
            'lt_lainnya' => 'decimal:2',
            'tgl_perolehan' => 'date',
            'lt_tipe_sawah' => 'decimal:2',
            'lt_tipe_tegal' => 'decimal:2',
            'lt_tipe_kebun' => 'decimal:2',
            'lt_tipe_tambak' => 'decimal:2',
            'lt_tipe_kering' => 'decimal:2',
            'patok_tanah' => 'boolean',
            'papan_nama' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
