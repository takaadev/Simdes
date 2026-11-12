<?php

namespace App\Http\Requests\ResidentMutation;

use App\Enums\CitizenshipList;
use App\Enums\GenderList;
use App\Enums\MutationCategoryList;
use App\Enums\MutationTypeList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateResidentMutationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => [
                'required',
                'string',
                'max:150',
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

            'jenis_kelamin' => [
                'required',
                Rule::enum(GenderList::class),
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

            'tipe_mutasi' => [
                'required',
                Rule::enum(MutationTypeList::class),
            ],

            'kategori_mutasi' => [
                'required',
                Rule::enum(MutationCategoryList::class),
            ],

            'tgl_mutasi' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'tujuan_mutasi' => [
                'required_unless:kategori_mutasi,birth,death',
                'nullable',
                'string',
                'max:255',
            ],

            'catatan' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'nama_lengkap.max' => 'Nama lengkap maksimal 150 karakter.',

            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tempat_lahir.max' => 'Tempat lahir maksimal 100 karakter.',

            'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tgl_lahir.date' => 'Format tanggal lahir tidak valid.',
            'tgl_lahir.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini.',

            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.enum' => 'Jenis kelamin yang dipilih tidak valid.',

            'kewarganegaraan.required' => 'Kewarganegaraan wajib dipilih.',
            'kewarganegaraan.enum' => 'Kewarganegaraan yang dipilih tidak valid.',

            'kebangsaan.required' => 'Kebangsaan wajib diisi.',
            'kebangsaan.max' => 'Kebangsaan maksimal 100 karakter.',

            'tipe_mutasi.required' => 'Tipe mutasi wajib dipilih.',
            'tipe_mutasi.enum' => 'Tipe mutasi yang dipilih tidak valid.',

            'kategori_mutasi.required' => 'Kategori mutasi wajib dipilih.',
            'kategori_mutasi.enum' => 'Kategori mutasi yang dipilih tidak valid.',

            'tgl_mutasi.required' => 'Tanggal mutasi wajib diisi.',
            'tgl_mutasi.date' => 'Format tanggal mutasi tidak valid.',
            'tgl_mutasi.before_or_equal' => 'Tanggal mutasi tidak boleh melebihi hari ini.',

            'tujuan_mutasi.required_unless' => 'Asal atau tujuan mutasi wajib diisi untuk penduduk masuk dan keluar.',
            'tujuan_mutasi.max' => 'Asal atau tujuan mutasi maksimal 255 karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_lengkap' => 'nama lengkap',
            'tempat_lahir' => 'tempat lahir',
            'tgl_lahir' => 'tanggal lahir',
            'jenis_kelamin' => 'jenis kelamin',
            'kewarganegaraan' => 'kewarganegaraan',
            'kebangsaan' => 'kebangsaan',
            'tipe_mutasi' => 'tipe mutasi',
            'kategori_mutasi' => 'kategori mutasi',
            'tgl_mutasi' => 'tanggal mutasi',
            'tujuan_mutasi' => 'asal atau tujuan mutasi',
            'catatan' => 'catatan',
        ];
    }
}
