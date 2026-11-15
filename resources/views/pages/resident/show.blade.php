<x-action-show :id="$data->id" modalTitle="Detail Data Penduduk" size="lg">

    <x-fieldset label="Identitas Penduduk">
        <div class="row g-3">

            <div class="col-12 col-md-4">
                <x-input-text name="nama_lengkap" label="Nama Lengkap" value="{{ $data->nama_lengkap }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="nik" label="NIK" value="{{ $data->nik }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="kk" label="Nomor KK" value="{{ $data->kk }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="jenis_kelamin" label="Jenis Kelamin" :options="\App\Enums\GenderList::options()"
                    value="{{ $data->jenis_kelamin?->value ?? $data->jenis_kelamin }}" disabled />
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
                    value="{{ $data->status_perkawinan?->value ?? $data->status_perkawinan }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="agama" label="Agama" :options="\App\Enums\ReligionList::options()"
                    value="{{ $data->agama?->value ?? $data->agama }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="pendidikan" label="Pendidikan Terakhir" :options="\App\Enums\EducationList::options()"
                    value="{{ $data->pendidikan?->value ?? $data->pendidikan }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="pekerjaan" label="Pekerjaan" :options="\App\Enums\OccupationList::options()"
                    value="{{ $data->pekerjaan?->value ?? $data->pekerjaan }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="status_dalam_keluarga" label="Status dalam Keluarga" :options="\App\Enums\RelationshipList::options()"
                    value="{{ $data->status_dalam_keluarga?->value ?? $data->status_dalam_keluarga }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="dapat_membaca" label="Dapat Membaca?" :options="['1' => 'Ya', '0' => 'Tidak']"
                    value="{{ (string) (int) $data->dapat_membaca }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="gol_darah" label="Golongan Darah" :options="\App\Enums\BloodList::options()"
                    value="{{ $data->gol_darah?->value ?? $data->gol_darah }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="kewarganegaraan" label="Kewarganegaraan" :options="\App\Enums\CitizenshipList::options()"
                    value="{{ $data->kewarganegaraan?->value ?? $data->kewarganegaraan }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="kebangsaan" label="Kebangsaan" value="{{ $data->kebangsaan }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="ayah" label="Nama Ayah" value="{{ $data->ayah }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="ibu" label="Nama Ibu" value="{{ $data->ibu }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="tempat_dikeluarkan" label="Tempat Dikeluarkan KTP"
                    value="{{ $data->tempat_dikeluarkan }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text type="date" name="tgl_dikeluarkan" label="Tanggal Dikeluarkan KTP"
                    value="{{ $data->tgl_dikeluarkan?->format('Y-m-d') }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text type="date" name="tgl_mulai_tinggal" label="Mulai Tinggal di Desa"
                    value="{{ $data->tgl_mulai_tinggal?->format('Y-m-d') }}" disabled />
            </div>

            <div class="col-12 col-md-2">
                <x-input-select name="rt" label="RT" :options="\App\Enums\RtList::options()" value="{{ $data->rt }}"
                    disabled />
            </div>

            <div class="col-12 col-md-2">
                <x-input-select name="rw" label="RW" :options="\App\Enums\RwList::options()" value="{{ $data->rw }}"
                    disabled />
            </div>

            <div class="col-12">
                <x-input-text name="alamat" label="Alamat Lengkap" value="{{ $data->alamat }}" disabled />
            </div>

            <div class="col-12">
                <x-input-textarea name="catatan" label="Catatan"
                    placeholder="Keterangan tambahan mengenai penduduk..." value="{{ $data->catatan }}"
                    :rows="3" disabled />
            </div>

        </div>
    </x-fieldset>

</x-action-show>
