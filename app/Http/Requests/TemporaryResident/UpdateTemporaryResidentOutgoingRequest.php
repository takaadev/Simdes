<?php

namespace App\Http\Requests\TemporaryResident;

use App\Models\TemporaryResident;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTemporaryResidentOutgoingRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna diizinkan melakukan permintaan.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi data kepergian penduduk sementara.
     */
    public function rules(): array
    {
        $idTemporary = $this->route('id');

        // Pastikan parameter rute berupa model TemporaryResident.
        if (! $idTemporary instanceof TemporaryResident) {
            $temporary = TemporaryResident::findOrFail($idTemporary);
        }

        return [
            'tgl_pergi' => [
                'required',
                'date',
                'after_or_equal:' . $temporary->tgl_datang->format('Y-m-d'),
                'before_or_equal:today',
            ],
        ];
    }

    /**
     * Pesan kesalahan validasi dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'tgl_pergi.required' => 'Tanggal kepergian wajib diisi.',
            'tgl_pergi.date' => 'Tanggal kepergian harus berupa tanggal yang valid.',
            'tgl_pergi.after_or_equal' => 'Tanggal kepergian tidak boleh lebih awal dari tanggal kedatangan.',
            'tgl_pergi.before_or_equal' => 'Tanggal kepergian tidak boleh melebihi tanggal hari ini.',
        ];
    }

    /**
     * Nama atribut dalam pesan validasi.
     */
    public function attributes(): array
    {
        return [
            'tgl_pergi' => 'tanggal kepergian',
        ];
    }
}
