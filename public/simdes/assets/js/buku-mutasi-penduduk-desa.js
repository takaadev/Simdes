/* ==========================================================================
   SIMDES DESA TIARO — Buku Mutasi Penduduk Desa Controller
   File: assets/js/buku-mutasi-penduduk-desa.js
   Kode Buku: B.2 (Kelompok B. Administrasi Penduduk)
   Struktur Tabel Resmi: Tepat 13 Kolom dengan Header 2 Tingkat (rowspan/colspan):
     1.  NOMOR URUT (rowspan 2)
     2.  NAMA LENGKAP/PANGGILAN (rowspan 2)
     3.  TEMPAT (subkolom TEMPAT & TANGGAL LAHIR)
     4.  TANGGAL (subkolom TEMPAT & TANGGAL LAHIR)
     5.  JENIS KELAMIN (rowspan 2)
     6.  KEWARGANEGARAAN (rowspan 2)
     7.  DATANG DARI (subkolom PENAMBAHAN)
     8.  TANGGAL (subkolom PENAMBAHAN)
     9.  PINDAH KE (subkolom PENGURANGAN)
     10. TANGGAL (subkolom PENGURANGAN)
     11. MENINGGAL (subkolom PENGURANGAN)
     12. TANGGAL (subkolom PENGURANGAN)
     13. KET (rowspan 2)
   Pure Vanilla JS + localStorage persistence (fictitious simulation data).
   ========================================================================== */

