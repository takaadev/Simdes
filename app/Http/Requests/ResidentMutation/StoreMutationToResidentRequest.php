<?php

namespace App\Http\Requests\ResidentMutation;

use App\Enums\EducationList;
use App\Enums\MaritalStatusList;
use App\Enums\OccupationList;
use App\Enums\RelationshipList;
use App\Enums\ReligionList;
use App\Enums\RtList;
use App\Enums\RwList;
use App\Enums\MutationTypeList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMutationToResidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mutation_id' => [
                'required',
                'integer',
                Rule::exists('resident_mutations', 'id')
                    ->where('tipe_mutasi', MutationTypeList::ADD->value)
                    ->whereNull('resident_id')
                    ->whereNull('deleted_at'),
            ],

            'nik' => [
                'required',
                'string',
                'digits:16',
                Rule::unique('residents', 'nik'),
            ],

            'kk' => [
                'required',
                'string',
                'digits:16',
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

            'rt' => [
                'nullable',
                Rule::enum(RtList::class),
            ],

            'rw' => [
                'nullable',
                Rule::enum(RwList::class),
            ],

            'nomor_rumah' => [
                'nullable',
                'string',
                'max:10',
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
            'mutation_id.required' => 'Data mutasi penduduk wajib dipilih.',
            'mutation_id.integer' => 'Data mutasi penduduk tidak valid.',
            'mutation_id.exists' => 'Data mutasi tidak ditemukan, bukan mutasi masuk, atau sudah masuk Buku Induk Penduduk.',

            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'nik.unique' => 'NIK sudah terdaftar dalam Buku Induk Penduduk.',

            'kk.required' => 'Nomor Kartu Keluarga wajib diisi.',
            'kk.digits' => 'Nomor Kartu Keluarga harus terdiri dari 16 digit angka.',

            'status_perkawinan.required' => 'Status perkawinan wajib dipilih.',
            'status_perkawinan.enum' => 'Status perkawinan yang dipilih tidak valid.',

            'agama.required' => 'Agama wajib dipilih.',
            'agama.enum' => 'Agama yang dipilih tidak valid.',

            'pendidikan.required' => 'Pendidikan terakhir wajib dipilih.',
            'pendidikan.enum' => 'Pendidikan terakhir yang dipilih tidak valid.',

            'pekerjaan.required' => 'Pekerjaan wajib dipilih.',
            'pekerjaan.enum' => 'Pekerjaan yang dipilih tidak valid.',

            'status_dalam_keluarga.required' => 'Status dalam keluarga wajib dipilih.',
            'status_dalam_keluarga.enum' => 'Status dalam keluarga yang dipilih tidak valid.',

            'dapat_membaca.required' => 'Kemampuan membaca wajib dipilih.',
            'dapat_membaca.boolean' => 'Pilihan kemampuan membaca tidak valid.',

            'rt.enum' => 'RT yang dipilih tidak valid.',
            'rw.enum' => 'RW yang dipilih tidak valid.',

            'nomor_rumah.max' => 'Nomor rumah maksimal 10 karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'mutation_id' => 'data mutasi',
            'nik' => 'NIK',
            'kk' => 'nomor Kartu Keluarga',
            'status_perkawinan' => 'status perkawinan',
            'agama' => 'agama',
            'pendidikan' => 'pendidikan terakhir',
            'pekerjaan' => 'pekerjaan',
            'status_dalam_keluarga' => 'status dalam keluarga',
            'dapat_membaca' => 'kemampuan membaca',
            'rt' => 'RT',
            'rw' => 'RW',
            'nomor_rumah' => 'nomor rumah',
            'catatan' => 'catatan',
        ];
    }
}
