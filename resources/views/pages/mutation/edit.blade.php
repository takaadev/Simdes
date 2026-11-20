
<x-action-edit :id="$data->id" href="{{ route('mutations.update', $data->id) }}">
    <x-fieldset label="Informasi Mutasi">
        <div class="row g-3">

            <div class="col-12">
                <x-input-select2
                    name="resident_id"
                    label="Nama Penduduk"
                    value="{{ $data->resident_id }}"
                    :options="\App\Support\ResidentOptions::options()"
                    disabled
                />
            </div>

            @if ($data->tipe_mutasi?->value === 'add')
                <div class="col-12">
                    <div class="alert alert-info d-flex align-items-start gap-2 mb-0" role="alert">
                        <i class="fas fa-circle-info mt-1"></i>
                        <div>
                            <strong>Informasi Perubahan Data Penduduk</strong>
                            <div class="small mt-1">
                                Mutasi ini merupakan mutasi masuk atau penambahan penduduk.
                                Apabila terdapat perubahan identitas atau biodata penduduk,
                                silakan lakukan perubahan melalui halaman
                                <strong><a href="{{ route('residents.index') }}" class="btn btn-link">Buku Induk Penduduk</a></strong>.
                                Form ini hanya digunakan untuk memperbarui riwayat mutasi.
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-12 col-md-6">
                <x-input-select
                    name="kategori_mutasi"
                    label="Kategori Mutasi"
                    :options="\App\Enums\MutationCategoryList::options(
                        $data->tipe_mutasi?->value === 'add'
                            ? 'BIRTH,INCOMING'
                            : 'DEATH,OUTGOING'
                    )"
                    placeholder="Pilih kategori mutasi"
                    value="{{ $data->kategori_mutasi?->value }}"
                    required
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    type="date"
                    name="tgl_mutasi"
                    label="Tanggal Mutasi"
                    value="{{ $data->tgl_mutasi?->format('Y-m-d') }}"
                    required
                />
            </div>

            <div class="col-12">
                <x-input-text
                    name="tujuan_mutasi"
                    label="Asal / Tujuan Mutasi"
                    placeholder="Contoh: Desa asal atau wilayah tujuan lainnya"
                    maxlength="255"
                    value="{{ $data->tujuan_mutasi }}"
                />
                <small class="text-muted">
                    Wajib diisi untuk kategori Penduduk Masuk dan Penduduk Keluar.
                </small>
            </div>

            <div class="col-12">
                <x-input-textarea
                    name="catatan"
                    label="Catatan"
                    placeholder="Masukkan keterangan tambahan mengenai mutasi penduduk..."
                    hint="Informasi tambahan mengenai peristiwa mutasi penduduk."
                    rows="3"
                    value="{{ $data->catatan }}"
                />
            </div>

        </div>
    </x-fieldset>
</x-action-edit>
