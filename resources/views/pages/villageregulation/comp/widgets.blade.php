<section class="row g-3 mb-4 ledger-kpi-row" aria-label="Ringkasan Status Peraturan Desa">

    {{-- Draft --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <article class="stat-card py-3">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div>
                    <div class="stat-label mb-1">Draft</div>
                    <div class="stat-value fs-3" id="statStatusDraft">0</div>
                </div>
                <div class="stat-icon-box stat-icon-secondary">
                    <i class="fa-solid fa-file-pen" aria-hidden="true"></i>
                </div>
            </div>
        </article>
    </div>

    {{-- Berlaku --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <article class="stat-card py-3">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div>
                    <div class="stat-label mb-1">Berlaku</div>
                    <div class="stat-value fs-3" id="statStatusActive">0</div>
                </div>
                <div class="stat-icon-box stat-icon-primary">
                    <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
                </div>
            </div>
        </article>
    </div>

    {{-- Dicabut --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <article class="stat-card py-3">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div>
                    <div class="stat-label mb-1">Dicabut</div>
                    <div class="stat-value fs-3" id="statStatusRevoked">0</div>
                </div>
                <div class="stat-icon-box stat-icon-accent">
                    <i class="fa-solid fa-ban" aria-hidden="true"></i>
                </div>
            </div>
        </article>
    </div>

    {{-- Diubah --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <article class="stat-card py-3">
            <div class="d-flex align-items-center justify-content-between gap-2">
                <div>
                    <div class="stat-label mb-1">Diubah</div>
                    <div class="stat-value fs-3" id="statStatusAmended">0</div>
                </div>
                <div class="stat-icon-box stat-icon-info">
                    <i class="fa-solid fa-file-circle-check" aria-hidden="true"></i>
                </div>
            </div>
        </article>
    </div>

</section>

@push('footer')
    <script>
        function updateSummary(summary) {
            if (!summary) return;

            $('#statStatusDraft').text(summary.draft ?? 0);
            $('#statStatusActive').text(summary.active ?? 0);
            $('#statStatusRevoked').text(summary.revoked ?? 0);
            $('#statStatusAmended').text(summary.amended ?? 0);
        }

        $('#simdesDataTable').on('xhr.dt', function(e, settings, json) {
            updateSummary(json?.summary);
        });
    </script>
@endpush
