<?php

namespace App\Http\Requests\ResidentMutation;

use App\Enums\BloodList;
use App\Enums\CitizenshipList;
use App\Enums\EducationList;
use App\Enums\GenderList;
use App\Enums\MaritalStatusList;
use App\Enums\MutationCategoryList;
use App\Enums\OccupationList;
use App\Enums\ReligionList;
use App\Enums\RelationshipList;
use App\Enums\RtList;
use App\Enums\RwList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreResidentMutationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nomor_identitas' => [
                'required',
                'string',
                'max:30',
                Rule::unique('residents', 'nik'),
            ],

            'nama_lengkap' => [
                'required',
                'string',
                'max:150',
            ],

            'jenis_kelamin' => [
                'required',
                Rule::enum(GenderList::class),
            ],

            'tempat_lahir' => [
                'required',
                'string',
                'max:100',
            ],

            'tgl_lahir' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'status_perkawinan' => [
                'required',
                Rule::enum(MaritalStatusList::class),
            ],

            'agama' => [
                'required',
                Rule::enum(ReligionList::class),
            ],

            'pendidikan' => [
                'required',
                Rule::enum(EducationList::class),
            ],

            'pekerjaan' => [
                'required',
                Rule::enum(OccupationList::class),
            ],

            'status_dalam_keluarga' => [
                'required',
                Rule::enum(RelationshipList::class),
            ],

            'dapat_membaca' => [
                'required',
                'boolean',
            ],

            'gol_darah' => [
                'nullable',
                Rule::enum(BloodList::class),
            ],

            'kewarganegaraan' => [
                'required',
                Rule::enum(CitizenshipList::class),
            ],

            'kebangsaan' => [
                'required',
                'string',
                'max:100',
            ],

            'ayah' => [
                'nullable',
                'string',
                'max:150',
            ],

            'ibu' => [
                'nullable',
                'string',
                'max:150',
            ],

            'tempat_dikeluarkan' => [
                'nullable',
                'string',
                'max:100',
            ],

            'tgl_dikeluarkan' => [
                'nullable',
                'date',
            ],

            'tgl_mulai_tinggal' => [
                'nullable',
                'date',
            ],

            'kk' => [
                'nullable',
                'string',
                'digits:16',
            ],

            'rt' => [
                'nullable',
                Rule::enum(RtList::class),
            ],

            'rw' => [
                'nullable',
                Rule::enum(RwList::class),
            ],

            'alamat' => [
                'nullable',
                'string',
            ],

            'tgl_mutasi' => [
                'required',
                'date',
                'before_or_equal:today',
                'after_or_equal:tgl_lahir',
            ],

            'kategori_mutasi' => [
                'required',
                Rule::in([
                    MutationCategoryList::BIRTH->value,
                    MutationCategoryList::INCOMING->value,
                ]),
            ],

            'tujuan_mutasi' => [
                'nullable',
                'required_if:kategori_mutasi,' . MutationCategoryList::INCOMING->value,
                'string',
                'max:255',
            ],

            'catatan_mutasi' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nomor_identitas.required' => 'Nomor identitas wajib diisi.',
            'nomor_identitas.unique' => 'Nomor identitas sudah terdaftar di Buku Induk Penduduk.',
            'nomor_identitas.max' => 'Nomor identitas maksimal 30 karakter.',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 150 karakter.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.enum' => 'Jenis kelamin tidak valid.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tempat_lahir.max' => 'Tempat lahir maksimal 100 karakter.',
            'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tgl_lahir.date' => 'Tanggal lahir tidak valid.',
            'tgl_lahir.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini.',
            'status_perkawinan.required' => 'Status perkawinan wajib dipilih.',
            'status_perkawinan.enum' => 'Status perkawinan tidak valid.',
            'agama.required' => 'Agama wajib dipilih.',
            'agama.enum' => 'Agama tidak valid.',
            'pendidikan.required' => 'Pendidikan terakhir wajib dipilih.',
            'pendidikan.enum' => 'Pendidikan tidak valid.',
            'pekerjaan.required' => 'Pekerjaan wajib dipilih.',
            'pekerjaan.enum' => 'Pekerjaan tidak valid.',
            'status_dalam_keluarga.required' => 'Status dalam keluarga wajib dipilih.',
            'status_dalam_keluarga.enum' => 'Status dalam keluarga tidak valid.',
            'dapat_membaca.required' => 'Status membaca wajib dipilih.',
            'dapat_membaca.boolean' => 'Status membaca tidak valid.',
            'gol_darah.enum' => 'Golongan darah tidak valid.',
            'kewarganegaraan.required' => 'Kewarganegaraan wajib dipilih.',
            'kewarganegaraan.enum' => 'Kewarganegaraan tidak valid.',
            'kebangsaan.required' => 'Kebangsaan wajib diisi.',
            'kebangsaan.max' => 'Kebangsaan maksimal 100 karakter.',
            'kk.digits' => 'Nomor KK harus terdiri dari 16 digit.',
            'rt.enum' => 'RT yang dipilih tidak valid.',
            'rw.enum' => 'RW yang dipilih tidak valid.',
            'tgl_mutasi.required' => 'Tanggal mutasi wajib diisi.',
            'tgl_mutasi.date' => 'Tanggal mutasi tidak valid.',
            'tgl_mutasi.before_or_equal' => 'Tanggal mutasi tidak boleh melebihi hari ini.',
            'tgl_mutasi.after_or_equal' => 'Tanggal mutasi tidak boleh sebelum tanggal lahir.',
            'kategori_mutasi.required' => 'Kategori mutasi wajib dipilih.',
            'kategori_mutasi.in' => 'Kategori hanya boleh kelahiran atau penduduk masuk.',
            'tujuan_mutasi.required_if' => 'Asal mutasi wajib diisi untuk penduduk masuk.',
            'tujuan_mutasi.max' => 'Asal mutasi maksimal 255 karakter.',
            'catatan_mutasi.max' => 'Catatan mutasi maksimal 255 karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nomor_identitas' => 'nomor identitas',
            'nama_lengkap' => 'nama lengkap',
            'jenis_kelamin' => 'jenis kelamin',
            'tempat_lahir' => 'tempat lahir',
            'tgl_lahir' => 'tanggal lahir',
            'status_perkawinan' => 'status perkawinan',
            'agama' => 'agama',
            'pendidikan' => 'pendidikan',
            'pekerjaan' => 'pekerjaan',
            'status_dalam_keluarga' => 'status dalam keluarga',
            'dapat_membaca' => 'status membaca',
            'gol_darah' => 'golongan darah',
            'kewarganegaraan' => 'kewarganegaraan',
            'kebangsaan' => 'kebangsaan',
            'tempat_dikeluarkan' => 'tempat dikeluarkan',
            'tgl_dikeluarkan' => 'tanggal dikeluarkan',
            'tgl_mulai_tinggal' => 'tanggal mulai tinggal',
            'kk' => 'nomor KK',
            'rt' => 'RT',
            'rw' => 'RW',
            'alamat' => 'alamat',
            'tgl_mutasi' => 'tanggal mutasi',
            'kategori_mutasi' => 'kategori mutasi',
            'tujuan_mutasi' => 'asal mutasi',
            'catatan_mutasi' => 'catatan mutasi',
        ];
    }
}
