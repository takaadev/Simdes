<?php

namespace App\Http\Requests\VillagePublication;

use App\Enums\RegulationStatusList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVillagePublicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // A. Informasi Peraturan
            'regulation_id' => [
                'required',
                'integer',
                Rule::exists('village_regulations', 'id')
                    ->where('status', RegulationStatusList::DRAFT->value),
                Rule::unique('village_publications', 'regulation_id'),
            ],

            // B. Pengundangan dan Berita Desa
            'nomor_diundang' => [
                'nullable',
                'string',
                'max:100',
                'unique:village_publications,nomor_diundang',
            ],

            'tgl_diundang' => [
                'required',
                'date',
            ],

            'nomor_berita' => [
                'nullable',
                'string',
                'max:100',
                'unique:village_publications,nomor_berita',
            ],

            'tgl_berita' => [
                'required',
                'date',
                'after_or_equal:tgl_diundang',
            ],

            // C. Status Pengundangan
            'status' => [
                'required',
                \Illuminate\Validation\Rule::enum(\App\Enums\RegulationStatusList::class),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'integer' => ':attribute tidak valid.',
            'exists' => ':attribute tidak ditemukan atau bukan berstatus draft.',
            'unique' => ':attribute sudah digunakan.',
            'string' => ':attribute harus berupa teks.',
            'max' => ':attribute maksimal :max karakter.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'after_or_equal' => ':attribute tidak boleh mendahului :date.',
            'enum' => ':attribute yang dipilih tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'regulation_id' => 'peraturan desa',
            'nomor_diundang' => 'nomor pengundangan',
            'tgl_diundang' => 'tanggal diundangkan',
            'nomor_berita' => 'nomor berita desa',
            'tgl_berita' => 'tanggal berita desa',
            'status' => 'status pengundangan',
        ];
    }
}
