<?php

namespace App\Models;

use App\Contracts\Restorable;
use App\Models\Concerns\RestoresSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VillageLand extends Model implements Restorable
{
    use HasFactory, SoftDeletes, RestoresSoftDeletes;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            // Jumlah Luas Tanah
            'jml' => 'decimal:2',

            // Status Hak Tanah
            'hm' => 'decimal:2',
            'hgb' => 'decimal:2',
            'hp' => 'decimal:2',
            'hgu' => 'decimal:2',
            'hpl' => 'decimal:2',
            'ma' => 'decimal:2',
            'vi' => 'decimal:2',
            'tn' => 'decimal:2',

            // Penggunaan Tanah - Non Pertanian
            'perumahan' => 'decimal:2',
            'dagang_jasa' => 'decimal:2',
            'perkantoran' => 'decimal:2',
            'industri' => 'decimal:2',
            'fasilitas_umum' => 'decimal:2',

            // Penggunaan Tanah - Pertanian
            'sawah' => 'decimal:2',
            'tegalan' => 'decimal:2',
            'perkebunan' => 'decimal:2',
            'peternakan_perikanan' => 'decimal:2',
            'hutan_belukar' => 'decimal:2',
            'hutan_lebat' => 'decimal:2',

            // Mutasi dan Luas Lainnya
            'mutasi_tanah' => 'decimal:2',
            'tanah_kosong' => 'decimal:2',
            'lainnya' => 'decimal:2',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
