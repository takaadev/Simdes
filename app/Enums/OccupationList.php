<?php

namespace App\Enums;

enum OccupationList: string
{
    case NOT_WORKING = 'Belum/Tidak Bekerja';
    case HOUSEWIFE = 'Mengurus Rumah Tangga';
    case STUDENT = 'Pelajar/Mahasiswa';
    case CIVIL_SERVANT = 'Pegawai Negeri Sipil (PNS)';
    case PPPK = 'Pegawai Pemerintah dengan Perjanjian Kerja (PPPK)';
    case TNI = 'TNI';
    case POLRI = 'Kepolisian RI';
    case PRIVATE_EMPLOYEE = 'Karyawan Swasta';
    case ENTREPRENEUR = 'Wiraswasta';
    case FARMER = 'Petani/Pekebun';
    case FARM_WORKER = 'Buruh Tani';
    case FISHERMAN = 'Nelayan';
    case BREEDER = 'Peternak';
    case TRADER = 'Pedagang';
    case LABORER = 'Buruh Harian Lepas';
    case CONSTRUCTION_WORKER = 'Tukang Bangunan';
    case DRIVER = 'Sopir';
    case TEACHER = 'Guru';
    case LECTURER = 'Dosen';
    case HEALTH_WORKER = 'Tenaga Kesehatan';
    case DOCTOR = 'Dokter';
    case MIDWIFE = 'Bidan';
    case NURSE = 'Perawat';
    case RELIGIOUS_FIGURE = 'Tokoh Agama';
    case VILLAGE_OFFICIAL = 'Perangkat Desa';
    case RETIRED = 'Pensiunan';
    case FREELANCER = 'Pekerja Lepas';
    case ARTIST = 'Seniman';
    case OTHER = 'Lainnya';

    public function label(): string
    {
        return $this->value;
    }

    public static function options(): array
    {
        return array_column(
            array_map(fn(self $item) => [
                'value' => $item->value,
                'label' => $item->label(),
            ], self::cases()),
            'label',
            'value'
        );
    }
}
