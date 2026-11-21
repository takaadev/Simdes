<?php

namespace App\Http\Requests\User;

use App\Enums\RoleAccessType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'official_id' => [
                'bail',
                'required',
                'integer',
                Rule::exists('village_officials', 'id')
                    ->whereNull('auth_id')
                    ->whereNull('deleted_at'),
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],

            'role' => [
                'required',
                Rule::enum(RoleAccessType::class),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'official_id.required' => 'Perangkat desa wajib dipilih.',
            'official_id.integer' => 'Perangkat desa tidak valid.',
            'official_id.exists' => 'Perangkat desa tidak tersedia atau sudah memiliki akun.',

            'email.email' => 'Format alamat email tidak valid.',
            'email.max' => 'Alamat email maksimal 255 karakter.',
            'email.unique' => 'Alamat email sudah digunakan.',

            'role.required' => 'Hak akses wajib dipilih.',
            'role.enum' => 'Hak akses yang dipilih tidak valid.',

            'password.string' => 'Kata sandi harus berupa teks.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.max' => 'Kata sandi maksimal 255 karakter.',
        ];
    }
}
