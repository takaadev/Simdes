<?php

namespace App\Models;

use App\Contracts\Restorable;
use App\Models\Concerns\RestoresSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $nama_asset
 * @property int $asal_beli
 * @property int $asal_pemerintah
 * @property int $asal_provinsi
 * @property int $asal_kabupaten
 * @property int $asal_sumbangan
 * @property int $kondisi_awal_baik
 * @property int $kondisi_awal_rusak
 * @property int $hapus_rusak
 * @property int $hapus_dijual
 * @property int $hapus_disumbangkan
 * @property \Illuminate\Support\Carbon|null $tgl_hapus
 * @property int $kondisi_ahir_baik
 * @property int $kondisi_ahir_rusak
 * @property string|null $catatan
 * @property string|null $dokumen
 */
class VillageAsset extends Model implements Restorable
{
    /** @use HasFactory<\Database\Factories\VillageAssetFactory> */
    use HasFactory, SoftDeletes, RestoresSoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'tgl_hapus' => 'date',

            'asal_beli' => 'integer',
            'asal_pemerintah' => 'integer',
            'asal_provinsi' => 'integer',
            'asal_kabupaten' => 'integer',
            'asal_sumbangan' => 'integer',

            'kondisi_awal_baik' => 'integer',
            'kondisi_awal_rusak' => 'integer',

            'hapus_rusak' => 'integer',
            'hapus_dijual' => 'integer',
            'hapus_disumbangkan' => 'integer',

            'kondisi_ahir_baik' => 'integer',
            'kondisi_ahir_rusak' => 'integer',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
