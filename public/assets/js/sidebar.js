
/* ==========================================================================
   SIMDES DESA TIARO — Sidebar & Responsive Offcanvas Controller
   sidebar.js

   Manages:
   - Desktop collapsible sidebar
   - Mobile offcanvas drawer
   - Backdrop and inert background
   - Collapsed sidebar tooltips
   - Sidebar group toggle
   - Keyboard accessibility
   ========================================================================== */

(() => {
    'use strict';

    const STORAGE_KEY = 'simdes_tiaro_sidebar_collapsed';
    const DESKTOP_BREAKPOINT = 992;

    function initSidebar() {
        const sidebar = document.getElementById('appSidebar');
        const mainArea = document.getElementById('appMainWrapper');
        const toggleBtn = document.getElementById('sidebarToggleBtn');
        const closeMobileBtn = document.getElementById('sidebarCloseMobileBtn');
        const backdrop = document.getElementById('sidebarBackdrop');

        if (!sidebar || !toggleBtn) return;

        let tooltipInstances = [];

        const isDesktop = () => window.innerWidth >= DESKTOP_BREAKPOINT;

        /**
         * Refresh tooltip pada sidebar yang sedang collapsed.
         */
        const refreshCollapsedTooltips = () => {
            tooltipInstances.forEach((instance) => instance.dispose());
            tooltipInstances = [];

            if (!window.bootstrap?.Tooltip) return;

            if (isDesktop() && document.body.classList.contains('sidebar-collapsed')) {
                sidebar.querySelectorAll('[data-sidebar-tooltip]').forEach((element) => {
                    const title = element.getAttribute('data-sidebar-tooltip');

                    if (!title) return;

                    tooltipInstances.push(
                        new window.bootstrap.Tooltip(element, {
                            title,
                            placement: 'right',
                            trigger: 'hover focus'
                        })
                    );
                });
            }
        };

        /**
         * Mengatur kondisi sidebar desktop.
         */
        const setDesktopCollapsed = (collapsed, save = true) => {
            document.body.classList.toggle('sidebar-collapsed', collapsed);

            toggleBtn.setAttribute('aria-expanded', String(!collapsed));
            toggleBtn.setAttribute(
                'aria-label',
                collapsed
                    ? 'Perluas panel navigasi samping'
                    : 'Ciutkan panel navigasi samping'
            );

            if (save) {
                try {
                    localStorage.setItem(STORAGE_KEY, collapsed ? '1' : '0');
                } catch (_) {
                    // Ignore storage error.
                }
            }

            refreshCollapsedTooltips();
        };

        /**
         * Membuka sidebar mobile.
         */
        const openMobileOffcanvas = () => {
            sidebar.classList.add('offcanvas-open');
            backdrop?.classList.add('show');

            document.body.style.overflow = 'hidden';

            toggleBtn.setAttribute('aria-expanded', 'true');

            if (mainArea) {
                mainArea.inert = true;
            }

            window.requestAnimationFrame(() => {
                (closeMobileBtn || sidebar.querySelector('a, button'))?.focus();
            });
        };

        /**
         * Menutup sidebar mobile.
         */
        const closeMobileOffcanvas = (restoreFocus = false) => {
            if (!sidebar.classList.contains('offcanvas-open')) return;

            sidebar.classList.remove('offcanvas-open');
            backdrop?.classList.remove('show');

            document.body.style.overflow = '';

            toggleBtn.setAttribute('aria-expanded', 'false');

            if (mainArea) {
                mainArea.inert = false;
            }

            if (restoreFocus) {
                toggleBtn.focus();
            }
        };

        /**
         * Memuat preferensi sidebar desktop.
         */
        try {
            const saved = localStorage.getItem(STORAGE_KEY);

            if (isDesktop() && saved === '1') {
                setDesktopCollapsed(true, false);
            } else {
                toggleBtn.setAttribute(
                    'aria-expanded',
                    isDesktop() ? 'true' : 'false'
                );
            }
        } catch (_) {
            // Ignore storage error.
        }

        /**
         * Tombol utama sidebar.
         */
        toggleBtn.addEventListener('click', () => {
            if (isDesktop()) {
                const nextCollapsed = !document.body.classList.contains(
                    'sidebar-collapsed'
                );

                setDesktopCollapsed(nextCollapsed, true);
            } else if (sidebar.classList.contains('offcanvas-open')) {
                closeMobileOffcanvas(true);
            } else {
                openMobileOffcanvas();
            }
        });

        /**
         * Tombol tutup dan backdrop mobile.
         */
        closeMobileBtn?.addEventListener('click', () => {
            closeMobileOffcanvas(true);
        });

        backdrop?.addEventListener('click', () => {
            closeMobileOffcanvas(true);
        });

        /**
         * Menutup sidebar mobile dengan tombol Escape.
         */
        document.addEventListener('keydown', (event) => {
            if (
                event.key === 'Escape' &&
                sidebar.classList.contains('offcanvas-open')
            ) {
                closeMobileOffcanvas(true);
            }
        });

        /**
         * Menyesuaikan sidebar ketika ukuran layar berubah.
         */
        window.addEventListener('resize', () => {
            if (isDesktop()) {
                closeMobileOffcanvas(false);
                refreshCollapsedTooltips();
            } else {
                tooltipInstances.forEach((instance) => instance.dispose());
                tooltipInstances = [];
            }
        });

        /**
         * Toggle grup menu.
         * Konten menu disediakan oleh Blade, bukan dibuat oleh JavaScript.
         */
        sidebar.querySelectorAll('[data-sidebar-group]').forEach((groupBtn) => {
            groupBtn.addEventListener('click', () => {
                if (
                    isDesktop() &&
                    document.body.classList.contains('sidebar-collapsed')
                ) {
                    setDesktopCollapsed(false, true);
                }

                const panelId = groupBtn.getAttribute('aria-controls');
                const panel = panelId
                    ? document.getElementById(panelId)
                    : null;

                if (!panel) return;

                const isExpanded =
                    groupBtn.getAttribute('aria-expanded') === 'true';

                groupBtn.setAttribute(
                    'aria-expanded',
                    String(!isExpanded)
                );

                panel.hidden = isExpanded;
            });
        });
    }

    document.addEventListener('DOMContentLoaded', initSidebar);

    window.SimdesSidebar = {
        init: initSidebar
    };
})();
