<?php

namespace App\Http\Requests\FamilyCard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFamilyCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $familyCard = $this->route('familycard');

        return [
            'kk' => [
                'required',
                'string',
                'digits:16',
                Rule::unique('family_cards', 'nomor_kk')
                    ->ignore($familyCard?->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
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

    public function attributes(): array
    {
        return [
            'kk' => 'nomor kartu keluarga',
        ];
    }
}
