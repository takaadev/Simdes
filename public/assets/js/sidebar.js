/* ==========================================================================
   SIMDES DESA TIARO — Sidebar & Responsive Offcanvas Controller (sidebar.js)
   Manages desktop collapsible sidebar rail, smartphone offcanvas drawer with
   inert background management, tooltips, and official 14-book menu links.
   ========================================================================== */

(() => {
  'use strict';

  const STORAGE_KEY = 'simdes_tiaro_sidebar_collapsed';
  const DESKTOP_BREAKPOINT = 992;

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

  /**
   * Kelompok Menu: Arsip Surat
   * Rencana bertahap fitur administrasi & dokumentasi persuratan desa:
   *   1. Buku Agenda (aktif pada tahap ini)
   *   2. Surat Masuk (rencana tahap berikutnya)
   *   3. Surat Keluar (rencana tahap berikutnya)
   *   4. Klasifikasi Surat (rencana tahap berikutnya)
   *   5. Arsip Dokumen Surat (rencana tahap berikutnya)
   * Hanya item dengan `enabled: true` yang dirender ke DOM agar tidak ada submenu kosong/error.
   */
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

  function ensureArsipSuratGroup(sidebar, badgeCount) {
    let arsipBtn = sidebar.querySelector('[data-sidebar-group="arsip-surat"]');
    let arsipPanel = document.getElementById('sidebarGroupArsipInfo');

    if (!arsipBtn) {
      const umumBtn = sidebar.querySelector('[data-sidebar-group="umum"]');
      const umumLi = umumBtn?.closest('li');
      const menuList = umumLi?.parentElement || sidebar.querySelector('.sidebar-menu:last-of-type');

      if (menuList) {
        const li = document.createElement('li');
        li.innerHTML = `
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
          <div id="sidebarGroupArsipInfo" class="sidebar-group-panel" hidden></div>
        `;
        if (umumLi && typeof umumLi.insertAdjacentElement === 'function') {
          umumLi.insertAdjacentElement('afterend', li);
        } else {
          menuList.appendChild(li);
        }
        arsipBtn = li.querySelector('[data-sidebar-group="arsip-surat"]');
        arsipPanel = document.getElementById('sidebarGroupArsipInfo');
      }
    } else {
      const badgeEl = arsipBtn.querySelector('.sidebar-badge');
      if (badgeEl) badgeEl.textContent = String(badgeCount);
    }

    return { arsipBtn, arsipPanel };
  }

  function hydrateBookSubmenus(sidebar) {
    const rootPrefix = window.SimdesApp ? window.SimdesApp.getRootPrefix() : './';
    const esc = window.SimdesApp ? window.SimdesApp.escapeHtml : (s) => s;
    const currentPath = window.location.pathname;
    const activeSlug = document.body?.dataset?.activeNav || '';

    const buildSubmenuHtml = (items) => `
      <ul class="sidebar-submenu">
        ${items
          .filter((item) => item.enabled !== false)
          .map((item) => {
            const itemKey = item.navKey || item.slug;
            const isActive = activeSlug
              ? activeSlug === itemKey
              : currentPath.endsWith(`/${item.path}`);
            const href = `${rootPrefix}${item.path}`;
            return `
              <li>
                <a href="${esc(href)}"
                   class="sidebar-sublink${isActive ? ' active' : ''}"
                   ${isActive ? 'aria-current="page"' : ''}
                   data-book-ready="${item.ready ? 'true' : 'false'}"
                   data-book-label="${esc(item.label)}">
                  <span class="sidebar-sublink-num">${esc(item.no)}.</span>
                  <span>${esc(item.label)}</span>
                </a>
              </li>
            `;
          })
          .join('')}
      </ul>
    `;

    const enabledArsipItems = MENU_ARSIP_SURAT.filter((item) => item.enabled !== false);
    const { arsipBtn, arsipPanel } = ensureArsipSuratGroup(sidebar, enabledArsipItems.length);

    const umumPanel = document.getElementById('sidebarGroupUmumInfo');
    const pendudukPanel = document.getElementById('sidebarGroupPendudukInfo');
    const umumBtn = sidebar.querySelector('[data-sidebar-group="umum"]');
    const pendudukBtn = sidebar.querySelector('[data-sidebar-group="penduduk"]');

    if (umumPanel) {
      umumPanel.classList.add('is-submenu');
      umumPanel.innerHTML = buildSubmenuHtml(MENU_ADM_UMUM);
      const hasActiveUmum = Boolean(umumPanel.querySelector('.sidebar-sublink.active'));
      if (hasActiveUmum) {
        umumPanel.hidden = false;
        umumBtn?.classList.add('active');
        umumBtn?.setAttribute('aria-expanded', 'true');
      }
    }

    if (arsipPanel) {
      arsipPanel.classList.add('is-submenu');
      arsipPanel.innerHTML = buildSubmenuHtml(enabledArsipItems);
      const hasActiveArsip = Boolean(arsipPanel.querySelector('.sidebar-sublink.active'));
      if (hasActiveArsip) {
        arsipPanel.hidden = false;
        arsipBtn?.classList.add('active');
        arsipBtn?.setAttribute('aria-expanded', 'true');
      }
    }

    if (pendudukPanel) {
      pendudukPanel.classList.add('is-submenu');
      pendudukPanel.innerHTML = buildSubmenuHtml(MENU_ADM_PENDUDUK);
      const hasActivePenduduk = Boolean(pendudukPanel.querySelector('.sidebar-sublink.active'));
      if (hasActivePenduduk) {
        pendudukPanel.hidden = false;
        pendudukBtn?.classList.add('active');
        pendudukBtn?.setAttribute('aria-expanded', 'true');
      }
    }

    sidebar.querySelectorAll('[data-book-ready="false"]').forEach((link) => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        const label = link.getAttribute('data-book-label') || 'Buku Administrasi';
        window.SimdesApp?.showToast({
          title: label,
          message: 'Halaman buku ini menunggu instruksi struktur kolom tabel berikutnya.',
          variant: 'primary'
        });
      });
    });
  }

  function initSidebar() {
    const sidebar = document.getElementById('appSidebar');
    const mainArea = document.getElementById('appMainWrapper');
    const toggleBtn = document.getElementById('sidebarToggleBtn');
    const closeMobileBtn = document.getElementById('sidebarCloseMobileBtn');
    const backdrop = document.getElementById('sidebarBackdrop');

    if (!sidebar || !toggleBtn) return;

    hydrateBookSubmenus(sidebar);

    let tooltipInstances = [];

    const isDesktop = () => window.innerWidth >= DESKTOP_BREAKPOINT;

    const refreshCollapsedTooltips = () => {
      tooltipInstances.forEach((instance) => instance.dispose());
      tooltipInstances = [];

      if (!window.bootstrap?.Tooltip) return;
      if (isDesktop() && document.body.classList.contains('sidebar-collapsed')) {
        sidebar.querySelectorAll('[data-sidebar-tooltip]').forEach((el) => {
          const title = el.getAttribute('data-sidebar-tooltip');
          if (title) {
            tooltipInstances.push(
              new window.bootstrap.Tooltip(el, {
                title,
                placement: 'right',
                trigger: 'hover focus'
              })
            );
          }
        });
      }
    };

    const setDesktopCollapsed = (collapsed, save = true) => {
      document.body.classList.toggle('sidebar-collapsed', collapsed);
      toggleBtn.setAttribute('aria-expanded', String(!collapsed));
      toggleBtn.setAttribute(
        'aria-label',
        collapsed ? 'Perluas panel navigasi samping' : 'Ciutkan panel navigasi samping'
      );

      if (save) {
        try {
          localStorage.setItem(STORAGE_KEY, collapsed ? '1' : '0');
        } catch (_) {
          // Ignore storage error
        }
      }
      refreshCollapsedTooltips();
    };

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

    try {
      const saved = localStorage.getItem(STORAGE_KEY);
      if (isDesktop() && saved === '1') {
        setDesktopCollapsed(true, false);
      } else {
        toggleBtn.setAttribute('aria-expanded', isDesktop() ? 'true' : 'false');
      }
    } catch (_) {
      // Ignore storage error
    }

    toggleBtn.addEventListener('click', () => {
      if (isDesktop()) {
        const nextCollapsed = !document.body.classList.contains('sidebar-collapsed');
        setDesktopCollapsed(nextCollapsed, true);
      } else if (sidebar.classList.contains('offcanvas-open')) {
        closeMobileOffcanvas(true);
      } else {
        openMobileOffcanvas();
      }
    });

    closeMobileBtn?.addEventListener('click', () => closeMobileOffcanvas(true));
    backdrop?.addEventListener('click', () => closeMobileOffcanvas(true));

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && sidebar.classList.contains('offcanvas-open')) {
        closeMobileOffcanvas(true);
      }
    });

    window.addEventListener('resize', () => {
      if (isDesktop()) {
        closeMobileOffcanvas(false);
        refreshCollapsedTooltips();
      } else {
        tooltipInstances.forEach((instance) => instance.dispose());
        tooltipInstances = [];
      }
    });

    sidebar.querySelectorAll('[data-sidebar-group]').forEach((groupBtn) => {
      groupBtn.addEventListener('click', () => {
        if (isDesktop() && document.body.classList.contains('sidebar-collapsed')) {
          setDesktopCollapsed(false, true);
        }

        const panelId = groupBtn.getAttribute('aria-controls');
        const panel = panelId ? document.getElementById(panelId) : null;
        if (!panel) return;

        const isExpanded = groupBtn.getAttribute('aria-expanded') === 'true';
        groupBtn.setAttribute('aria-expanded', String(!isExpanded));
        panel.hidden = isExpanded;
      });
    });
  }

  document.addEventListener('DOMContentLoaded', initSidebar);

  window.SimdesSidebar = {
    init: initSidebar
  };
})();
