/* ==========================================================================
   SIMDES DESA TIARO — Core Application Controller (app.js)
   Handles session dummy state, user profile hydration, global search,
   notifications dropdown, modals, and accessible toast alerts.
   ========================================================================== */

(() => {
  'use strict';

  const SESSION_KEY = 'simdes_tiaro_user_session';

  const DEFAULT_USER = {
    username: 'sekdes.tiaro',
    fullName: 'Budi Santoso, S.AP',
    role: 'Sekretaris Desa',
    nipd: '19880412 201503 1 002',
    unit: 'Sekretariat Pemerintah Desa Tiaro',
    initials: 'BS',
    lastLogin: 'Hari ini, 08:15 WITA'
  };

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

  function escapeHtml(value) {
    const str = String(value ?? '');
    return str
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  function getRootPrefix() {
    return document.body?.dataset?.rootPrefix || './';
  }

  function computeInitials(name) {
    const parts = String(name || 'Aparat Desa')
      .replace(/,.*/, '')
      .trim()
      .split(/\s+/)
      .filter(Boolean);
    if (parts.length === 0) return 'AD';
    if (parts.length === 1) return parts[0].slice(0, 2).toUpperCase();
    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
  }

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

  function getUserSession() {
    try {
      const raw = sessionStorage.getItem(SESSION_KEY) || localStorage.getItem(SESSION_KEY);
      if (raw) {
        const parsed = JSON.parse(raw);
        if (parsed && parsed.fullName) return parsed;
      }
    } catch (_) {
      // Ignore storage access errors in restricted modes
    }
    return { ...DEFAULT_USER };
  }

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
    } catch (_) {
      // Fallback gracefully if storage is unavailable
    }
    return profile;
  }

  function clearUserSession() {
    try {
      sessionStorage.removeItem(SESSION_KEY);
      localStorage.removeItem(SESSION_KEY);
    } catch (_) {
      // Ignore storage errors
    }
  }

  function hydrateUserIdentity() {
    const user = getUserSession();

    document.querySelectorAll('[data-user-fullname]').forEach((el) => {
      el.textContent = user.fullName;
    });
    document.querySelectorAll('[data-user-role]').forEach((el) => {
      el.textContent = user.role;
    });
    document.querySelectorAll('[data-user-initials]').forEach((el) => {
      el.textContent = user.initials;
    });
    document.querySelectorAll('[data-user-nipd]').forEach((el) => {
      el.textContent = user.nipd;
    });
    document.querySelectorAll('[data-user-unit]').forEach((el) => {
      el.textContent = user.unit;
    });
    document.querySelectorAll('[data-user-username]').forEach((el) => {
      el.textContent = user.username;
    });
  }

  function ensureToastContainer() {
    let container = document.getElementById('simdesToastContainer');
    if (!container) {
      container = document.createElement('div');
      container.id = 'simdesToastContainer';
      container.className = 'simdes-toast-container';
      container.setAttribute('aria-live', 'polite');
      container.setAttribute('aria-atomic', 'false');
      document.body.appendChild(container);
    }
    return container;
  }

  function showToast({ title = 'Informasi Sistem', message = '', variant = 'primary', duration = 4200 } = {}) {
    const container = ensureToastContainer();
    const toast = document.createElement('div');
    toast.className = 'simdes-toast';
    toast.setAttribute('role', 'status');

    const variantMap = {
      primary: { color: 'var(--simdes-primary)', icon: 'fa-circle-info' },
      success: { color: 'var(--simdes-success)', icon: 'fa-circle-check' },
      warning: { color: 'var(--simdes-warning)', icon: 'fa-triangle-exclamation' },
      danger: { color: 'var(--simdes-danger)', icon: 'fa-circle-exclamation' }
    };
    const cfg = variantMap[variant] || variantMap.primary;
    toast.style.borderInlineStartColor = cfg.color;

    toast.innerHTML = `
      <div style="color:${cfg.color}; font-size:1.05rem; margin-top:2px;">
        <i class="fa-solid ${cfg.icon}" aria-hidden="true"></i>
      </div>
      <div style="flex:1 1 auto; min-width:0;">
        <div style="font-weight:700; font-size:0.84rem; color:var(--simdes-text);">${escapeHtml(title)}</div>
        <div style="font-size:0.79rem; color:var(--simdes-text-muted); margin-top:2px;">${escapeHtml(message)}</div>
      </div>
      <button type="button" class="btn-close" style="font-size:0.7rem;" aria-label="Tutup notifikasi"></button>
    `;

    const closeBtn = toast.querySelector('.btn-close');
    const removeToast = () => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(6px)';
      toast.style.transition = 'opacity 160ms ease, transform 160ms ease';
      window.setTimeout(() => toast.remove(), 170);
    };

    closeBtn.addEventListener('click', removeToast);
    container.appendChild(toast);

    if (duration > 0) {
      window.setTimeout(removeToast, duration);
    }
  }

  function initQuickSearch() {
    const searchInput = document.getElementById('globalSearchInput');
    const resultsBox = document.getElementById('globalSearchResults');
    if (!searchInput || !resultsBox) return;

    const rootPrefix = getRootPrefix();
    const catalog = [
      { label: 'Dashboard Utama', category: 'Ringkasan Desa', icon: 'fa-chart-pie', target: '#mainContent', url: `${rootPrefix}pages/dashboard.html` },
      { label: 'Buku Peraturan di Desa', category: 'Administrasi Umum', icon: 'fa-scale-balanced', url: `${rootPrefix}pages/administrasi-umum/buku-peraturan-di-desa.html` },
      { label: 'Buku Keputusan Kepala Desa', category: 'Administrasi Umum', icon: 'fa-file-signature', url: `${rootPrefix}pages/administrasi-umum/buku-keputusan-kepala-desa.html` },
      { label: 'Buku Inventaris dan Kekayaan Desa', category: 'Administrasi Umum', icon: 'fa-boxes-stacked', url: `${rootPrefix}pages/administrasi-umum/buku-inventaris-dan-kekayaan-desa.html` },
      { label: 'Buku Aparat Pemerintah Desa', category: 'Administrasi Umum', icon: 'fa-user-tie', url: `${rootPrefix}pages/administrasi-umum/buku-aparat-pemerintah-desa.html` },
      { label: 'Buku Tanah Kas Desa', category: 'Administrasi Umum', icon: 'fa-map-location-dot', url: `${rootPrefix}pages/administrasi-umum/buku-tanah-kas-desa.html` },
      { label: 'Buku Tanah di Desa', category: 'Administrasi Umum', icon: 'fa-layer-group', url: `${rootPrefix}pages/administrasi-umum/buku-tanah-di-desa.html` },
      { label: 'Buku Ekspedisi', category: 'Administrasi Umum', icon: 'fa-paper-plane', url: `${rootPrefix}pages/administrasi-umum/buku-ekspedisi.html` },
      { label: 'Buku Lembaran Desa dan Berita Desa', category: 'Administrasi Umum', icon: 'fa-newspaper', url: `${rootPrefix}pages/administrasi-umum/buku-lembaran-desa-dan-berita-desa.html` },
      { label: 'Buku Agenda', category: 'Arsip Surat', icon: 'fa-folder-open', url: `${rootPrefix}pages/arsip-surat/buku-agenda.html` },
      { label: 'Buku Induk Penduduk', category: 'Administrasi Penduduk', icon: 'fa-address-book', url: `${rootPrefix}pages/administrasi-penduduk/buku-induk-penduduk.html` },
      { label: 'Buku Mutasi Penduduk Desa', category: 'Administrasi Penduduk', icon: 'fa-people-arrows', url: `${rootPrefix}pages/administrasi-penduduk/buku-mutasi-penduduk-desa.html` },
      { label: 'Buku Rekapitulasi Jumlah Penduduk', category: 'Administrasi Penduduk', icon: 'fa-table-list', url: `${rootPrefix}pages/administrasi-penduduk/buku-rekapitulasi-jumlah-penduduk.html` },
      { label: 'Buku Penduduk Sementara', category: 'Administrasi Penduduk', icon: 'fa-suitcase-rolling', url: `${rootPrefix}pages/administrasi-penduduk/buku-penduduk-sementara.html` },
      { label: 'Buku Kartu Tanda Penduduk dan Buku Kartu Keluarga', category: 'Administrasi Penduduk', icon: 'fa-id-card', url: `${rootPrefix}pages/administrasi-penduduk/buku-ktp-dan-kk.html` },
      { label: 'Ringkasan Administrasi Umum (9 Buku)', category: 'Administrasi Umum', icon: 'fa-book-bookmark', target: '#sectionAdmUmum', url: `${rootPrefix}pages/dashboard.html#sectionAdmUmum` },
      { label: 'Ringkasan Administrasi Penduduk (5 Buku)', category: 'Administrasi Penduduk', icon: 'fa-users', target: '#sectionAdmPenduduk', url: `${rootPrefix}pages/dashboard.html#sectionAdmPenduduk` },
      { label: 'Aktivitas Administrasi Terbaru', category: 'Log Pemerintahan', icon: 'fa-clock-rotate-left', target: '#sectionAktivitas', url: `${rootPrefix}pages/dashboard.html#sectionAktivitas` },
      { label: 'Informasi Administrasi Desa Tiaro', category: 'Profil Desa', icon: 'fa-landmark', target: '#sectionInfoDesa', url: `${rootPrefix}pages/dashboard.html#sectionInfoDesa` }
    ];

    const renderMatches = (query) => {
      const q = query.trim().toLowerCase();
      if (!q) {
        resultsBox.classList.remove('show');
        resultsBox.innerHTML = '';
        return;
      }

      const matches = catalog.filter(
        (item) => item.label.toLowerCase().includes(q) || item.category.toLowerCase().includes(q)
      );

      if (matches.length === 0) {
        resultsBox.innerHTML = `
          <div class="px-3 py-2 text-simdes-muted" style="font-size:0.8rem;">
            Tidak ditemukan hasil untuk "<strong>${escapeHtml(query)}</strong>"
          </div>
        `;
        resultsBox.classList.add('show');
        return;
      }

      resultsBox.innerHTML = matches
        .map(
          (item, idx) => `
          <button type="button" class="search-result-item" data-result-idx="${idx}">
            <i class="fa-solid ${item.icon} text-simdes-primary" aria-hidden="true"></i>
            <span style="flex:1 1 auto; min-width:0;">
              <span class="d-block fw-semibold">${escapeHtml(item.label)}</span>
              <small class="text-simdes-muted">${escapeHtml(item.category)}</small>
            </span>
            <i class="fa-solid fa-arrow-turn-down fa-rotate-90 text-simdes-muted" style="font-size:0.72rem;" aria-hidden="true"></i>
          </button>
        `
        )
        .join('');

      resultsBox.querySelectorAll('[data-result-idx]').forEach((btn) => {
        btn.addEventListener('click', () => {
          const item = matches[Number(btn.dataset.resultIdx)];
          resultsBox.classList.remove('show');
          searchInput.value = '';
          if (item.target) {
            const el = document.querySelector(item.target);
            if (el) {
              el.scrollIntoView({ behavior: 'smooth', block: 'start' });
              return;
            }
          }
          if (item.url) {
            window.location.href = item.url;
          }
        });
      });

      resultsBox.classList.add('show');
    };

    searchInput.addEventListener('input', () => renderMatches(searchInput.value));
    searchInput.addEventListener('focus', () => {
      if (searchInput.value.trim()) renderMatches(searchInput.value);
    });

    document.addEventListener('click', (e) => {
      if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
        resultsBox.classList.remove('show');
      }
    });

    // Keyboard shortcut: Ctrl+K / Cmd+K or '/' when outside inputs
    document.addEventListener('keydown', (e) => {
      if (e.isComposing) return;
      const tag = document.activeElement?.tagName;
      const isEditing = tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT' || document.activeElement?.isContentEditable;

      if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
        e.preventDefault();
        searchInput.focus();
      } else if (!isEditing && e.key === '/') {
        e.preventDefault();
        searchInput.focus();
      } else if (e.key === 'Escape' && document.activeElement === searchInput) {
        resultsBox.classList.remove('show');
        searchInput.blur();
      }
    });
  }

  function initNotifications() {
    const markAllBtn = document.getElementById('markAllNotifReadBtn');
    const unreadBadge = document.getElementById('notifCountBadge');
    const notifDot = document.getElementById('topbarNotifDot');

    if (!markAllBtn) return;

    markAllBtn.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      document.querySelectorAll('.notif-item.unread').forEach((item) => {
        item.classList.remove('unread');
      });
      if (unreadBadge) {
        unreadBadge.textContent = '0 Baru';
        unreadBadge.className = 'badge-simdes badge-simdes-neutral';
      }
      if (notifDot) {
        notifDot.classList.add('hidden');
      }
      showToast({
        title: 'Notifikasi Diperbarui',
        message: 'Seluruh pemberitahuan administrasi telah ditandai dibaca.',
        variant: 'success'
      });
    });
  }

  function initProfileAndLogoutActions() {
    const profileModalEl = document.getElementById('userProfileModal');
    const logoutModalEl = document.getElementById('logoutConfirmModal');
    const confirmLogoutBtn = document.getElementById('confirmLogoutBtn');

    document.querySelectorAll('[data-action="open-profile-modal"]').forEach((trigger) => {
      trigger.addEventListener('click', (e) => {
        e.preventDefault();
        if (profileModalEl && window.bootstrap?.Modal) {
          window.bootstrap.Modal.getOrCreateInstance(profileModalEl).show();
        }
      });
    });

    document.querySelectorAll('[data-action="confirm-logout"]').forEach((trigger) => {
      trigger.addEventListener('click', (e) => {
        e.preventDefault();
        if (logoutModalEl && window.bootstrap?.Modal) {
          window.bootstrap.Modal.getOrCreateInstance(logoutModalEl).show();
        } else {
          clearUserSession();
          window.location.href = `${getRootPrefix()}login.html`;
        }
      });
    });

    if (confirmLogoutBtn) {
      confirmLogoutBtn.addEventListener('click', () => {
        clearUserSession();
        window.location.href = `${getRootPrefix()}login.html`;
      });
    }
  }

  document.addEventListener('DOMContentLoaded', () => {
    hydrateUserIdentity();
    initQuickSearch();
    initNotifications();
    initProfileAndLogoutActions();
  });

  window.SimdesApp = {
    escapeHtml,
    getRootPrefix,
    getUserSession,
    setUserSession,
    clearUserSession,
    hydrateUserIdentity,
    showToast
  };
})();

