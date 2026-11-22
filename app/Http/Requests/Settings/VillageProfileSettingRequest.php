<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class VillageProfileSettingRequest extends FormRequest
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
            'nama_desa'       => ['required', 'string', 'max:100'],
            'kode_wilayah'    => ['required', 'string', 'max:100'],
            'kecamatan'       => ['required', 'string', 'max:100'],
            'kabupaten'       => ['required', 'string', 'max:100'],
            'provinsi'        => ['required', 'string', 'max:100'],
            'kode_pos'        => ['required', 'string', 'max:10'],
            'alamat_kantor'   => ['required', 'string'],
            'telepon'         => ['nullable', 'string', 'max:20'],
            'email'           => ['nullable', 'email', 'max:255'],
            'nama_kades'      => ['nullable', 'string', 'max:150'],
            'nama_sekdes'     => ['nullable', 'string', 'max:150'],
            'total_rw'        => ['required', 'integer', 'min:0'],
            'total_rt'        => ['required', 'integer', 'min:0'],
            'jam_operasional' => ['nullable', 'string', 'max:255'],
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
            'nama_desa'       => 'Nama Desa',
            'kode_wilayah'    => 'Kode Wilayah',
            'kecamatan'       => 'Kecamatan',
            'kabupaten'       => 'Kabupaten',
            'provinsi'        => 'Provinsi',
            'kode_pos'        => 'Kode Pos',
            'alamat_kantor'   => 'Alamat Kantor',
            'telepon'         => 'Telepon',
            'email'           => 'Email',
            'nama_kades'      => 'Kepala Desa',
            'nama_sekdes'     => 'Sekretaris Desa',
            'total_rw'        => 'Jumlah RW',
            'total_rt'        => 'Jumlah RT',
            'jam_operasional' => 'Jam Layanan Kantor',
        ];
    }
}