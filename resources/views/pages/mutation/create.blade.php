<x-action-create href="{{ route('mutations.store') }}" size="xl">

    {{-- A. Identitas Penduduk --}}
    <x-fieldset label="A. Identitas Penduduk">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text name="nama_lengkap" label="Nama Lengkap" placeholder="Masukkan nama lengkap sesuai KTP"
                    maxlength="150" required />
            </div>

            <div class="col-12 col-md-6">
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

            <div class="col-12 col-md-4">
                <x-input-select name="kewarganegaraan" label="Kewarganegaraan" :options="\App\Enums\CitizenshipList::options()"
                    placeholder="Pilih Kewarganegaraan" required />
            </div>

            <div class="col-12">
                <x-input-text name="kebangsaan" label="Kebangsaan" placeholder="Contoh: Indonesia" maxlength="100"
                    required />
            </div>

        </div>
    </x-fieldset>

    {{-- B. Informasi Mutasi --}}
    <x-fieldset label="B. Informasi Mutasi">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-select name="tipe_mutasi" label="Tipe Mutasi" :options="\App\Enums\MutationTypeList::options()" placeholder="Pilih Tipe Mutasi"
                    required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-select name="kategori_mutasi" label="Kategori Mutasi" :options="\App\Enums\MutationCategoryList::options()"
                    placeholder="Pilih Kategori Mutasi" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_mutasi" label="Tanggal Mutasi" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text name="tujuan_mutasi" label="Asal / Tujuan Mutasi"
                    placeholder="Contoh: Desa Tiaro atau Bangko" maxlength="255" />
            </div>

        </div>
    </x-fieldset>

    {{-- C. Keterangan --}}
    <x-fieldset label="C. Keterangan">
        <div class="row g-3">

            <div class="col-12">
                <x-input-textarea name="catatan" label="Catatan"
                    placeholder="Masukkan keterangan tambahan mengenai mutasi penduduk..."
                    hint="Informasi tambahan yang diperlukan dalam administrasi kependudukan." rows="3" />
            </div>

        </div>
    </x-fieldset>

</x-action-create>
