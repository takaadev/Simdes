<?php

namespace App\Http\Requests\FamilyCard;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFamilyCardRequest extends FormRequest
{
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
        return [
            'resident_id' => [
                'required',
                'integer',
                Rule::exists('residents', 'id')
                    ->whereNull('deleted_at')
                    ->where(function ($query) {
                        $query->whereNull('status_dalam_keluarga')
                            ->orWhere(
                                'status_dalam_keluarga',
                                '!=',
                                'Kepala Keluarga'
                            );
                    }),
            ],

            'kk' => [
                'required',
                'string',
                'digits:16',
                Rule::unique('family_cards', 'nomor_kk'),
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'resident_id.required' =>
                'Penduduk wajib dipilih.',

            'resident_id.integer' =>
                'Data penduduk yang dipilih tidak valid.',

            'resident_id.exists' =>
                'Penduduk masih berstatus Kepala Keluarga. Silakan ubah status dalam keluarga melalui Buku Induk Penduduk terlebih dahulu.',

            'kk.required' =>
                'Nomor Kartu Keluarga wajib diisi.',

            'kk.string' =>
                'Nomor Kartu Keluarga harus berupa teks.',

            'kk.digits' =>
                'Nomor Kartu Keluarga harus terdiri dari 16 digit.',

            'kk.unique' =>
                'Nomor Kartu Keluarga sudah terdaftar.',
        ];
    }

    /**
     * Custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'resident_id' => 'penduduk',
            'kk' => 'nomor kartu keluarga',
        ];
    }
}
