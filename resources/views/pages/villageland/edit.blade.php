<x-action-edit :id="$data->id" href="{{ route('village-lands.update', $data->id) }}" size="xl">
    <div class="js-land-calculator" data-auto-calculate="true">

        {{-- A. Identitas Pemilik Tanah --}}
        <x-fieldset label="A. Identitas Pemilik Tanah">
            <div class="row g-3">
                <div class="col-12 col-md-8">
                    <x-input-text name="owner_name" label="Nama Per-Orangan / Badan Hukum"
                        placeholder="Contoh: Ahmad Yani / Koperasi Desa Tiaro" maxlength="150"
                        value="{{ $data->owner_name }}" required />
                </div>

                <div class="col-12 col-md-4">
                    <x-input-text type="number" name="jml" label="Jumlah Luas Tercatat (M²)" min="0"
                        step="0.01" value="{{ $data->jml ?? 0 }}"
                        hint="Terisi otomatis dari hasil hitung Status Hak Tanah." readonly data-total-input />
                </div>
            </div>
        </x-fieldset>

        {{-- B. Status Hak Tanah (M²) --}}
        <x-fieldset label="B. Status Hak Tanah (M²)">
            <div class="row g-3">
                <div class="col-12">
                    <span class="small fw-semibold text-muted">Sudah Bersertifikat</span>
                </div>

                @foreach ([
        'hm' => 'HM',
        'hgb' => 'HGB',
        'hp' => 'HP',
        'hgu' => 'HGU',
        'hpl' => 'HPL',
    ] as $name => $label)
                    <div class="col-6 col-md-4 col-lg">
                        <x-input-text type="number" name="{{ $name }}" label="{{ $label }}"
                            min="0" step="0.01" value="{{ $data->$name ?? 0 }}" data-land-source required />
                    </div>
                @endforeach

                <div class="col-12 pt-2">
                    <span class="small fw-semibold text-muted">Belum Bersertifikat</span>
                </div>

                @foreach ([
        'ma' => 'MA',
        'vi' => 'VI',
        'tn' => 'TN',
    ] as $name => $label)
                    <div class="col-6 col-md-4">
                        <x-input-text type="number" name="{{ $name }}" label="{{ $label }}"
                            min="0" step="0.01" value="{{ $data->$name ?? 0 }}" data-land-source required />
                    </div>
                @endforeach

                <div class="col-12">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="land-counter">
                            <div>
                                <span class="land-counter-label">Total Luas Tanah (JML)</span>
                                <strong data-total-result>0,00 m²</strong>
                            </div>

                            <div>
                                <span class="land-counter-label">Status Perhitungan</span>
                                <strong data-source-status>Belum dihitung</strong>
                            </div>
                        </div>

                        <button type="button" data-btn-calculate class="btn btn-simdes-outline">
                            <i class="fa-solid fa-calculator me-1" aria-hidden="true"></i>
                            Hitung Luas
                        </button>
                    </div>
                </div>
            </div>
        </x-fieldset>

        {{-- C. Penggunaan Tanah (M²) --}}
        <x-fieldset label="C. Penggunaan Tanah (M²)">
            <fieldset class="land-type-fieldset" data-type-fieldset disabled>
                <div class="row g-3">
                    <div class="col-12">
                        <span class="small fw-semibold text-muted">Non Pertanian</span>
                    </div>

                    @foreach ([
        'perumahan' => 'Perumahan',
        'dagang_jasa' => 'Perdagangan & Jasa',
        'perkantoran' => 'Perkantoran',
        'industri' => 'Industri',
        'fasilitas_umum' => 'Fasilitas Umum',
    ] as $name => $label)
                        <div class="col-6 col-md-4 col-lg">
                            <x-input-text type="number" name="{{ $name }}" label="{{ $label }}"
                                min="0" step="0.01" value="{{ $data->$name ?? 0 }}" data-land-type
                                required />
                        </div>
                    @endforeach

                    <div class="col-12 pt-2">
                        <span class="small fw-semibold text-muted">Pertanian</span>
                    </div>

                    @foreach ([
        'sawah' => 'Sawah',
        'tegalan' => 'Tegalan',
        'perkebunan' => 'Perkebunan',
        'peternakan_perikanan' => 'Peternakan / Perikanan',
        'hutan_belukar' => 'Hutan Belukar',
        'hutan_lebat' => 'Hutan Lebat / Lindung',
    ] as $name => $label)
                        <div class="col-6 col-md-4 col-lg-2">
                            <x-input-text type="number" name="{{ $name }}" label="{{ $label }}"
                                min="0" step="0.01" value="{{ $data->$name ?? 0 }}" data-land-type
                                required />
                        </div>
                    @endforeach

                    <div class="col-12 pt-2">
                        <span class="small fw-semibold text-muted">Kategori Lainnya</span>
                    </div>

                    @foreach ([
        'tanah_kosong' => 'Tanah Kosong',
        'lainnya' => 'Lain-lain',
    ] as $name => $label)
                        <div class="col-6 col-md-4 col-lg-3">
                            <x-input-text type="number" name="{{ $name }}" label="{{ $label }}"
                                min="0" step="0.01" value="{{ $data->$name ?? 0 }}" data-land-type
                                required />
                        </div>
                    @endforeach

                    <div class="col-12">
                        <div class="land-counter">
                            <div>
                                <span class="land-counter-label">Total Luas Tersedia</span>
                                <strong data-available-result>0,00 m²</strong>
                            </div>

                            <div>
                                <span class="land-counter-label">Sudah Dibagikan</span>
                                <strong data-distributed-result>0,00 m²</strong>
                            </div>

                            <div>
                                <span class="land-counter-label">Sisa Luas</span>
                                <strong data-remaining-result>0,00 m²</strong>
                            </div>
                        </div>

                        <div data-type-status class="mt-2 small text-muted">
                            Memuat pembagian luas tanah...
                        </div>
                    </div>
                </div>
            </fieldset>
        </x-fieldset>

        {{-- D. Mutasi Tanah & Keterangan --}}
        <x-fieldset label="D. Mutasi Tanah & Keterangan">
            <div class="row g-3">
                <div class="col-12 col-md-4">
                    <x-input-text type="number" name="mutasi_tanah" label="Mutasi Tanah di Desa (M²)" min="0"
                        step="0.01" value="{{ $data->mutasi_tanah ?? 0 }}"
                        hint="Isi luas mutasi tanah dalam M² apabila ada perubahan." />
                </div>

                <div class="col-12">
                    <x-input-textarea name="catatan" label="Keterangan"
                        placeholder="Catatan tambahan mengenai kondisi, batas, atau status tanah..."
                        value="{{ $data->catatan }}" hint="Informasi tambahan yang dianggap penting."
                        :rows="3" />
                </div>
            </div>
        </x-fieldset>

    </div>
    @include('pages.villageland.comp.script-style')
</x-action-edit>
