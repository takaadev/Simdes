/* ==========================================================================
   SIMDES DESA TIARO — Buku Inventaris dan Kekayaan Desa (Kode: A.3)
   File: assets/js/buku-inventaris-dan-kekayaan-desa.js
   Features:
   - Multi-row grouped header with configurable Reserve Columns (12–16)
   - CRUD with localStorage persistence
   - Search, origin/condition filters, sorting, pagination, auto numbering
   - Detail modal, delete confirmation, sample data reset, landscape print
   ========================================================================== */

(() => {
  'use strict';

  const STORAGE_KEY = 'simdes_tiaro_buku_inventaris_kekayaan_desa_v1';

  /**
   * ========================================================================
   * KONFIGURASI LIMA KOLOM CADANGAN (KOLOM 12–16)
   * ========================================================================
   * Kolom 12–16 pada tabel sumber belum memiliki nama resmi.
   * Untuk mengaktifkan salah satu atau seluruh kolom ini sebagai kolom resmi
   * di kemudian hari tanpa membangun ulang halaman:
   *   1. Isi `officialLabel` dengan nama kolom resmi (mis. 'Luas (m2)').
   *   2. Opsional: ubah `inputType` ('text' | 'number' | 'date').
   *   3. Opsional: set `enabled: false` jika kolom tidak digunakan.
   */
  const RESERVED_COLUMNS_CONFIG = {
    defaultVisible: true,
    groupNote: 'Slot Cadangan 12–16 (Nama Kolom Belum Tersedia)',
    officialGroupLabel: null,
    columns: [
      { slot: 12, key: 'cadangan_12', tempCode: 'Slot 12', officialLabel: null, inputType: 'text', enabled: true },
      { slot: 13, key: 'cadangan_13', tempCode: 'Slot 13', officialLabel: null, inputType: 'text', enabled: true },
      { slot: 14, key: 'cadangan_14', tempCode: 'Slot 14', officialLabel: null, inputType: 'text', enabled: true },
      { slot: 15, key: 'cadangan_15', tempCode: 'Slot 15', officialLabel: null, inputType: 'text', enabled: true },
      { slot: 16, key: 'cadangan_16', tempCode: 'Slot 16', officialLabel: null, inputType: 'text', enabled: true }
    ]
  };

  /**
   * Data Contoh Simulasi (8 item inventaris & bangunan)
   * Tidak menggunakan klaim dokumen/aset resmi nyata Pemerintah Desa Tiaro.
   */
  const SEED_DATA = [
    {
      id: 'inv-sim-001',
      jenisBarang: 'Gedung Balai Pelayanan Masyarakat (Data Contoh)',
      asalDibeli: 0,
      asalPemerintah: 0,
      asalProvinsi: 0,
      asalKabKota: 1,
      asalSumbangan: 0,
      awalBaik: 1,
      awalRusak: 0,
      hapusRusak: 0,
      hapusDijual: 0,
      hapusDisumbangkan: 0,
      tanggalPenghapusan: '',
      akhirBaik: 1,
      akhirRusak: 0,
      keterangan: 'Data contoh simulasi — Bangunan permanen layanan umum'
    },
    {
      id: 'inv-sim-002',
      jenisBarang: 'Perangkat Komputer Meja Layanan Administrasi (Data Contoh)',
      asalDibeli: 4,
      asalPemerintah: 2,
      asalProvinsi: 0,
      asalKabKota: 0,
      asalSumbangan: 0,
      awalBaik: 5,
      awalRusak: 1,
      hapusRusak: 1,
      hapusDijual: 0,
      hapusDisumbangkan: 0,
      tanggalPenghapusan: '2026-06-18',
      akhirBaik: 5,
      akhirRusak: 0,
      keterangan: 'Data contoh — 1 unit dihapus karena kerusakan komponen utama'
    },
    {
      id: 'inv-sim-003',
      jenisBarang: 'Kursi Lipat Ruang Pertemuan Umum (Data Contoh)',
      asalDibeli: 40,
      asalPemerintah: 0,
      asalProvinsi: 0,
      asalKabKota: 0,
      asalSumbangan: 20,
      awalBaik: 56,
      awalRusak: 4,
      hapusRusak: 4,
      hapusDijual: 0,
      hapusDisumbangkan: 0,
      tanggalPenghapusan: '2026-08-10',
      akhirBaik: 54,
      akhirRusak: 2,
      keterangan: 'Data contoh simulasi UI'
    },
    {
      id: 'inv-sim-004',
      jenisBarang: 'Kendaraan Operasional Roda Dua (Data Contoh)',
      asalDibeli: 0,
      asalPemerintah: 0,
      asalProvinsi: 2,
      asalKabKota: 0,
      asalSumbangan: 0,
      awalBaik: 2,
      awalRusak: 0,
      hapusRusak: 0,
      hapusDijual: 0,
      hapusDisumbangkan: 0,
      tanggalPenghapusan: '',
      akhirBaik: 2,
      akhirRusak: 0,
      keterangan: 'Data contoh — Operasional pelayanan lapangan'
    },
    {
      id: 'inv-sim-005',
      jenisBarang: 'Lemari Arsip Besi Pintu Geser (Data Contoh)',
      asalDibeli: 5,
      asalPemerintah: 0,
      asalProvinsi: 0,
      asalKabKota: 0,
      asalSumbangan: 0,
      awalBaik: 5,
      awalRusak: 0,
      hapusRusak: 0,
      hapusDijual: 0,
      hapusDisumbangkan: 0,
      tanggalPenghapusan: '',
      akhirBaik: 5,
      akhirRusak: 0,
      keterangan: 'Data contoh — Penyimpanan buku register administrasi'
    },
    {
      id: 'inv-sim-006',
      jenisBarang: 'Tenda Kerucut Kegiatan Kemasyarakatan (Data Contoh)',
      asalDibeli: 0,
      asalPemerintah: 0,
      asalProvinsi: 0,
      asalKabKota: 4,
      asalSumbangan: 0,
      awalBaik: 4,
      awalRusak: 0,
      hapusRusak: 0,
      hapusDijual: 0,
      hapusDisumbangkan: 1,
      tanggalPenghapusan: '2026-05-22',
      akhirBaik: 3,
      akhirRusak: 0,
      keterangan: 'Data contoh — 1 unit disumbangkan untuk fasilitas kelompok warga'
    },
    {
      id: 'inv-sim-007',
      jenisBarang: 'Mesin Pemotong Rumput Dorong (Data Contoh)',
      asalDibeli: 2,
      asalPemerintah: 0,
      asalProvinsi: 0,
      asalKabKota: 0,
      asalSumbangan: 1,
      awalBaik: 2,
      awalRusak: 1,
      hapusRusak: 0,
      hapusDijual: 0,
      hapusDisumbangkan: 0,
      tanggalPenghapusan: '',
      akhirBaik: 2,
      akhirRusak: 1,
      keterangan: 'Data contoh — 1 unit dalam pemeliharaan berkala'
    },
    {
      id: 'inv-sim-008',
      jenisBarang: 'Perangkat Pengeras Suara Portabel (Data Contoh)',
      asalDibeli: 2,
      asalPemerintah: 1,
      asalProvinsi: 0,
      asalKabKota: 0,
      asalSumbangan: 0,
      awalBaik: 3,
      awalRusak: 0,
      hapusRusak: 0,
      hapusDijual: 0,
      hapusDisumbangkan: 0,
      tanggalPenghapusan: '',
      akhirBaik: 2,
      akhirRusak: 1,
      keterangan: 'Data contoh simulasi UI'
    }
  ];

  function toNonNegInt(val) {
    const n = parseInt(val, 10);
    return Number.isFinite(n) && n >= 0 ? n : 0;
  }

  function cloneSeedData() {
    return SEED_DATA.map((item) => ({ ...item }));
  }

  function loadRecords() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      if (raw) {
        const parsed = JSON.parse(raw);
        if (Array.isArray(parsed)) return parsed;
      }
      const initial = cloneSeedData();
      localStorage.setItem(STORAGE_KEY, JSON.stringify(initial));
      return initial;
    } catch (_) {
      return cloneSeedData();
    }
  }

  function saveRecords(records) {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(records));
    } catch (_) {
      // Ignore storage errors
    }
  }

  function totalOriginUnits(r) {
    return (
      toNonNegInt(r.asalDibeli) +
      toNonNegInt(r.asalPemerintah) +
      toNonNegInt(r.asalProvinsi) +
      toNonNegInt(r.asalKabKota) +
      toNonNegInt(r.asalSumbangan)
    );
  }

  function totalDeletedUnits(r) {
    return (
      toNonNegInt(r.hapusRusak) +
      toNonNegInt(r.hapusDijual) +
      toNonNegInt(r.hapusDisumbangkan)
    );
  }

  function initBukuInventarisPage() {
    const pageRoot = document.getElementById('bukuInventarisPageRoot');
    if (!pageRoot) return;

    const esc = window.SimdesApp ? window.SimdesApp.escapeHtml : (s) => String(s ?? '');
    const formatDate = window.SimdesTable ? window.SimdesTable.formatDateIndo : (s) => s;

    let records = loadRecords();
    let showReservedCols = Boolean(RESERVED_COLUMNS_CONFIG.defaultVisible);
    let activeDetailId = null;
    let pendingDeleteId = null;

    // DOM Elements
    const tableEl = document.getElementById('inventarisTable');
    const theadEl = document.getElementById('inventarisTableHead');
    const tbodyEl = document.getElementById('inventarisTableBody');

    const searchInput = document.getElementById('tableSearchInput');
    const clearSearchBtn = document.getElementById('clearSearchInputBtn');
    const filterAsalSelect = document.getElementById('filterAsalSelect');
    const filterKondisiSelect = document.getElementById('filterKondisiSelect');
    const pageSizeSelect = document.getElementById('pageSizeSelect');
    const resetFilterBtn = document.getElementById('resetTableFilterBtn');
    const toggleReservedBtn = document.getElementById('toggleReservedColsBtn');

    const paginationInfo = document.getElementById('tablePaginationInfo');
    const paginationList = document.getElementById('tablePaginationList');

    // KPI Summary Elements
    const statJenisEl = document.getElementById('statTotalJenis');
    const statAkhirBaikEl = document.getElementById('statTotalAkhirBaik');
    const statAkhirRusakEl = document.getElementById('statTotalAkhirRusak');
    const statPenghapusanEl = document.getElementById('statTotalPenghapusan');

    // Top Buttons
    const addBtn = document.getElementById('btnTambahInventaris');
    const printBtn = document.getElementById('btnCetakInventaris');
    const resetSeedBtn = document.getElementById('btnResetSampleInventaris');

    // Modals & Form Elements
    const formModalEl = document.getElementById('inventarisFormModal');
    const detailModalEl = document.getElementById('inventarisDetailModal');
    const deleteModalEl = document.getElementById('inventarisDeleteModal');

    const formEl = document.getElementById('inventarisForm');
    const formModalTitle = document.getElementById('inventarisFormModalLabel');
    const formSubmitBtn = document.getElementById('inventarisFormSubmitBtn');
    const formAlertBox = document.getElementById('inventarisFormAlert');
    const formAlertText = document.getElementById('inventarisFormAlertText');
    const dynamicReservedWrap = document.getElementById('dynamicReservedFieldsWrap');
    const dynamicReservedGrid = document.getElementById('dynamicReservedFieldsGrid');

    const inputId = document.getElementById('invRecordId');
    const inputJenis = document.getElementById('invJenisBarang');
    const inputAsalDibeli = document.getElementById('invAsalDibeli');
    const inputAsalPemerintah = document.getElementById('invAsalPemerintah');
    const inputAsalProvinsi = document.getElementById('invAsalProvinsi');
    const inputAsalKabKota = document.getElementById('invAsalKabKota');
    const inputAsalSumbangan = document.getElementById('invAsalSumbangan');
    const inputAwalBaik = document.getElementById('invAwalBaik');
    const inputAwalRusak = document.getElementById('invAwalRusak');
    const inputHapusRusak = document.getElementById('invHapusRusak');
    const inputHapusDijual = document.getElementById('invHapusDijual');
    const inputHapusDisumbangkan = document.getElementById('invHapusDisumbangkan');
    const inputTanggalHapus = document.getElementById('invTanggalHapus');
    const inputAkhirBaik = document.getElementById('invAkhirBaik');
    const inputAkhirRusak = document.getElementById('invAkhirRusak');
    const inputKeterangan = document.getElementById('invKeterangan');

    window.SimdesForms?.InteractionValidator?.bindForm(formEl);

    function getActiveReservedColumns() {
      if (!showReservedCols) return [];
      return RESERVED_COLUMNS_CONFIG.columns.filter((c) => c.enabled);
    }

    function getOfficialConfiguredReservedColumns() {
      return RESERVED_COLUMNS_CONFIG.columns.filter(
        (c) => c.enabled && typeof c.officialLabel === 'string' && c.officialLabel.trim().length > 0
      );
    }

    /**
     * Render optional form inputs ONLY if official column names have been
     * assigned in RESERVED_COLUMNS_CONFIG (stays hidden by default).
     */
    function renderConfiguredReservedFormInputs() {
      const configured = getOfficialConfiguredReservedColumns();
      if (!dynamicReservedWrap || !dynamicReservedGrid) return;

      if (configured.length === 0) {
        dynamicReservedWrap.hidden = true;
        dynamicReservedGrid.innerHTML = '';
        return;
      }

      dynamicReservedWrap.hidden = false;
      dynamicReservedGrid.innerHTML = configured
        .map(
          (col) => `
          <div class="col-12 col-md-4">
            <div class="simdes-field mb-2">
              <label for="inv_${esc(col.key)}" class="simdes-label">${esc(col.officialLabel)}</label>
              <input
                type="${esc(col.inputType || 'text')}"
                id="inv_${esc(col.key)}"
                name="${esc(col.key)}"
                class="simdes-input"
                data-reserved-key="${esc(col.key)}"
              >
            </div>
          </div>
        `
        )
        .join('');
    }

    /**
     * Build 3-level semantic <thead> with exact rowspan & colspan alignment
     */
    function renderMultiLevelHeader(sortKey, sortDirection) {
      if (!theadEl) return;

      const reservedCols = getActiveReservedColumns();
      tableEl?.classList.toggle('compact-no-reserved', reservedCols.length === 0);

      const sortIconFor = (key) => {
        if (sortKey !== key) return 'fa-sort';
        return sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down';
      };

      const ariaSortFor = (key) => {
        if (sortKey !== key) return 'none';
        return sortDirection === 'asc' ? 'ascending' : 'descending';
      };

      const hasAllOfficial =
        reservedCols.length > 0 &&
        reservedCols.every((c) => typeof c.officialLabel === 'string' && c.officialLabel.trim());

      const reservedGroupHeaderHtml =
        reservedCols.length > 0
          ? hasAllOfficial
            ? `<th colspan="${reservedCols.length}" scope="colgroup" class="th-group-top group-divider-end">
                 ${esc(RESERVED_COLUMNS_CONFIG.officialGroupLabel || 'Informasi Tambahan')}
               </th>`
            : `<th colspan="${reservedCols.length}" scope="colgroup" class="th-placeholder-group" title="Lima kolom cadangan yang dapat disesuaikan melalui konfigurasi JavaScript">
                 ${esc(RESERVED_COLUMNS_CONFIG.groupNote)}
               </th>`
          : '';

      const reservedSubHeadersHtml = reservedCols
        .map((col, idx) => {
          const isLast = idx === reservedCols.length - 1;
          if (col.officialLabel && col.officialLabel.trim()) {
            return `<th rowspan="2" scope="col" class="${isLast ? 'group-divider-end' : ''}">
                      ${esc(col.officialLabel)}
                    </th>`;
          }
          return `
            <th rowspan="2" scope="col" class="th-placeholder-col${isLast ? ' last-placeholder-col' : ''}" title="Nama kolom ke-${col.slot} belum tersedia pada tabel sumber">
              <span class="placeholder-slot-code">${esc(col.tempCode)}</span>
              <span class="placeholder-slot-hint">Belum tersedia</span>
            </th>
          `;
        })
        .join('');

      theadEl.innerHTML = `
        <tr>
          <th rowspan="3" scope="col" class="col-no-urut sticky-col-first" style="width:58px;">No. Urut</th>
          <th rowspan="3" scope="col" class="group-divider-end" style="min-width:230px; text-align:start;" aria-sort="${ariaSortFor('jenisBarang')}">
            <button type="button" class="th-sort-btn${sortKey === 'jenisBarang' ? ' is-sorted' : ''}" data-sort-key="jenisBarang">
              <span>Jenis Barang/Bangunan</span>
              <i class="fa-solid ${sortIconFor('jenisBarang')} th-sort-icon" aria-hidden="true"></i>
            </button>
          </th>
          <th colspan="5" scope="colgroup" class="th-group-top group-divider-end">Asal Barang/Bangunan</th>
          <th colspan="2" scope="colgroup" class="th-group-top group-divider-end">Keadaan Barang/Bangunan Awal Tahun</th>
          <th colspan="4" scope="colgroup" class="th-group-top group-divider-end">Penghapusan Barang dan Bangunan</th>
          <th colspan="2" scope="colgroup" class="th-group-top group-divider-end">Keadaan Barang/Bangunan Akhir Tahun</th>
          ${reservedGroupHeaderHtml}
          <th rowspan="3" scope="col" style="min-width:185px;">Keterangan</th>
        </tr>
        <tr>
          <!-- Asal Barang/Bangunan Subheaders -->
          <th rowspan="2" scope="col" style="min-width:86px;">Dibeli Sendiri</th>
          <th colspan="3" scope="colgroup" class="th-group-sub">Bantuan</th>
          <th rowspan="2" scope="col" class="group-divider-end" style="min-width:86px;">Sumbangan</th>

          <!-- Keadaan Awal Tahun Subheaders -->
          <th rowspan="2" scope="col" style="min-width:74px;" aria-sort="${ariaSortFor('awalBaik')}">
            <button type="button" class="th-sort-btn justify-content-center${sortKey === 'awalBaik' ? ' is-sorted' : ''}" data-sort-key="awalBaik">
              <span>Baik</span>
              <i class="fa-solid ${sortIconFor('awalBaik')} th-sort-icon" aria-hidden="true"></i>
            </button>
          </th>
          <th rowspan="2" scope="col" class="group-divider-end" style="min-width:74px;">Rusak</th>

          <!-- Penghapusan Barang dan Bangunan Subheaders -->
          <th rowspan="2" scope="col" style="min-width:74px;">Rusak</th>
          <th rowspan="2" scope="col" style="min-width:74px;">Dijual</th>
          <th rowspan="2" scope="col" style="min-width:96px;">Disumbangkan</th>
          <th rowspan="2" scope="col" class="group-divider-end" style="min-width:130px;" aria-sort="${ariaSortFor('tanggalPenghapusan')}">
            <button type="button" class="th-sort-btn justify-content-center${sortKey === 'tanggalPenghapusan' ? ' is-sorted' : ''}" data-sort-key="tanggalPenghapusan">
              <span>Tanggal Penghapusan</span>
              <i class="fa-solid ${sortIconFor('tanggalPenghapusan')} th-sort-icon" aria-hidden="true"></i>
            </button>
          </th>

          <!-- Keadaan Akhir Tahun Subheaders -->
          <th rowspan="2" scope="col" style="min-width:74px;" aria-sort="${ariaSortFor('akhirBaik')}">
            <button type="button" class="th-sort-btn justify-content-center${sortKey === 'akhirBaik' ? ' is-sorted' : ''}" data-sort-key="akhirBaik">
              <span>Baik</span>
              <i class="fa-solid ${sortIconFor('akhirBaik')} th-sort-icon" aria-hidden="true"></i>
            </button>
          </th>
          <th rowspan="2" scope="col" class="group-divider-end" style="min-width:74px;">Rusak</th>

          <!-- Kolom Cadangan 12–16 (Jika Aktif) -->
          ${reservedSubHeadersHtml}
        </tr>
        <tr>
          <!-- Rincian Keterangan Asal Bantuan -->
          <th scope="col" style="min-width:86px;">Pemerintah</th>
          <th scope="col" style="min-width:78px;">Provinsi</th>
          <th scope="col" style="min-width:108px;">Kabupaten/Kota</th>
        </tr>
      `;

      // Bind sort triggers inside freshly rendered header
      theadEl.querySelectorAll('[data-sort-key]').forEach((btn) => {
        btn.addEventListener('click', () => {
          const key = btn.getAttribute('data-sort-key');
          if (key) tableCtrl.toggleSort(key);
        });
      });
    }

    function formatNumCell(val, toneClass = '', extraClass = '') {
      const n = toNonNegInt(val);
      const cls = ['td-num', n === 0 ? 'is-zero' : toneClass, extraClass]
        .filter(Boolean)
        .join(' ');
      return `<td class="${cls}">${n === 0 ? '&ndash;' : n.toLocaleString('id-ID')}</td>`;
    }

    function renderBodyRows(rowsList, startIndex = 0) {
      const reservedCols = getActiveReservedColumns();
      const totalLeafCols = 16 + reservedCols.length;

      if (rowsList.length === 0) {
        return `
          <tr>
            <td colspan="${totalLeafCols}">
              <div class="table-empty-state">
                <div class="table-empty-icon">
                  <i class="fa-solid fa-boxes-stacked" aria-hidden="true"></i>
                </div>
                <div class="fw-bold mb-1" style="color:var(--simdes-text); font-size:0.95rem;">
                  Data Inventaris Tidak Ditemukan
                </div>
                <p class="mb-3" style="font-size:0.83rem; max-width:46ch; margin-inline:auto;">
                  Tidak ada barang atau bangunan yang cocok dengan pencarian maupun filter asal/kondisi saat ini.
                </p>
                <div class="d-inline-flex flex-wrap justify-content-center gap-2 no-print">
                  <button type="button" class="btn-simdes-outline" data-empty-action="reset-filter">
                    <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                    <span>Reset Filter</span>
                  </button>
                  <button type="button" class="btn-simdes-primary" data-empty-action="add-new">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                    <span>Tambah Inventaris</span>
                  </button>
                </div>
              </div>
            </td>
          </tr>
        `;
      }

      return rowsList
        .map((row, idx) => {
          const noUrut = startIndex + idx + 1;
          const tglHapus = row.tanggalPenghapusan
            ? `<span class="tabular-nums" style="font-size:0.8rem;">${esc(formatDate(row.tanggalPenghapusan))}</span>`
            : `<span class="text-simdes-muted">&ndash;</span>`;

          const reservedCellsHtml = reservedCols
            .map((col, cIdx) => {
              const isLast = cIdx === reservedCols.length - 1;
              if (col.officialLabel && col.officialLabel.trim()) {
                const v = String(row[col.key] ?? '').trim();
                return `<td class="${isLast ? 'group-divider-end' : ''}">${v ? esc(v) : '&ndash;'}</td>`;
              }
              return `<td class="td-placeholder-col${isLast ? ' last-placeholder-col' : ''}" title="Kolom cadangan ke-${col.slot}">&mdash;</td>`;
            })
            .join('');

          const ketText = String(row.keterangan || '').trim()
            ? esc(row.keterangan)
            : '-';

          return `
            <tr data-row-id="${esc(row.id)}">
              <td class="col-no-urut sticky-col-first">${noUrut}</td>
              <td class="group-divider-end">
                <button type="button" class="tentang-title-btn" data-row-action="detail" data-id="${esc(row.id)}">
                  ${esc(row.jenisBarang)}
                </button>
              </td>

              <!-- Asal Barang/Bangunan (5 Kolom) -->
              ${formatNumCell(row.asalDibeli)}
              ${formatNumCell(row.asalPemerintah)}
              ${formatNumCell(row.asalProvinsi)}
              ${formatNumCell(row.asalKabKota)}
              ${formatNumCell(row.asalSumbangan, '', 'group-divider-end')}

              <!-- Keadaan Awal Tahun (2 Kolom) -->
              ${formatNumCell(row.awalBaik, 'is-good')}
              ${formatNumCell(row.awalRusak, 'is-damaged', 'group-divider-end')}

              <!-- Penghapusan Barang dan Bangunan (4 Kolom) -->
              ${formatNumCell(row.hapusRusak, 'is-removed')}
              ${formatNumCell(row.hapusDijual, 'is-removed')}
              ${formatNumCell(row.hapusDisumbangkan, 'is-removed')}
              <td class="text-center group-divider-end">${tglHapus}</td>

              <!-- Keadaan Akhir Tahun (2 Kolom) -->
              ${formatNumCell(row.akhirBaik, 'is-good')}
              ${formatNumCell(row.akhirRusak, 'is-damaged', 'group-divider-end')}

              <!-- Kolom Cadangan 12–16 -->
              ${reservedCellsHtml}

              <!-- Keterangan & Aksi Baris Non-Cetak -->
              <td>
                <div class="ket-cell-content">
                  <span class="ket-text">${ketText}</span>
                  <div class="row-action-group no-print" role="group" aria-label="Aksi baris inventaris nomor ${noUrut}">
                    <button type="button" class="btn-row-action" data-row-action="detail" data-id="${esc(row.id)}" title="Lihat Detail" aria-label="Lihat detail ${esc(row.jenisBarang)}">
                      <i class="fa-regular fa-eye" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn-row-action" data-row-action="edit" data-id="${esc(row.id)}" title="Edit Inventaris" aria-label="Edit ${esc(row.jenisBarang)}">
                      <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn-row-action action-delete" data-row-action="delete" data-id="${esc(row.id)}" title="Hapus Inventaris" aria-label="Hapus ${esc(row.jenisBarang)}">
                      <i class="fa-regular fa-trash-can" aria-hidden="true"></i>
                    </button>
                  </div>
                </div>
              </td>
            </tr>
          `;
        })
        .join('');
    }

    function updateSummaryStats() {
      const totalJenis = records.length;
      const sumAkhirBaik = records.reduce((acc, r) => acc + toNonNegInt(r.akhirBaik), 0);
      const sumAkhirRusak = records.reduce((acc, r) => acc + toNonNegInt(r.akhirRusak), 0);
      const sumHapus = records.reduce((acc, r) => acc + totalDeletedUnits(r), 0);

      if (statJenisEl) statJenisEl.textContent = totalJenis.toLocaleString('id-ID');
      if (statAkhirBaikEl) statAkhirBaikEl.textContent = sumAkhirBaik.toLocaleString('id-ID');
      if (statAkhirRusakEl) statAkhirRusakEl.textContent = sumAkhirRusak.toLocaleString('id-ID');
      if (statPenghapusanEl) statPenghapusanEl.textContent = sumHapus.toLocaleString('id-ID');
    }

    // Initialize Table Controller
    const tableCtrl = window.SimdesTable.create({
      data: records,
      pageSize: 5,
      searchFields: ['jenisBarang', 'keterangan'],
      defaultSortKey: 'jenisBarang',
      defaultSortDirection: 'asc',
      comparators: {
        jenisBarang: (a, b) =>
          String(a.jenisBarang || '').localeCompare(String(b.jenisBarang || ''), 'id', { sensitivity: 'base' }),
        awalBaik: (a, b) => toNonNegInt(a.awalBaik) - toNonNegInt(b.awalBaik),
        akhirBaik: (a, b) => toNonNegInt(a.akhirBaik) - toNonNegInt(b.akhirBaik),
        tanggalPenghapusan: (a, b) =>
          String(a.tanggalPenghapusan || '').localeCompare(String(b.tanggalPenghapusan || ''))
      },
      onRender: (state) => {
        renderMultiLevelHeader(state.sortKey, state.sortDirection);
        if (tbodyEl) {
          tbodyEl.innerHTML = renderBodyRows(state.rows, state.startIndex);
        }

        if (paginationInfo) {
          if (state.totalItems === 0) {
            paginationInfo.textContent = 'Menampilkan 0 data inventaris';
          } else {
            paginationInfo.innerHTML = `Menampilkan <strong>${state.startEntry}&ndash;${state.endEntry}</strong> dari <strong>${state.totalItems}</strong> jenis barang/bangunan`;
          }
        }

        window.SimdesTable.renderPagination(paginationList, state, (page) => {
          tableCtrl.setPage(page);
        });

        if (resetFilterBtn) {
          resetFilterBtn.disabled = !state.hasActiveQuery;
        }
        if (clearSearchBtn) {
          clearSearchBtn.classList.toggle('visible', Boolean(searchInput?.value.trim()));
        }
      }
    });

    function hideFormBanner() {
      if (formAlertBox) formAlertBox.classList.remove('show');
    }

    function showFormBanner(msg) {
      if (!formAlertBox || !formAlertText) return;
      formAlertText.textContent = msg;
      formAlertBox.classList.add('show');
    }

    function resetNumericDefaults() {
      [
        inputAsalDibeli,
        inputAsalPemerintah,
        inputAsalProvinsi,
        inputAsalKabKota,
        inputAsalSumbangan,
        inputAwalBaik,
        inputAwalRusak,
        inputHapusRusak,
        inputHapusDijual,
        inputHapusDisumbangkan,
        inputAkhirBaik,
        inputAkhirRusak
      ].forEach((el) => {
        if (el) el.value = '0';
      });
    }

    function openCreateModal() {
      formEl?.reset();
      hideFormBanner();
      resetNumericDefaults();
      if (inputId) inputId.value = '';
      if (inputTanggalHapus) inputTanggalHapus.value = '';

      if (formModalTitle) {
        formModalTitle.innerHTML = `<i class="fa-solid fa-plus-circle text-simdes-primary me-2" aria-hidden="true"></i>Tambah Inventaris dan Kekayaan Desa`;
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML = `<i class="fa-solid fa-floppy-disk" aria-hidden="true"></i><span>Simpan Inventaris</span>`;
      }

      if (formModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(formModalEl).show();
      }
    }

    function openEditModal(id) {
      const record = records.find((r) => r.id === id);
      if (!record || !formEl) return;

      formEl.reset();
      hideFormBanner();

      if (inputId) inputId.value = record.id;
      if (inputJenis) inputJenis.value = record.jenisBarang || '';
      if (inputAsalDibeli) inputAsalDibeli.value = toNonNegInt(record.asalDibeli);
      if (inputAsalPemerintah) inputAsalPemerintah.value = toNonNegInt(record.asalPemerintah);
      if (inputAsalProvinsi) inputAsalProvinsi.value = toNonNegInt(record.asalProvinsi);
      if (inputAsalKabKota) inputAsalKabKota.value = toNonNegInt(record.asalKabKota);
      if (inputAsalSumbangan) inputAsalSumbangan.value = toNonNegInt(record.asalSumbangan);

      if (inputAwalBaik) inputAwalBaik.value = toNonNegInt(record.awalBaik);
      if (inputAwalRusak) inputAwalRusak.value = toNonNegInt(record.awalRusak);

      if (inputHapusRusak) inputHapusRusak.value = toNonNegInt(record.hapusRusak);
      if (inputHapusDijual) inputHapusDijual.value = toNonNegInt(record.hapusDijual);
      if (inputHapusDisumbangkan) inputHapusDisumbangkan.value = toNonNegInt(record.hapusDisumbangkan);
      if (inputTanggalHapus) inputTanggalHapus.value = record.tanggalPenghapusan || '';

      if (inputAkhirBaik) inputAkhirBaik.value = toNonNegInt(record.akhirBaik);
      if (inputAkhirRusak) inputAkhirRusak.value = toNonNegInt(record.akhirRusak);
      if (inputKeterangan) inputKeterangan.value = record.keterangan || '';

      getOfficialConfiguredReservedColumns().forEach((col) => {
        const dynInput = document.getElementById(`inv_${col.key}`);
        if (dynInput) dynInput.value = record[col.key] ?? '';
      });

      if (formModalTitle) {
        formModalTitle.innerHTML = `<i class="fa-regular fa-pen-to-square text-simdes-primary me-2" aria-hidden="true"></i>Edit Data Inventaris Desa`;
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML = `<i class="fa-solid fa-check" aria-hidden="true"></i><span>Simpan Perubahan</span>`;
      }

      if (formModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(formModalEl).show();
      }
    }

    function openDetailModal(id) {
      const record = records.find((r) => r.id === id);
      if (!record) return;
      activeDetailId = record.id;

      const setTxt = (elId, val) => {
        const el = document.getElementById(elId);
        if (el) el.textContent = String(val);
      };

      setTxt('detJenisBarang', record.jenisBarang || '-');
      setTxt('detAsalDibeli', `${toNonNegInt(record.asalDibeli)} unit`);
      setTxt('detAsalPemerintah', `${toNonNegInt(record.asalPemerintah)} unit`);
      setTxt('detAsalProvinsi', `${toNonNegInt(record.asalProvinsi)} unit`);
      setTxt('detAsalKabKota', `${toNonNegInt(record.asalKabKota)} unit`);
      setTxt('detAsalSumbangan', `${toNonNegInt(record.asalSumbangan)} unit`);
      setTxt('detTotalAsal', `${totalOriginUnits(record)} unit`);

      setTxt('detAwalBaik', `${toNonNegInt(record.awalBaik)} unit`);
      setTxt('detAwalRusak', `${toNonNegInt(record.awalRusak)} unit`);

      setTxt('detHapusRusak', `${toNonNegInt(record.hapusRusak)} unit`);
      setTxt('detHapusDijual', `${toNonNegInt(record.hapusDijual)} unit`);
      setTxt('detHapusDisumbangkan', `${toNonNegInt(record.hapusDisumbangkan)} unit`);
      setTxt(
        'detTanggalHapus',
        record.tanggalPenghapusan ? formatDate(record.tanggalPenghapusan) : 'Tidak ada penghapusan'
      );

      setTxt('detAkhirBaik', `${toNonNegInt(record.akhirBaik)} unit`);
      setTxt('detAkhirRusak', `${toNonNegInt(record.akhirRusak)} unit`);
      setTxt('detKeterangan', String(record.keterangan || '').trim() || '-');

      const detReservedBox = document.getElementById('detConfiguredReservedBox');
      const detReservedGrid = document.getElementById('detConfiguredReservedGrid');
      const configuredCols = getOfficialConfiguredReservedColumns();
      if (detReservedBox && detReservedGrid) {
        if (configuredCols.length === 0) {
          detReservedBox.hidden = true;
          detReservedGrid.innerHTML = '';
        } else {
          detReservedBox.hidden = false;
          detReservedGrid.innerHTML = configuredCols
            .map(
              (col) => `
              <div class="col-6 col-md-4">
                <small class="text-simdes-muted d-block">${esc(col.officialLabel)}</small>
                <strong>${esc(String(record[col.key] || '-').trim() || '-')}</strong>
              </div>
            `
            )
            .join('');
        }
      }

      if (detailModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(detailModalEl).show();
      }
    }

    function openDeleteModal(id) {
      const record = records.find((r) => r.id === id);
      if (!record) return;
      pendingDeleteId = record.id;

      const nameEl = document.getElementById('deleteInventarisTargetName');
      const metaEl = document.getElementById('deleteInventarisTargetMeta');
      if (nameEl) nameEl.textContent = record.jenisBarang;
      if (metaEl) {
        metaEl.textContent = `Keadaan Akhir Tahun: ${toNonNegInt(record.akhirBaik)} Baik, ${toNonNegInt(record.akhirRusak)} Rusak`;
      }

      if (deleteModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(deleteModalEl).show();
      }
    }

    // Form Validation & Save
    formEl?.addEventListener('submit', (e) => {
      e.preventDefault();
      hideFormBanner();
      inputTanggalHapus?.setCustomValidity('');

      const baseValid = window.SimdesForms?.InteractionValidator
        ? window.SimdesForms.InteractionValidator.validateAll(formEl)
        : formEl.checkValidity();

      if (!baseValid) {
        showFormBanner('Mohon lengkapi kolom wajib dan pastikan nilai angka tidak bernilai negatif.');
        return;
      }

      const draft = {
        asalDibeli: toNonNegInt(inputAsalDibeli?.value),
        asalPemerintah: toNonNegInt(inputAsalPemerintah?.value),
        asalProvinsi: toNonNegInt(inputAsalProvinsi?.value),
        asalKabKota: toNonNegInt(inputAsalKabKota?.value),
        asalSumbangan: toNonNegInt(inputAsalSumbangan?.value),
        awalBaik: toNonNegInt(inputAwalBaik?.value),
        awalRusak: toNonNegInt(inputAwalRusak?.value),
        hapusRusak: toNonNegInt(inputHapusRusak?.value),
        hapusDijual: toNonNegInt(inputHapusDijual?.value),
        hapusDisumbangkan: toNonNegInt(inputHapusDisumbangkan?.value),
        tanggalPenghapusan: inputTanggalHapus?.value || '',
        akhirBaik: toNonNegInt(inputAkhirBaik?.value),
        akhirRusak: toNonNegInt(inputAkhirRusak?.value)
      };

      if (totalOriginUnits(draft) === 0) {
        showFormBanner('Minimal salah satu sumber pada "B. Asal Barang/Bangunan" harus lebih dari 0.');
        inputAsalDibeli?.focus();
        return;
      }

      const deletedCount = totalDeletedUnits(draft);
      if (deletedCount > 0 && !draft.tanggalPenghapusan) {
        const msg = 'Tanggal penghapusan wajib diisi apabila terdapat barang yang dihapus/dijual/disumbangkan.';
        window.SimdesForms?.InteractionValidator?.syncFieldState(inputTanggalHapus, msg);
        showFormBanner(msg);
        inputTanggalHapus?.focus();
        return;
      }

      const editingId = inputId?.value.trim();
      const payload = {
        id: editingId || `inv-${Date.now()}`,
        jenisBarang: inputJenis.value.trim(),
        ...draft,
        keterangan: inputKeterangan?.value.trim() || ''
      };

      getOfficialConfiguredReservedColumns().forEach((col) => {
        const dynInput = document.getElementById(`inv_${col.key}`);
        if (dynInput) payload[col.key] = dynInput.value.trim();
      });

      if (editingId) {
        records = records.map((r) => (r.id === editingId ? payload : r));
      } else {
        records = [payload, ...records];
      }

      saveRecords(records);
      updateSummaryStats();
      tableCtrl.setData(records, Boolean(editingId));

      if (formModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getInstance(formModalEl)?.hide();
      }

      window.SimdesApp?.showToast({
        title: editingId ? 'Inventaris Diperbarui' : 'Inventaris Baru Ditambahkan',
        message: `"${payload.jenisBarang}" berhasil disimpan.`,
        variant: 'success'
      });
    });

    // Table Body Actions Delegation
    tbodyEl?.addEventListener('click', (e) => {
      const actionBtn = e.target.closest('[data-row-action]');
      if (actionBtn) {
        const action = actionBtn.getAttribute('data-row-action');
        const id = actionBtn.getAttribute('data-id');
        if (action === 'detail') openDetailModal(id);
        else if (action === 'edit') openEditModal(id);
        else if (action === 'delete') openDeleteModal(id);
        return;
      }

      const emptyBtn = e.target.closest('[data-empty-action]');
      if (emptyBtn) {
        const act = emptyBtn.getAttribute('data-empty-action');
        if (act === 'reset-filter') resetAllFilters();
        else if (act === 'add-new') openCreateModal();
      }
    });

    document.getElementById('btnEditInventarisFromDetail')?.addEventListener('click', () => {
      if (!activeDetailId) return;
      const id = activeDetailId;
      if (detailModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getInstance(detailModalEl)?.hide();
      }
      window.setTimeout(() => openEditModal(id), 180);
    });

    document.getElementById('confirmDeleteInventarisBtn')?.addEventListener('click', () => {
      if (!pendingDeleteId) return;
      const target = records.find((r) => r.id === pendingDeleteId);
      records = records.filter((r) => r.id !== pendingDeleteId);
      pendingDeleteId = null;

      saveRecords(records);
      updateSummaryStats();
      tableCtrl.setData(records, true);

      if (deleteModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getInstance(deleteModalEl)?.hide();
      }

      window.SimdesApp?.showToast({
        title: 'Data Inventaris Dihapus',
        message: target
          ? `"${target.jenisBarang}" telah dihapus dari buku inventaris.`
          : 'Data inventaris berhasil dihapus.',
        variant: 'warning'
      });
    });

    // Search & Filters
    function resetAllFilters() {
      if (searchInput) searchInput.value = '';
      if (filterAsalSelect) filterAsalSelect.value = 'ALL';
      if (filterKondisiSelect) filterKondisiSelect.value = 'ALL';
      tableCtrl.resetAll();
    }

    searchInput?.addEventListener('input', () => {
      tableCtrl.setSearch(searchInput.value);
    });

    clearSearchBtn?.addEventListener('click', () => {
      if (!searchInput) return;
      searchInput.value = '';
      tableCtrl.setSearch('');
      searchInput.focus();
    });

    resetFilterBtn?.addEventListener('click', resetAllFilters);

    filterAsalSelect?.addEventListener('change', () => {
      const val = filterAsalSelect.value;
      if (val === 'ALL') {
        tableCtrl.setFilter('asal', null);
      } else {
        tableCtrl.setFilter('asal', (_, row) => toNonNegInt(row[val]) > 0);
      }
    });

    filterKondisiSelect?.addEventListener('change', () => {
      const val = filterKondisiSelect.value;
      if (val === 'ALL') {
        tableCtrl.setFilter('kondisi', null);
      } else if (val === 'GOOD_ONLY') {
        tableCtrl.setFilter(
          'kondisi',
          (_, row) => toNonNegInt(row.akhirBaik) > 0 && toNonNegInt(row.akhirRusak) === 0
        );
      } else if (val === 'HAS_DAMAGED') {
        tableCtrl.setFilter(
          'kondisi',
          (_, row) => toNonNegInt(row.awalRusak) > 0 || toNonNegInt(row.akhirRusak) > 0
        );
      } else if (val === 'HAS_DELETION') {
        tableCtrl.setFilter('kondisi', (_, row) => totalDeletedUnits(row) > 0);
      }
    });

    pageSizeSelect?.addEventListener('change', () => {
      tableCtrl.setPageSize(Number(pageSizeSelect.value) || 5);
    });

    // Toggle Visibility of 5 Temporary Reserve Columns (12–16)
    toggleReservedBtn?.addEventListener('click', () => {
      showReservedCols = !showReservedCols;
      toggleReservedBtn.setAttribute('aria-pressed', String(showReservedCols));
      const labelSpan = toggleReservedBtn.querySelector('span');
      if (labelSpan) {
        labelSpan.textContent = showReservedCols
          ? 'Kolom 12–16: Tampil'
          : 'Kolom 12–16: Sembunyi';
      }
      tableCtrl.render();
    });

    // Top Actions
    addBtn?.addEventListener('click', openCreateModal);

    resetSeedBtn?.addEventListener('click', () => {
      records = cloneSeedData();
      saveRecords(records);
      resetAllFilters();
      updateSummaryStats();
      tableCtrl.setData(records, false);

      window.SimdesApp?.showToast({
        title: 'Data Contoh Dipulihkan',
        message: 'Buku Inventaris dan Kekayaan Desa dikembalikan ke 8 data simulasi awal.',
        variant: 'primary'
      });
    });

    // Print Support (All Filtered Rows)
    const preparePrintView = () => {
      const state = tableCtrl.getState();
      if (tbodyEl) {
        tbodyEl.innerHTML = renderBodyRows(state.allFilteredRows, 0);
      }
      const printMetaEl = document.getElementById('printInventarisMeta');
      if (printMetaEl) {
        try {
          const nowStr = new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
          }).format(new Date());
          printMetaEl.textContent = `Dicetak pada: ${nowStr} • Total: ${state.allFilteredRows.length} jenis barang/bangunan (Data Contoh Simulasi)`;
        } catch (_) {
          printMetaEl.textContent = `Total: ${state.allFilteredRows.length} jenis barang/bangunan (Data Contoh Simulasi)`;
        }
      }
    };

    window.addEventListener('beforeprint', preparePrintView);
    window.addEventListener('afterprint', () => tableCtrl.render());

    printBtn?.addEventListener('click', () => {
      preparePrintView();
      window.print();
    });

    // Boot
    renderConfiguredReservedFormInputs();
    updateSummaryStats();
    tableCtrl.render();
  }

  document.addEventListener('DOMContentLoaded', initBukuInventarisPage);
})();
