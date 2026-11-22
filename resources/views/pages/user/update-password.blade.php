<x-action-edit :id="$data->id" href="{{ route('users.update-password', $data->id) }}"
    icon="key" modalTitle="Ganti Sandi" labelSubmit="Ganti Sandi">
    <x-fieldset label="Ubah Kata Sandi">
        <div class="row g-3">
            <div class="col-12">
                <x-input-text type="password" name="password" label="Kata Sandi Baru" placeholder="Masukkan kata sandi baru"
                    required />
            </div>

            <div class="col-12">
                <x-input-text type="password" name="password_confirmation" label="Konfirmasi Kata Sandi"
                    placeholder="Ulangi kata sandi baru" required />
            </div>
        </div>
    </x-fieldset>
</x-action-edit>
