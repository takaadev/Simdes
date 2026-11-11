<x-action-edit :id="$data->id" href="{{ route('village-assets.update', $data->id) }}" size="xl">

    {{-- A. Identitas Barang --}}
    <x-fieldset label="A. Identitas Barang">
        <div class="row g-3">
            <div class="col-12">
                <x-input-text name="nama_asset" label="Jenis Barang/Bangunan"
                    placeholder="Contoh: Meja Kerja Kayu Ruang Pelayanan"
                    hint="Nama barang inventaris atau bangunan milik desa." value="{{ $data->nama_asset }}" required />
            </div>
        </div>
    </x-fieldset>

    {{-- B. Asal Barang/Bangunan --}}
    <x-fieldset label="B. Asal Barang/Bangunan">
        <div class="row g-3">
            <div class="col-12">
                <span class="text-muted small">
                    Isi jumlah unit berdasarkan sumber perolehan barang atau bangunan.
                </span>
            </div>

            <div class="col-12 col-md-6 col-lg">
                <x-input-text type="number" name="asal_beli" label="Dibeli Sendiri" min="0" step="1"
                    value="{{ $data->asal_beli }}" />
            </div>

            <div class="col-12 col-md-6 col-lg">
                <x-input-text type="number" name="asal_pemerintah" label="Bantuan Pemerintah" min="0"
                    step="1" value="{{ $data->asal_pemerintah }}" />
            </div>

            <div class="col-12 col-md-6 col-lg">
                <x-input-text type="number" name="asal_provinsi" label="Bantuan Provinsi" min="0" step="1"
                    value="{{ $data->asal_provinsi }}" />
            </div>

            <div class="col-12 col-md-6 col-lg">
                <x-input-text type="number" name="asal_kabupaten" label="Bantuan Kabupaten/Kota" min="0"
                    step="1" value="{{ $data->asal_kabupaten }}" />
            </div>

            <div class="col-12 col-md-6 col-lg">
                <x-input-text type="number" name="asal_sumbangan" label="Sumbangan" min="0" step="1"
                    value="{{ $data->asal_sumbangan }}" />
            </div>
        </div>
    </x-fieldset>

    {{-- C. Keadaan Barang/Bangunan Awal Tahun --}}
    <x-fieldset label="C. Keadaan Barang/Bangunan Awal Tahun">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-text type="number" name="kondisi_awal_baik" label="Kondisi Baik" min="0" step="1"
                    value="{{ $data->kondisi_awal_baik }}" />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="number" name="kondisi_awal_rusak" label="Kondisi Rusak" min="0"
                    step="1" value="{{ $data->kondisi_awal_rusak }}" />
            </div>
        </div>
    </x-fieldset>

    {{-- D. Penghapusan Barang dan Bangunan --}}
    <x-fieldset label="D. Penghapusan Barang dan Bangunan">
        <div class="row g-3">
            <div class="col-12 col-md-6 col-lg-3">
                <x-input-text type="number" name="hapus_rusak" label="Rusak" min="0" step="1"
                    value="{{ $data->hapus_rusak }}" />
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <x-input-text type="number" name="hapus_dijual" label="Dijual" min="0" step="1"
                    value="{{ $data->hapus_dijual }}" />
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <x-input-text type="number" name="hapus_disumbangkan" label="Disumbangkan" min="0"
                    step="1" value="{{ $data->hapus_disumbangkan }}" />
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <x-input-text type="date" name="tgl_hapus" label="Tanggal Penghapusan"
                    value="{{ $data->tgl_hapus?->format('Y-m-d') }}" />
            </div>
        </div>
    </x-fieldset>

    {{-- E. Keadaan Barang/Bangunan Akhir Tahun --}}
    <x-fieldset label="E. Keadaan Barang/Bangunan Akhir Tahun">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-text type="number" name="kondisi_ahir_baik" label="Kondisi Baik" min="0"
                    step="1" value="{{ $data->kondisi_ahir_baik }}" />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="number" name="kondisi_ahir_rusak" label="Kondisi Rusak" min="0"
                    step="1" value="{{ $data->kondisi_ahir_rusak }}" />
            </div>
        </div>
    </x-fieldset>

    {{-- F. Dokumen dan Catatan --}}
    <x-fieldset label="F. Dokumen dan Catatan">
        <div class="row g-3">
            <div class="col-12">
                <x-input-text type="file" name="dokumen" label="Dokumen Inventaris" accept=".pdf,application/pdf"
                    hint="Unggah dokumen pendukung inventaris dalam format PDF. Kosongkan jika tidak ingin mengganti dokumen." />
            </div>

            <div class="col-12">
                <x-input-textarea name="catatan" label="Keterangan"
                    placeholder="Catatan tambahan terkait lokasi, spesifikasi, atau kondisi barang..."
                    hint="Tambahkan informasi lain yang dianggap penting terkait barang atau bangunan."
                    value="{{ $data->catatan }}" :rows="3" />
            </div>
        </div>
    </x-fieldset>

</x-action-edit>
