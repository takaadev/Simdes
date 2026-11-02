/* ==========================================================================
   SIMDES DESA TIARO — Buku Tanah di Desa (Kode: A.6)
   File: assets/js/buku-tanah-di-desa.js
   Features:
   - 3-level grouped header (rowspan/colspan) preserving exact source labels:
     HM, HGB, HP, HGU, HPL, MA, VI, TN (unexpanded) & SAWAH before TEGALAN
   - Configurable reserve column slots 23–26 without inventing official names
   - 27-input structured form & 8-group detail view with m² formatting
   - CRUD with localStorage persistence, search, hak & penggunaan filters,
     sorting, pagination, auto row numbering, and landscape print
   ========================================================================== */

(() => {
  'use strict';

  const STORAGE_KEY = 'simdes_tiaro_buku_tanah_di_desa_v1';

  /**
   * ========================================================================
   * KONFIGURASI EMPAT KOLOM CADANGAN (KOLOM 23–26) — BUKU TANAH DI DESA (A.6)
   * ========================================================================
   * Kolom 23–26 pada tabel sumber belum diketahui nama resminya.
   * Untuk mengaktifkan sebagai kolom resmi ketika informasi sumber tersedia:
   *   1. Isi `officialLabel` dengan nama resmi kolom.
   *   2. Sesuaikan `inputType` ('number' | 'text' | 'date') bila diperlukan.
   */
  const RESERVED_COLUMNS_CONFIG = {
    defaultVisible: true,
    groupNote: 'Slot Cadangan 23–26 (Belum Diketahui)',
    officialGroupLabel: null,
    columns: [
      { slot: 23, key: 'cadangan_23', tempCode: 'Slot 23', officialLabel: null, inputType: 'text', enabled: true },
      { slot: 24, key: 'cadangan_24', tempCode: 'Slot 24', officialLabel: null, inputType: 'text', enabled: true },
      { slot: 25, key: 'cadangan_25', tempCode: 'Slot 25', officialLabel: null, inputType: 'text', enabled: true },
      { slot: 26, key: 'cadangan_26', tempCode: 'Slot 26', officialLabel: null, inputType: 'text', enabled: true }
    ]
  };

  const HAK_SERTIFIKAT_KEYS = ['hm', 'hgb', 'hp', 'hgu', 'hpl'];
  const HAK_BELUM_KEYS = ['ma', 'vi', 'tn'];
  const NON_PERTANIAN_KEYS = [
    'perumahan',
    'perdaganganJasa',
    'perkantoran',
    'industri',
    'fasilitasUmum'
  ];
  const PERTANIAN_KEYS = [
    'sawah',
    'tegalan',
    'perkebunan',
    'peternakanPerikanan',
    'hutanBelukar',
    'hutanLebatLindung'
  ];
  const LAINNYA_KEYS = ['tanahKosong', 'lainLain'];

  /**
   * Data Contoh Fiktif Simulasi UI (7 entri fiktif, bukan data warga sebenarnya)
   */
  const SEED_DATA = [
    {
      id: 'tdd-sim-001',
      namaPemilik: 'Pemegang Persil Contoh A-01 (Simulasi Perorangan)',
      jumlahLuas: 2450,
      sudahBersertifikat: 2450,
      belumBersertifikat: 0,
      hm: 2450,
      hgb: 0,
      hp: 0,
      hgu: 0,
      hpl: 0,
      ma: 0,
      vi: 0,
      tn: 0,
      perumahan: 450,
      perdaganganJasa: 0,
      perkantoran: 0,
      industri: 0,
      fasilitasUmum: 0,
      sawah: 2000,
      tegalan: 0,
      perkebunan: 0,
      peternakanPerikanan: 0,
      hutanBelukar: 0,
      hutanLebatLindung: 0,
      tanahKosong: 0,
      lainLain: 0,
      mutasi: 'Tetap / Tidak ada perubahan kepemilikan',
      keterangan: 'Data contoh simulasi antarmuka'
    },
    {
      id: 'tdd-sim-002',
      namaPemilik: 'Koperasi Unit Tani Sejahtera (Badan Hukum Simulasi)',
      jumlahLuas: 3800,
      sudahBersertifikat: 3800,
      belumBersertifikat: 0,
      hm: 0,
      hgb: 1200,
      hp: 2600,
      hgu: 0,
      hpl: 0,
      ma: 0,
      vi: 0,
      tn: 0,
      perumahan: 0,
      perdaganganJasa: 800,
      perkantoran: 400,
      industri: 0,
      fasilitasUmum: 0,
      sawah: 0,
      tegalan: 0,
      perkebunan: 2600,
      peternakanPerikanan: 0,
      hutanBelukar: 0,
      hutanLebatLindung: 0,
      tanahKosong: 0,
      lainLain: 0,
      mutasi: 'Pencatatan penggabungan gudang hasil tani (simulasi)',
      keterangan: 'Data contoh badan hukum koperasi'
    },
    {
      id: 'tdd-sim-003',
      namaPemilik: 'Pemegang Persil Contoh B-04 (Simulasi Perorangan)',
      jumlahLuas: 1900,
      sudahBersertifikat: 0,
      belumBersertifikat: 1900,
      hm: 0,
      hgb: 0,
      hp: 0,
      hgu: 0,
      hpl: 0,
      ma: 1900,
      vi: 0,
      tn: 0,
      perumahan: 300,
      perdaganganJasa: 0,
      perkantoran: 0,
      industri: 0,
      fasilitasUmum: 0,
      sawah: 0,
      tegalan: 1100,
      perkebunan: 0,
      peternakanPerikanan: 0,
      hutanBelukar: 0,
      hutanLebatLindung: 0,
      tanahKosong: 500,
      lainLain: 0,
      mutasi: 'Proses pendaftaran pengukuran sporadik (contoh)',
      keterangan: 'Data contoh simulasi UI'
    },
    {
      id: 'tdd-sim-004',
      namaPemilik: 'Yayasan Pendidikan Harapan Desa (Badan Hukum Simulasi)',
      jumlahLuas: 1650,
      sudahBersertifikat: 1650,
      belumBersertifikat: 0,
      hm: 0,
      hgb: 0,
      hp: 1650,
      hgu: 0,
      hpl: 0,
      ma: 0,
      vi: 0,
      tn: 0,
      perumahan: 0,
      perdaganganJasa: 0,
      perkantoran: 250,
      industri: 0,
      fasilitasUmum: 1400,
      sawah: 0,
      tegalan: 0,
      perkebunan: 0,
      peternakanPerikanan: 0,
      hutanBelukar: 0,
      hutanLebatLindung: 0,
      tanahKosong: 0,
      lainLain: 0,
      mutasi: 'Tetap / Digunakan sarana pendidikan masyarakat',
      keterangan: 'Data contoh fasilitas umum pendidikan'
    },
    {
      id: 'tdd-sim-005',
      namaPemilik: 'BUMDes Karya Mandiri Tiaro (Unit Usaha Simulasi)',
      jumlahLuas: 4500,
      sudahBersertifikat: 3000,
      belumBersertifikat: 1500,
      hm: 0,
      hgb: 0,
      hp: 0,
      hgu: 0,
      hpl: 3000,
      ma: 0,
      vi: 0,
      tn: 1500,
      perumahan: 0,
      perdaganganJasa: 500,
      perkantoran: 0,
      industri: 600,
      fasilitasUmum: 0,
      sawah: 1400,
      tegalan: 0,
      perkebunan: 0,
      peternakanPerikanan: 1600,
      hutanBelukar: 0,
      hutanLebatLindung: 0,
      tanahKosong: 0,
      lainLain: 400,
      mutasi: 'Pengembangan kolam perikanan & pengolahan pascapanen (contoh)',
      keterangan: 'Data contoh simulasi UI'
    },
    {
      id: 'tdd-sim-006',
      namaPemilik: 'Pemegang Persil Contoh C-09 (Simulasi Perorangan)',
      jumlahLuas: 3200,
      sudahBersertifikat: 2000,
      belumBersertifikat: 1200,
      hm: 2000,
      hgb: 0,
      hp: 0,
      hgu: 0,
      hpl: 0,
      ma: 1200,
      vi: 0,
      tn: 0,
      perumahan: 350,
      perdaganganJasa: 0,
      perkantoran: 0,
      industri: 0,
      fasilitasUmum: 0,
      sawah: 1250,
      tegalan: 600,
      perkebunan: 700,
      peternakanPerikanan: 0,
      hutanBelukar: 300,
      hutanLebatLindung: 0,
      tanahKosong: 0,
      lainLain: 0,
      mutasi: 'Pemekaran sebagian bidang waris keluarga (simulasi)',
      keterangan: 'Data contoh simulasi UI'
    },
    {
      id: 'tdd-sim-007',
      namaPemilik: 'Kelompok Pengelola Hutan Desa (Lembaga Simulasi)',
      jumlahLuas: 5200,
      sudahBersertifikat: 0,
      belumBersertifikat: 5200,
      hm: 0,
      hgb: 0,
      hp: 0,
      hgu: 0,
      hpl: 0,
      ma: 0,
      vi: 0,
      tn: 5200,
      perumahan: 0,
      perdaganganJasa: 0,
      perkantoran: 0,
      industri: 0,
      fasilitasUmum: 0,
      sawah: 0,
      tegalan: 0,
      perkebunan: 0,
      peternakanPerikanan: 0,
      hutanBelukar: 1400,
      hutanLebatLindung: 3800,
      tanahKosong: 0,
      lainLain: 0,
      mutasi: 'Kawasan penyangga tata air desa (contoh)',
      keterangan: 'Data contoh kawasan konservasi desa'
    }
  ];

  function toNonNegNum(val) {
    const n = Number(val);
    return Number.isFinite(n) && n >= 0 ? n : 0;
  }

  function formatM2(val, withUnit = false) {
    const num = toNonNegNum(val);
    const formatted = num.toLocaleString('id-ID');
    return withUnit ? `${formatted} m²` : formatted;
  }

  function sumKeys(row, keys) {
    return keys.reduce((acc, k) => acc + toNonNegNum(row[k]), 0);
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

  function initBukuTanahDiDesa() {
    const pageRoot = document.getElementById('bukuTanahDesaPageRoot');
    if (!pageRoot) return;

    const esc = window.SimdesApp ? window.SimdesApp.escapeHtml : (s) => String(s ?? '');

    let records = loadRecords();
    let showReservedCols = Boolean(RESERVED_COLUMNS_CONFIG.defaultVisible);
    let activeDetailId = null;
    let pendingDeleteId = null;

    // DOM References
    const statTotalPemilik = document.getElementById('statTotalPemilik');
    const statTotalLuasDesa = document.getElementById('statTotalLuasDesa');
    const statLuasBersertifikat = document.getElementById('statLuasBersertifikat');
    const statLuasPertanian = document.getElementById('statLuasPertanian');
    const printMetaEl = document.getElementById('printTanahDesaMeta');

    const tableEl = document.getElementById('tanahDesaTable');
    const theadEl = document.getElementById('tanahDesaTableHead');
    const tbodyEl = document.getElementById('tanahDesaTableBody');
    const paginationInfoEl = document.getElementById('tablePaginationInfo');
    const paginationListEl = document.getElementById('tablePaginationList');

    const searchInput = document.getElementById('tableSearchInput');
    const clearSearchBtn = document.getElementById('clearSearchInputBtn');
    const filterHakSelect = document.getElementById('filterHakSelect');
    const filterPenggunaanSelect = document.getElementById('filterPenggunaanSelect');
    const pageSizeSelect = document.getElementById('pageSizeSelect');
    const resetFilterBtn = document.getElementById('resetTableFilterBtn');
    const toggleReservedBtn = document.getElementById('toggleReservedColsBtn');
    const resetSampleBtn = document.getElementById('btnResetSampleTanahDesa');

    const btnTambah = document.getElementById('btnTambahTanahDesa');
    const btnCetak = document.getElementById('btnCetakTanahDesa');

    // Form Modal Elements
    const formModalEl = document.getElementById('tanahDesaFormModal');
    const formModalTitle = document.getElementById('tanahDesaFormModalLabel');
    const formEl = document.getElementById('tanahDesaForm');
    const formAlertBox = document.getElementById('tanahDesaFormAlert');
    const formAlertText = document.getElementById('tanahDesaFormAlertText');
    const formSubmitBtn = document.getElementById('tanahDesaFormSubmitBtn');
    const btnAutoSumLuas = document.getElementById('btnAutoSumTanahDesa');

    const fId = document.getElementById('tddRecordId');
    const fNamaPemilik = document.getElementById('tddNamaPemilik');
    const fJumlahLuas = document.getElementById('tddJumlahLuas');
    const fSudahBersertifikat = document.getElementById('tddSudahBersertifikat');
    const fBelumBersertifikat = document.getElementById('tddBelumBersertifikat');

    const fieldMap = {
      hm: document.getElementById('tddHM'),
      hgb: document.getElementById('tddHGB'),
      hp: document.getElementById('tddHP'),
      hgu: document.getElementById('tddHGU'),
      hpl: document.getElementById('tddHPL'),
      ma: document.getElementById('tddMA'),
      vi: document.getElementById('tddVI'),
      tn: document.getElementById('tddTN'),
      perumahan: document.getElementById('tddPerumahan'),
      perdaganganJasa: document.getElementById('tddPerdaganganJasa'),
      perkantoran: document.getElementById('tddPerkantoran'),
      industri: document.getElementById('tddIndustri'),
      fasilitasUmum: document.getElementById('tddFasilitasUmum'),
      sawah: document.getElementById('tddSawah'),
      tegalan: document.getElementById('tddTegalan'),
      perkebunan: document.getElementById('tddPerkebunan'),
      peternakanPerikanan: document.getElementById('tddPeternakanPerikanan'),
      hutanBelukar: document.getElementById('tddHutanBelukar'),
      hutanLebatLindung: document.getElementById('tddHutanLebatLindung'),
      tanahKosong: document.getElementById('tddTanahKosong'),
      lainLain: document.getElementById('tddLainLain')
    };

    const fMutasi = document.getElementById('tddMutasi');
    const fKeterangan = document.getElementById('tddKeterangan');

    const dynamicReservedWrap = document.getElementById('dynamicReservedFieldsWrap');
    const dynamicReservedGrid = document.getElementById('dynamicReservedFieldsGrid');

    // Detail & Delete Modals
    const detailModalEl = document.getElementById('tanahDesaDetailModal');
    const btnEditFromDetail = document.getElementById('btnEditTanahDesaFromDetail');
    const deleteModalEl = document.getElementById('tanahDesaDeleteModal');
    const deleteTargetNameEl = document.getElementById('deleteTanahDesaTargetName');
    const deleteTargetMetaEl = document.getElementById('deleteTanahDesaTargetMeta');
    const confirmDeleteBtn = document.getElementById('confirmDeleteTanahDesaBtn');

    function getActiveReservedColumns() {
      if (!showReservedCols) return [];
      return RESERVED_COLUMNS_CONFIG.columns.filter((col) => col.enabled !== false);
    }

    function getConfiguredReservedColumns() {
      return RESERVED_COLUMNS_CONFIG.columns.filter(
        (col) => col.enabled !== false && Boolean(col.officialLabel)
      );
    }

    function getTotalColumnCount() {
      // 26 known leaf columns + active reserve columns (4 by default: 23–26)
      return 26 + getActiveReservedColumns().length;
    }

    function renderConfiguredReservedInputs() {
      const configured = getConfiguredReservedColumns();
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
          <div class="col-12 col-sm-6 col-lg-3">
            <div class="simdes-field mb-2">
              <label for="tddSlot_${esc(col.key)}" class="simdes-label">${esc(col.officialLabel)}</label>
              <input
                type="${esc(col.inputType || 'text')}"
                id="tddSlot_${esc(col.key)}"
                name="${esc(col.key)}"
                class="simdes-input"
                data-reserved-key="${esc(col.key)}"
                data-field-label="${esc(col.officialLabel)}"
              >
            </div>
          </div>
        `
        )
        .join('');
    }

    function buildSortBtnHtml(sortKey, labelText, currentSortKey, currentSortDir, alignStart = false) {
      const isSorted = currentSortKey === sortKey;
      const iconClass = !isSorted
        ? 'fa-sort'
        : currentSortDir === 'asc'
          ? 'fa-sort-up'
          : 'fa-sort-down';
      return `
        <button type="button"
                class="th-sort-btn${isSorted ? ' is-sorted' : ''}${alignStart ? '' : ' justify-content-center'}"
                data-sort-key="${esc(sortKey)}"
                aria-label="Urutkan berdasarkan ${esc(labelText)}">
          <span>${esc(labelText)}</span>
          <i class="fa-solid ${iconClass} th-sort-icon no-print" aria-hidden="true"></i>
        </button>
      `;
    }

    /**
     * Render 3-Row Grouped Header (HTML rowspan & colspan)
     * Preserving HM, HGB, HP, HGU, HPL, MA, VI, TN verbatim and SAWAH before TEGALAN
     */
    function renderTableHeader(state) {
      if (!theadEl) return;

      const reservedCols = getActiveReservedColumns();
      const hasReserved = reservedCols.length > 0;
      const allOfficial = hasReserved && reservedCols.every((c) => Boolean(c.officialLabel));

      tableEl?.classList.toggle('compact-no-reserved', !hasReserved);

      const ariaSortFor = (key) => {
        if (state.sortKey !== key) return 'none';
        return state.sortDirection === 'asc' ? 'ascending' : 'descending';
      };

      const reservedTopGroupHtml = hasReserved
        ? `
          <th scope="colgroup"
              colspan="${reservedCols.length}"
              class="${allOfficial ? 'th-group-top group-divider-end' : 'th-placeholder-group'}">
            ${
              RESERVED_COLUMNS_CONFIG.officialGroupLabel
                ? esc(RESERVED_COLUMNS_CONFIG.officialGroupLabel)
                : `<span class="d-block">${esc(RESERVED_COLUMNS_CONFIG.groupNote)}</span>`
            }
          </th>
        `
        : '';

      const reservedSubRow2Html = hasReserved
        ? reservedCols
            .map((col, idx) => {
              const isLast = idx === reservedCols.length - 1;
              if (col.officialLabel) {
                return `
                  <th scope="col" rowspan="2" class="${isLast ? 'group-divider-end' : ''}">
                    ${esc(col.officialLabel)}
                  </th>
                `;
              }
              return `
                <th scope="col"
                    rowspan="2"
                    class="th-placeholder-col${isLast ? ' last-placeholder-col' : ''}"
                    title="Posisi cadangan kolom ${esc(String(col.slot))} — nama resmi belum tersedia pada sumber">
                  <span class="placeholder-slot-code">${esc(col.tempCode)}</span>
                  <span class="placeholder-slot-hint">Belum diketahui</span>
                </th>
              `;
            })
            .join('')
        : '';

      theadEl.innerHTML = `
        <tr>
          <th scope="col" rowspan="3" class="col-no-urut sticky-col-first group-divider-end" style="min-inline-size:62px;">
            NOMOR URUT
          </th>
          <th scope="col" rowspan="3" class="text-start group-divider-end" style="min-inline-size:210px;" aria-sort="${ariaSortFor('namaPemilik')}">
            ${buildSortBtnHtml('namaPemilik', 'NAMA PER-ORANGAN / BADAN HUKUM', state.sortKey, state.sortDirection, true)}
          </th>
          <th scope="col" rowspan="3" class="group-divider-end" style="min-inline-size:105px;" aria-sort="${ariaSortFor('jumlahLuas')}">
            ${buildSortBtnHtml('jumlahLuas', 'JML (M²)', state.sortKey, state.sortDirection)}
          </th>
          <th scope="colgroup" colspan="8" class="th-group-top group-divider-end">
            STATUS HAK TANAH (M²)
          </th>
          <th scope="colgroup" colspan="13" class="th-group-top group-divider-end">
            PENGGUNAAN TANAH (M²)
          </th>
          <th scope="col" rowspan="3" class="group-divider-end" style="min-inline-size:155px;">
            MUTASI TANAH DI DESA
          </th>
          ${reservedTopGroupHtml}
          <th scope="col" rowspan="3" style="min-inline-size:165px;">
            KET.
          </th>
        </tr>
        <tr>
          <th scope="colgroup" colspan="5" class="th-group-sub">SUDAH BERSERTIFIKAT</th>
          <th scope="colgroup" colspan="3" class="th-group-sub group-divider-end">BELUM BERSERTIFIKAT</th>
          <th scope="colgroup" colspan="5" class="th-group-sub">NON PERTANIAN</th>
          <th scope="colgroup" colspan="6" class="th-group-sub">PERTANIAN</th>
          <th scope="col" rowspan="2" style="min-inline-size:88px;">TANAH KOSONG</th>
          <th scope="col" rowspan="2" class="group-divider-end" style="min-inline-size:82px;">LAIN-LAIN</th>
          ${reservedSubRow2Html}
        </tr>
        <tr>
          <th scope="col" style="min-inline-size:68px;">HM</th>
          <th scope="col" style="min-inline-size:68px;">HGB</th>
          <th scope="col" style="min-inline-size:68px;">HP</th>
          <th scope="col" style="min-inline-size:68px;">HGU</th>
          <th scope="col" style="min-inline-size:68px;">HPL</th>
          <th scope="col" style="min-inline-size:68px;">MA</th>
          <th scope="col" style="min-inline-size:68px;">VI</th>
          <th scope="col" class="group-divider-end" style="min-inline-size:68px;">TN</th>
          <th scope="col" style="min-inline-size:90px;">PERUMAHAN</th>
          <th scope="col" style="min-inline-size:108px;">PERDAGANGAN DAN JASA</th>
          <th scope="col" style="min-inline-size:96px;">PERKANTORAN</th>
          <th scope="col" style="min-inline-size:82px;">INDUSTRI</th>
          <th scope="col" style="min-inline-size:92px;">FASILITAS UMUM</th>
          <th scope="col" style="min-inline-size:78px;">SAWAH</th>
          <th scope="col" style="min-inline-size:82px;">TEGALAN</th>
          <th scope="col" style="min-inline-size:96px;">PERKEBUNAN</th>
          <th scope="col" style="min-inline-size:105px;">PETERNAKAN/PERIKANAN</th>
          <th scope="col" style="min-inline-size:92px;">HUTAN BELUKAR</th>
          <th scope="col" style="min-inline-size:105px;">HUTAN LEBAT/LINDUNG</th>
        </tr>
      `;

      theadEl.querySelectorAll('[data-sort-key]').forEach((btn) => {
        btn.addEventListener('click', () => {
          tableCtrl.toggleSort(btn.dataset.sortKey);
        });
      });
    }

    function renderAreaCell(val, extraClass = '') {
      const n = toNonNegNum(val);
      const cls = ['td-num', n === 0 ? 'is-zero' : '', extraClass].filter(Boolean).join(' ');
      return `<td class="${cls}">${n === 0 ? '&ndash;' : esc(formatM2(n))}</td>`;
    }

    function getEffectiveSudahBersertifikat(r) {
      const sumCodes = sumKeys(r, HAK_SERTIFIKAT_KEYS);
      return toNonNegNum(r.sudahBersertifikat) || sumCodes;
    }

    function getEffectiveBelumBersertifikat(r) {
      const sumCodes = sumKeys(r, HAK_BELUM_KEYS);
      return toNonNegNum(r.belumBersertifikat) || sumCodes;
    }

    function updateSummaryStats() {
      const totalPemilik = records.length;
      const totalLuas = records.reduce((acc, r) => acc + toNonNegNum(r.jumlahLuas), 0);
      const luasSertifikat = records.reduce((acc, r) => acc + getEffectiveSudahBersertifikat(r), 0);
      const luasPertanian = records.reduce((acc, r) => acc + sumKeys(r, PERTANIAN_KEYS), 0);

      if (statTotalPemilik) statTotalPemilik.textContent = totalPemilik.toLocaleString('id-ID');
      if (statTotalLuasDesa) statTotalLuasDesa.textContent = formatM2(totalLuas, true);
      if (statLuasBersertifikat) statLuasBersertifikat.textContent = formatM2(luasSertifikat, true);
      if (statLuasPertanian) statLuasPertanian.textContent = formatM2(luasPertanian, true);

      if (printMetaEl) {
        const nowStr = new Intl.DateTimeFormat('id-ID', {
          day: 'numeric',
          month: 'long',
          year: 'numeric'
        }).format(new Date());
        printMetaEl.textContent = `Total tercatat: ${totalPemilik} subjek kepemilikan (${formatM2(totalLuas, true)}) • Dicetak pada ${nowStr}`;
      }
    }

    const tableCtrl = window.SimdesTable.create({
      data: records,
      pageSize: 5,
      defaultSortKey: 'namaPemilik',
      defaultSortDirection: 'asc',
      searchFields: ['namaPemilik', 'mutasi', 'keterangan'],
      comparators: {
        jumlahLuas: (a, b) => toNonNegNum(a.jumlahLuas) - toNonNegNum(b.jumlahLuas)
      },
      onRender: (state) => {
        renderTableHeader(state);

        const reservedCols = getActiveReservedColumns();
        const colSpanTotal = getTotalColumnCount();

        if (resetFilterBtn) {
          resetFilterBtn.disabled = !state.hasActiveQuery;
        }

        if (state.rows.length === 0) {
          tbodyEl.innerHTML = `
            <tr>
              <td colspan="${colSpanTotal}" class="p-0">
                <div class="table-empty-state">
                  <div class="table-empty-icon">
                    <i class="fa-solid fa-layer-group" aria-hidden="true"></i>
                  </div>
                  <div class="fw-bold mb-1" style="color:var(--simdes-text); font-size:0.95rem;">
                    ${state.hasActiveQuery ? 'Data tanah di desa tidak ditemukan' : 'Belum ada data tanah di desa'}
                  </div>
                  <div class="mb-3" style="font-size:0.82rem;">
                    ${
                      state.hasActiveQuery
                        ? 'Tidak ada data pemilik atau kategori penggunaan tanah yang sesuai dengan filter.'
                        : 'Silakan tambahkan data tanah di desa menggunakan tombol Tambah Data Tanah.'
                    }
                  </div>
                  ${
                    state.hasActiveQuery
                      ? `<button type="button" class="btn-simdes-outline" id="emptyResetTanahDesaBtn">
                           <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                           <span>Reset Pencarian &amp; Filter</span>
                         </button>`
                      : ''
                  }
                </div>
              </td>
            </tr>
          `;
          document.getElementById('emptyResetTanahDesaBtn')?.addEventListener('click', handleResetAllFilters);
        } else {
          tbodyEl.innerHTML = state.rows
            .map((row, idx) => {
              const noUrut = state.startIndex + idx + 1;

              const reservedCellsHtml = reservedCols
                .map((col, cIdx) => {
                  const isLast = cIdx === reservedCols.length - 1;
                  if (col.officialLabel) {
                    const v = row[col.key] ?? '';
                    return `<td class="${isLast ? 'group-divider-end' : ''}">${v ? esc(v) : '<span class="text-simdes-muted">&ndash;</span>'}</td>`;
                  }
                  return `<td class="td-placeholder-col${isLast ? ' last-placeholder-col' : ''}" aria-label="Kolom cadangan ${esc(String(col.slot))} belum aktif">&mdash;</td>`;
                })
                .join('');

              return `
                <tr>
                  <td class="col-no-urut sticky-col-first group-divider-end">${noUrut}</td>
                  <td class="group-divider-end">
                    <button type="button"
                            class="tentang-title-btn"
                            data-row-action="detail"
                            data-id="${esc(row.id)}"
                            title="Lihat rincian kepemilikan &amp; penggunaan tanah">
                      ${esc(row.namaPemilik)}
                    </button>
                  </td>
                  <td class="td-num is-good group-divider-end">${esc(formatM2(row.jumlahLuas))}</td>
                  ${renderAreaCell(row.hm)}
                  ${renderAreaCell(row.hgb)}
                  ${renderAreaCell(row.hp)}
                  ${renderAreaCell(row.hgu)}
                  ${renderAreaCell(row.hpl)}
                  ${renderAreaCell(row.ma)}
                  ${renderAreaCell(row.vi)}
                  ${renderAreaCell(row.tn, 'group-divider-end')}
                  ${renderAreaCell(row.perumahan)}
                  ${renderAreaCell(row.perdaganganJasa)}
                  ${renderAreaCell(row.perkantoran)}
                  ${renderAreaCell(row.industri)}
                  ${renderAreaCell(row.fasilitasUmum)}
                  ${renderAreaCell(row.sawah)}
                  ${renderAreaCell(row.tegalan)}
                  ${renderAreaCell(row.perkebunan)}
                  ${renderAreaCell(row.peternakanPerikanan)}
                  ${renderAreaCell(row.hutanBelukar)}
                  ${renderAreaCell(row.hutanLebatLindung)}
                  ${renderAreaCell(row.tanahKosong)}
                  ${renderAreaCell(row.lainLain, 'group-divider-end')}
                  <td class="group-divider-end">
                    ${row.mutasi ? esc(row.mutasi) : '<span class="text-simdes-muted">&ndash;</span>'}
                  </td>
                  ${reservedCellsHtml}
                  <td>
                    <div class="ket-cell-content">
                      <div class="ket-text">
                        ${row.keterangan ? esc(row.keterangan) : '<span class="text-simdes-muted">&ndash;</span>'}
                      </div>
                      <div class="row-action-group no-print" role="group" aria-label="Aksi baris ${noUrut}">
                        <button type="button"
                                class="btn-row-action"
                                data-row-action="detail"
                                data-id="${esc(row.id)}"
                                title="Lihat Detail"
                                aria-label="Lihat detail ${esc(row.namaPemilik)}">
                          <i class="fa-regular fa-eye" aria-hidden="true"></i>
                        </button>
                        <button type="button"
                                class="btn-row-action"
                                data-row-action="edit"
                                data-id="${esc(row.id)}"
                                title="Edit Data"
                                aria-label="Edit data ${esc(row.namaPemilik)}">
                          <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
                        </button>
                        <button type="button"
                                class="btn-row-action action-delete"
                                data-row-action="delete"
                                data-id="${esc(row.id)}"
                                title="Hapus Data"
                                aria-label="Hapus data ${esc(row.namaPemilik)}">
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

        if (paginationInfoEl) {
          paginationInfoEl.innerHTML =
            state.totalItems === 0
              ? 'Menampilkan <strong>0</strong> dari <strong>0</strong> data tanah di desa'
              : `Menampilkan <strong>${state.startEntry}&ndash;${state.endEntry}</strong> dari <strong>${state.totalItems}</strong> data tanah${
                  state.hasActiveQuery ? ` (disaring dari total ${state.totalAllItems} data)` : ''
                }`;
        }

        window.SimdesTable.renderPagination(paginationListEl, state, (page) => {
          tableCtrl.setPage(page);
        });
      }
    });

    // Search & Filters
    function handleResetAllFilters() {
      if (searchInput) searchInput.value = '';
      clearSearchBtn?.classList.remove('visible');
      if (filterHakSelect) filterHakSelect.value = 'ALL';
      if (filterPenggunaanSelect) filterPenggunaanSelect.value = 'ALL';
      tableCtrl.resetAll();
    }

    searchInput?.addEventListener('input', () => {
      const val = searchInput.value;
      clearSearchBtn?.classList.toggle('visible', val.length > 0);
      tableCtrl.setSearch(val);
    });

    clearSearchBtn?.addEventListener('click', () => {
      if (!searchInput) return;
      searchInput.value = '';
      clearSearchBtn.classList.remove('visible');
      tableCtrl.setSearch('');
      searchInput.focus();
    });

    filterHakSelect?.addEventListener('change', () => {
      const val = filterHakSelect.value;
      if (val === 'ALL') {
        tableCtrl.setFilter('hakFilter', null);
      } else if (val === 'GROUP_SUDAH') {
        tableCtrl.setFilter('hakFilter', (_v, r) => getEffectiveSudahBersertifikat(r) > 0);
      } else if (val === 'GROUP_BELUM') {
        tableCtrl.setFilter('hakFilter', (_v, r) => getEffectiveBelumBersertifikat(r) > 0);
      } else {
        tableCtrl.setFilter('hakFilter', (_v, r) => toNonNegNum(r[val]) > 0);
      }
    });

    filterPenggunaanSelect?.addEventListener('change', () => {
      const val = filterPenggunaanSelect.value;
      if (val === 'ALL') {
        tableCtrl.setFilter('gunaFilter', null);
      } else if (val === 'GROUP_NON_PERTANIAN') {
        tableCtrl.setFilter('gunaFilter', (_v, r) => sumKeys(r, NON_PERTANIAN_KEYS) > 0);
      } else if (val === 'GROUP_PERTANIAN') {
        tableCtrl.setFilter('gunaFilter', (_v, r) => sumKeys(r, PERTANIAN_KEYS) > 0);
      } else if (val === 'GROUP_LAINNYA') {
        tableCtrl.setFilter('gunaFilter', (_v, r) => sumKeys(r, LAINNYA_KEYS) > 0);
      } else {
        tableCtrl.setFilter('gunaFilter', (_v, r) => toNonNegNum(r[val]) > 0);
      }
    });

    pageSizeSelect?.addEventListener('change', () => {
      tableCtrl.setPageSize(Number(pageSizeSelect.value));
    });

    resetFilterBtn?.addEventListener('click', handleResetAllFilters);

    function syncReservedToggleBtnUI() {
      if (!toggleReservedBtn) return;
      toggleReservedBtn.setAttribute('aria-pressed', String(showReservedCols));
      const span = toggleReservedBtn.querySelector('span');
      if (span) {
        span.textContent = showReservedCols ? 'Kolom 23–26: Tampil' : 'Kolom 23–26: Sembunyi';
      }
    }

    toggleReservedBtn?.addEventListener('click', () => {
      showReservedCols = !showReservedCols;
      syncReservedToggleBtnUI();
      tableCtrl.render();
    });

    // Helper: compute totals from code inputs when operator clicks helper button
    btnAutoSumLuas?.addEventListener('click', () => {
      const sumSudah = HAK_SERTIFIKAT_KEYS.reduce((a, k) => a + toNonNegNum(fieldMap[k]?.value), 0);
      const sumBelum = HAK_BELUM_KEYS.reduce((a, k) => a + toNonNegNum(fieldMap[k]?.value), 0);
      const sumGuna = [
        ...NON_PERTANIAN_KEYS,
        ...PERTANIAN_KEYS,
        ...LAINNYA_KEYS
      ].reduce((a, k) => a + toNonNegNum(fieldMap[k]?.value), 0);

      if (fSudahBersertifikat) fSudahBersertifikat.value = String(sumSudah);
      if (fBelumBersertifikat) fBelumBersertifikat.value = String(sumBelum);

      const bestTotal = Math.max(sumSudah + sumBelum, sumGuna);
      if (bestTotal > 0 && fJumlahLuas) {
        fJumlahLuas.value = String(bestTotal);
        fJumlahLuas.dispatchEvent(new Event('input', { bubbles: true }));
      }
    });

    // Form Modal Helpers
    function hideFormBanner() {
      formAlertBox?.classList.remove('show');
    }

    function showFormBanner(msg) {
      if (!formAlertBox || !formAlertText) return;
      formAlertText.textContent = msg;
      formAlertBox.classList.add('show');
    }

    function openCreateModal() {
      if (!formEl || !formModalEl) return;
      formEl.reset();
      fId.value = '';
      hideFormBanner();

      if (formModalTitle) {
        formModalTitle.innerHTML =
          '<i class="fa-solid fa-plus-circle text-simdes-primary me-2" aria-hidden="true"></i>Tambah Data Tanah di Desa';
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML =
          '<i class="fa-solid fa-floppy-disk" aria-hidden="true"></i><span>Simpan Data Tanah</span>';
      }

      window.bootstrap?.Modal.getOrCreateInstance(formModalEl).show();
      formModalEl.addEventListener('shown.bs.modal', () => fNamaPemilik?.focus(), { once: true });
    }

    function openEditModal(id) {
      const item = records.find((r) => r.id === id);
      if (!item || !formEl || !formModalEl) return;

      formEl.reset();
      hideFormBanner();

      fId.value = item.id;
      fNamaPemilik.value = item.namaPemilik || '';
      fJumlahLuas.value = toNonNegNum(item.jumlahLuas);
      fSudahBersertifikat.value = toNonNegNum(item.sudahBersertifikat);
      fBelumBersertifikat.value = toNonNegNum(item.belumBersertifikat);

      Object.entries(fieldMap).forEach(([key, input]) => {
        if (input) input.value = toNonNegNum(item[key]);
      });

      fMutasi.value = item.mutasi || '';
      fKeterangan.value = item.keterangan || '';

      getConfiguredReservedColumns().forEach((col) => {
        const slotInput = document.getElementById(`tddSlot_${col.key}`);
        if (slotInput) slotInput.value = item[col.key] ?? '';
      });

      if (formModalTitle) {
        formModalTitle.innerHTML =
          '<i class="fa-regular fa-pen-to-square text-simdes-primary me-2" aria-hidden="true"></i>Edit Data Tanah di Desa';
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML =
          '<i class="fa-solid fa-check" aria-hidden="true"></i><span>Simpan Perubahan</span>';
      }

      window.bootstrap?.Modal.getOrCreateInstance(formModalEl).show();
      formModalEl.addEventListener('shown.bs.modal', () => fNamaPemilik?.focus(), { once: true });
    }

    function openDetailModal(id) {
      const item = records.find((r) => r.id === id);
      if (!item || !detailModalEl) return;
      activeDetailId = item.id;

      const setText = (elId, val) => {
        const el = document.getElementById(elId);
        if (el) el.textContent = val;
      };

      setText('detTddNamaPemilik', item.namaPemilik || '-');
      setText('detTddJumlahLuas', formatM2(item.jumlahLuas, true));

      setText('detTddSudahBersertifikat', formatM2(getEffectiveSudahBersertifikat(item), true));
      setText('detTddBelumBersertifikat', formatM2(getEffectiveBelumBersertifikat(item), true));
      setText('detTddHM', formatM2(item.hm, true));
      setText('detTddHGB', formatM2(item.hgb, true));
      setText('detTddHP', formatM2(item.hp, true));
      setText('detTddHGU', formatM2(item.hgu, true));
      setText('detTddHPL', formatM2(item.hpl, true));
      setText('detTddMA', formatM2(item.ma, true));
      setText('detTddVI', formatM2(item.vi, true));
      setText('detTddTN', formatM2(item.tn, true));

      setText('detTddPerumahan', formatM2(item.perumahan, true));
      setText('detTddPerdaganganJasa', formatM2(item.perdaganganJasa, true));
      setText('detTddPerkantoran', formatM2(item.perkantoran, true));
      setText('detTddIndustri', formatM2(item.industri, true));
      setText('detTddFasilitasUmum', formatM2(item.fasilitasUmum, true));

      setText('detTddSawah', formatM2(item.sawah, true));
      setText('detTddTegalan', formatM2(item.tegalan, true));
      setText('detTddPerkebunan', formatM2(item.perkebunan, true));
      setText('detTddPeternakanPerikanan', formatM2(item.peternakanPerikanan, true));
      setText('detTddHutanBelukar', formatM2(item.hutanBelukar, true));
      setText('detTddHutanLebatLindung', formatM2(item.hutanLebatLindung, true));

      setText('detTddTanahKosong', formatM2(item.tanahKosong, true));
      setText('detTddLainLain', formatM2(item.lainLain, true));

      setText('detTddMutasi', item.mutasi || 'Tidak ada catatan mutasi');
      setText('detTddKeterangan', item.keterangan || 'Tidak ada keterangan tambahan');

      window.bootstrap?.Modal.getOrCreateInstance(detailModalEl).show();
    }

    function openDeleteModal(id) {
      const item = records.find((r) => r.id === id);
      if (!item || !deleteModalEl) return;
      pendingDeleteId = item.id;

      if (deleteTargetNameEl) deleteTargetNameEl.textContent = item.namaPemilik;
      if (deleteTargetMetaEl) {
        deleteTargetMetaEl.textContent = `Jumlah Luas: ${formatM2(item.jumlahLuas, true)}`;
      }

      window.bootstrap?.Modal.getOrCreateInstance(deleteModalEl).show();
    }

    if (formEl && window.SimdesForms?.InteractionValidator) {
      renderConfiguredReservedInputs();
      window.SimdesForms.InteractionValidator.bindForm(formEl);

      formEl.addEventListener('submit', (e) => {
        e.preventDefault();
        hideFormBanner();

        const validBase = window.SimdesForms.InteractionValidator.validateAll(formEl);
        if (!validBase) {
          showFormBanner('Mohon periksa kembali kolom wajib Nama Pemilik dan Jumlah Luas Tanah.');
          return;
        }

        const jmlLuasVal = Number(fJumlahLuas.value);
        if (!Number.isFinite(jmlLuasVal) || jmlLuasVal <= 0) {
          window.SimdesForms.InteractionValidator.syncFieldState(
            fJumlahLuas,
            'Jumlah luas tanah harus lebih besar dari 0 m².'
          );
          fJumlahLuas.focus();
          showFormBanner('Jumlah luas tanah (m²) harus berupa angka lebih dari 0.');
          return;
        }

        const existingId = fId.value.trim();

        const numericValues = {};
        Object.entries(fieldMap).forEach(([key, input]) => {
          numericValues[key] = toNonNegNum(input?.value);
        });

        const autoSudah = HAK_SERTIFIKAT_KEYS.reduce((a, k) => a + numericValues[k], 0);
        const autoBelum = HAK_BELUM_KEYS.reduce((a, k) => a + numericValues[k], 0);
        const rawSudah = toNonNegNum(fSudahBersertifikat.value);
        const rawBelum = toNonNegNum(fBelumBersertifikat.value);

        const payload = {
          id: existingId || `tdd-${Date.now()}`,
          namaPemilik: fNamaPemilik.value.trim(),
          jumlahLuas: jmlLuasVal,
          sudahBersertifikat: rawSudah > 0 ? rawSudah : autoSudah,
          belumBersertifikat: rawBelum > 0 ? rawBelum : autoBelum,
          ...numericValues,
          mutasi: fMutasi.value.trim(),
          keterangan: fKeterangan.value.trim()
        };

        getConfiguredReservedColumns().forEach((col) => {
          const slotInput = document.getElementById(`tddSlot_${col.key}`);
          if (slotInput) payload[col.key] = slotInput.value.trim();
        });

        if (existingId) {
          records = records.map((r) => (r.id === existingId ? { ...r, ...payload } : r));
        } else {
          records = [payload, ...records];
        }

        saveRecords(records);
        updateSummaryStats();
        tableCtrl.setData(records, Boolean(existingId));

        window.bootstrap?.Modal.getOrCreateInstance(formModalEl).hide();
        window.SimdesApp?.showToast({
          title: existingId ? 'Data Tanah Diperbarui' : 'Data Tanah Ditambahkan',
          message: `Data tanah "${payload.namaPemilik}" berhasil disimpan.`,
          variant: 'success'
        });
      });
    }

    tbodyEl?.addEventListener('click', (e) => {
      const btn = e.target.closest('[data-row-action]');
      if (!btn) return;
      const action = btn.getAttribute('data-row-action');
      const id = btn.getAttribute('data-id');
      if (!id) return;

      if (action === 'detail') openDetailModal(id);
      else if (action === 'edit') openEditModal(id);
      else if (action === 'delete') openDeleteModal(id);
    });

    btnTambah?.addEventListener('click', openCreateModal);

    btnEditFromDetail?.addEventListener('click', () => {
      if (!activeDetailId) return;
      window.bootstrap?.Modal.getOrCreateInstance(detailModalEl)?.hide();
      window.setTimeout(() => openEditModal(activeDetailId), 180);
    });

    confirmDeleteBtn?.addEventListener('click', () => {
      if (!pendingDeleteId) return;
      const target = records.find((r) => r.id === pendingDeleteId);
      records = records.filter((r) => r.id !== pendingDeleteId);
      pendingDeleteId = null;

      saveRecords(records);
      updateSummaryStats();
      tableCtrl.setData(records, true);

      window.bootstrap?.Modal.getOrCreateInstance(deleteModalEl)?.hide();
      window.SimdesApp?.showToast({
        title: 'Data Tanah Dihapus',
        message: target
          ? `Data "${target.namaPemilik}" telah dihapus dari Buku Tanah di Desa.`
          : 'Data berhasil dihapus.',
        variant: 'warning'
      });
    });

    resetSampleBtn?.addEventListener('click', () => {
      records = cloneSeedData();
      saveRecords(records);
      handleResetAllFilters();
      updateSummaryStats();
      tableCtrl.setData(records, false);
      window.SimdesApp?.showToast({
        title: 'Data Contoh Dipulihkan',
        message: '7 data contoh simulasi Buku Tanah di Desa berhasil dimuat ulang.',
        variant: 'primary'
      });
    });

    btnCetak?.addEventListener('click', () => {
      window.print();
    });

    // Initial Boot
    syncReservedToggleBtnUI();
    updateSummaryStats();
    tableCtrl.render();
  }

  document.addEventListener('DOMContentLoaded', initBukuTanahDiDesa);
})();

