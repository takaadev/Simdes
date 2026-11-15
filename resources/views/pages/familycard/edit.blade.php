<x-action-edit :id="$data->id" href="{{ route('familycards.update', $data->id) }}">
     <x-fieldset label="">
        <x-input-select2
            name="head_family"
            label="Kepala Keluarga"
            :options="App\Support\ResidentOptions::options($data->family_card_id)"
            value="{{ $data->id }}"
            disabled
        />
        <x-input-text :value="$data->nomor_kk" name="kk" label="Nomor Kartu Keluarga" placeholder="Masukkan 16 digit nomor KK" maxlength="16"
            required />
    </x-fieldset>
</x-action-edit>
