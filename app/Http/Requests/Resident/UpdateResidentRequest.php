<?php

namespace App\Http\Requests\Resident;

use App\Enums\BloodList;
use App\Enums\CitizenshipList;
use App\Enums\EducationList;
use App\Enums\GenderList;
use App\Enums\MaritalStatusList;
use App\Enums\OccupationList;
use App\Enums\ReligionList;
use App\Enums\RelationshipList;
use App\Enums\RtList;
use App\Enums\RwList;
use App\Models\Resident;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateResidentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    protected function resident(): Resident
    {
        return $this->route('resident');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $resident = $this->resident();

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
                Rule::unique('residents', 'nik')
                    ->ignore($resident->id),
            ],
            'kk' => [
                'required',
                'digits:16',
                ...(
                    $this->input('status_dalam_keluarga') === RelationshipList::HEAD->value
                    ? [
                        Rule::unique('residents', 'kk')
                            ->where(
                                'status_dalam_keluarga',
                                RelationshipList::HEAD->value
                            )
                            ->ignore($resident->id)
                            ->withoutTrashed(),
                    ]
                    : []
                ),
            ],
            'jenis_kelamin' => [
                'required',
                Rule::enum(GenderList::class),
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
            'gol_darah' => [
                'nullable',
                Rule::enum(BloodList::class),
            ],
            'ayah' => [
                'nullable',
                'string',
                'max:150',
            ],
            'ibu' => [
                'nullable',
                'string',
                'max:150',
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
            'alamat' => [
                'nullable',
                'string',
            ],

            // D. Data Penerbitan KTP
            'tempat_dikeluarkan' => [
                'nullable',
                'string',
                'max:100',
            ],
            'tgl_dikeluarkan' => [
                'nullable',
                'date',
            ],
            'tgl_mulai_tinggal' => [
                'nullable',
                'date',
                'before_or_equal:today',
            ],

            // E. Keterangan
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

            'kk.required' => ':attribute wajib diisi.',
            'kk.digits' => ':attribute harus terdiri dari :digits digit.',
            'kk.unique' => 'Nomor KK ini sudah memiliki Kepala Keluarga.',

            'jenis_kelamin.required' => ':attribute wajib dipilih.',
            'jenis_kelamin.enum' => 'Pilihan :attribute tidak valid.',

            'tempat_lahir.required' => ':attribute wajib diisi.',
            'tempat_lahir.string' => ':attribute harus berupa teks.',
            'tempat_lahir.max' => ':attribute maksimal :max karakter.',

            'tgl_lahir.required' => ':attribute wajib diisi.',
            'tgl_lahir.date' => ':attribute harus berupa tanggal yang valid.',
            'tgl_lahir.before_or_equal' => ':attribute tidak boleh melebihi hari ini.',

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

            'gol_darah.enum' => 'Pilihan :attribute tidak valid.',

            'ayah.string' => ':attribute harus berupa teks.',
            'ayah.max' => ':attribute maksimal :max karakter.',

            'ibu.string' => ':attribute harus berupa teks.',
            'ibu.max' => ':attribute maksimal :max karakter.',

            // C. Alamat Tempat Tinggal
            'rt.enum' => 'Pilihan :attribute tidak valid.',
            'rw.enum' => 'Pilihan :attribute tidak valid.',
            'alamat.string' => ':attribute harus berupa teks.',

            // D. Data Penerbitan KTP
            'tempat_dikeluarkan.string' => ':attribute harus berupa teks.',
            'tempat_dikeluarkan.max' => ':attribute maksimal :max karakter.',

            'tgl_dikeluarkan.date' => ':attribute harus berupa tanggal yang valid.',

            'tgl_mulai_tinggal.date' => ':attribute harus berupa tanggal yang valid.',
            'tgl_mulai_tinggal.before_or_equal' => ':attribute tidak boleh melebihi hari ini.',

            // E. Keterangan
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
            'kk' => 'nomor kartu keluarga',
            'jenis_kelamin' => 'jenis kelamin',
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
            'gol_darah' => 'golongan darah',
            'ayah' => 'nama ayah',
            'ibu' => 'nama ibu',

            // C. Alamat Tempat Tinggal
            'rt' => 'RT',
            'rw' => 'RW',
            'alamat' => 'alamat',

            // D. Data Penerbitan KTP
            'tempat_dikeluarkan' => 'tempat dikeluarkan',
            'tgl_dikeluarkan' => 'tanggal dikeluarkan',
            'tgl_mulai_tinggal' => 'tanggal mulai tinggal',

            // E. Keterangan
            'catatan' => 'catatan',
        ];
    }
}
