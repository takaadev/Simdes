<?php

namespace App\Http\Requests\TemporaryResident;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTemporaryResidentRequest extends FormRequest
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
        $temporary = $this->route('temporary');
        $id = $temporary?->id ?? $temporary;

        return [
            // A. Identitas Penduduk
            'nama_lengkap' => [
                'required',
                'string',
                'max:150',
            ],

            'nomor_identitas' => [
                'required',
                'string',
                'max:30',
                Rule::unique('temporary_residents', 'nomor_identitas')
                    ->whereNull('tgl_pergi')
                    ->ignore($id),
            ],

            'jenis_kelamin' => [
                'required',
                'string',
            ],

            'tempat_lahir' => [
                'required',
                'string',
                'max:100',
            ],

            'tgl_lahir' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'umur' => [
                'required',
                'integer',
                'min:0',
                'max:150',
            ],

            'pekerjaan' => [
                'required',
                'string',
            ],

            'kewarganegaraan' => [
                'required',
                'string',
            ],

            'kebangsaan' => [
                'nullable',
                'string',
                'max:100',
            ],

            'keturunan' => [
                'nullable',
                'string',
                'max:50',
            ],

            // B. Informasi Kedatangan
            'tgl_datang' => [
                'required',
                'date',
            ],

            'datang_dari' => [
                'required',
                'string',
                'max:150',
            ],

            'tujuan_kedatangan' => [
                'required',
                'string',
                'max:255',
            ],

            'alamat_tujuan' => [
                'required',
                'string',
                'max:255',
            ],

            'catatan' => [
                'nullable',
                'string',
            ],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',

            'nomor_identitas.required' => 'Nomor identitas wajib diisi.',
            'nomor_identitas.unique' => 'Nomor identitas ini masih terdaftar sebagai penduduk sementara yang belum pergi.',

            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tgl_lahir.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini.',

            'umur.required' => 'Umur wajib diisi.',
            'umur.integer' => 'Umur harus berupa angka bulat.',
            'umur.min' => 'Umur minimal 0 tahun.',
            'umur.max' => 'Umur maksimal 150 tahun.',

            'pekerjaan.required' => 'Pekerjaan wajib dipilih.',
            'kewarganegaraan.required' => 'Kewarganegaraan wajib dipilih.',

            'tgl_datang.required' => 'Tanggal kedatangan wajib diisi.',
            'datang_dari.required' => 'Daerah asal wajib diisi.',
            'tujuan_kedatangan.required' => 'Tujuan kedatangan wajib diisi.',
            'alamat_tujuan.required' => 'Alamat tujuan wajib diisi.',
        ];
    }

    /**
     * Get custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'nama_lengkap' => 'nama lengkap',
            'nomor_identitas' => 'nomor identitas',
            'jenis_kelamin' => 'jenis kelamin',
            'tempat_lahir' => 'tempat lahir',
            'tgl_lahir' => 'tanggal lahir',
            'umur' => 'umur',
            'pekerjaan' => 'pekerjaan',
            'kewarganegaraan' => 'kewarganegaraan',
            'kebangsaan' => 'kebangsaan',
            'keturunan' => 'keturunan',
            'tgl_datang' => 'tanggal kedatangan',
            'datang_dari' => 'daerah asal',
            'tujuan_kedatangan' => 'tujuan kedatangan',
            'alamat_tujuan' => 'alamat tujuan',
            'catatan' => 'catatan',
        ];
    }
}