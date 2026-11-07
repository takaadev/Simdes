<?php

namespace App\Http\Requests\VillageAsset;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreVillageAssetRequest extends FormRequest
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
     */
    public function rules(): array
    {
        // Cek apakah terdapat jumlah barang yang dihapus.
        $hasDeletion = collect([
            'hapus_rusak',
            'hapus_dijual',
            'hapus_disumbangkan',
        ])->contains(
            fn(string $field) =>
            is_numeric($this->input($field))
                && (int) $this->input($field) > 0
        );

        return [
            // A. Identitas Barang
            'nama_asset' => [
                'required',
                'string',
                'max:150',
            ],

            // B. Asal Barang/Bangunan
            'asal_beli' => ['required', 'integer', 'min:0'],
            'asal_pemerintah' => ['required', 'integer', 'min:0'],
            'asal_provinsi' => ['required', 'integer', 'min:0'],
            'asal_kabupaten' => ['required', 'integer', 'min:0'],
            'asal_sumbangan' => ['required', 'integer', 'min:0'],

            // C. Keadaan Awal Tahun
            'kondisi_awal_baik' => ['required', 'integer', 'min:0'],
            'kondisi_awal_rusak' => ['required', 'integer', 'min:0'],

            // D. Penghapusan Barang dan Bangunan
            'hapus_rusak' => ['required', 'integer', 'min:0'],
            'hapus_dijual' => ['required', 'integer', 'min:0'],
            'hapus_disumbangkan' => ['required', 'integer', 'min:0'],

            'tgl_hapus' => [
                Rule::requiredIf($hasDeletion),
                'nullable',
                'date',
            ],

            // E. Keadaan Akhir Tahun
            'kondisi_ahir_baik' => ['required', 'integer', 'min:0'],
            'kondisi_ahir_rusak' => ['required', 'integer', 'min:0'],

            // F. Dokumen dan Catatan
            'dokumen' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:10240',
            ],

            'catatan' => [
                'nullable',
                'string',
            ],
        ];
    }

    /**
     * Validate asset quantity calculations.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $asalFields = [
                'asal_beli',
                'asal_pemerintah',
                'asal_provinsi',
                'asal_kabupaten',
                'asal_sumbangan',
            ];

            $awalFields = [
                'kondisi_awal_baik',
                'kondisi_awal_rusak',
            ];

            $hapusFields = [
                'hapus_rusak',
                'hapus_dijual',
                'hapus_disumbangkan',
            ];

            $akhirFields = [
                'kondisi_ahir_baik',
                'kondisi_ahir_rusak',
            ];

            // Gabungkan seluruh field yang digunakan dalam perhitungan.
            $fields = array_merge(
                $asalFields,
                $awalFields,
                $hapusFields,
                $akhirFields
            );

            // Hentikan perhitungan jika validasi dasar gagal.
            foreach ($fields as $field) {
                if ($validator->errors()->has($field)) {
                    return;
                }
            }

            // 1. Hitung total asal barang.
            $totalAsal = collect($asalFields)->sum(
                fn($field) => (int) $this->input($field)
            );

            // 2. Hitung total kondisi awal.
            $totalAwal = collect($awalFields)->sum(
                fn($field) => (int) $this->input($field)
            );

            // 3. Hitung total penghapusan.
            $totalHapus = collect($hapusFields)->sum(
                fn($field) => (int) $this->input($field)
            );

            // 4. Hitung total kondisi akhir.
            $totalAkhir = collect($akhirFields)->sum(
                fn($field) => (int) $this->input($field)
            );

            // Validasi 1: Total asal harus sama dengan kondisi awal.
            if ($totalAsal !== $totalAwal) {
                $validator->errors()->add(
                    'kondisi_awal_baik',
                    "Total asal barang ({$totalAsal} unit) harus sama dengan total kondisi awal ({$totalAwal} unit)."
                );
            }

            // Validasi 2: Penghapusan tidak boleh melebihi kondisi awal.
            if ($totalHapus > $totalAwal) {
                $validator->errors()->add(
                    'hapus_rusak',
                    "Total penghapusan ({$totalHapus} unit) tidak boleh melebihi total aset awal ({$totalAwal} unit)."
                );

                return;
            }

            // 5. Hitung sisa aset setelah penghapusan.
            $sisaAset = $totalAwal - $totalHapus;

            // Validasi 3: Kondisi akhir harus sama dengan sisa aset.
            if ($totalAkhir !== $sisaAset) {
                $validator->errors()->add(
                    'kondisi_ahir_baik',
                    "Total kondisi akhir ({$totalAkhir} unit) harus sama dengan sisa aset ({$sisaAset} unit). Periksa jumlah kondisi baik dan rusak akhir tahun."
                );
            }
        });
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'nama_asset.required' => 'Jenis barang atau bangunan wajib diisi.',
            'nama_asset.string' => 'Jenis barang atau bangunan harus berupa teks.',
            'nama_asset.max' => 'Jenis barang atau bangunan maksimal 150 karakter.',

            '*.required' => 'Field :attribute wajib diisi.',
            '*.integer' => 'Field :attribute harus berupa bilangan bulat.',
            '*.min' => 'Jumlah :attribute tidak boleh kurang dari 0.',

            'tgl_hapus.required' => 'Tanggal penghapusan wajib diisi jika terdapat barang yang dihapus.',
            'tgl_hapus.date' => 'Tanggal penghapusan tidak valid.',

            'dokumen.file' => 'Dokumen inventaris harus berupa file.',
            'dokumen.mimes' => 'Dokumen inventaris harus berformat PDF.',
            'dokumen.max' => 'Ukuran dokumen inventaris maksimal 10 MB.',

            'catatan.string' => 'Keterangan harus berupa teks.',
        ];
    }

    /**
     * Get custom validation attributes.
     */
    public function attributes(): array
    {
        return [
            'nama_asset' => 'jenis barang/bangunan',

            'asal_beli' => 'jumlah dibeli sendiri',
            'asal_pemerintah' => 'jumlah bantuan pemerintah',
            'asal_provinsi' => 'jumlah bantuan provinsi',
            'asal_kabupaten' => 'jumlah bantuan kabupaten/kota',
            'asal_sumbangan' => 'jumlah sumbangan',

            'kondisi_awal_baik' => 'kondisi baik awal tahun',
            'kondisi_awal_rusak' => 'kondisi rusak awal tahun',

            'hapus_rusak' => 'jumlah barang rusak yang dihapus',
            'hapus_dijual' => 'jumlah barang dijual',
            'hapus_disumbangkan' => 'jumlah barang disumbangkan',
            'tgl_hapus' => 'tanggal penghapusan',

            'kondisi_ahir_baik' => 'kondisi baik akhir tahun',
            'kondisi_ahir_rusak' => 'kondisi rusak akhir tahun',

            'dokumen' => 'dokumen inventaris',
            'catatan' => 'keterangan',
        ];
    }
}
