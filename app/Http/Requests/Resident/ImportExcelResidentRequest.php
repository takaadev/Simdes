<?php

namespace App\Http\Requests\Resident;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ImportExcelResidentRequest extends FormRequest
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
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
                'max:5120',
            ],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'file.required' => 'Silakan pilih file Excel terlebih dahulu.',
            'file.file' => 'File yang diunggah tidak valid.',
            'file.mimes' => 'Format file harus XLSX, XLS, atau CSV.',
            'file.max' => 'Ukuran file maksimal 5 MB.',
        ];
    }

    /**
     * Configure the validator.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->hasFile('file')) {
                $file = $this->file('file');

                if (!$file->isValid()) {
                    $validator->errors()->add(
                        'file',
                        'File gagal diunggah. Silakan coba kembali.'
                    );
                }
            }
        });
    }

    /**
     * Return JSON validation errors.
     */
    protected function failedValidation(
        \Illuminate\Contracts\Validation\Validator $validator
    ): void {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'File tidak memenuhi ketentuan.',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
