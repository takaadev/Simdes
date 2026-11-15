<?php

namespace App\Http\Requests\ResidentMutation;

use App\Enums\EducationList;
use App\Enums\MaritalStatusList;
use App\Enums\OccupationList;
use App\Enums\RelationshipList;
use App\Enums\ReligionList;
use App\Enums\RtList;
use App\Enums\RwList;
use App\Enums\MutationTypeList;
use App\Models\FamilyCard;
use App\Models\ResidentMutation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMutationToResidentRequest extends FormRequest
{
    /**
     * Ambil data mutasi yang dipilih.
     */
    protected function selectedMutation(): ?ResidentMutation
    {
        if (!$this->filled('mutation_id')) {
            return null;
        }

        return ResidentMutation::query()
            ->where('tipe_mutasi', MutationTypeList::ADD->value)
            ->whereNull('resident_id')
            ->find($this->input('mutation_id'));
    }

    /**
     * Persiapkan data sebelum validasi.
     */
    protected function prepareForValidation(): void
    {
        $mutation = $this->selectedMutation();

        // Jika nomor identitas pada mutasi tersedia,
        // gunakan sebagai NIK apabila NIK belum diisi.
        if ($mutation && blank($this->input('nik')) && filled($mutation->nomor_identitas)) {
            $this->merge([
                'nik' => $mutation->nomor_identitas,
            ]);
        }

        // Jika nomor KK belum terdaftar, otomatis jadikan
        // penduduk sebagai Kepala Keluarga.
        if (
            $this->filled('kk') &&
            !FamilyCard::withTrashed()
                ->where('nomor_kk', $this->input('kk'))
                ->exists()
        ) {
            $this->merge([
                'status_dalam_keluarga' => RelationshipList::HEAD->value,
            ]);
        }
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mutation_id' => [
                'required',
                'integer',
                Rule::exists('resident_mutations', 'id')
                    ->where('tipe_mutasi', MutationTypeList::ADD->value)
                    ->whereNull('resident_id')
                    ->whereNull('deleted_at'),
            ],

            'nik' => [
                'required',
                'string',
                'digits:16',
                Rule::unique('residents', 'nik'),
            ],

            'kk' => [
                'required',
                'string',
                'digits:16',
            ],

            'status_perkawinan' => [
                'required',
                Rule::enum(MaritalStatusList::class),
            ],

            'agama' => [
                'required',
                Rule::enum(ReligionList::class),
            ],

            'pendidikan' => [
                'required',
                Rule::enum(EducationList::class),
            ],

            'pekerjaan' => [
                'required',
                Rule::enum(OccupationList::class),
            ],

            'status_dalam_keluarga' => [
                'required',
                Rule::enum(RelationshipList::class),

                // Satu KK hanya boleh memiliki satu Kepala Keluarga.
                Rule::unique('residents', 'kk')
                    ->where('status_dalam_keluarga', RelationshipList::HEAD->value)
                    ->withoutTrashed()
                    ->ignore(null),
            ],

            'dapat_membaca' => [
                'required',
                'boolean',
            ],

            'rt' => [
                'nullable',
                Rule::enum(RtList::class),
            ],

            'rw' => [
                'nullable',
                Rule::enum(RwList::class),
            ],
            'alamat' => [
                'nullable',
                'string',
                'max:65535',
            ],

            'catatan' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'mutation_id.required' => 'Data mutasi penduduk wajib dipilih.',
            'mutation_id.integer' => 'Data mutasi penduduk tidak valid.',
            'mutation_id.exists' => 'Data mutasi tidak ditemukan, bukan mutasi penambahan, atau sudah masuk Buku Induk Penduduk.',

            'nik.required' => 'NIK wajib diisi apabila nomor identitas pada data mutasi kosong.',
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'nik.unique' => 'NIK sudah terdaftar dalam Buku Induk Penduduk.',

            'kk.required' => 'Nomor Kartu Keluarga wajib diisi.',
            'kk.digits' => 'Nomor Kartu Keluarga harus terdiri dari 16 digit angka.',

            'status_perkawinan.required' => 'Status perkawinan wajib dipilih.',
            'status_perkawinan.enum' => 'Status perkawinan yang dipilih tidak valid.',

            'agama.required' => 'Agama wajib dipilih.',
            'agama.enum' => 'Agama yang dipilih tidak valid.',

            'pendidikan.required' => 'Pendidikan terakhir wajib dipilih.',
            'pendidikan.enum' => 'Pendidikan terakhir yang dipilih tidak valid.',

            'pekerjaan.required' => 'Pekerjaan wajib dipilih.',
            'pekerjaan.enum' => 'Pekerjaan yang dipilih tidak valid.',

            'status_dalam_keluarga.required' => 'Status dalam keluarga wajib dipilih.',
            'status_dalam_keluarga.enum' => 'Status dalam keluarga yang dipilih tidak valid.',
            'status_dalam_keluarga.unique' => 'Nomor KK tersebut sudah memiliki Kepala Keluarga.',

            'dapat_membaca.required' => 'Kemampuan membaca wajib dipilih.',
            'dapat_membaca.boolean' => 'Pilihan kemampuan membaca tidak valid.',

            'rt.enum' => 'RT yang dipilih tidak valid.',
            'rw.enum' => 'RW yang dipilih tidak valid.',
            'alamat.string' => 'Alamat harus berupa teks.',
            'alamat.max' => 'Alamat terlalu panjang.',

        ];
    }

    public function attributes(): array
    {
        return [
            'mutation_id' => 'data mutasi',
            'nik' => 'NIK',
            'kk' => 'nomor Kartu Keluarga',
            'status_perkawinan' => 'status perkawinan',
            'agama' => 'agama',
            'pendidikan' => 'pendidikan terakhir',
            'pekerjaan' => 'pekerjaan',
            'status_dalam_keluarga' => 'status dalam keluarga',
            'dapat_membaca' => 'kemampuan membaca',
            'rt' => 'RT',
            'rw' => 'RW',
            'alamat' => 'alamat',
            'catatan' => 'catatan',
        ];
    }
}
