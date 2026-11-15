<x-action-create href="{{ route('familycards.mutation') }}" color="btn-simdes-excel" label="Mutasi KK"
    icon="fa-solid fa-right-left" submitLable="Simpan Mutasi" modalTitle="Mutasi Kartu Keluarga">
    <x-fieldset label="Identitas Penduduk">
        <x-input-text name="nik" label="Masukkan NIK Penduduk" placeholder="Masukkan 16 digit NIK penduduk"
            maxlength="16" required />
    </x-fieldset>
    <x-fieldset label="Kartu Keluarga Tujuan">
        <x-input-text name="kk_tujuan" label="Masukkan Nomor KK Tujuan" placeholder="Masukkan 16 digit nomor KK tujuan"
            maxlength="16" required />
        <x-input-select name="hubungan" label="Hubungan dalam Keluarga" :options="App\Enums\RelationshipList::options()"
            placeholder="Pilih hubungan keluarga" required />
    </x-fieldset>
    <x-fieldset label="Informasi Mutasi">
        <x-input-text type="date" name="tgl_mutasi" label="Tanggal Mutasi" required />
        <x-input-textarea name="catatan" label="Catatan" placeholder="Masukkan keterangan mutasi (opsional)" />
    </x-fieldset>
</x-action-create>
