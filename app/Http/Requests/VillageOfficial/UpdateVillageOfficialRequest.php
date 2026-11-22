<?php

namespace App\Http\Requests\VillageOfficial;

use App\Enums\EducationList;
use App\Enums\GenderList;
use App\Enums\ReligionList;
use App\Enums\VillageOfficialStatusList;
use App\Models\VillageOfficial;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVillageOfficialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var VillageOfficial $villageOfficial */
        $villageOfficial = $this->route('villageOfficial');

        return [
            // A. Identitas Aparat
            'nama' => [
                'required',
                'string',
                'max:150',
            ],

            'niap' => [
                'required',
                'string',
                'max:16',
                Rule::unique('village_officials', 'niap')
                    ->ignore($villageOfficial->id),
            ],

            'nip' => [
                'nullable',
                'string',
                'max:16',
                Rule::unique('village_officials', 'nip')
                    ->ignore($villageOfficial->id),
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

            'agama' => [
                'required',
                Rule::enum(ReligionList::class),
            ],

            // B. Pendidikan, Kepangkatan, dan Jabatan
            'pendidikan' => [
                'required',
                Rule::enum(EducationList::class),
            ],

            'pangkat_golongan' => [
                'nullable',
                'string',
                'max:16',
            ],

            'village_position_id' => [
                'required',
                'integer',
                Rule::exists('village_positions', 'id')
                    ->where('is_active', true)
                    ->whereNull('deleted_at'),
                Rule::unique('village_officials', 'village_position_id')
                    ->where('status', VillageOfficialStatusList::ACTIVE->value)
                    ->whereNull('deleted_at')
                    ->ignore($villageOfficial->id)
                    ->when(
                        $this->input('status') !== VillageOfficialStatusList::ACTIVE->value,
                        fn($rule) => null
                    ),
            ],

            // C. Keputusan Pengangkatan
            'nomor_pengangkatan' => [
                'required',
                'string',
                'max:100',
            ],

            'tgl_pengangkatan' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            // D. Status dan Kontak
            'status' => [
                'required',
                Rule::enum(VillageOfficialStatusList::class),
            ],

            'telpon' => [
                'required',
                'string',
                'max:30',
                Rule::unique('village_officials', 'telpon')
                    ->ignore($villageOfficial->id),
            ],

            'alamat' => [
                'required',
                'string',
            ],

            // E. Keterangan
            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama lengkap aparat desa wajib diisi.',
            'nama.max' => 'Nama aparat desa maksimal 150 karakter.',

            'niap.required' => 'NIAP wajib diisi.',
            'niap.max' => 'NIAP maksimal 16 karakter.',
            'niap.unique' => 'NIAP sudah digunakan.',

            'nip.max' => 'NIP maksimal 16 karakter.',
            'nip.unique' => 'NIP sudah digunakan.',

            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',

            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tempat_lahir.max' => 'Tempat lahir maksimal 100 karakter.',

            'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tgl_lahir.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini.',

            'agama.required' => 'Agama wajib dipilih.',

            'pendidikan.required' => 'Pendidikan terakhir wajib dipilih.',

            'pangkat_golongan.max' => 'Pangkat atau golongan maksimal 16 karakter.',

            'village_position_id.required' => 'Jabatan wajib dipilih.',
            'village_position_id.exists' => 'Jabatan yang dipilih tidak tersedia atau sudah tidak aktif.',
            'village_position_id.unique' => 'Jabatan tersebut sudah digunakan oleh aparat desa lain yang masih aktif.',

            'nomor_pengangkatan.required' => 'Nomor keputusan pengangkatan wajib diisi.',
            'nomor_pengangkatan.max' => 'Nomor keputusan pengangkatan maksimal 100 karakter.',

            'tgl_pengangkatan.required' => 'Tanggal keputusan pengangkatan wajib diisi.',
            'tgl_pengangkatan.before_or_equal' => 'Tanggal pengangkatan tidak boleh melebihi hari ini.',

            'status.required' => 'Status aparat wajib dipilih.',

            'telpon.required' => 'Nomor telepon wajib diisi.',
            'telpon.max' => 'Nomor telepon maksimal 30 karakter.',
            'telpon.unique' => 'Nomor telepon sudah digunakan.',

            'alamat.required' => 'Alamat wajib diisi.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nama' => 'nama lengkap',
            'niap' => 'NIAP',
            'nip' => 'NIP',
            'jenis_kelamin' => 'jenis kelamin',
            'tempat_lahir' => 'tempat lahir',
            'tgl_lahir' => 'tanggal lahir',
            'agama' => 'agama',
            'pendidikan' => 'pendidikan terakhir',
            'pangkat_golongan' => 'pangkat/golongan',
            'village_position_id' => 'jabatan',
            'nomor_pengangkatan' => 'nomor keputusan pengangkatan',
            'tgl_pengangkatan' => 'tanggal keputusan pengangkatan',
            'status' => 'status aparat',
            'telpon' => 'nomor telepon',
            'alamat' => 'alamat',
            'notes' => 'catatan',
        ];
    }
}
