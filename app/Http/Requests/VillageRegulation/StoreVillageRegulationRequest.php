<?php

namespace App\Http\Requests\VillageRegulation;

use App\Enums\RegulationStatusList;
use App\Enums\RegulationTypeList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVillageRegulationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // A. Informasi Peraturan
            'jenis_peraturan' => [
                'required',
                Rule::enum(RegulationTypeList::class),
            ],

            'nomor_peraturan' => [
                'required',
                'string',
                'max:100',
                'unique:village_regulations,nomor_peraturan',
            ],

            'nama_peraturan' => [
                'required',
                'string',
                'max:255',
                'unique:village_regulations,nama_peraturan',
            ],

            'uraian' => [
                'nullable',
                'string',
            ],

            // B. Penetapan
            'tgl_ditetapkan' => [
                'required',
                'date',
            ],

            'tgl_kesepakatan' => [
                'nullable',
                'date',
                'before_or_equal:tgl_ditetapkan',
            ],

            // C. Pelaporan
            'nomor_dilaporkan' => [
                'nullable',
                'string',
                'max:100',
                'unique:village_regulations,nomor_dilaporkan',
                'required_with:tgl_dilaporkan',
            ],

            'tgl_dilaporkan' => [
                'nullable',
                'date',
                'after_or_equal:tgl_ditetapkan',
                'required_with:nomor_dilaporkan',
            ],

            // D. Dokumen dan Catatan
            'dokumen' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:5120',
            ],

            'catatan' => [
                'nullable',
                'string',
            ],

            // E. Status
            'status' => [
                'sometimes',
                Rule::enum(RegulationStatusList::class),
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'required_with' => ':attribute wajib diisi apabila :values diisi.',
            'string' => ':attribute harus berupa teks.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'before_or_equal' => ':attribute tidak boleh melebihi :date.',
            'after_or_equal' => ':attribute tidak boleh mendahului :date.',
            'max' => ':attribute maksimal :max karakter.',
            'unique' => ':attribute sudah digunakan.',
            'file' => ':attribute harus berupa berkas.',
            'mimes' => ':attribute harus berformat PDF.',
            'enum' => ':attribute yang dipilih tidak valid.',
        ];
    }

    /**
     * Custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'jenis_peraturan' => 'jenis peraturan',
            'nomor_peraturan' => 'nomor peraturan',
            'nama_peraturan' => 'judul peraturan',
            'uraian' => 'uraian peraturan',
            'tgl_ditetapkan' => 'tanggal ditetapkan',
            'tgl_kesepakatan' => 'tanggal kesepakatan',
            'nomor_dilaporkan' => 'nomor pelaporan',
            'tgl_dilaporkan' => 'tanggal pelaporan',
            'dokumen' => 'dokumen peraturan',
            'catatan' => 'catatan',
            'status' => 'status peraturan',
        ];
    }
}
