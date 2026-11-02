/* ==========================================================================
   SIMDES DESA TIARO — Buku Lembaran Desa dan Berita Desa (Kode: A.9)
   File: assets/js/buku-lembaran-desa-dan-berita-desa.js
   Features:
   - Official 7-column multi-header table (DIUNDANGKAN colspan="2" -> TANGGAL & NOMOR)
   - Search by jenis peraturan, nomor peraturan/pengundangan, or tentang
   - Filters by Jenis Peraturan, Tahun Penetapan, and Tahun Pengundangan
   - Sorting by Tanggal Penetapan, Tanggal Pengundangan, Jenis Peraturan, Tentang
   - CRUD with localStorage persistence, validation, pagination, detail & print
   ========================================================================== */

(() => {
  'use strict';

  const STORAGE_KEY = 'simdes_tiaro_buku_lembaran_berita_desa_v1';

  /**
   * Data Contoh Fiktif Simulasi UI (8 peraturan contoh)
   * Tidak menggunakan nomor peraturan/pengundangan resmi Pemerintah Desa Tiaro.
   */
  const SEED_DATA = [
    {
      id: 'lbd-sim-001',
      jenisPeraturan: 'Peraturan Desa',
      nomorPeraturan: 'SIM-PERDES/04/2026',
      tanggalDitetapkan: '2026-10-14',
      tentang: 'Perubahan Anggaran Pendapatan dan Belanja Desa Tahun Anggaran Simulasi 2026 (Data Contoh)',
      tanggalDiundangkan: '2026-10-15',
      nomorDiundangkan: 'SIM-LD/04/2026',
      keterangan: 'Diundangkan dalam Lembaran Desa (Data Contoh)'
    },
    {
      id: 'lbd-sim-002',
      jenisPeraturan: 'Peraturan Kepala Desa',
      nomorPeraturan: 'SIM-PERKADES/07/2026',
      tanggalDitetapkan: '2026-10-16',
      tentang: 'Penjabaran Perubahan Anggaran Pendapatan dan Belanja Desa Tahun Simulasi 2026 (Data Contoh)',
      tanggalDiundangkan: '2026-10-17',
      nomorDiundangkan: 'SIM-BD/07/2026',
      keterangan: 'Diundangkan dalam Berita Desa (Data Contoh)'
    },
    {
      id: 'lbd-sim-003',
      jenisPeraturan: 'Peraturan Desa',
      nomorPeraturan: 'SIM-PERDES/03/2026',
      tanggalDitetapkan: '2026-08-20',
      tentang: 'Rencana Kerja Pemerintah Desa (RKP Desa) Tahun Perencanaan Berikutnya (Data Contoh)',
      tanggalDiundangkan: '2026-08-22',
      nomorDiundangkan: 'SIM-LD/03/2026',
      keterangan: 'Diundangkan dalam Lembaran Desa (Data Contoh)'
    },
    {
      id: 'lbd-sim-004',
      jenisPeraturan: 'Peraturan Bersama Kepala Desa',
      nomorPeraturan: 'SIM-PERMAKADES/01/2026',
      tanggalDitetapkan: '2026-07-08',
      tentang: 'Kerja Sama Pengelolaan Jaringan Irigasi Antar-Desa Kawasan Pertanian Terpadu (Data Contoh)',
      tanggalDiundangkan: '2026-07-10',
      nomorDiundangkan: 'SIM-BD/05/2026',
      keterangan: 'Diundangkan dalam Berita Desa (Data Contoh)'
    },
    {
      id: 'lbd-sim-005',
      jenisPeraturan: 'Peraturan Kepala Desa',
      nomorPeraturan: 'SIM-PERKADES/04/2026',
      tanggalDitetapkan: '2026-05-11',
      tentang: 'Tata Cara Pelayanan Administrasi Terpadu dan Standar Jam Layanan Kantor Desa (Data Contoh)',
      tanggalDiundangkan: '',
      nomorDiundangkan: '',
      keterangan: 'Menunggu penomoran registrasi Berita Desa (Data Contoh)'
    },
    {
      id: 'lbd-sim-006',
      jenisPeraturan: 'Peraturan Desa',
      nomorPeraturan: 'SIM-PERDES/02/2026',
      tanggalDitetapkan: '2026-03-19',
      tentang: 'Laporan Pertanggungjawaban Realisasi Pelaksanaan APBDes Tahun Sebelumnya (Data Contoh)',
      tanggalDiundangkan: '2026-03-20',
      nomorDiundangkan: 'SIM-LD/02/2026',
      keterangan: 'Diundangkan dalam Lembaran Desa (Data Contoh)'
    },
    {
      id: 'lbd-sim-007',
      jenisPeraturan: 'Peraturan Desa',
      nomorPeraturan: 'SIM-PERDES/06/2025',
      tanggalDitetapkan: '2025-12-29',
      tentang: 'Anggaran Pendapatan dan Belanja Desa Tahun Anggaran Simulasi 2026 (Data Contoh)',
      tanggalDiundangkan: '2025-12-30',
      nomorDiundangkan: 'SIM-LD/06/2025',
      keterangan: 'Diundangkan dalam Lembaran Desa (Data Contoh)'
    },
    {
      id: 'lbd-sim-008',
      jenisPeraturan: 'Peraturan Kepala Desa',
      nomorPeraturan: 'SIM-PERKADES/09/2025',
      tanggalDitetapkan: '2025-11-18',
      tentang: 'Pedoman Pengelolaan Inventaris Barang Milik Desa dan Pemanfaatan Fasilitas Umum (Data Contoh)',
      tanggalDiundangkan: '2025-11-19',
      nomorDiundangkan: 'SIM-BD/09/2025',
      keterangan: 'Diundangkan dalam Berita Desa (Data Contoh)'
    }
  ];

  function extractYear(isoDate) {
    if (!isoDate || typeof isoDate !== 'string') return '';
    const yr = isoDate.trim().slice(0, 4);
    return /^\d{4}$/.test(yr) ? yr : '';
  }

  function isPromulgated(row) {
    return Boolean(
      String(row.tanggalDiundangkan || '').trim() || String(row.nomorDiundangkan || '').trim()
    );
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

  function getJenisBadgeVariant(jenis) {
    if (jenis === 'Peraturan Desa') return 'badge-simdes-primary';
    if (jenis === 'Peraturan Kepala Desa') return 'badge-simdes-accent';
    if (jenis === 'Peraturan Bersama Kepala Desa') return 'badge-simdes-info';
    return 'badge-simdes-neutral';
  }

  function initBukuLembaranBeritaDesa() {
    const pageRoot = document.getElementById('bukuLembaranPageRoot');
    if (!pageRoot) return;

    const esc = window.SimdesApp ? window.SimdesApp.escapeHtml : (s) => String(s ?? '');
    const formatDate = window.SimdesTable ? window.SimdesTable.formatDateIndo : (s) => s;

    let records = loadRecords();
    let activeDetailId = null;
    let pendingDeleteId = null;

    // DOM References
    const statTotalPeraturan = document.getElementById('statTotalPeraturan');
    const statSudahDiundangkan = document.getElementById('statSudahDiundangkan');
    const statPerdesCount = document.getElementById('statPerdesCount');
    const statPerkadesCount = document.getElementById('statPerkadesCount');
    const printMetaEl = document.getElementById('printLembaranMeta');

    const tableEl = document.getElementById('lembaranTable');
    const tbodyEl = document.getElementById('lembaranTableBody');
    const paginationInfoEl = document.getElementById('tablePaginationInfo');
    const paginationListEl = document.getElementById('tablePaginationList');

    const searchInput = document.getElementById('tableSearchInput');
    const clearSearchBtn = document.getElementById('clearSearchInputBtn');
    const filterJenisSelect = document.getElementById('filterJenisSelect');
    const filterTahunPenetapanSelect = document.getElementById('filterTahunPenetapanSelect');
    const filterTahunPengundanganSelect = document.getElementById('filterTahunPengundanganSelect');
    const pageSizeSelect = document.getElementById('pageSizeSelect');
    const resetFilterBtn = document.getElementById('resetTableFilterBtn');
    const resetSampleBtn = document.getElementById('btnResetSampleLembaran');

    const btnTambah = document.getElementById('btnTambahLembaran');
    const btnCetak = document.getElementById('btnCetakLembaran');

    // Form Modal Elements
    const formModalEl = document.getElementById('lembaranFormModal');
    const formModalTitle = document.getElementById('lembaranFormModalLabel');
    const formEl = document.getElementById('lembaranForm');
    const formAlertBox = document.getElementById('lembaranFormAlert');
    const formAlertText = document.getElementById('lembaranFormAlertText');
    const formSubmitBtn = document.getElementById('lembaranFormSubmitBtn');

    const fId = document.getElementById('lbdRecordId');
    const fJenisPeraturan = document.getElementById('lbdJenisPeraturan');
    const fNomorPeraturan = document.getElementById('lbdNomorPeraturan');
    const fTanggalDitetapkan = document.getElementById('lbdTanggalDitetapkan');
    const fTentang = document.getElementById('lbdTentang');
    const fTanggalDiundangkan = document.getElementById('lbdTanggalDiundangkan');
    const fNomorDiundangkan = document.getElementById('lbdNomorDiundangkan');
    const fKeterangan = document.getElementById('lbdKeterangan');

    // Detail & Delete Modals
    const detailModalEl = document.getElementById('lembaranDetailModal');
    const btnEditFromDetail = document.getElementById('btnEditLembaranFromDetail');
    const deleteModalEl = document.getElementById('lembaranDeleteModal');
    const deleteTargetNameEl = document.getElementById('deleteLembaranTargetName');
    const deleteTargetMetaEl = document.getElementById('deleteLembaranTargetMeta');
    const confirmDeleteBtn = document.getElementById('confirmDeleteLembaranBtn');

    function updateSummaryStats() {
      const total = records.length;
      const promulgated = records.filter(isPromulgated).length;
      const perdes = records.filter((r) => r.jenisPeraturan === 'Peraturan Desa').length;
      const perkades = records.filter((r) => r.jenisPeraturan !== 'Peraturan Desa').length;

      if (statTotalPeraturan) statTotalPeraturan.textContent = total.toLocaleString('id-ID');
      if (statSudahDiundangkan) statSudahDiundangkan.textContent = promulgated.toLocaleString('id-ID');
      if (statPerdesCount) statPerdesCount.textContent = perdes.toLocaleString('id-ID');
      if (statPerkadesCount) statPerkadesCount.textContent = perkades.toLocaleString('id-ID');

      if (printMetaEl) {
        const nowStr = new Intl.DateTimeFormat('id-ID', {
          day: 'numeric',
          month: 'long',
          year: 'numeric'
        }).format(new Date());
        printMetaEl.textContent = `Total tercatat: ${total} peraturan (${promulgated} telah diundangkan) • Dicetak pada ${nowStr}`;
      }
    }

    function syncYearFilters() {
      if (filterTahunPenetapanSelect) {
        const curTetap = filterTahunPenetapanSelect.value || 'ALL';
        const yearsTetap = Array.from(
          new Set(records.map((r) => extractYear(r.tanggalDitetapkan)).filter(Boolean))
        ).sort((a, b) => b.localeCompare(a));

        filterTahunPenetapanSelect.innerHTML = [
          '<option value="ALL">Semua Tahun Penetapan</option>',
          ...yearsTetap.map((yr) => `<option value="${esc(yr)}">Ditetapkan ${esc(yr)}</option>`)
        ].join('');

        filterTahunPenetapanSelect.value = yearsTetap.includes(curTetap) ? curTetap : 'ALL';
      }

      if (filterTahunPengundanganSelect) {
        const curUndang = filterTahunPengundanganSelect.value || 'ALL';
        const yearsUndang = Array.from(
          new Set(records.map((r) => extractYear(r.tanggalDiundangkan)).filter(Boolean))
        ).sort((a, b) => b.localeCompare(a));

        filterTahunPengundanganSelect.innerHTML = [
          '<option value="ALL">Semua Tahun Pengundangan</option>',
          ...yearsUndang.map((yr) => `<option value="${esc(yr)}">Diundangkan ${esc(yr)}</option>`),
          '<option value="BELUM">Belum Diundangkan</option>'
        ].join('');

        if (curUndang === 'BELUM' || yearsUndang.includes(curUndang)) {
          filterTahunPengundanganSelect.value = curUndang;
        } else {
          filterTahunPengundanganSelect.value = 'ALL';
        }
      }
    }

    function syncSortHeadersUI(state) {
      if (!tableEl) return;
      tableEl.querySelectorAll('th [data-sort-key]').forEach((btn) => {
        const key = btn.getAttribute('data-sort-key');
        const th = btn.closest('th');
        const icon = btn.querySelector('.th-sort-icon');
        const isSorted = state.sortKey === key;

        btn.classList.toggle('is-sorted', isSorted);
        if (th) {
          th.setAttribute(
            'aria-sort',
            !isSorted ? 'none' : state.sortDirection === 'asc' ? 'ascending' : 'descending'
          );
        }
        if (icon) {
          icon.className = `fa-solid ${
            !isSorted ? 'fa-sort' : state.sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down'
          } th-sort-icon no-print`;
        }
      });
    }

    function buildCombinedDitetapkanCell(row) {
      const nomor = esc(row.nomorPeraturan || '-');
      const tgl = row.tanggalDitetapkan ? esc(formatDate(row.tanggalDitetapkan)) : '-';
      return `
        <div>
          <span class="doc-num-badge">${nomor}</span>
          <div class="doc-date-line">
            <i class="fa-regular fa-calendar" aria-hidden="true"></i>
            <span>${tgl}</span>
          </div>
        </div>
      `;
    }

    const tableCtrl = window.SimdesTable.create({
      data: records,
      pageSize: 5,
      defaultSortKey: 'tanggalDitetapkan',
      defaultSortDirection: 'desc',
      searchFields: [
        'jenisPeraturan',
        'nomorPeraturan',
        'nomorDiundangkan',
        'tentang',
        'keterangan'
      ],
      comparators: {
        tanggalDitetapkan: (a, b) => {
          const cmp = String(a.tanggalDitetapkan || '').localeCompare(String(b.tanggalDitetapkan || ''));
          if (cmp !== 0) return cmp;
          return String(a.nomorPeraturan || '').localeCompare(String(b.nomorPeraturan || ''), 'id', { numeric: true });
        },
        tanggalDiundangkan: (a, b) => {
          const da = String(a.tanggalDiundangkan || '');
          const db = String(b.tanggalDiundangkan || '');
          if (!da && !db) return 0;
          if (!da) return -1;
          if (!db) return 1;
          return da.localeCompare(db);
        }
      },
      onRender: (state) => {
        syncSortHeadersUI(state);

        if (resetFilterBtn) {
          resetFilterBtn.disabled = !state.hasActiveQuery;
        }

        if (state.rows.length === 0) {
          tbodyEl.innerHTML = `
            <tr>
              <td colspan="7" class="p-0">
                <div class="table-empty-state">
                  <div class="table-empty-icon">
                    <i class="fa-solid fa-newspaper" aria-hidden="true"></i>
                  </div>
                  <div class="fw-bold mb-1" style="color:var(--simdes-text); font-size:0.95rem;">
                    ${state.hasActiveQuery ? 'Data Lembaran / Berita Desa tidak ditemukan' : 'Belum ada data tercatat'}
                  </div>
                  <div class="mb-3" style="font-size:0.82rem;">
                    ${
                      state.hasActiveQuery
                        ? 'Tidak ada peraturan yang cocok dengan kata kunci atau filter yang dipilih.'
                        : 'Silakan tambahkan data baru melalui tombol Tambah Data.'
                    }
                  </div>
                  ${
                    state.hasActiveQuery
                      ? `<button type="button" class="btn-simdes-outline" id="emptyResetLembaranBtn">
                           <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                           <span>Reset Pencarian &amp; Filter</span>
                         </button>`
                      : ''
                  }
                </div>
              </td>
            </tr>
          `;
          document.getElementById('emptyResetLembaranBtn')?.addEventListener('click', handleResetAllFilters);
        } else {
          tbodyEl.innerHTML = state.rows
            .map((row, idx) => {
              const noUrut = state.startIndex + idx + 1;
              const badgeCls = getJenisBadgeVariant(row.jenisPeraturan);
              const tglUndang = String(row.tanggalDiundangkan || '').trim();
              const noUndang = String(row.nomorDiundangkan || '').trim();

              return `
                <tr>
                  <td class="col-no-urut group-divider-end">${noUrut}</td>
                  <td>
                    <span class="badge-simdes ${badgeCls}">${esc(row.jenisPeraturan)}</span>
                  </td>
                  <td>
                    ${buildCombinedDitetapkanCell(row)}
                  </td>
                  <td class="group-divider-end">
                    <button type="button"
                            class="tentang-title-btn"
                            data-row-action="detail"
                            data-id="${esc(row.id)}"
                            title="Lihat rincian penetapan &amp; pengundangan">
                      ${esc(row.tentang)}
                    </button>
                  </td>
                  <td class="tabular-nums text-center" style="white-space:nowrap;">
                    ${
                      tglUndang
                        ? `<span class="fw-semibold">${esc(formatDate(tglUndang))}</span>`
                        : '<span class="text-simdes-muted">&ndash;</span>'
                    }
                  </td>
                  <td class="text-center group-divider-end">
                    ${
                      noUndang
                        ? `<span class="doc-num-badge report-badge mb-0">${esc(noUndang)}</span>`
                        : '<span class="badge-simdes badge-simdes-warning">Belum diundangkan</span>'
                    }
                  </td>
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
                                aria-label="Lihat detail ${esc(row.nomorPeraturan)}">
                          <i class="fa-regular fa-eye" aria-hidden="true"></i>
                        </button>
                        <button type="button"
                                class="btn-row-action"
                                data-row-action="edit"
                                data-id="${esc(row.id)}"
                                title="Edit Data"
                                aria-label="Edit data ${esc(row.nomorPeraturan)}">
                          <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
                        </button>
                        <button type="button"
                                class="btn-row-action action-delete"
                                data-row-action="delete"
                                data-id="${esc(row.id)}"
                                title="Hapus Data"
                                aria-label="Hapus data ${esc(row.nomorPeraturan)}">
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
              ? 'Menampilkan <strong>0</strong> dari <strong>0</strong> data peraturan'
              : `Menampilkan <strong>${state.startEntry}&ndash;${state.endEntry}</strong> dari <strong>${state.totalItems}</strong> peraturan${
                  state.hasActiveQuery ? ` (disaring dari total ${state.totalAllItems} peraturan)` : ''
                }`;
        }

        window.SimdesTable.renderPagination(paginationListEl, state, (page) => {
          tableCtrl.setPage(page);
        });
      }
    });

    tableEl?.querySelectorAll('th [data-sort-key]').forEach((btn) => {
      btn.addEventListener('click', () => {
        tableCtrl.toggleSort(btn.getAttribute('data-sort-key'));
      });
    });

    function handleResetAllFilters() {
      if (searchInput) searchInput.value = '';
      clearSearchBtn?.classList.remove('visible');
      if (filterJenisSelect) filterJenisSelect.value = 'ALL';
      if (filterTahunPenetapanSelect) filterTahunPenetapanSelect.value = 'ALL';
      if (filterTahunPengundanganSelect) filterTahunPengundanganSelect.value = 'ALL';
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

    filterJenisSelect?.addEventListener('change', () => {
      tableCtrl.setFilter('jenisPeraturan', filterJenisSelect.value);
    });

    filterTahunPenetapanSelect?.addEventListener('change', () => {
      const val = filterTahunPenetapanSelect.value;
      if (val === 'ALL') {
        tableCtrl.setFilter('tahunPenetapan', null);
      } else {
        tableCtrl.setFilter('tahunPenetapan', (_v, r) => extractYear(r.tanggalDitetapkan) === val);
      }
    });

    filterTahunPengundanganSelect?.addEventListener('change', () => {
      const val = filterTahunPengundanganSelect.value;
      if (val === 'ALL') {
        tableCtrl.setFilter('tahunPengundangan', null);
      } else if (val === 'BELUM') {
        tableCtrl.setFilter('tahunPengundangan', (_v, r) => !isPromulgated(r));
      } else {
        tableCtrl.setFilter('tahunPengundangan', (_v, r) => extractYear(r.tanggalDiundangkan) === val);
      }
    });

    pageSizeSelect?.addEventListener('change', () => {
      tableCtrl.setPageSize(Number(pageSizeSelect.value));
    });

    resetFilterBtn?.addEventListener('click', handleResetAllFilters);

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
          '<i class="fa-solid fa-plus-circle text-simdes-primary me-2" aria-hidden="true"></i>Tambah Data Lembaran / Berita Desa';
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML =
          '<i class="fa-solid fa-floppy-disk" aria-hidden="true"></i><span>Simpan Data</span>';
      }

      window.bootstrap?.Modal.getOrCreateInstance(formModalEl).show();
      formModalEl.addEventListener('shown.bs.modal', () => fJenisPeraturan?.focus(), { once: true });
    }

    function openEditModal(id) {
      const item = records.find((r) => r.id === id);
      if (!item || !formEl || !formModalEl) return;

      formEl.reset();
      hideFormBanner();

      fId.value = item.id;
      fJenisPeraturan.value = item.jenisPeraturan || '';
      fNomorPeraturan.value = item.nomorPeraturan || '';
      fTanggalDitetapkan.value = item.tanggalDitetapkan || '';
      fTentang.value = item.tentang || '';
      fTanggalDiundangkan.value = item.tanggalDiundangkan || '';
      fNomorDiundangkan.value = item.nomorDiundangkan || '';
      fKeterangan.value = item.keterangan || '';

      if (formModalTitle) {
        formModalTitle.innerHTML =
          '<i class="fa-regular fa-pen-to-square text-simdes-primary me-2" aria-hidden="true"></i>Edit Data Lembaran / Berita Desa';
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML =
          '<i class="fa-solid fa-check" aria-hidden="true"></i><span>Simpan Perubahan</span>';
      }

      window.bootstrap?.Modal.getOrCreateInstance(formModalEl).show();
      formModalEl.addEventListener('shown.bs.modal', () => fJenisPeraturan?.focus(), { once: true });
    }

    function openDetailModal(id) {
      const item = records.find((r) => r.id === id);
      if (!item || !detailModalEl) return;
      activeDetailId = item.id;

      const setText = (elId, val) => {
        const el = document.getElementById(elId);
        if (el) el.textContent = val;
      };

      setText('detLbdJenisPeraturan', item.jenisPeraturan || '-');
      setText('detLbdNomorPeraturan', item.nomorPeraturan || '-');
      setText(
        'detLbdTanggalDitetapkan',
        item.tanggalDitetapkan ? formatDate(item.tanggalDitetapkan) : '-'
      );
      setText('detLbdTentang', item.tentang || '-');
      setText(
        'detLbdTanggalDiundangkan',
        item.tanggalDiundangkan ? formatDate(item.tanggalDiundangkan) : 'Belum tercatat'
      );
      setText(
        'detLbdNomorDiundangkan',
        item.nomorDiundangkan || 'Belum tercatat'
      );
      setText('detLbdKeterangan', item.keterangan || 'Tidak ada keterangan tambahan');

      window.bootstrap?.Modal.getOrCreateInstance(detailModalEl).show();
    }

    function openDeleteModal(id) {
      const item = records.find((r) => r.id === id);
      if (!item || !deleteModalEl) return;
      pendingDeleteId = item.id;

      if (deleteTargetNameEl) {
        deleteTargetNameEl.textContent = `${item.jenisPeraturan} — ${item.nomorPeraturan}`;
      }
      if (deleteTargetMetaEl) {
        deleteTargetMetaEl.textContent = item.tentang;
      }

      window.bootstrap?.Modal.getOrCreateInstance(deleteModalEl).show();
    }

    if (formEl && window.SimdesForms?.InteractionValidator) {
      window.SimdesForms.InteractionValidator.bindForm(formEl);

      formEl.addEventListener('submit', (e) => {
        e.preventDefault();
        hideFormBanner();

        const validBase = window.SimdesForms.InteractionValidator.validateAll(formEl);
        if (!validBase) {
          showFormBanner('Mohon lengkapi kolom wajib pada bagian Informasi Peraturan.');
          return;
        }

        if (
          fTanggalDiundangkan.value &&
          fTanggalDitetapkan.value &&
          fTanggalDiundangkan.value < fTanggalDitetapkan.value
        ) {
          window.SimdesForms.InteractionValidator.syncFieldState(
            fTanggalDiundangkan,
            'Tanggal diundangkan tidak boleh lebih awal dari tanggal ditetapkan.'
          );
          fTanggalDiundangkan.focus();
          showFormBanner('Tanggal diundangkan tidak boleh mendahului tanggal ditetapkan.');
          return;
        }

        const existingId = fId.value.trim();
        const payload = {
          id: existingId || `lbd-${Date.now()}`,
          jenisPeraturan: fJenisPeraturan.value,
          nomorPeraturan: fNomorPeraturan.value.trim(),
          tanggalDitetapkan: fTanggalDitetapkan.value,
          tentang: fTentang.value.trim(),
          tanggalDiundangkan: fTanggalDiundangkan.value,
          nomorDiundangkan: fNomorDiundangkan.value.trim(),
          keterangan: fKeterangan.value.trim()
        };

        if (existingId) {
          records = records.map((r) => (r.id === existingId ? { ...r, ...payload } : r));
        } else {
          records = [payload, ...records];
        }

        saveRecords(records);
        updateSummaryStats();
        syncYearFilters();
        tableCtrl.setData(records, Boolean(existingId));

        window.bootstrap?.Modal.getOrCreateInstance(formModalEl).hide();
        window.SimdesApp?.showToast({
          title: existingId ? 'Data Diperbarui' : 'Data Ditambahkan',
          message: `"${payload.nomorPeraturan}" berhasil disimpan pada Buku Lembaran Desa dan Berita Desa.`,
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
      syncYearFilters();
      tableCtrl.setData(records, true);

      window.bootstrap?.Modal.getOrCreateInstance(deleteModalEl)?.hide();
      window.SimdesApp?.showToast({
        title: 'Data Dihapus',
        message: target
          ? `Peraturan "${target.nomorPeraturan}" telah dihapus dari daftar.`
          : 'Data berhasil dihapus.',
        variant: 'warning'
      });
    });

    resetSampleBtn?.addEventListener('click', () => {
      records = cloneSeedData();
      saveRecords(records);
      handleResetAllFilters();
      updateSummaryStats();
      syncYearFilters();
      tableCtrl.setData(records, false);
      window.SimdesApp?.showToast({
        title: 'Data Contoh Dipulihkan',
        message: '8 data contoh simulasi Buku Lembaran Desa dan Berita Desa berhasil dimuat ulang.',
        variant: 'primary'
      });
    });

    btnCetak?.addEventListener('click', () => {
      window.print();
    });

    // Initial Boot
    updateSummaryStats();
    syncYearFilters();
    tableCtrl.render();
  }

  document.addEventListener('DOMContentLoaded', initBukuLembaranBeritaDesa);
})();

