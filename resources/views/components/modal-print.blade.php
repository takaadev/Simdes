<div class="modal fade simdes-modal" id="simdesPrintModal" tabindex="-1" aria-labelledby="simdesPrintModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-fullscreen-lg-down">
        <div class="modal-content" style="height: 90vh;">
            <div class="modal-header py-2">
                <div class="d-flex align-items-center gap-2 min-w-0">
                    <i class="fa-solid fa-print text-simdes-primary" aria-hidden="true"></i>
                    <h3 class="modal-title text-truncate" id="simdesPrintModalLabel">Pratinjau Cetak</h3>
                </div>

                <div class="d-flex align-items-center gap-2 ms-auto me-2">
                    <select id="simdesPrintPaperSelect" class="form-select form-select-sm" style="width: auto;"
                        aria-label="Pilih ukuran kertas">
                        <option value="A4 landscape" selected>A4 Landscape</option>
                        <option value="330mm 215mm">F4 / Folio Landscape</option>
                        <option value="A4 portrait">A4 Portrait</option>
                    </select>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <div class="modal-body p-0 position-relative bg-light" style="overflow: hidden;">
                {{-- Loading overlay --}}
                <div id="simdesPrintLoading"
                    class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center bg-white bg-opacity-75"
                    style="z-index: 5;">
                    <i class="fa-solid fa-spinner fa-spin fa-2x text-simdes-primary mb-2" aria-hidden="true"></i>
                    <span class="small text-muted fw-semibold">Menyiapkan dokumen cetak...</span>
                </div>

                {{-- Iframe Pratinjau --}}
                <iframe id="simdesPrintFrame" title="Pratinjau Dokumen Cetak"
                    style="width: 100%; height: 100%; border: 0; background: #f3f4f6;"></iframe>
            </div>

            <div class="modal-footer py-2">
                <button type="button" class="btn-simdes-outline" data-bs-dismiss="modal">Tutup</button>
                <button type="button" id="simdesPrintTriggerBtn" class="btn-simdes-primary" disabled>
                    <i class="fa-solid fa-print" aria-hidden="true"></i>
                    <span>Cetak / Simpan PDF</span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('footer')
    <script>
        $(function() {
            const $modalEl = $('#simdesPrintModal');
            const $title = $('#simdesPrintModalLabel');
            const $paperSelect = $('#simdesPrintPaperSelect');
            const $loading = $('#simdesPrintLoading');
            const $frame = $('#simdesPrintFrame');
            const $printBtn = $('#simdesPrintTriggerBtn');

            let currentPrintConfig = null;

            function buildPrintUrl(config) {
                const url = new URL(config.url, window.location.origin);
                url.searchParams.set('modal', '1');
                url.searchParams.set('paper', $paperSelect.val() || 'A4 landscape');

                if (config.filterMap) {
                    Object.entries(config.filterMap).forEach(([param, selector]) => {
                        const val = $(selector).val();
                        if (val !== undefined && val !== null && val !== '' && val !== 'ALL' && val !==
                            'all') {
                            url.searchParams.set(param, val);
                        }
                    });
                }

                if (config.tableSelector && $.fn.DataTable.isDataTable(config.tableSelector)) {
                    const searchVal = String($(config.tableSelector).DataTable().search() ?? '').trim();
                    if (searchVal !== '') {
                        url.searchParams.set('search', searchVal);
                    }
                }

                return url.toString();
            }

            function loadFrame() {
                if (!currentPrintConfig) return;

                $loading.removeClass('d-none').addClass('d-flex');
                $printBtn.prop('disabled', true);
                $frame.attr('src', buildPrintUrl(currentPrintConfig));
            }

            $frame.on('load', function() {
                if (!$frame.attr('src') || $frame.attr('src') === 'about:blank') return;
                $loading.removeClass('d-flex').addClass('d-none');
                $printBtn.prop('disabled', false);
            });

            // Ganti ukuran kertas langsung reload pratinjau
            $paperSelect.on('change', function() {
                if (currentPrintConfig) {
                    loadFrame();
                }
            });

            // Cetak isi iframe
            $printBtn.on('click', function() {
                const frameWindow = $frame[0]?.contentWindow;
                if (!frameWindow) return;

                frameWindow.focus();
                frameWindow.print();
            });

            // Bersihkan iframe saat modal ditutup
            $modalEl.on('hidden.bs.modal', function() {
                $frame.attr('src', 'about:blank');
                $printBtn.prop('disabled', true);
                currentPrintConfig = null;
            });

            // Fungsi global untuk dipanggil dari tombol Cetak di modul mana pun
            window.openSimdesPrintModal = function(config) {
                currentPrintConfig = config;
                $title.text(config.title ? `Pratinjau Cetak — ${config.title}` : 'Pratinjau Cetak');

                if (config.paper) {
                    $paperSelect.val(config.paper);
                }

                const modalInstance = bootstrap.Modal.getOrCreateInstance($modalEl[0]);
                modalInstance.show();
                loadFrame();
            };
        });
    </script>
@endpush
