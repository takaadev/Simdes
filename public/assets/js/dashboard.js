/* ==========================================================================
   SIMDES DESA TIARO — Dashboard Interactive Controller (dashboard.js)
   Renders dynamic Indonesian greeting, administrative register summaries,
   custom SVG visualizations (Trend Bar Chart & Dusun Donut Chart), and
   filterable recent administrative activity feed using dummy data.
   ========================================================================== */

(() => {
  'use strict';

  const MONTHLY_TREND_DATA = [
    { month: 'Jan', fullMonth: 'Januari 2026', umum: 18, penduduk: 24 },
    { month: 'Feb', fullMonth: 'Februari 2026', umum: 22, penduduk: 19 },
    { month: 'Mar', fullMonth: 'Maret 2026', umum: 31, penduduk: 27 },
    { month: 'Apr', fullMonth: 'April 2026', umum: 19, penduduk: 22 },
    { month: 'Mei', fullMonth: 'Mei 2026', umum: 26, penduduk: 30 },
    { month: 'Jun', fullMonth: 'Juni 2026', umum: 24, penduduk: 26 },
    { month: 'Jul', fullMonth: 'Juli 2026', umum: 29, penduduk: 34 },
    { month: 'Agu', fullMonth: 'Agustus 2026', umum: 35, penduduk: 28 },
    { month: 'Sep', fullMonth: 'September 2026', umum: 27, penduduk: 31 },
    { month: 'Okt', fullMonth: 'Oktober 2026', umum: 33, penduduk: 37 }
  ];

  const DUSUN_POPULATION_DATA = [
    { id: 'dusun-1', name: 'Dusun I Krajan', jiwa: 980, kk: 298, color: '#174C3A' },
    { id: 'dusun-2', name: 'Dusun II Mekar Sari', jiwa: 912, kk: 274, color: '#477568' },
    { id: 'dusun-3', name: 'Dusun III Lembah Hijau', jiwa: 845, kk: 251, color: '#D9AE52' },
    { id: 'dusun-4', name: 'Dusun IV Tiaro Jaya', jiwa: 745, kk: 223, color: '#10382D' }
  ];

  const BUKU_ADM_UMUM = [
    { no: '01', name: 'Buku Peraturan di Desa', count: 14, unit: 'Perdes', updated: '28 Okt 2026', status: 'Tertib', badge: 'success' },
    { no: '02', name: 'Buku Keputusan Kepala Desa', count: 38, unit: 'SK Kades', updated: '30 Okt 2026', status: 'Tertib', badge: 'success' },
    { no: '03', name: 'Buku Inventaris dan Kekayaan Desa', count: 46, unit: 'Aset', updated: '29 Okt 2026', status: 'Tertib', badge: 'success' },
    { no: '04', name: 'Buku Aparat Pemerintah Desa', count: 16, unit: 'Aparatur', updated: '15 Okt 2026', status: 'Lengkap', badge: 'primary' },
    { no: '05', name: 'Buku Tanah Kas Desa', count: 18, unit: 'Bidang', updated: '20 Okt 2026', status: 'Terverifikasi', badge: 'success' },
    { no: '06', name: 'Buku Tanah di Desa', count: 312, unit: 'Persil', updated: '25 Okt 2026', status: 'Pembaruan', badge: 'warning' },
    { no: '07', name: 'Buku Agenda', count: 124, unit: 'Surat', updated: 'Hari ini', status: 'Aktif', badge: 'primary' },
    { no: '08', name: 'Buku Ekspedisi', count: 86, unit: 'Pengiriman', updated: '30 Okt 2026', status: 'Tertib', badge: 'success' },
    { no: '09', name: 'Buku Lembaran Desa dan Berita Desa', count: 19, unit: 'Terbitan', updated: '28 Okt 2026', status: 'Tertib', badge: 'success' }
  ];

  const BUKU_ADM_PENDUDUK = [
    { no: '01', name: 'Buku Induk Penduduk', count: '3.482', unit: 'Jiwa', updated: '31 Okt 2026', status: 'Sinkron', badge: 'success' },
    { no: '02', name: 'Buku Mutasi Penduduk Desa', count: '23', unit: 'Peristiwa (Okt)', updated: 'Hari ini', status: 'Aktif', badge: 'primary' },
    { no: '03', name: 'Buku Rekapitulasi Jumlah Penduduk', count: '10', unit: 'Periode Bulan', updated: '30 Okt 2026', status: 'Tertib', badge: 'success' },
    { no: '04', name: 'Buku Penduduk Sementara', count: '19', unit: 'Pendatang Aktif', updated: '27 Okt 2026', status: 'Terpantau', badge: 'accent' },
    { no: '05', name: 'Buku Kartu Tanda Penduduk dan Buku Kartu Keluarga', count: '1.046', unit: 'KK Terdaftar', updated: '29 Okt 2026', status: 'Tertib', badge: 'success' }
  ];

  const RECENT_ACTIVITIES = [
    {
      category: 'umum',
      title: 'Registrasi surat masuk undangan Musrenbang Kecamatan pada Buku Agenda',
      actor: 'Siti Rahmawati, A.Md (Kaur TU & Umum)',
      time: '25 menit yang lalu',
      tag: 'Adm. Umum',
      icon: 'fa-envelope-open-text',
      iconClass: 'stat-icon-primary'
    },
    {
      category: 'penduduk',
      title: 'Pencatatan laporan kelahiran warga Dusun II Mekar Sari pada Buku Mutasi Penduduk Desa',
      actor: 'La Ode Muh. Fajar, S.IP (Kasi Pemerintahan)',
      time: '2 jam yang lalu',
      tag: 'Adm. Penduduk',
      icon: 'fa-baby',
      iconClass: 'stat-icon-secondary'
    },
    {
      category: 'umum',
      title: 'Pengundangan Peraturan Desa Nomor 04 Tahun 2026 pada Buku Lembaran Desa dan Berita Desa',
      actor: 'Budi Santoso, S.AP (Sekretaris Desa)',
      time: 'Kemarin, 15:40 WITA',
      tag: 'Adm. Umum',
      icon: 'fa-scale-balanced',
      iconClass: 'stat-icon-accent'
    },
    {
      category: 'penduduk',
      title: 'Pembaruan data anggota keluarga & perekaman KTP-el pemula pada Buku KTP dan Buku KK',
      actor: 'Operator Layanan Kependudukan',
      time: '29 Okt 2026, 13:15 WITA',
      tag: 'Adm. Penduduk',
      icon: 'fa-id-card',
      iconClass: 'stat-icon-info'
    },
    {
      category: 'umum',
      title: 'Verifikasi fisik 2 unit perangkat layanan pada Buku Inventaris dan Kekayaan Desa',
      actor: 'Kaur Keuangan & Aset Desa',
      time: '28 Okt 2026, 10:20 WITA',
      tag: 'Adm. Umum',
      icon: 'fa-boxes-stacked',
      iconClass: 'stat-icon-primary'
    }
  ];

  function initGreetingAndDate() {
    const greetingEl = document.getElementById('dashboardGreetingTitle');
    const dateEl = document.getElementById('dashboardCurrentDate');
    const now = new Date();
    const hour = now.getHours();

    let salutation = 'Selamat Malam';
    if (hour >= 4 && hour < 11) salutation = 'Selamat Pagi';
    else if (hour >= 11 && hour < 15) salutation = 'Selamat Siang';
    else if (hour >= 15 && hour < 18) salutation = 'Selamat Sore';

    const user = window.SimdesApp?.getUserSession();
    if (greetingEl && user) {
      greetingEl.textContent = `${salutation}, ${user.fullName}`;
    }

    if (dateEl) {
      try {
        const formatted = new Intl.DateTimeFormat('id-ID', {
          weekday: 'long',
          day: 'numeric',
          month: 'long',
          year: 'numeric'
        }).format(now);
        dateEl.textContent = formatted;
      } catch (_) {
        dateEl.textContent = 'Sabtu, 31 Oktober 2026';
      }
    }
  }

  function renderTrendChart(period = '6m') {
    const container = document.getElementById('adminTrendChart');
    const tooltip = document.getElementById('trendChartTooltip');
    if (!container) return;

    const slice = period === '6m' ? MONTHLY_TREND_DATA.slice(-6) : MONTHLY_TREND_DATA;
    const width = 680;
    const height = 255;
    const padLeft = 42;
    const padRight = 18;
    const padTop = 18;
    const padBottom = 38;
    const plotW = width - padLeft - padRight;
    const plotH = height - padTop - padBottom;

    const maxVal = Math.max(...slice.flatMap((d) => [d.umum, d.penduduk]), 40);
    const niceMax = Math.ceil(maxVal / 10) * 10;
    const yTicks = [0, niceMax * 0.25, niceMax * 0.5, niceMax * 0.75, niceMax].map(Math.round);

    const groupWidth = plotW / slice.length;
    const barWidth = Math.min(18, groupWidth * 0.28);
    const barGap = 5;

    const gridLines = yTicks
      .map((val) => {
        const y = padTop + plotH - (val / niceMax) * plotH;
        return `
          <line x1="${padLeft}" y1="${y}" x2="${width - padRight}" y2="${y}"
                stroke="#E4E9E3" stroke-dasharray="${val === 0 ? '0' : '4 4'}" stroke-width="1" />
          <text x="${padLeft - 10}" y="${y + 4}" text-anchor="end"
                fill="#7B8E85" font-size="11" font-weight="600">${val}</text>
        `;
      })
      .join('');

    const barGroups = slice
      .map((item, idx) => {
        const centerX = padLeft + idx * groupWidth + groupWidth / 2;
        const hUmum = (item.umum / niceMax) * plotH;
        const hPenduduk = (item.penduduk / niceMax) * plotH;
        const yUmum = padTop + plotH - hUmum;
        const yPenduduk = padTop + plotH - hPenduduk;
        const xUmum = centerX - barWidth - barGap / 2;
        const xPenduduk = centerX + barGap / 2;

        return `
          <g class="svg-bar-group" tabindex="0"
             role="img"
             aria-label="${item.fullMonth}: Administrasi Umum ${item.umum} dokumen, Administrasi Penduduk ${item.penduduk} layanan"
             data-month="${item.fullMonth}"
             data-umum="${item.umum}"
             data-penduduk="${item.penduduk}"
             data-cx="${centerX}"
             data-top="${Math.min(yUmum, yPenduduk)}">
            <rect x="${centerX - groupWidth * 0.42}" y="${padTop}" width="${groupWidth * 0.84}" height="${plotH}"
                  rx="6" fill="transparent" class="hover-band" />
            <rect class="svg-bar-rect" x="${xUmum}" y="${yUmum}" width="${barWidth}" height="${hUmum}"
                  rx="4" fill="#174C3A" />
            <rect class="svg-bar-rect" x="${xPenduduk}" y="${yPenduduk}" width="${barWidth}" height="${hPenduduk}"
                  rx="4" fill="#D9AE52" />
            <text x="${centerX}" y="${height - 12}" text-anchor="middle"
                  fill="#5A6E65" font-size="11.5" font-weight="600">${item.month}</text>
          </g>
        `;
      })
      .join('');

    container.innerHTML = `
      <svg viewBox="0 0 ${width} ${height}" width="100%" height="255" role="region" aria-label="Grafik tren pencatatan administrasi desa">
        ${gridLines}
        ${barGroups}
      </svg>
    `;

    // Interactive hover & keyboard focus tooltip
    const svgEl = container.querySelector('svg');
    container.querySelectorAll('.svg-bar-group').forEach((group) => {
      const band = group.querySelector('.hover-band');

      const showTip = () => {
        if (band) band.setAttribute('fill', 'rgba(23, 76, 58, 0.05)');
        if (!tooltip || !svgEl) return;

        const cx = Number(group.dataset.cx);
        const top = Number(group.dataset.top);
        const rect = svgEl.getBoundingClientRect();
        const scaleX = rect.width / width;
        const scaleY = rect.height / height;

        tooltip.innerHTML = `
          <div class="fw-bold mb-1" style="color:var(--simdes-accent);">${group.dataset.month}</div>
          <div>Adm. Umum: <strong>${group.dataset.umum} entri</strong></div>
          <div>Adm. Penduduk: <strong>${group.dataset.penduduk} peristiwa</strong></div>
        `;
        tooltip.style.left = `${cx * scaleX}px`;
        tooltip.style.top = `${Math.max(16, top * scaleY)}px`;
        tooltip.classList.add('visible');
      };

      const hideTip = () => {
        if (band) band.setAttribute('fill', 'transparent');
        tooltip?.classList.remove('visible');
      };

      group.addEventListener('mouseenter', showTip);
      group.addEventListener('mouseleave', hideTip);
      group.addEventListener('focus', showTip);
      group.addEventListener('blur', hideTip);
    });
  }

  function renderDusunDonut() {
    const svgBox = document.getElementById('dusunDonutSvgWrap');
    const listBox = document.getElementById('dusunBreakdownList');
    const centerNum = document.getElementById('donutCenterNumber');
    const centerCap = document.getElementById('donutCenterCaption');

    if (!svgBox || !listBox) return;

    const totalJiwa = DUSUN_POPULATION_DATA.reduce((acc, d) => acc + d.jiwa, 0);
    const radius = 72;
    const circumference = 2 * Math.PI * radius;
    let cumulativeFraction = 0;

    const slicesSvg = DUSUN_POPULATION_DATA.map((d, idx) => {
      const fraction = d.jiwa / totalJiwa;
      const dashLen = Math.max(0, fraction * circumference - 3);
      const dashOffset = -cumulativeFraction * circumference;
      cumulativeFraction += fraction;

      return `
        <circle class="donut-slice"
                data-dusun-idx="${idx}"
                cx="98" cy="98" r="${radius}"
                fill="transparent"
                stroke="${d.color}"
                stroke-width="22"
                stroke-dasharray="${dashLen} ${circumference}"
                stroke-dashoffset="${dashOffset}"
                transform="rotate(-90 98 98)" />
      `;
    }).join('');

    svgBox.innerHTML = `
      <svg viewBox="0 0 196 196" width="196" height="196" aria-label="Proporsi penduduk per dusun">
        <circle cx="98" cy="98" r="${radius}" fill="transparent" stroke="#ECEFEA" stroke-width="22" />
        ${slicesSvg}
      </svg>
    `;

    listBox.innerHTML = DUSUN_POPULATION_DATA.map((d, idx) => {
      const pct = ((d.jiwa / totalJiwa) * 100).toFixed(1).replace('.', ',');
      return `
        <div class="dusun-row" data-dusun-row="${idx}">
          <div class="d-flex align-items-center gap-2 min-w-0">
            <span style="width:10px; height:10px; border-radius:50%; background:${d.color}; flex-shrink:0;"></span>
            <div class="text-truncate">
              <div class="fw-semibold" style="font-size:0.82rem; color:var(--simdes-text);">${d.name}</div>
              <div style="font-size:0.71rem; color:var(--simdes-text-muted);">${d.kk} KK terdaftar</div>
            </div>
          </div>
          <div class="text-end tabular-nums">
            <span class="fw-bold" style="font-size:0.84rem; color:var(--simdes-deep-green);">${d.jiwa.toLocaleString('id-ID')}</span>
            <span class="badge-simdes badge-simdes-neutral ms-1">${pct}%</span>
          </div>
        </div>
      `;
    }).join('');

    const highlightDusun = (idx) => {
      const item = DUSUN_POPULATION_DATA[idx];
      if (!item) return;
      const pct = ((item.jiwa / totalJiwa) * 100).toFixed(1).replace('.', ',');
      if (centerNum) centerNum.textContent = item.jiwa.toLocaleString('id-ID');
      if (centerCap) centerCap.textContent = `${item.name.replace('Dusun ', 'Dsn. ')} (${pct}%)`;

      listBox.querySelectorAll('.dusun-row').forEach((row, rIdx) => {
        row.classList.toggle('active-dusun', rIdx === idx);
      });
      svgBox.querySelectorAll('.donut-slice').forEach((slice, sIdx) => {
        slice.style.opacity = sIdx === idx ? '1' : '0.45';
      });
    };

    const resetDusun = () => {
      if (centerNum) centerNum.textContent = totalJiwa.toLocaleString('id-ID');
      if (centerCap) centerCap.textContent = 'Total Jiwa (4 Dusun)';
      listBox.querySelectorAll('.dusun-row').forEach((row) => row.classList.remove('active-dusun'));
      svgBox.querySelectorAll('.donut-slice').forEach((slice) => {
        slice.style.opacity = '1';
      });
    };

    svgBox.querySelectorAll('.donut-slice').forEach((slice) => {
      const idx = Number(slice.dataset.dusunIdx);
      slice.addEventListener('mouseenter', () => highlightDusun(idx));
      slice.addEventListener('mouseleave', resetDusun);
    });

    listBox.querySelectorAll('.dusun-row').forEach((row) => {
      const idx = Number(row.dataset.dusunRow);
      row.addEventListener('mouseenter', () => highlightDusun(idx));
      row.addEventListener('mouseleave', resetDusun);
    });
  }

  function renderBookRegisterSummaries() {
    const umumEl = document.getElementById('summaryAdmUmumList');
    const pendudukEl = document.getElementById('summaryAdmPendudukList');
    const esc = window.SimdesApp ? window.SimdesApp.escapeHtml : (s) => s;

    if (umumEl) {
      umumEl.innerHTML = BUKU_ADM_UMUM.map(
        (b) => `
        <div class="register-row">
          <span class="register-num">${esc(b.no)}</span>
          <div class="register-info">
            <p class="register-name" title="${esc(b.name)}">${esc(b.name)}</p>
            <span class="register-updated">Pembaruan: ${esc(b.updated)}</span>
          </div>
          <span class="badge-simdes badge-simdes-${esc(b.badge)} d-none d-sm-inline-flex">${esc(b.status)}</span>
          <div class="register-count">
            <span class="register-count-val">${esc(b.count)}</span>
            <span class="register-count-unit">${esc(b.unit)}</span>
          </div>
        </div>
      `
      ).join('');
    }

    if (pendudukEl) {
      pendudukEl.innerHTML = BUKU_ADM_PENDUDUK.map(
        (b) => `
        <div class="register-row">
          <span class="register-num">${esc(b.no)}</span>
          <div class="register-info">
            <p class="register-name" title="${esc(b.name)}">${esc(b.name)}</p>
            <span class="register-updated">Pembaruan: ${esc(b.updated)}</span>
          </div>
          <span class="badge-simdes badge-simdes-${esc(b.badge)} d-none d-sm-inline-flex">${esc(b.status)}</span>
          <div class="register-count">
            <span class="register-count-val">${esc(b.count)}</span>
            <span class="register-count-unit">${esc(b.unit)}</span>
          </div>
        </div>
      `
      ).join('');
    }
  }

  function renderRecentActivity(filter = 'all') {
    const listEl = document.getElementById('recentActivityList');
    if (!listEl) return;
    const esc = window.SimdesApp ? window.SimdesApp.escapeHtml : (s) => s;

    const items =
      filter === 'all'
        ? RECENT_ACTIVITIES
        : RECENT_ACTIVITIES.filter((item) => item.category === filter);

    listEl.innerHTML = items
      .map(
        (item) => `
        <div class="activity-item">
          <div class="activity-dot ${esc(item.iconClass)}">
            <i class="fa-solid ${esc(item.icon)}" aria-hidden="true"></i>
          </div>
          <div class="activity-body">
            <div class="activity-title">${esc(item.title)}</div>
            <div class="activity-meta">
              <span><i class="fa-regular fa-user me-1" aria-hidden="true"></i>${esc(item.actor)}</span>
              <span>&bull;</span>
              <span><i class="fa-regular fa-clock me-1" aria-hidden="true"></i>${esc(item.time)}</span>
              <span class="badge-simdes badge-simdes-neutral">${esc(item.tag)}</span>
            </div>
          </div>
        </div>
      `
      )
      .join('');
  }

  function initDashboardControls() {
    // Trend Chart Period Switcher
    document.querySelectorAll('[data-trend-period]').forEach((btn) => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('[data-trend-period]').forEach((b) => {
          b.classList.remove('active');
          b.setAttribute('aria-pressed', 'false');
        });
        btn.classList.add('active');
        btn.setAttribute('aria-pressed', 'true');
        renderTrendChart(btn.dataset.trendPeriod);
      });
    });

    // Activity Filter Switcher
    document.querySelectorAll('[data-activity-filter]').forEach((btn) => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('[data-activity-filter]').forEach((b) => {
          b.classList.remove('active');
          b.setAttribute('aria-pressed', 'false');
        });
        btn.classList.add('active');
        btn.setAttribute('aria-pressed', 'true');
        renderRecentActivity(btn.dataset.activityFilter);
      });
    });

    // Refresh Dummy Data Action
    const refreshBtn = document.getElementById('refreshDashboardBtn');
    refreshBtn?.addEventListener('click', () => {
      const icon = refreshBtn.querySelector('i');
      icon?.classList.add('fa-spin');
      window.setTimeout(() => {
        icon?.classList.remove('fa-spin');
        initGreetingAndDate();
        window.SimdesApp?.showToast({
          title: 'Ringkasan Diperbarui',
          message: 'Data statistik dummy SIMDES Desa Tiaro telah dimuat ulang.',
          variant: 'success'
        });
      }, 450);
    });

    // Print Summary Action
    const printBtn = document.getElementById('printDashboardSummaryBtn');
    printBtn?.addEventListener('click', () => {
      window.print();
    });
  }

  document.addEventListener('DOMContentLoaded', () => {
    if (!document.getElementById('dashboardPageRoot')) return;
    initGreetingAndDate();
    renderTrendChart('6m');
    renderDusunDonut();
    renderBookRegisterSummaries();
    renderRecentActivity('all');
    initDashboardControls();
  });
})();

