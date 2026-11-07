<?php

namespace App\Http\Requests\VillageRegulation;

use App\Enums\RegulationStatusList;
use App\Enums\RegulationTypeList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVillageRegulationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make the request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Ambil data peraturan dari route resource.
        $regulation = $this->route('villageRegulation');

        // Ambil ID data yang sedang diedit.
        $regulationId = $regulation instanceof \Illuminate\Database\Eloquent\Model ? $regulation->getKey() : $regulation;

        return [
            // Informasi Peraturan
            'jenis_peraturan' => [
                'required',
                Rule::enum(RegulationTypeList::class),
            ],

            'nomor_peraturan' => [
                'required',
                'string',
                'max:100',
                Rule::unique('village_regulations', 'nomor_peraturan')
                    ->ignore($regulationId),
            ],

            'nama_peraturan' => [
                'required',
                'string',
                'max:255',
                Rule::unique('village_regulations', 'nama_peraturan')
                    ->ignore($regulationId),
            ],

            'uraian' => [
                'nullable',
                'string',
            ],

            // Penetapan dan Kesepakatan
            'tgl_ditetapkan' => [
                'required',
                'date',
            ],

            'tgl_kesepakatan' => [
                'nullable',
                'date',
            ],

            // Pelaporan
            'nomor_dilaporkan' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('village_regulations', 'nomor_dilaporkan')
                    ->ignore($regulationId),
            ],

            'tgl_dilaporkan' => [
                'nullable',
                'date',
            ],

            // Pengundangan
            'nomor_diundang' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('village_regulations', 'nomor_diundang')
                    ->ignore($regulationId),
            ],

            'tgl_diundang' => [
                'nullable',
                'date',
            ],

            // Berita Desa
            'nomor_berita' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('village_regulations', 'nomor_berita')
                    ->ignore($regulationId),
            ],

            'tgl_berita' => [
                'nullable',
                'date',
            ],

            // Dokumen dan Catatan
            'catatan' => [
                'nullable',
                'string',
            ],

            'dokumen' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:5120',
            ],

            // Status
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
            'string' => ':attribute harus berupa teks.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'max' => ':attribute maksimal :max karakter.',
            'unique' => ':attribute sudah digunakan.',
            'file' => ':attribute harus berupa berkas.',
            'mimes' => ':attribute harus berformat PDF.',
            'jenis_peraturan.enum' => 'Jenis peraturan yang dipilih tidak valid.',
            'status.enum' => 'Status peraturan yang dipilih tidak valid.',
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
            'nama_peraturan' => 'nama peraturan',
            'uraian' => 'uraian peraturan',
            'tgl_ditetapkan' => 'tanggal ditetapkan',
            'tgl_kesepakatan' => 'tanggal kesepakatan',
            'nomor_dilaporkan' => 'nomor pelaporan',
            'tgl_dilaporkan' => 'tanggal pelaporan',
            'nomor_diundang' => 'nomor pengundangan',
            'tgl_diundang' => 'tanggal pengundangan',
            'nomor_berita' => 'nomor berita desa',
            'tgl_berita' => 'tanggal berita desa',
            'catatan' => 'catatan',
            'dokumen' => 'dokumen peraturan',
            'status' => 'status peraturan',
        ];
    }
}
