@php
    $totalLuasAsal =
        (float) ($data->lt_asli_desa ?? 0) +
        (float) ($data->lt_pemerintah ?? 0) +
        (float) ($data->lt_provinsi ?? 0) +
        (float) ($data->lt_kabupaten ?? 0) +
        (float) ($data->lt_lainnya ?? 0);

    $totalLuasJenis =
        (float) ($data->lt_tipe_sawah ?? 0) +
        (float) ($data->lt_tipe_tegal ?? 0) +
        (float) ($data->lt_tipe_kebun ?? 0) +
        (float) ($data->lt_tipe_tambak ?? 0) +
        (float) ($data->lt_tipe_kering ?? 0);

    $sisaLuas = $totalLuasAsal - $totalLuasJenis;

    $asalTanahLabel = is_object($data->asal_tanah)
        ? (method_exists($data->asal_tanah, 'label')
            ? $data->asal_tanah->label()
            : $data->asal_tanah->name ?? $data->asal_tanah->value)
        : \App\Enums\RegionLandList::options()[$data->asal_tanah] ?? $data->asal_tanah;

    $kelasTanahLabel = is_object($data->kelas_tanah)
        ? (method_exists($data->kelas_tanah, 'label')
            ? $data->kelas_tanah->label()
            : $data->kelas_tanah->name ?? $data->kelas_tanah->value)
        : \App\Enums\LandClassList::options()[$data->kelas_tanah] ?? $data->kelas_tanah;
@endphp

