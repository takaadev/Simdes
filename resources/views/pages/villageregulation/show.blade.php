<x-action-show :id="$data->id" modalTitle="Detail Peraturan Desa" size="lg">

    {{-- A. Informasi Peraturan --}}
    <x-fieldset label="A. Informasi Peraturan">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-select
                    name="jenis_peraturan"
                    label="Jenis Peraturan di Desa"
                    :options="\App\Enums\RegulationTypeList::options()"
                    value="{{ $data->jenis_peraturan?->value ?? $data->jenis_peraturan }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    name="nomor_peraturan"
                    label="Nomor Peraturan"
                    value="{{ $data->nomor_peraturan }}"
                    disabled
                />
            </div>

            <div class="col-12">
                <x-input-text
                    name="nama_peraturan"
                    label="Judul atau Tentang Peraturan"
                    value="{{ $data->nama_peraturan }}"
                    disabled
                />
            </div>

            <div class="col-12">
                <x-input-textarea
                    name="uraian"
                    label="Uraian Peraturan"
                    :rows="4"
                    value="{{ $data->uraian }}"
                    disabled
                />
            </div>

        </div>
    </x-fieldset>

    {{-- B. Penetapan --}}
    <x-fieldset label="B. Penetapan">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_ditetapkan"
                    label="Tanggal Ditetapkan"
                    value="{{ format_date_input($data->tgl_ditetapkan) }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_kesepakatan"
                    label="Tanggal Kesepakatan"
                    value="{{ format_date_input($data->tgl_kesepakatan) }}"
                    disabled
                />
            </div>

        </div>
    </x-fieldset>

    {{-- C. Pelaporan --}}
    <x-fieldset label="C. Pelaporan">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text
                    name="nomor_dilaporkan"
                    label="Nomor Pelaporan"
                    value="{{ $data->nomor_dilaporkan }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_dilaporkan"
                    label="Tanggal Dilaporkan"
                    value="{{ format_date_input($data->tgl_dilaporkan) }}"
                    disabled
                />
            </div>

        </div>
    </x-fieldset>

    {{-- D. Pengundangan dan Berita Desa --}}
    <x-fieldset label="D. Pengundangan dan Berita Desa">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text
                    name="nomor_diundang"
                    label="Nomor Pengundangan"
                    value="{{ $data->publication?->nomor_diundang }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_diundang"
                    label="Tanggal Diundangkan"
                    value="{{ format_date_input($data->publication?->tgl_diundang) }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    name="nomor_berita"
                    label="Nomor Berita Desa"
                    value="{{ $data->publication?->nomor_berita }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_berita"
                    label="Tanggal Berita Desa"
                    value="{{ format_date_input($data->publication?->tgl_berita) }}"
                    disabled
                />
            </div>

            <div class="col-12">
                <x-input-text
                    name="status_pengundangan"
                    label="Status Pengundangan"
                    value="{{ $data->publication?->status?->label() ?? 'Belum Diundangkan' }}"
                    disabled
                />
            </div>

        </div>
    </x-fieldset>

    {{-- E. Status, Dokumen dan Catatan --}}
    <x-fieldset label="E. Status, Dokumen dan Catatan">
        <div class="row g-3">

            <div class="col-12">
                <x-input-select
                    name="status"
                    label="Status Peraturan"
                    :options="\App\Enums\RegulationStatusList::options()"
                    value="{{ $data->status?->value ?? $data->status }}"
                    disabled
                />
            </div>

            {{-- Dokumen --}}
            <div class="col-12">
                <label class="form-label">Dokumen Peraturan Desa</label>

                @if ($data->dokumen)
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 border rounded-3 p-3">

                        <div class="d-flex align-items-center gap-3">
                            <i class="fa-solid fa-file-pdf text-danger fs-3"></i>

                            <div>
                                <div class="fw-semibold">Dokumen Peraturan Desa</div>
                                <small class="text-muted">File PDF peraturan desa</small>
                            </div>
                        </div>

                        <a
                            href="{{ asset('storage/' . $data->dokumen) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn btn-sm btn-outline-danger"
                        >
                            <i class="fa-solid fa-eye me-1"></i>
                            Lihat Dokumen PDF
                        </a>

                    </div>
                @else
                    <div class="alert alert-light border mb-0">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Dokumen peraturan desa belum tersedia.
                    </div>
                @endif
            </div>

            {{-- Catatan --}}
            <div class="col-12">
                <x-input-textarea
                    name="catatan"
                    label="Catatan"
                    :rows="3"
                    value="{{ $data->catatan }}"
                    disabled
                />
            </div>

        </div>
    </x-fieldset>

</x-action-show>
