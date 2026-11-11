<x-action-show
    :id="$data->id"
    modalTitle="Detail Aparat Pemerintah Desa"
    size="xl"
>
    {{-- <x-fieldset label="A. Identitas Aparat">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text
                    name="nama"
                    label="Nama Lengkap"
                    value="{{ $data->nama }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-3">
                <x-input-text
                    name="niap"
                    label="NIAP"
                    value="{{ $data->niap }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-3">
                <x-input-text
                    name="nip"
                    label="NIP"
                    value="{{ $data->nip }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    name="jenis_kelamin"
                    label="Jenis Kelamin"
                    value="{{ $data->jenis_kelamin->label() ?? $data->jenis_kelamin }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    name="tempat_lahir"
                    label="Tempat Lahir"
                    value="{{ $data->tempat_lahir }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    type="date"
                    name="tgl_lahir"
                    label="Tanggal Lahir"
                    value="{{ format_date_input($data->tgl_lahir) }}"
                    disabled
                />
            </div>

        </div>
    </x-fieldset>


    <x-fieldset label="B. Pendidikan, Kepangkatan, dan Jabatan">
        <div class="row g-3">

            <div class="col-12 col-md-4">
                <x-input-text
                    name="pendidikan"
                    label="Pendidikan Terakhir"
                    value="{{ $data->pendidikan->label() ?? $data->pendidikan }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    name="pangkat_golongan"
                    label="Pangkat / Golongan"
                    value="{{ $data->pangkat_golongan }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    name="jabatan"
                    label="Jabatan"
                    value="{{ $data->position?->nama }}"
                    disabled
                />
            </div>

        </div>
    </x-fieldset>


    <x-fieldset label="C. Keputusan Pengangkatan">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text
                    name="nomor_pengangkatan"
                    label="Nomor Keputusan Pengangkatan"
                    value="{{ $data->nomor_pengangkatan }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_pengangkatan"
                    label="Tanggal Keputusan Pengangkatan"
                    value="{{ format_date_input($data->tgl_pengangkatan) }}"
                    disabled
                />
            </div>

        </div>
    </x-fieldset>


    <x-fieldset label="D. Keputusan Pemberhentian">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text
                    name="nomor_pemberhentian"
                    label="Nomor Keputusan Pemberhentian"
                    value="{{ $data->nomor_pemberhentian }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_pemberhentian"
                    label="Tanggal Keputusan Pemberhentian"
                    value="{{ format_date_input($data->tgl_pemberhentian) }}"
                    disabled
                />
            </div>

        </div>
    </x-fieldset>

    <x-fieldset label="E. Status dan Kontak">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text
                    name="status"
                    label="Status Aparat"
                    value="{{ $data->status->label() ?? $data->status }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    type="tel"
                    name="telpon"
                    label="Nomor Telepon"
                    value="{{ $data->telpon }}"
                    disabled
                />
            </div>

            <div class="col-12">
                <x-input-textarea
                    name="alamat"
                    label="Alamat"
                    value="{{ $data->alamat }}"
                    :rows="3"
                    disabled
                />
            </div>

        </div>
    </x-fieldset>

    <x-fieldset label="F. Keterangan">
        <div class="row g-3">

            <div class="col-12">
                <x-input-textarea
                    name="notes"
                    label="Catatan"
                    value="{{ $data->notes }}"
                    :rows="3"
                    disabled
                />
            </div>

        </div>
    </x-fieldset> --}}

</x-action-show>