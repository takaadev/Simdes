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
               CUSTOM DROPDOWN
            ============================================ */
        .row-action-menu {
            display: none !important;
            position: fixed !important;
            inset: auto !important;
            margin: 0 !important;
            padding: 6px !important;

            min-width: 175px;
            max-width: calc(100vw - 16px);

            border: 1px solid var(--bs-border-color);
            border-radius: 10px;
            background: var(--bs-body-bg);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .16);

            z-index: 99999 !important;
            transform: none !important;
        }

        .row-action-menu.show {
            display: block !important;
        }

        .row-action-menu .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
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
            (() => {
                let activeDropdown = null;

                /**
                 * Tutup dropdown dan kembalikan
                 * menu ke posisi DOM semula.
                 */
                function closeDropdown() {
                    if (!activeDropdown) return;

                    const {
                        button,
                        menu,
                        placeholder
                    } = activeDropdown;

                    menu.classList.remove('show');
                    button.setAttribute('aria-expanded', 'false');

                    // Kembalikan menu ke parent semula
                    if (placeholder.parentNode) {
                        placeholder.parentNode.insertBefore(
                            menu,
                            placeholder
                        );

                        placeholder.remove();
                    }

                    menu.removeAttribute('style');

                    activeDropdown = null;
                }

                /**
                 * Hitung posisi dropdown berdasarkan tombol.
                 */
                function positionDropdown(button, menu) {
                    const rect = button.getBoundingClientRect();

                    const menuWidth = menu.offsetWidth;
                    const menuHeight = menu.offsetHeight;

                    const viewportWidth = window.innerWidth;
                    const viewportHeight = window.innerHeight;

                    const spacing = 8;

                    // Posisi horizontal: rata kanan tombol
                    let left = rect.right - menuWidth;

                    // Cegah dropdown keluar viewport
                    left = Math.max(
                        spacing,
                        Math.min(left, viewportWidth - menuWidth - spacing)
                    );

                    // Posisi vertikal
                    let top = rect.bottom + 6;

                    // Jika ruang bawah tidak cukup,
                    // tampilkan dropdown di atas tombol.
                    if (
                        top + menuHeight > viewportHeight - spacing &&
                        rect.top - menuHeight - 6 >= spacing
                    ) {
                        top = rect.top - menuHeight - 6;
                    }

                    // Batasi posisi agar tetap dalam viewport
                    top = Math.max(
                        spacing,
                        Math.min(top, viewportHeight - menuHeight - spacing)
                    );

                    menu.style.setProperty('left', `${left}px`, 'important');
                    menu.style.setProperty('top', `${top}px`, 'important');
                }

                /**
                 * Buka dropdown.
                 */
                function openDropdown(button) {
                    const wrapper = button.closest('.dropdown');

                    if (!wrapper) return;

                    const menu = wrapper.querySelector('.row-action-menu');

                    if (!menu) return;

                    // Tutup dropdown sebelumnya
                    closeDropdown();

                    // Simpan posisi asli menu
                    const placeholder = document.createComment(
                        'row-action-menu'
                    );

                    menu.parentNode.insertBefore(placeholder, menu);

                    activeDropdown = {
                        button,
                        menu,
                        placeholder
                    };

                    // Pindahkan menu ke BODY
                    document.body.appendChild(menu);

                    button.setAttribute('aria-expanded', 'true');

                    menu.classList.add('show');

                    // Hitung posisi setelah menu dirender
                    positionDropdown(button, menu);
                }

                /**
                 * Event klik tombol dan area luar.
                 */
                document.addEventListener('click', function(e) {
                    const button = e.target.closest('.btn-row-action');

                    // Klik tombol dropdown
                    if (button) {
                        e.preventDefault();
                        e.stopPropagation();

                        if (
                            activeDropdown?.button === button
                        ) {
                            closeDropdown();
                        } else {
                            openDropdown(button);
                        }

                        return;
                    }

                    // Klik di dalam dropdown
                    if (
                        activeDropdown?.menu.contains(e.target)
                    ) {
                        const item = e.target.closest('.dropdown-item');

                        if (item) {
                            closeDropdown();
                        }

                        return;
                    }

                    // Klik di luar dropdown
                    closeDropdown();

                }, true);

                /**
                 * Tutup menggunakan tombol Escape.
                 */
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closeDropdown();
                    }
                });

                /**
                 * Tutup saat halaman digulir atau di-resize.
                 */
                window.addEventListener('scroll', closeDropdown, true);
                window.addEventListener('resize', closeDropdown);

                /**
                 * Tutup jika tombol dihapus oleh DataTables
                 * ketika melakukan redraw.
                 */
                const observer = new MutationObserver(() => {
                    if (
                        activeDropdown &&
                        !activeDropdown.button.isConnected
                    ) {
                        closeDropdown();
                    }
                });

                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });

            })
            ();
        </script>
    @endpush
@endonce

<div class="dropdown">
    <button class="btn-row-action" type="button" aria-expanded="false" aria-label="Menu aksi">
        <i class="fa-solid fa-ellipsis-vertical"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end row-action-menu">
        {{ $slot }}
    </ul>
</div>
