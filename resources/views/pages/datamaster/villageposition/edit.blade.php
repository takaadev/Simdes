<x-action-edit :id="$data->id" href="{{ route('village-positions.update', $data->id) }}">
    <x-fieldset label="A. Data Jabatan">
        <div class="row g-3">

            <div class="col-12 col-md-3">
                <x-input-text name="kode" :value="$data->kode" label="Kode Jabatan" placeholder="Contoh: A1" maxlength="10"
                    required />
            </div>

            <div class="col-12 col-md-9">
                <x-input-text name="nama" :value="$data->nama" label="Nama Jabatan" placeholder="Contoh: Kepala Desa"
                    required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-select name="is_active" :value="$data->is_active" label="Status Jabatan" :options="[
                    '1' => 'Aktif',
                    '0' => 'Tidak Aktif',
                ]"
                    placeholder="Pilih Status Jabatan" required />
            </div>

        </div>
    </x-fieldset>
</x-action-edit>
