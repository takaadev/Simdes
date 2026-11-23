<?php

namespace App\Http\Requests\VillagePosition;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVillagePositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'kode' => is_string($this->kode) ? trim($this->kode) : $this->kode,
            'nama' => is_string($this->nama) ? trim($this->nama) : $this->nama,
        ]);
    }

    public function rules(): array
    {
        $villagePosition = $this->route('village_position')
            ?? $this->route('villagePosition');

        $villagePositionId = is_object($villagePosition)
            ? $villagePosition->getKey()
            : $villagePosition;

        return [
            'kode' => [
                'required',
                'string',
                'max:10',
                Rule::unique('village_positions', 'kode')
                    ->ignore($villagePositionId),
            ],

            'nama' => [
                'required',
                'string',
                'max:255',
                Rule::unique('village_positions', 'nama')
                    ->ignore($villagePositionId),
            ],

            'is_active' => [
                'required',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // Kode
            'kode.required' => 'Kode jabatan wajib diisi.',
            'kode.string' => 'Kode jabatan harus berupa teks.',
            'kode.max' => 'Kode jabatan maksimal 10 karakter.',
            'kode.unique' => 'Kode jabatan sudah digunakan.',

            // Nama
            'nama.required' => 'Nama jabatan wajib diisi.',
            'nama.string' => 'Nama jabatan harus berupa teks.',
            'nama.max' => 'Nama jabatan maksimal 255 karakter.',
            'nama.unique' => 'Nama jabatan sudah digunakan.',

            // Status
            'is_active.required' => 'Status jabatan wajib dipilih.',
            'is_active.boolean' => 'Status jabatan tidak valid.',
        ];
    }
}
