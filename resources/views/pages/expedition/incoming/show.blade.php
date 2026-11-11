
<x-action-show :id="$data->id" modalTitle="Detail Surat Masuk" size="lg">

    {{-- A. Informasi Penerimaan Surat --}}
    <x-fieldset label="A. Informasi Penerimaan Surat">
        <div class="row g-3">

            {{-- Tanggal Terima --}}
            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tanggal_terima"
                    label="Tanggal Terima"
                    value="{{ format_date_input($data->tanggal_terima) }}"
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

            {{-- Pengirim --}}
            <div class="col-12 col-md-6">
                <x-input-text
                    name="pengirim"
                    label="Asal / Pengirim Surat"
                    value="{{ $data->pengirim }}"
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
                <label class="form-label">Dokumen Surat Masuk</label>

                @if ($data->dokumen)
                    <div class="d-flex align-items-center justify-content-between gap-3 border rounded-3 p-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-file-pdf text-danger fs-4"></i>

                            <div>
                                <div class="fw-semibold">Dokumen Surat Masuk</div>
                                <small class="text-muted">
                                    File PDF surat masuk
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
                        Dokumen surat masuk belum tersedia.
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
