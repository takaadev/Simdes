<?php

namespace App\Http\Requests\User;

use App\Enums\RoleAccessType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->route('user');

        return [
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user?->id),
            ],
            'role' => [
                'required',
                Rule::enum(RoleAccessType::class),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.email' => 'Format alamat email tidak valid.',
            'email.max' => 'Alamat email maksimal 255 karakter.',
            'email.unique' => 'Alamat email sudah digunakan.',
            'role.required' => 'Hak akses wajib dipilih.',
            'role.enum' => 'Hak akses yang dipilih tidak valid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'alamat email',
            'role' => 'hak akses',
        ];
    }
}
