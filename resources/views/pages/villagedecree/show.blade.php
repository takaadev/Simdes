<x-action-show :id="$data->id" modalTitle="Detail Keputusan Kepala Desa" size="xl">

    {{-- A. Informasi Keputusan --}}
    <x-fieldset label="A. Informasi Keputusan">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-text name="nomor_keputusan" label="Nomor Keputusan" value="{{ $data->nomor_keputusan }}"
                    disabled />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_keputusan" label="Tanggal Ditetapkan"
                    value="{{ format_date_input($data->tgl_keputusan) }}" disabled />
            </div>

            <div class="col-12">
                <x-input-text name="nama_keputusan" label="Judul atau Tentang Keputusan"
                    value="{{ $data->nama_keputusan }}" disabled />
            </div>

            <div class="col-12">
                <x-input-textarea name="uraian" label="Uraian Keputusan" :rows="4" value="{{ $data->uraian }}"
                    disabled />
            </div>
        </div>
    </x-fieldset>

    {{-- B. Informasi Pelaporan --}}
    <x-fieldset label="B. Informasi Pelaporan">
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <x-input-text name="nomor_dilaporkan" label="Nomor Pelaporan" value="{{ $data->nomor_dilaporkan }}"
                    disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text type="date" name="tgl_dilaporkan" label="Tanggal Dilaporkan"
                    value="{{ format_date_input($data->tgl_dilaporkan) }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="status" label="Status Keputusan" :options="\App\Enums\DecreeStatusList::options()" value="{{ $data->status }}"
                    disabled />
            </div>
        </div>
    </x-fieldset>

    {{-- C. Dokumen dan Catatan --}}
    <x-fieldset label="C. Dokumen dan Catatan">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Dokumen Keputusan</label>

                @if ($data->dokumen)
                    <div class="d-flex align-items-center justify-content-between gap-3 border rounded-3 p-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-file-pdf text-danger fs-4"></i>
                            <div>
                                <div class="fw-semibold">Dokumen Keputusan</div>
                                <small class="text-muted">File PDF keputusan kepala desa</small>
                            </div>
                        </div>

                        <a href="{{ asset('storage/' . $data->dokumen) }}" target="_blank" rel="noopener noreferrer"
                            class="btn btn-sm btn-outline-danger">
                            <i class="fa-solid fa-eye me-1"></i>
                            Lihat PDF
                        </a>
                    </div>
                @else
                    <div class="alert alert-light border mb-0">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Dokumen keputusan belum tersedia.
                    </div>
                @endif
            </div>

            <div class="col-12">
                <x-input-textarea name="catatan" label="Catatan" :rows="3" value="{{ $data->catatan }}"
                    disabled />
            </div>
        </div>
    </x-fieldset>

</x-action-show>
