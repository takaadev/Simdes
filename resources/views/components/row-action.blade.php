@push('header')
    <style>
        /* ============================================
                               TOMBOL AKSI
                            ============================================ */
        .btn-row-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            padding: 0;
            border: 1px solid transparent;
            border-radius: 8px;
            background: transparent;
            color: var(--bs-secondary-color);
            cursor: pointer;
        }

        .btn-row-action:hover,
        .btn-row-action[aria-expanded="true"] {
            background: var(--bs-tertiary-bg);
            border-color: var(--bs-border-color);
        }

        .btn-row-action i {
            font-size: 16px;
        }

        /* ============================================
                               MENU DROPDOWN
                            ============================================ */
        .row-action-menu {
            position: relative;
            min-width: 175px;
            padding: 6px;
            border: 1px solid var(--bs-border-color);
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
            z-index: 1080 !important;
            right: 100px;
        }

        .row-action-menu .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
        }

        .row-action-menu .dropdown-item i {
            width: 17px;
            font-size: 14px;
            text-align: center;
            color: var(--bs-secondary-color);
        }

        .row-action-menu .dropdown-item.text-danger,
        .row-action-menu .dropdown-item.text-danger i {
            color: var(--bs-danger) !important;
        }
    </style>
@endpush

@once
    @push('scripts')
        <script>
            // 1) Pakai strategy "fixed" agar menu tidak terpotong overflow
            //    wadah tabel. Capture phase -> jalan sebelum data-api Bootstrap,
            //    jadi juga bekerja untuk baris hasil redraw DataTables.
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-row-action');
                if (!btn) return;

                bootstrap.Dropdown.getOrCreateInstance(btn, {
                    popperConfig: (defaultConfig) => ({
                        ...defaultConfig,
                        strategy: 'fixed',
                    }),
                });
            }, true);

            // 2) Naikkan z-index sel yang dropdown-nya terbuka
            //    (cadangan untuk browser tanpa dukungan :has()).
            document.addEventListener('show.bs.dropdown', function(e) {
                e.target.closest('td')?.classList.add('has-open-dropdown');
            });
            document.addEventListener('hidden.bs.dropdown', function(e) {
                e.target.closest('td')?.classList.remove('has-open-dropdown');
            });
        </script>
    @endpush
@endonce

<div class="dropdown">
    <button class="btn-row-action" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menu aksi">
        <i class="fa-solid fa-ellipsis-vertical"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end row-action-menu">
        {{ $slot }}
    </ul>
</div>
