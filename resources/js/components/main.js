
/* ==========================================================================
   SIMDES DESA TIARO — Core Application Controller
   app.js — jQuery 4.0.0

   Handles:
   - Dummy user session
   - User profile hydration
   - Global quick search
   - Notifications dropdown
   - Bootstrap modals
   - Accessible toast alerts
   ========================================================================== */

import { $ } from '../bootstrap';

(function ($, window, document) {
    'use strict';

    const SESSION_KEY = 'simdes_tiaro_user_session';
    const EVENT_NAMESPACE = '.simdesApp';

    // ======================================================================
    // DEFAULT USER
    // ======================================================================

    const DEFAULT_USER = {
        username: 'sekdes.tiaro',
        fullName: 'Budi Santoso, S.AP',
        role: 'Sekretaris Desa',
        nipd: '19880412 201503 1 002',
        unit: 'Sekretariat Pemerintah Desa Tiaro',
        initials: 'BS',
        lastLogin: 'Hari ini, 08:15 WITA'
    };

    // ======================================================================
    // DEMO PROFILES
    // ======================================================================

    const DEMO_PROFILES = {
        'sekdes.tiaro': DEFAULT_USER,

        'kades.tiaro': {
            username: 'kades.tiaro',
            fullName: 'H. Hendra Wijaya, S.Sos',
            role: 'Kepala Desa Tiaro',
            nipd: '19750819 202105 1 001',
            unit: 'Pimpinan Pemerintah Desa Tiaro',
            initials: 'HW',
            lastLogin: 'Hari ini, 08:05 WITA'
        },

        'kaur.umum': {
            username: 'kaur.umum',
            fullName: 'Siti Rahmawati, A.Md',
            role: 'Kaur Tata Usaha & Umum',
            nipd: '19930621 201902 2 004',
            unit: 'Urusan Tata Usaha & Umum',
            initials: 'SR',
            lastLogin: 'Hari ini, 08:22 WITA'
        },

        'kasi.pemerintahan': {
            username: 'kasi.pemerintahan',
            fullName: 'La Ode Muh. Fajar, S.IP',
            role: 'Kasi Pemerintahan',
            nipd: '19911104 201801 1 003',
            unit: 'Seksi Pemerintahan & Kependudukan',
            initials: 'LF',
            lastLogin: 'Hari ini, 08:10 WITA'
        }
    };

    // ======================================================================
    // HELPERS
    // ======================================================================

    /**
     * Escape HTML untuk mencegah HTML injection.
     */
    function escapeHtml(value) {
        return $('<div>').text(String(value ?? '')).html();
    }

    /**
     * Mendapatkan root prefix aplikasi.
     */
    function getRootPrefix() {
        return $('body').data('root-prefix') || './';
    }

    /**
     * Membuat inisial nama pengguna.
     */
    function computeInitials(name) {
        const parts = String(name || 'Aparat Desa')
            .replace(/,.*/, '')
            .trim()
            .split(/\s+/)
            .filter(Boolean);

        if (parts.length === 0) {
            return 'AD';
        }

        if (parts.length === 1) {
            return parts[0].slice(0, 2).toUpperCase();
        }

        return (
            parts[0][0] +
            parts[parts.length - 1][0]
        ).toUpperCase();
    }

    // ======================================================================
    // USER PROFILE
    // ======================================================================

    /**
     * Membuat profil berdasarkan username.
     */
    function buildProfileFromUsername(rawUsername) {
        const key = String(rawUsername || '').trim().toLowerCase();

        if (DEMO_PROFILES[key]) {
            return { ...DEMO_PROFILES[key] };
        }

        const formattedName = String(rawUsername || 'Aparat Desa')
            .replace(/[._-]+/g, ' ')
            .replace(/\b\w/g, (ch) => ch.toUpperCase());

        return {
            username: rawUsername,
            fullName: formattedName,
            role: 'Aparat Pemerintah Desa',
            nipd: '19900101 202001 1 009',
            unit: 'Kantor Pemerintah Desa Tiaro',
            initials: computeInitials(formattedName),
            lastLogin: 'Baru saja'
        };
    }

    /**
     * Mengambil session pengguna.
     */
    function getUserSession() {
        try {
            const raw =
                sessionStorage.getItem(SESSION_KEY) ||
                localStorage.getItem(SESSION_KEY);

            if (raw) {
                const parsed = JSON.parse(raw);

                if (parsed && parsed.fullName) {
                    return parsed;
                }
            }
        } catch (error) {
            // Ignore storage access errors.
        }

        return { ...DEFAULT_USER };
    }

    /**
     * Menyimpan session pengguna.
     */
    function setUserSession(username, persist = false) {
        const profile = buildProfileFromUsername(username);

        try {
            const payload = JSON.stringify(profile);

            sessionStorage.setItem(SESSION_KEY, payload);

            if (persist) {
                localStorage.setItem(SESSION_KEY, payload);
            } else {
                localStorage.removeItem(SESSION_KEY);
            }
        } catch (error) {
            // Fallback gracefully if storage is unavailable.
        }

        return profile;
    }

    /**
     * Menghapus session pengguna.
     */
    function clearUserSession() {
        try {
            sessionStorage.removeItem(SESSION_KEY);
            localStorage.removeItem(SESSION_KEY);
        } catch (error) {
            // Ignore storage errors.
        }
    }

    /**
     * Memperbarui identitas pengguna pada halaman.
     */
    function hydrateUserIdentity() {
        const user = getUserSession();

        $('[data-user-fullname]').text(user.fullName);
        $('[data-user-role]').text(user.role);
        $('[data-user-initials]').text(user.initials);
        $('[data-user-nipd]').text(user.nipd);
        $('[data-user-unit]').text(user.unit);
        $('[data-user-username]').text(user.username);
    }

    // ======================================================================
    // TOAST NOTIFICATIONS
    // ======================================================================

    /**
     * Memastikan container toast tersedia.
     */
    function ensureToastContainer() {
        let $container = $('#simdesToastContainer');

        if (!$container.length) {
            $container = $('<div>', {
                id: 'simdesToastContainer',
                class: 'simdes-toast-container',
                'aria-live': 'polite',
                'aria-atomic': 'false'
            });

            $('body').append($container);
        }

        return $container;
    }

    /**
     * Menampilkan toast notification.
     */
    function showToast({
        title = 'Informasi Sistem',
        message = '',
        variant = 'primary',
        duration = 4200
    } = {}) {
        const $container = ensureToastContainer();

        const variantMap = {
            primary: {
                color: 'var(--simdes-primary)',
                icon: 'fa-circle-info'
            },

            success: {
                color: 'var(--simdes-success)',
                icon: 'fa-circle-check'
            },

            warning: {
                color: 'var(--simdes-warning)',
                icon: 'fa-triangle-exclamation'
            },

            danger: {
                color: 'var(--simdes-danger)',
                icon: 'fa-circle-exclamation'
            }
        };

        const cfg = variantMap[variant] || variantMap.primary;

        const $toast = $('<div>', {
            class: 'simdes-toast',
            role: 'status'
        });

        $toast.css('border-inline-start-color', cfg.color);

        $toast.html(`
            <div style="color:${cfg.color}; font-size:1.05rem; margin-top:2px;">
                <i class="fa-solid ${cfg.icon}" aria-hidden="true"></i>
            </div>

            <div style="flex:1 1 auto; min-width:0;">
                <div style="font-weight:700; font-size:0.84rem; color:var(--simdes-text);">
                    ${escapeHtml(title)}
                </div>

                <div style="font-size:0.79rem; color:var(--simdes-text-muted); margin-top:2px;">
                    ${escapeHtml(message)}
                </div>
            </div>

            <button
                type="button"
                class="btn-close"
                style="font-size:0.7rem;"
                aria-label="Tutup notifikasi"
            ></button>
        `);

        /**
         * Menghapus toast dengan animasi.
         */
        function removeToast() {
            if (!$toast.closest('body').length) {
                return;
            }

            $toast.css({
                opacity: '0',
                transform: 'translateY(6px)',
                transition: 'opacity 160ms ease, transform 160ms ease'
            });

            window.setTimeout(() => {
                $toast.remove();
            }, 170);
        }

        $toast.on(`click${EVENT_NAMESPACE}`, '.btn-close', function () {
            removeToast();
        });

        $container.append($toast);

        if (duration > 0) {
            window.setTimeout(removeToast, duration);
        }
    }

    // ======================================================================
    // GLOBAL QUICK SEARCH
    // ======================================================================

    function initQuickSearch() {
        const $searchInput = $('#globalSearchInput');
        const $resultsBox = $('#globalSearchResults');

        if (!$searchInput.length || !$resultsBox.length) {
            return;
        }

        const rootPrefix = getRootPrefix();

        // ------------------------------------------------------------------
        // SEARCH CATALOG
        // ------------------------------------------------------------------

        const catalog = [
            {
                label: 'Dashboard Utama',
                category: 'Ringkasan Desa',
                icon: 'fa-chart-pie',
                target: '#mainContent',
                url: `${rootPrefix}pages/dashboard.html`
            },
            {
                label: 'Buku Peraturan di Desa',
                category: 'Administrasi Umum',
                icon: 'fa-scale-balanced',
                url: `${rootPrefix}pages/administrasi-umum/buku-peraturan-di-desa.html`
            },
            {
                label: 'Buku Keputusan Kepala Desa',
                category: 'Administrasi Umum',
                icon: 'fa-file-signature',
                url: `${rootPrefix}pages/administrasi-umum/buku-keputusan-kepala-desa.html`
            },
            {
                label: 'Buku Inventaris dan Kekayaan Desa',
                category: 'Administrasi Umum',
                icon: 'fa-boxes-stacked',
                url: `${rootPrefix}pages/administrasi-umum/buku-inventaris-dan-kekayaan-desa.html`
            },
            {
                label: 'Buku Aparat Pemerintah Desa',
                category: 'Administrasi Umum',
                icon: 'fa-user-tie',
                url: `${rootPrefix}pages/administrasi-umum/buku-aparat-pemerintah-desa.html`
            },
            {
                label: 'Buku Tanah Kas Desa',
                category: 'Administrasi Umum',
                icon: 'fa-map-location-dot',
                url: `${rootPrefix}pages/administrasi-umum/buku-tanah-kas-desa.html`
            },
            {
                label: 'Buku Tanah di Desa',
                category: 'Administrasi Umum',
                icon: 'fa-layer-group',
                url: `${rootPrefix}pages/administrasi-umum/buku-tanah-di-desa.html`
            },
            {
                label: 'Buku Ekspedisi',
                category: 'Administrasi Umum',
                icon: 'fa-paper-plane',
                url: `${rootPrefix}pages/administrasi-umum/buku-ekspedisi.html`
            },
            {
                label: 'Buku Lembaran Desa dan Berita Desa',
                category: 'Administrasi Umum',
                icon: 'fa-newspaper',
                url: `${rootPrefix}pages/administrasi-umum/buku-lembaran-desa-dan-berita-desa.html`
            },
            {
                label: 'Buku Agenda',
                category: 'Arsip Surat',
                icon: 'fa-folder-open',
                url: `${rootPrefix}pages/arsip-surat/buku-agenda.html`
            },
            {
                label: 'Buku Induk Penduduk',
                category: 'Administrasi Penduduk',
                icon: 'fa-address-book',
                url: `${rootPrefix}pages/administrasi-penduduk/buku-induk-penduduk.html`
            },
            {
                label: 'Buku Mutasi Penduduk Desa',
                category: 'Administrasi Penduduk',
                icon: 'fa-people-arrows',
                url: `${rootPrefix}pages/administrasi-penduduk/buku-mutasi-penduduk-desa.html`
            },
            {
                label: 'Buku Rekapitulasi Jumlah Penduduk',
                category: 'Administrasi Penduduk',
                icon: 'fa-table-list',
                url: `${rootPrefix}pages/administrasi-penduduk/buku-rekapitulasi-jumlah-penduduk.html`
            },
            {
                label: 'Buku Penduduk Sementara',
                category: 'Administrasi Penduduk',
                icon: 'fa-suitcase-rolling',
                url: `${rootPrefix}pages/administrasi-penduduk/buku-penduduk-sementara.html`
            },
            {
                label: 'Buku Kartu Tanda Penduduk dan Buku Kartu Keluarga',
                category: 'Administrasi Penduduk',
                icon: 'fa-id-card',
                url: `${rootPrefix}pages/administrasi-penduduk/buku-ktp-dan-kk.html`
            },
            {
                label: 'Ringkasan Administrasi Umum (9 Buku)',
                category: 'Administrasi Umum',
                icon: 'fa-book-bookmark',
                target: '#sectionAdmUmum',
                url: `${rootPrefix}pages/dashboard.html#sectionAdmUmum`
            },
            {
                label: 'Ringkasan Administrasi Penduduk (5 Buku)',
                category: 'Administrasi Penduduk',
                icon: 'fa-users',
                target: '#sectionAdmPenduduk',
                url: `${rootPrefix}pages/dashboard.html#sectionAdmPenduduk`
            },
            {
                label: 'Aktivitas Administrasi Terbaru',
                category: 'Log Pemerintahan',
                icon: 'fa-clock-rotate-left',
                target: '#sectionAktivitas',
                url: `${rootPrefix}pages/dashboard.html#sectionAktivitas`
            },
            {
                label: 'Informasi Administrasi Desa Tiaro',
                category: 'Profil Desa',
                icon: 'fa-landmark',
                target: '#sectionInfoDesa',
                url: `${rootPrefix}pages/dashboard.html#sectionInfoDesa`
            }
        ];

        // ------------------------------------------------------------------
        // RENDER SEARCH RESULTS
        // ------------------------------------------------------------------

        function renderMatches(query) {
            const q = query.trim().toLowerCase();

            if (!q) {
                $resultsBox
                    .removeClass('show')
                    .empty();

                return;
            }

            const matches = catalog.filter(item => {
                return (
                    item.label.toLowerCase().includes(q) ||
                    item.category.toLowerCase().includes(q)
                );
            });

            if (matches.length === 0) {
                $resultsBox
                    .html(`
                        <div class="px-3 py-2 text-simdes-muted" style="font-size:0.8rem;">
                            Tidak ditemukan hasil untuk
                            "<strong>${escapeHtml(query)}</strong>"
                        </div>
                    `)
                    .addClass('show');

                return;
            }

            const html = matches.map((item, index) => `
                <button
                    type="button"
                    class="search-result-item"
                    data-result-idx="${index}"
                >
                    <i
                        class="fa-solid ${escapeHtml(item.icon)} text-simdes-primary"
                        aria-hidden="true"
                    ></i>

                    <span style="flex:1 1 auto; min-width:0;">
                        <span class="d-block fw-semibold">
                            ${escapeHtml(item.label)}
                        </span>

                        <small class="text-simdes-muted">
                            ${escapeHtml(item.category)}
                        </small>
                    </span>

                    <i
                        class="fa-solid fa-arrow-turn-down fa-rotate-90 text-simdes-muted"
                        style="font-size:0.72rem;"
                        aria-hidden="true"
                    ></i>
                </button>
            `).join('');

            $resultsBox
                .html(html)
                .addClass('show');

            // Simpan hasil pencarian untuk event delegation.
            $resultsBox.data('search-matches', matches);
        }

        // ------------------------------------------------------------------
        // SEARCH INPUT
        // ------------------------------------------------------------------

        $searchInput
            .off(EVENT_NAMESPACE)
            .on(`input${EVENT_NAMESPACE}`, function () {
                renderMatches($(this).val());
            })
            .on(`focus${EVENT_NAMESPACE}`, function () {
                const query = $(this).val();

                if (query.trim()) {
                    renderMatches(query);
                }
            });

        // ------------------------------------------------------------------
        // RESULT CLICK
        // ------------------------------------------------------------------

        $resultsBox
            .off(EVENT_NAMESPACE)
            .on(
                `click${EVENT_NAMESPACE}`,
                '[data-result-idx]',
                function () {
                    const matches = $resultsBox.data('search-matches') || [];

                    const index = Number($(this).attr('data-result-idx'));
                    const item = matches[index];

                    if (!item) {
                        return;
                    }

                    $resultsBox.removeClass('show');
                    $searchInput.val('');

                    if (item.target) {
                        const $target = $(item.target).first();

                        if ($target.length) {
                            $target[0].scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });

                            return;
                        }
                    }

                    if (item.url) {
                        window.location.href = item.url;
                    }
                }
            );

        // ------------------------------------------------------------------
        // CLICK OUTSIDE SEARCH
        // ------------------------------------------------------------------

        $(document)
            .off(`click${EVENT_NAMESPACE}`, closeSearchOutside)
            .on(`click${EVENT_NAMESPACE}`, closeSearchOutside);

        function closeSearchOutside(event) {
            const $target = $(event.target);

            if (
                !$target.closest($searchInput).length &&
                !$target.closest($resultsBox).length
            ) {
                $resultsBox.removeClass('show');
            }
        }

        // ------------------------------------------------------------------
        // KEYBOARD SHORTCUTS
        // ------------------------------------------------------------------

        $(document)
            .off(`keydown${EVENT_NAMESPACE}`, handleSearchKeyboard)
            .on(`keydown${EVENT_NAMESPACE}`, handleSearchKeyboard);

        function handleSearchKeyboard(event) {
            if (event.isComposing) {
                return;
            }

            const $active = $(document.activeElement);
            const tag = document.activeElement?.tagName;

            const isEditing =
                ['INPUT', 'TEXTAREA', 'SELECT'].includes(tag) ||
                Boolean($active.prop('isContentEditable'));

            // Ctrl + K atau Cmd + K
            if (
                (event.ctrlKey || event.metaKey) &&
                event.key.toLowerCase() === 'k'
            ) {
                event.preventDefault();
                $searchInput.trigger('focus');

                return;
            }

            // Slash untuk membuka pencarian.
            if (!isEditing && event.key === '/') {
                event.preventDefault();
                $searchInput.trigger('focus');

                return;
            }

            // Escape untuk menutup pencarian.
            if (
                event.key === 'Escape' &&
                document.activeElement === $searchInput[0]
            ) {
                $resultsBox.removeClass('show');
                $searchInput.trigger('blur');
            }
        }
    }

    // ======================================================================
    // NOTIFICATIONS
    // ======================================================================

    function initNotifications() {
        const $markAllBtn = $('#markAllNotifReadBtn');
        const $unreadBadge = $('#notifCountBadge');
        const $notifDot = $('#topbarNotifDot');

        if (!$markAllBtn.length) {
            return;
        }

        $markAllBtn
            .off(EVENT_NAMESPACE)
            .on(`click${EVENT_NAMESPACE}`, function (event) {
                event.preventDefault();
                event.stopPropagation();

                $('.notif-item.unread').removeClass('unread');

                if ($unreadBadge.length) {
                    $unreadBadge
                        .text('0 Baru')
                        .removeClass()
                        .addClass('badge-simdes badge-simdes-neutral');
                }

                if ($notifDot.length) {
                    $notifDot.addClass('hidden');
                }

                showToast({
                    title: 'Notifikasi Diperbarui',
                    message: 'Seluruh pemberitahuan administrasi telah ditandai dibaca.',
                    variant: 'success'
                });
            });
    }

    // ======================================================================
    // PROFILE & LOGOUT
    // ======================================================================

    function initProfileAndLogoutActions() {
        const $profileModal = $('#userProfileModal');
        const $logoutModal = $('#logoutConfirmModal');
        const $confirmLogoutBtn = $('#confirmLogoutBtn');

        // ------------------------------------------------------------------
        // OPEN PROFILE MODAL
        // ------------------------------------------------------------------

        $(document)
            .off(`click${EVENT_NAMESPACE}`, '[data-action="open-profile-modal"]')
            .on(
                `click${EVENT_NAMESPACE}`,
                '[data-action="open-profile-modal"]',
                function (event) {
                    event.preventDefault();

                    if (
                        $profileModal.length &&
                        window.bootstrap?.Modal
                    ) {
                        window.bootstrap.Modal
                            .getOrCreateInstance($profileModal[0])
                            .show();
                    }
                }
            );

        // ------------------------------------------------------------------
        // OPEN LOGOUT CONFIRMATION
        // ------------------------------------------------------------------

        $(document)
            .off(`click${EVENT_NAMESPACE}`, '[data-action="confirm-logout"]')
            .on(
                `click${EVENT_NAMESPACE}`,
                '[data-action="confirm-logout"]',
                function (event) {
                    event.preventDefault();

                    if (
                        $logoutModal.length &&
                        window.bootstrap?.Modal
                    ) {
                        window.bootstrap.Modal
                            .getOrCreateInstance($logoutModal[0])
                            .show();
                    } else {
                        clearUserSession();

                        window.location.href = `${getRootPrefix()}login.html`;
                    }
                }
            );

        // ------------------------------------------------------------------
        // CONFIRM LOGOUT
        // ------------------------------------------------------------------

        $confirmLogoutBtn
            .off(EVENT_NAMESPACE)
            .on(`click${EVENT_NAMESPACE}`, function () {
                clearUserSession();

                window.location.href = `${getRootPrefix()}login.html`;
            });
    }

    // ======================================================================
    // INITIALIZATION
    // ======================================================================

    function initApp() {
        hydrateUserIdentity();
        initQuickSearch();
        initNotifications();
        initProfileAndLogoutActions();
    }

    // ======================================================================
    // PUBLIC API
    // ======================================================================

    window.SimdesApp = {
        escapeHtml,
        getRootPrefix,
        getUserSession,
        setUserSession,
        clearUserSession,
        hydrateUserIdentity,
        showToast
    };

    // ======================================================================
    // DOCUMENT READY
    // ======================================================================

    $(function () {
        initApp();
    });

})( $, window, document );
