<x-action-show :id="$data->id" modalTitle="Detail Ekspedisi" size="lg">

    <x-fieldset label="A. Informasi Surat">
        <div class="row g-3">

            <div class="col-md-6">
                <x-input-text
                    name="tanggal_kirim"
                    label="Tanggal Pengiriman"
                    value="{{ $data->tanggal_kirim?->format('d/m/Y') }}"
                    disabled
                />
            </div>

            <div class="col-md-6">
                <x-input-text
                    name="tgl_surat"
                    label="Tanggal Surat"
                    value="{{ $data->tgl_surat?->format('d/m/Y') }}"
                    disabled
                />
            </div>

            <div class="col-12">
                <x-input-text
                    name="nomor_surat"
                    label="Nomor Surat"
                    value="{{ $data->nomor_surat }}"
                    disabled
                />
            </div>

            <div class="col-12">
                <x-input-text
                    name="penerima"
                    label="Ditujukan Kepada"
                    value="{{ $data->penerima }}"
                    disabled
                />
            </div>

            <div class="col-12">
                <x-input-textarea
                    name="isi_surat"
                    label="Isi Singkat Surat"
                    value="{{ $data->isi_surat }}"
                    :rows="4"
                    disabled
                />
            </div>

            <div class="col-12">
                <x-input-textarea
                    name="catatan"
                    label="Keterangan"
                    value="{{ $data->catatan }}"
                    :rows="3"
                    disabled
                />
            </div>

        </div>
    </x-fieldset>

    <x-fieldset label="B. Dokumen Pendukung">
        <div class="row g-3">
            <div class="col-12">
                @if ($data->agendaEntry?->dokumen)
                    <a
                        href="{{ asset('storage/' . $data->agendaEntry->dokumen) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn btn-light-primary"
                    >
                        <i class="fa-solid fa-file-pdf me-1"></i>
                        Lihat Dokumen Surat
                    </a>
                @else
                    <span class="text-muted">
                        <i class="fa-regular fa-file-pdf me-1"></i>
                        Tidak ada dokumen surat.
                    </span>
                @endif
            </div>
        </div>
    </x-fieldset>

</x-action-show>