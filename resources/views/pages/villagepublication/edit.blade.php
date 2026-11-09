<x-action-edit :id="$data->id" href="{{ route('village-publications.update', $data->id) }}" size="lg">

    <x-fieldset label="A. Informasi Peraturan">
        <div class="row g-3">
            <div class="col-12">
                <x-input-text
                    name="regulation"
                    label="{{ $data->regulation?->jenis_peraturan?->label() }}"
                    value="{{ $data->regulation?->nomor_peraturan . ' - ' . $data->regulation?->nama_peraturan }}"
                    readonly
                />
            </div>
        </div>
    </x-fieldset>

    <x-fieldset label="B. Pengundangan dan Berita Desa">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text
                    name="nomor_diundang"
                    label="Nomor Pengundangan"
                    placeholder="Masukkan nomor pengundangan"
                    value="{{ $data->nomor_diundang }}"
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_diundang"
                    label="Tanggal Diundangkan"
                    value="{{ $data->tgl_diundang?->format('Y-m-d') }}"
                    required
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    name="nomor_berita"
                    label="Nomor Berita Desa"
                    placeholder="Masukkan nomor berita desa"
                    value="{{ $data->nomor_berita }}"
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_berita"
                    label="Tanggal Berita Desa"
                    value="{{ $data->tgl_berita?->format('Y-m-d') }}"
                    required
                />
            </div>

            <div class="col-12">
                <x-input-select
                    name="status"
                    label="Status Peraturan"
                    :options="\App\Enums\RegulationStatusList::options()"
                    value="{{ $data->status?->value }}"
                    required
                />
            </div>

        </div>
    </x-fieldset>

</x-action-edit>