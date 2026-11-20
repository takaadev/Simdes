
<x-action-show :id="$data->id" modalTitle="Detail Mutasi Penduduk" size="lg">

    <x-fieldset label="A. Identitas Penduduk">
        <div class="row g-3">

            <div class="col-12 col-md-7">
                <x-input-text
                    name="nama_lengkap"
                    label="Nama Lengkap"
                    value="{{ $data->resident?->nama_lengkap }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-5">
                <x-input-text
                    name="nomor_identitas"
                    label="Nomor Identitas (KTP/Paspor)"
                    value="{{ $data->nomor_identitas }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select
                    name="jenis_kelamin"
                    label="Jenis Kelamin"
                    :options="\App\Enums\GenderList::options()"
                    value="{{ $data->resident?->jenis_kelamin?->value }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    name="tempat_lahir"
                    label="Tempat Lahir"
                    value="{{ $data->resident?->tempat_lahir }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    type="date"
                    name="tgl_lahir"
                    label="Tanggal Lahir"
                    value="{{ $data->resident?->tgl_lahir?->format('Y-m-d') }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select
                    name="status_perkawinan"
                    label="Status Perkawinan"
                    :options="\App\Enums\MaritalStatusList::options()"
                    value="{{ $data->resident?->status_perkawinan?->value }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select
                    name="agama"
                    label="Agama"
                    :options="\App\Enums\ReligionList::options()"
                    value="{{ $data->resident?->agama?->value }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select
                    name="pendidikan"
                    label="Pendidikan Terakhir"
                    :options="\App\Enums\EducationList::options()"
                    value="{{ $data->resident?->pendidikan?->value }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select2
                    name="pekerjaan"
                    label="Pekerjaan"
                    :options="\App\Enums\OccupationList::options()"
                    value="{{ $data->resident?->pekerjaan?->value }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select
                    name="status_dalam_keluarga"
                    label="Status dalam Keluarga"
                    :options="\App\Enums\RelationshipList::options()"
                    value="{{ $data->resident?->status_dalam_keluarga?->value }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select
                    name="dapat_membaca"
                    label="Dapat Membaca?"
                    :options="['1' => 'Ya', '0' => 'Tidak']"
                    value="{{ (int) ($data->resident?->dapat_membaca ?? 0) }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select
                    name="gol_darah"
                    label="Golongan Darah"
                    :options="\App\Enums\BloodList::options()"
                    value="{{ $data->resident?->gol_darah?->value }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select
                    name="kewarganegaraan"
                    label="Kewarganegaraan"
                    :options="\App\Enums\CitizenshipList::options()"
                    value="{{ $data->resident?->kewarganegaraan?->value }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    name="kebangsaan"
                    label="Kebangsaan"
                    value="{{ $data->resident?->kebangsaan }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    name="ayah"
                    label="Nama Ayah"
                    value="{{ $data->resident?->ayah }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    name="ibu"
                    label="Nama Ibu"
                    value="{{ $data->resident?->ibu }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    name="tempat_dikeluarkan"
                    label="Tempat Dikeluarkan KTP"
                    value="{{ $data->resident?->tempat_dikeluarkan }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    type="date"
                    name="tgl_dikeluarkan"
                    label="Tanggal Dikeluarkan KTP"
                    value="{{ $data->resident?->tgl_dikeluarkan?->format('Y-m-d') }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    type="date"
                    name="tgl_mulai_tinggal"
                    label="Mulai Tinggal di Desa"
                    value="{{ $data->resident?->tgl_mulai_tinggal?->format('Y-m-d') }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-2">
                <x-input-select
                    name="rt"
                    label="RT"
                    :options="\App\Enums\RtList::options()"
                    value="{{ $data->resident?->rt?->value }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-2">
                <x-input-select
                    name="rw"
                    label="RW"
                    :options="\App\Enums\RwList::options()"
                    value="{{ $data->resident?->rw?->value }}"
                    disabled
                />
            </div>

            <div class="col-12">
                <x-input-text
                    name="alamat"
                    label="Alamat"
                    value="{{ $data->resident?->alamat }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select
                    name="is_aktif"
                    label="Status Penduduk"
                    :options="['1' => 'Aktif', '0' => 'Tidak Aktif']"
                    value="{{ (int) ($data->resident?->is_aktif ?? 0) }}"
                    disabled
                />
            </div>

        </div>
    </x-fieldset>

    <x-fieldset label="B. Informasi Mutasi Masuk">
        <div class="row g-3">

            <div class="col-12 col-md-3">
                <x-input-select
                    name="tipe_mutasi"
                    label="Tipe Mutasi"
                    :options="\App\Enums\MutationTypeList::options()"
                    value="{{ $data->tipe_mutasi?->value }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-3">
                <x-input-select
                    name="kategori_mutasi"
                    label="Kategori Mutasi"
                    :options="\App\Enums\MutationCategoryList::options('BIRTH,INCOMING')"
                    value="{{ $data->kategori_mutasi?->value }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-3">
                <x-input-text
                    type="date"
                    name="tgl_mutasi"
                    label="Tanggal Mutasi"
                    value="{{ $data->tgl_mutasi?->format('Y-m-d') }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-3">
                <x-input-text
                    name="tujuan_mutasi"
                    label="Asal Mutasi"
                    value="{{ $data->tujuan_mutasi }}"
                    disabled
                />
            </div>

        </div>
    </x-fieldset>

    <x-fieldset label="C. Keterangan Tambahan Mutasi">
        <div class="row g-3">

            <div class="col-12">
                <x-input-textarea
                    name="catatan"
                    label="Catatan Mutasi"
                    placeholder="Keterangan tambahan mengenai mutasi penduduk..."
                    value="{{ $data->catatan }}"
                    hint="Informasi tambahan mengenai peristiwa mutasi penduduk."
                    rows="3"
                    disabled
                />
            </div>

        </div>
    </x-fieldset>

</x-action-show>
