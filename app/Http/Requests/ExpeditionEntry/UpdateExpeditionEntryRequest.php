<?php

namespace App\Http\Requests\ExpeditionEntry;

use App\Enums\AgendaTypeList;
use App\Models\ExpeditionEntry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UpdateExpeditionEntryRequest extends FormRequest
{
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
        $expedition = $this->route('expedition');

        $expeditionId = $expedition instanceof ExpeditionEntry ? $expedition->getKey() : $expedition;
        return [
            // A. Agenda Surat Keluar
            'agenda_entry_id' => [
                'required',
                'integer',
                Rule::exists('agenda_entries', 'id')
                    ->where('tipe_agenda', AgendaTypeList::OUTGOING->value)
                    ->where('has_expedition', true)
                    ->whereNull('deleted_at'),

                function ($attribute, $value, $fail) use ($expeditionId) {
                    $exists = DB::table('expedition_entries')
                        ->where('agenda_entry_id', $value)
                        ->whereNull('deleted_at')
                        ->where('id', '!=', $expeditionId)
                        ->exists();

                    if ($exists) {
                        $fail('Surat tersebut sudah tercatat dalam Buku Ekspedisi.');
                    }
                },
            ],

            // B. Isi Surat
            'isi_surat' => [
                'nullable',
                'string',
            ],

            // C. Catatan
            'catatan' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'agenda_entry_id.required' => 'Surat keluar wajib dipilih.',
            'agenda_entry_id.integer' => 'Pilihan surat keluar tidak valid.',
            'agenda_entry_id.exists' => 'Surat keluar tidak tersedia atau tidak memenuhi ketentuan pencatatan Buku Ekspedisi.',
            'isi_surat.string' => 'Isi surat harus berupa teks.',
            'catatan.string' => 'Catatan harus berupa teks.',
        ];
    }
}
