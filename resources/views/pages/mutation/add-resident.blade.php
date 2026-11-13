<x-action-create href="{{ route('mutations.to-resident') }}" size="lg" color="btn-simdes-excel" label="Mutasi ke BIP"
    icon="share" modalTitle="Mutasi ke Buku Induk Penduduk" submitLable="Kirim ke BIP">

    {{-- A. Data Mutasi Penduduk --}}
    <x-fieldset label="A. Data Mutasi Penduduk">
        <div class="col-12">
            <x-input-select name="mutation_id" label="Pilih Data Mutasi" :options="\App\Support\MutationToResidentOptions::options()" placeholder="Pilih Data Mutasi"
                required />
        </div>
    </x-fieldset>

    {{-- B. Data Kependudukan --}}
    <x-fieldset label="B. Data Kependudukan">
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <x-input-text name="nik" label="Nomor Induk Kependudukan (NIK)" placeholder="Masukkan 16 digit NIK"
                    maxlength="16" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="kk" label="Nomor Kartu Keluarga" placeholder="Masukkan 16 digit nomor KK"
                    maxlength="16" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="status_perkawinan" label="Status Perkawinan" :options="\App\Enums\MaritalStatusList::options()"
                    placeholder="Pilih Status Perkawinan" required />
            </div>
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

            <div class="col-12 col-md-6">
                <x-input-select name="status_dalam_keluarga" label="Status dalam Keluarga" :options="\App\Enums\RelationshipList::options()"
                    placeholder="Pilih Status dalam Keluarga" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-select name="dapat_membaca" label="Kemampuan Membaca" :options="['1' => 'Ya', '0' => 'Tidak']"
                    placeholder="Pilih Kemampuan Membaca" required />
            </div>

        </div>
    </x-fieldset>

    {{-- C. Alamat Tempat Tinggal --}}
    <x-fieldset label="C. Alamat Tempat Tinggal">
        <div class="row g-3">

            <div class="col-12 col-md-4">
                <x-input-select name="rt" label="RT" :options="\App\Enums\RtList::options()" placeholder="Pilih RT" />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="rw" label="RW" :options="\App\Enums\RwList::options()" placeholder="Pilih RW" />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="nomor_rumah" label="Nomor Rumah" placeholder="Contoh: 001 atau A12"
                    maxlength="10" />
            </div>

        </div>
    </x-fieldset>

    {{-- D. Keterangan --}}
    <x-fieldset label="D. Keterangan">
        <x-input-textarea name="catatan" label="Catatan" placeholder="Masukkan keterangan tambahan mengenai penduduk..."
            hint="Informasi tambahan yang diperlukan dalam administrasi kependudukan." :rows="3" />
    </x-fieldset>

</x-action-create>
