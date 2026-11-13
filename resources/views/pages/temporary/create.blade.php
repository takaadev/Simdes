<x-action-create href="{{ route('temporaryresidents.store') }}" size="lg" modalTitle="Tambah Penduduk Sementara"
    submitLabel="Simpan Data">
    {{-- A. Identitas Penduduk --}}
    <x-fieldset label="A. Identitas Penduduk">
        <div class="row g-3">

            <div class="col-12 col-md-4">
                <x-input-text name="nama_lengkap" label="Nama Lengkap" placeholder="Masukkan nama lengkap "
                    maxlength="150" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="nomor_identitas" label="Nomor Identitas (KTP / Paspor)"
                    placeholder="Masukkan nomor identitas" maxlength="30" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="jenis_kelamin" label="Jenis Kelamin" :options="\App\Enums\GenderList::options()"
                    placeholder="Pilih Jenis Kelamin" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="pekerjaan" label="Pekerjaan" placeholder="Masukkan pekerjaan" :options="\App\Enums\OccupationList::options()"
                    required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="tempat_lahir" label="Tempat Lahir" placeholder="Contoh: Bangko" maxlength="100"
                    required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text type="date" name="tgl_lahir" label="Tanggal Lahir" required />
            </div>

            <div class="col-12 col-md-2">
                <x-input-text type="number" name="umur" label="Umur (Tahun)" min="0" max="150"
                    required />
            </div>

        </div>
    </x-fieldset>

    {{-- B. Kewarganegaraan --}}
    <x-fieldset label="B. Kewarganegaraan">
        <div class="row g-3">

            <div class="col-12 col-md-4">
                <x-input-select name="kewarganegaraan" label="Kewarganegaraan" :options="\App\Enums\CitizenshipList::options()"
                    placeholder="Pilih Kewarganegaraan" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="kebangsaan" label="Kebangsaan" placeholder="Contoh: Indonesia" maxlength="100" />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="keturunan" label="Keturunan" placeholder="Masukkan keturunan" maxlength="50" />
            </div>

        </div>
    </x-fieldset>

    {{-- C. Informasi Kedatangan --}}
    <x-fieldset label="C. Informasi Kedatangan">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text name="datang_dari" label="Datang dari" placeholder="Masukkan daerah asal" maxlength="150"
                    required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_datang" label="Tanggal Kedatangan" required />
            </div>

            <div class="col-12">
                <x-input-text name="tujuan_kedatangan" label="Tujuan Kedatangan"
                    placeholder="Contoh: Bekerja, berkunjung, urusan keluarga" maxlength="255" required />
            </div>

            <div class="col-12">
                <x-input-textarea name="alamat_tujuan" label="Alamat Tujuan"
                    placeholder="Masukkan alamat tempat tinggal sementara di wilayah desa..." :rows="3"
                    required />
            </div>

        </div>
    </x-fieldset>

    {{-- D. Keterangan --}}
    <x-fieldset label="D. Keterangan">
        <div class="row g-3">

            <div class="col-12">
                <x-input-textarea name="catatan" label="Catatan"
                    placeholder="Masukkan keterangan tambahan mengenai penduduk sementara..."
                    hint="Informasi tambahan yang diperlukan dalam administrasi penduduk sementara."
                    :rows="3" />
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
