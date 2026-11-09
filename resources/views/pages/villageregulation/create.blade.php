<x-action-create href="{{ route('village-regulations.store') }}" size="lg">

    {{-- A. Informasi Peraturan --}}
    <x-fieldset label="A. Informasi Peraturan">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-select name="jenis_peraturan" label="Jenis Peraturan di Desa"
                    :options="\App\Enums\RegulationTypeList::options()" placeholder="-- Pilih Jenis Peraturan --"
                    />
            </div>
            <div class="col-12 col-md-6">
                <x-input-text name="nomor_peraturan" label="Nomor Peraturan" placeholder="Contoh: 01 Tahun 2026"
                     />
            </div>
            <div class="col-12">
                <x-input-text name="nama_peraturan" label="Judul atau Tentang Peraturan"
                    placeholder="Masukkan judul atau pokok materi peraturan" required />
            </div>
            <div class="col-12">
                <x-input-textarea name="uraian" label="Uraian Peraturan"
                    placeholder="Tuliskan ringkasan substansi pokok peraturan..." :rows="3" />
            </div>
        </div>
    </x-fieldset>

    {{-- B. Penetapan dan Kesepakatan --}}
    <x-fieldset label="B. Penetapan">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_ditetapkan" label="Tanggal Ditetapkan" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_kesepakatan" label="Tanggal Kesepakatan" />
            </div>
             <div class="col-12 col-md-6">
                <x-input-text name="nomor_dilaporkan" label="Nomor Dilaporkan" placeholder="Masukkan nomor pelaporan" />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_dilaporkan" label="Tanggal Dilaporkan" />
            </div>
        </div>
    </x-fieldset>

    {{-- D. Berita Desa dan Dokumen --}}
    <x-fieldset label="Dokumen dan Catatan">
        <div class="row g-3">
            <div class="col-12">
                <x-input-text type="file" name="dokumen" label="Dokumen Peraturan" accept=".pdf"
                    hint="Unggah dokumen peraturan dalam format PDF." />
            </div>

            <div class="col-12">
                <x-input-textarea name="catatan" label="Catatan" placeholder="Tambahkan catatan jika diperlukan..."
                    :rows="2" />
            </div>
        </div>
    </x-fieldset>

</x-action-create>