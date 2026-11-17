<?php

namespace App\Http\Requests\ResidentMutation;

use App\Enums\MutationCategoryList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMutationOutgoingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'resident_id' => [
                'required',
                'integer',
                Rule::exists('residents', 'id')
                    ->where('is_aktif', true)
                    ->whereNull('deleted_at'),
            ],

            'kategori_mutasi' => [
                'required',
                Rule::enum(MutationCategoryList::class),
                Rule::in([
                    MutationCategoryList::DEATH->value,
                    MutationCategoryList::OUTGOING->value,
                ]),
            ],

            'tgl_mutasi' => [
                'required',
                'date',
            ],

            'tujuan_mutasi' => [
                'nullable',
                'required_if:kategori_mutasi,outgoing',
                'string',
                'max:255',
            ],

            'catatan' => [
                'nullable',
                'string',
                'max:65535',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'resident_id.required' => 'Penduduk yang akan dimutasi wajib dipilih.',
            'resident_id.integer' => 'Data penduduk tidak valid.',
            'resident_id.exists' => 'Penduduk tidak ditemukan, sudah tidak aktif, atau telah dihapus.',

            'kategori_mutasi.required' => 'Kategori mutasi wajib dipilih.',
            'kategori_mutasi.enum' => 'Kategori mutasi tidak valid.',
            'kategori_mutasi.in' => 'Kategori mutasi harus berupa kematian atau penduduk keluar.',

            'tgl_mutasi.required' => 'Tanggal mutasi wajib diisi.',
            'tgl_mutasi.date' => 'Tanggal mutasi tidak valid.',

            'tujuan_mutasi.required_if' => 'Tujuan pindah wajib diisi untuk mutasi penduduk keluar.',
            'tujuan_mutasi.string' => 'Tujuan pindah harus berupa teks.',
            'tujuan_mutasi.max' => 'Tujuan pindah maksimal 255 karakter.',

            'catatan.string' => 'Catatan harus berupa teks.',
            'catatan.max' => 'Catatan terlalu panjang.',
        ];
    }

    public function attributes(): array
    {
        return [
            'resident_id' => 'penduduk',
            'kategori_mutasi' => 'kategori mutasi',
            'tgl_mutasi' => 'tanggal mutasi',
            'tujuan_mutasi' => 'tujuan pindah',
            'catatan' => 'catatan',
        ];
    }
}
