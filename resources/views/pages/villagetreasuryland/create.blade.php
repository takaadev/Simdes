<x-action-create href="{{ route('village-treasury-lands.store') }}" size="fullscreen">

    {{-- A. Identitas Tanah --}}
    <x-fieldset label="A. Identitas Tanah">
        <div class="row g-3">

            <div class="col-12 col-md-6">
                <x-input-select name="asal_tanah" label="Asal Tanah" :options="\App\Enums\RegionLandList::options()" placeholder="Pilih Asal Tanah"
                    required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text name="nomor_sertifikat" label="Nomor Sertifikat" placeholder="Contoh: 001/2024/SHM"
                    required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-select name="kelas_tanah" label="Kelas Tanah" :options="\App\Enums\LandClassList::options()" placeholder="Pilih Kelas Tanah"
                    required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_perolehan"
                    label="Tanggal tanah diperoleh atau menjadi aset desa." required />
            </div>

        </div>
    </x-fieldset>

    {{-- B. Asal Perolehan Tanah --}}
    <x-fieldset label="B. Luas Berdasarkan Asal Perolehan (M²)">
        <div class="row g-3 justify-content-between align-items-center">
            @foreach ([
        'lt_asli_desa' => 'Asli Milik Desa',
        'lt_pemerintah' => 'Pemerintah Pusat',
        'lt_provinsi' => 'Provinsi',
        'lt_kabupaten' => 'Kabupaten/Kota',
        'lt_lainnya' => 'Lainnya',
    ] as $name => $label)
                <div class="col-12 col-md-2">
                    <x-input-text type="number" :name="$name" :label="$label" min="0" step="0.01"
                        value="0" data-land-source />
                </div>
            @endforeach

            <div class="col-12 col-md-2">
                <button type="button" id="btnCalculateLand" class="btn btn-simdes-outline mt-3 w-100">
                    <i class="fa-solid fa-calculator me-1"></i>
                    Hitung Luas
                </button>
            </div>

            <div class="col-12">
                <div class="land-counter">
                    <div>
                        <span class="land-counter-label">Hasil Total</span>
                        <strong id="totalLandResult">0,00 m²</strong>
                    </div>

                    <div>
                        <span class="land-counter-label">Status</span>
                        <strong id="sourceLandStatus">Belum dihitung</strong>
                    </div>
                </div>
            </div>

        </div>
    </x-fieldset>

    {{-- C. Luas Berdasarkan Jenis Tanah --}}
    <x-fieldset label="C. Luas Berdasarkan Jenis Tanah (M²)">
        <fieldset id="landTypeFieldset" disabled>
            <div class="row g-3 justify-content-between align-items-center">

                @foreach ([
        'lt_tipe_sawah' => 'Sawah',
        'lt_tipe_tegal' => 'Tegal / Ladang',
        'lt_tipe_kebun' => 'Kebun',
        'lt_tipe_tambak' => 'Tambak',
        'lt_tipe_kering' => 'Tanah Kering',
    ] as $name => $label)
                    <div class="col-12 col-md-2">
                        <x-input-text type="number" :name="$name" :label="$label" min="0" step="0.01"
                            value="0" data-land-type />
                    </div>
                @endforeach

                <div class="col-12">
                    <div class="land-counter">
                        <div>
                            <span class="land-counter-label">Total Luas Tanah</span>
                            <strong id="availableLandResult">0,00 m²</strong>
                        </div>

                        <div>
                            <span class="land-counter-label">Sudah Dibagikan</span>
                            <strong id="distributedLandResult">0,00 m²</strong>
                        </div>

                        <div>
                            <span class="land-counter-label">Sisa Luas</span>
                            <strong id="remainingLandResult">0,00 m²</strong>
                        </div>
                    </div>

                    <div id="landTypeStatus" class="mt-2 small text-muted">
                        Silakan hitung total luas tanah terlebih dahulu.
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
                    :options="[
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
                    :options="[
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
                    hint="Tuliskan dusun, wilayah, atau keterangan lokasi tanah secara jelas." required />
            </div>

            <div class="col-12">
                <x-input-text name="peruntukan" label="Peruntukan Tanah"
                    placeholder="Contoh: Tanah Pertanian, Perkebunan Desa, Fasilitas Umum"
                    hint="Tuliskan penggunaan atau tujuan pemanfaatan tanah." />
            </div>

        </div>
    </x-fieldset>

    {{-- F. Mutasi, Dokumen dan Catatan --}}
    <x-fieldset label="F. Mutasi, Dokumen dan Catatan">
        <div class="row g-3">

            <div class="col-12">
                <x-input-textarea name="mutasi" label="Mutasi Tanah"
                    placeholder="Catat perubahan luas, status, batas, atau peruntukan tanah..."
                    hint="Isi apabila terdapat perubahan atau mutasi tanah." :rows="3" />
            </div>

            <div class="col-12">
                <x-input-text type="file" name="dokumen" label="Dokumen Tanah" accept=".pdf,application/pdf"
                    hint="Unggah sertifikat atau dokumen pendukung tanah dalam format PDF." />
            </div>

            <div class="col-12">
                <x-input-textarea name="catatan" label="Keterangan"
                    placeholder="Catatan tambahan mengenai kondisi atau status tanah..."
                    hint="Informasi tambahan yang dianggap penting." :rows="3" />
            </div>

        </div>
    </x-fieldset>
    @push('header')
        <style>
            .land-counter {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
                gap: 1rem;
                padding: 1rem 1.25rem;
                background: var(--simdes-bg);
                border: 1px solid var(--simdes-border);
                border-radius: var(--radius-sm);
            }

            .land-counter>div {
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
            }

            .land-counter-label {
                font-size: 0.75rem;
                color: var(--simdes-text-muted);
            }

            .land-counter strong {
                font-size: 1rem;
                font-weight: 700;
                color: var(--simdes-primary);
            }

            #landTypeFieldset {
                min-width: 0;
                margin: 0;
                padding: 0;
                border: 0;
            }

            #landTypeFieldset:disabled {
                opacity: 0.55;
            }

            #landTypeFieldset:disabled .land-counter {
                opacity: 0.7;
            }
        </style>
    @endpush
    @push('footer')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const sourceInputs = document.querySelectorAll('[data-land-source]');
                const typeInputs = document.querySelectorAll('[data-land-type]');

                const calculateButton = document.getElementById('btnCalculateLand');
                const typeFieldset = document.getElementById('landTypeFieldset');

                const totalResult = document.getElementById('totalLandResult');
                const sourceStatus = document.getElementById('sourceLandStatus');

                const availableResult = document.getElementById('availableLandResult');
                const distributedResult = document.getElementById('distributedLandResult');
                const remainingResult = document.getElementById('remainingLandResult');
                const typeStatus = document.getElementById('landTypeStatus');

                let totalLand = 0;
                let isCalculated = false;

                const formatNumber = value => {
                    return new Intl.NumberFormat('id-ID', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                    }).format(value);
                };

                const getValue = input => {
                    const value = Number(input.value);
                    return Number.isFinite(value) && value > 0 ? value : 0;
                };

                const calculateSourceLand = () => {
                    return [...sourceInputs].reduce(
                        (total, input) => total + getValue(input),
                        0
                    );
                };

                const calculateTypeLand = () => {
                    return [...typeInputs].reduce(
                        (total, input) => total + getValue(input),
                        0
                    );
                };

                const updateTypeLand = () => {
                    const distributed = calculateTypeLand();
                    const remaining = totalLand - distributed;

                    availableResult.textContent = `${formatNumber(totalLand)} m²`;
                    distributedResult.textContent = `${formatNumber(distributed)} m²`;
                    remainingResult.textContent = `${formatNumber(remaining)} m²`;

                    if (distributed > totalLand) {
                        typeStatus.textContent =
                            `Pembagian melebihi total luas sebesar ${formatNumber(Math.abs(remaining))} m².`;

                        typeStatus.className = 'mt-2 small text-danger fw-semibold';
                    } else if (distributed === totalLand) {
                        typeStatus.textContent = 'Pembagian luas tanah sudah sesuai.';
                        typeStatus.className = 'mt-2 small text-success fw-semibold';
                    } else if (distributed > 0) {
                        typeStatus.textContent =
                            `Masih ada ${formatNumber(remaining)} m² yang belum dibagikan.`;

                        typeStatus.className = 'mt-2 small text-warning fw-semibold';
                    } else {
                        typeStatus.textContent = 'Silakan isi pembagian jenis tanah.';
                        typeStatus.className = 'mt-2 small text-muted';
                    }
                };

                calculateButton.addEventListener('click', () => {
                    const hasSource = [...sourceInputs].some(input => getValue(input) > 0);

                    if (!hasSource) {
                        totalResult.textContent = '0,00 m²';
                        sourceStatus.textContent = 'Sumber belum diisi';
                        sourceStatus.className = 'text-danger';

                        typeFieldset.disabled = true;
                        isCalculated = false;
                        totalLand = 0;

                        return;
                    }

                    totalLand = calculateSourceLand();

                    totalResult.textContent = `${formatNumber(totalLand)} m²`;
                    sourceStatus.textContent = 'Berhasil dihitung';
                    sourceStatus.className = 'text-success';

                    // Aktifkan pembagian jenis tanah.
                    typeFieldset.disabled = false;
                    isCalculated = true;

                    updateTypeLand();
                });

                typeInputs.forEach(input => {
                    input.addEventListener('input', () => {
                        if (isCalculated) {
                            updateTypeLand();
                        }
                    });
                });

                // Jika sumber perolehan berubah setelah dihitung,
                // wajib hitung ulang sebelum melanjutkan.
                sourceInputs.forEach(input => {
                    input.addEventListener('input', () => {
                        if (isCalculated) {
                            isCalculated = false;
                            typeFieldset.disabled = true;

                            totalLand = 0;

                            totalResult.textContent = 'Belum dihitung';
                            sourceStatus.textContent = 'Perlu dihitung ulang';
                            sourceStatus.className = 'text-warning';

                            typeStatus.textContent =
                                'Sumber perolehan berubah. Silakan hitung ulang luas tanah.';

                            typeStatus.className = 'mt-2 small text-warning fw-semibold';
                        }
                    });
                });
            });
        </script>
    @endpush
</x-action-create>
