<x-action-edit :id="$data->id" href="{{ route('temporaryresidents.update', $data->id) }}"
    icon="{{ $data->tgl_pergi === null ? 'sign-out-alt' : 'check-circle' }}" label="Catat Kepergian" color="btn-outline-warning"
    modalTitle="Konfirmasi Kepergian Penduduk" submitLabel="Konfirmasi Kepergian" submitLable="Konfirmasi" disabled="{{ $data->tgl_pergi === null ? false : true }}">
    {{-- Informasi Penduduk --}}
    <div class="d-flex align-items-center gap-3 p-3 mb-3 rounded-3 bg-body-tertiary border">
        <div class="d-flex align-items-center justify-content-center rounded-circle bg-warning-subtle text-warning flex-shrink-0"
            style="width: 48px; height: 48px;">
            <i class="fa-solid fa-person-walking-arrow-right fs-5"></i>
        </div>

        <div class="min-w-0">
            <div class="fw-semibold text-dark text-truncate">
                {{ $data->nama_lengkap }}
            </div>
            <small class="text-muted">
                {{ $data->nomor_identitas ?: 'Nomor identitas tidak tersedia' }}
            </small>
        </div>
    </div>

    {{-- Peringatan --}}
    <div class="alert alert-warning border-0 rounded-3 d-flex gap-3 align-items-start mb-3">
        <i class="fa-solid fa-circle-exclamation fs-5 mt-1"></i>

        <div>
            <div class="fw-semibold mb-1">Perhatian!</div>
            <small>
                Pastikan penduduk benar-benar telah meninggalkan wilayah desa.
                Tanggal kepergian yang dicatat akan mengubah status penduduk
                menjadi <strong>Sudah Pergi</strong>.
            </small>
        </div>
    </div>

    {{-- Form Tanggal --}}
    <x-fieldset label="Informasi Kepergian">
        <x-input-text type="date" name="tgl_pergi" label="Tanggal Kepergian" required />

        <div class="form-text mt-2">
            <i class="fa-regular fa-calendar me-1"></i>
            Masukkan tanggal penduduk meninggalkan wilayah desa.
        </div>
    </x-fieldset>
</x-action-edit>