(() => {
  'use strict';

  const STORAGE_KEY = 'simdes_tiaro_buku_mutasi_penduduk';
  const DEFAULT_BULAN = '10';
  const DEFAULT_TAHUN = '2026';

  const BULAN_MAP = {
    '01': 'Januari',
    '02': 'Februari',
    '03': 'Maret',
    '04': 'April',
    '05': 'Mei',
    '06': 'Juni',
    '07': 'Juli',
    '08': 'Agustus',
    '09': 'September',
    '10': 'Oktober',
    '11': 'November',
    '12': 'Desember'
  };

  /**
   * Data contoh mutasi penduduk desa sepenuhnya fiktif (bukan identitas warga sebenarnya).
   * Mencakup variasi Datang, Pindah, dan Meninggal dengan tanggal mutasi yang selaras
   * dengan periode bulan & tahun pencatatan (Oktober 2026, September 2026, Desember 2025).
   */
  const SEED_DATA = [
    // ===================== PERIODE OKTOBER 2026 (11 DATA) =====================
    {
      id: 'mut-tiaro-001',
      jenisMutasi: 'Datang',
      namaLengkap: 'La Ode Syahrul Ramadhan / Syahrul',
      tempatLahir: 'Bau-Bau',
      tanggalLahir: '1991-04-12',
      jenisKelamin: 'Laki-laki',
      kewarganegaraan: 'WNI',
      datangDari: 'Kel. Wale, Kec. Wolio, Kota Bau-Bau',
      tanggalDatang: '2026-10-02',
      pindahKe: '',
      tanggalPindah: '',
      meninggal: '',
      tanggalMeninggal: '',
      keterangan: 'SKDWNI No. 471.2/089/X/2026 — Pindah domisili keluarga ke Dusun I Krajan.'
    },
    {
      id: 'mut-tiaro-002',
      jenisMutasi: 'Datang',
      namaLengkap: 'Wa Ode Nurlaila Sari / Laila',
      tempatLahir: 'Raha',
      tanggalLahir: '1994-08-19',
      jenisKelamin: 'Perempuan',
      kewarganegaraan: 'WNI',
      datangDari: 'Kel. Wale, Kec. Wolio, Kota Bau-Bau',
      tanggalDatang: '2026-10-02',
      pindahKe: '',
      tanggalPindah: '',
      meninggal: '',
      tanggalMeninggal: '',
      keterangan: 'SKDWNI No. 471.2/089/X/2026 — Mengikuti suami ke RT 002 / RW 001.'
    },
    {
      id: 'mut-tiaro-003',
      jenisMutasi: 'Pindah',
      namaLengkap: 'Fajar Nugroho Pratama / Fajar',
      tempatLahir: 'Bombana',
      tanggalLahir: '1998-01-25',
      jenisKelamin: 'Laki-laki',
      kewarganegaraan: 'WNI',
      datangDari: '',
      tanggalDatang: '',
      pindahKe: 'Kel. Korumba, Kec. Mandonga, Kota Kendari',
      tanggalPindah: '2026-10-05',
      meninggal: '',
      tanggalMeninggal: '',
      keterangan: 'SKPWNI No. 471.2/102/X/2026 — Pindah karena penempatan kerja swasta.'
    },
    {
      id: 'mut-tiaro-004',
      jenisMutasi: 'Meninggal',
      namaLengkap: 'H. Abdul Halim Labaco / Halim',
      tempatLahir: 'Tiaro',
      tanggalLahir: '1948-06-03',
      jenisKelamin: 'Laki-laki',
      kewarganegaraan: 'WNI',
      datangDari: '',
      tanggalDatang: '',
      pindahKe: '',
      tanggalPindah: '',
      meninggal: 'Desa Tiaro (Rumah Kediaman Dusun I)',
      tanggalMeninggal: '2026-10-08',
      keterangan: 'Surat Ket. Kematian No. 474.3/014/X/2026 — Meninggal dunia karena usia lanjut.'
    },
    {
      id: 'mut-tiaro-005',
      jenisMutasi: 'Datang',
      namaLengkap: 'Maria Fransiska Lengkong / Siska',
      tempatLahir: 'Manado',
      tanggalLahir: '1993-11-30',
      jenisKelamin: 'Perempuan',
      kewarganegaraan: 'WNI',
      datangDari: 'Kel. Bahu, Kec. Malalayang, Kota Manado',
      tanggalDatang: '2026-10-11',
      pindahKe: '',
      tanggalPindah: '',
      meninggal: '',
      tanggalMeninggal: '',
      keterangan: 'Tenaga bidan penugasan Puskesmas Pembantu Desa Tiaro.'
    },
    {
      id: 'mut-tiaro-006',
      jenisMutasi: 'Pindah',
      namaLengkap: 'Rizky Ananda Saputri / Kiki',
      tempatLahir: 'Kendari',
      tanggalLahir: '2003-03-17',
      jenisKelamin: 'Perempuan',
      kewarganegaraan: 'WNI',
      datangDari: '',
      tanggalDatang: '',
      pindahKe: 'Kel. Tamalanrea Indah, Kec. Tamalanrea, Kota Makassar',
      tanggalPindah: '2026-10-14',
      meninggal: '',
      tanggalMeninggal: '',
      keterangan: 'SKPWNI No. 471.2/107/X/2026 — Pindah domisili melanjutkan studi.'
    },
    {
      id: 'mut-tiaro-007',
      jenisMutasi: 'Datang',
      namaLengkap: 'Kenji Takahashi / Kenji',
      tempatLahir: 'Osaka',
      tanggalLahir: '1986-07-09',
      jenisKelamin: 'Laki-laki',
      kewarganegaraan: 'WNA',
      datangDari: 'Kel. Karet Semanggi, Setiabudi, Jakarta Selatan',
      tanggalDatang: '2026-10-16',
      pindahKe: '',
      tanggalPindah: '',
      meninggal: '',
      tanggalMeninggal: '',
      keterangan: 'Pemegang KITAP — Pendamping teknis budidaya rumput laut pesisir.'
    },
    {
      id: 'mut-tiaro-008',
      jenisMutasi: 'Pindah',
      namaLengkap: 'Darwis Laode Mappatunru / Darwis',
      tempatLahir: 'Kolaka',
      tanggalLahir: '1985-05-21',
      jenisKelamin: 'Laki-laki',
      kewarganegaraan: 'WNI',
      datangDari: '',
      tanggalDatang: '',
      pindahKe: 'Desa Lantari, Kec. Lantari Jaya, Kab. Bombana',
      tanggalPindah: '2026-10-19',
      meninggal: '',
      tanggalMeninggal: '',
      keterangan: 'SKPWNI No. 471.2/112/X/2026 — Pindah satu keluarga mengelola lahan pertanian.'
    },
    {
      id: 'mut-tiaro-009',
      jenisMutasi: 'Meninggal',
      namaLengkap: 'Hj. Siti Aminah Lasaima / Aminah',
      tempatLahir: 'Tiaro',
      tanggalLahir: '1952-09-14',
      jenisKelamin: 'Perempuan',
      kewarganegaraan: 'WNI',
      datangDari: '',
      tanggalDatang: '',
      pindahKe: '',
      tanggalPindah: '',
      meninggal: 'RSUD Kabupaten Bombana',
      tanggalMeninggal: '2026-10-22',
      keterangan: 'Surat Ket. Kematian No. 474.3/015/X/2026 — Dimakamkan di TPU Desa Tiaro.'
    },
    {
      id: 'mut-tiaro-010',
      jenisMutasi: 'Datang',
      namaLengkap: 'Ilham Maulana Yusuf / Ilham',
      tempatLahir: 'Kasipute',
      tanggalLahir: '1996-02-08',
      jenisKelamin: 'Laki-laki',
      kewarganegaraan: 'WNI',
      datangDari: 'Kel. Doule, Kec. Rumbia, Kab. Bombana',
      tanggalDatang: '2026-10-25',
      pindahKe: '',
      tanggalPindah: '',
      meninggal: '',
      tanggalMeninggal: '',
      keterangan: 'SKDWNI No. 471.2/096/X/2026 — Menempati rumah baru di Dusun II Mekar.'
    },
    {
      id: 'mut-tiaro-011',
      jenisMutasi: 'Pindah',
      namaLengkap: 'Nadia Maharani Putri / Nadia',
      tempatLahir: 'Bombana',
      tanggalLahir: '1999-12-04',
      jenisKelamin: 'Perempuan',
      kewarganegaraan: 'WNI',
      datangDari: '',
      tanggalDatang: '',
      pindahKe: 'Kel. Lamokato, Kec. Kolaka, Kab. Kolaka',
      tanggalPindah: '2026-10-28',
      meninggal: '',
      tanggalMeninggal: '',
      keterangan: 'SKPWNI No. 471.2/118/X/2026 — Pindah mengikuti suami setelah menikah.'
    },

    // ===================== PERIODE SEPTEMBER 2026 (4 DATA) ====================
    {
      id: 'mut-tiaro-012',
      jenisMutasi: 'Datang',
      namaLengkap: 'Ruslan Efendi Tahir / Ruslan',
      tempatLahir: 'Bone',
      tanggalLahir: '1989-03-10',
      jenisKelamin: 'Laki-laki',
      kewarganegaraan: 'WNI',
      datangDari: 'Kel. Jeppee, Kec. Tanete Riattang Barat, Kab. Bone',
      tanggalDatang: '2026-09-04',
      pindahKe: '',
      tanggalPindah: '',
      meninggal: '',
      tanggalMeninggal: '',
      keterangan: 'SKDWNI No. 471.2/074/IX/2026 — Domisili usaha perikanan di Dusun III Pesisir.'
    },
    {
      id: 'mut-tiaro-013',
      jenisMutasi: 'Pindah',
      namaLengkap: 'Sartika Dewi Anggraini / Tika',
      tempatLahir: 'Tiaro',
      tanggalLahir: '2000-07-22',
      jenisKelamin: 'Perempuan',
      kewarganegaraan: 'WNI',
      datangDari: '',
      tanggalDatang: '',
      pindahKe: 'Kel. Lepo-Lepo, Kec. Baruga, Kota Kendari',
      tanggalPindah: '2026-09-12',
      meninggal: '',
      tanggalMeninggal: '',
      keterangan: 'SKPWNI No. 471.2/088/IX/2026 — Pindah domisili pekerjaan.'
    },
    {
      id: 'mut-tiaro-014',
      jenisMutasi: 'Meninggal',
      namaLengkap: 'La Ode Mustari Lamba / Mustari',
      tempatLahir: 'Muna',
      tanggalLahir: '1955-01-16',
      jenisKelamin: 'Laki-laki',
      kewarganegaraan: 'WNI',
      datangDari: '',
      tanggalDatang: '',
      pindahKe: '',
      tanggalPindah: '',
      meninggal: 'Desa Tiaro (Dusun II Mekar)',
      tanggalMeninggal: '2026-09-19',
      keterangan: 'Surat Ket. Kematian No. 474.3/011/IX/2026 — Tercatat pada buku pokok pemakaman.'
    },
    {
      id: 'mut-tiaro-015',
      jenisMutasi: 'Datang',
      namaLengkap: 'Hasnawati Binti Rahman / Hasna',
      tempatLahir: 'Kolaka Timur',
      tanggalLahir: '1992-10-05',
      jenisKelamin: 'Perempuan',
      kewarganegaraan: 'WNI',
      datangDari: 'Desa Tirawuta, Kec. Tirawuta, Kab. Kolaka Timur',
      tanggalDatang: '2026-09-26',
      pindahKe: '',
      tanggalPindah: '',
      meninggal: '',
      tanggalMeninggal: '',
      keterangan: 'SKDWNI No. 471.2/081/IX/2026 — Penggabungan Kartu Keluarga di Dusun IV Harapan.'
    },

    // ===================== PERIODE DESEMBER 2025 (2 DATA) =====================
    {
      id: 'mut-tiaro-016',
      jenisMutasi: 'Datang',
      namaLengkap: 'Ardiansyah Putra Lasappe / Ardi',
      tempatLahir: 'Makassar',
      tanggalLahir: '1990-06-14',
      jenisKelamin: 'Laki-laki',
      kewarganegaraan: 'WNI',
      datangDari: 'Kel. Sudiang Raya, Kec. Biringkanaya, Kota Makassar',
      tanggalDatang: '2025-12-09',
      pindahKe: '',
      tanggalPindah: '',
      meninggal: '',
      tanggalMeninggal: '',
      keterangan: 'Arsip mutasi akhir tahun 2025 — Domisili Dusun I Krajan.'
    },
    {
      id: 'mut-tiaro-017',
      jenisMutasi: 'Pindah',
      namaLengkap: 'Yuliana Kartika Sari / Yuli',
      tempatLahir: 'Tiaro',
      tanggalLahir: '1997-04-28',
      jenisKelamin: 'Perempuan',
      kewarganegaraan: 'WNI',
      datangDari: '',
      tanggalDatang: '',
      pindahKe: 'Kel. Kasipute, Kec. Rumbia, Kab. Bombana',
      tanggalPindah: '2025-12-18',
      meninggal: '',
      tanggalMeninggal: '',
      keterangan: 'Arsip SKPWNI akhir tahun 2025.'
    }
  ];

  function cloneSeedData() {
    return SEED_DATA.map((item) => ({ ...item }));
  }

  function sanitizeRecord(raw, idx = 0) {
    const jenisRaw = String(raw?.jenisMutasi || '').trim();
    let jenisMutasi = 'Datang';
    if (jenisRaw === 'Pindah' || jenisRaw === 'Meninggal' || jenisRaw === 'Datang') {
      jenisMutasi = jenisRaw;
    } else if (raw?.pindahKe || raw?.tanggalPindah) {
      jenisMutasi = 'Pindah';
    } else if (raw?.meninggal || raw?.tanggalMeninggal) {
      jenisMutasi = 'Meninggal';
    }

    return {
      id: String(raw?.id || `mut-tiaro-${Date.now()}-${idx}`),
      jenisMutasi,
      namaLengkap: String(raw?.namaLengkap || '').trim(),
      tempatLahir: String(raw?.tempatLahir || '').trim(),
      tanggalLahir: String(raw?.tanggalLahir || '').trim(),
      jenisKelamin: raw?.jenisKelamin === 'Perempuan' ? 'Perempuan' : 'Laki-laki',
      kewarganegaraan: raw?.kewarganegaraan === 'WNA' ? 'WNA' : 'WNI',
      datangDari: jenisMutasi === 'Datang' ? String(raw?.datangDari || '').trim() : '',
      tanggalDatang: jenisMutasi === 'Datang' ? String(raw?.tanggalDatang || '').trim() : '',
      pindahKe: jenisMutasi === 'Pindah' ? String(raw?.pindahKe || '').trim() : '',
      tanggalPindah: jenisMutasi === 'Pindah' ? String(raw?.tanggalPindah || '').trim() : '',
      meninggal: jenisMutasi === 'Meninggal' ? String(raw?.meninggal || '').trim() : '',
      tanggalMeninggal: jenisMutasi === 'Meninggal' ? String(raw?.tanggalMeninggal || '').trim() : '',
      keterangan: String(raw?.keterangan || '').trim()
    };
  }

  function loadRecords() {
    try {
      const raw = localStorage.getItem(STORAGE_KEY);
      if (!raw) {
        const seeded = cloneSeedData();
        localStorage.setItem(STORAGE_KEY, JSON.stringify(seeded));
        return seeded;
      }
      const parsed = JSON.parse(raw);
      if (!Array.isArray(parsed)) {
        const seeded = cloneSeedData();
        localStorage.setItem(STORAGE_KEY, JSON.stringify(seeded));
        return seeded;
      }
      return parsed.map((item, i) => sanitizeRecord(item, i));
    } catch (_) {
      return cloneSeedData();
    }
  }

  function saveRecords(list) {
    try {
      localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
    } catch (_) {
      // Abaikan jika mode penyimpanan privat penuh
    }
  }

  function esc(val) {
    if (window.SimdesApp?.escapeHtml) {
      return window.SimdesApp.escapeHtml(val);
    }
    return String(val ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');
  }

  function formatDate(iso) {
    if (!iso) return '';
    if (window.SimdesTable?.formatDateIndo) {
      return window.SimdesTable.formatDateIndo(iso);
    }
    return iso;
  }

  function getEffectiveMutationDate(record) {
    if (!record) return '';
    if (record.jenisMutasi === 'Pindah') return record.tanggalPindah || '';
    if (record.jenisMutasi === 'Meninggal') return record.tanggalMeninggal || '';
    return record.tanggalDatang || '';
  }

  function extractYearMonth(isoDate) {
    const clean = String(isoDate || '').trim();
    const parts = clean.split('-');
    if (parts.length < 2) return { year: '', month: '' };
    return {
      year: parts[0] || '',
      month: (parts[1] || '').padStart(2, '0')
    };
  }

  function formatPeriodTitleText(bulanVal, tahunVal) {
    const bulanLabel =
      bulanVal === 'ALL'
        ? 'JANUARI–DESEMBER'
        : (BULAN_MAP[bulanVal] || 'OKTOBER').toUpperCase();
    const tahunLabel = tahunVal === 'ALL' ? 'SEMUA TAHUN' : String(tahunVal || DEFAULT_TAHUN);
    return `BUKU MUTASI PENDUDUK DESA BULAN ${bulanLabel} TAHUN ${tahunLabel}`;
  }

  function formatPeriodShortLabel(isoDate) {
    const { year, month } = extractYearMonth(isoDate);
    if (!year || !BULAN_MAP[month]) return '-';
    return `${BULAN_MAP[month]} ${year}`;
  }

  function getJenisMutasiBadgeClass(jenis) {
    if (jenis === 'Datang') return 'badge-simdes badge-simdes-success';
    if (jenis === 'Pindah') return 'badge-simdes badge-simdes-accent';
    if (jenis === 'Meninggal') return 'badge-simdes badge-simdes-danger';
    return 'badge-simdes badge-simdes-neutral';
  }

  function compareIsoDates(daRaw, dbRaw) {
    const da = String(daRaw || '').trim();
    const db = String(dbRaw || '').trim();
    if (!da && !db) return 0;
    if (!da) return 1;
    if (!db) return -1;
    return da.localeCompare(db);
  }

  function initBukuMutasiPendudukDesa() {
    const pageRoot = document.getElementById('bukuMutasiPendudukPageRoot');
    if (!pageRoot || !window.SimdesTable) return;

    let records = loadRecords();
    let activeDetailId = null;
    let pendingDeleteId = null;

    // Period Controls & Headings
    const bookPeriodTitleEl = document.getElementById('bookPeriodTitle');
    const printPeriodTitleEl = document.getElementById('printPeriodTitle');
    const printMetaEl = document.getElementById('printMutasiMeta');
    const periodBulanSelect = document.getElementById('periodBulanSelect');
    const periodTahunSelect = document.getElementById('periodTahunSelect');

    // KPI Summary Elements
    const statTotalEl = document.getElementById('statTotalMutasi');
    const statDatangEl = document.getElementById('statMutasiDatang');
    const statPindahEl = document.getElementById('statMutasiPindah');
    const statMeninggalEl = document.getElementById('statMutasiMeninggal');

    // Toolbar & Table Elements
    const btnTambah = document.getElementById('btnTambahMutasi');
    const btnCetak = document.getElementById('btnCetakMutasi');
    const btnResetSample = document.getElementById('btnResetSampleMutasi');

    const searchInput = document.getElementById('tableSearchInput');
    const clearSearchBtn = document.getElementById('clearSearchInputBtn');
    const filterJenisMutasiSelect = document.getElementById('filterJenisMutasiSelect');
    const filterGenderSelect = document.getElementById('filterGenderSelect');
    const pageSizeSelect = document.getElementById('pageSizeSelect');
    const resetFilterBtn = document.getElementById('resetTableFilterBtn');

    const tableEl = document.getElementById('mutasiTable');
    const tbodyEl = document.getElementById('mutasiTableBody');
    const paginationInfoEl = document.getElementById('tablePaginationInfo');
    const paginationListEl = document.getElementById('tablePaginationList');

    // Form Modal Elements
    const formModalEl = document.getElementById('mutasiFormModal');
    const formModalTitle = document.getElementById('mutasiFormModalLabel');
    const formEl = document.getElementById('mutasiForm');
    const formAlert = document.getElementById('mutasiFormAlert');
    const formAlertText = document.getElementById('mutasiFormAlertText');
    const formSubmitBtn = document.getElementById('mutasiFormSubmitBtn');

    const fId = document.getElementById('mutasiRecordId');
    const fNomorUrutPreview = document.getElementById('mutasiNomorUrutPreview');
    const fNama = document.getElementById('mutasiNama');
    const fTempatLahir = document.getElementById('mutasiTempatLahir');
    const fTanggalLahir = document.getElementById('mutasiTanggalLahir');

    const fieldsetPenambahan = document.getElementById('fieldsetPenambahan');
    const fDatangDari = document.getElementById('mutasiDatangDari');
    const fTanggalDatang = document.getElementById('mutasiTanggalDatang');

    const fieldsetPengurangan = document.getElementById('fieldsetPengurangan');
    const subGroupPindah = document.getElementById('subGroupPindah');
    const fPindahKe = document.getElementById('mutasiPindahKe');
    const fTanggalPindah = document.getElementById('mutasiTanggalPindah');

    const subGroupMeninggal = document.getElementById('subGroupMeninggal');
    const fMeninggal = document.getElementById('mutasiMeninggal');
    const fTanggalMeninggal = document.getElementById('mutasiTanggalMeninggal');

    const fKeterangan = document.getElementById('mutasiKeterangan');

    // Detail & Delete Modal Elements
    const detailModalEl = document.getElementById('mutasiDetailModal');
    const btnEditFromDetail = document.getElementById('btnEditMutasiFromDetail');

    const deleteModalEl = document.getElementById('mutasiDeleteModal');
    const deleteTargetNameEl = document.getElementById('deleteMutasiTargetName');
    const deleteTargetJenisEl = document.getElementById('deleteMutasiTargetJenis');
    const deleteTargetMetaEl = document.getElementById('deleteMutasiTargetMeta');
    const confirmDeleteBtn = document.getElementById('confirmDeleteMutasiBtn');

    window.SimdesForms?.InteractionValidator?.bindForm(formEl);

    function syncYearSelectOptions(preferredYear = null) {
      if (!periodTahunSelect) return;
      const currentVal = preferredYear || periodTahunSelect.value || DEFAULT_TAHUN;
      const yearSet = new Set(['2026', '2025']);

      records.forEach((r) => {
        const { year } = extractYearMonth(getEffectiveMutationDate(r));
        if (year && /^\d{4}$/.test(year)) {
          yearSet.add(year);
        }
      });

      if (currentVal && currentVal !== 'ALL' && /^\d{4}$/.test(currentVal)) {
        yearSet.add(currentVal);
      }

      const sortedYears = Array.from(yearSet).sort((a, b) => b.localeCompare(a));
      periodTahunSelect.innerHTML = [
        ...sortedYears.map((yr) => `<option value="${esc(yr)}">${esc(yr)}</option>`),
        '<option value="ALL">Semua Tahun</option>'
      ].join('');

      if (currentVal === 'ALL' || sortedYears.includes(currentVal)) {
        periodTahunSelect.value = currentVal;
      } else {
        periodTahunSelect.value = DEFAULT_TAHUN;
      }
    }

    function getActivePeriodSelection() {
      return {
        bulan: periodBulanSelect?.value || DEFAULT_BULAN,
        tahun: periodTahunSelect?.value || DEFAULT_TAHUN
      };
    }

    function isNonDefaultPeriodSelected() {
      const { bulan, tahun } = getActivePeriodSelection();
      return bulan !== DEFAULT_BULAN || tahun !== DEFAULT_TAHUN;
    }

    function updateBookPeriodHeadings() {
      const { bulan, tahun } = getActivePeriodSelection();
      const titleText = formatPeriodTitleText(bulan, tahun);
      if (bookPeriodTitleEl) bookPeriodTitleEl.textContent = titleText;
      if (printPeriodTitleEl) printPeriodTitleEl.textContent = titleText;
    }

    function getPeriodFilteredRecords() {
      const { bulan, tahun } = getActivePeriodSelection();
      const filtered = records.filter((item) => {
        const effDate = getEffectiveMutationDate(item);
        const { year, month } = extractYearMonth(effDate);
        if (tahun !== 'ALL' && year !== tahun) return false;
        if (bulan !== 'ALL' && month !== bulan) return false;
        return true;
      });

      // Urutkan kronologis berdasarkan tanggal mutasi aktif (lalu urutan simpan)
      const indexMap = new Map(records.map((r, i) => [r.id, i]));
      filtered.sort((a, b) => {
        const cmpDate = compareIsoDates(getEffectiveMutationDate(a), getEffectiveMutationDate(b));
        if (cmpDate !== 0) return cmpDate;
        return (indexMap.get(a.id) ?? 0) - (indexMap.get(b.id) ?? 0);
      });

      return filtered.map((item, idx) => ({
        ...item,
        urutan: idx + 1
      }));
    }

    function updateSummaryCards(displayedRows) {
      const list = Array.isArray(displayedRows) ? displayedRows : [];
      const total = list.length;
      let datang = 0;
      let pindah = 0;
      let meninggal = 0;

      list.forEach((row) => {
        if (row.jenisMutasi === 'Datang') datang += 1;
        else if (row.jenisMutasi === 'Pindah') pindah += 1;
        else if (row.jenisMutasi === 'Meninggal') meninggal += 1;
      });

      if (statTotalEl) statTotalEl.textContent = total.toLocaleString('id-ID');
      if (statDatangEl) statDatangEl.textContent = datang.toLocaleString('id-ID');
      if (statPindahEl) statPindahEl.textContent = pindah.toLocaleString('id-ID');
      if (statMeninggalEl) statMeninggalEl.textContent = meninggal.toLocaleString('id-ID');
    }

    function syncSortHeadersUI(state) {
      if (!tableEl) return;
      tableEl.querySelectorAll('th [data-sort-key]').forEach((btn) => {
        const key = btn.getAttribute('data-sort-key');
        const th = btn.closest('th');
        const icon = btn.querySelector('.th-sort-icon');
        const isActive = state.sortKey === key;

        btn.classList.toggle('is-sorted', isActive);
        if (th) {
          th.setAttribute(
            'aria-sort',
            isActive ? (state.sortDirection === 'asc' ? 'ascending' : 'descending') : 'none'
          );
        }
        if (icon) {
          icon.className = isActive
            ? `fa-solid ${state.sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down'} th-sort-icon no-print`
            : 'fa-solid fa-sort th-sort-icon no-print';
        }
      });
    }

    function renderRowsHtml(rowsList, startIndex = 0, state = null) {
      const hasQueryOrPeriodFilter = Boolean(state?.hasActiveQuery) || isNonDefaultPeriodSelected();

      if (rowsList.length === 0) {
        const { bulan, tahun } = getActivePeriodSelection();
        const periodInfo =
          bulan === 'ALL'
            ? `Tahun ${tahun === 'ALL' ? 'Semua Tahun' : tahun}`
            : `Bulan ${BULAN_MAP[bulan] || ''} ${tahun === 'ALL' ? '' : tahun}`.trim();

        return `
          <tr>
            <td colspan="13">
              <div class="table-empty-state">
                <div class="table-empty-icon">
                  <i class="fa-solid fa-people-arrows" aria-hidden="true"></i>
                </div>
                <div class="fw-bold mb-1" style="color:var(--simdes-text); font-size:0.95rem;">
                  Belum Ada Catatan Mutasi pada Periode ${esc(periodInfo)}
                </div>
                <p class="mb-3" style="font-size:0.83rem; max-width:48ch; margin-inline:auto;">
                  Tidak ditemukan data mutasi penduduk yang cocok dengan periode atau filter pencarian saat ini. Silakan tambah mutasi baru atau sesuaikan filter.
                </p>
                <div class="d-inline-flex flex-wrap justify-content-center gap-2 no-print">
                  ${
                    hasQueryOrPeriodFilter
                      ? `<button type="button" class="btn-simdes-outline" data-empty-action="reset-filter">
                           <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                           <span>Reset Filter &amp; Periode</span>
                         </button>`
                      : ''
                  }
                  <button type="button" class="btn-simdes-primary" data-empty-action="add-new">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                    <span>Tambah Mutasi</span>
                  </button>
                </div>
              </div>
            </td>
          </tr>
        `;
      }

      const totalFiltered = state?.totalItems ?? rowsList.length;
      const isDescUrutan = state?.sortKey === 'urutan' && state?.sortDirection === 'desc';

      return rowsList
        .map((row, idx) => {
          // Nomor urut selalu dibuat otomatis dan konsisten
          const noUrut = isDescUrutan
            ? Math.max(1, totalFiltered - (startIndex + idx))
            : startIndex + idx + 1;

          const isDatang = row.jenisMutasi === 'Datang';
          const isPindah = row.jenisMutasi === 'Pindah';
          const isMeninggal = row.jenisMutasi === 'Meninggal';

          const tglLahirCell = row.tanggalLahir
            ? esc(formatDate(row.tanggalLahir))
            : '<span class="text-simdes-muted">&mdash;</span>';

          const genderBadge =
            row.jenisKelamin === 'Perempuan'
              ? '<span class="badge-simdes badge-simdes-accent no-print">Perempuan</span><span class="print-only-inline">Perempuan</span>'
              : '<span class="badge-simdes badge-simdes-primary no-print">Laki-laki</span><span class="print-only-inline">Laki-laki</span>';

          // Kolom 7 & 8: PENAMBAHAN (DATANG DARI & TANGGAL)
          const datangDariCell =
            isDatang && row.datangDari
              ? `<span class="fw-medium">${esc(row.datangDari)}</span>`
              : '<span class="text-simdes-muted">&mdash;</span>';

          const tglDatangCell =
            isDatang && row.tanggalDatang
              ? `<span class="fw-semibold" style="color:var(--simdes-primary);">${esc(formatDate(row.tanggalDatang))}</span>`
              : '<span class="text-simdes-muted">&mdash;</span>';

          // Kolom 9 & 10: PENGURANGAN — PINDAH KE & TANGGAL
          const pindahKeCell =
            isPindah && row.pindahKe
              ? `<span class="fw-medium">${esc(row.pindahKe)}</span>`
              : '<span class="text-simdes-muted">&mdash;</span>';

          const tglPindahCell =
            isPindah && row.tanggalPindah
              ? `<span class="fw-semibold" style="color:#8A6416;">${esc(formatDate(row.tanggalPindah))}</span>`
              : '<span class="text-simdes-muted">&mdash;</span>';

          // Kolom 11 & 12: PENGURANGAN — MENINGGAL & TANGGAL
          const meninggalCell =
            isMeninggal && row.meninggal
              ? `<span class="fw-medium">${esc(row.meninggal)}</span>`
              : '<span class="text-simdes-muted">&mdash;</span>';

          const tglMeninggalCell =
            isMeninggal && row.tanggalMeninggal
              ? `<span class="fw-semibold" style="color:var(--simdes-danger);">${esc(formatDate(row.tanggalMeninggal))}</span>`
              : '<span class="text-simdes-muted">&mdash;</span>';

          return `
            <tr data-row-id="${esc(row.id)}">
              <!-- 1. NOMOR URUT -->
              <td class="col-no-urut sticky-col-first group-divider-end">${noUrut}</td>
              <!-- 2. NAMA LENGKAP/PANGGILAN -->
              <td>
                <button
                  type="button"
                  class="tentang-title-btn"
                  data-row-action="detail"
                  data-id="${esc(row.id)}"
                  title="Lihat detail mutasi ${esc(row.namaLengkap)}"
                >
                  ${esc(row.namaLengkap)}
                </button>
              </td>
              <!-- 3. TEMPAT (Lahir) -->
              <td style="white-space:nowrap;">${row.tempatLahir ? esc(row.tempatLahir) : '<span class="text-simdes-muted">&mdash;</span>'}</td>
              <!-- 4. TANGGAL (Lahir) -->
              <td class="tabular-nums text-center group-divider-end" style="white-space:nowrap;">${tglLahirCell}</td>
              <!-- 5. JENIS KELAMIN -->
              <td class="text-center" style="white-space:nowrap;">${genderBadge}</td>
              <!-- 6. KEWARGANEGARAAN -->
              <td class="text-center fw-semibold group-divider-end" style="white-space:nowrap;">${esc(row.kewarganegaraan || 'WNI')}</td>
              <!-- 7. DATANG DARI -->
              <td>${datangDariCell}</td>
              <!-- 8. TANGGAL (Kedatangan) -->
              <td class="tabular-nums text-center group-divider-end" style="white-space:nowrap;">${tglDatangCell}</td>
              <!-- 9. PINDAH KE -->
              <td>${pindahKeCell}</td>
              <!-- 10. TANGGAL (Pindah) -->
              <td class="tabular-nums text-center group-divider-end" style="white-space:nowrap;">${tglPindahCell}</td>
              <!-- 11. MENINGGAL -->
              <td>${meninggalCell}</td>
              <!-- 12. TANGGAL (Meninggal) -->
              <td class="tabular-nums text-center group-divider-end" style="white-space:nowrap;">${tglMeninggalCell}</td>
              <!-- 13. KET -->
              <td>
                <div class="ket-cell-content">
                  <div class="ket-text">
                    ${row.keterangan ? esc(row.keterangan) : '<span class="text-simdes-muted">&mdash;</span>'}
                  </div>
                  <div class="row-action-group no-print" role="group" aria-label="Aksi baris ${noUrut}">
                    <button
                      type="button"
                      class="btn-row-action"
                      data-row-action="detail"
                      data-id="${esc(row.id)}"
                      title="Lihat Detail Mutasi"
                      aria-label="Lihat detail ${esc(row.namaLengkap)}"
                    >
                      <i class="fa-regular fa-eye" aria-hidden="true"></i>
                    </button>
                    <button
                      type="button"
                      class="btn-row-action"
                      data-row-action="edit"
                      data-id="${esc(row.id)}"
                      title="Edit Data Mutasi"
                      aria-label="Edit data ${esc(row.namaLengkap)}"
                    >
                      <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
                    </button>
                    <button
                      type="button"
                      class="btn-row-action action-delete"
                      data-row-action="delete"
                      data-id="${esc(row.id)}"
                      title="Hapus Data Mutasi"
                      aria-label="Hapus data ${esc(row.namaLengkap)}"
                    >
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

    syncYearSelectOptions(DEFAULT_TAHUN);
    updateBookPeriodHeadings();

    const tableCtrl = window.SimdesTable.create({
      data: getPeriodFilteredRecords(),
      pageSize: 10,
      defaultSortKey: 'urutan',
      defaultSortDirection: 'asc',
      searchFields: ['namaLengkap'],
      comparators: {
        urutan: (a, b) => (a.urutan || 0) - (b.urutan || 0),
        tanggalLahir: (a, b) => compareIsoDates(a.tanggalLahir, b.tanggalLahir),
        tanggalDatang: (a, b) => compareIsoDates(a.tanggalDatang, b.tanggalDatang),
        tanggalPindah: (a, b) => compareIsoDates(a.tanggalPindah, b.tanggalPindah),
        tanggalMeninggal: (a, b) => compareIsoDates(a.tanggalMeninggal, b.tanggalMeninggal)
      },
      onRender: (state) => {
        syncSortHeadersUI(state);
        updateSummaryCards(state.allFilteredRows);

        if (resetFilterBtn) {
          resetFilterBtn.disabled = !(state.hasActiveQuery || isNonDefaultPeriodSelected());
        }

        if (tbodyEl) {
          tbodyEl.innerHTML = renderRowsHtml(state.rows, state.startIndex, state);
        }

        if (paginationInfoEl) {
          paginationInfoEl.innerHTML =
            state.totalItems === 0
              ? 'Menampilkan <strong>0</strong> dari <strong>0</strong> data mutasi penduduk'
              : `Menampilkan <strong>${state.startEntry}&ndash;${state.endEntry}</strong> dari <strong>${state.totalItems}</strong> mutasi penduduk${
                  state.hasActiveQuery ? ` (disaring dari ${state.totalAllItems} mutasi periode ini)` : ''
                }`;
        }

        window.SimdesTable.renderPagination(paginationListEl, state, (page) => {
          tableCtrl.setPage(page);
        });
      }
    });

    function refreshPeriodTable(preservePage = false) {
      updateBookPeriodHeadings();
      tableCtrl.setData(getPeriodFilteredRecords(), preservePage);
    }

    // =========================================================================
    // Conditional Form Controls Based on Jenis Mutasi (Datang / Pindah / Meninggal)
    // =========================================================================
    function getSelectedRadio(name, fallback = '') {
      if (!formEl) return fallback;
      const checked = formEl.querySelector(`input[type="radio"][name="${name}"]:checked`);
      return checked ? checked.value : fallback;
    }

    function setSelectedRadio(name, value) {
      if (!formEl) return;
      formEl.querySelectorAll(`input[type="radio"][name="${name}"]`).forEach((radio) => {
        radio.checked = radio.value === value;
      });
    }

    function clearControlValidation(input) {
      if (!input) return;
      input.setCustomValidity('');
      input.classList.remove('user-invalid-fallback', 'user-valid-fallback', 'is-invalid-field');
      input.removeAttribute('aria-invalid');
      input.closest('.simdes-field')?.classList.remove('has-error');
    }

    function setGroupControlsActive(controls, active) {
      controls.forEach((ctrl) => {
        if (!ctrl) return;
        ctrl.disabled = !active;
        if (active) {
          ctrl.setAttribute('required', '');
        } else {
          ctrl.removeAttribute('required');
          ctrl.value = '';
          clearControlValidation(ctrl);
        }
      });
    }

    function syncNomorUrutPreview() {
      if (!fNomorUrutPreview) return;
      const editId = fId?.value || '';
      const jenis = getSelectedRadio('jenisMutasi', 'Datang');
      const rawDate =
        jenis === 'Pindah'
          ? fTanggalPindah?.value
          : jenis === 'Meninggal'
            ? fTanggalMeninggal?.value
            : fTanggalDatang?.value;

      let targetBulan = periodBulanSelect?.value || DEFAULT_BULAN;
      let targetTahun = periodTahunSelect?.value || DEFAULT_TAHUN;

      if (rawDate) {
        const parts = extractYearMonth(rawDate);
        if (parts.month && parts.year) {
          targetBulan = parts.month;
          targetTahun = parts.year;
        }
      }

      const periodName =
        targetBulan === 'ALL'
          ? `Tahun ${targetTahun}`
          : `${BULAN_MAP[targetBulan] || 'Oktober'} ${targetTahun === 'ALL' ? '' : targetTahun}`.trim();

      const matchingInPeriod = records.filter((r) => {
        const { year, month } = extractYearMonth(getEffectiveMutationDate(r));
        if (targetTahun !== 'ALL' && year !== targetTahun) return false;
        if (targetBulan !== 'ALL' && month !== targetBulan) return false;
        return true;
      });

      if (editId) {
        const existingIdx = matchingInPeriod.findIndex((r) => r.id === editId);
        const seqNum = existingIdx >= 0 ? existingIdx + 1 : matchingInPeriod.length + 1;
        fNomorUrutPreview.value = `#${seqNum} (${periodName})`;
      } else {
        fNomorUrutPreview.value = `Otomatis (#${matchingInPeriod.length + 1} — ${periodName})`;
      }
    }

    function syncFormSectionsByJenis() {
      const jenis = getSelectedRadio('jenisMutasi', 'Datang');
      const isDatang = jenis === 'Datang';
      const isPindah = jenis === 'Pindah';
      const isMeninggal = jenis === 'Meninggal';

      if (fieldsetPenambahan) {
        fieldsetPenambahan.hidden = !isDatang;
        fieldsetPenambahan.disabled = !isDatang;
      }
      setGroupControlsActive([fDatangDari, fTanggalDatang], isDatang);

      const showPengurangan = isPindah || isMeninggal;
      if (fieldsetPengurangan) {
        fieldsetPengurangan.hidden = !showPengurangan;
        fieldsetPengurangan.disabled = !showPengurangan;
      }

      if (subGroupPindah) {
        subGroupPindah.hidden = !isPindah;
      }
      setGroupControlsActive([fPindahKe, fTanggalPindah], isPindah);

      if (subGroupMeninggal) {
        subGroupMeninggal.hidden = !isMeninggal;
      }
      setGroupControlsActive([fMeninggal, fTanggalMeninggal], isMeninggal);

      syncNomorUrutPreview();
    }

    formEl?.querySelectorAll('input[name="jenisMutasi"]').forEach((radio) => {
      radio.addEventListener('change', () => {
        hideFormAlert();
        syncFormSectionsByJenis();
      });
    });

    [fTanggalDatang, fTanggalPindah, fTanggalMeninggal].forEach((dateCtrl) => {
      dateCtrl?.addEventListener('change', syncNomorUrutPreview);
    });

    function showFormAlert(msg) {
      if (!formAlert || !formAlertText) return;
      formAlertText.textContent = msg;
      formAlert.classList.add('show');
    }

    function hideFormAlert() {
      formAlert?.classList.remove('show');
      if (formAlertText) formAlertText.textContent = '';
    }

    function getDefaultDateForActivePeriod() {
      const { bulan, tahun } = getActivePeriodSelection();
      const yr = tahun !== 'ALL' && /^\d{4}$/.test(tahun) ? tahun : DEFAULT_TAHUN;
      const mo = bulan !== 'ALL' && BULAN_MAP[bulan] ? bulan : DEFAULT_BULAN;
      return `${yr}-${mo}-01`;
    }

    // =========================================================================
    // Add, Edit, Detail & Delete Workflows
    // =========================================================================
    function openAddModal() {
      if (!formEl || !formModalEl) return;
      formEl.reset();
      hideFormAlert();

      if (fId) fId.value = '';
      setSelectedRadio('jenisMutasi', 'Datang');
      setSelectedRadio('jenisKelamin', '');
      setSelectedRadio('kewarganegaraan', 'WNI');

      syncFormSectionsByJenis();

      if (fTanggalDatang) {
        fTanggalDatang.value = getDefaultDateForActivePeriod();
      }
      syncNomorUrutPreview();

      if (formModalTitle) {
        formModalTitle.innerHTML =
          '<i class="fa-solid fa-plus-circle text-simdes-primary me-2" aria-hidden="true"></i>Tambah Data Mutasi Penduduk';
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML =
          '<i class="fa-solid fa-floppy-disk" aria-hidden="true"></i><span>Simpan Mutasi</span>';
      }

      window.bootstrap?.Modal.getOrCreateInstance(formModalEl).show();
      formModalEl.addEventListener('shown.bs.modal', () => fNama?.focus(), { once: true });
    }

    function openEditModal(id) {
      const item = records.find((r) => r.id === id);
      if (!item || !formEl || !formModalEl) return;

      formEl.reset();
      hideFormAlert();

      if (fId) fId.value = item.id;
      setSelectedRadio('jenisMutasi', item.jenisMutasi || 'Datang');
      syncFormSectionsByJenis();

      if (fNama) fNama.value = item.namaLengkap || '';
      if (fTempatLahir) fTempatLahir.value = item.tempatLahir || '';
      if (fTanggalLahir) fTanggalLahir.value = item.tanggalLahir || '';
      setSelectedRadio('jenisKelamin', item.jenisKelamin || 'Laki-laki');
      setSelectedRadio('kewarganegaraan', item.kewarganegaraan || 'WNI');

      if (item.jenisMutasi === 'Datang') {
        if (fDatangDari) fDatangDari.value = item.datangDari || '';
        if (fTanggalDatang) fTanggalDatang.value = item.tanggalDatang || '';
      } else if (item.jenisMutasi === 'Pindah') {
        if (fPindahKe) fPindahKe.value = item.pindahKe || '';
        if (fTanggalPindah) fTanggalPindah.value = item.tanggalPindah || '';
      } else if (item.jenisMutasi === 'Meninggal') {
        if (fMeninggal) fMeninggal.value = item.meninggal || '';
        if (fTanggalMeninggal) fTanggalMeninggal.value = item.tanggalMeninggal || '';
      }

      if (fKeterangan) fKeterangan.value = item.keterangan || '';
      syncNomorUrutPreview();

      if (formModalTitle) {
        formModalTitle.innerHTML =
          '<i class="fa-regular fa-pen-to-square text-simdes-primary me-2" aria-hidden="true"></i>Edit Data Mutasi Penduduk';
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML =
          '<i class="fa-solid fa-check" aria-hidden="true"></i><span>Simpan Perubahan</span>';
      }

      window.bootstrap?.Modal.getOrCreateInstance(formModalEl).show();
      formModalEl.addEventListener('shown.bs.modal', () => fNama?.focus(), { once: true });
    }

    function openDetailModal(id) {
      const item = records.find((r) => r.id === id);
      if (!item || !detailModalEl) return;
      activeDetailId = item.id;

      const periodItems = getPeriodFilteredRecords();
      const inCurrentIdx = periodItems.findIndex((r) => r.id === item.id);
      const displaySeq = inCurrentIdx >= 0 ? inCurrentIdx + 1 : 1;

      const effDate = getEffectiveMutationDate(item);
      const jenis = item.jenisMutasi || 'Datang';

      const setText = (elId, text) => {
        const el = document.getElementById(elId);
        if (el) el.textContent = text;
      };

      setText('detMutasiNomorUrut', `#${displaySeq}`);
      setText('detMutasiPeriodeLabel', formatPeriodShortLabel(effDate));
      setText('detMutasiNama', item.namaLengkap || '—');

      const badgeEl = document.getElementById('detMutasiJenisBadge');
      if (badgeEl) {
        badgeEl.className = getJenisMutasiBadgeClass(jenis);
        badgeEl.textContent =
          jenis === 'Datang'
            ? 'Penambahan • Datang'
            : jenis === 'Pindah'
              ? 'Pengurangan • Pindah'
              : 'Pengurangan • Meninggal';
      }

      setText('detMutasiTempatLahir', item.tempatLahir || '—');
      setText('detMutasiTanggalLahir', item.tanggalLahir ? formatDate(item.tanggalLahir) : '—');
      setText('detMutasiGender', item.jenisKelamin || '—');
      setText('detMutasiKewarganegaraan', item.kewarganegaraan || 'WNI');

      setText('detMutasiDatangDari', jenis === 'Datang' && item.datangDari ? item.datangDari : '—');
      setText(
        'detMutasiTanggalDatang',
        jenis === 'Datang' && item.tanggalDatang ? formatDate(item.tanggalDatang) : '—'
      );

      setText('detMutasiPindahKe', jenis === 'Pindah' && item.pindahKe ? item.pindahKe : '—');
      setText(
        'detMutasiTanggalPindah',
        jenis === 'Pindah' && item.tanggalPindah ? formatDate(item.tanggalPindah) : '—'
      );

      setText('detMutasiMeninggal', jenis === 'Meninggal' && item.meninggal ? item.meninggal : '—');
      setText(
        'detMutasiTanggalMeninggal',
        jenis === 'Meninggal' && item.tanggalMeninggal ? formatDate(item.tanggalMeninggal) : '—'
      );

      setText('detMutasiKeterangan', item.keterangan || '—');

      window.bootstrap?.Modal.getOrCreateInstance(detailModalEl).show();
    }

    function openDeleteModal(id) {
      const item = records.find((r) => r.id === id);
      if (!item || !deleteModalEl) return;
      pendingDeleteId = item.id;

      const effDate = getEffectiveMutationDate(item);
      const detailMutasi =
        item.jenisMutasi === 'Datang'
          ? `Datang dari ${item.datangDari || '-'}`
          : item.jenisMutasi === 'Pindah'
            ? `Pindah ke ${item.pindahKe || '-'}`
            : `Meninggal (${item.meninggal || '-'})`;

      if (deleteTargetNameEl) deleteTargetNameEl.textContent = item.namaLengkap || '-';
      if (deleteTargetJenisEl) deleteTargetJenisEl.textContent = detailMutasi;
      if (deleteTargetMetaEl) {
        deleteTargetMetaEl.textContent = `Tanggal Mutasi: ${effDate ? formatDate(effDate) : '-'} • Periode ${formatPeriodShortLabel(effDate)}`;
      }

      window.bootstrap?.Modal.getOrCreateInstance(deleteModalEl).show();
    }

    btnEditFromDetail?.addEventListener('click', () => {
      if (!activeDetailId) return;
      const targetId = activeDetailId;
      window.bootstrap?.Modal.getOrCreateInstance(detailModalEl)?.hide();
      window.setTimeout(() => openEditModal(targetId), 180);
    });

    formEl?.addEventListener('submit', (e) => {
      e.preventDefault();
      hideFormAlert();

      const valid = window.SimdesForms?.InteractionValidator
        ? window.SimdesForms.InteractionValidator.validateAll(formEl)
        : formEl.checkValidity();

      if (!valid) {
        showFormAlert('Mohon lengkapi seluruh kolom wajib yang ditandai sebelum menyimpan data mutasi.');
        return;
      }

      const jenisMutasi = getSelectedRadio('jenisMutasi', 'Datang');
      const tanggalLahir = (fTanggalLahir?.value || '').trim();
      const activeMutasiDate =
        jenisMutasi === 'Pindah'
          ? (fTanggalPindah?.value || '').trim()
          : jenisMutasi === 'Meninggal'
            ? (fTanggalMeninggal?.value || '').trim()
            : (fTanggalDatang?.value || '').trim();

      if (tanggalLahir && activeMutasiDate && tanggalLahir > activeMutasiDate) {
        showFormAlert('Tanggal mutasi tidak boleh lebih awal daripada Tanggal Lahir penduduk.');
        const targetDateField =
          jenisMutasi === 'Pindah'
            ? fTanggalPindah
            : jenisMutasi === 'Meninggal'
              ? fTanggalMeninggal
              : fTanggalDatang;
        targetDateField?.focus();
        return;
      }

      const existingId = (fId?.value || '').trim();
      const payload = sanitizeRecord({
        id: existingId || `mut-tiaro-${Date.now()}`,
        jenisMutasi,
        namaLengkap: fNama?.value || '',
        tempatLahir: fTempatLahir?.value || '',
        tanggalLahir,
        jenisKelamin: getSelectedRadio('jenisKelamin', 'Laki-laki'),
        kewarganegaraan: getSelectedRadio('kewarganegaraan', 'WNI'),
        // Pastikan kolom mutasi jenis lain selalu dikosongkan (tidak terisi otomatis secara keliru)
        datangDari: jenisMutasi === 'Datang' ? fDatangDari?.value || '' : '',
        tanggalDatang: jenisMutasi === 'Datang' ? activeMutasiDate : '',
        pindahKe: jenisMutasi === 'Pindah' ? fPindahKe?.value || '' : '',
        tanggalPindah: jenisMutasi === 'Pindah' ? activeMutasiDate : '',
        meninggal: jenisMutasi === 'Meninggal' ? fMeninggal?.value || '' : '',
        tanggalMeninggal: jenisMutasi === 'Meninggal' ? activeMutasiDate : '',
        keterangan: fKeterangan?.value || ''
      });

      const isEdit = Boolean(existingId);
      if (isEdit) {
        records = records.map((r) => (r.id === existingId ? payload : r));
      } else {
        records.push(payload);
      }
      saveRecords(records);

      // Sesuaikan pilihan periode jika data yang disimpan berada pada bulan/tahun berbeda
      const { year: savedYear, month: savedMonth } = extractYearMonth(activeMutasiDate);
      syncYearSelectOptions(savedYear || null);

      const curBulan = periodBulanSelect?.value || DEFAULT_BULAN;
      const curTahun = periodTahunSelect?.value || DEFAULT_TAHUN;
      let switchedPeriod = false;

      if (savedYear && curTahun !== 'ALL' && curTahun !== savedYear && periodTahunSelect) {
        periodTahunSelect.value = savedYear;
        switchedPeriod = true;
      }
      if (savedMonth && curBulan !== 'ALL' && curBulan !== savedMonth && periodBulanSelect) {
        periodBulanSelect.value = savedMonth;
        switchedPeriod = true;
      }

      refreshPeriodTable(isEdit && !switchedPeriod);
      window.bootstrap?.Modal.getOrCreateInstance(formModalEl)?.hide();

      window.SimdesApp?.showToast({
        title: isEdit ? 'Data Mutasi Diperbarui' : 'Mutasi Penduduk Ditambahkan',
        message: switchedPeriod
          ? `"${payload.namaLengkap}" disimpan dan periode dialihkan ke ${formatPeriodShortLabel(activeMutasiDate)}.`
          : `"${payload.namaLengkap}" (${payload.jenisMutasi}) berhasil tercatat pada Buku Mutasi.`,
        variant: 'success'
      });
    });

    confirmDeleteBtn?.addEventListener('click', () => {
      if (!pendingDeleteId) return;
      const target = records.find((r) => r.id === pendingDeleteId);
      records = records.filter((r) => r.id !== pendingDeleteId);
      pendingDeleteId = null;

      saveRecords(records);
      syncYearSelectOptions();
      refreshPeriodTable(true);

      window.bootstrap?.Modal.getOrCreateInstance(deleteModalEl)?.hide();
      window.SimdesApp?.showToast({
        title: 'Data Mutasi Dihapus',
        message: target
          ? `Catatan mutasi "${target.namaLengkap}" dihapus dan nomor urut disesuaikan otomatis.`
          : 'Data mutasi berhasil dihapus.',
        variant: 'warning'
      });
    });

    // =========================================================================
    // Period, Search, Filter, Sort, Reset, Sample Restore & Print Listeners
    // =========================================================================
    function handleResetAllFilters() {
      if (searchInput) searchInput.value = '';
      clearSearchBtn?.classList.remove('visible');
      if (filterJenisMutasiSelect) filterJenisMutasiSelect.value = 'ALL';
      if (filterGenderSelect) filterGenderSelect.value = 'ALL';
      if (periodBulanSelect) periodBulanSelect.value = DEFAULT_BULAN;
      syncYearSelectOptions(DEFAULT_TAHUN);
      if (periodTahunSelect) periodTahunSelect.value = DEFAULT_TAHUN;

      updateBookPeriodHeadings();
      tableCtrl.searchQuery = '';
      tableCtrl.filters = {};
      tableCtrl.setData(getPeriodFilteredRecords(), false);
    }

    periodBulanSelect?.addEventListener('change', () => {
      refreshPeriodTable(false);
    });

    periodTahunSelect?.addEventListener('change', () => {
      refreshPeriodTable(false);
    });

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

    filterJenisMutasiSelect?.addEventListener('change', () => {
      const val = filterJenisMutasiSelect.value;
      tableCtrl.setFilter('jenisMutasi', val === 'ALL' ? null : val);
    });

    filterGenderSelect?.addEventListener('change', () => {
      const val = filterGenderSelect.value;
      tableCtrl.setFilter('jenisKelamin', val === 'ALL' ? null : val);
    });

    pageSizeSelect?.addEventListener('change', () => {
      tableCtrl.setPageSize(Number(pageSizeSelect.value) || 10);
    });

    resetFilterBtn?.addEventListener('click', handleResetAllFilters);

    tableEl?.querySelectorAll('th [data-sort-key]').forEach((btn) => {
      btn.addEventListener('click', () => {
        const key = btn.getAttribute('data-sort-key');
        if (key) tableCtrl.toggleSort(key);
      });
    });

    tbodyEl?.addEventListener('click', (e) => {
      const actionBtn = e.target.closest('[data-row-action]');
      if (actionBtn) {
        const action = actionBtn.getAttribute('data-row-action');
        const id = actionBtn.getAttribute('data-id');
        if (!id) return;
        if (action === 'detail') openDetailModal(id);
        else if (action === 'edit') openEditModal(id);
        else if (action === 'delete') openDeleteModal(id);
        return;
      }

      const emptyBtn = e.target.closest('[data-empty-action]');
      if (emptyBtn) {
        const emptyAction = emptyBtn.getAttribute('data-empty-action');
        if (emptyAction === 'reset-filter') handleResetAllFilters();
        else if (emptyAction === 'add-new') openAddModal();
      }
    });

    btnTambah?.addEventListener('click', openAddModal);

    btnResetSample?.addEventListener('click', () => {
      records = cloneSeedData();
      saveRecords(records);
      handleResetAllFilters();
      window.SimdesApp?.showToast({
        title: 'Data Contoh Dipulihkan',
        message: '17 data contoh fiktif Buku Mutasi Penduduk Desa berhasil dimuat ulang.',
        variant: 'primary'
      });
    });

    // Full-Table Landscape Print Support
    function preparePrintView() {
      updateBookPeriodHeadings();
      const state = tableCtrl.getState();
      if (tbodyEl) {
        tbodyEl.innerHTML = renderRowsHtml(state.allFilteredRows, 0, {
          ...state,
          totalItems: state.allFilteredRows.length
        });
      }
      if (printMetaEl) {
        const datangCount = state.allFilteredRows.filter((r) => r.jenisMutasi === 'Datang').length;
        const pindahCount = state.allFilteredRows.filter((r) => r.jenisMutasi === 'Pindah').length;
        const meninggalCount = state.allFilteredRows.filter((r) => r.jenisMutasi === 'Meninggal').length;
        try {
          const nowStr = new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
          }).format(new Date());
          printMetaEl.textContent = `Total mutasi tercetak: ${state.allFilteredRows.length} jiwa (Datang: ${datangCount}, Pindah: ${pindahCount}, Meninggal: ${meninggalCount}) • Dicetak pada ${nowStr}`;
        } catch (_) {
          printMetaEl.textContent = `Total mutasi tercetak: ${state.allFilteredRows.length} jiwa (Datang: ${datangCount}, Pindah: ${pindahCount}, Meninggal: ${meninggalCount})`;
        }
      }
    }

    window.addEventListener('beforeprint', preparePrintView);
    window.addEventListener('afterprint', () => tableCtrl.render());

    btnCetak?.addEventListener('click', () => {
      preparePrintView();
      window.print();
    });

    // Initial Render
    tableCtrl.render();
  }

  document.addEventListener('DOMContentLoaded', initBukuMutasiPendudukDesa);
})();
