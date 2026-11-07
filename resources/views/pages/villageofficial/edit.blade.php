<x-action-edit
    :id="$data->id"
    href="{{ route('village-officials.update', $data->id) }}"
    size="xl"
>

    {{-- A. Identitas Aparat --}}
    <x-fieldset label="A. Identitas Aparat">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text
                    name="nama"
                    label="Nama Lengkap"
                    placeholder="Contoh: Arman Saputra, S.AP"
                    hint="Nama lengkap aparat desa beserta gelar akademik."
                    value="{{ $data->nama }}"
                    required
                />
            </div>

            <div class="col-12 col-md-3">
                <x-input-text
                    name="niap"
                    label="NIAP"
                    placeholder="Masukkan NIAP"
                    hint="Nomor Induk Aparat Pemerintah Desa."
                    maxlength="16"
                    value="{{ $data->niap }}"
                    required
                />
            </div>

            <div class="col-12 col-md-3">
                <x-input-text
                    name="nip"
                    label="NIP"
                    placeholder="Masukkan NIP"
                    hint="Kosongkan apabila bukan PNS."
                    maxlength="16"
                    value="{{ $data->nip }}"
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select
                    name="jenis_kelamin"
                    label="Jenis Kelamin"
                    :options="\App\Enums\GenderList::options()"
                    placeholder="Pilih Jenis Kelamin"
                    value="{{ $data->jenis_kelamin->value ?? $data->jenis_kelamin }}"
                    required
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    name="tempat_lahir"
                    label="Tempat Lahir"
                    placeholder="Contoh: Bangko"
                    value="{{ $data->tempat_lahir }}"
                    required
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    type="date"
                    name="tgl_lahir"
                    label="Tanggal Lahir"
                    value="{{ $data->tgl_lahir?->format('Y-m-d') }}"
                    required
                />
            </div>

        </div>
    </x-fieldset>

    {{-- B. Pendidikan, Kepangkatan, dan Jabatan --}}
    <x-fieldset label="B. Pendidikan, Kepangkatan, dan Jabatan">
        <div class="row g-3">

            <div class="col-12 col-md-4">
                <x-input-select
                    name="pendidikan"
                    label="Pendidikan Terakhir"
                    :options="\App\Enums\EducationList::options()"
                    placeholder="Pilih Pendidikan"
                    value="{{ $data->pendidikan->value ?? $data->pendidikan }}"
                    required
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    name="pangkat_golongan"
                    label="Pangkat / Golongan"
                    placeholder="Contoh: Penata Muda (III/a)"
                    value="{{ $data->pangkat_golongan }}"
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select
                    name="village_position_id"
                    label="Jabatan"
                    :options="\App\Models\VillagePosition::query()
                        ->where('is_active', true)
                        ->orderBy('kode')
                        ->pluck('nama', 'id')
                        ->toArray()"
                    placeholder="Pilih Jabatan"
                    value="{{ $data->village_position_id }}"
                    required
                />
            </div>

        </div>
    </x-fieldset>

    {{-- C. Keputusan Pengangkatan --}}
    <x-fieldset label="C. Keputusan Pengangkatan">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text
                    name="nomor_pengangkatan"
                    label="Nomor Keputusan Pengangkatan"
                    placeholder="Contoh: 02/KEP/DS/2026"
                    value="{{ $data->nomor_pengangkatan }}"
                    required
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_pengangkatan"
                    label="Tanggal Keputusan Pengangkatan"
                    value="{{ $data->tgl_pengangkatan?->format('Y-m-d') }}"
                    required
                />
            </div>

        </div>
    </x-fieldset>

    {{-- D. Keputusan Pemberhentian --}}
    <x-fieldset label="D. Keputusan Pemberhentian">
        <div class="row g-3">

            <div class="col-12">
                <span class="text-muted small">
                    Kosongkan apabila aparat masih aktif menjabat.
                </span>
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    name="nomor_pemberhentian"
                    label="Nomor Keputusan Pemberhentian"
                    placeholder="Contoh: 03/KEP/DS/2026"
                    value="{{ $data->nomor_pemberhentian }}"
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_pemberhentian"
                    label="Tanggal Keputusan Pemberhentian"
                    value="{{ $data->tgl_pemberhentian?->format('Y-m-d') }}"
                />
            </div>

        </div>
    </x-fieldset>

    {{-- E. Status dan Kontak --}}
    <x-fieldset label="E. Status dan Kontak">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-select
                    name="status"
                    label="Status Aparat"
                    :options="\App\Enums\VillageOfficialStatusList::options()"
                    placeholder="Pilih Status"
                    value="{{ $data->status->value ?? $data->status }}"
                    required
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    type="tel"
                    name="telpon"
                    label="Nomor Telepon"
                    placeholder="Contoh: 081234567890"
                    maxlength="30"
                    value="{{ $data->telpon }}"
                    required
                />
            </div>

            <div class="col-12">
                <x-input-textarea
                    name="alamat"
                    label="Alamat"
                    placeholder="Masukkan alamat lengkap aparat desa..."
                    value="{{ $data->alamat }}"
                    :rows="3"
                    required
                />
            </div>

        </div>
    </x-fieldset>

    {{-- F. Keterangan --}}
    <x-fieldset label="F. Keterangan">
        <div class="row g-3">

            <div class="col-12">
                <x-input-textarea
                    name="notes"
                    label="Catatan"
                    placeholder="Catatan tambahan terkait aparat desa..."
                    hint="Informasi tambahan mengenai kepegawaian atau penugasan."
                    value="{{ $data->notes }}"
                    :rows="3"
                />
            </div>

        </div>
    </x-fieldset>

</x-action-edit>