<x-action-edit
    :id="$data->id"
    href="{{ route('village-regulations.update', $data->id) }}"
    size="lg"
>

    {{-- A. Informasi Peraturan --}}
    <x-fieldset label="A. Informasi Peraturan">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-select
                    name="jenis_peraturan"
                    label="Jenis Peraturan di Desa"
                    :options="\App\Enums\RegulationTypeList::options()"
                    placeholder="-- Pilih Jenis Peraturan --"
                    value="{{ $data->jenis_peraturan?->value ?? $data->jenis_peraturan }}"
                    required
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    name="nomor_peraturan"
                    label="Nomor Peraturan"
                    placeholder="Contoh: 01 Tahun 2026"
                    value="{{ $data->nomor_peraturan }}"
                    required
                />
            </div>
            <div class="col-12">
                <x-input-text
                    name="nama_peraturan"
                    label="Judul atau Tentang Peraturan"
                    placeholder="Masukkan judul atau pokok materi peraturan"
                    value="{{ $data->nama_peraturan }}"
                    required
                />
            </div>

            <div class="col-12">
                <x-input-textarea
                    name="uraian"
                    label="Uraian Peraturan"
                    placeholder="Tuliskan ringkasan substansi pokok peraturan..."
                    value="{{ $data->uraian }}"
                    :rows="3"
                />
            </div>

        </div>
    </x-fieldset>

    {{-- B. Penetapan --}}
    <x-fieldset label="B. Penetapan">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_ditetapkan"
                    label="Tanggal Ditetapkan"
                    value="{{ $data->tgl_ditetapkan?->format('Y-m-d') }}"
                    required
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_kesepakatan"
                    label="Tanggal Kesepakatan"
                    value="{{ $data->tgl_kesepakatan?->format('Y-m-d') }}"
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    name="nomor_dilaporkan"
                    label="Nomor Dilaporkan"
                    placeholder="Masukkan nomor pelaporan"
                    value="{{ $data->nomor_dilaporkan }}"
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_dilaporkan"
                    label="Tanggal Dilaporkan"
                    value="{{ $data->tgl_dilaporkan?->format('Y-m-d') }}"
                />
            </div>

        </div>
    </x-fieldset>

    {{-- C. Dokumen dan Catatan --}}
    <x-fieldset label="Dokumen dan Catatan">
        <div class="row g-3">

            <div class="col-12">
                <x-input-text
                    type="file"
                    name="dokumen"
                    label="Dokumen Peraturan"
                    accept=".pdf,application/pdf"
                    hint="Kosongkan apabila tidak ingin mengganti dokumen. Format PDF."
                />

                @if ($data->dokumen)
                    <div class="mt-2">
                        <a
                            href="{{ asset('storage/' . $data->dokumen) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="btn btn-sm btn-simdes-outline"
                        >
                            <i class="fa-solid fa-file-pdf me-1"></i>
                            Lihat Dokumen Saat Ini
                        </a>
                    </div>
                @endif
            </div>

            <div class="col-12">
                <x-input-textarea
                    name="catatan"
                    label="Catatan"
                    placeholder="Tambahkan catatan jika diperlukan..."
                    value="{{ $data->catatan }}"
                    :rows="2"
                />
            </div>

        </div>
    </x-fieldset>

</x-action-edit>
