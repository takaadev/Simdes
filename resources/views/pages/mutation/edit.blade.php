<x-action-edit :id="$data->id" href="{{ route('mutations.update', $data->id) }}" size="lg">
    <x-fieldset label="A. Identitas Penduduk">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text
                    name="nama_lengkap"
                    label="Nama Lengkap"
                    placeholder="Masukkan nama lengkap sesuai identitas"
                    maxlength="150"
                    value="{{ $data->nama_lengkap }}"
                    required
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    name="nomor_identitas"
                    label="Nomor Identitas (KTP/Paspor)"
                    placeholder="Masukkan NIK atau nomor paspor"
                    maxlength="30"
                    value="{{ $data->nomor_identitas }}"
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select
                    name="jenis_kelamin"
                    label="Jenis Kelamin"
                    :options="\App\Enums\GenderList::options()"
                    placeholder="Pilih jenis kelamin"
                    value="{{ $data->jenis_kelamin->value ?? $data->jenis_kelamin }}"
                    required
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    name="tempat_lahir"
                    label="Tempat Lahir"
                    placeholder="Contoh: Bangko"
                    maxlength="100"
                    value="{{ $data->tempat_lahir }}"
                    required
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    type="date"
                    name="tgl_lahir"
                    label="Tanggal Lahir"
                    value="{{ $data->tgl_lahir?->format('Y-m-d') }}"
                    required
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-select
                    name="kewarganegaraan"
                    label="Kewarganegaraan"
                    :options="\App\Enums\CitizenshipList::options()"
                    placeholder="Pilih kewarganegaraan"
                    value="{{ $data->kewarganegaraan->value ?? $data->kewarganegaraan }}"
                    required
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    name="kebangsaan"
                    label="Kebangsaan"
                    placeholder="Contoh: Indonesia"
                    maxlength="100"
                    value="{{ $data->kebangsaan }}"
                    required
                />
            </div>

        </div>
    </x-fieldset>

    <x-fieldset label="B. Informasi Mutasi">
        <div class="row g-3">

            <div class="col-12 col-md-4">
                <x-input-select
                    name="tipe_mutasi"
                    label="Tipe Mutasi"
                    :options="\App\Enums\MutationTypeList::options()"
                    placeholder="Pilih tipe mutasi"
                    value="{{ $data->tipe_mutasi->value ?? $data->tipe_mutasi }}"
                    required
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select
                    name="kategori_mutasi"
                    label="Kategori Mutasi"
                    :options="\App\Enums\MutationCategoryList::options()"
                    placeholder="Pilih kategori mutasi"
                    value="{{ $data->kategori_mutasi->value ?? $data->kategori_mutasi }}"
                    required
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    type="date"
                    name="tgl_mutasi"
                    label="Tanggal Mutasi"
                    value="{{ $data->tgl_mutasi?->format('Y-m-d') }}"
                    required
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    name="tujuan_mutasi"
                    label="Asal / Tujuan Mutasi"
                    placeholder="Contoh: Desa Tiaro atau Bangko"
                    maxlength="255"
                    value="{{ $data->tujuan_mutasi }}"
                />
            </div>

            <div class="col-12 col-md-3">
                <x-input-select
                    name="rw_tujuan"
                    label="RW Tujuan"
                    :options="\App\Enums\RwList::options()"
                    placeholder="Pilih RW tujuan"
                    value="{{ $data->rw_tujuan }}"
                />
            </div>

            <div class="col-12 col-md-3">
                <x-input-select
                    name="rt_tujuan"
                    label="RT Tujuan"
                    :options="\App\Enums\RtList::options()"
                    placeholder="Pilih RT tujuan"
                    value="{{ $data->rt_tujuan }}"
                />
            </div>

        </div>
    </x-fieldset>

    <x-fieldset label="C. Keterangan Tambahan">
        <div class="row g-3">

            <div class="col-12">
                <x-input-textarea
                    name="catatan"
                    label="Catatan"
                    placeholder="Masukkan keterangan tambahan mengenai mutasi penduduk..."
                    hint="Informasi tambahan yang diperlukan dalam administrasi kependudukan."
                    value="{{ $data->catatan }}"
                    rows="3"
                />
            </div>

        </div>
    </x-fieldset>

</x-action-edit>
