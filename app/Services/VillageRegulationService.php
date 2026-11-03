<?php

namespace App\Services;

use App\Models\VillageRegulation;
use Illuminate\Database\Eloquent\Builder;

class VillageRegulationService
{
    public function getData(): Builder
    {
        return VillageRegulation::query()
            ->select([
                'id',
                // 1. Jenis Peraturan
                'jenis_peraturan',
                // 2. Nomor dan Tanggal Ditetapkan
                'nomor_peraturan',
                'tgl_ditetapkan',
                // 3. Nama Peraturan
                'nama_peraturan',
                // 4. Uraian
                'uraian',
                // 5. Kesepakatan
                'tgl_kesepakatan',
                // 6. Pelaporan
                'nomor_dilaporkan',
                'tgl_dilaporkan',
                // 7. Pengundangan
                'nomor_diundang',
                'tgl_diundang',
                // 8. Berita Desa
                'nomor_berita',
                'tgl_berita',
                // 9. Keterangan
                'catatan',
                // 10. Dokumen
                'dokumen',
                // 11. Status
                'status',
                // 12. Kebutuhan Sistem
                'created_by',
                'created_at',
                'updated_at',
            ])
            ->latest('tgl_ditetapkan');
    }
    public function getStore() {}
    public function getUpdate() {}
    public function getDestroy() {}
}
