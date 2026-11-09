<?php

namespace App\Http\Requests\VillageRegulation;

use App\Enums\RegulationStatusList;
use App\Enums\RegulationTypeList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVillageRegulationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
        $regulationId = $regulation instanceof \Illuminate\Database\Eloquent\Model
            ? $regulation->getKey()
            : $regulation;

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
                Rule::unique('village_regulations', 'nomor_dilaporkan')
                    ->ignore($regulationId),
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
