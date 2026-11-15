<x-action-create href="{{ route('familycards.store') }}">

    {{-- A. Identitas Penduduk --}}
    <x-fieldset label="">
        <x-input-select2 name="resident_id" label="Pilih Penduduk" :options="App\Support\ResidentOptions::options()"
            placeholder="Cari nama atau NIK penduduk..." hint="Pilih penduduk yang akan menjadi kepala keluarga."
            required />
        <x-input-text name="kk" label="Nomor Kartu Keluarga" placeholder="Masukkan 16 digit nomor KK" maxlength="16"
            required />
    </x-fieldset>

</x-action-create>
