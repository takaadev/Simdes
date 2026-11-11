<x-action-edit :id="$data->id" href="{{ route('expeditions.update', $data->id) }}" size="lg">
    <x-fieldset label="A. Informasi Surat">
        <div class="row g-3">
            <div class="col-12">
                <x-input-select
                    name="agenda_entry_id"
                    label="Daftar Surat Keluar"
                    :options="\App\Support\AgendaEntryHasExpeditionOptions::options($data->id)"
                    placeholder="-- Pilih Surat Keluar --"
                    value="{{ $data->agenda_entry_id }}"
                    required
                />
            </div>

            <div class="col-12">
                <x-input-textarea
                    name="isi_surat"
                    label="Isi Surat"
                    value="{{ $data->isi_surat }}"
                    hint="Kosongkan jika ingin menggunakan isi surat dari agenda yang dipilih."
                    placeholder="Isi surat akan mengikuti data agenda jika dikosongkan..."
                    :rows="4"
                />
            </div>

            <div class="col-12">
                <x-input-textarea
                    name="catatan"
                    label="Catatan"
                    value="{{ $data->catatan }}"
                    hint="Kosongkan jika ingin menggunakan catatan dari agenda yang dipilih."
                    placeholder="Tambahkan catatan jika diperlukan..."
                    :rows="3"
                />
            </div>
        </div>
    </x-fieldset>
</x-action-edit>