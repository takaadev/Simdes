import { $ } from '../bootstrap';

(function ($, window, document) {
    'use strict';

    const STORAGE_KEY = 'simdes_tiaro_sidebar_collapsed';
    const DESKTOP_BREAKPOINT = 992;
    const EVENT_NAMESPACE = '.simdesSidebar';

    // ======================================================================
    // DATA MENU
    // ======================================================================

    const MENU_ADM_UMUM = [
        { no: '1', slug: 'buku-peraturan-di-desa', label: 'Buku Peraturan di Desa', path: 'pages/administrasi-umum/buku-peraturan-di-desa.html', ready: true },
        { no: '2', slug: 'buku-keputusan-kepala-desa', label: 'Buku Keputusan Kepala Desa', path: 'pages/administrasi-umum/buku-keputusan-kepala-desa.html', ready: true },
        { no: '3', slug: 'buku-inventaris-dan-kekayaan-desa', label: 'Buku Inventaris dan Kekayaan Desa', path: 'pages/administrasi-umum/buku-inventaris-dan-kekayaan-desa.html', ready: true },
        { no: '4', slug: 'buku-aparat-pemerintah-desa', label: 'Buku Aparat Pemerintah Desa', path: 'pages/administrasi-umum/buku-aparat-pemerintah-desa.html', ready: true },
        { no: '5', slug: 'buku-tanah-kas-desa', label: 'Buku Tanah Kas Desa', path: 'pages/administrasi-umum/buku-tanah-kas-desa.html', ready: true },
        { no: '6', slug: 'buku-tanah-di-desa', label: 'Buku Tanah di Desa', path: 'pages/administrasi-umum/buku-tanah-di-desa.html', ready: true },
        { no: '7', slug: 'buku-agenda', label: 'Buku Agenda', path: 'pages/administrasi-umum/buku-agenda.html', ready: false },
        { no: '8', slug: 'buku-ekspedisi', label: 'Buku Ekspedisi', path: 'pages/administrasi-umum/buku-ekspedisi.html', ready: true },
        { no: '9', slug: 'buku-lembaran-desa-dan-berita-desa', label: 'Buku Lembaran Desa dan Berita Desa', path: 'pages/administrasi-umum/buku-lembaran-desa-dan-berita-desa.html', ready: true }
    ];

    const MENU_ARSIP_SURAT = [
        { no: '1', slug: 'buku-agenda', navKey: 'arsip-buku-agenda', label: 'Buku Agenda', path: 'pages/arsip-surat/buku-agenda.html', ready: true, enabled: true },
        { no: '2', slug: 'surat-masuk', navKey: 'arsip-surat-masuk', label: 'Surat Masuk', path: 'pages/arsip-surat/surat-masuk.html', ready: false, enabled: false },
        { no: '3', slug: 'surat-keluar', navKey: 'arsip-surat-keluar', label: 'Surat Keluar', path: 'pages/arsip-surat/surat-keluar.html', ready: false, enabled: false },
        { no: '4', slug: 'klasifikasi-surat', navKey: 'arsip-klasifikasi-surat', label: 'Klasifikasi Surat', path: 'pages/arsip-surat/klasifikasi-surat.html', ready: false, enabled: false },
        { no: '5', slug: 'arsip-dokumen-surat', navKey: 'arsip-dokumen-surat', label: 'Arsip Dokumen Surat', path: 'pages/arsip-surat/arsip-dokumen-surat.html', ready: false, enabled: false }
    ];

    const MENU_ADM_PENDUDUK = [
        { no: '1', slug: 'buku-induk-penduduk', label: 'Buku Induk Penduduk', path: 'pages/administrasi-penduduk/buku-induk-penduduk.html', ready: true },
        { no: '2', slug: 'buku-mutasi-penduduk-desa', label: 'Buku Mutasi Penduduk Desa', path: 'pages/administrasi-penduduk/buku-mutasi-penduduk-desa.html', ready: true },
        { no: '3', slug: 'buku-rekapitulasi-jumlah-penduduk', label: 'Buku Rekapitulasi Jumlah Penduduk', path: 'pages/administrasi-penduduk/buku-rekapitulasi-jumlah-penduduk.html', ready: true },
        { no: '4', slug: 'buku-penduduk-sementara', label: 'Buku Penduduk Sementara', path: 'pages/administrasi-penduduk/buku-penduduk-sementara.html', ready: true },
        { no: '5', slug: 'buku-ktp-dan-kk', label: 'Buku Kartu Tanda Penduduk dan Buku Kartu Keluarga', path: 'pages/administrasi-penduduk/buku-ktp-dan-kk.html', ready: true }
    ];

    // ======================================================================
    // HELPERS
    // ======================================================================

    const isDesktop = () => window.innerWidth >= DESKTOP_BREAKPOINT;

    const getRootPrefix = () => {
        return window.SimdesApp
            ? window.SimdesApp.getRootPrefix()
            : './';
    };

    const escapeHtml = (value) => {
        if (window.SimdesApp?.escapeHtml) {
            return window.SimdesApp.escapeHtml(value);
        }

        return $('<div>').text(value ?? '').html();
    };

    const showToast = (options) => {
        window.SimdesApp?.showToast(options);
    };

    const getSavedState = () => {
        try {
            return localStorage.getItem(STORAGE_KEY) === '1';
        } catch (error) {
            return false;
        }
    };

    const saveState = (collapsed) => {
        try {
            localStorage.setItem(STORAGE_KEY, collapsed ? '1' : '0');
        } catch (error) {
            // Ignore storage error
        }
    };

    // ======================================================================
    // DYNAMIC ARSIP SURAT GROUP
    // ======================================================================

    function ensureArsipSuratGroup($sidebar, badgeCount) {
        let $arsipBtn = $sidebar.find('[data-sidebar-group="arsip-surat"]').first();
        let $arsipPanel = $('#sidebarGroupArsipInfo');

        if (!$arsipBtn.length) {
            const $umumBtn = $sidebar.find('[data-sidebar-group="umum"]').first();
            const $umumLi = $umumBtn.closest('li');
            const $menuList = $umumLi.parent().length
                ? $umumLi.parent()
                : $sidebar.find('.sidebar-menu').last();

            if ($menuList.length) {
                const html = `
                    <li>
                        <button
                            type="button"
                            class="sidebar-link"
                            data-sidebar-group="arsip-surat"
                            data-sidebar-tooltip="Arsip Surat"
                            aria-expanded="false"
                            aria-controls="sidebarGroupArsipInfo"
                        >
                            <span class="sidebar-link-icon">
                                <i class="fa-solid fa-folder-open" aria-hidden="true"></i>
                            </span>

                            <span class="sidebar-link-text">Arsip Surat</span>

                            <span class="sidebar-badge">${badgeCount}</span>

                            <i class="fa-solid fa-chevron-down sidebar-chevron" aria-hidden="true"></i>
                        </button>

                        <div
                            id="sidebarGroupArsipInfo"
                            class="sidebar-group-panel"
                            hidden
                        ></div>
                    </li>
                `;

                const $newItem = $(html);

                if ($umumLi.length) {
                    $umumLi.after($newItem);
                } else {
                    $menuList.append($newItem);
                }

                $arsipBtn = $newItem.find('[data-sidebar-group="arsip-surat"]');
                $arsipPanel = $newItem.find('#sidebarGroupArsipInfo');
            }
        } else {
            $arsipBtn.find('.sidebar-badge').text(badgeCount);
        }

        return {
            $arsipBtn,
            $arsipPanel
        };
    }

    // ======================================================================
    // SUBMENU RENDERING
    // ======================================================================

    function buildSubmenuHtml(items) {
        const rootPrefix = getRootPrefix();
        const currentPath = window.location.pathname;
        const activeSlug = $('body').data('active-nav') || '';

        const enabledItems = items.filter(item => item.enabled !== false);

        const submenuItems = enabledItems.map(item => {
            const itemKey = item.navKey || item.slug;

            const isActive = activeSlug
                ? activeSlug === itemKey
                : currentPath.endsWith(`/${item.path}`);

            const href = `${rootPrefix}${item.path}`;

            return `
                <li>
                    <a
                        href="${escapeHtml(href)}"
                        class="sidebar-sublink${isActive ? ' active' : ''}"
                        ${isActive ? 'aria-current="page"' : ''}
                        data-book-ready="${item.ready ? 'true' : 'false'}"
                        data-book-label="${escapeHtml(item.label)}"
                    >
                        <span class="sidebar-sublink-num">${escapeHtml(item.no)}.</span>
                        <span>${escapeHtml(item.label)}</span>
                    </a>
                </li>
            `;
        }).join('');

        return `
            <ul class="sidebar-submenu">
                ${submenuItems}
            </ul>
        `;
    }

    function hydrateBookSubmenus($sidebar) {
        const enabledArsipItems = MENU_ARSIP_SURAT.filter(
            item => item.enabled !== false
        );

        const {
            $arsipBtn,
            $arsipPanel
        } = ensureArsipSuratGroup($sidebar, enabledArsipItems.length);

        const $umumPanel = $('#sidebarGroupUmumInfo');
        const $pendudukPanel = $('#sidebarGroupPendudukInfo');

        const $umumBtn = $sidebar.find('[data-sidebar-group="umum"]').first();
        const $pendudukBtn = $sidebar.find('[data-sidebar-group="penduduk"]').first();

        // Administrasi Umum
        if ($umumPanel.length) {
            $umumPanel
                .addClass('is-submenu')
                .html(buildSubmenuHtml(MENU_ADM_UMUM));

            const hasActive = $umumPanel.find('.sidebar-sublink.active').length > 0;

            if (hasActive) {
                $umumPanel.prop('hidden', false);

                $umumBtn
                    .addClass('active')
                    .attr('aria-expanded', 'true');
            }
        }

        // Arsip Surat
        if ($arsipPanel.length) {
            $arsipPanel
                .addClass('is-submenu')
                .html(buildSubmenuHtml(enabledArsipItems));

            const hasActive = $arsipPanel.find('.sidebar-sublink.active').length > 0;

            if (hasActive) {
                $arsipPanel.prop('hidden', false);

                $arsipBtn
                    .addClass('active')
                    .attr('aria-expanded', 'true');
            }
        }

        // Administrasi Penduduk
        if ($pendudukPanel.length) {
            $pendudukPanel
                .addClass('is-submenu')
                .html(buildSubmenuHtml(MENU_ADM_PENDUDUK));

            const hasActive = $pendudukPanel.find('.sidebar-sublink.active').length > 0;

            if (hasActive) {
                $pendudukPanel.prop('hidden', false);

                $pendudukBtn
                    .addClass('active')
                    .attr('aria-expanded', 'true');
            }
        }
    }

    // ======================================================================
    // BOOTSTRAP TOOLTIP
    // ======================================================================

    function createTooltipManager($sidebar) {
        let tooltipInstances = [];

        function destroy() {
            tooltipInstances.forEach(instance => instance.dispose());
            tooltipInstances = [];
        }

        function refresh() {
            destroy();

            if (!window.bootstrap?.Tooltip) {
                return;
            }

            if (!isDesktop() || !$('body').hasClass('sidebar-collapsed')) {
                return;
            }

            $sidebar.find('[data-sidebar-tooltip]').each(function () {
                const title = $(this).attr('data-sidebar-tooltip');

                if (!title) {
                    return;
                }

                tooltipInstances.push(
                    new window.bootstrap.Tooltip(this, {
                        title,
                        placement: 'right',
                        trigger: 'hover focus'
                    })
                );
            });
        }

        return {
            refresh,
            destroy
        };
    }

    // ======================================================================
    // SIDEBAR INITIALIZATION
    // ======================================================================

    function initSidebar() {
        const $sidebar = $('#appSidebar');
        const $mainArea = $('#appMainWrapper');
        const $toggleBtn = $('#sidebarToggleBtn');
        const $closeMobileBtn = $('#sidebarCloseMobileBtn');
        const $backdrop = $('#sidebarBackdrop');

        if (!$sidebar.length || !$toggleBtn.length) {
            return;
        }

        // Hindari event handler ganda ketika init dipanggil kembali.
        $(document).off(EVENT_NAMESPACE);
        $(window).off(EVENT_NAMESPACE);
        $sidebar.off(EVENT_NAMESPACE);

        hydrateBookSubmenus($sidebar);

        const tooltipManager = createTooltipManager($sidebar);

        // ------------------------------------------------------------------
        // DESKTOP COLLAPSE
        // ------------------------------------------------------------------

        function setDesktopCollapsed(collapsed, save = true) {
            $('body').toggleClass('sidebar-collapsed', collapsed);

            $toggleBtn
                .attr('aria-expanded', String(!collapsed))
                .attr(
                    'aria-label',
                    collapsed
                        ? 'Perluas panel navigasi samping'
                        : 'Ciutkan panel navigasi samping'
                );

            if (save) {
                saveState(collapsed);
            }

            tooltipManager.refresh();
        }

        // ------------------------------------------------------------------
        // MOBILE OFFCANVAS
        // ------------------------------------------------------------------

        function openMobileOffcanvas() {
            $sidebar.addClass('offcanvas-open');
            $backdrop.addClass('show');

            $('body').css('overflow', 'hidden');

            $toggleBtn.attr('aria-expanded', 'true');

            $mainArea.prop('inert', true);

            window.requestAnimationFrame(() => {
                const $focusTarget = $closeMobileBtn.length
                    ? $closeMobileBtn
                    : $sidebar.find('a, button').first();

                $focusTarget.trigger('focus');
            });
        }

        function closeMobileOffcanvas(restoreFocus = false) {
            if (!$sidebar.hasClass('offcanvas-open')) {
                return;
            }

            $sidebar.removeClass('offcanvas-open');
            $backdrop.removeClass('show');

            $('body').css('overflow', '');

            $toggleBtn.attr('aria-expanded', 'false');

            $mainArea.prop('inert', false);

            if (restoreFocus) {
                $toggleBtn.trigger('focus');
            }
        }

        // ------------------------------------------------------------------
        // INITIAL STATE
        // ------------------------------------------------------------------

        if (isDesktop() && getSavedState()) {
            setDesktopCollapsed(true, false);
        } else {
            $toggleBtn.attr(
                'aria-expanded',
                String(isDesktop())
            );
        }

        // ------------------------------------------------------------------
        // TOGGLE BUTTON
        // ------------------------------------------------------------------

        $toggleBtn.off(EVENT_NAMESPACE).on(
            `click${EVENT_NAMESPACE}`,
            function (event) {
                event.preventDefault();

                if (isDesktop()) {
                    const nextCollapsed = !$('body').hasClass('sidebar-collapsed');

                    setDesktopCollapsed(nextCollapsed, true);

                    return;
                }

                if ($sidebar.hasClass('offcanvas-open')) {
                    closeMobileOffcanvas(true);
                } else {
                    openMobileOffcanvas();
                }
            }
        );

        // ------------------------------------------------------------------
        // CLOSE MOBILE BUTTON
        // ------------------------------------------------------------------

        $closeMobileBtn.off(EVENT_NAMESPACE).on(
            `click${EVENT_NAMESPACE}`,
            function (event) {
                event.preventDefault();
                closeMobileOffcanvas(true);
            }
        );

        // ------------------------------------------------------------------
        // BACKDROP
        // ------------------------------------------------------------------

        $backdrop.off(EVENT_NAMESPACE).on(
            `click${EVENT_NAMESPACE}`,
            function () {
                closeMobileOffcanvas(true);
            }
        );

        // ------------------------------------------------------------------
        // ESCAPE KEY
        // ------------------------------------------------------------------

        $(document).on(
            `keydown${EVENT_NAMESPACE}`,
            function (event) {
                if (
                    event.key === 'Escape' &&
                    $sidebar.hasClass('offcanvas-open')
                ) {
                    closeMobileOffcanvas(true);
                }
            }
        );

        // ------------------------------------------------------------------
        // RESPONSIVE RESIZE
        // ------------------------------------------------------------------

        $(window).on(
            `resize${EVENT_NAMESPACE}`,
            function () {
                if (isDesktop()) {
                    closeMobileOffcanvas(false);
                    tooltipManager.refresh();
                } else {
                    tooltipManager.destroy();

                    // Pastikan status collapse desktop tidak terbawa ke mobile.
                    $('body').removeClass('sidebar-collapsed');
                }
            }
        );

        // ------------------------------------------------------------------
        // SIDEBAR GROUP TOGGLE
        // ------------------------------------------------------------------

        $sidebar.on(
            `click${EVENT_NAMESPACE}`,
            '[data-sidebar-group]',
            function (event) {
                event.preventDefault();

                const $groupBtn = $(this);

                if (
                    isDesktop() &&
                    $('body').hasClass('sidebar-collapsed')
                ) {
                    setDesktopCollapsed(false, true);
                }

                const panelId = $groupBtn.attr('aria-controls');

                if (!panelId) {
                    return;
                }

                const $panel = $(`#${panelId}`);

                if (!$panel.length) {
                    return;
                }

                const isExpanded = $groupBtn.attr('aria-expanded') === 'true';

                $groupBtn.attr('aria-expanded', String(!isExpanded));

                $panel.prop('hidden', isExpanded);
            }
        );

        // ------------------------------------------------------------------
        // DISABLED BOOK LINKS
        // ------------------------------------------------------------------

        $sidebar.on(
            `click${EVENT_NAMESPACE}`,
            '[data-book-ready="false"]',
            function (event) {
                event.preventDefault();

                const label = $(this).attr('data-book-label') || 'Buku Administrasi';

                showToast({
                    title: label,
                    message: 'Halaman buku ini menunggu instruksi struktur kolom tabel berikutnya.',
                    variant: 'primary'
                });
            }
        );

        // ------------------------------------------------------------------
        // INITIAL TOOLTIP
        // ------------------------------------------------------------------

        tooltipManager.refresh();
    }

    // ======================================================================
    // PUBLIC API
    // ======================================================================

    window.SimdesSidebar = {
        init: initSidebar
    };

    // ======================================================================
    // DOM READY
    // ======================================================================

    $(function () {
        initSidebar();
    });

})( $, window, document );
