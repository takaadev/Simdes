<?php

namespace App\Http\Requests\VillageOfficial;

use App\Enums\EducationList;
use App\Enums\GenderList;
use App\Enums\ReligionList;
use App\Enums\RoleAccessType;
use App\Enums\VillageOfficialStatusList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVillageOfficialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isActive = $this->input('status') === VillageOfficialStatusList::ACTIVE->value;

        $positionRules = [
            'required',
            'integer',
            Rule::exists('village_positions', 'id')
                ->where('is_active', true)
                ->whereNull('deleted_at'),
        ];

        // Jabatan hanya boleh dimiliki satu aparat aktif.
        if ($isActive) {
            $positionRules[] = Rule::unique(
                'village_officials',
                'village_position_id'
            )
                ->where('status', VillageOfficialStatusList::ACTIVE->value)
                ->whereNull('deleted_at');
        }

        return [
            // A. Identitas Aparat
            'nama' => [
                'required',
                'string',
                'max:150',
            ],

            'pangkat_golongan' => [
                'nullable',
                'string',
                'max:16',
            ],

            'niap' => [
                'required',
                'string',
                'max:16',
                Rule::unique('village_officials', 'niap'),
            ],

            'nip' => [
                'nullable',
                'string',
                'max:16',
                Rule::unique('village_officials', 'nip'),
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

            'pendidikan' => [
                'required',
                Rule::enum(EducationList::class),
            ],

            // B. Jabatan
            'village_position_id' => $positionRules,

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

            // D. Status Aparat
            'status' => [
                'required',
                Rule::enum(VillageOfficialStatusList::class),
            ],

            // E. Kontak
            'telpon' => [
                'required',
                'string',
                'max:30',
                Rule::unique('village_officials', 'telpon'),
            ],

            'alamat' => [
                'required',
                'string',
            ],

            // F. Catatan
            'notes' => [
                'nullable',
                'string',
            ],

            // G. Akun Login
            'has_user' => [
                'required',
                'boolean',
            ],

            'role' => [
                'required_if:has_user,1',
                'nullable',
                Rule::enum(RoleAccessType::class),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // Identitas Aparat
            'nama.required' => 'Nama lengkap aparat desa wajib diisi.',
            'nama.max' => 'Nama aparat desa maksimal 150 karakter.',

            'niap.required' => 'NIAP wajib diisi.',
            'niap.max' => 'NIAP maksimal 16 karakter.',
            'niap.unique' => 'NIAP sudah digunakan.',

            'nip.max' => 'NIP maksimal 16 karakter.',
            'nip.unique' => 'NIP sudah digunakan.',

            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'jenis_kelamin.enum' => 'Jenis kelamin yang dipilih tidak valid.',

            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tempat_lahir.max' => 'Tempat lahir maksimal 100 karakter.',

            'tgl_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tgl_lahir.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini.',

            'agama.required' => 'Agama wajib dipilih.',
            'agama.enum' => 'Agama yang dipilih tidak valid.',

            'pendidikan.required' => 'Pendidikan terakhir wajib dipilih.',
            'pendidikan.enum' => 'Pendidikan terakhir yang dipilih tidak valid.',

            // Jabatan
            'village_position_id.required' => 'Jabatan wajib dipilih.',
            'village_position_id.exists' => 'Jabatan yang dipilih tidak tersedia atau sudah tidak aktif.',
            'village_position_id.unique' => 'Jabatan tersebut sudah dimiliki oleh aparat desa yang masih aktif.',

            'pangkat_golongan.max' => 'Pangkat/golongan maksimal 16 karakter.',

            // Pengangkatan
            'nomor_pengangkatan.required' => 'Nomor keputusan pengangkatan wajib diisi.',
            'nomor_pengangkatan.max' => 'Nomor keputusan pengangkatan maksimal 100 karakter.',

            'tgl_pengangkatan.required' => 'Tanggal keputusan pengangkatan wajib diisi.',
            'tgl_pengangkatan.before_or_equal' => 'Tanggal pengangkatan tidak boleh melebihi hari ini.',

            // Status
            'status.required' => 'Status aparat wajib dipilih.',
            'status.enum' => 'Status aparat yang dipilih tidak valid.',

            // Kontak
            'telpon.required' => 'Nomor telepon wajib diisi.',
            'telpon.max' => 'Nomor telepon maksimal 30 karakter.',
            'telpon.unique' => 'Nomor telepon sudah digunakan.',

            'alamat.required' => 'Alamat wajib diisi.',

            // Akun Login
            'has_user.required' => 'Status pembuatan akun login wajib ditentukan.',
            'has_user.boolean' => 'Status pembuatan akun login tidak valid.',

            'role.required_if' => 'Hak akses wajib dipilih jika aparat dijadikan akun login.',
            'role.enum' => 'Hak akses yang dipilih tidak valid.',
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
            'village_position_id' => 'jabatan',
            'pangkat_golongan' => 'pangkat/golongan',
            'nomor_pengangkatan' => 'nomor keputusan pengangkatan',
            'tgl_pengangkatan' => 'tanggal keputusan pengangkatan',
            'status' => 'status aparat',
            'telpon' => 'nomor telepon',
            'alamat' => 'alamat',
            'notes' => 'catatan',
            'has_user' => 'akun login',
            'role' => 'hak akses',
        ];
    }
}
