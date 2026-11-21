<?php

namespace Database\Seeders;
use App\Enums\CitizenshipList;
use App\Enums\EducationList;
use App\Enums\GenderList;
use App\Enums\MaritalStatusList;
use App\Enums\OccupationList;
use App\Enums\ReligionList;
use App\Models\FamilyCard;
use App\Models\Resident;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResidentSeeder extends Seeder
{
    public function run(): void
    {
        $families = [
            [
                'kk' => '1502011501200001',
                'members' => [
                    [
                        'nama_lengkap' => 'Budi Santoso',
                        'nik' => '1502010101800001',
                        'jenis_kelamin' => 'L',
                        'status_dalam_keluarga' => 'Kepala Keluarga',
                        'status_perkawinan' => MaritalStatusList::MARRIED->value,
                        'tgl_lahir' => '1980-01-01',
                        'tempat_lahir' => 'Merangin',
                        'pekerjaan' => 'Petani',
                    ],
                    [
                        'nama_lengkap' => 'Siti Aminah',
                        'nik' => '1502014505850002',
                        'jenis_kelamin' => 'P',
                        'status_dalam_keluarga' => 'Istri',
                        'status_perkawinan' => MaritalStatusList::MARRIED->value,
                        'tgl_lahir' => '1985-05-05',
                        'tempat_lahir' => 'Merangin',
                        'pekerjaan' => 'Ibu Rumah Tangga',
                    ],
                    [
                        'nama_lengkap' => 'Andi Saputra',
                        'nik' => '1502011208100003',
                        'jenis_kelamin' => 'L',
                        'status_dalam_keluarga' => 'Anak',
                        'status_perkawinan' => MaritalStatusList::UNMARRIED->value,
                        'tgl_lahir' => '2010-08-12',
                        'tempat_lahir' => 'Merangin',
                        'pekerjaan' => 'Pelajar',
                    ],
                ],
            ],
            [
                'kk' => '1502012002200002',
                'members' => [
                    [
                        'nama_lengkap' => 'Ahmad Fauzi',
                        'nik' => '1502010201750004',
                        'jenis_kelamin' => 'L',
                        'status_dalam_keluarga' => 'Kepala Keluarga',
                        'status_perkawinan' => MaritalStatusList::MARRIED->value,
                        'tgl_lahir' => '1975-01-02',
                        'tempat_lahir' => 'Merangin',
                        'pekerjaan' => 'Petani',
                    ],
                    [
                        'nama_lengkap' => 'Nurhayati',
                        'nik' => '1502014207800005',
                        'jenis_kelamin' => 'P',
                        'status_dalam_keluarga' => 'Istri',
                        'status_perkawinan' => MaritalStatusList::MARRIED->value,
                        'tgl_lahir' => '1980-07-02',
                        'tempat_lahir' => 'Merangin',
                        'pekerjaan' => 'Ibu Rumah Tangga',
                    ],
                    [
                        'nama_lengkap' => 'Rina Fauziah',
                        'nik' => '1502016509050006',
                        'jenis_kelamin' => 'P',
                        'status_dalam_keluarga' => 'Anak',
                        'status_perkawinan' => MaritalStatusList::UNMARRIED->value,
                        'tgl_lahir' => '2005-09-25',
                        'tempat_lahir' => 'Merangin',
                        'pekerjaan' => 'Pelajar',
                    ],
                ],
            ],
            [
                'kk' => '1502012503200003',
                'members' => [
                    [
                        'nama_lengkap' => 'Dedi Kurniawan',
                        'nik' => '1502011503820007',
                        'jenis_kelamin' => 'L',
                        'status_dalam_keluarga' => 'Kepala Keluarga',
                        'status_perkawinan' => MaritalStatusList::MARRIED->value,
                        'tgl_lahir' => '1982-03-15',
                        'tempat_lahir' => 'Merangin',
                        'pekerjaan' => 'Wiraswasta',
                    ],
                    [
                        'nama_lengkap' => 'Lina Marlina',
                        'nik' => '1502015508860008',
                        'jenis_kelamin' => 'P',
                        'status_dalam_keluarga' => 'Istri',
                        'status_perkawinan' => MaritalStatusList::MARRIED->value,
                        'tgl_lahir' => '1986-08-15',
                        'tempat_lahir' => 'Merangin',
                        'pekerjaan' => 'Ibu Rumah Tangga',
                    ],
                    [
                        'nama_lengkap' => 'Rizky Kurniawan',
                        'nik' => '1502011001120009',
                        'jenis_kelamin' => 'L',
                        'status_dalam_keluarga' => 'Anak',
                        'status_perkawinan' => MaritalStatusList::UNMARRIED->value,
                        'tgl_lahir' => '2012-01-10',
                        'tempat_lahir' => 'Merangin',
                        'pekerjaan' => 'Pelajar',
                    ],
                ],
            ],
            [
                'kk' => '1502013004200004',
                'members' => [
                    [
                        'nama_lengkap' => 'Hendra Gunawan',
                        'nik' => '1502011004800010',
                        'jenis_kelamin' => 'L',
                        'status_dalam_keluarga' => 'Kepala Keluarga',
                        'status_perkawinan' => MaritalStatusList::MARRIED->value,
                        'tgl_lahir' => '1980-04-10',
                        'tempat_lahir' => 'Merangin',
                        'pekerjaan' => 'Pedagang',
                    ],
                    [
                        'nama_lengkap' => 'Dewi Anggraini',
                        'nik' => '1502014808830011',
                        'jenis_kelamin' => 'P',
                        'status_dalam_keluarga' => 'Istri',
                        'status_perkawinan' => MaritalStatusList::MARRIED->value,
                        'tgl_lahir' => '1983-08-08',
                        'tempat_lahir' => 'Merangin',
                        'pekerjaan' => 'Ibu Rumah Tangga',
                    ],
                    [
                        'nama_lengkap' => 'Fajar Hendra',
                        'nik' => '1502011207100012',
                        'jenis_kelamin' => 'L',
                        'status_dalam_keluarga' => 'Anak',
                        'status_perkawinan' => MaritalStatusList::UNMARRIED->value,
                        'tgl_lahir' => '2010-07-12',
                        'tempat_lahir' => 'Merangin',
                        'pekerjaan' => 'Pelajar',
                    ],
                ],
            ],
            [
                'kk' => '1502010505200005',
                'members' => [
                    [
                        'nama_lengkap' => 'Eko Prasetyo',
                        'nik' => '1502010505750013',
                        'jenis_kelamin' => 'L',
                        'status_dalam_keluarga' => 'Kepala Keluarga',
                        'status_perkawinan' => MaritalStatusList::MARRIED->value,
                        'tgl_lahir' => '1975-05-05',
                        'tempat_lahir' => 'Merangin',
                        'pekerjaan' => 'Petani',
                    ],
                    [
                        'nama_lengkap' => 'Sri Wahyuni',
                        'nik' => '1502014808800014',
                        'jenis_kelamin' => 'P',
                        'status_dalam_keluarga' => 'Istri',
                        'status_perkawinan' => MaritalStatusList::MARRIED->value,
                        'tgl_lahir' => '1980-08-08',
                        'tempat_lahir' => 'Merangin',
                        'pekerjaan' => 'Ibu Rumah Tangga',
                    ],
                    [
                        'nama_lengkap' => 'Putri Prasetyo',
                        'nik' => '1502015509060015',
                        'jenis_kelamin' => 'P',
                        'status_dalam_keluarga' => 'Anak',
                        'status_perkawinan' => MaritalStatusList::UNMARRIED->value,
                        'tgl_lahir' => '2006-09-15',
                        'tempat_lahir' => 'Merangin',
                        'pekerjaan' => 'Pelajar',
                    ],
                ],
            ],
            [
                'kk' => '1502011006200006',
                'members' => [
                    [
                        'nama_lengkap' => 'Agus Setiawan',
                        'nik' => '1502011201780016',
                        'jenis_kelamin' => 'L',
                        'status_dalam_keluarga' => 'Kepala Keluarga',
                        'status_perkawinan' => MaritalStatusList::MARRIED->value,
                        'tgl_lahir' => '1978-01-12',
                        'tempat_lahir' => 'Merangin',
                        'pekerjaan' => 'PNS',
                    ],
                    [
                        'nama_lengkap' => 'Fitri Handayani',
                        'nik' => '1502015207820017',
                        'jenis_kelamin' => 'P',
                        'status_dalam_keluarga' => 'Istri',
                        'status_perkawinan' => MaritalStatusList::MARRIED->value,
                        'tgl_lahir' => '1982-07-12',
                        'tempat_lahir' => 'Merangin',
                        'pekerjaan' => 'Ibu Rumah Tangga',
                    ],
                    [
                        'nama_lengkap' => 'Dinda Setiawan',
                        'nik' => '1502016509080018',
                        'jenis_kelamin' => 'P',
                        'status_dalam_keluarga' => 'Anak',
                        'status_perkawinan' => MaritalStatusList::UNMARRIED->value,
                        'tgl_lahir' => '2008-09-25',
                        'tempat_lahir' => 'Merangin',
                        'pekerjaan' => 'Pelajar',
                    ],
                ],
            ],
        ];

        DB::transaction(function () use ($families) {
            foreach ($families as $family) {
                $familyCard = FamilyCard::withTrashed()->firstOrCreate([
                    'nomor_kk' => $family['kk'],
                ]);

                if ($familyCard->trashed()) {
                    $familyCard->restore();
                }

                foreach ($family['members'] as $member) {
                    $gender = $member['jenis_kelamin'] === 'L'
                        ? GenderList::cases()[0]->value
                        : GenderList::cases()[1]->value;

                    $resident = Resident::withTrashed()->updateOrCreate(
                        [
                            'nik' => $member['nik'],
                        ],
                        [
                            'family_card_id' => $familyCard->id,
                            'nama_lengkap' => $member['nama_lengkap'],
                            'jenis_kelamin' => $gender,
                            'kk' => $family['kk'],
                            'tempat_lahir' => $member['tempat_lahir'],
                            'tgl_lahir' => $member['tgl_lahir'],
                            'status_perkawinan' => $member['status_perkawinan'],
                            'agama' => ReligionList::cases()[0]->value,
                            'pendidikan' => EducationList::cases()[0]->value,
                            'pekerjaan' => OccupationList::cases()[0]->value,
                            'dapat_membaca' => true,
                            'kewarganegaraan' => CitizenshipList::cases()[0]->value,
                            'status_dalam_keluarga' => $member['status_dalam_keluarga'],
                            'kebangsaan' => 'Indonesia',
                            'gol_darah' => null,
                            'ayah' => null,
                            'ibu' => null,
                            'rt' => '001',
                            'rw' => '001',
                            'alamat' => 'Desa Tiaro',
                            'tempat_dikeluarkan' => null,
                            'tgl_dikeluarkan' => null,
                            'tgl_mulai_tinggal' => null,
                            'catatan' => null,
                        ]
                    );

                    if ($resident->trashed()) {
                        $resident->restore();
                    }
                }
            }
        });
    }
}
