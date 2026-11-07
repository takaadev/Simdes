<?php

namespace App\Http\Requests\VillageOfficial;

use App\Enums\EducationList;
use App\Enums\GenderList;
use App\Enums\VillageOfficialStatusList;
use App\Models\VillageOfficial;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVillageOfficialRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
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
                    ->ignore($villageOfficial),
            ],

            'nip' => [
                'nullable',
                'string',
                'max:16',
                Rule::unique('village_officials', 'nip')
                    ->ignore($villageOfficial),
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

            // B. Pendidikan, Kepangkatan, dan Jabatan
            'pendidikan' => [
                'required',
                Rule::enum(EducationList::class),
            ],

            'pangkat_golongan' => [
                'nullable',
                'string',
                'max:100',
            ],

            'village_position_id' => [
                'required',
                'integer',
                Rule::exists('village_positions', 'id')
                    ->where('is_active', true)
                    ->whereNull('deleted_at'),
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

            // D. Keputusan Pemberhentian
            'nomor_pemberhentian' => [
                'nullable',
                'string',
                'max:100',
                'required_with:tgl_pemberhentian',
            ],

            'tgl_pemberhentian' => [
                'nullable',
                'date',
                'after_or_equal:tgl_pengangkatan',
                'required_with:nomor_pemberhentian',
            ],

            // E. Status dan Kontak
            'status' => [
                'required',
                Rule::enum(VillageOfficialStatusList::class),
            ],

            'telpon' => [
                'required',
                'string',
                'max:30',
                Rule::unique('village_officials', 'telpon')
                    ->ignore($villageOfficial),
            ],

            'alamat' => [
                'required',
                'string',
            ],

            // F. Keterangan
            'notes' => [
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
            // Identitas Aparat
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

            // Pendidikan, Kepangkatan, dan Jabatan
            'pendidikan.required' => 'Pendidikan terakhir wajib dipilih.',
            'pangkat_golongan.max' => 'Pangkat atau golongan maksimal 100 karakter.',

            'village_position_id.required' => 'Jabatan wajib dipilih.',
            'village_position_id.exists' => 'Jabatan yang dipilih tidak tersedia atau sudah tidak aktif.',

            // Keputusan Pengangkatan
            'nomor_pengangkatan.required' => 'Nomor keputusan pengangkatan wajib diisi.',
            'nomor_pengangkatan.max' => 'Nomor keputusan pengangkatan maksimal 100 karakter.',

            'tgl_pengangkatan.required' => 'Tanggal keputusan pengangkatan wajib diisi.',
            'tgl_pengangkatan.before_or_equal' => 'Tanggal pengangkatan tidak boleh melebihi hari ini.',

            // Keputusan Pemberhentian
            'nomor_pemberhentian.required_with' => 'Nomor keputusan pemberhentian wajib diisi jika tanggal pemberhentian diisi.',
            'tgl_pemberhentian.required_with' => 'Tanggal keputusan pemberhentian wajib diisi jika nomor pemberhentian diisi.',
            'tgl_pemberhentian.after_or_equal' => 'Tanggal pemberhentian tidak boleh sebelum tanggal pengangkatan.',

            // Status dan Kontak
            'status.required' => 'Status aparat wajib dipilih.',

            'telpon.required' => 'Nomor telepon wajib diisi.',
            'telpon.max' => 'Nomor telepon maksimal 30 karakter.',
            'telpon.unique' => 'Nomor telepon sudah digunakan.',

            'alamat.required' => 'Alamat wajib diisi.',
        ];
    }

    /**
     * Get custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'nama' => 'nama lengkap',
            'niap' => 'NIAP',
            'nip' => 'NIP',
            'jenis_kelamin' => 'jenis kelamin',
            'tempat_lahir' => 'tempat lahir',
            'tgl_lahir' => 'tanggal lahir',
            'pendidikan' => 'pendidikan terakhir',
            'pangkat_golongan' => 'pangkat/golongan',
            'village_position_id' => 'jabatan',
            'nomor_pengangkatan' => 'nomor keputusan pengangkatan',
            'tgl_pengangkatan' => 'tanggal keputusan pengangkatan',
            'nomor_pemberhentian' => 'nomor keputusan pemberhentian',
            'tgl_pemberhentian' => 'tanggal keputusan pemberhentian',
            'status' => 'status aparat',
            'telpon' => 'nomor telepon',
            'alamat' => 'alamat',
            'notes' => 'catatan',
        ];
    }
}
