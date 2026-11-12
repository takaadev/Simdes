<?php

namespace App\Http\Requests\Resident;

use App\Enums\CitizenshipList;
use App\Enums\EducationList;
use App\Enums\GenderList;
use App\Enums\MaritalStatusList;
use App\Enums\OccupationList;
use App\Enums\ReligionList;
use App\Enums\RelationshipList;
use App\Enums\RtList;
use App\Enums\RwList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreResidentRequest extends FormRequest
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
            // A. Identitas Penduduk
            'nama_lengkap' => [
                'required',
                'string',
                'max:150',
            ],
            'nik' => [
                'required',
                'digits:16',
                Rule::unique('residents', 'nik'),
            ],
            'jenis_kelamin' => [
                'required',
                Rule::enum(GenderList::class),
            ],
            'kk' => [
                'required',
                'digits:16',
                ...(
                    $this->input('status_dalam_keluarga') === RelationshipList::HEAD->value
                    ? [
                        Rule::unique('residents', 'kk')
                            ->where('status_dalam_keluarga', RelationshipList::HEAD->value)
                            ->withoutTrashed(),
                    ]
                    : []
                ),
            ],
            'tempat_lahir' => [
                'required',
                'string',
                'max:100',
            ],
            'tgl_lahir' => [
                'required',
                'date',
                'before:today',
            ],
            'status_perkawinan' => [
                'required',
                Rule::enum(MaritalStatusList::class),
            ],

            // B. Data Kependudukan
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
            'dapat_membaca' => [
                'required',
                'boolean',
            ],
            'kewarganegaraan' => [
                'required',
                Rule::enum(CitizenshipList::class),
            ],
            'kebangsaan' => [
                'required',
                'string',
                'max:100',
            ],
            'status_dalam_keluarga' => [
                'required',
                Rule::enum(RelationshipList::class),
            ],

            // C. Alamat Tempat Tinggal
            'rt' => [
                'nullable',
                Rule::enum(RtList::class),
            ],
            'rw' => [
                'nullable',
                Rule::enum(RwList::class),
            ],
            'nomor_rumah' => [
                'nullable',
                'string',
                'max:10',
            ],

            // D. Keterangan
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
            // A. Identitas Penduduk
            'nama_lengkap.required' => ':attribute wajib diisi.',
            'nama_lengkap.string' => ':attribute harus berupa teks.',
            'nama_lengkap.max' => ':attribute maksimal :max karakter.',

            'nik.required' => ':attribute wajib diisi.',
            'nik.digits' => ':attribute harus terdiri dari :digits digit.',
            'nik.unique' => ':attribute sudah terdaftar.',

            'jenis_kelamin.required' => ':attribute wajib dipilih.',
            'jenis_kelamin.enum' => 'Pilihan :attribute tidak valid.',

            'kk.required' => ':attribute wajib diisi.',
            'kk.digits' => ':attribute harus terdiri dari :digits digit.',
            'kk.unique' => 'Nomor KK ini sudah memiliki Kepala Keluarga.',

            'tempat_lahir.required' => ':attribute wajib diisi.',
            'tempat_lahir.string' => ':attribute harus berupa teks.',
            'tempat_lahir.max' => ':attribute maksimal :max karakter.',

            'tgl_lahir.required' => ':attribute wajib diisi.',
            'tgl_lahir.date' => ':attribute harus berupa tanggal yang valid.',
            'tgl_lahir.before' => ':attribute harus sebelum hari ini.',

            'status_perkawinan.required' => ':attribute wajib dipilih.',
            'status_perkawinan.enum' => 'Pilihan :attribute tidak valid.',

            // B. Data Kependudukan
            'agama.required' => ':attribute wajib dipilih.',
            'agama.enum' => 'Pilihan :attribute tidak valid.',

            'pendidikan.required' => ':attribute wajib dipilih.',
            'pendidikan.enum' => 'Pilihan :attribute tidak valid.',

            'pekerjaan.required' => ':attribute wajib dipilih.',
            'pekerjaan.enum' => 'Pilihan :attribute tidak valid.',

            'dapat_membaca.required' => ':attribute wajib dipilih.',
            'dapat_membaca.boolean' => 'Nilai :attribute tidak valid.',

            'kewarganegaraan.required' => ':attribute wajib dipilih.',
            'kewarganegaraan.enum' => 'Pilihan :attribute tidak valid.',

            'kebangsaan.required' => ':attribute wajib diisi.',
            'kebangsaan.string' => ':attribute harus berupa teks.',
            'kebangsaan.max' => ':attribute maksimal :max karakter.',

            'status_dalam_keluarga.required' => ':attribute wajib dipilih.',
            'status_dalam_keluarga.enum' => 'Pilihan :attribute tidak valid.',

            // C. Alamat Tempat Tinggal
            'rt.enum' => 'Pilihan :attribute tidak valid.',
            'rw.enum' => 'Pilihan :attribute tidak valid.',

            'nomor_rumah.string' => ':attribute harus berupa teks.',
            'nomor_rumah.max' => ':attribute maksimal :max karakter.',

            // D. Keterangan
            'catatan.string' => ':attribute harus berupa teks.',
        ];
    }

    /**
     * Get custom attribute names.
     */
    public function attributes(): array
    {
        return [
            // A. Identitas Penduduk
            'nama_lengkap' => 'nama lengkap',
            'nik' => 'NIK',
            'jenis_kelamin' => 'jenis kelamin',
            'kk' => 'nomor kartu keluarga',
            'tempat_lahir' => 'tempat lahir',
            'tgl_lahir' => 'tanggal lahir',
            'status_perkawinan' => 'status perkawinan',

            // B. Data Kependudukan
            'agama' => 'agama',
            'pendidikan' => 'pendidikan terakhir',
            'pekerjaan' => 'pekerjaan',
            'dapat_membaca' => 'kemampuan membaca',
            'kewarganegaraan' => 'kewarganegaraan',
            'kebangsaan' => 'kebangsaan',
            'status_dalam_keluarga' => 'status dalam keluarga',

            // C. Alamat Tempat Tinggal
            'rt' => 'RT',
            'rw' => 'RW',
            'nomor_rumah' => 'nomor rumah',

            // D. Keterangan
            'catatan' => 'catatan',
        ];
    }
}
