<x-action-create href="{{ route('village-publications.store') }}" size="lg">
    <x-fieldset label="A. Informasi Peraturan">
        <div class="row g-3">
            <div class="col-12">
                <x-input-select name="regulation_id" label="Daftar Peraturan yang Belum Diundangkan" :options="\App\Support\PublicationRegulationOptions::options()"
                    placeholder="-- Pilih Peraturan Desa --" required />
            </div>
        </div>
    </x-fieldset>
    <x-fieldset label="B. Pengundangan dan Berita Desa">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text name="nomor_diundang" label="Nomor Pengundangan"
                    placeholder="Masukkan nomor pengundangan" />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_diundang" label="Tanggal Diundangkan" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text name="nomor_berita" label="Nomor Berita Desa" placeholder="Masukkan nomor berita desa" />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_berita" label="Tanggal Berita Desa" required />
            </div>
            <x-input-select name="status" label="Status Praturan" required :options="\App\Enums\RegulationStatusList::options()" />
        </div>
    </x-fieldset>
</x-action-create>
