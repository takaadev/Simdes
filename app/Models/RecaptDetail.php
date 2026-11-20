<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecaptDetail extends Model
{
    /** @use HasFactory<\Database\Factories\RecaptDetailFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            // Awal Bulan
            'awb_wni_l' => 'integer',
            'awb_wni_p' => 'integer',
            'awb_wna_l' => 'integer',
            'awb_wna_p' => 'integer',
            'awb_jml_kk' => 'integer',
            'awb_jml_anggota_kk' => 'integer',
            'awb_jml_penduduk' => 'integer',

            // Penambahan Bulan Ini (Lahir & Datang)
            'tbl_wni_l' => 'integer',
            'tbl_wni_p' => 'integer',
            'tbl_wna_l' => 'integer',
            'tbl_wna_p' => 'integer',
            'tbd_wni_l' => 'integer',
            'tbd_wni_p' => 'integer',
            'tbd_wna_l' => 'integer',
            'tbd_wna_p' => 'integer',

            // Pengurangan Bulan Ini (Meninggal & Pindah)
            'pbm_wni_l' => 'integer',
            'pbm_wni_p' => 'integer',
            'pbm_wna_l' => 'integer',
            'pbm_wna_p' => 'integer',
            'pbp_wni_l' => 'integer',
            'pbp_wni_p' => 'integer',
            'pbp_wna_l' => 'integer',
            'pbp_wna_p' => 'integer',

            // Akhir Bulan
            'ahb_wni_l' => 'integer',
            'ahb_wni_p' => 'integer',
            'ahb_wna_l' => 'integer',
            'ahb_wna_p' => 'integer',
            'ahb_jml_kk' => 'integer',
            'ahb_jml_anggota_kk' => 'integer',
            'ahb_jml_penduduk' => 'integer',
        ];
    }

    public function recapt(): BelongsTo
    {
        return $this->belongsTo(Recapt::class, 'recapt_id');
    }
}
