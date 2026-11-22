<x-action-edit :id="$data->id" href="{{ route('users.update', $data->id) }}">
    <x-fieldset label="Data Perangkat Desa">
        <div class="row g-3">
            <div class="col-12">
                <x-input-text name="official" label="Perangkat Desa"
                    value="{{ $data->villageOfficial ? $data->villageOfficial->nama . ' - ' . $data->villageOfficial->niap : '-' }}"
                    disabled />
            </div>
        </div>
    </x-fieldset>

    <x-fieldset label="Informasi Akun">
        <div class="row g-3">
            <div class="col-12">
                <x-input-text type="email" name="email" label="Alamat Email" value="{{ $data->email ?? '' }}"
                    placeholder="Masukkan alamat email (opsional)" />
            </div>

            <div class="col-12">
                <x-input-select name="role" label="Hak Akses" value="{{ $data->role?->value ?? ($data->role ?? '') }}"
                    :options="\App\Enums\RoleAccessType::options()" placeholder="Pilih Hak Akses" required />
            </div>
        </div>
    </x-fieldset>
</x-action-edit>
