<?php

namespace App\Http\Requests\ResidentMutation;

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
        // Mengambil data mutasi dari route resource.
        $mutation = $this->route('mutation');

        // Tipe mutasi diambil dari data yang tersimpan, bukan input form.
        $isAdd = $mutation?->tipe_mutasi === MutationTypeList::ADD;
        $isMinus = $mutation?->tipe_mutasi === MutationTypeList::MINUS;

        // Menentukan kategori yang diperbolehkan berdasarkan tipe mutasi.
        $allowedCategories = match (true) {
            $isAdd => [
                MutationCategoryList::BIRTH->value,
                MutationCategoryList::INCOMING->value,
            ],

            $isMinus => [
                MutationCategoryList::DEATH->value,
                MutationCategoryList::OUTGOING->value,
            ],

            default => [],
        };

        return [
            'kategori_mutasi' => [
                'required',
                Rule::enum(MutationCategoryList::class),
                Rule::in($allowedCategories),
            ],

            'tgl_mutasi' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'tujuan_mutasi' => [
                'required_if:kategori_mutasi,incoming,outgoing',
                'nullable',
                'string',
                'max:255',
            ],

            'catatan' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'kategori_mutasi.required' => 'Kategori mutasi wajib dipilih.',
            'kategori_mutasi.enum' => 'Kategori mutasi yang dipilih tidak valid.',
            'kategori_mutasi.in' => 'Kategori mutasi tidak sesuai dengan tipe mutasi.',

            'tgl_mutasi.required' => 'Tanggal mutasi wajib diisi.',
            'tgl_mutasi.date' => 'Format tanggal mutasi tidak valid.',
            'tgl_mutasi.before_or_equal' => 'Tanggal mutasi tidak boleh melebihi hari ini.',

            'tujuan_mutasi.required_if' => 'Asal atau tujuan mutasi wajib diisi untuk penduduk masuk dan keluar.',
            'tujuan_mutasi.string' => 'Asal atau tujuan mutasi harus berupa teks.',
            'tujuan_mutasi.max' => 'Asal atau tujuan mutasi maksimal 255 karakter.',

            'catatan.string' => 'Catatan harus berupa teks.',
            'catatan.max' => 'Catatan maksimal 255 karakter.',
        ];
    }

    public function attributes(): array
    {
        return [
            'kategori_mutasi' => 'kategori mutasi',
            'tgl_mutasi' => 'tanggal mutasi',
            'tujuan_mutasi' => 'asal atau tujuan mutasi',
            'catatan' => 'catatan',
        ];
    }
}
