<x-action-create href="{{ route('village-positions.store') }}">
    <x-fieldset label="A. Data Jabatan">
        <div class="row g-3">

            <div class="col-12 col-md-3">
                <x-input-text
                    name="kode"
                    label="Kode Jabatan"
                    placeholder="Contoh: A1"
                    maxlength="10"
                    required
                />
            </div>

            <div class="col-12 col-md-9">
                <x-input-text
                    name="nama"
                    label="Nama Jabatan"
                    placeholder="Contoh: Kepala Desa"
                    required
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-select
                    name="is_active"
                    label="Status Jabatan"
                    :options="[
                        '1' => 'Aktif',
                        '0' => 'Tidak Aktif',
                    ]"
                    placeholder="Pilih Status Jabatan"
                    required
                />
            </div>

        </div>
    </x-fieldset>
</x-action-create>