<x-action-edit
    :id="$data->id"
    :href="route('recapts.update-note', $data->id)"
    submitLabel="Simpan Catatan"
    modalTitle="Catatan {{ $data->lingkungan }}"
>
    <x-fieldset label="Catatan Rekapitulasi">
        <div class="row g-3">
            <div class="col-12">
                <x-input-textarea
                    :id="'catatan_' . $data->id"
                    name="catatan"
                    label="Catatan ({{ $data->lingkungan }})"
                    placeholder="Masukkan catatan untuk rekapitulasi lingkungan ini..."
                    value="{{ $data->catatan }}"
                    rows="4"
                />
            </div>
        </div>
    </x-fieldset>
</x-action-edit>