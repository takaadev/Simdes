@once
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

            .land-type-fieldset {
                min-width: 0;
                margin: 0;
                padding: 0;
                border: 0;
            }

            .land-type-fieldset:disabled {
                opacity: 0.55;
            }

            .land-type-fieldset:disabled .land-counter {
                opacity: 0.7;
            }
        </style>
    @endpush

    @push('footer')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const formatNumber = cents => new Intl.NumberFormat('id-ID', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                }).format(cents / 100);

                // Resolve elemen <input> asli baik jika data-attribute menempel di <input> maupun di wrapper <div>
                const resolveInputs = (container, selector) => {
                    const elements = [...container.querySelectorAll(selector)];
                    return elements.map(el => el.tagName === 'INPUT' ? el : el.querySelector('input')).filter(
                        Boolean);
                };

                const getCents = input => {
                    const rawValue = String(input.value ?? '').replace(',', '.');
                    const value = parseFloat(rawValue);

                    if (!Number.isFinite(value) || value <= 0) {
                        return 0;
                    }

                    return Math.round(value * 100);
                };

                const initCalculator = container => {
                    if (container.dataset.initialized === 'true') return;
                    container.dataset.initialized = 'true';

                    const sourceInputs = resolveInputs(container, '[data-land-source]');
                    const typeInputs = resolveInputs(container, '[data-land-type]');

                    const calculateButton = container.querySelector('[data-btn-calculate]');
                    const typeFieldset = container.querySelector('[data-type-fieldset]');

                    const totalResult = container.querySelector('[data-total-result]');
                    const sourceStatus = container.querySelector('[data-source-status]');

                    const availableResult = container.querySelector('[data-available-result]');
                    const distributedResult = container.querySelector('[data-distributed-result]');
                    const remainingResult = container.querySelector('[data-remaining-result]');
                    const typeStatus = container.querySelector('[data-type-status]');

                    let totalLandCents = 0;
                    let isCalculated = false;

                    const calculateSourceLand = () =>
                        sourceInputs.reduce((total, input) => total + getCents(input), 0);

                    const calculateTypeLand = () =>
                        typeInputs.reduce((total, input) => total + getCents(input), 0);

                    const updateTypeLand = () => {
                        const distributed = calculateTypeLand();
                        const remaining = totalLandCents - distributed;

                        availableResult.textContent = `${formatNumber(totalLandCents)} m²`;
                        distributedResult.textContent = `${formatNumber(distributed)} m²`;
                        remainingResult.textContent = `${formatNumber(remaining)} m²`;

                        if (distributed > totalLandCents) {
                            typeStatus.textContent =
                                `Pembagian melebihi total luas sebesar ${formatNumber(Math.abs(remaining))} m².`;
                            typeStatus.className = 'mt-2 small text-danger fw-semibold';
                        } else if (distributed === totalLandCents && totalLandCents > 0) {
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

                    const calculate = () => {
                        totalLandCents = calculateSourceLand();

                        if (totalLandCents <= 0) {
                            totalResult.textContent = '0,00 m²';
                            sourceStatus.textContent = 'Sumber belum diisi';
                            sourceStatus.className = 'text-danger';

                            typeFieldset.disabled = true;
                            isCalculated = false;

                            availableResult.textContent = '0,00 m²';
                            distributedResult.textContent = '0,00 m²';
                            remainingResult.textContent = '0,00 m²';
                            typeStatus.textContent = 'Silakan hitung total luas tanah terlebih dahulu.';
                            typeStatus.className = 'mt-2 small text-muted';
                            return;
                        }

                        totalResult.textContent = `${formatNumber(totalLandCents)} m²`;
                        sourceStatus.textContent = 'Berhasil dihitung';
                        sourceStatus.className = 'text-success';

                        typeFieldset.disabled = false;
                        isCalculated = true;

                        updateTypeLand();
                    };

                    if (calculateButton) {
                        calculateButton.addEventListener('click', calculate);
                    }

                    typeInputs.forEach(input => {
                        input.addEventListener('input', () => {
                            if (isCalculated) {
                                updateTypeLand();
                            }
                        });
                    });

                    sourceInputs.forEach(input => {
                        input.addEventListener('input', () => {
                            if (!isCalculated) return;

                            isCalculated = false;
                            typeFieldset.disabled = true;
                            totalLandCents = 0;

                            totalResult.textContent = 'Belum dihitung';
                            sourceStatus.textContent = 'Perlu dihitung ulang';
                            sourceStatus.className = 'text-warning';

                            typeStatus.textContent =
                                'Sumber perolehan berubah. Silakan hitung ulang luas tanah.';
                            typeStatus.className = 'mt-2 small text-warning fw-semibold';
                        });
                    });

                    // Hitung otomatis saat form edit dibuka atau di-load
                    if (container.dataset.autoCalculate === 'true') {
                        calculate();
                    }
                };

                // Inisialisasi semua form yang ada saat halaman dimuat
                document.querySelectorAll('.js-land-calculator').forEach(initCalculator);

                // Jika form berada di dalam Bootstrap Modal (termasuk yang di-render via DataTables),
                // pastikan terhitung otomatis saat modal ditampilkan
                document.addEventListener('shown.bs.modal', event => {
                    const calculators = event.target.querySelectorAll('.js-land-calculator');
                    calculators.forEach(container => {
                        initCalculator(container);
                        if (container.dataset.autoCalculate === 'true') {
                            const btn = container.querySelector('[data-btn-calculate]');
                            if (btn) btn.click();
                        }
                    });
                });
            });
        </script>
    @endpush
@endonce
