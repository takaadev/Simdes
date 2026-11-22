<x-action-edit :id="$data->id" href="{{ route('village-officials.update', $data->id) }}" size="lg">
    <x-fieldset label="A. Identitas Aparat">
        <div class="row g-3">

            <div class="col-12 col-md-8">
                <x-input-text
                    name="nama"
                    label="Nama Lengkap"
                    placeholder="Contoh: Arman Saputra, S.AP"
                    value="{{ $data->nama }}"
                    required
                />
            </div>

            <div class="col-12 col-md-6">
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

            <div class="col-12 col-md-6">
                <x-input-text
                    name="pangkat_golongan"
                    label="Pangkat/Gol"
                    placeholder="Contoh: Penata Muda Tingkat I (III/b)"
                    maxlength="16"
                    value="{{ $data->pangkat_golongan }}"
                />
            </div>

            <div class="col-12 col-md-6">
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

            <div class="col-12 col-md-6">
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

            <div class="col-12 col-md-4">
                <x-input-select
                    name="agama"
                    label="Agama"
                    :options="\App\Enums\ReligionList::options()"
                    value="{{ $data->agama->value ?? $data->agama }}"
                    required
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select
                    name="pendidikan"
                    label="Pendidikan Terakhir"
                    :options="\App\Enums\EducationList::options()"
                    value="{{ $data->pendidikan->value ?? $data->pendidikan }}"
                    required
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    name="nomor_pengangkatan"
                    label="Nomor Keputusan Pengangkatan"
                    placeholder="Contoh: 02/KEP/DS/2026"
                    value="{{ $data->nomor_pengangkatan }}"
                    required
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    type="date"
                    name="tgl_pengangkatan"
                    label="Tanggal Keputusan Pengangkatan"
                    value="{{ $data->tgl_pengangkatan?->format('Y-m-d') }}"
                    required
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select
                    name="status"
                    label="Status Aparat"
                    :options="\App\Enums\VillageOfficialStatusList::options()"
                    placeholder="Pilih Status"
                    value="{{ $data->status->value ?? $data->status }}"
                    required
                />
            </div>

            <div class="col-12 col-md-4">
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
                <x-input-text
                    name="alamat"
                    label="Alamat"
                    placeholder="Masukkan alamat lengkap aparat desa..."
                    value="{{ $data->alamat }}"
                    required
                />
            </div>

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