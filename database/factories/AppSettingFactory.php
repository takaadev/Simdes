<?php

namespace Database\Factories;

use App\Models\AppSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AppSetting>
 */
class AppSettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'logo' => null,
            'time_zone' => 'Asia/Jakarta',
            'kode_wilayah' => '15.02.03.2013',
            'nama_desa' => 'Tiaro',
            'kecamatan' => 'Muara Siau',
            'kabupaten' => 'Merangin',
            'provinsi' => 'Jambi',
            'kode_pos' => '37371',
            'alamat_kantor' => 'Desa Tiaro, Kecamatan Muara Siau, Kabupaten Merangin, Provinsi Jambi 37371',
            'telepon' => '082217134017',
            'email' => 'desatiaro@gmail.com',
            'nama_kades' => 'BASARUDIN',
            'nama_sekdes' => 'MUHHAMAD SATAR',
            'total_rw' => 1,
            'total_rt' => 4,
            'jam_operasional' =>'Sen–Kam 08.00–15.30 WITA' ,
            'deskripsi' => 'Sistem Informasi Manajemen Desa Tiaro untuk mendukung administrasi dan pelayanan pemerintahan desa.',
            'updated_by' => 1,
        ];
    }
}
