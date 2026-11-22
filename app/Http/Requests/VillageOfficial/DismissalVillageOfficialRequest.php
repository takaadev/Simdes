<?php

namespace App\Http\Requests\VillageOfficial;

use App\Enums\VillageOfficialStatusList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DismissalVillageOfficialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'official_id' => [
                'required',
                'integer',
                Rule::exists('village_officials', 'id')
                    ->where('status', VillageOfficialStatusList::ACTIVE->value)
                    ->whereNull('deleted_at'),
            ],

            'nomor_pemberhentian' => [
                'required',
                'string',
                'max:100',
            ],

            'tgl_pemberhentian' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'status' => [
                'required',
                'string',
                Rule::enum(VillageOfficialStatusList::class),
                Rule::notIn([VillageOfficialStatusList::ACTIVE->value]),
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'official_id.required' => 'Aparat desa wajib dipilih.',
            'official_id.integer' => 'Pilihan aparat desa tidak valid.',
            'official_id.exists' => 'Aparat desa tidak ditemukan atau sudah tidak aktif.',

            'nomor_pemberhentian.required' => 'Nomor keputusan pemberhentian wajib diisi.',
            'nomor_pemberhentian.max' => 'Nomor keputusan pemberhentian maksimal 100 karakter.',

            'tgl_pemberhentian.required' => 'Tanggal keputusan pemberhentian wajib diisi.',
            'tgl_pemberhentian.date' => 'Format tanggal pemberhentian tidak valid.',
            'tgl_pemberhentian.before_or_equal' => 'Tanggal pemberhentian tidak boleh melebihi hari ini.',

            'status.required' => 'Status pemberhentian wajib dipilih.',
            'status.enum' => 'Status pemberhentian tidak valid.',
            'status.not_in' => 'Status pemberhentian tidak boleh bernilai Aktif.',
        ];
    }

    public function attributes(): array
    {
        return [
            'official_id' => 'aparat desa',
            'nomor_pemberhentian' => 'nomor keputusan pemberhentian',
            'tgl_pemberhentian' => 'tanggal keputusan pemberhentian',
            'status' => 'status pemberhentian',
            'notes' => 'catatan',
        ];
    }
}
