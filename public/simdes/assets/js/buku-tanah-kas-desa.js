/* ==========================================================================
   SIMDES DESA TIARO — Buku Tanah Kas Desa (Kode: A.5)
   File: assets/js/buku-tanah-kas-desa.js
   Features:
   - 3-level grouped header (rowspan/colspan) preserving 24 source column positions
   - Configurable reserve column slots (14–24) without inventing official names
   - Conditional form input: Asal Bantuan shown only when Asal Tanah === 'Bantuan'
   - CRUD with localStorage persistence, search, multi-filter, sort, pagination, print
   ========================================================================== */

(() => {
  'use strict';

  const STORAGE_KEY = 'simdes_tiaro_buku_tanah_kas_desa_v1';

  /**
   * ========================================================================
   * KONFIGURASI POSISI KOLOM CADANGAN (14–24) — BUKU TANAH KAS DESA (A.5)
   * ========================================================================
   * Catatan Struktur Sumber:
   * - 13 kelompok judul utama yang diketahui apabila dijabarkan seluruh subkolom
   *   resminya menghasilkan 23 kolom daun (leaf columns):
   *     [1] Nomor Urut (1)
   *     [2–6] Asal Tanah Kas Desa: Asli Milik Desa, Pemerintah, Provinsi, Kab/Kota, Lain-lain (5)
   *     [7] Nomor Sertifikat Buku Letter C/Persil (1)
   *     [8] Luas (m²) (1)
   *     [9] Kelas (1)
   *     [10] Perolehan Tanah Kas Desa: Tanggal Perolehan (1)
   *     [11–15] Jenis Tanah Kas Desa: Sawah, Tegal, Kebun, Tambak/Kolam, Tanah Kering/Darat (5)
   *     [16–17] Patok Tanda Batas: Ada, Tidak Ada (2)
   *     [18–19] Papan Nama: Ada, Tidak Ada (2)
   *     [20] Lokasi (1)
   *     [21] Peruntukkan (1)
   *     [22] Mutasi (1)
   *     [24] KET (1)
   * - Untuk mempertahankan tepat 24 posisi kolom fisik sesuai penomoran sumber
   *   tanpa mengarang label resmi yang belum dapat dipastikan, 1 slot cadangan
   *   (`slot: 24`) diaktifkan secara bawaan sehingga tabel memiliki tepat 24 kolom fisik.
   * - Seluruh definisi slot 14–24 tetap disediakan di bawah ini agar mudah diberi
   *   `officialLabel` dan `enabled: true` apabila rincian tabel sumber ditambahkan kemudian.
   */
  const RESERVED_COLUMNS_CONFIG = {
    defaultVisible: true,
    groupNote: 'Posisi Kolom Cadangan (Belum Tersedia)',
    officialGroupLabel: null,
    columns: [
      { slot: 14, key: 'cadangan_14', tempCode: 'Slot 14', officialLabel: null, inputType: 'text', enabled: false },
      { slot: 15, key: 'cadangan_15', tempCode: 'Slot 15', officialLabel: null, inputType: 'text', enabled: false },
      { slot: 16, key: 'cadangan_16', tempCode: 'Slot 16', officialLabel: null, inputType: 'text', enabled: false },
      { slot: 17, key: 'cadangan_17', tempCode: 'Slot 17', officialLabel: null, inputType: 'text', enabled: false },
      { slot: 18, key: 'cadangan_18', tempCode: 'Slot 18', officialLabel: null, inputType: 'text', enabled: false },
      { slot: 19, key: 'cadangan_19', tempCode: 'Slot 19', officialLabel: null, inputType: 'text', enabled: false },
      { slot: 20, key: 'cadangan_20', tempCode: 'Slot 20', officialLabel: null, inputType: 'text', enabled: false },
      { slot: 21, key: 'cadangan_21', tempCode: 'Slot 21', officialLabel: null, inputType: 'text', enabled: false },
      { slot: 22, key: 'cadangan_22', tempCode: 'Slot 22', officialLabel: null, inputType: 'text', enabled: false },
      { slot: 23, key: 'cadangan_23', tempCode: 'Slot 23', officialLabel: null, inputType: 'text', enabled: false },
      { slot: 24, key: 'cadangan_24', tempCode: 'Pos. Cadangan #24', officialLabel: null, inputType: 'text', enabled: true }
    ]
  };

  /**
   * Data Contoh Fiktif Simulasi UI (7 bidang tanah contoh)
   * Tidak menggunakan nomor sertifikat atau data tanah resmi Desa Tiaro.
   */
  const SEED_DATA = [
    {
      id: 'tkd-sim-001',
      nomorSertifikat: 'SIM-C.0101 / Persil 04.A (Contoh)',
      luas: 1850,
      kelas: 'D.I',
      asalTanah: 'Asli Milik Desa',
      asalBantuan: '',
      tanggalPerolehan: '2016-03-15',
      jenisTanah: 'Tanah Kering/Darat',
      patokBatas: 'Ada',
      papanNama: 'Ada',
      lokasi: 'Blok Poros Utama Dusun I (Simulasi)',
      peruntukkan: 'Lahan Kantor & Balai Pelayanan Desa',
      mutasi: 'Tetap / Tidak ada mutasi',
      keterangan: 'Data contoh simulasi antarmuka'
    },
    {
      id: 'tkd-sim-002',
      nomorSertifikat: 'SIM-C.0108 / Persil 12.S (Contoh)',
      luas: 4200,
      kelas: 'S.I',
      asalTanah: 'Asli Milik Desa',
      asalBantuan: '',
      tanggalPerolehan: '2017-07-20',
      jenisTanah: 'Sawah',
      patokBatas: 'Ada',
      papanNama: 'Ada',
      lokasi: 'Blok Lembah Irigasi Dusun III (Simulasi)',
      peruntukkan: 'Lahan Pertanian Produktif PADes',
      mutasi: 'Digarap kelompok tani desa',
      keterangan: 'Data contoh — Sistem bagi hasil kas desa'
    },
    {
      id: 'tkd-sim-003',
      nomorSertifikat: 'SIM-HP.0024 / Blok B-02 (Contoh)',
      luas: 2600,
      kelas: 'D.II',
      asalTanah: 'Bantuan',
      asalBantuan: 'Kabupaten/Kota',
      tanggalPerolehan: '2019-11-05',
      jenisTanah: 'Tanah Kering/Darat',
      patokBatas: 'Ada',
      papanNama: 'Ada',
      lokasi: 'Blok Lapangan Tengah Dusun II (Simulasi)',
      peruntukkan: 'Sarana Olahraga & Kegiatan Warga',
      mutasi: 'Hibah aset daerah ke kas desa (simulasi)',
      keterangan: 'Data contoh simulasi UI'
    },
    {
      id: 'tkd-sim-004',
      nomorSertifikat: 'SIM-C.0119 / Persil 19.K (Contoh)',
      luas: 3100,
      kelas: 'D.II',
      asalTanah: 'Bantuan',
      asalBantuan: 'Provinsi',
      tanggalPerolehan: '2021-05-12',
      jenisTanah: 'Kebun',
      patokBatas: 'Ada',
      papanNama: 'Tidak Ada',
      lokasi: 'Blok Perbukitan Timur Dusun IV (Simulasi)',
      peruntukkan: 'Kebun Bibit & Tanaman Perkebunan Desa',
      mutasi: 'Papan nama dalam pengadaan pemeliharaan',
      keterangan: 'Data contoh — Perlu pemasangan papan identitas aset'
    },
    {
      id: 'tkd-sim-005',
      nomorSertifikat: 'SIM-C.0125 / Persil 07.T (Contoh)',
      luas: 1500,
      kelas: 'S.II',
      asalTanah: 'Bantuan',
      asalBantuan: 'Pemerintah',
      tanggalPerolehan: '2022-08-29',
      jenisTanah: 'Tambak/Kolam',
      patokBatas: 'Ada',
      papanNama: 'Ada',
      lokasi: 'Blok Tirta Mekar Dusun II (Simulasi)',
      peruntukkan: 'Kolam Budidaya Air Tawar BUMDes',
      mutasi: 'Dikelola unit ketahanan pangan',
      keterangan: 'Data contoh simulasi UI'
    },
    {
      id: 'tkd-sim-006',
      nomorSertifikat: 'SIM-C.0133 / Persil 22.TG (Contoh)',
      luas: 2250,
      kelas: 'D.III',
      asalTanah: 'Asli Milik Desa',
      asalBantuan: '',
      tanggalPerolehan: '2018-02-14',
      jenisTanah: 'Tegal',
      patokBatas: 'Tidak Ada',
      papanNama: 'Tidak Ada',
      lokasi: 'Blok Batas Utara Dusun IV (Simulasi)',
      peruntukkan: 'Cadangan Lahan Palawija Kas Desa',
      mutasi: 'Menunggu penataan ulang patok batas',
      keterangan: 'Data contoh — Dijadwalkan pengukuran ulang'
    },
    {
      id: 'tkd-sim-007',
      nomorSertifikat: 'SIM-L.0041 / Persil 09.D (Contoh)',
      luas: 640,
      kelas: 'D.I',
      asalTanah: 'Lain-lain',
      asalBantuan: '',
      tanggalPerolehan: '2024-04-18',
      jenisTanah: 'Tanah Kering/Darat',
      patokBatas: 'Ada',
      papanNama: 'Ada',
      lokasi: 'Blok Simpang Krajan Dusun I (Simulasi)',
      peruntukkan: 'Posyandu & Taman Baca Masyarakat',
      mutasi: 'Penyerahan swadaya masyarakat (contoh)',
      keterangan: 'Data contoh simulasi UI'
    }
  ];

  function toPositiveNumber(val) {
    const n = Number(val);
    return Number.isFinite(n) && n >= 0 ? n : 0;
  }

  function formatArea(val) {
    return toPositiveNumber(val).toLocaleString('id-ID');
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
      // Ignore restricted storage errors
    }
  }

  function initBukuTanahKasDesa() {
    const pageRoot = document.getElementById('bukuTanahKasPageRoot');
    if (!pageRoot) return;

    const esc = window.SimdesApp ? window.SimdesApp.escapeHtml : (s) => String(s ?? '');
    const formatDate = window.SimdesTable ? window.SimdesTable.formatDateIndo : (s) => s;

    let records = loadRecords();
    let showReservedCols = Boolean(RESERVED_COLUMNS_CONFIG.defaultVisible);
    let activeDetailId = null;
    let pendingDeleteId = null;

    // DOM References
    const statTotalBidang = document.getElementById('statTotalBidang');
    const statTotalLuas = document.getElementById('statTotalLuas');
    const statPatokLengkap = document.getElementById('statPatokLengkap');
    const statPapanLengkap = document.getElementById('statPapanLengkap');
    const printMetaEl = document.getElementById('printTanahKasMeta');

    const tableEl = document.getElementById('tanahKasTable');
    const theadEl = document.getElementById('tanahKasTableHead');
    const tbodyEl = document.getElementById('tanahKasTableBody');
    const paginationInfoEl = document.getElementById('tablePaginationInfo');
    const paginationListEl = document.getElementById('tablePaginationList');

    const searchInput = document.getElementById('tableSearchInput');
    const clearSearchBtn = document.getElementById('clearSearchInputBtn');
    const filterAsalSelect = document.getElementById('filterAsalSelect');
    const filterJenisSelect = document.getElementById('filterJenisSelect');
    const filterKelasSelect = document.getElementById('filterKelasSelect');
    const pageSizeSelect = document.getElementById('pageSizeSelect');
    const resetFilterBtn = document.getElementById('resetTableFilterBtn');
    const toggleReservedBtn = document.getElementById('toggleReservedColsBtn');
    const resetSampleBtn = document.getElementById('btnResetSampleTanahKas');

    const btnTambah = document.getElementById('btnTambahTanahKas');
    const btnCetak = document.getElementById('btnCetakTanahKas');

    // Form Modal Elements
    const formModalEl = document.getElementById('tanahKasFormModal');
    const formModalTitle = document.getElementById('tanahKasFormModalLabel');
    const formEl = document.getElementById('tanahKasForm');
    const formAlertBox = document.getElementById('tanahKasFormAlert');
    const formAlertText = document.getElementById('tanahKasFormAlertText');
    const formSubmitBtn = document.getElementById('tanahKasFormSubmitBtn');

    const fId = document.getElementById('tkdRecordId');
    const fNomorSertifikat = document.getElementById('tkdNomorSertifikat');
    const fLuas = document.getElementById('tkdLuas');
    const fKelas = document.getElementById('tkdKelas');
    const fAsalTanah = document.getElementById('tkdAsalTanah');
    const fAsalBantuanWrap = document.getElementById('tkdAsalBantuanWrap');
    const fAsalBantuan = document.getElementById('tkdAsalBantuan');
    const fTanggalPerolehan = document.getElementById('tkdTanggalPerolehan');
    const fJenisTanah = document.getElementById('tkdJenisTanah');
    const fLokasi = document.getElementById('tkdLokasi');
    const fPeruntukkan = document.getElementById('tkdPeruntukkan');
    const fMutasi = document.getElementById('tkdMutasi');
    const fKeterangan = document.getElementById('tkdKeterangan');

    const dynamicReservedWrap = document.getElementById('dynamicReservedFieldsWrap');
    const dynamicReservedGrid = document.getElementById('dynamicReservedFieldsGrid');

    // Detail & Delete Modals
    const detailModalEl = document.getElementById('tanahKasDetailModal');
    const btnEditFromDetail = document.getElementById('btnEditTanahKasFromDetail');
    const deleteModalEl = document.getElementById('tanahKasDeleteModal');
    const deleteTargetNameEl = document.getElementById('deleteTanahKasTargetName');
    const deleteTargetMetaEl = document.getElementById('deleteTanahKasTargetMeta');
    const confirmDeleteBtn = document.getElementById('confirmDeleteTanahKasBtn');

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
      // 23 known leaf columns + active reserve slots (1 by default = exact 24 positions)
      return 23 + getActiveReservedColumns().length;
    }

    function syncConditionalAsalBantuan() {
      if (!fAsalTanah || !fAsalBantuanWrap || !fAsalBantuan) return;
      const isBantuan = fAsalTanah.value === 'Bantuan';
      fAsalBantuanWrap.hidden = !isBantuan;
      if (isBantuan) {
        fAsalBantuan.setAttribute('required', '');
      } else {
        fAsalBantuan.removeAttribute('required');
        fAsalBantuan.value = '';
        fAsalBantuan.setCustomValidity('');
        fAsalBantuan.classList.remove('user-invalid-fallback', 'is-invalid-field');
        fAsalBantuan.removeAttribute('aria-invalid');
        fAsalBantuan.closest('.simdes-field')?.classList.remove('has-error');
      }
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
          <div class="col-12 col-sm-6 col-lg-4">
            <div class="simdes-field mb-2">
              <label for="tkdSlot_${esc(col.key)}" class="simdes-label">${esc(col.officialLabel)}</label>
              <input
                type="${esc(col.inputType || 'text')}"
                id="tkdSlot_${esc(col.key)}"
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

    function populateKelasFilter(currentVal = 'ALL') {
      if (!filterKelasSelect) return;
      const uniqueKelas = Array.from(
        new Set(records.map((r) => String(r.kelas || '').trim()).filter(Boolean))
      ).sort((a, b) => a.localeCompare(b, 'id', { numeric: true }));

      filterKelasSelect.innerHTML = [
        '<option value="ALL">Semua Kelas Tanah</option>',
        ...uniqueKelas.map((k) => `<option value="${esc(k)}">Kelas ${esc(k)}</option>`)
      ].join('');

      if (currentVal !== 'ALL' && uniqueKelas.includes(currentVal)) {
        filterKelasSelect.value = currentVal;
      } else {
        filterKelasSelect.value = 'ALL';
      }
    }

    function buildSortBtnHtml(sortKey, labelText, currentSortKey, currentSortDir) {
      const isSorted = currentSortKey === sortKey;
      const iconClass = !isSorted
        ? 'fa-sort'
        : currentSortDir === 'asc'
          ? 'fa-sort-up'
          : 'fa-sort-down';
      return `
        <button type="button"
                class="th-sort-btn${isSorted ? ' is-sorted' : ''} justify-content-center"
                data-sort-key="${esc(sortKey)}"
                aria-label="Urutkan berdasarkan ${esc(labelText)}">
          <span>${esc(labelText)}</span>
          <i class="fa-solid ${iconClass} th-sort-icon no-print" aria-hidden="true"></i>
        </button>
      `;
    }

    /**
     * Render 3-Row Grouped Header (HTML rowspan & colspan)
     * Exact 23 known leaf columns + configurable reserve slots (default 1 = 24 positions)
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
                    title="Posisi kolom cadangan sumber — dapat diberi nama resmi melalui RESERVED_COLUMNS_CONFIG">
                  <span class="placeholder-slot-code">${esc(col.tempCode)}</span>
                  <span class="placeholder-slot-hint">Belum tersedia</span>
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
          <th scope="colgroup" colspan="5" class="th-group-top group-divider-end">
            ASAL TANAH KAS DESA
          </th>
          <th scope="col" rowspan="3" class="group-divider-end" style="min-inline-size:178px;" aria-sort="${ariaSortFor('nomorSertifikat')}">
            ${buildSortBtnHtml('nomorSertifikat', 'NOMOR SERTIFIKAT BUKU LETTER C/PERSIL', state.sortKey, state.sortDirection)}
          </th>
          <th scope="col" rowspan="3" class="group-divider-end" style="min-inline-size:106px;" aria-sort="${ariaSortFor('luas')}">
            ${buildSortBtnHtml('luas', 'LUAS (m²)', state.sortKey, state.sortDirection)}
          </th>
          <th scope="col" rowspan="3" class="group-divider-end" style="min-inline-size:88px;" aria-sort="${ariaSortFor('kelas')}">
            ${buildSortBtnHtml('kelas', 'KELAS', state.sortKey, state.sortDirection)}
          </th>
          <th scope="colgroup" colspan="1" class="th-group-top group-divider-end">
            PEROLEHAN TANAH KAS DESA
          </th>
          <th scope="colgroup" colspan="5" class="th-group-top group-divider-end">
            JENIS TANAH KAS DESA
          </th>
          <th scope="colgroup" colspan="2" class="th-group-top group-divider-end">
            PATOK TANDA BATAS
          </th>
          <th scope="colgroup" colspan="2" class="th-group-top group-divider-end">
            PAPAN NAMA
          </th>
          <th scope="col" rowspan="3" class="group-divider-end" style="min-inline-size:175px;" aria-sort="${ariaSortFor('lokasi')}">
            ${buildSortBtnHtml('lokasi', 'LOKASI', state.sortKey, state.sortDirection)}
          </th>
          <th scope="col" rowspan="3" class="group-divider-end" style="min-inline-size:175px;" aria-sort="${ariaSortFor('peruntukkan')}">
            ${buildSortBtnHtml('peruntukkan', 'PERUNTUKKAN', state.sortKey, state.sortDirection)}
          </th>
          <th scope="col" rowspan="3" class="group-divider-end" style="min-inline-size:150px;">
            MUTASI
          </th>
          ${reservedTopGroupHtml}
          <th scope="col" rowspan="3" style="min-inline-size:165px;">
            KET
          </th>
        </tr>
        <tr>
          <th scope="col" rowspan="2" style="min-inline-size:88px;">Asli Milik Desa</th>
          <th scope="colgroup" colspan="3" class="th-group-sub">Bantuan</th>
          <th scope="col" rowspan="2" class="group-divider-end" style="min-inline-size:82px;">Lain-lain</th>
          <th scope="col" rowspan="2" class="group-divider-end" style="min-inline-size:122px;" aria-sort="${ariaSortFor('tanggalPerolehan')}">
            ${buildSortBtnHtml('tanggalPerolehan', 'Tanggal Perolehan', state.sortKey, state.sortDirection)}
          </th>
          <th scope="col" rowspan="2" style="min-inline-size:78px;">Sawah</th>
          <th scope="col" rowspan="2" style="min-inline-size:78px;">Tegal</th>
          <th scope="col" rowspan="2" style="min-inline-size:78px;">Kebun</th>
          <th scope="col" rowspan="2" style="min-inline-size:96px;">Tambak/Kolam</th>
          <th scope="col" rowspan="2" class="group-divider-end" style="min-inline-size:102px;">Tanah Kering/Darat</th>
          <th scope="col" rowspan="2" style="min-inline-size:72px;">Ada</th>
          <th scope="col" rowspan="2" class="group-divider-end" style="min-inline-size:78px;">Tidak Ada</th>
          <th scope="col" rowspan="2" style="min-inline-size:72px;">Ada</th>
          <th scope="col" rowspan="2" class="group-divider-end" style="min-inline-size:78px;">Tidak Ada</th>
          ${reservedSubRow2Html}
        </tr>
        <tr>
          <th scope="col" style="min-inline-size:88px;">Pemerintah</th>
          <th scope="col" style="min-inline-size:82px;">Provinsi</th>
          <th scope="col" style="min-inline-size:102px;">Kabupaten/Kota</th>
        </tr>
      `;

      theadEl.querySelectorAll('[data-sort-key]').forEach((btn) => {
        btn.addEventListener('click', () => {
          tableCtrl.toggleSort(btn.dataset.sortKey);
        });
      });
    }

    function renderCheckCell(isActive, extraClass = '') {
      const cls = ['td-num', isActive ? 'is-good' : 'is-zero', extraClass]
        .filter(Boolean)
        .join(' ');
      if (isActive) {
        return `<td class="${cls}"><span class="ledger-check-mark" aria-label="Ya">&#10003;</span></td>`;
      }
      return `<td class="${cls}">&ndash;</td>`;
    }

    function updateSummaryStats() {
      const totalBidang = records.length;
      const totalLuas = records.reduce((acc, r) => acc + toPositiveNumber(r.luas), 0);
      const totalPatokAda = records.filter((r) => r.patokBatas === 'Ada').length;
      const totalPapanAda = records.filter((r) => r.papanNama === 'Ada').length;

      if (statTotalBidang) statTotalBidang.textContent = totalBidang.toLocaleString('id-ID');
      if (statTotalLuas) statTotalLuas.textContent = `${totalLuas.toLocaleString('id-ID')} m²`;
      if (statPatokLengkap) statPatokLengkap.textContent = `${totalPatokAda} / ${totalBidang}`;
      if (statPapanLengkap) statPapanLengkap.textContent = `${totalPapanAda} / ${totalBidang}`;

      if (printMetaEl) {
        const nowStr = new Intl.DateTimeFormat('id-ID', {
          day: 'numeric',
          month: 'long',
          year: 'numeric'
        }).format(new Date());
        printMetaEl.textContent = `Total tercatat: ${totalBidang} bidang tanah (${totalLuas.toLocaleString('id-ID')} m²) • Dicetak pada ${nowStr}`;
      }
    }

    const tableCtrl = window.SimdesTable.create({
      data: records,
      pageSize: 5,
      defaultSortKey: 'tanggalPerolehan',
      defaultSortDirection: 'desc',
      searchFields: ['nomorSertifikat', 'lokasi', 'peruntukkan', 'kelas', 'mutasi', 'keterangan'],
      comparators: {
        luas: (a, b) => toPositiveNumber(a.luas) - toPositiveNumber(b.luas),
        tanggalPerolehan: (a, b) =>
          String(a.tanggalPerolehan || '').localeCompare(String(b.tanggalPerolehan || ''))
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
                    <i class="fa-solid fa-map-location-dot" aria-hidden="true"></i>
                  </div>
                  <div class="fw-bold mb-1" style="color:var(--simdes-text); font-size:0.95rem;">
                    ${state.hasActiveQuery ? 'Data tanah kas desa tidak ditemukan' : 'Belum ada data tanah kas desa'}
                  </div>
                  <div class="mb-3" style="font-size:0.82rem;">
                    ${
                      state.hasActiveQuery
                        ? 'Tidak ada bidang tanah yang cocok dengan kata kunci atau filter yang dipilih.'
                        : 'Silakan tambahkan data tanah kas desa menggunakan tombol Tambah Tanah Kas Desa.'
                    }
                  </div>
                  ${
                    state.hasActiveQuery
                      ? `<button type="button" class="btn-simdes-outline" id="emptyResetTanahKasBtn">
                           <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                           <span>Reset Pencarian &amp; Filter</span>
                         </button>`
                      : ''
                  }
                </div>
              </td>
            </tr>
          `;
          document.getElementById('emptyResetTanahKasBtn')?.addEventListener('click', handleResetAllFilters);
        } else {
          tbodyEl.innerHTML = state.rows
            .map((row, idx) => {
              const noUrut = state.startIndex + idx + 1;
              const isAsli = row.asalTanah === 'Asli Milik Desa';
              const isBantPem = row.asalTanah === 'Bantuan' && row.asalBantuan === 'Pemerintah';
              const isBantProv = row.asalTanah === 'Bantuan' && row.asalBantuan === 'Provinsi';
              const isBantKab = row.asalTanah === 'Bantuan' && row.asalBantuan === 'Kabupaten/Kota';
              const isLain = row.asalTanah === 'Lain-lain';

              const tglFormatted = row.tanggalPerolehan ? formatDate(row.tanggalPerolehan) : '';

              const reservedCellsHtml = reservedCols
                .map((col, cIdx) => {
                  const isLast = cIdx === reservedCols.length - 1;
                  if (col.officialLabel) {
                    const v = row[col.key] ?? '';
                    return `<td class="${isLast ? 'group-divider-end' : ''}">${v ? esc(v) : '<span class="text-simdes-muted">&ndash;</span>'}</td>`;
                  }
                  return `<td class="td-placeholder-col${isLast ? ' last-placeholder-col' : ''}" aria-label="Posisi kolom cadangan belum aktif">&mdash;</td>`;
                })
                .join('');

              return `
                <tr>
                  <td class="col-no-urut sticky-col-first group-divider-end">${noUrut}</td>
                  ${renderCheckCell(isAsli)}
                  ${renderCheckCell(isBantPem)}
                  ${renderCheckCell(isBantProv)}
                  ${renderCheckCell(isBantKab)}
                  ${renderCheckCell(isLain, 'group-divider-end')}
                  <td class="group-divider-end">
                    <button type="button"
                            class="tentang-title-btn"
                            data-row-action="detail"
                            data-id="${esc(row.id)}"
                            title="Lihat detail bidang tanah">
                      <span class="doc-num-badge mb-0">${esc(row.nomorSertifikat)}</span>
                    </button>
                  </td>
                  <td class="td-num is-good group-divider-end">${esc(formatArea(row.luas))}</td>
                  <td class="td-num group-divider-end">
                    <span class="badge-simdes badge-simdes-neutral">${esc(row.kelas)}</span>
                  </td>
                  <td class="td-num group-divider-end">
                    ${tglFormatted ? esc(tglFormatted) : '<span class="text-simdes-muted">&ndash;</span>'}
                  </td>
                  ${renderCheckCell(row.jenisTanah === 'Sawah')}
                  ${renderCheckCell(row.jenisTanah === 'Tegal')}
                  ${renderCheckCell(row.jenisTanah === 'Kebun')}
                  ${renderCheckCell(row.jenisTanah === 'Tambak/Kolam')}
                  ${renderCheckCell(row.jenisTanah === 'Tanah Kering/Darat', 'group-divider-end')}
                  ${renderCheckCell(row.patokBatas === 'Ada')}
                  ${renderCheckCell(row.patokBatas === 'Tidak Ada', 'group-divider-end')}
                  ${renderCheckCell(row.papanNama === 'Ada')}
                  ${renderCheckCell(row.papanNama === 'Tidak Ada', 'group-divider-end')}
                  <td class="group-divider-end">${esc(row.lokasi)}</td>
                  <td class="group-divider-end">${esc(row.peruntukkan)}</td>
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
                                aria-label="Lihat detail ${esc(row.nomorSertifikat)}">
                          <i class="fa-regular fa-eye" aria-hidden="true"></i>
                        </button>
                        <button type="button"
                                class="btn-row-action"
                                data-row-action="edit"
                                data-id="${esc(row.id)}"
                                title="Edit Data"
                                aria-label="Edit data ${esc(row.nomorSertifikat)}">
                          <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
                        </button>
                        <button type="button"
                                class="btn-row-action action-delete"
                                data-row-action="delete"
                                data-id="${esc(row.id)}"
                                title="Hapus Data"
                                aria-label="Hapus data ${esc(row.nomorSertifikat)}">
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
              ? 'Menampilkan <strong>0</strong> dari <strong>0</strong> data tanah kas desa'
              : `Menampilkan <strong>${state.startEntry}&ndash;${state.endEntry}</strong> dari <strong>${state.totalItems}</strong> bidang tanah${
                  state.hasActiveQuery ? ` (disaring dari total ${state.totalAllItems} bidang)` : ''
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
      if (filterAsalSelect) filterAsalSelect.value = 'ALL';
      if (filterJenisSelect) filterJenisSelect.value = 'ALL';
      if (filterKelasSelect) filterKelasSelect.value = 'ALL';
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

    filterAsalSelect?.addEventListener('change', () => {
      const val = filterAsalSelect.value;
      if (val === 'ALL') {
        tableCtrl.setFilter('asalFilter', null);
      } else if (val.startsWith('BANTUAN:')) {
        const sub = val.split(':')[1];
        tableCtrl.setFilter('asalFilter', (_v, row) => row.asalTanah === 'Bantuan' && row.asalBantuan === sub);
      } else {
        tableCtrl.setFilter('asalFilter', (_v, row) => row.asalTanah === val);
      }
    });

    filterJenisSelect?.addEventListener('change', () => {
      tableCtrl.setFilter('jenisTanah', filterJenisSelect.value);
    });

    filterKelasSelect?.addEventListener('change', () => {
      tableCtrl.setFilter('kelas', filterKelasSelect.value);
    });

    pageSizeSelect?.addEventListener('change', () => {
      tableCtrl.setPageSize(Number(pageSizeSelect.value));
    });

    resetFilterBtn?.addEventListener('click', handleResetAllFilters);

    // Reserve Column Slot Toggle
    function syncReservedToggleBtnUI() {
      if (!toggleReservedBtn) return;
      toggleReservedBtn.setAttribute('aria-pressed', String(showReservedCols));
      const span = toggleReservedBtn.querySelector('span');
      if (span) {
        span.textContent = showReservedCols
          ? 'Posisi Kolom ke-24: Tampil (24 Kolom)'
          : 'Posisi Kolom ke-24: Sembunyi';
      }
    }

    toggleReservedBtn?.addEventListener('click', () => {
      showReservedCols = !showReservedCols;
      syncReservedToggleBtnUI();
      tableCtrl.render();
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

    function setRadioGroupValue(name, value) {
      if (!formEl) return;
      const radios = Array.from(formEl.querySelectorAll(`input[type="radio"][name="${name}"]`));
      radios.forEach((r) => {
        r.checked = r.value === value;
      });
    }

    function getRadioGroupValue(name) {
      if (!formEl) return '';
      const checked = formEl.querySelector(`input[type="radio"][name="${name}"]:checked`);
      return checked ? checked.value : '';
    }

    function openCreateModal() {
      if (!formEl || !formModalEl) return;
      formEl.reset();
      fId.value = '';
      hideFormBanner();
      syncConditionalAsalBantuan();

      if (formModalTitle) {
        formModalTitle.innerHTML =
          '<i class="fa-solid fa-plus-circle text-simdes-primary me-2" aria-hidden="true"></i>Tambah Data Tanah Kas Desa';
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML =
          '<i class="fa-solid fa-floppy-disk" aria-hidden="true"></i><span>Simpan Data Tanah</span>';
      }

      window.bootstrap?.Modal.getOrCreateInstance(formModalEl).show();
      formModalEl.addEventListener('shown.bs.modal', () => fNomorSertifikat?.focus(), { once: true });
    }

    function openEditModal(id) {
      const item = records.find((r) => r.id === id);
      if (!item || !formEl || !formModalEl) return;

      formEl.reset();
      hideFormBanner();

      fId.value = item.id;
      fNomorSertifikat.value = item.nomorSertifikat || '';
      fLuas.value = item.luas ?? '';
      fKelas.value = item.kelas || '';
      fAsalTanah.value = item.asalTanah || '';
      syncConditionalAsalBantuan();
      if (item.asalTanah === 'Bantuan') {
        fAsalBantuan.value = item.asalBantuan || '';
      }
      fTanggalPerolehan.value = item.tanggalPerolehan || '';
      fJenisTanah.value = item.jenisTanah || '';
      setRadioGroupValue('patokBatas', item.patokBatas || '');
      setRadioGroupValue('papanNama', item.papanNama || '');
      fLokasi.value = item.lokasi || '';
      fPeruntukkan.value = item.peruntukkan || '';
      fMutasi.value = item.mutasi || '';
      fKeterangan.value = item.keterangan || '';

      getConfiguredReservedColumns().forEach((col) => {
        const slotInput = document.getElementById(`tkdSlot_${col.key}`);
        if (slotInput) slotInput.value = item[col.key] ?? '';
      });

      if (formModalTitle) {
        formModalTitle.innerHTML =
          '<i class="fa-regular fa-pen-to-square text-simdes-primary me-2" aria-hidden="true"></i>Edit Data Tanah Kas Desa';
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML =
          '<i class="fa-solid fa-check" aria-hidden="true"></i><span>Simpan Perubahan</span>';
      }

      window.bootstrap?.Modal.getOrCreateInstance(formModalEl).show();
      formModalEl.addEventListener('shown.bs.modal', () => fNomorSertifikat?.focus(), { once: true });
    }

    function openDetailModal(id) {
      const item = records.find((r) => r.id === id);
      if (!item || !detailModalEl) return;
      activeDetailId = item.id;

      const setText = (elId, val) => {
        const el = document.getElementById(elId);
        if (el) el.textContent = val;
      };

      const asalDesc =
        item.asalTanah === 'Bantuan' && item.asalBantuan
          ? `Bantuan (${item.asalBantuan})`
          : item.asalTanah || '-';

      setText('detTkdIdentitasKelas', `Kelas Tanah: ${item.kelas || '-'}`);
      setText('detTkdAsal', asalDesc);
      setText('detTkdSertifikat', item.nomorSertifikat || '-');
      setText('detTkdLuas', `${formatArea(item.luas)} m²`);
      setText(
        'detTkdTanggalPerolehan',
        item.tanggalPerolehan ? formatDate(item.tanggalPerolehan) : '-'
      );
      setText('detTkdJenisTanah', item.jenisTanah || '-');
      setText('detTkdPatok', item.patokBatas || '-');
      setText('detTkdPapan', item.papanNama || '-');
      setText('detTkdLokasi', item.lokasi || '-');
      setText('detTkdPeruntukkan', item.peruntukkan || '-');
      setText('detTkdMutasi', item.mutasi || 'Tidak ada catatan mutasi');
      setText('detTkdKeterangan', item.keterangan || 'Tidak ada keterangan tambahan');

      window.bootstrap?.Modal.getOrCreateInstance(detailModalEl).show();
    }

    function openDeleteModal(id) {
      const item = records.find((r) => r.id === id);
      if (!item || !deleteModalEl) return;
      pendingDeleteId = item.id;

      if (deleteTargetNameEl) deleteTargetNameEl.textContent = item.nomorSertifikat;
      if (deleteTargetMetaEl) {
        deleteTargetMetaEl.textContent = `${item.lokasi} • Luas ${formatArea(item.luas)} m² (${item.jenisTanah})`;
      }

      window.bootstrap?.Modal.getOrCreateInstance(deleteModalEl).show();
    }

    fAsalTanah?.addEventListener('change', syncConditionalAsalBantuan);

    if (formEl && window.SimdesForms?.InteractionValidator) {
      renderConfiguredReservedInputs();
      window.SimdesForms.InteractionValidator.bindForm(formEl);

      formEl.addEventListener('submit', (e) => {
        e.preventDefault();
        hideFormBanner();
        syncConditionalAsalBantuan();

        const validBase = window.SimdesForms.InteractionValidator.validateAll(formEl);
        if (!validBase) {
          showFormBanner('Mohon lengkapi seluruh kolom wajib yang ditandai sebelum menyimpan.');
          return;
        }

        const luasVal = Number(fLuas.value);
        if (!Number.isFinite(luasVal) || luasVal <= 0) {
          window.SimdesForms.InteractionValidator.syncFieldState(
            fLuas,
            'Luas tanah harus berupa angka lebih dari 0 m².'
          );
          fLuas.focus();
          showFormBanner('Luas tanah kas desa harus lebih besar dari 0 m².');
          return;
        }

        const existingId = fId.value.trim();
        const asalVal = fAsalTanah.value;

        const payload = {
          id: existingId || `tkd-${Date.now()}`,
          nomorSertifikat: fNomorSertifikat.value.trim(),
          luas: luasVal,
          kelas: fKelas.value.trim(),
          asalTanah: asalVal,
          asalBantuan: asalVal === 'Bantuan' ? fAsalBantuan.value : '',
          tanggalPerolehan: fTanggalPerolehan.value,
          jenisTanah: fJenisTanah.value,
          patokBatas: getRadioGroupValue('patokBatas'),
          papanNama: getRadioGroupValue('papanNama'),
          lokasi: fLokasi.value.trim(),
          peruntukkan: fPeruntukkan.value.trim(),
          mutasi: fMutasi.value.trim(),
          keterangan: fKeterangan.value.trim()
        };

        getConfiguredReservedColumns().forEach((col) => {
          const slotInput = document.getElementById(`tkdSlot_${col.key}`);
          if (slotInput) payload[col.key] = slotInput.value.trim();
        });

        if (existingId) {
          records = records.map((r) => (r.id === existingId ? { ...r, ...payload } : r));
        } else {
          records = [payload, ...records];
        }

        saveRecords(records);
        updateSummaryStats();
        populateKelasFilter(filterKelasSelect?.value || 'ALL');
        tableCtrl.setData(records, Boolean(existingId));

        window.bootstrap?.Modal.getOrCreateInstance(formModalEl).hide();
        window.SimdesApp?.showToast({
          title: existingId ? 'Data Tanah Kas Diperbarui' : 'Data Tanah Kas Ditambahkan',
          message: `Bidang "${payload.nomorSertifikat}" berhasil disimpan pada Buku Tanah Kas Desa.`,
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
      populateKelasFilter(filterKelasSelect?.value || 'ALL');
      tableCtrl.setData(records, true);

      window.bootstrap?.Modal.getOrCreateInstance(deleteModalEl)?.hide();
      window.SimdesApp?.showToast({
        title: 'Data Tanah Kas Dihapus',
        message: target
          ? `Bidang "${target.nomorSertifikat}" telah dihapus dari daftar.`
          : 'Data berhasil dihapus.',
        variant: 'warning'
      });
    });

    resetSampleBtn?.addEventListener('click', () => {
      records = cloneSeedData();
      saveRecords(records);
      handleResetAllFilters();
      updateSummaryStats();
      populateKelasFilter('ALL');
      tableCtrl.setData(records, false);
      window.SimdesApp?.showToast({
        title: 'Data Contoh Dipulihkan',
        message: '7 data contoh simulasi Buku Tanah Kas Desa berhasil dimuat ulang.',
        variant: 'primary'
      });
    });

    btnCetak?.addEventListener('click', () => {
      window.print();
    });

    // Initial Boot
    syncReservedToggleBtnUI();
    updateSummaryStats();
    populateKelasFilter('ALL');
    tableCtrl.render();
  }

  document.addEventListener('DOMContentLoaded', initBukuTanahKasDesa);
})();

