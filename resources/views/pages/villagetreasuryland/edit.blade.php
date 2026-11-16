<x-action-edit :id="$data->id" href="{{ route('village-treasury-lands.update', $data->id) }}" size="xl">
    <div class="js-land-calculator" data-auto-calculate="true">

        {{-- A. Identitas Tanah --}}
        <x-fieldset label="A. Identitas Tanah">
            <div class="row g-3">

                <div class="col-12 col-md-6">
                    <x-input-select name="asal_tanah" label="Asal Tanah" :options="\App\Enums\RegionLandList::options()" placeholder="Pilih Asal Tanah"
                        value="{{ $data->asal_tanah?->value }}" required />
                </div>

                <div class="col-12 col-md-6">
                    <x-input-text name="nomor_sertifikat" label="Nomor Sertifikat" placeholder="Contoh: 001/2024/SHM"
                        value="{{ $data->nomor_sertifikat }}" required />
                </div>

                <div class="col-12 col-md-6">
                    <x-input-select name="kelas_tanah" label="Kelas Tanah" :options="\App\Enums\LandClassList::options()"
                        placeholder="Pilih Kelas Tanah" value="{{ $data->kelas_tanah?->value }}" required />
                </div>

                <div class="col-12 col-md-6">
                    <x-input-text type="date" name="tgl_perolehan" label="Tanggal Perolehan"
                        value="{{ optional($data->tgl_perolehan)->format('Y-m-d') }}" required />
                </div>

            </div>
        </x-fieldset>

        {{-- B. Luas Berdasarkan Asal Perolehan --}}
        <x-fieldset label="B. Luas Berdasarkan Asal Perolehan (M²)">
            <div class="row g-3 justify-content-between align-items-center">

                @foreach ([
        'lt_asli_desa' => 'Asli Milik Desa',
        'lt_pemerintah' => 'Pemerintah Pusat',
        'lt_provinsi' => 'Provinsi',
        'lt_kabupaten' => 'Kabupaten/Kota',
        'lt_lainnya' => 'Lainnya',
    ] as $name => $label)
                    <div class="col-12 col-md-6 col-lg">
                        <x-input-text type="number" name="{{ $name }}" label="{{ $label }}"
                            min="0" step="0.01" value="{{ $data->$name ?? 0 }}" data-land-source required />
                    </div>
                @endforeach

                <div class="col-12">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div class="land-counter">
                            <div>
                                <span class="land-counter-label">Total Luas Tanah</span>
                                <strong data-total-result>0,00 m²</strong>
                            </div>

                            <div>
                                <span class="land-counter-label">Status Perhitungan</span>
                                <strong data-source-status>Belum dihitung</strong>
                            </div>
                        </div>

                        <button type="button" data-btn-calculate class="btn btn-simdes-outline">
                            <i class="fa-solid fa-calculator me-1"></i>
                            Hitung Luas
                        </button>
                    </div>
                </div>

            </div>
        </x-fieldset>

        {{-- C. Luas Berdasarkan Jenis Tanah --}}
        <x-fieldset label="C. Luas Berdasarkan Jenis Tanah (M²)">
            <fieldset class="land-type-fieldset" data-type-fieldset disabled>
                <div class="row g-3 justify-content-between align-items-center">

                    @foreach ([
        'lt_tipe_sawah' => 'Sawah',
        'lt_tipe_tegal' => 'Tegal / Ladang',
        'lt_tipe_kebun' => 'Kebun',
        'lt_tipe_tambak' => 'Tambak',
        'lt_tipe_kering' => 'Tanah Kering',
    ] as $name => $label)
                        <div class="col-12 col-md-6 col-lg">
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

        {{-- D. Kelengkapan Tanah --}}
        <x-fieldset label="D. Kelengkapan Tanah">
            <div class="row g-3">

                <div class="col-12 col-md-6">
                    <x-input-radio name="patok_tanah" label="Patok Tanda Batas" number="1" :required="true"
                        :value="(int) $data->patok_tanah" :options="[
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
                    <x-input-radio name="papan_nama" label="Papan Nama" number="2" :required="true"
                        :value="(int) $data->papan_nama" :options="[
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

        {{-- E. Lokasi dan Peruntukan --}}
        <x-fieldset label="E. Lokasi dan Peruntukan Tanah">
            <div class="row g-3">

                <div class="col-12">
                    <x-input-text name="lokasi" label="Lokasi Tanah" placeholder="Contoh: Dusun I, dekat kantor desa"
                        value="{{ $data->lokasi }}"
                        hint="Tuliskan dusun, wilayah, atau keterangan lokasi tanah secara jelas." required />
                </div>

                <div class="col-12">
                    <x-input-text name="peruntukan" label="Peruntukan Tanah"
                        placeholder="Contoh: Tanah Pertanian, Perkebunan Desa, Fasilitas Umum"
                        value="{{ $data->peruntukan }}" hint="Tuliskan penggunaan atau tujuan pemanfaatan tanah." />
                </div>

            </div>
        </x-fieldset>

        {{-- F. Mutasi, Dokumen dan Catatan --}}
        <x-fieldset label="F. Mutasi, Dokumen dan Catatan">
            <div class="row g-3">

                <div class="col-12">
                    <x-input-textarea name="mutasi" label="Mutasi Tanah"
                        placeholder="Catat perubahan luas, status, batas, atau peruntukan tanah..."
                        value="{{ $data->mutasi }}" hint="Isi apabila terdapat perubahan atau mutasi tanah."
                        :rows="3" />
                </div>

                <div class="col-12">
                    <x-input-text type="file" name="dokumen" label="Dokumen Tanah" accept=".pdf,application/pdf"
                        hint="{{ $data->dokumen ? 'Dokumen saat ini: ' . basename($data->dokumen) . '. Unggah PDF baru jika ingin menggantinya.' : 'Unggah sertifikat atau dokumen pendukung tanah dalam format PDF.' }}" />

                    @if ($data->dokumen)
                        <div class="mt-2">
                            <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($data->dokumen) }}"
                                target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-simdes-outline">
                                <i class="fa-solid fa-file-pdf me-1"></i>
                                Lihat Dokumen Saat Ini
                            </a>
                        </div>
                    @endif
                </div>

                <div class="col-12">
                    <x-input-textarea name="catatan" label="Keterangan"
                        placeholder="Catatan tambahan mengenai kondisi atau status tanah..."
                        value="{{ $data->catatan }}" hint="Informasi tambahan yang dianggap penting."
                        :rows="3" />
                </div>

            </div>
        </x-fieldset>

    </div>
    @include('pages.villagetreasuryland.comp.script-style')
</x-action-edit>