<x-action-show :id="$data->id" modalTitle="Detail Tanah Kas Desa" size="xl">

    {{-- A. Identitas Tanah --}}
    <x-fieldset label="A. Identitas Tanah">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-text name="asal_tanah" label="Asal Tanah" value="{{ $asalTanahLabel }}" disabled />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text name="nomor_sertifikat" label="Nomor Sertifikat" value="{{ $data->nomor_sertifikat }}"
                    disabled />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text name="kelas_tanah" label="Kelas Tanah" value="{{ $kelasTanahLabel }}" disabled />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_perolehan" label="Tanggal Perolehan"
                    value="{{ function_exists('format_date_input') ? format_date_input($data->tgl_perolehan) : optional($data->tgl_perolehan)->format('Y-m-d') }}"
                    disabled />
            </div>
        </div>
    </x-fieldset>

    {{-- B. Luas Berdasarkan Asal Perolehan (M²) --}}
    <x-fieldset label="B. Luas Berdasarkan Asal Perolehan (M²)">
        <div class="row g-3">
            @foreach ([
        'lt_asli_desa' => 'Asli Milik Desa',
        'lt_pemerintah' => 'Pemerintah Pusat',
        'lt_provinsi' => 'Provinsi',
        'lt_kabupaten' => 'Kabupaten/Kota',
        'lt_lainnya' => 'Lainnya',
    ] as $name => $label)
                <div class="col-12 col-md-6 col-lg">
                    <x-input-text type="number" name="{{ $name }}" label="{{ $label }}"
                        value="{{ $data->$name ?? 0 }}" disabled />
                </div>
            @endforeach

            <div class="col-12">
                <div class="land-counter">
                    <div>
                        <span class="land-counter-label">Total Luas Tanah</span>
                        <strong>{{ number_format($totalLuasAsal, 2, ',', '.') }} m²</strong>
                    </div>

                    <div>
                        <span class="land-counter-label">Status</span>
                        <strong class="text-success">Terdata</strong>
                    </div>
                </div>
            </div>
        </div>
    </x-fieldset>

    {{-- C. Luas Berdasarkan Jenis Tanah (M²) --}}
    <x-fieldset label="C. Luas Berdasarkan Jenis Tanah (M²)">
        <div class="row g-3">
            @foreach ([
        'lt_tipe_sawah' => 'Sawah',
        'lt_tipe_tegal' => 'Tegal / Ladang',
        'lt_tipe_kebun' => 'Kebun',
        'lt_tipe_tambak' => 'Tambak',
        'lt_tipe_kering' => 'Tanah Kering',
    ] as $name => $label)
                <div class="col-12 col-md-6 col-lg">
                    <x-input-text type="number" name="{{ $name }}" label="{{ $label }}"
                        value="{{ $data->$name ?? 0 }}" disabled />
                </div>
            @endforeach

            <div class="col-12">
                <div class="land-counter">
                    <div>
                        <span class="land-counter-label">Total Luas Tersedia</span>
                        <strong>{{ number_format($totalLuasAsal, 2, ',', '.') }} m²</strong>
                    </div>

                    <div>
                        <span class="land-counter-label">Sudah Dibagikan</span>
                        <strong>{{ number_format($totalLuasJenis, 2, ',', '.') }} m²</strong>
                    </div>

                    <div>
                        <span class="land-counter-label">Sisa Luas</span>
                        <strong class="{{ abs($sisaLuas) > 0.001 ? 'text-warning' : 'text-success' }}">
                            {{ number_format($sisaLuas, 2, ',', '.') }} m²
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </x-fieldset>

    {{-- D. Kelengkapan Tanah --}}
    <x-fieldset label="D. Kelengkapan Tanah">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-radio name="patok_tanah_show_{{ $data->id }}" label="Patok Tanda Batas" number="1"
                    :value="(int) $data->patok_tanah" :disabled="true" :options="[
                        [
                            'value' => 1,
                            'label' => 'Ada',
                            'icon' => 'fa-solid fa-circle-check',
                        ],
                        [
                            'value' => 0,
                            'label' => 'Tidak Ada',
                            'icon' => 'fa-solid fa-circle-xmark',
                        ],
                    ]" />
            </div>

            <div class="col-12 col-md-6">
                <x-input-radio name="papan_nama_show_{{ $data->id }}" label="Papan Nama" number="2"
                    :value="(int) $data->papan_nama" :disabled="true" :options="[
                        [
                            'value' => 1,
                            'label' => 'Ada',
                            'icon' => 'fa-solid fa-circle-check',
                        ],
                        [
                            'value' => 0,
                            'label' => 'Tidak Ada',
                            'icon' => 'fa-solid fa-circle-xmark',
                        ],
                    ]" />
            </div>
        </div>
    </x-fieldset>

    {{-- E. Lokasi dan Peruntukan Tanah --}}
    <x-fieldset label="E. Lokasi dan Peruntukan Tanah">
        <div class="row g-3">
            <div class="col-12">
                <x-input-text name="lokasi" label="Lokasi Tanah" value="{{ $data->lokasi }}" disabled />
            </div>

            <div class="col-12">
                <x-input-text name="peruntukan" label="Peruntukan Tanah" value="{{ $data->peruntukan ?? '-' }}"
                    disabled />
            </div>
        </div>
    </x-fieldset>

    {{-- F. Mutasi, Dokumen dan Catatan --}}
    <x-fieldset label="F. Mutasi, Dokumen dan Catatan">
        <div class="row g-3">
            <div class="col-12">
                <x-input-textarea name="mutasi" label="Mutasi Tanah" :rows="3"
                    value="{{ $data->mutasi ?? '-' }}" disabled />
            </div>

            <div class="col-12">
                <label class="form-label">Dokumen Tanah</label>

                @if ($data->dokumen)
                    <div class="d-flex align-items-center justify-content-between gap-3 border rounded-3 p-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-file-pdf text-danger fs-4"></i>
                            <div>
                                <div class="fw-semibold">{{ basename($data->dokumen) }}</div>
                                <small class="text-muted">File PDF sertifikat / dokumen pendukung tanah desa</small>
                            </div>
                        </div>

                        <a href="{{ asset('storage/' . $data->dokumen) }}" target="_blank" rel="noopener noreferrer"
                            class="btn btn-sm btn-outline-danger">
                            <i class="fa-solid fa-eye me-1"></i>
                            Lihat PDF
                        </a>
                    </div>
                @else
                    <div class="alert alert-light border mb-0">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Dokumen tanah belum tersedia.
                    </div>
                @endif
            </div>

            <div class="col-12">
                <x-input-textarea name="catatan" label="Keterangan" :rows="3"
                    value="{{ $data->catatan ?? '-' }}" disabled />
            </div>
        </div>
    </x-fieldset>

</x-action-show>
