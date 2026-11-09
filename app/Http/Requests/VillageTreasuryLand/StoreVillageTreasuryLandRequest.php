<?php

namespace App\Http\Requests\VillageTreasuryLand;

use App\Enums\LandClassList;
use App\Enums\RegionLandList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreVillageTreasuryLandRequest extends FormRequest
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
            // A. Identitas Tanah
            'asal_tanah' => [
                'required',
                Rule::enum(RegionLandList::class),
            ],

            'nomor_sertifikat' => [
                'required',
                'string',
                'max:100',
                'unique:village_treasury_lands,nomor_sertifikat',
            ],

            'kelas_tanah' => [
                'required',
                Rule::enum(LandClassList::class),
            ],

            'tgl_perolehan' => [
                'required',
                'date',
            ],

            // B. Luas Berdasarkan Asal Perolehan
            'lt_asli_desa' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'lt_pemerintah' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'lt_provinsi' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'lt_kabupaten' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'lt_lainnya' => ['required', 'numeric', 'min:0', 'decimal:0,2'],

            // C. Luas Berdasarkan Jenis Tanah
            'lt_tipe_sawah' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'lt_tipe_tegal' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'lt_tipe_kebun' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'lt_tipe_tambak' => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'lt_tipe_kering' => ['required', 'numeric', 'min:0', 'decimal:0,2'],

            // D. Kelengkapan Tanah
            'patok_tanah' => [
                'required',
                'boolean',
            ],

            'papan_nama' => [
                'required',
                'boolean',
            ],

            // E. Lokasi dan Peruntukan
            'lokasi' => [
                'required',
                'string',
            ],

            'peruntukan' => [
                'nullable',
                'string',
                'max:255',
            ],

            // F. Mutasi, Dokumen dan Catatan
            'mutasi' => [
                'nullable',
                'string',
            ],

            'dokumen' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:5120',
            ],

            'catatan' => [
                'nullable',
                'string',
            ],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $sourceFields = [
                'lt_asli_desa',
                'lt_pemerintah',
                'lt_provinsi',
                'lt_kabupaten',
                'lt_lainnya',
            ];

            $typeFields = [
                'lt_tipe_sawah',
                'lt_tipe_tegal',
                'lt_tipe_kebun',
                'lt_tipe_tambak',
                'lt_tipe_kering',
            ];

            // Jangan jalankan validasi perhitungan jika
            // terdapat input yang tidak valid.
            $allFields = array_merge($sourceFields, $typeFields);

            foreach ($allFields as $field) {
                if (
                    $validator->errors()->has($field) ||
                    !$this->has($field) ||
                    !is_numeric($this->input($field)) ||
                    (float) $this->input($field) < 0
                ) {
                    return;
                }
            }

            // Konversi ke satuan sen (0.01 m²)
            // untuk menghindari masalah presisi floating point.
            $toCents = fn ($value) => (int) round((float) $value * 100);

            $totalSource = collect($sourceFields)
                ->sum(fn ($field) => $toCents($this->input($field)));

            $totalType = collect($typeFields)
                ->sum(fn ($field) => $toCents($this->input($field)));

            // Minimal satu sumber perolehan harus diisi.
            if ($totalSource <= 0) {
                $validator->errors()->add(
                    'lt_asli_desa',
                    'Minimal salah satu sumber perolehan tanah harus memiliki luas lebih dari 0.'
                );
            }

            // Minimal satu jenis tanah harus diisi.
            if ($totalType <= 0) {
                $validator->errors()->add(
                    'lt_tipe_sawah',
                    'Minimal salah satu jenis tanah harus memiliki luas lebih dari 0.'
                );
            }

            // Total pembagian jenis tanah harus sama
            // dengan total luas berdasarkan asal perolehan.
            if ($totalSource > 0 && $totalType > 0 && $totalType !== $totalSource) {
                $difference = abs($totalSource - $totalType) / 100;

                if ($totalType > $totalSource) {
                    $message = 'Total pembagian jenis tanah melebihi total luas tanah sebesar '
                        . number_format($difference, 2, ',', '.') . ' m².';
                } else {
                    $message = 'Total pembagian jenis tanah masih kurang '
                        . number_format($difference, 2, ',', '.') . ' m² dari total luas tanah.';
                }

                $validator->errors()->add('lt_tipe_sawah', $message);
            }
        });
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'asal_tanah.required' => 'Asal tanah wajib dipilih.',
            'asal_tanah.enum' => 'Asal tanah yang dipilih tidak valid.',

            'nomor_sertifikat.required' => 'Nomor sertifikat wajib diisi.',
            'nomor_sertifikat.unique' => 'Nomor sertifikat sudah terdaftar.',

            'kelas_tanah.required' => 'Kelas tanah wajib dipilih.',
            'kelas_tanah.enum' => 'Kelas tanah yang dipilih tidak valid.',

            'tgl_perolehan.required' => 'Tanggal perolehan wajib diisi.',
            'tgl_perolehan.date' => 'Tanggal perolehan tidak valid.',

            '*.required' => 'Bagian ini wajib diisi.',
            '*.numeric' => 'Nilai harus berupa angka.',
            '*.min' => 'Luas tanah tidak boleh kurang dari 0.',
            '*.decimal' => 'Luas tanah maksimal menggunakan 2 angka desimal.',

            'patok_tanah.boolean' => 'Pilihan patok tanah tidak valid.',
            'papan_nama.boolean' => 'Pilihan papan nama tidak valid.',

            'lokasi.required' => 'Lokasi tanah wajib diisi.',
            'peruntukan.max' => 'Peruntukan maksimal 255 karakter.',

            'dokumen.file' => 'Dokumen yang diunggah tidak valid.',
            'dokumen.mimes' => 'Dokumen harus berformat PDF.',
            'dokumen.max' => 'Ukuran dokumen maksimal 5 MB.',
        ];
    }
}
