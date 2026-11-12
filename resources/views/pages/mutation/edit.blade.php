<x-action-edit :id="$data->id" href="{{ route('mutations.update', $data->id) }}" size="xl">

    {{-- A. Identitas Penduduk --}}
    <x-fieldset label="A. Identitas Penduduk">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text name="nama_lengkap" label="Nama Lengkap" placeholder="Masukkan nama lengkap sesuai KTP"
                    maxlength="150" value="{{ $data->nama_lengkap }}" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-select name="jenis_kelamin" label="Jenis Kelamin" :options="\App\Enums\GenderList::options()"
                    placeholder="Pilih Jenis Kelamin" value="{{ $data->jenis_kelamin->value ?? $data->jenis_kelamin }}"
                    required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="tempat_lahir" label="Tempat Lahir" placeholder="Contoh: Bangko" maxlength="100"
                    value="{{ $data->tempat_lahir }}" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text type="date" name="tgl_lahir" label="Tanggal Lahir"
                    value="{{ $data->tgl_lahir?->format('Y-m-d') }}" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="kewarganegaraan" label="Kewarganegaraan" :options="\App\Enums\CitizenshipList::options()"
                    placeholder="Pilih Kewarganegaraan"
                    value="{{ $data->kewarganegaraan->value ?? $data->kewarganegaraan }}" required />
            </div>

            <div class="col-12">
                <x-input-text name="kebangsaan" label="Kebangsaan" placeholder="Contoh: Indonesia" maxlength="100"
                    value="{{ $data->kebangsaan }}" required />
            </div>

        </div>
    </x-fieldset>

    {{-- B. Informasi Mutasi --}}
    <x-fieldset label="B. Informasi Mutasi">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-select name="tipe_mutasi" label="Tipe Mutasi" :options="\App\Enums\MutationTypeList::options()" placeholder="Pilih Tipe Mutasi"
                    value="{{ $data->tipe_mutasi->value ?? $data->tipe_mutasi }}" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-select name="kategori_mutasi" label="Kategori Mutasi" :options="\App\Enums\MutationCategoryList::options()"
                    placeholder="Pilih Kategori Mutasi"
                    value="{{ $data->kategori_mutasi->value ?? $data->kategori_mutasi }}" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_mutasi" label="Tanggal Mutasi"
                    value="{{ $data->tgl_mutasi?->format('Y-m-d') }}" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text name="tujuan_mutasi" label="Asal / Tujuan Mutasi"
                    placeholder="Contoh: Desa Tiaro atau Bangko" maxlength="255" value="{{ $data->tujuan_mutasi }}" />
            </div>

        </div>
    </x-fieldset>

    {{-- C. Keterangan --}}
    <x-fieldset label="C. Keterangan">
        <div class="row g-3">

            <div class="col-12">
                <x-input-textarea name="catatan" label="Catatan"
                    placeholder="Masukkan keterangan tambahan mengenai mutasi penduduk..."
                    hint="Informasi tambahan yang diperlukan dalam administrasi kependudukan."
                    value="{{ $data->catatan }}" rows="3" />
            </div>

        </div>
    </x-fieldset>

</x-action-edit>
