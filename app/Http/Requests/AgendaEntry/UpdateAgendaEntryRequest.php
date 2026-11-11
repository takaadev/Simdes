<?php

namespace App\Http\Requests\AgendaEntry;

use App\Enums\AgendaCategoryList;
use App\Models\AgendaEntry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAgendaEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $agenda = $this->route('agenda');

        $agendaId = $agenda instanceof AgendaEntry ? $agenda->getKey() : $agenda;
        return [
            // A. Jenis Agenda
            'tipe_agenda' => [
                'required',
                Rule::in(['incoming', 'outgoing']),
            ],

            // B. Kategori Surat
            'kategori_agenda' => [
                'exclude_unless:tipe_agenda,outgoing',
                'required',
                Rule::enum(AgendaCategoryList::class),
            ],

            // C. Tanggal Surat Masuk
            'tanggal_terima' => [
                'exclude_unless:tipe_agenda,incoming',
                'required',
                'date',
            ],

            // D. Tanggal Surat Keluar
            'tanggal_kirim' => [
                'exclude_unless:tipe_agenda,outgoing',
                'required',
                'date',
            ],

            // E. Nomor Surat
            'nomor_surat' => [
                'required',
                'string',
                'max:100',
                Rule::unique('agenda_entries', 'nomor_surat')
                    ->ignore($agendaId),
            ],

            // F. Tanggal Surat
            'tgl_surat' => [
                'required',
                'date',
            ],

            // G. Pengirim (Surat Masuk)
            'pengirim' => [
                'exclude_unless:tipe_agenda,incoming',
                'required',
                'string',
                'max:150',
            ],

            // H. Penerima / Tujuan (Surat Keluar)
            'penerima' => [
                'exclude_unless:tipe_agenda,outgoing',
                'required',
                'string',
                'max:150',
            ],

            // I. Isi Surat
            'isi_surat' => [
                'nullable',
                'string',
            ],

            // J. Disposisi
            'disposisi' => [
                'nullable',
                'string',
            ],

            'tgl_disposisi' => [
                'nullable',
                'date',
            ],

            // K. Dokumen PDF
            'dokumen' => [
                'nullable',
                'file',
                'mimes:pdf',
                'mimetypes:application/pdf',
                'max:5120',
            ],

            'has_expedition' => [
                'required',
                'boolean',
            ],

            // L. Catatan
            'catatan' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // A. Jenis Agenda
            'tipe_agenda.required' => 'Jenis agenda wajib dipilih.',
            'tipe_agenda.in' => 'Jenis agenda tidak valid.',

            // B. Kategori Surat
            'kategori_agenda.required' => 'Kategori surat wajib dipilih.',
            'kategori_agenda.enum' => 'Kategori surat tidak valid.',

            // C. Tanggal Surat Masuk
            'tanggal_terima.required' => 'Tanggal penerimaan surat wajib diisi.',
            'tanggal_terima.date' => 'Tanggal penerimaan surat tidak valid.',

            // D. Tanggal Surat Keluar
            'tanggal_kirim.required' => 'Tanggal pengiriman surat wajib diisi.',
            'tanggal_kirim.date' => 'Tanggal pengiriman surat tidak valid.',

            // E. Nomor Surat
            'nomor_surat.required' => 'Nomor surat wajib diisi.',
            'nomor_surat.string' => 'Nomor surat harus berupa teks.',
            'nomor_surat.max' => 'Nomor surat maksimal 100 karakter.',
            'nomor_surat.unique' => 'Nomor surat sudah terdaftar. Silakan gunakan nomor surat lain.',

            // F. Tanggal Surat
            'tgl_surat.required' => 'Tanggal surat wajib diisi.',
            'tgl_surat.date' => 'Tanggal surat tidak valid.',

            // G. Pengirim
            'pengirim.required' => 'Nama pengirim wajib diisi.',
            'pengirim.string' => 'Nama pengirim harus berupa teks.',
            'pengirim.max' => 'Nama pengirim maksimal 150 karakter.',

            // H. Penerima
            'penerima.required' => 'Nama penerima atau tujuan surat wajib diisi.',
            'penerima.string' => 'Nama penerima harus berupa teks.',
            'penerima.max' => 'Nama penerima maksimal 150 karakter.',

            // I. Isi Surat
            'isi_surat.string' => 'Isi surat harus berupa teks.',

            // J. Disposisi
            'disposisi.string' => 'Disposisi harus berupa teks.',
            'tgl_disposisi.date' => 'Tanggal disposisi tidak valid.',

            // K. Dokumen PDF
            'dokumen.file' => 'Dokumen harus berupa file.',
            'dokumen.mimes' => 'Dokumen harus berformat PDF.',
            'dokumen.mimetypes' => 'Dokumen harus memiliki tipe MIME application/pdf.',
            'dokumen.max' => 'Ukuran dokumen maksimal 5 MB.',

            'has_expedition.required' => 'Status pencatatan buku ekspedisi wajib ditentukan.',
            'has_expedition.boolean' => 'Status pencatatan buku ekspedisi tidak valid.',

            // L. Catatan
            'catatan.string' => 'Catatan harus berupa teks.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'has_expedition' => $this->boolean('has_expedition'),
        ]);
    }
}
