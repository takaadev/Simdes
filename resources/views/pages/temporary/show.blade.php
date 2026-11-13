<x-action-show :id="$data->id" modalTitle="Detail Data Penduduk" size="xl">

    {{-- A. Identitas Penduduk --}}
    <x-fieldset label="A. Identitas Penduduk">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text name="nama_lengkap" label="Nama Lengkap" value="{{ $data->nama_lengkap }}" disabled />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text name="nik" label="Nomor Induk Kependudukan (NIK)" value="{{ $data->nik }}"
                    disabled />
            </div>

            <div class="col-12 col-md-6">
                <x-input-select name="jenis_kelamin" label="Jenis Kelamin" :options="\App\Enums\GenderList::options()"
                    value="{{ $data->jenis_kelamin->value ?? $data->jenis_kelamin }}" disabled />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text name="kk" label="Nomor Kartu Keluarga" value="{{ $data->kk }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="tempat_lahir" label="Tempat Lahir" value="{{ $data->tempat_lahir }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text type="date" name="tgl_lahir" label="Tanggal Lahir"
                    value="{{ $data->tgl_lahir?->format('Y-m-d') }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="status_perkawinan" label="Status Perkawinan" :options="\App\Enums\MaritalStatusList::options()"
                    value="{{ $data->status_perkawinan->value ?? $data->status_perkawinan }}" disabled />
            </div>

        </div>
    </x-fieldset>

    {{-- B. Data Kependudukan --}}
    <x-fieldset label="B. Data Kependudukan">
        <div class="row g-3">

            <div class="col-12 col-md-4">
                <x-input-select name="agama" label="Agama" :options="\App\Enums\ReligionList::options()"
                    value="{{ $data->agama->value ?? $data->agama }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="pendidikan" label="Pendidikan Terakhir" :options="\App\Enums\EducationList::options()"
                    value="{{ $data->pendidikan->value ?? $data->pendidikan }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="pekerjaan" label="Pekerjaan" :options="\App\Enums\OccupationList::options()"
                    value="{{ $data->pekerjaan->value ?? $data->pekerjaan }}" disabled />
            </div>

            <div class="col-12 col-md-3">
                <x-input-select name="kewarganegaraan" label="Kewarganegaraan" :options="\App\Enums\CitizenshipList::options()"
                    value="{{ $data->kewarganegaraan->value ?? $data->kewarganegaraan }}" disabled />
            </div>

            <div class="col-12 col-md-3">
                <x-input-text name="kebangsaan" label="Kebangsaan" value="{{ $data->kebangsaan }}" disabled />
            </div>

            <div class="col-12 col-md-3">
                <x-input-select name="status_dalam_keluarga" label="Status dalam Keluarga" :options="\App\Enums\RelationshipList::options()"
                    value="{{ $data->status_dalam_keluarga->value ?? $data->status_dalam_keluarga }}" disabled />
            </div>

            <div class="col-12 col-md-3">
                <x-input-select name="dapat_membaca" label="Dapat Membaca?" :options="['1' => 'Ya', '0' => 'Tidak']"
                    value="{{ (string) (int) $data->dapat_membaca }}" disabled />
            </div>

        </div>
    </x-fieldset>

    {{-- C. Alamat Tempat Tinggal --}}
    <x-fieldset label="C. Alamat Tempat Tinggal">
        <div class="row g-3">

            <div class="col-12 col-md-4">
                <x-input-select name="rt" label="RT" :options="\App\Enums\RtList::options()"
                    value="{{ $data->rt?->value ?? $data->rt }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="rw" label="RW" :options="\App\Enums\RwList::options()"
                    value="{{ $data->rw?->value ?? $data->rw }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="nomor_rumah" label="Nomor Rumah" value="{{ $data->nomor_rumah }}" disabled />
            </div>

        </div>
    </x-fieldset>

    {{-- D. Keterangan --}}
    <x-fieldset label="D. Keterangan">
        <div class="row g-3">

            <div class="col-12">
                <x-input-textarea name="catatan" label="Catatan" placeholder="Keterangan tambahan mengenai penduduk..."
                    value="{{ $data->catatan }}" rows="3" disabled />
            </div>

        </div>
    </x-fieldset>

</x-action-show>
