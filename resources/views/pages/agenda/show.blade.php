<x-action-show :id="$data->id" modalTitle="Detail Inventaris dan Kekayaan Desa" size="xl">

    {{-- A. Identitas Barang --}}
    <x-fieldset label="A. Identitas Barang">
        <div class="row g-3">
            <div class="col-12">
                <x-input-text name="nama_asset" label="Jenis Barang/Bangunan" value="{{ $data->nama_asset }}" disabled />
            </div>
        </div>
    </x-fieldset>

    {{-- B. Asal Barang/Bangunan --}}
    <x-fieldset label="B. Asal Barang/Bangunan">
        <div class="row g-3">
            <div class="col-12 col-md-6 col-lg">
                <x-input-text type="number" name="asal_beli" label="Dibeli Sendiri" value="{{ $data->asal_beli }}"
                    disabled />
            </div>

            <div class="col-12 col-md-6 col-lg">
                <x-input-text type="number" name="asal_pemerintah" label="Bantuan Pemerintah"
                    value="{{ $data->asal_pemerintah }}" disabled />
            </div>

            <div class="col-12 col-md-6 col-lg">
                <x-input-text type="number" name="asal_provinsi" label="Bantuan Provinsi"
                    value="{{ $data->asal_provinsi }}" disabled />
            </div>

            <div class="col-12 col-md-6 col-lg">
                <x-input-text type="number" name="asal_kabupaten" label="Bantuan Kabupaten/Kota"
                    value="{{ $data->asal_kabupaten }}" disabled />
            </div>

            <div class="col-12 col-md-6 col-lg">
                <x-input-text type="number" name="asal_sumbangan" label="Sumbangan" value="{{ $data->asal_sumbangan }}"
                    disabled />
            </div>
        </div>
    </x-fieldset>

    {{-- C. Keadaan Barang/Bangunan Awal Tahun --}}
    <x-fieldset label="C. Keadaan Barang/Bangunan Awal Tahun">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-text type="number" name="kondisi_awal_baik" label="Kondisi Baik"
                    value="{{ $data->kondisi_awal_baik }}" disabled />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="number" name="kondisi_awal_rusak" label="Kondisi Rusak"
                    value="{{ $data->kondisi_awal_rusak }}" disabled />
            </div>
        </div>
    </x-fieldset>

    {{-- D. Penghapusan Barang dan Bangunan --}}
    <x-fieldset label="D. Penghapusan Barang dan Bangunan">
        <div class="row g-3">
            <div class="col-12 col-md-6 col-lg-3">
                <x-input-text type="number" name="hapus_rusak" label="Rusak" value="{{ $data->hapus_rusak }}"
                    disabled />
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <x-input-text type="number" name="hapus_dijual" label="Dijual" value="{{ $data->hapus_dijual }}"
                    disabled />
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <x-input-text type="number" name="hapus_disumbangkan" label="Disumbangkan"
                    value="{{ $data->hapus_disumbangkan }}" disabled />
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <x-input-text type="date" name="tgl_hapus" label="Tanggal Penghapusan"
                    value="{{ format_date_input($data->tgl_hapus) }}" disabled />
            </div>
        </div>
    </x-fieldset>

    {{-- E. Keadaan Barang/Bangunan Akhir Tahun --}}
    <x-fieldset label="E. Keadaan Barang/Bangunan Akhir Tahun">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-text type="number" name="kondisi_ahir_baik" label="Kondisi Baik"
                    value="{{ $data->kondisi_ahir_baik }}" disabled />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="number" name="kondisi_ahir_rusak" label="Kondisi Rusak"
                    value="{{ $data->kondisi_ahir_rusak }}" disabled />
            </div>
        </div>
    </x-fieldset>

    {{-- F. Dokumen dan Catatan --}}
    <x-fieldset label="F. Dokumen dan Catatan">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Dokumen Inventaris</label>

                @if ($data->dokumen)
                    <div class="d-flex align-items-center justify-content-between gap-3 border rounded-3 p-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-file-pdf text-danger fs-4"></i>
                            <div>
                                <div class="fw-semibold">Dokumen Inventaris</div>
                                <small class="text-muted">File PDF inventaris dan kekayaan desa</small>
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
                        Dokumen inventaris belum tersedia.
                    </div>
                @endif
            </div>

            <div class="col-12">
                <x-input-textarea name="catatan" label="Keterangan" :rows="3" value="{{ $data->catatan }}"
                    disabled />
            </div>
        </div>
    </x-fieldset>

</x-action-show>
