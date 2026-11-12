<x-action-show :id="$data->id" modalTitle="Detail Mutasi Penduduk" size="xl">

    {{-- A. Identitas Penduduk --}}
    <x-fieldset label="A. Identitas Penduduk">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text name="nama_lengkap" label="Nama Lengkap" value="{{ $data->nama_lengkap }}" disabled />
            </div>

            <div class="col-12 col-md-6">
                <x-input-select name="jenis_kelamin" label="Jenis Kelamin" :options="\App\Enums\GenderList::options()"
                    value="{{ $data->jenis_kelamin->value ?? $data->jenis_kelamin }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="tempat_lahir" label="Tempat Lahir" value="{{ $data->tempat_lahir }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text type="date" name="tgl_lahir" label="Tanggal Lahir"
                    value="{{ $data->tgl_lahir?->format('Y-m-d') }}" disabled />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="kewarganegaraan" label="Kewarganegaraan" :options="\App\Enums\CitizenshipList::options()"
                    value="{{ $data->kewarganegaraan->value ?? $data->kewarganegaraan }}" disabled />
            </div>

            <div class="col-12">
                <x-input-text name="kebangsaan" label="Kebangsaan" value="{{ $data->kebangsaan }}" disabled />
            </div>

        </div>
    </x-fieldset>

    {{-- B. Informasi Mutasi --}}
    <x-fieldset label="B. Informasi Mutasi">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-select name="tipe_mutasi" label="Tipe Mutasi" :options="\App\Enums\MutationTypeList::options()"
                    value="{{ $data->tipe_mutasi->value ?? $data->tipe_mutasi }}" disabled />
            </div>

            <div class="col-12 col-md-6">
                <x-input-select name="kategori_mutasi" label="Kategori Mutasi" :options="\App\Enums\MutationCategoryList::options()"
                    value="{{ $data->kategori_mutasi->value ?? $data->kategori_mutasi }}" disabled />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_mutasi" label="Tanggal Mutasi"
                    value="{{ $data->tgl_mutasi?->format('Y-m-d') }}" disabled />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text name="tujuan_mutasi" label="Asal / Tujuan Mutasi" value="{{ $data->tujuan_mutasi }}"
                    disabled />
            </div>

        </div>
    </x-fieldset>

    {{-- C. Keterangan --}}
    <x-fieldset label="C. Keterangan">
        <div class="row g-3">

            <div class="col-12">
                <x-input-textarea name="catatan" label="Catatan"
                    placeholder="Keterangan tambahan mengenai mutasi penduduk..." value="{{ $data->catatan }}"
                    rows="3" disabled />
            </div>

        </div>
    </x-fieldset>

</x-action-show>
