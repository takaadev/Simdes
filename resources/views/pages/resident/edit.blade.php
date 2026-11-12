<x-action-edit :id="$data->id" href="{{ route('residents.update', $data->id) }}" size="xl">

    {{-- A. Identitas Penduduk --}}
    <x-fieldset label="A. Identitas Penduduk">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text name="nama_lengkap" label="Nama Lengkap" placeholder="Masukkan nama lengkap sesuai KTP"
                    maxlength="150" :value="$data->nama_lengkap" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text name="nik" label="Nomor Induk Kependudukan (NIK)" placeholder="Masukkan 16 digit NIK"
                    maxlength="16" :value="$data->nik" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-select name="jenis_kelamin" label="Jenis Kelamin" :options="\App\Enums\GenderList::options()"
                    placeholder="Pilih Jenis Kelamin" :value="$data->jenis_kelamin->value ?? $data->jenis_kelamin" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text name="kk" label="Nomor Kartu Keluarga" placeholder="Masukkan 16 digit nomor KK"
                    maxlength="16" :value="$data->kk" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="tempat_lahir" label="Tempat Lahir" placeholder="Contoh: Bangko" maxlength="100"
                    :value="$data->tempat_lahir" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text type="date" name="tgl_lahir" label="Tanggal Lahir" :value="$data->tgl_lahir?->format('Y-m-d')" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="status_perkawinan" label="Status Perkawinan" :options="\App\Enums\MaritalStatusList::options()"
                    placeholder="Pilih Status Perkawinan" :value="$data->status_perkawinan->value ?? $data->status_perkawinan" required />
            </div>

        </div>
    </x-fieldset>

    {{-- B. Data Kependudukan --}}
    <x-fieldset label="B. Data Kependudukan">
        <div class="row g-3">

            <div class="col-12 col-md-4">
                <x-input-select name="agama" label="Agama" :options="\App\Enums\ReligionList::options()" placeholder="Pilih Agama"
                    :value="$data->agama->value ?? $data->agama" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="pendidikan" label="Pendidikan Terakhir" :options="\App\Enums\EducationList::options()"
                    placeholder="Pilih Pendidikan" :value="$data->pendidikan->value ?? $data->pendidikan" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="pekerjaan" label="Pekerjaan" :options="\App\Enums\OccupationList::options()" placeholder="Pilih Pekerjaan"
                    :value="$data->pekerjaan->value ?? $data->pekerjaan" required />
            </div>

            <div class="col-12 col-md-3">
                <x-input-select name="kewarganegaraan" label="Kewarganegaraan" :options="\App\Enums\CitizenshipList::options()"
                    placeholder="Pilih Kewarganegaraan" :value="$data->kewarganegaraan->value ?? $data->kewarganegaraan" required />
            </div>

            <div class="col-12 col-md-3">
                <x-input-text name="kebangsaan" label="Kebangsaan" placeholder="Contoh: Indonesia" maxlength="100"
                    :value="$data->kebangsaan" required />
            </div>

            <div class="col-12 col-md-3">
                <x-input-select name="status_dalam_keluarga" label="Status dalam Keluarga" :options="\App\Enums\RelationshipList::options()"
                    placeholder="Pilih Status dalam Keluarga" :value="$data->status_dalam_keluarga->value ?? $data->status_dalam_keluarga" required />
            </div>

            <div class="col-12 col-md-3">
                <x-input-select name="dapat_membaca" label="Dapat Membaca?" :options="['1' => 'Ya', '0' => 'Tidak']"
                    placeholder="Pilih Status Membaca" :value="(string) (int) $data->dapat_membaca" required />
            </div>

        </div>
    </x-fieldset>

    {{-- C. Alamat Tempat Tinggal --}}
    <x-fieldset label="C. Alamat Tempat Tinggal">
        <div class="row g-3">

            <div class="col-12 col-md-4">
                <x-input-select name="rt" label="RT" :options="\App\Enums\RtList::options()" placeholder="Pilih RT"
                    :value="$data->rt?->value ?? $data->rt" />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="rw" label="RW" :options="\App\Enums\RwList::options()" placeholder="Pilih RW"
                    :value="$data->rw?->value ?? $data->rw" />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="nomor_rumah" label="Nomor Rumah" placeholder="Contoh: 001 atau A12" maxlength="10"
                    :value="$data->nomor_rumah" />
            </div>

        </div>
    </x-fieldset>

    {{-- D. Keterangan --}}
    <x-fieldset label="D. Keterangan">
        <div class="row g-3">

            <div class="col-12">
                <x-input-textarea name="catatan" label="Catatan"
                    placeholder="Masukkan keterangan tambahan mengenai penduduk..."
                    hint="Informasi tambahan yang diperlukan dalam administrasi kependudukan." :value="$data->catatan"
                    :rows="3" />
            </div>

        </div>
    </x-fieldset>

</x-action-edit>
