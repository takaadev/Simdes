<?php

namespace App\Http\Requests\FamilyCard;

use App\Models\FamilyCard;
use App\Models\Resident;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class MutationFamilyCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nik' => [
                'required',
                'string',
                'max_digits:16',
            ],

            'kk_tujuan' => [
                'required',
                'string',
                'max_digits:16',
            ],

            'hubungan' => [
                'required',
                'string',
            ],

            'tgl_mutasi' => [
                'required',
                'date',
            ],

            'catatan' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            // Hentikan pemeriksaan jika validasi dasar gagal.
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            /*
             * 1. Periksa NIK penduduk.
             */
            $resident = Resident::query()
                ->where('nik', $this->input('nik'))
                ->first();

            if (!$resident) {
                $validator->errors()->add(
                    'nik',
                    'NIK penduduk belum terdaftar. Silakan daftarkan penduduk melalui Buku Induk Penduduk (BIP) terlebih dahulu.'
                );

                return;
            }

            /*
             * 2. Periksa status kepala keluarga.
             */
            if (
                $resident->status_dalam_keluarga === 'Kepala Keluarga'
            ) {
                $validator->errors()->add(
                    'nik',
                    'Penduduk masih berstatus Kepala Keluarga. Silakan ubah hubungan dalam keluarga melalui Buku Induk Penduduk (BIP) terlebih dahulu sebelum melakukan mutasi.'
                );

                return;
            }

            /*
             * 3. Pastikan penduduk memiliki KK asal.
             */
            if (!$resident->family_card_id) {
                $validator->errors()->add(
                    'nik',
                    'Penduduk belum terhubung dengan Kartu Keluarga. Lengkapi data penduduk melalui Buku Induk Penduduk (BIP) terlebih dahulu.'
                );

                return;
            }

            /*
             * 4. Periksa KK tujuan.
             */
            $familyCard = FamilyCard::query()
                ->where('nomor_kk', $this->input('kk_tujuan'))
                ->first();

            if (!$familyCard) {
                $validator->errors()->add(
                    'kk_tujuan',
                    'Nomor KK tujuan belum terdaftar. Silakan daftarkan Kartu Keluarga melalui menu Buku Kartu Keluarga terlebih dahulu.'
                );

                return;
            }

            /*
             * 5. Pastikan KK asal dan tujuan berbeda.
             */
            if ($resident->family_card_id === $familyCard->id) {
                $validator->errors()->add(
                    'kk_tujuan',
                    'Penduduk sudah terdaftar dalam Kartu Keluarga tersebut.'
                );

                return;
            }

            /*
             * 6. Periksa apakah KK tujuan sudah memiliki kepala keluarga.
             */
            $hasHead = Resident::query()
                ->where('family_card_id', $familyCard->id)
                ->where('status_dalam_keluarga', 'Kepala Keluarga')
                ->exists();

            /*
             * 7. Kepala Keluarga hanya boleh dipilih jika
             *    KK tujuan belum memiliki kepala keluarga.
             */
            if (
                $this->input('hubungan') === 'Kepala Keluarga'
                && $hasHead
            ) {
                $validator->errors()->add(
                    'hubungan',
                    'Kartu Keluarga tujuan sudah memiliki Kepala Keluarga. Silakan pilih hubungan keluarga yang lain.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'nik.required' => 'NIK penduduk wajib diisi.',
            'nik.string' => 'NIK penduduk harus berupa teks.',
            'nik.digits' => 'NIK penduduk harus terdiri dari 16 digit.',

            'kk_tujuan.required' => 'Nomor KK tujuan wajib diisi.',
            'kk_tujuan.string' => 'Nomor KK tujuan harus berupa teks.',
            'kk_tujuan.digits' => 'Nomor KK tujuan harus terdiri dari 16 digit.',

            'hubungan.required' => 'Hubungan dalam keluarga wajib dipilih.',
            'hubungan.string' => 'Hubungan dalam keluarga tidak valid.',

            'tgl_mutasi.required' => 'Tanggal mutasi wajib diisi.',
            'tgl_mutasi.date' => 'Tanggal mutasi tidak valid.',

            'catatan.string' => 'Catatan harus berupa teks.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nik' => 'NIK penduduk',
            'kk_tujuan' => 'Nomor KK tujuan',
            'hubungan' => 'hubungan dalam keluarga',
            'tgl_mutasi' => 'tanggal mutasi',
            'catatan' => 'catatan',
        ];
    }
}