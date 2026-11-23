<x-action-edit id="sistem" variant="button" label="Sistem" modalTitle="Perbarui Pengaturan Sistem"
    color="btn-simdes-accent" href="#">

    <x-fieldset label="Identitas Aplikasi">
        <x-input-text name="app_name" value="{{ config('app.name') }}" label="Nama Aplikasi" disabled
            hint="Nama aplikasi tidak bisa diubah" />
        <x-input-text type="file" name="logo" id="logo" label="Logo"
            accept="image/png,image/jpeg,image/webp" />
        <img id="preview-logo" src="" alt="Logo Aplikasi" class="img-thumbnail"
            style="max-height: 100px; display: none;">
        <x-input-textarea name="deskripsi" label="Deskripsi Aplikasi" />
    </x-fieldset>

    <x-fieldset label="Konfigurasi Sistem">
        @php
            $timezones = collect(timezone_identifiers_list())
                ->mapWithKeys(fn($timezone) => [$timezone => $timezone])
                ->all();
        @endphp

        <x-input-select2 name="time_zone" label="Zona Waktu" :options="$timezones" value="Asia/Jakarta" />
    </x-fieldset>
</x-action-edit>

@push('footer')
    <script>
        $(document).on('change', '#logo', function() {
            const file = this.files[0];
            if (!file) return;

            $('#preview-logo').attr('src', URL.createObjectURL(file)).show();
        });

        $(document).on('hidden.bs.modal', '[id*="sistem"]', function() {
            $(this).find('[name="logo"]').val('');
        });
    </script>
@endpush
