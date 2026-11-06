<x-action-edit :id="$data->id" href="{{ route('village-regulations.update', $data->id) }}"
    modalTitle="Ubah Peraturan Desa" size="lg">
    {{-- A. Informasi Peraturan --}}
    <x-fieldset label="A. Informasi Peraturan">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-select name="jenis_peraturan" label="Jenis Peraturan di Desa"
                    :options="\App\Enums\RegulationTypeList::options()" placeholder="-- Pilih Jenis Peraturan --"
                    value="{{ $data->jenis_peraturan }}" required />
            </div>
            <div class="col-12 col-md-6">
                <x-input-text name="nomor_peraturan" label="Nomor Peraturan" placeholder="Contoh: 01 Tahun 2026"
                    value="{{ $data->nomor_peraturan }}" required />
            </div>
            <div class="col-12">
                <x-input-text name="nama_peraturan" label="Judul atau Tentang Peraturan"
                    placeholder="Masukkan judul atau pokok materi peraturan" value="{{ $data->nama_peraturan }}"
                    required />
            </div>
            <div class="col-12">
                <x-input-textarea name="uraian" label="Uraian Peraturan"
                    placeholder="Tuliskan ringkasan substansi pokok peraturan..." :rows="4"
                    value="{{ $data->uraian }}" />
            </div>
        </div>
    </x-fieldset>

    {{-- B. Penetapan dan Kesepakatan --}}
    <x-fieldset label="B. Penetapan dan Kesepakatan">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_ditetapkan" label="Tanggal Ditetapkan" required
                    value="{{ format_date_input($data->tgl_ditetapkan) }}" />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_kesepakatan" label="Tanggal Kesepakatan"
                    value="{{ format_date_input($data->tgl_kesepakatan) }}" />
            </div>
        </div>
    </x-fieldset>

    {{-- C. Pelaporan dan Pengundangan --}}
    <x-fieldset label="C. Pelaporan dan Pengundangan">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-text name="nomor_dilaporkan" label="Nomor Dilaporkan" placeholder="Masukkan nomor pelaporan"
                    value="{{ $data->nomor_dilaporkan }}" />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_dilaporkan" label="Tanggal Dilaporkan"
                    value="{{ format_date_input($data->tgl_dilaporkan) }}" />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text name="nomor_diundang" label="Nomor Pengundangan" placeholder="Masukkan nomor pengundangan"
                    value="{{ $data->nomor_diundang }}" />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_diundang" label="Tanggal Diundangkan"
                    value="{{ format_date_input($data->tgl_diundang) }}" />
            </div>
        </div>
    </x-fieldset>

    {{-- D. Berita Desa dan Dokumen --}}
    <x-fieldset label="D. Berita Desa dan Dokumen">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-text name="nomor_berita" label="Nomor Berita" placeholder="Masukkan nomor berita"
                    value="{{ $data->nomor_berita }}" />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_berita" label="Tanggal Berita"
                    value="{{ format_date_input($data->tgl_berita) }}" />
            </div>

            <div class="col-12">
                <x-input-text type="file" name="dokumen" label="Dokumen Peraturan" accept=".pdf"
                    hint="Unggah dokumen peraturan dalam format PDF." />
            </div>

            <div class="col-12">
                <x-input-textarea name="catatan" label="Catatan" placeholder="Tambahkan catatan jika diperlukan..."
                    :rows="3" value="{{ $data->catatan }}" />
            </div>
        </div>
    </x-fieldset>
</x-action-edit>