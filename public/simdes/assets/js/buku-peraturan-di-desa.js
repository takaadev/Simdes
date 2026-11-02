/* ==========================================================================
   SIMDES DESA TIARO — Buku Peraturan di Desa Controller (Format A.1)
   File: assets/js/buku-peraturan-di-desa.js
   Features:
   - Exact 10-column official register rendering with combined Nomor & Tanggal
   - CRUD via large modal form (6 sections, 13 inputs) + localStorage persistence
   - Search (jenis, nomor, tentang, uraian), Filter Jenis Peraturan & Tahun, Reset
   - Column sorting, pagination, auto row numbering, detail modal, delete modal
   - Landscape print mode rendering all filtered rows cleanly
   ========================================================================== */

(() => {
  'use strict';

  const STORAGE_KEY = 'simdes_tiaro_buku_peraturan_di_desa_v1';

  const JENIS_PERATURAN_LIST = [
    'Peraturan Desa',
    'Peraturan Kepala Desa',
    'Peraturan Bersama Kepala Desa'
  ];

  /**
   * Realistic Simulation Data (8 Records across 2026 & 2025)
   * Contains zero real resident personal data; uses simulation numbering.
   */
  const SEED_DATA = [
    {
      id: 'per-sim-001',
      jenisPeraturan: 'Peraturan Desa',
      nomorPeraturan: '01/PERDES-CONTOH/I/2026',
      tanggalDitetapkan: '2026-01-14',
      tentang: 'Rencana Kerja Pemerintah Desa Tahun Anggaran 2026 (Data Contoh)',
      uraianSingkat: 'Menetapkan prioritas program pembangunan, pemberdayaan masyarakat, pembinaan kemasyarakatan, serta pagu indikatif kegiatan tahun berjalan.',
      tanggalKesepakatan: '2026-01-10',
      nomorLaporan: '140/006/LAP-CONTOH/I/2026',
      tanggalDilaporkan: '2026-01-16',
      nomorLembaranDesa: 'Nomor 01 Tahun 2026',
      tanggalLembaranDesa: '2026-01-15',
      nomorBeritaDesa: '',
      tanggalBeritaDesa: '',
      keterangan: 'Data contoh simulasi — Diundangkan dalam Lembaran Desa'
    },
    {
      id: 'per-sim-002',
      jenisPeraturan: 'Peraturan Desa',
      nomorPeraturan: '02/PERDES-CONTOH/I/2026',
      tanggalDitetapkan: '2026-01-28',
      tentang: 'Anggaran Pendapatan dan Belanja Desa Tahun Anggaran 2026 (Data Contoh)',
      uraianSingkat: 'Mengatur rincian anggaran pendapatan desa, belanja bidang penyelenggaraan pemerintahan, pembangunan, serta pembiayaan desa tahun 2026.',
      tanggalKesepakatan: '2026-01-23',
      nomorLaporan: '140/014/LAP-CONTOH/I/2026',
      tanggalDilaporkan: '2026-01-30',
      nomorLembaranDesa: 'Nomor 02 Tahun 2026',
      tanggalLembaranDesa: '2026-01-29',
      nomorBeritaDesa: '',
      tanggalBeritaDesa: '',
      keterangan: 'Data contoh simulasi — Telah dievaluasi kecamatan'
    },
    {
      id: 'per-sim-003',
      jenisPeraturan: 'Peraturan Kepala Desa',
      nomorPeraturan: '01/PERKADES-CONTOH/II/2026',
      tanggalDitetapkan: '2026-02-04',
      tentang: 'Penjabaran Anggaran Pendapatan dan Belanja Desa Tahun Anggaran 2026 (Data Contoh)',
      uraianSingkat: 'Penjabaran teknis rincian objek pendapatan, belanja operasional kegiatan, dan jadwal pelaksanaan anggaran per subbidang kegiatan.',
      tanggalKesepakatan: '',
      nomorLaporan: '140/021/LAP-CONTOH/II/2026',
      tanggalDilaporkan: '2026-02-06',
      nomorLembaranDesa: '',
      tanggalLembaranDesa: '',
      nomorBeritaDesa: 'Nomor 01 Tahun 2026',
      tanggalBeritaDesa: '2026-02-05',
      keterangan: 'Data contoh simulasi — Diundangkan dalam Berita Desa'
    },
    {
      id: 'per-sim-004',
      jenisPeraturan: 'Peraturan Bersama Kepala Desa',
      nomorPeraturan: '01/PERMAKADES-CONTOH/III/2026',
      tanggalDitetapkan: '2026-03-18',
      tentang: 'Kerja Sama Antar-Desa Pengelolaan Jaringan Irigasi Pertanian Terpadu (Data Contoh)',
      uraianSingkat: 'Pengaturan bersama pemeliharaan saluran irigasi primer perbatasan wilayah, pembagian jadwal distribusi air tanam, dan skema gotong royong.',
      tanggalKesepakatan: '',
      nomorLaporan: '140/034/LAP-CONTOH/III/2026',
      tanggalDilaporkan: '2026-03-23',
      nomorLembaranDesa: '',
      tanggalLembaranDesa: '',
      nomorBeritaDesa: 'Nomor 02 Tahun 2026',
      tanggalBeritaDesa: '2026-03-19',
      keterangan: 'Data contoh simulasi — Kerja sama kawasan perdesaan'
    },
    {
      id: 'per-sim-005',
      jenisPeraturan: 'Peraturan Desa',
      nomorPeraturan: '03/PERDES-CONTOH/V/2026',
      tanggalDitetapkan: '2026-05-12',
      tentang: 'Pengelolaan dan Pemanfaatan Aset Milik Desa secara Tertib dan Akuntabel (Data Contoh)',
      uraianSingkat: 'Ketentuan perencanaan, penggunaan, pemanfaatan, pengamanan, pemeliharaan, hingga tata cara penghapusan barang dan kekayaan milik desa.',
      tanggalKesepakatan: '2026-05-07',
      nomorLaporan: '140/048/LAP-CONTOH/V/2026',
      tanggalDilaporkan: '2026-05-15',
      nomorLembaranDesa: 'Nomor 03 Tahun 2026',
      tanggalLembaranDesa: '2026-05-13',
      nomorBeritaDesa: '',
      tanggalBeritaDesa: '',
      keterangan: 'Data contoh simulasi UI'
    },
    {
      id: 'per-sim-006',
      jenisPeraturan: 'Peraturan Kepala Desa',
      nomorPeraturan: '02/PERKADES-CONTOH/VII/2026',
      tanggalDitetapkan: '2026-07-08',
      tentang: 'Standar Operasional Pelayanan Administrasi Terpadu Kantor Desa (Data Contoh)',
      uraianSingkat: 'Menetapkan tata cara pelayanan surat pengantar, jam operasional pelayanan publik, serta standar maklumat pelayanan administrasi warga.',
      tanggalKesepakatan: '',
      nomorLaporan: '140/063/LAP-CONTOH/VII/2026',
      tanggalDilaporkan: '2026-07-13',
      nomorLembaranDesa: '',
      tanggalLembaranDesa: '',
      nomorBeritaDesa: 'Nomor 03 Tahun 2026',
      tanggalBeritaDesa: '2026-07-09',
      keterangan: 'Data contoh simulasi UI'
    },
    {
      id: 'per-sim-007',
      jenisPeraturan: 'Peraturan Desa',
      nomorPeraturan: '04/PERDES-CONTOH/IX/2026',
      tanggalDitetapkan: '2026-09-22',
      tentang: 'Perubahan Anggaran Pendapatan dan Belanja Desa Tahun Anggaran 2026 (Data Contoh)',
      uraianSingkat: 'Penyesuaian rincian pendapatan transfer, pergeseran belanja kegiatan prioritas darurat skala desa, dan pemanfaatan sisa lebih perhitungan anggaran.',
      tanggalKesepakatan: '2026-09-18',
      nomorLaporan: '',
      tanggalDilaporkan: '',
      nomorLembaranDesa: 'Nomor 04 Tahun 2026',
      tanggalLembaranDesa: '2026-09-23',
      nomorBeritaDesa: '',
      tanggalBeritaDesa: '',
      keterangan: 'Data contoh — Proses penyampaian laporan ke kecamatan'
    },
    {
      id: 'per-sim-008',
      jenisPeraturan: 'Peraturan Desa',
      nomorPeraturan: '06/PERDES-CONTOH/XII/2025',
      tanggalDitetapkan: '2025-12-29',
      tentang: 'Laporan Pertanggungjawaban Realisasi Pelaksanaan APBDes Tahun Anggaran 2025 (Data Contoh)',
      uraianSingkat: 'Pertanggungjawaban realisasi pendapatan, belanja, pembiayaan, serta daftar kekayaan milik desa per akhir tahun anggaran 2025.',
      tanggalKesepakatan: '2025-12-23',
      nomorLaporan: '140/102/LAP-CONTOH/XII/2025',
      tanggalDilaporkan: '2025-12-31',
      nomorLembaranDesa: 'Nomor 06 Tahun 2025',
      tanggalLembaranDesa: '2025-12-30',
      nomorBeritaDesa: '',
      tanggalBeritaDesa: '',
      keterangan: 'Data contoh arsip tahun sebelumnya'
    }
  ];

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
      // Ignore storage quota / private browsing errors
    }
  }

  function extractYear(isoDate) {
    if (!isoDate || typeof isoDate !== 'string') return '';
    return isoDate.slice(0, 4);
  }

  function badgeClassForJenis(jenis) {
    if (jenis === 'Peraturan Desa') return 'badge-simdes badge-simdes-primary';
    if (jenis === 'Peraturan Kepala Desa') return 'badge-simdes badge-simdes-accent';
    if (jenis === 'Peraturan Bersama Kepala Desa') return 'badge-simdes badge-simdes-info';
    return 'badge-simdes badge-simdes-neutral';
  }

  function initBukuPeraturanPage() {
    const pageRoot = document.getElementById('bukuPeraturanPageRoot');
    if (!pageRoot) return;

    const esc = window.SimdesApp ? window.SimdesApp.escapeHtml : (s) => String(s ?? '');
    const formatDate = window.SimdesTable ? window.SimdesTable.formatDateIndo : (s) => s;

    let records = loadRecords();
    let activeDetailId = null;
    let pendingDeleteId = null;

    // DOM References
    const tbody = document.getElementById('peraturanTableBody');
    const searchInput = document.getElementById('tableSearchInput');
    const clearSearchBtn = document.getElementById('clearSearchInputBtn');
    const filterJenisSelect = document.getElementById('filterJenisSelect');
    const filterTahunSelect = document.getElementById('filterTahunSelect');
    const pageSizeSelect = document.getElementById('pageSizeSelect');
    const resetFilterBtn = document.getElementById('resetTableFilterBtn');

    const paginationInfo = document.getElementById('tablePaginationInfo');
    const paginationList = document.getElementById('tablePaginationList');

    // Summary Stat Elements
    const statTotalEl = document.getElementById('statTotalPeraturan');
    const statPerdesEl = document.getElementById('statTotalPerdes');
    const statPerkadesEl = document.getElementById('statTotalPerkades');
    const statPermaKadesEl = document.getElementById('statTotalPermaKades');

    // Top Action Buttons
    const addBtn = document.getElementById('btnTambahPeraturan');
    const printBtn = document.getElementById('btnCetakPeraturan');
    const restoreSeedBtn = document.getElementById('btnResetSamplePeraturan');

    // Modals & Form Controls
    const formModalEl = document.getElementById('peraturanFormModal');
    const detailModalEl = document.getElementById('peraturanDetailModal');
    const deleteModalEl = document.getElementById('peraturanDeleteModal');

    const formEl = document.getElementById('peraturanForm');
    const formModalTitle = document.getElementById('peraturanFormModalLabel');
    const formSubmitBtn = document.getElementById('peraturanFormSubmitBtn');
    const formAlertBox = document.getElementById('peraturanFormAlert');
    const formAlertText = document.getElementById('peraturanFormAlertText');

    // 13 Form Inputs across Sections A–F
    const inputId = document.getElementById('inputRecordId');
    const inputJenisPeraturan = document.getElementById('inputJenisPeraturan');
    const inputTentang = document.getElementById('inputTentang');
    const inputUraianSingkat = document.getElementById('inputUraianSingkat');
    const inputNomorPeraturan = document.getElementById('inputNomorPeraturan');
    const inputTanggalDitetapkan = document.getElementById('inputTanggalDitetapkan');
    const inputTanggalKesepakatan = document.getElementById('inputTanggalKesepakatan');
    const inputNomorLaporan = document.getElementById('inputNomorLaporan');
    const inputTanggalDilaporkan = document.getElementById('inputTanggalDilaporkan');
    const inputNomorLembaranDesa = document.getElementById('inputNomorLembaranDesa');
    const inputTanggalLembaranDesa = document.getElementById('inputTanggalLembaranDesa');
    const inputNomorBeritaDesa = document.getElementById('inputNomorBeritaDesa');
    const inputTanggalBeritaDesa = document.getElementById('inputTanggalBeritaDesa');
    const inputKeterangan = document.getElementById('inputKeterangan');

    window.SimdesForms?.InteractionValidator?.bindForm(formEl);

    function hideFormBanner() {
      formAlertBox?.classList.remove('show');
    }

    function showFormBanner(message) {
      if (!formAlertBox || !formAlertText) return;
      formAlertText.textContent = message;
      formAlertBox.classList.add('show');
    }

    function updateSummaryStats() {
      const total = records.length;
      const countPerdes = records.filter((r) => r.jenisPeraturan === 'Peraturan Desa').length;
      const countPerkades = records.filter((r) => r.jenisPeraturan === 'Peraturan Kepala Desa').length;
      const countPermaKades = records.filter((r) => r.jenisPeraturan === 'Peraturan Bersama Kepala Desa').length;

      if (statTotalEl) statTotalEl.textContent = total.toLocaleString('id-ID');
      if (statPerdesEl) statPerdesEl.textContent = countPerdes.toLocaleString('id-ID');
      if (statPerkadesEl) statPerkadesEl.textContent = countPerkades.toLocaleString('id-ID');
      if (statPermaKadesEl) statPermaKadesEl.textContent = countPermaKades.toLocaleString('id-ID');
    }

    function syncYearFilterOptions() {
      if (!filterTahunSelect) return;
      const currentVal = filterTahunSelect.value || 'ALL';
      const years = Array.from(
        new Set(records.map((r) => extractYear(r.tanggalDitetapkan)).filter(Boolean))
      ).sort((a, b) => b.localeCompare(a));

      filterTahunSelect.innerHTML = `
        <option value="ALL">Semua Tahun</option>
        ${years.map((yr) => `<option value="${esc(yr)}">Tahun ${esc(yr)}</option>`).join('')}
      `;

      filterTahunSelect.value = years.includes(currentVal) ? currentVal : 'ALL';
    }

    /**
     * Render combined Nomor + Tanggal cell consistently
     */
    function buildCombinedNumberDateCell(nomorRaw, tanggalRaw, options = {}) {
      const {
        isPrimary = false,
        icon = 'fa-calendar',
        emptyBadgeText = '',
        emptyMutedDash = true
      } = options;

      const hasNomor = Boolean(String(nomorRaw || '').trim());
      const hasTanggal = Boolean(String(tanggalRaw || '').trim());

      if (!hasNomor && !hasTanggal) {
        if (emptyBadgeText) {
          return `<span class="badge-simdes badge-simdes-warning">${esc(emptyBadgeText)}</span>`;
        }
        return emptyMutedDash ? `<span class="text-simdes-muted">&ndash;</span>` : '-';
      }

      const nomorText = hasNomor ? esc(nomorRaw) : 'Nomor belum diisi';
      const tglText = hasTanggal ? esc(formatDate(tanggalRaw)) : 'Tanggal belum diisi';
      const badgeCls = isPrimary ? 'doc-num-badge' : 'doc-num-badge report-badge';

      return `
        <div>
          <span class="${badgeCls}">${nomorText}</span>
          <div class="doc-date-line">
            <i class="fa-regular ${icon}" aria-hidden="true"></i>
            <span>${tglText}</span>
          </div>
        </div>
      `;
    }

    function buildKesepakatanCell(row) {
      const tgl = String(row.tanggalKesepakatan || '').trim();
      if (!tgl) {
        return `<span class="text-simdes-muted" title="Tidak memerlukan atau belum ada tanggal kesepakatan">&ndash;</span>`;
      }
      return `
        <div class="doc-date-line" style="color:var(--simdes-text); font-weight:600;">
          <i class="fa-regular fa-calendar-check text-simdes-primary" aria-hidden="true"></i>
          <span>${esc(formatDate(tgl))}</span>
        </div>
      `;
    }

    function renderRowsHtml(rowsList, startIndex = 0) {
      if (rowsList.length === 0) {
        return `
          <tr>
            <td colspan="10">
              <div class="table-empty-state">
                <div class="table-empty-icon">
                  <i class="fa-solid fa-scale-balanced" aria-hidden="true"></i>
                </div>
                <div class="fw-bold mb-1" style="color:var(--simdes-text); font-size:0.95rem;">
                  Data Peraturan Tidak Ditemukan
                </div>
                <p class="mb-3" style="font-size:0.83rem; max-width:46ch; margin-inline:auto;">
                  Belum ada catatan peraturan yang cocok dengan kata kunci pencarian, jenis peraturan, atau filter tahun yang dipilih.
                </p>
                <div class="d-inline-flex flex-wrap justify-content-center gap-2 no-print">
                  <button type="button" class="btn-simdes-outline" data-empty-action="reset-filter">
                    <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                    <span>Reset Filter</span>
                  </button>
                  <button type="button" class="btn-simdes-primary" data-empty-action="add-new">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                    <span>Tambah Peraturan</span>
                  </button>
                </div>
              </div>
            </td>
          </tr>
        `;
      }

      return rowsList
        .map((row, idx) => {
          const nomorUrut = startIndex + idx + 1;
          const ketText = String(row.keterangan || '').trim() ? esc(row.keterangan) : '-';

          return `
            <tr data-row-id="${esc(row.id)}">
              <td class="col-no-urut sticky-col-first">${nomorUrut}</td>
              <td>
                <span class="${badgeClassForJenis(row.jenisPeraturan)}">${esc(row.jenisPeraturan)}</span>
              </td>
              <td>
                ${buildCombinedNumberDateCell(row.nomorPeraturan, row.tanggalDitetapkan, {
                  isPrimary: true,
                  icon: 'fa-calendar'
                })}
              </td>
              <td>
                <button type="button" class="tentang-title-btn" data-row-action="detail" data-id="${esc(row.id)}">
                  ${esc(row.tentang)}
                </button>
              </td>
              <td>${esc(row.uraianSingkat)}</td>
              <td>${buildKesepakatanCell(row)}</td>
              <td>
                ${buildCombinedNumberDateCell(row.nomorLaporan, row.tanggalDilaporkan, {
                  isPrimary: false,
                  icon: 'fa-paper-plane',
                  emptyBadgeText: 'Belum dilaporkan'
                })}
              </td>
              <td>
                ${buildCombinedNumberDateCell(row.nomorLembaranDesa, row.tanggalLembaranDesa, {
                  isPrimary: false,
                  icon: 'fa-calendar'
                })}
              </td>
              <td>
                ${buildCombinedNumberDateCell(row.nomorBeritaDesa, row.tanggalBeritaDesa, {
                  isPrimary: false,
                  icon: 'fa-newspaper'
                })}
              </td>
              <td>
                <div class="ket-cell-content">
                  <span class="ket-text">${ketText}</span>
                  <div class="row-action-group no-print" role="group" aria-label="Aksi baris peraturan nomor urut ${nomorUrut}">
                    <button type="button" class="btn-row-action" data-row-action="detail" data-id="${esc(row.id)}" title="Lihat Detail" aria-label="Lihat detail peraturan ${esc(row.nomorPeraturan)}">
                      <i class="fa-regular fa-eye" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn-row-action" data-row-action="edit" data-id="${esc(row.id)}" title="Edit Peraturan" aria-label="Edit peraturan ${esc(row.nomorPeraturan)}">
                      <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn-row-action action-delete" data-row-action="delete" data-id="${esc(row.id)}" title="Hapus Peraturan" aria-label="Hapus peraturan ${esc(row.nomorPeraturan)}">
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

    function updateSortHeaderIndicators(sortKey, sortDirection) {
      document.querySelectorAll('[data-sort-key]').forEach((btn) => {
        const key = btn.getAttribute('data-sort-key');
        const th = btn.closest('th');
        const icon = btn.querySelector('.th-sort-icon');

        if (key === sortKey) {
          btn.classList.add('is-sorted');
          th?.setAttribute('aria-sort', sortDirection === 'asc' ? 'ascending' : 'descending');
          if (icon) {
            icon.className = `fa-solid ${
              sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down'
            } th-sort-icon`;
          }
        } else {
          btn.classList.remove('is-sorted');
          th?.setAttribute('aria-sort', 'none');
          if (icon) {
            icon.className = 'fa-solid fa-sort th-sort-icon';
          }
        }
      });
    }

    // Initialize Table Controller
    const tableCtrl = window.SimdesTable.create({
      data: records,
      pageSize: 5,
      searchFields: [
        'jenisPeraturan',
        'nomorPeraturan',
        'tentang',
        'uraianSingkat',
        'nomorLaporan',
        'nomorLembaranDesa',
        'nomorBeritaDesa'
      ],
      defaultSortKey: 'ditetapkan',
      defaultSortDirection: 'desc',
      comparators: {
        jenisPeraturan: (a, b) =>
          String(a.jenisPeraturan || '').localeCompare(String(b.jenisPeraturan || ''), 'id', { sensitivity: 'base' }),
        ditetapkan: (a, b) => {
          const cmpDate = String(a.tanggalDitetapkan || '').localeCompare(String(b.tanggalDitetapkan || ''));
          if (cmpDate !== 0) return cmpDate;
          return String(a.nomorPeraturan || '').localeCompare(String(b.nomorPeraturan || ''), 'id', { numeric: true });
        },
        tentang: (a, b) =>
          String(a.tentang || '').localeCompare(String(b.tentang || ''), 'id', { sensitivity: 'base' }),
        tanggalKesepakatan: (a, b) =>
          String(a.tanggalKesepakatan || '').localeCompare(String(b.tanggalKesepakatan || '')),
        dilaporkan: (a, b) => {
          const cmpDate = String(a.tanggalDilaporkan || '').localeCompare(String(b.tanggalDilaporkan || ''));
          if (cmpDate !== 0) return cmpDate;
          return String(a.nomorLaporan || '').localeCompare(String(b.nomorLaporan || ''), 'id', { numeric: true });
        }
      },
      onRender: (state) => {
        if (tbody) {
          tbody.innerHTML = renderRowsHtml(state.rows, state.startIndex);
        }

        if (paginationInfo) {
          if (state.totalItems === 0) {
            paginationInfo.textContent = 'Menampilkan 0 data peraturan';
          } else {
            paginationInfo.innerHTML = `Menampilkan <strong>${state.startEntry}&ndash;${state.endEntry}</strong> dari <strong>${state.totalItems}</strong> data peraturan`;
          }
        }

        window.SimdesTable.renderPagination(paginationList, state, (page) => {
          tableCtrl.setPage(page);
        });

        updateSortHeaderIndicators(state.sortKey, state.sortDirection);

        if (resetFilterBtn) {
          resetFilterBtn.disabled = !state.hasActiveQuery;
        }
        if (clearSearchBtn) {
          clearSearchBtn.classList.toggle('visible', Boolean(searchInput?.value.trim()));
        }
      }
    });

    function openCreateModal() {
      formEl?.reset();
      hideFormBanner();
      if (inputId) inputId.value = '';

      if (formModalTitle) {
        formModalTitle.innerHTML = `<i class="fa-solid fa-plus-circle text-simdes-primary me-2" aria-hidden="true"></i>Tambah Peraturan di Desa`;
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML = `<i class="fa-solid fa-floppy-disk" aria-hidden="true"></i><span>Simpan Peraturan</span>`;
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
      if (inputJenisPeraturan) {
        inputJenisPeraturan.value = JENIS_PERATURAN_LIST.includes(record.jenisPeraturan)
          ? record.jenisPeraturan
          : '';
      }
      if (inputTentang) inputTentang.value = record.tentang || '';
      if (inputUraianSingkat) inputUraianSingkat.value = record.uraianSingkat || '';
      if (inputNomorPeraturan) inputNomorPeraturan.value = record.nomorPeraturan || '';
      if (inputTanggalDitetapkan) inputTanggalDitetapkan.value = record.tanggalDitetapkan || '';
      if (inputTanggalKesepakatan) inputTanggalKesepakatan.value = record.tanggalKesepakatan || '';
      if (inputNomorLaporan) inputNomorLaporan.value = record.nomorLaporan || '';
      if (inputTanggalDilaporkan) inputTanggalDilaporkan.value = record.tanggalDilaporkan || '';
      if (inputNomorLembaranDesa) inputNomorLembaranDesa.value = record.nomorLembaranDesa || '';
      if (inputTanggalLembaranDesa) inputTanggalLembaranDesa.value = record.tanggalLembaranDesa || '';
      if (inputNomorBeritaDesa) inputNomorBeritaDesa.value = record.nomorBeritaDesa || '';
      if (inputTanggalBeritaDesa) inputTanggalBeritaDesa.value = record.tanggalBeritaDesa || '';
      if (inputKeterangan) inputKeterangan.value = record.keterangan || '';

      if (formModalTitle) {
        formModalTitle.innerHTML = `<i class="fa-regular fa-pen-to-square text-simdes-primary me-2" aria-hidden="true"></i>Edit Peraturan di Desa`;
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

      const setTxt = (elId, text) => {
        const el = document.getElementById(elId);
        if (el) el.textContent = text;
      };

      const jenisBadgeEl = document.getElementById('detJenisBadge');
      if (jenisBadgeEl) {
        jenisBadgeEl.className = badgeClassForJenis(record.jenisPeraturan);
        jenisBadgeEl.textContent = record.jenisPeraturan || '-';
      }

      const hasLapor = Boolean(
        String(record.nomorLaporan || '').trim() || String(record.tanggalDilaporkan || '').trim()
      );
      const statusBadgeEl = document.getElementById('detStatusLaporBadge');
      if (statusBadgeEl) {
        statusBadgeEl.className = hasLapor
          ? 'badge-simdes badge-simdes-success'
          : 'badge-simdes badge-simdes-warning';
        statusBadgeEl.textContent = hasLapor ? 'Sudah Dilaporkan' : 'Belum Dilaporkan';
      }

      setTxt('detTentang', record.tentang || '-');
      setTxt('detUraianSingkat', record.uraianSingkat || '-');
      setTxt('detNomorPeraturan', record.nomorPeraturan || '-');
      setTxt(
        'detTanggalDitetapkan',
        record.tanggalDitetapkan ? formatDate(record.tanggalDitetapkan) : '-'
      );
      setTxt(
        'detTanggalKesepakatan',
        record.tanggalKesepakatan ? formatDate(record.tanggalKesepakatan) : 'Tidak ada / Tidak dipersyaratkan'
      );

      setTxt(
        'detNomorLaporan',
        String(record.nomorLaporan || '').trim() || 'Belum dicantumkan'
      );
      setTxt(
        'detTanggalDilaporkan',
        record.tanggalDilaporkan ? formatDate(record.tanggalDilaporkan) : 'Belum dilaporkan'
      );

      setTxt(
        'detNomorLembaranDesa',
        String(record.nomorLembaranDesa || '').trim() || '-'
      );
      setTxt(
        'detTanggalLembaranDesa',
        record.tanggalLembaranDesa ? formatDate(record.tanggalLembaranDesa) : '-'
      );

      setTxt(
        'detNomorBeritaDesa',
        String(record.nomorBeritaDesa || '').trim() || '-'
      );
      setTxt(
        'detTanggalBeritaDesa',
        record.tanggalBeritaDesa ? formatDate(record.tanggalBeritaDesa) : '-'
      );

      setTxt('detKeterangan', String(record.keterangan || '').trim() || '-');

      if (detailModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(detailModalEl).show();
      }
    }

    function openDeleteModal(id) {
      const record = records.find((r) => r.id === id);
      if (!record) return;
      pendingDeleteId = record.id;

      const jenisEl = document.getElementById('deleteTargetJenis');
      const nomorEl = document.getElementById('deleteTargetNomor');
      const tentangEl = document.getElementById('deleteTargetTentang');

      if (jenisEl) jenisEl.textContent = record.jenisPeraturan;
      if (nomorEl) nomorEl.textContent = record.nomorPeraturan;
      if (tentangEl) tentangEl.textContent = record.tentang;

      if (deleteModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(deleteModalEl).show();
      }
    }

    /**
     * Validate chronological date relationships across sections B, C, D, E
     */
    function validateChronologicalDates() {
      [
        inputTanggalKesepakatan,
        inputTanggalDilaporkan,
        inputTanggalLembaranDesa,
        inputTanggalBeritaDesa
      ].forEach((el) => el?.setCustomValidity(''));

      const tglTetap = inputTanggalDitetapkan?.value || '';
      const tglSepakat = inputTanggalKesepakatan?.value || '';
      const tglLapor = inputTanggalDilaporkan?.value || '';
      const tglLembaran = inputTanggalLembaranDesa?.value || '';
      const tglBerita = inputTanggalBeritaDesa?.value || '';

      if (tglTetap && tglSepakat && tglSepakat > tglTetap) {
        const msg = 'Tanggal kesepakatan tidak boleh melampaui tanggal ditetapkan.';
        window.SimdesForms?.InteractionValidator?.syncFieldState(inputTanggalKesepakatan, msg);
        showFormBanner(msg);
        inputTanggalKesepakatan?.focus();
        return false;
      }

      if (tglTetap && tglLapor && tglLapor < tglTetap) {
        const msg = 'Tanggal dilaporkan tidak boleh mendahului tanggal ditetapkan.';
        window.SimdesForms?.InteractionValidator?.syncFieldState(inputTanggalDilaporkan, msg);
        showFormBanner(msg);
        inputTanggalDilaporkan?.focus();
        return false;
      }

      if (tglTetap && tglLembaran && tglLembaran < tglTetap) {
        const msg = 'Tanggal diundangkan dalam Lembaran Desa tidak boleh mendahului tanggal ditetapkan.';
        window.SimdesForms?.InteractionValidator?.syncFieldState(inputTanggalLembaranDesa, msg);
        showFormBanner(msg);
        inputTanggalLembaranDesa?.focus();
        return false;
      }

      if (tglTetap && tglBerita && tglBerita < tglTetap) {
        const msg = 'Tanggal diundangkan dalam Berita Desa tidak boleh mendahului tanggal ditetapkan.';
        window.SimdesForms?.InteractionValidator?.syncFieldState(inputTanggalBeritaDesa, msg);
        showFormBanner(msg);
        inputTanggalBeritaDesa?.focus();
        return false;
      }

      return true;
    }

    // Form Submit Handler
    formEl?.addEventListener('submit', (e) => {
      e.preventDefault();
      hideFormBanner();

      const validRequired = window.SimdesForms?.InteractionValidator
        ? window.SimdesForms.InteractionValidator.validateAll(formEl)
        : formEl.checkValidity();

      if (!validRequired) {
        showFormBanner('Mohon lengkapi seluruh kolom wajib yang ditandai bintang (*).');
        return;
      }

      if (!validateChronologicalDates()) return;

      const editingId = inputId?.value.trim();
      const payload = {
        id: editingId || `per-${Date.now()}`,
        jenisPeraturan: inputJenisPeraturan.value.trim(),
        tentang: inputTentang.value.trim(),
        uraianSingkat: inputUraianSingkat.value.trim(),
        nomorPeraturan: inputNomorPeraturan.value.trim(),
        tanggalDitetapkan: inputTanggalDitetapkan.value,
        tanggalKesepakatan: inputTanggalKesepakatan?.value || '',
        nomorLaporan: inputNomorLaporan?.value.trim() || '',
        tanggalDilaporkan: inputTanggalDilaporkan?.value || '',
        nomorLembaranDesa: inputNomorLembaranDesa?.value.trim() || '',
        tanggalLembaranDesa: inputTanggalLembaranDesa?.value || '',
        nomorBeritaDesa: inputNomorBeritaDesa?.value.trim() || '',
        tanggalBeritaDesa: inputTanggalBeritaDesa?.value || '',
        keterangan: inputKeterangan?.value.trim() || ''
      };

      if (editingId) {
        records = records.map((r) => (r.id === editingId ? payload : r));
      } else {
        records = [payload, ...records];
      }

      saveRecords(records);
      updateSummaryStats();
      syncYearFilterOptions();
      tableCtrl.setData(records, Boolean(editingId));

      if (formModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getInstance(formModalEl)?.hide();
      }

      window.SimdesApp?.showToast({
        title: editingId ? 'Data Peraturan Diperbarui' : 'Peraturan Baru Ditambahkan',
        message: `${payload.jenisPeraturan} Nomor "${payload.nomorPeraturan}" berhasil disimpan.`,
        variant: 'success'
      });
    });

    // Table Body Delegation
    tbody?.addEventListener('click', (e) => {
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

    // Detail Modal -> Edit Shortcut
    document.getElementById('btnEditPeraturanFromDetail')?.addEventListener('click', () => {
      if (!activeDetailId) return;
      const id = activeDetailId;
      if (detailModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getInstance(detailModalEl)?.hide();
      }
      window.setTimeout(() => openEditModal(id), 180);
    });

    // Confirm Delete Handler
    document.getElementById('confirmDeletePeraturanBtn')?.addEventListener('click', () => {
      if (!pendingDeleteId) return;
      const target = records.find((r) => r.id === pendingDeleteId);
      records = records.filter((r) => r.id !== pendingDeleteId);
      pendingDeleteId = null;

      saveRecords(records);
      updateSummaryStats();
      syncYearFilterOptions();
      tableCtrl.setData(records, true);

      if (deleteModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getInstance(deleteModalEl)?.hide();
      }

      window.SimdesApp?.showToast({
        title: 'Data Peraturan Dihapus',
        message: target
          ? `${target.jenisPeraturan} "${target.nomorPeraturan}" telah dihapus dari daftar.`
          : 'Data peraturan berhasil dihapus.',
        variant: 'warning'
      });
    });

    // Search & Filters
    function resetAllFilters() {
      if (searchInput) searchInput.value = '';
      if (filterJenisSelect) filterJenisSelect.value = 'ALL';
      if (filterTahunSelect) filterTahunSelect.value = 'ALL';
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

    filterJenisSelect?.addEventListener('change', () => {
      const val = filterJenisSelect.value;
      if (val === 'ALL') {
        tableCtrl.setFilter('jenisPeraturan', null);
      } else {
        tableCtrl.setFilter('jenisPeraturan', val);
      }
    });

    filterTahunSelect?.addEventListener('change', () => {
      const val = filterTahunSelect.value;
      if (val === 'ALL') {
        tableCtrl.setFilter('tahun', null);
      } else {
        tableCtrl.setFilter('tahun', (_, row) => extractYear(row.tanggalDitetapkan) === val);
      }
    });

    pageSizeSelect?.addEventListener('change', () => {
      tableCtrl.setPageSize(Number(pageSizeSelect.value) || 5);
    });

    // Sorting Bindings
    document.querySelectorAll('[data-sort-key]').forEach((btn) => {
      btn.addEventListener('click', () => {
        const key = btn.getAttribute('data-sort-key');
        if (key) tableCtrl.toggleSort(key);
      });
    });

    // Top Actions
    addBtn?.addEventListener('click', openCreateModal);

    restoreSeedBtn?.addEventListener('click', () => {
      records = cloneSeedData();
      saveRecords(records);
      resetAllFilters();
      updateSummaryStats();
      syncYearFilterOptions();
      tableCtrl.setData(records, false);

      window.SimdesApp?.showToast({
        title: 'Data Contoh Dipulihkan',
        message: 'Buku Peraturan di Desa dikembalikan ke 8 data simulasi awal.',
        variant: 'primary'
      });
    });

    // Print Full Filtered Table
    const preparePrintView = () => {
      const state = tableCtrl.getState();
      if (tbody) {
        tbody.innerHTML = renderRowsHtml(state.allFilteredRows, 0);
      }
      const printMetaEl = document.getElementById('printPeraturanMeta');
      if (printMetaEl) {
        try {
          const nowStr = new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
          }).format(new Date());
          printMetaEl.textContent = `Dicetak pada: ${nowStr} • Total: ${state.allFilteredRows.length} data peraturan (Data Contoh Simulasi)`;
        } catch (_) {
          printMetaEl.textContent = `Total: ${state.allFilteredRows.length} data peraturan (Data Contoh Simulasi)`;
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
    updateSummaryStats();
    syncYearFilterOptions();
    tableCtrl.render();
  }

  document.addEventListener('DOMContentLoaded', initBukuPeraturanPage);
})();

