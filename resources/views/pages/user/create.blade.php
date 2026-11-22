<x-action-create href="{{ route('users.store') }}">
    <x-fieldset label="Data Perangkat Desa">
        <div class="row g-3">
            <div class="col-12">
                <x-input-select2 name="official_id" label="Perangkat Desa" :options="\App\Support\OfficialVillageOptions::options()"
                    placeholder="Pilih Perangkat Desa" required />
            </div>
        </div>
    </x-fieldset>

    <x-fieldset label="Informasi Akun">
        <div class="row g-3">
            <div class="col-12">
                <x-input-text type="email" name="email" label="Alamat Email"
                    placeholder="Masukkan alamat email (opsional)" />
            </div>

            <div class="col-12">
                <x-input-select name="role" label="Hak Akses" :options="\App\Enums\RoleAccessType::options()" placeholder="Pilih Hak Akses"
                    required />
            </div>

            <div class="col-12">
                <x-input-text
                    hint="Kata sandi default: @pemdes2026.
                    Kosongkan jika ingin menggunakan kata sandi default."
                    type="password" name="password" label="Kata Sandi" value="@pemdes2026"
                    placeholder="Masukkan kata sandi" />
            </div>
        </div>
    </x-fieldset>
</x-action-create>
