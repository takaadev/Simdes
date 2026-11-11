<x-action-show :id="$data->id" modalTitle="Detail Surat Keluar" size="lg">
    {{-- A. Informasi Surat Keluar --}}
    <x-fieldset label="A. Informasi Surat Keluar">
        <div class="row g-3">

            {{-- Kategori Surat --}}
            <div class="col-12 col-md-6">
                <x-input-select
                    name="kategori_agenda"
                    label="Kategori Surat"
                    :options="\App\Enums\AgendaCategoryList::options()"
                    value="{{ $data->kategori_agenda?->value ?? $data->kategori_agenda }}"
                    disabled
                />
            </div>

            {{-- Nomor Surat --}}
            <div class="col-12 col-md-6">
                <x-input-text
                    name="nomor_surat"
                    label="Nomor Surat"
                    value="{{ $data->nomor_surat }}"
                    disabled
                />
            </div>

            {{-- Tanggal Surat --}}
            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_surat"
                    label="Tanggal Surat"
                    value="{{ format_date_input($data->tgl_surat) }}"
                    disabled
                />
            </div>

            {{-- Tanggal Kirim --}}
            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tanggal_kirim"
                    label="Tanggal Kirim"
                    value="{{ format_date_input($data->tanggal_kirim) }}"
                    disabled
                />
            </div>

            {{-- Tujuan / Penerima --}}
            <div class="col-12">
                <x-input-text
                    name="penerima"
                    label="Tujuan / Penerima Surat"
                    value="{{ $data->penerima }}"
                    disabled
                />
            </div>

            {{-- Perihal --}}
            <div class="col-12">
                <x-input-textarea
                    name="isi_surat"
                    label="Perihal Surat"
                    value="{{ $data->isi_surat }}"
                    :rows="3"
                    disabled
                />
            </div>

        </div>
    </x-fieldset>

    {{-- B. Dokumen dan Catatan --}}
    <x-fieldset label="B. Dokumen dan Catatan">
        <div class="row g-3">

            {{-- Dokumen Surat --}}
            <div class="col-12">
                <label class="form-label">Dokumen Surat Keluar</label>

                @if ($data->dokumen)
                    <div class="d-flex align-items-center justify-content-between gap-3 border rounded-3 p-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-file-pdf text-danger fs-4"></i>

                            <div>
                                <div class="fw-semibold">Dokumen Surat Keluar</div>
                                <small class="text-muted">
                                    File PDF surat keluar
                                </small>
                            </div>
                        </div>

                        <a
                            href="{{ asset('storage/' . $data->dokumen) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn btn-sm btn-outline-danger"
                        >
                            <i class="fa-solid fa-eye me-1"></i>
                            Lihat PDF
                        </a>
                    </div>
                @else
                    <div class="alert alert-light border mb-0">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Dokumen surat keluar belum tersedia.
                    </div>
                @endif
            </div>

            {{-- Catatan --}}
            <div class="col-12">
                <x-input-textarea
                    name="catatan"
                    label="Catatan"
                    value="{{ $data->catatan }}"
                    :rows="3"
                    disabled
                />
            </div>

        </div>
    </x-fieldset>

</x-action-show>
