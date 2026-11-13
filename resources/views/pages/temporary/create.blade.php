<x-action-create href="{{ route('temporaryresidents.store') }}" size="lg" modalTitle="Tambah Penduduk Sementara"
    submitLabel="Simpan Data">

    {{-- A. Identitas Penduduk --}}
    <x-fieldset label="A. Identitas Penduduk">
        <div class="row g-3">

            <div class="col-12 col-md-7">
                <x-input-text name="nama_lengkap" label="Nama Lengkap" placeholder="Masukkan nama lengkap" maxlength="150"
                    required />
            </div>

            <div class="col-12 col-md-5">
                <x-input-text name="nomor_identitas" label="Nomor Identitas (KTP / Paspor)"
                    placeholder="Masukkan nomor identitas" maxlength="30" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="jenis_kelamin" label="Jenis Kelamin" :options="\App\Enums\GenderList::options()"
                    placeholder="Pilih Jenis Kelamin" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="tempat_lahir" label="Tempat Lahir" placeholder="Contoh: Bangko" maxlength="100"
                    required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text type="date" name="tgl_lahir" label="Tanggal Lahir" required />
            </div>

            <div class="col-12 col-md-2">
                <x-input-text type="number" name="umur" label="Umur (Tahun)"  max="150" placeholder="Conth:20"
                    required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="pekerjaan" label="Pekerjaan" :options="\App\Enums\OccupationList::options()" placeholder="Pilih Pekerjaan"
                    required />
            </div>

            <div class="col-12 col-md-3">
                <x-input-select name="kewarganegaraan" label="Kewarganegaraan" :options="\App\Enums\CitizenshipList::options()"
                    placeholder="Pilih Kewarganegaraan" required />
            </div>

            <div class="col-12 col-md-3">
                <x-input-text name="kebangsaan" label="Kebangsaan" placeholder="Contoh: Indonesia" maxlength="100" />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="keturunan" label="Keturunan" placeholder="Masukkan keturunan" maxlength="50" />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text type="date" name="tgl_datang" label="Tanggal Kedatangan" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="datang_dari" label="Datang dari" placeholder="Masukkan daerah asal" maxlength="150"
                    required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text name="tujuan_kedatangan" label="Tujuan Kedatangan" placeholder="Contoh: Bekerja, dll."
                    required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text name="alamat_tujuan" label="Alamat Tujuan" placeholder="Alamat tempat tinggal sementara"
                    required />
            </div>

            <div class="col-12">
                <x-input-text name="catatan" label="Keterangan/Catatan" placeholder="Masukkan keterangan tambahan" />
            </div>

        </div>
    </x-fieldset>

    {{-- Informasi Status --}}
    <div class="alert alert-info border-0 rounded-3 mb-0">
        <div class="d-flex align-items-start gap-2">
            <i class="fa-solid fa-circle-info mt-1"></i>
            <div>
                <div class="fw-semibold mb-1">Informasi Penduduk Sementara</div>
                <small>
                    Penduduk yang baru ditambahkan akan tercatat sebagai
                    <strong>Masih Tinggal</strong>. Tanggal kepergian dapat
                    dicatat kemudian melalui fitur Konfirmasi Kepergian.
                </small>
            </div>
        </div>
    </div>

</x-action-create>
