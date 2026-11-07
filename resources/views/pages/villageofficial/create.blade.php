<x-action-create href="{{ route('village-officials.store') }}" size="xl">

    {{-- A. Identitas Aparat --}}
    <x-fieldset label="A. Identitas Aparat">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text
                    name="nama"
                    label="Nama Lengkap"
                    placeholder="Contoh: Arman Saputra, S.AP"
                    hint="Nama lengkap aparat desa beserta gelar akademik."
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
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select
                    name="jenis_kelamin"
                    label="Jenis Kelamin"
                    :options="\App\Enums\GenderList::options()"
                    placeholder="Pilih Jenis Kelamin"
                    required
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    name="tempat_lahir"
                    label="Tempat Lahir"
                    placeholder="Contoh: Bangko"
                    required
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    type="date"
                    name="tgl_lahir"
                    label="Tanggal Lahir"
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
                    required
                />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text
                    name="pangkat_golongan"
                    label="Pangkat / Golongan"
                    placeholder="Contoh: Penata Muda (III/a)"
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
                    required
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_pengangkatan"
                    label="Tanggal Keputusan Pengangkatan"
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
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_pemberhentian"
                    label="Tanggal Keputusan Pemberhentian"
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
                    required
                />
            </div>

            <div class="col-12">
                <x-input-textarea
                    name="alamat"
                    label="Alamat"
                    placeholder="Masukkan alamat lengkap aparat desa..."
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
                    :rows="3"
                />
            </div>

        </div>
    </x-fieldset>

</x-action-create>