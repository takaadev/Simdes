<div class="modal fade simdes-modal" id="logoutConfirmModal" tabindex="-1" aria-labelledby="logoutConfirmModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="logoutConfirmModalLabel">Konfirmasi Keluar</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body" style="font-size:0.875rem; color:var(--simdes-text-muted);">
                Apakah Anda yakin ingin mengakhiri sesi kerja pada <strong>SIMDES Desa Tiaro</strong> dan
                kembali ke halaman masuk?
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" id="cancelLogoutBtn" class="btn-simdes-outline" data-bs-dismiss="modal">
                    Batal
                </button>
                <form id="simdesLogoutForm" action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" id="confirmLogoutBtn" class="btn-simdes-primary"
                        style="background-color:var(--simdes-danger); border-color:var(--simdes-danger);">
                        <i class="fa-solid fa-arrow-right-from-bracket" aria-hidden="true"></i>
                        <span>Ya, Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('footer')
    <script>
        $(function() {
            const $logoutModal = $('#logoutConfirmModal');
            const $logoutForm = $('#simdesLogoutForm');
            const $confirmBtn = $('#confirmLogoutBtn');
            const $cancelBtn = $('#cancelLogoutBtn');
            const $closeBtn = $logoutModal.find('.btn-close');

            const defaultBtnHtml = $confirmBtn.html();
            let isLoggingOut = false;

            function setLogoutLoading(state, label = 'Mengakhiri sesi...') {
                isLoggingOut = state;
                $confirmBtn.prop('disabled', state);
                $cancelBtn.prop('disabled', state);
                $closeBtn.prop('disabled', state);

                if (state) {
                    $confirmBtn.html(
                        `<i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i><span>${label}</span>`
                    );
                } else {
                    $confirmBtn.html(defaultBtnHtml);
                }
            }

            $logoutForm.on('submit', function(e) {
                e.preventDefault();
                if (isLoggingOut) return;

                setLogoutLoading(true, 'Keluar...');

                $.ajax({
                    url: $logoutForm.attr('action'),
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    data: {
                        _token: $logoutForm.find('input[name="_token"]').val(),
                    },
                    success: function(response) {
                        $confirmBtn.html(
                            '<i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Mengalihkan...</span>'
                        );
                        window.location.href = response.redirect ||
                        @json(route('login'));
                    },
                    error: function(xhr) {
                        if (xhr.status === 419 || xhr.status === 401) {
                            window.location.href = @json(route('login'));
                            return;
                        }

                        setLogoutLoading(false);
                    }
                });
            });
        });
    </script>
@endpush
