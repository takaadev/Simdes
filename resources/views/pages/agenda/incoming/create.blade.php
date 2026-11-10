<x-action-create href="{{ route('agendas.store') }}" size="xl" modalTitle="Tambah Surat Masuk" icon="inbox"
    label="Surat Masuk" color="btn-simdes-outline">
    <x-input-text value="incoming" name="tipe_agenda" type="hidden" />
    <x-fieldset label="A. Informasi Penerimaan Surat">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tanggal_terima" label="Tanggal Terima" :value="now()->format('Y-m-d')" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text name="nomor_surat" label="Nomor Surat" placeholder="Contoh: 400.12.3/680/DS/VII/2026"
                    required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_surat" label="Tanggal Surat" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text name="pengirim" label="Asal / Pengirim Surat"
                    placeholder="Nama instansi atau pihak pengirim" required />
            </div>

            <div class="col-12">
                <x-input-textarea name="isi_surat" label="Perihal Surat"
                    placeholder="Tuliskan perihal atau ringkasan isi surat..."
                    hint="Masukkan pokok permasalahan atau tujuan surat." :rows="3" required />
            </div>
        </div>
    </x-fieldset>

    {{-- B. Dokumen dan Catatan --}}
    <x-fieldset label="B. Dokumen dan Catatan">
        <div class="row g-3">
            <div class="col-12">
                <x-input-text type="file" name="dokumen" label="Dokumen Surat Masuk" accept=".pdf,application/pdf"
                    hint="Unggah hasil pemindaian surat masuk dalam format PDF." />
            </div>

            <div class="col-12">
                <x-input-textarea name="catatan" label="Catatan" placeholder="Catatan tambahan terkait surat masuk..."
                    hint="Informasi tambahan yang dianggap penting." :rows="3" />
            </div>
        </div>
    </x-fieldset>

</x-action-create>
