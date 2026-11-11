<x-action-edit :id="$data->id" href="{{ route('agendas.update', $data->id) }}" size="lg"
    modalTitle="Edit Surat Masuk" label="Edit Surat Masuk">
    <x-input-text value="incoming" name="tipe_agenda" type="hidden" />
    {{-- A. Informasi Penerimaan Surat --}}
    <x-fieldset label="A. Informasi Penerimaan Surat">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tanggal_terima"
                    label="Tanggal Terima"
                    value="{{ $data->tanggal_terima?->format('Y-m-d') }}"
                    required
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    name="nomor_surat"
                    label="Nomor Surat"
                    placeholder="Contoh: 400.12.3/680/DS/VII/2026"
                    value="{{ $data->nomor_surat }}"
                    required
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_surat"
                    label="Tanggal Surat"
                    value="{{ $data->tgl_surat?->format('Y-m-d') }}"
                    required
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    name="pengirim"
                    label="Asal / Pengirim Surat"
                    placeholder="Nama instansi atau pihak pengirim"
                    value="{{ $data->pengirim }}"
                    required
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-textarea
                    name="isi_surat"
                    label="Perihal Surat"
                    placeholder="Tuliskan perihal atau ringkasan isi surat..."
                    hint="Masukkan pokok permasalahan atau tujuan surat."
                    value="{{ $data->isi_surat }}"
                    :rows="3"
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-textarea
                    name="catatan"
                    label="Catatan"
                    placeholder="Catatan tambahan terkait surat masuk..."
                    hint="Informasi tambahan yang dianggap penting."
                    value="{{ $data->catatan }}"
                    :rows="3"
                />
            </div>

            <div class="col-12">
                <x-input-text
                    type="file"
                    name="dokumen"
                    label="Dokumen Surat Masuk"
                    accept=".pdf,application/pdf"
                    hint="Unggah dokumen PDF baru jika ingin mengganti dokumen sebelumnya."
                />

                @if ($data->dokumen)
                    <div class="mt-2">
                        <a href="{{ Storage::disk('public')->url($data->dokumen) }}"
                            target="_blank" rel="noopener noreferrer"
                            class="small text-decoration-none">
                            <i class="fa-solid fa-file-pdf me-1 text-danger"></i>
                            Lihat dokumen surat saat ini
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </x-fieldset>
</x-action-edit>
