<x-action-show :id="$data->id" modalTitle="Detail Publikasi Peraturan Desa" size="lg">

    {{-- A. Informasi Peraturan --}}
    <x-fieldset label="A. Informasi Peraturan">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text
                    name="jenis_peraturan"
                    label="Jenis Peraturan di Desa"
                    value="{{ $data->regulation?->jenis_peraturan?->label() }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    name="nomor_peraturan"
                    label="Nomor Peraturan"
                    value="{{ $data->regulation?->nomor_peraturan }}"
                    disabled
                />
            </div>

            <div class="col-12">
                <x-input-text
                    name="nama_peraturan"
                    label="Judul atau Tentang Peraturan"
                    value="{{ $data->regulation?->nama_peraturan }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_ditetapkan"
                    label="Tanggal Ditetapkan"
                    value="{{ format_date_input($data->regulation?->tgl_ditetapkan) }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    name="status_peraturan"
                    label="Status Peraturan"
                    value="{{ $data->regulation?->status?->label() }}"
                    disabled
                />
            </div>

        </div>
    </x-fieldset>

    {{-- B. Pengundangan dan Berita Desa --}}
    <x-fieldset label="B. Pengundangan dan Berita Desa">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text
                    name="nomor_diundang"
                    label="Nomor Pengundangan"
                    value="{{ $data->nomor_diundang }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_diundang"
                    label="Tanggal Diundangkan"
                    value="{{ format_date_input($data->tgl_diundang) }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    name="nomor_berita"
                    label="Nomor Berita Desa"
                    value="{{ $data->nomor_berita }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_berita"
                    label="Tanggal Berita Desa"
                    value="{{ format_date_input($data->tgl_berita) }}"
                    disabled
                />
            </div>

        </div>
    </x-fieldset>

    {{-- C. Status Publikasi --}}
    <x-fieldset label="C. Status Publikasi">
        <div class="row g-3">

            <div class="col-12">
                <x-input-select
                    name="status"
                    label="Status Pengundangan"
                    :options="\App\Enums\RegulationStatusList::options()"
                    value="{{ $data->status?->value }}"
                    disabled
                />
            </div>

        </div>
    </x-fieldset>

</x-action-show>