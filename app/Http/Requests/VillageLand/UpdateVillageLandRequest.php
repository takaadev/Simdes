<?php

namespace App\Http\Requests\VillageLand;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVillageLandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // A. Identitas Pemilik Tanah
            'owner_name' => [
                'required',
                'string',
                'max:150',
            ],

            'jml' => [
                'required',
                'numeric',
                'min:0',
                'decimal:0,2',
            ],

            // B. Status Hak Tanah - Sudah Bersertifikat
            'hm' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'hgb' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'hp' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'hgu' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'hpl' => ['required', 'numeric', 'min:0', 'decimal:0,2'],

            // B. Status Hak Tanah - Belum Bersertifikat
            'ma' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'vi' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'tn' => ['required', 'numeric', 'min:0', 'decimal:0,2'],

            // C. Penggunaan Tanah - Non Pertanian
            'perumahan' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'dagang_jasa' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'perkantoran' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'industri' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'fasilitas_umum' => ['required', 'numeric', 'min:0', 'decimal:0,2'],

            // C. Penggunaan Tanah - Pertanian
            'sawah' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'tegalan' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'perkebunan' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'peternakan_perikanan' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'hutan_belukar' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'hutan_lebat' => ['required', 'numeric', 'min:0', 'decimal:0,2'],

            // C. Penggunaan Tanah - Kategori Lainnya
            'tanah_kosong' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'lainnya' => ['required', 'numeric', 'min:0', 'decimal:0,2'],

            // D. Mutasi Tanah
            'mutasi_tanah' => [
                'required',
                'numeric',
                'min:0',
                'decimal:0,2',
            ],

            // E. Keterangan
            'catatan' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'owner_name.required' => 'Nama perorangan atau badan hukum wajib diisi.',
            'owner_name.max' => 'Nama pemilik tanah maksimal 150 karakter.',

            'jml.required' => 'Jumlah luas tercatat wajib diisi.',
            'jml.numeric' => 'Jumlah luas tercatat harus berupa angka.',
            'jml.min' => 'Jumlah luas tercatat tidak boleh kurang dari 0.',

            '*.required' => 'Kolom :attribute wajib diisi.',
            '*.numeric' => 'Kolom :attribute harus berupa angka.',
            '*.min' => 'Kolom :attribute tidak boleh kurang dari 0.',
            '*.decimal' => 'Kolom :attribute maksimal menggunakan 2 angka desimal.',

            'catatan.string' => 'Keterangan harus berupa teks.',
        ];
    }

    public function attributes(): array
    {
        return [
            'owner_name' => 'nama pemilik tanah',
            'jml' => 'jumlah luas tercatat',

            'hm' => 'HM',
            'hgb' => 'HGB',
            'hp' => 'HP',
            'hgu' => 'HGU',
            'hpl' => 'HPL',

            'ma' => 'MA',
            'vi' => 'VI',
            'tn' => 'TN',

            'perumahan' => 'perumahan',
            'dagang_jasa' => 'perdagangan dan jasa',
            'perkantoran' => 'perkantoran',
            'industri' => 'industri',
            'fasilitas_umum' => 'fasilitas umum',

            'sawah' => 'sawah',
            'tegalan' => 'tegalan',
            'perkebunan' => 'perkebunan',
            'peternakan_perikanan' => 'peternakan/perikanan',
            'hutan_belukar' => 'hutan belukar',
            'hutan_lebat' => 'hutan lebat/lindung',

            'tanah_kosong' => 'tanah kosong',
            'lainnya' => 'lain-lain',

            'mutasi_tanah' => 'mutasi tanah di desa',
            'catatan' => 'keterangan',
        ];
    }
}
