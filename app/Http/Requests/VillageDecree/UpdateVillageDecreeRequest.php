<?php

namespace App\Http\Requests\VillageDecree;

use App\Enums\DecreeStatusList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVillageDecreeRequest extends FormRequest
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
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $decree = $this->route('villageDecree');

        return [
            'nomor_keputusan' => [
                'required',
                'string',
                'max:30',
                Rule::unique('village_decrees', 'nomor_keputusan')
                    ->ignore($decree?->id),
            ],

            'tgl_keputusan' => [
                'required',
                'date',
            ],

            'nama_keputusan' => [
                'required',
                'string',
                'max:255',
            ],

            'uraian' => [
                'nullable',
                'string',
            ],

            'nomor_dilaporkan' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('village_decrees', 'nomor_dilaporkan')
                    ->ignore($decree?->id),
            ],

            'tgl_dilaporkan' => [
                'nullable',
                'date',
                'after_or_equal:tgl_keputusan',
            ],

            'dokumen' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:10240',
            ],

            'status' => [
                'sometimes',
                Rule::enum(DecreeStatusList::class),
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
            'nomor_keputusan.required' => 'Nomor keputusan wajib diisi.',
            'nomor_keputusan.string' => 'Nomor keputusan harus berupa teks.',
            'nomor_keputusan.max' => 'Nomor keputusan maksimal 30 karakter.',
            'nomor_keputusan.unique' => 'Nomor keputusan sudah digunakan.',

            'tgl_keputusan.required' => 'Tanggal keputusan wajib diisi.',
            'tgl_keputusan.date' => 'Tanggal keputusan tidak valid.',

            'nama_keputusan.required' => 'Nama keputusan wajib diisi.',
            'nama_keputusan.string' => 'Nama keputusan harus berupa teks.',
            'nama_keputusan.max' => 'Nama keputusan maksimal 255 karakter.',

            'uraian.string' => 'Uraian harus berupa teks.',

            'nomor_dilaporkan.string' => 'Nomor pelaporan harus berupa teks.',
            'nomor_dilaporkan.max' => 'Nomor pelaporan maksimal 100 karakter.',
            'nomor_dilaporkan.unique' => 'Nomor pelaporan sudah digunakan.',

            'tgl_dilaporkan.date' => 'Tanggal pelaporan tidak valid.',
            'tgl_dilaporkan.after_or_equal' => 'Tanggal pelaporan tidak boleh sebelum tanggal keputusan.',

            'dokumen.file' => 'Dokumen harus berupa berkas.',
            'dokumen.mimes' => 'Dokumen harus berformat PDF.',
            'dokumen.max' => 'Ukuran dokumen maksimal 10 MB.',

            'status.enum' => 'Status keputusan yang dipilih tidak valid.',
            'catatan.string' => 'Catatan harus berupa teks.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nomor_keputusan' => 'nomor keputusan',
            'tgl_keputusan' => 'tanggal keputusan',
            'nama_keputusan' => 'nama keputusan',
            'uraian' => 'uraian',
            'nomor_dilaporkan' => 'nomor pelaporan',
            'tgl_dilaporkan' => 'tanggal pelaporan',
            'dokumen' => 'dokumen',
            'status' => 'status',
            'catatan' => 'catatan',
        ];
    }
}
