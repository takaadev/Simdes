<x-action-create href="{{ route('mutations.store') }}" size="lg" icon="person-arrow-down-to-line" label="Mutasi Masuk">
    <x-fieldset label="A. Identitas Penduduk">
        <div class="row g-3">
            <div class="col-12 col-md-7">
                <x-input-text name="nama_lengkap" label="Nama Lengkap" placeholder="Masukkan nama lengkap" maxlength="150"
                    required />
            </div>

            <div class="col-12 col-md-5">
                <x-input-text name="nomor_identitas" label="Nomor Identitas (KTP/Paspor)"
                    placeholder="Masukkan nomor KTP atau Paspor" maxlength="30" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="jenis_kelamin" label="Jenis Kelamin" :options="\App\Enums\GenderList::options()"
                    placeholder="Pilih jenis kelamin" required />
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
                    placeholder="Pilih status perkawinan" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="agama" label="Agama" :options="\App\Enums\ReligionList::options()" placeholder="Pilih agama" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="pendidikan" label="Pendidikan Terakhir" :options="\App\Enums\EducationList::options()"
                    placeholder="Pilih pendidikan" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select2 name="pekerjaan" label="Pekerjaan" :options="\App\Enums\OccupationList::options()" placeholder="Pilih pekerjaan"
                    required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="status_dalam_keluarga" label="Status dalam Keluarga" :options="\App\Enums\RelationshipList::options()"
                    placeholder="Pilih status dalam keluarga" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="dapat_membaca" label="Dapat Membaca?" :options="['1' => 'Ya', '0' => 'Tidak']"
                    placeholder="Pilih status membaca" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="gol_darah" label="Golongan Darah" :options="\App\Enums\BloodList::options()"
                    placeholder="Pilih golongan darah" />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="kewarganegaraan" label="Kewarganegaraan" :options="\App\Enums\CitizenshipList::options()"
                    placeholder="Pilih kewarganegaraan" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="kebangsaan" label="Kebangsaan" placeholder="Contoh: Indonesia" maxlength="100"
                    required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="ayah" label="Nama Ayah" placeholder="Masukkan nama ayah" maxlength="150" />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="ibu" label="Nama Ibu" placeholder="Masukkan nama ibu" maxlength="150" />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="tempat_dikeluarkan" label="Tempat Dikeluarkan KTP" placeholder="Contoh: Merangin"
                    maxlength="100" />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text type="date" name="tgl_dikeluarkan" label="Tanggal Dikeluarkan KTP" />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text type="date" name="tgl_mulai_tinggal" label="Mulai Tinggal di Desa" />
            </div>

            <div class="col-12 col-md-2">
                <x-input-select name="rt" label="RT" :options="\App\Enums\RtList::options()" placeholder="Pilih RT" />
            </div>

            <div class="col-12 col-md-2">
                <x-input-select name="rw" label="RW" :options="\App\Enums\RwList::options()" placeholder="Pilih RW" />
            </div>

            <div class="col-12">
                <x-input-text name="alamat" label="Alamat" placeholder="Masukkan alamat lengkap" />
            </div>
        </div>
    </x-fieldset>

    <x-fieldset label="B. Informasi Mutasi Masuk">
        <div class="row g-3">
            <div class="col-12 col-md-3">
                <x-input-text type="date" name="tgl_mutasi" label="Tanggal Mutasi" required />
            </div>

            <div class="col-12 col-md-3">
                <x-input-select name="kategori_mutasi" label="Kategori Mutasi" :options="\App\Enums\MutationCategoryList::options('BIRTH,INCOMING')"
                    placeholder="Pilih kategori mutasi" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text name="tujuan_mutasi" label="Asal Mutasi"
                    placeholder="Contoh: Desa asal atau Kecamatan Bangko" maxlength="255" required />
            </div>
        </div>
    </x-fieldset>

    <x-fieldset label="C. Keterangan Tambahan Mutasi">
        <div class="row g-3">
            <div class="col-12">
                <x-input-textarea name="catatan_mutasi" label="Catatan Mutasi"
                    placeholder="Keterangan tambahan mengenai mutasi masuk..."
                    hint="Informasi tambahan mengenai peristiwa mutasi masuk." rows="3" />
            </div>
        </div>
    </x-fieldset>
</x-action-create>
