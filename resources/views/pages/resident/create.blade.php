<x-action-create href="{{ route('residents.store') }}" size="xl">

    {{-- A. Identitas Penduduk --}}
    <x-fieldset label="A. Identitas Penduduk">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text name="nama_lengkap" label="Nama Lengkap" placeholder="Masukkan nama lengkap sesuai KTP"
                    maxlength="150" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text name="nik" label="Nomor Induk Kependudukan (NIK)" placeholder="Masukkan 16 digit NIK"
                    maxlength="16" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-select name="jenis_kelamin" label="Jenis Kelamin" :options="\App\Enums\GenderList::options()"
                    placeholder="Pilih Jenis Kelamin" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text name="kk" label="Nomor Kartu Keluarga" placeholder="Masukkan 16 digit nomor KK"
                    maxlength="16" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="tempat_lahir" label="Tempat Lahir" placeholder="Contoh: Bangko" maxlength="100"
                    required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text type="date" name="tgl_lahir" label="Tanggal Lahir" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="status_perkawinan" label="Status Perkawinan" :options="\App\Enums\MaritalStatusList::options()"
                    placeholder="Pilih Status Perkawinan" required />
            </div>

        </div>
    </x-fieldset>

    {{-- B. Data Kependudukan --}}
    <x-fieldset label="B. Data Kependudukan">
        <div class="row g-3">

            <div class="col-12 col-md-4">
                <x-input-select name="agama" label="Agama" :options="\App\Enums\ReligionList::options()" placeholder="Pilih Agama" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="pendidikan" label="Pendidikan Terakhir" :options="\App\Enums\EducationList::options()"
                    placeholder="Pilih Pendidikan" required />
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
                <x-input-text name="kebangsaan" label="Kebangsaan" placeholder="Contoh: Indonesia" maxlength="100"
                    required />
            </div>

            <div class="col-12 col-md-3">
                <x-input-select name="status_dalam_keluarga" label="Status dalam Keluarga" :options="\App\Enums\RelationshipList::options()"
                    placeholder="Pilih Status dalam Keluarga" required />
            </div>

            <div class="col-12 col-md-3">
                <x-input-select name="dapat_membaca" label="Dapat Membaca?" :options="['1' => 'Ya', '0' => 'Tidak']"
                    placeholder="Pilih Status Membaca" required />
            </div>
        </div>
    </x-fieldset>

    {{-- C. Alamat Tempat Tinggal --}}
    <x-fieldset label="C. Alamat Tempat Tinggal">
        <div class="row g-3">

            <div class="col-12 col-md-4">
                <x-input-select name="dusun_id" label="Dusun" :options="\App\Models\Dusun::query()->orderBy('name')->pluck('name', 'id')->toArray()" placeholder="Pilih Dusun" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="rt" label="RT" :options="\App\Enums\RtList::options()" placeholder="Pilih RT" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="rw" label="RW" :options="\App\Enums\RwList::options()" placeholder="Pilih RW" required />
            </div>

        </div>
    </x-fieldset>

    {{-- D. Keterangan --}}
    <x-fieldset label="D. Keterangan">
        <div class="row g-3">

            <div class="col-12">
                <x-input-textarea name="catatan" label="Catatan"
                    placeholder="Masukkan keterangan tambahan mengenai penduduk..."
                    hint="Informasi tambahan yang diperlukan dalam administrasi kependudukan." :rows="3" />
            </div>

        </div>
    </x-fieldset>

</x-action-create>
