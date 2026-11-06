<x-action-show :id="$data->id" modalTitle="Detail Peraturan Desa" size="lg">
    {{-- A. Informasi Peraturan --}}
    <x-fieldset label="A. Informasi Peraturan">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-select label="Jenis Peraturan di Desa" :options="\App\Enums\RegulationTypeList::options()" value="{{ $data->jenis_peraturan }}"
                    disabled />
            </div>
            <div class="col-12 col-md-6">
                <x-input-text name="nomor_peraturan" label="Nomor Peraturan" value="{{ $data->nomor_peraturan }}"
                    disabled />
            </div>
            <div class="col-12">
                <x-input-text name="nama_peraturan" label="Judul atau Tentang Peraturan"
                    value="{{ $data->nama_peraturan }}" disabled />
            </div>
            <div class="col-12">
                <x-input-textarea name="uraian" label="Uraian Peraturan" :rows="4" value="{{ $data->uraian }}"
                    disabled />
            </div>
        </div>
    </x-fieldset>

    {{-- B. Penetapan dan Kesepakatan --}}
    <x-fieldset label="B. Penetapan dan Kesepakatan">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_ditetapkan" label="Tanggal Ditetapkan"
                    value="{{ format_date_input($data->tgl_ditetapkan) }}" disabled />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_kesepakatan" label="Tanggal Kesepakatan"
                    value="{{ format_date_input($data->tgl_kesepakatan) }}" disabled />
            </div>
        </div>
    </x-fieldset>

    {{-- C. Pelaporan dan Pengundangan --}}
    <x-fieldset label="C. Pelaporan dan Pengundangan">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-text name="nomor_dilaporkan" label="Nomor Dilaporkan" value="{{ $data->nomor_dilaporkan }}"
                    disabled />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_dilaporkan" label="Tanggal Dilaporkan"
                    value="{{ format_date_input($data->tgl_dilaporkan) }}" disabled />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text name="nomor_diundang" label="Nomor Pengundangan" value="{{ $data->nomor_diundang }}"
                    disabled />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_diundang" label="Tanggal Diundangkan"
                    value="{{ format_date_input($data->tgl_diundang) }}" disabled />
            </div>
        </div>
    </x-fieldset>

    {{-- D. Berita Desa dan Dokumen --}}
    <x-fieldset label="D. Berita Desa dan Dokumen">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-text name="nomor_berita" label="Nomor Berita" value="{{ $data->nomor_berita }}" disabled />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_berita" label="Tanggal Berita"
                    value="{{ format_date_input($data->tgl_berita) }}" disabled />
            </div>

            <div class="col-12">
                <x-input-select name="jenis_peraturan" label="Jenis Peraturan di Desa" :options="\App\Enums\RegulationStatusList::options()"
                    value="{{ $data->status }}" disabled />
            </div>

            <div class="col-12">
                <x-input-textarea name="catatan" label="Catatan" :rows="3" value="{{ $data->catatan }}"
                    disabled />
            </div>
        </div>
    </x-fieldset>
</x-action-show>
