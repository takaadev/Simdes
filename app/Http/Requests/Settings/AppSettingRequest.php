<?php

namespace App\Http\Requests\Settings;
use Illuminate\Foundation\Http\FormRequest;

class AppSettingRequest extends FormRequest
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
        return [
            'logo'      => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:500'],
            'deskripsi' => ['nullable', 'string'],
            'time_zone' => ['required', 'string', 'timezone:all', 'max:50'],
        ];
    }

    /**
     * Custom attribute names for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'logo'      => 'Logo',
            'deskripsi' => 'Deskripsi Aplikasi',
            'time_zone' => 'Zona Waktu',
        ];
    }
}