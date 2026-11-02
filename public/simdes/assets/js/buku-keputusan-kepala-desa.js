/* ==========================================================================
   SIMDES DESA TIARO — Buku Keputusan Kepala Desa Controller
   File: assets/js/buku-keputusan-kepala-desa.js
   Features: CRUD with localStorage persistence, search (nomor/tentang/uraian),
   filters, sorting, pagination, auto sequential numbering, detail view,
   delete confirmation, sample data reset, and print ledger mode.
   ========================================================================== */

(() => {
  'use strict';

  const STORAGE_KEY = 'simdes_tiaro_buku_keputusan_kepala_desa_v1';

  /**
   * Sample Simulation Data (Data Contoh)
   * Explicitly uses non-official simulation numbering and contains zero
   * personal names, per project guidelines.
   */
  const SEED_DATA = [
    {
      id: 'kep-sim-001',
      nomorKeputusan: '01/KEP-CONTOH/I/2026',
      tanggalKeputusan: '2026-01-08',
      tentang: 'Penetapan Tim Pelaksana Penataan Arsip Administrasi Umum (Data Contoh)',
      uraianSingkat: 'Menetapkan susunan tim kerja internal untuk penataan berkas surat menyurat, klasifikasi kode arsip, dan pemeliharaan buku administrasi umum.',
      nomorDilaporkan: '140/004/LAP-CONTOH/I/2026',
      tanggalDilaporkan: '2026-01-13',
      keterangan: 'Data contoh simulasi UI'
    },
    {
      id: 'kep-sim-002',
      nomorKeputusan: '02/KEP-CONTOH/I/2026',
      tanggalKeputusan: '2026-01-21',
      tentang: 'Pembentukan Kelompok Kerja Kebersihan Lingkungan dan Pengelolaan Sampah Terpadu (Data Contoh)',
      uraianSingkat: 'Pembentukan kelompok kerja pelaksana edukasi pemilahan sampah rumah tangga serta pengaturan jadwal gotong royong kebersihan lingkungan.',
      nomorDilaporkan: '140/011/LAP-CONTOH/I/2026',
      tanggalDilaporkan: '2026-01-26',
      keterangan: 'Data contoh simulasi UI'
    },
    {
      id: 'kep-sim-003',
      nomorKeputusan: '03/KEP-CONTOH/II/2026',
      tanggalKeputusan: '2026-02-10',
      tentang: 'Penetapan Pengelola Ruang Baca dan Perpustakaan Masyarakat (Data Contoh)',
      uraianSingkat: 'Penugasan unit pengelola layanan ruang baca masyarakat, inventarisasi koleksi buku bacaan umum, dan pengaturan jam layanan literasi.',
      nomorDilaporkan: '140/019/LAP-CONTOH/II/2026',
      tanggalDilaporkan: '2026-02-16',
      keterangan: 'Data contoh — Berlaku tahun berjalan'
    },
    {
      id: 'kep-sim-004',
      nomorKeputusan: '04/KEP-CONTOH/III/2026',
      tanggalKeputusan: '2026-03-04',
      tentang: 'Penetapan Standar Pelayanan Loket Administrasi Terpadu (Data Contoh)',
      uraianSingkat: 'Mengatur alur penerimaan berkas permohonan surat keterangan, standar waktu penyelesaian layanan, dan tata tertib loket pelayanan.',
      nomorDilaporkan: '140/027/LAP-CONTOH/III/2026',
      tanggalDilaporkan: '2026-03-09',
      keterangan: 'Data contoh simulasi UI'
    },
    {
      id: 'kep-sim-005',
      nomorKeputusan: '05/KEP-CONTOH/IV/2026',
      tanggalKeputusan: '2026-04-15',
      tentang: 'Pembentukan Panitia Pelaksana Musyawarah Perencanaan Kegiatan Tahunan (Data Contoh)',
      uraianSingkat: 'Menetapkan panitia teknis persiapan forum musyawarah perencanaan pembangunan tahunan serta penghimpunan daftar usulan kegiatan wilayah.',
      nomorDilaporkan: '140/038/LAP-CONTOH/IV/2026',
      tanggalDilaporkan: '2026-04-20',
      keterangan: 'Data contoh — Arsip perencanaan'
    },
    {
      id: 'kep-sim-006',
      nomorKeputusan: '06/KEP-CONTOH/VI/2026',
      tanggalKeputusan: '2026-06-09',
      tentang: 'Penetapan Jadwal Pemeliharaan Berkala Sarana Air Bersih Komunal (Data Contoh)',
      uraianSingkat: 'Pedoman teknis pemeriksaan jaringan perpipaan distribusi, kebersihan bak penampungan, serta pelaporan kondisi sarana air bersih warga.',
      nomorDilaporkan: '140/051/LAP-CONTOH/VI/2026',
      tanggalDilaporkan: '2026-06-15',
      keterangan: 'Data contoh simulasi UI'
    },
    {
      id: 'kep-sim-007',
      nomorKeputusan: '07/KEP-CONTOH/VIII/2026',
      tanggalKeputusan: '2026-08-19',
      tentang: 'Pembentukan Tim Inventarisasi Barang dan Aset Semester Kedua (Data Contoh)',
      uraianSingkat: 'Pelaksanaan sensus fisik peralatan kantor, perlengkapan layanan, dan aset tetap guna pemutakhiran data buku inventaris.',
      nomorDilaporkan: '',
      tanggalDilaporkan: '',
      keterangan: 'Data contoh — Proses penyusunan laporan'
    },
    {
      id: 'kep-sim-008',
      nomorKeputusan: '08/KEP-CONTOH/X/2025',
      tanggalKeputusan: '2025-10-14',
      tentang: 'Penetapan Tim Penyusun Laporan Penyelenggaraan Pemerintahan Akhir Tahun (Data Contoh)',
      uraianSingkat: 'Menetapkan tim penyusun dokumen evaluasi capaian kegiatan administrasi dan pelaporan penyelenggaraan pemerintahan akhir tahun anggaran.',
      nomorDilaporkan: '140/089/LAP-CONTOH/X/2025',
      tanggalDilaporkan: '2025-10-20',
      keterangan: 'Data contoh periode sebelumnya'
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
      // Ignore storage quota / private mode errors
    }
  }

  function isReported(record) {
    return Boolean(
      String(record.nomorDilaporkan || '').trim() ||
      String(record.tanggalDilaporkan || '').trim()
    );
  }

  function extractYear(isoDate) {
    if (!isoDate || typeof isoDate !== 'string') return '';
    return isoDate.slice(0, 4);
  }

  function initBukuKeputusanPage() {
    const pageRoot = document.getElementById('bukuKeputusanPageRoot');
    if (!pageRoot) return;

    const esc = window.SimdesApp ? window.SimdesApp.escapeHtml : (s) => String(s ?? '');
    const formatDate = window.SimdesTable ? window.SimdesTable.formatDateIndo : (s) => s;

    let records = loadRecords();
    let activeDetailId = null;
    let pendingDeleteId = null;

    // DOM References
    const tbody = document.getElementById('keputusanTableBody');
    const searchInput = document.getElementById('tableSearchInput');
    const clearSearchBtn = document.getElementById('clearSearchInputBtn');
    const resetFilterBtn = document.getElementById('resetTableFilterBtn');
    const filterTahunSelect = document.getElementById('filterTahunSelect');
    const filterStatusSelect = document.getElementById('filterStatusLaporSelect');
    const pageSizeSelect = document.getElementById('pageSizeSelect');
    const paginationInfo = document.getElementById('tablePaginationInfo');
    const paginationList = document.getElementById('tablePaginationList');

    const statTotalEl = document.getElementById('statTotalKeputusan');
    const statReportedEl = document.getElementById('statSudahDilaporkan');
    const statPendingEl = document.getElementById('statBelumDilaporkan');

    const addBtn = document.getElementById('btnTambahKeputusan');
    const printBtn = document.getElementById('btnCetakTabel');
    const restoreSeedBtn = document.getElementById('btnResetSampleData');

    // Modals & Form Controls
    const formModalEl = document.getElementById('keputusanFormModal');
    const detailModalEl = document.getElementById('keputusanDetailModal');
    const deleteModalEl = document.getElementById('keputusanDeleteModal');

    const formEl = document.getElementById('keputusanForm');
    const formModalTitle = document.getElementById('keputusanFormModalLabel');
    const formSubmitBtn = document.getElementById('keputusanFormSubmitBtn');

    const inputId = document.getElementById('inputRecordId');
    const inputNomorKeputusan = document.getElementById('inputNomorKeputusan');
    const inputTanggalKeputusan = document.getElementById('inputTanggalKeputusan');
    const inputTentang = document.getElementById('inputTentang');
    const inputUraianSingkat = document.getElementById('inputUraianSingkat');
    const inputNomorDilaporkan = document.getElementById('inputNomorDilaporkan');
    const inputTanggalDilaporkan = document.getElementById('inputTanggalDilaporkan');
    const inputKeterangan = document.getElementById('inputKeterangan');

    window.SimdesForms?.InteractionValidator?.bindForm(formEl);

    function updateSummaryStats() {
      const total = records.length;
      const reported = records.filter(isReported).length;
      const pending = total - reported;

      if (statTotalEl) statTotalEl.textContent = total.toLocaleString('id-ID');
      if (statReportedEl) statReportedEl.textContent = reported.toLocaleString('id-ID');
      if (statPendingEl) statPendingEl.textContent = pending.toLocaleString('id-ID');
    }

    function syncYearFilterOptions() {
      if (!filterTahunSelect) return;
      const currentVal = filterTahunSelect.value || 'ALL';
      const years = Array.from(
        new Set(records.map((r) => extractYear(r.tanggalKeputusan)).filter(Boolean))
      ).sort((a, b) => b.localeCompare(a));

      filterTahunSelect.innerHTML = `
        <option value="ALL">Semua Tahun</option>
        ${years.map((yr) => `<option value="${esc(yr)}">Tahun ${esc(yr)}</option>`).join('')}
      `;

      if (years.includes(currentVal)) {
        filterTahunSelect.value = currentVal;
      } else {
        filterTahunSelect.value = 'ALL';
      }
    }

    function buildCombinedKeputusanCell(row) {
      const nomor = esc(row.nomorKeputusan || '-');
      const tgl = row.tanggalKeputusan ? esc(formatDate(row.tanggalKeputusan)) : '-';
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

    function buildCombinedDilaporkanCell(row) {
      const hasNomor = Boolean(String(row.nomorDilaporkan || '').trim());
      const hasTgl = Boolean(String(row.tanggalDilaporkan || '').trim());

      if (!hasNomor && !hasTgl) {
        return `<span class="badge-simdes badge-simdes-warning">Belum dilaporkan</span>`;
      }

      const nomor = hasNomor ? esc(row.nomorDilaporkan) : 'Nomor belum diisi';
      const tgl = hasTgl ? esc(formatDate(row.tanggalDilaporkan)) : 'Tanggal belum diisi';

      return `
        <div>
          <span class="doc-num-badge report-badge">${nomor}</span>
          <div class="doc-date-line">
            <i class="fa-regular fa-paper-plane" aria-hidden="true"></i>
            <span>${tgl}</span>
          </div>
        </div>
      `;
    }

    function renderRowsHtml(rowsList, startIndex = 0) {
      if (rowsList.length === 0) {
        return `
          <tr>
            <td colspan="6">
              <div class="table-empty-state">
                <div class="table-empty-icon">
                  <i class="fa-solid fa-folder-open" aria-hidden="true"></i>
                </div>
                <div class="fw-bold mb-1" style="color:var(--simdes-text); font-size:0.95rem;">
                  Data Keputusan Tidak Ditemukan
                </div>
                <p class="mb-3" style="font-size:0.83rem; max-width:44ch; margin-inline:auto;">
                  Belum ada catatan yang cocok dengan kata kunci pencarian atau filter yang dipilih.
                </p>
                <div class="d-inline-flex flex-wrap justify-content-center gap-2 no-print">
                  <button type="button" class="btn-simdes-outline" data-empty-action="reset-filter">
                    <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                    <span>Reset Pencarian</span>
                  </button>
                  <button type="button" class="btn-simdes-primary" data-empty-action="add-new">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                    <span>Tambah Keputusan</span>
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
          const ketText = String(row.keterangan || '').trim()
            ? esc(row.keterangan)
            : '-';

          return `
            <tr data-row-id="${esc(row.id)}">
              <td class="col-no-urut">${nomorUrut}</td>
              <td>${buildCombinedKeputusanCell(row)}</td>
              <td>
                <button type="button" class="tentang-title-btn" data-row-action="detail" data-id="${esc(row.id)}">
                  ${esc(row.tentang)}
                </button>
              </td>
              <td>${esc(row.uraianSingkat)}</td>
              <td>${buildCombinedDilaporkanCell(row)}</td>
              <td>
                <div class="ket-cell-content">
                  <span class="ket-text">${ketText}</span>
                  <div class="row-action-group no-print" role="group" aria-label="Aksi baris keputusan nomor urut ${nomorUrut}">
                    <button type="button" class="btn-row-action" data-row-action="detail" data-id="${esc(row.id)}" title="Lihat Detail" aria-label="Lihat detail keputusan ${esc(row.nomorKeputusan)}">
                      <i class="fa-regular fa-eye" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn-row-action" data-row-action="edit" data-id="${esc(row.id)}" title="Edit Data" aria-label="Edit keputusan ${esc(row.nomorKeputusan)}">
                      <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn-row-action action-delete" data-row-action="delete" data-id="${esc(row.id)}" title="Hapus Data" aria-label="Hapus keputusan ${esc(row.nomorKeputusan)}">
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
      searchFields: ['nomorKeputusan', 'tentang', 'uraianSingkat'],
      defaultSortKey: 'keputusan',
      defaultSortDirection: 'desc',
      comparators: {
        keputusan: (a, b) => {
          const cmpDate = String(a.tanggalKeputusan || '').localeCompare(String(b.tanggalKeputusan || ''));
          if (cmpDate !== 0) return cmpDate;
          return String(a.nomorKeputusan || '').localeCompare(String(b.nomorKeputusan || ''), 'id', { numeric: true });
        },
        tentang: (a, b) =>
          String(a.tentang || '').localeCompare(String(b.tentang || ''), 'id', { sensitivity: 'base' }),
        dilaporkan: (a, b) => {
          const cmpDate = String(a.tanggalDilaporkan || '').localeCompare(String(b.tanggalDilaporkan || ''));
          if (cmpDate !== 0) return cmpDate;
          return String(a.nomorDilaporkan || '').localeCompare(String(b.nomorDilaporkan || ''), 'id', { numeric: true });
        }
      },
      onRender: (state) => {
        if (tbody) {
          tbody.innerHTML = renderRowsHtml(state.rows, state.startIndex);
        }

        if (paginationInfo) {
          if (state.totalItems === 0) {
            paginationInfo.textContent = 'Menampilkan 0 data keputusan';
          } else {
            paginationInfo.innerHTML = `Menampilkan <strong>${state.startEntry}&ndash;${state.endEntry}</strong> dari <strong>${state.totalItems}</strong> data keputusan`;
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

    // Open Add Modal
    function openCreateModal() {
      formEl?.reset();
      if (inputId) inputId.value = '';
      if (formModalTitle) {
        formModalTitle.innerHTML = `<i class="fa-solid fa-plus-circle text-simdes-primary me-2" aria-hidden="true"></i>Tambah Keputusan Kepala Desa`;
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML = `<i class="fa-solid fa-floppy-disk" aria-hidden="true"></i><span>Simpan Keputusan</span>`;
      }
      if (formModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(formModalEl).show();
      }
    }

    // Open Edit Modal
    function openEditModal(id) {
      const record = records.find((r) => r.id === id);
      if (!record || !formEl) return;

      formEl.reset();
      if (inputId) inputId.value = record.id;
      if (inputNomorKeputusan) inputNomorKeputusan.value = record.nomorKeputusan || '';
      if (inputTanggalKeputusan) inputTanggalKeputusan.value = record.tanggalKeputusan || '';
      if (inputTentang) inputTentang.value = record.tentang || '';
      if (inputUraianSingkat) inputUraianSingkat.value = record.uraianSingkat || '';
      if (inputNomorDilaporkan) inputNomorDilaporkan.value = record.nomorDilaporkan || '';
      if (inputTanggalDilaporkan) inputTanggalDilaporkan.value = record.tanggalDilaporkan || '';
      if (inputKeterangan) inputKeterangan.value = record.keterangan || '';

      if (formModalTitle) {
        formModalTitle.innerHTML = `<i class="fa-regular fa-pen-to-square text-simdes-primary me-2" aria-hidden="true"></i>Edit Keputusan Kepala Desa`;
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML = `<i class="fa-solid fa-check" aria-hidden="true"></i><span>Simpan Perubahan</span>`;
      }

      if (formModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(formModalEl).show();
      }
    }

    // Open Detail Modal
    function openDetailModal(id) {
      const idx = records.findIndex((r) => r.id === id);
      if (idx === -1) return;
      const record = records[idx];
      activeDetailId = record.id;

      const setDetailText = (elId, text) => {
        const el = document.getElementById(elId);
        if (el) el.textContent = text;
      };

      const nomorKeputusanText = record.nomorKeputusan || '-';
      const tglKeputusanText = record.tanggalKeputusan ? formatDate(record.tanggalKeputusan) : '-';
      const hasLapor = isReported(record);

      setDetailText('detailNomorKeputusan', nomorKeputusanText);
      setDetailText('detailTanggalKeputusan', tglKeputusanText);
      setDetailText('detailGabunganKeputusan', `${nomorKeputusanText} — ${tglKeputusanText}`);
      setDetailText('detailTentang', record.tentang || '-');
      setDetailText('detailUraianSingkat', record.uraianSingkat || '-');
      setDetailText(
        'detailNomorDilaporkan',
        String(record.nomorDilaporkan || '').trim() || 'Belum dicantumkan'
      );
      setDetailText(
        'detailTanggalDilaporkan',
        record.tanggalDilaporkan ? formatDate(record.tanggalDilaporkan) : 'Belum dilaporkan'
      );
      setDetailText('detailKeterangan', String(record.keterangan || '').trim() || '-');

      const statusBadge = document.getElementById('detailStatusBadge');
      if (statusBadge) {
        statusBadge.className = hasLapor
          ? 'badge-simdes badge-simdes-success'
          : 'badge-simdes badge-simdes-warning';
        statusBadge.textContent = hasLapor ? 'Sudah Dilaporkan' : 'Belum Dilaporkan';
      }

      if (detailModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(detailModalEl).show();
      }
    }

    // Open Delete Confirmation Modal
    function openDeleteModal(id) {
      const record = records.find((r) => r.id === id);
      if (!record) return;
      pendingDeleteId = record.id;

      const targetNomorEl = document.getElementById('deleteTargetNomor');
      const targetTentangEl = document.getElementById('deleteTargetTentang');
      if (targetNomorEl) targetNomorEl.textContent = record.nomorKeputusan;
      if (targetTentangEl) targetTentangEl.textContent = record.tentang;

      if (deleteModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(deleteModalEl).show();
      }
    }

    // Validate chronological consistency on Pelaporan date
    function validateLogicalDates() {
      if (!inputTanggalKeputusan || !inputTanggalDilaporkan) return true;
      inputTanggalDilaporkan.setCustomValidity('');

      const tglKep = inputTanggalKeputusan.value;
      const tglLap = inputTanggalDilaporkan.value;
      if (tglKep && tglLap && tglLap < tglKep) {
        const msg = 'Tanggal dilaporkan tidak boleh mendahului tanggal keputusan.';
        window.SimdesForms?.InteractionValidator?.syncFieldState(inputTanggalDilaporkan, msg);
        inputTanggalDilaporkan.focus();
        return false;
      }
      window.SimdesForms?.InteractionValidator?.syncFieldState(inputTanggalDilaporkan);
      return true;
    }

    // Form Submit Handler (Create / Update)
    formEl?.addEventListener('submit', (e) => {
      e.preventDefault();

      const validRequired = window.SimdesForms?.InteractionValidator
        ? window.SimdesForms.InteractionValidator.validateAll(formEl)
        : formEl.checkValidity();

      if (!validRequired) return;
      if (!validateLogicalDates()) return;

      const editingId = inputId?.value.trim();
      const payload = {
        id: editingId || `kep-${Date.now()}`,
        nomorKeputusan: inputNomorKeputusan.value.trim(),
        tanggalKeputusan: inputTanggalKeputusan.value,
        tentang: inputTentang.value.trim(),
        uraianSingkat: inputUraianSingkat.value.trim(),
        nomorDilaporkan: inputNomorDilaporkan.value.trim(),
        tanggalDilaporkan: inputTanggalDilaporkan.value,
        keterangan: inputKeterangan.value.trim()
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
        title: editingId ? 'Data Keputusan Diperbarui' : 'Keputusan Baru Ditambahkan',
        message: `Keputusan Nomor "${payload.nomorKeputusan}" berhasil disimpan pada penyimpanan lokal.`,
        variant: 'success'
      });
    });

    // Event Delegation on Table Body
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
        const emptyAction = emptyBtn.getAttribute('data-empty-action');
        if (emptyAction === 'reset-filter') resetAllFilters();
        else if (emptyAction === 'add-new') openCreateModal();
      }
    });

    // Detail Modal -> Edit Shortcut
    document.getElementById('btnEditFromDetail')?.addEventListener('click', () => {
      if (!activeDetailId) return;
      const targetId = activeDetailId;
      if (detailModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getInstance(detailModalEl)?.hide();
      }
      window.setTimeout(() => openEditModal(targetId), 180);
    });

    // Confirm Delete Handler
    document.getElementById('confirmDeleteKeputusanBtn')?.addEventListener('click', () => {
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
        title: 'Data Keputusan Dihapus',
        message: target
          ? `Keputusan "${target.nomorKeputusan}" telah dihapus dari daftar.`
          : 'Data keputusan berhasil dihapus.',
        variant: 'warning'
      });
    });

    // Search & Filter Bindings
    function resetAllFilters() {
      if (searchInput) searchInput.value = '';
      if (filterTahunSelect) filterTahunSelect.value = 'ALL';
      if (filterStatusSelect) filterStatusSelect.value = 'ALL';
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

    filterTahunSelect?.addEventListener('change', () => {
      const val = filterTahunSelect.value;
      if (val === 'ALL') {
        tableCtrl.setFilter('tahun', null);
      } else {
        tableCtrl.setFilter('tahun', (_, row) => extractYear(row.tanggalKeputusan) === val);
      }
    });

    filterStatusSelect?.addEventListener('change', () => {
      const val = filterStatusSelect.value;
      if (val === 'ALL') {
        tableCtrl.setFilter('statusLapor', null);
      } else if (val === 'REPORTED') {
        tableCtrl.setFilter('statusLapor', (_, row) => isReported(row));
      } else if (val === 'UNREPORTED') {
        tableCtrl.setFilter('statusLapor', (_, row) => !isReported(row));
      }
    });

    pageSizeSelect?.addEventListener('change', () => {
      tableCtrl.setPageSize(Number(pageSizeSelect.value) || 5);
    });

    // Column Sorting Bindings
    document.querySelectorAll('[data-sort-key]').forEach((btn) => {
      btn.addEventListener('click', () => {
        const key = btn.getAttribute('data-sort-key');
        if (key) tableCtrl.toggleSort(key);
      });
    });

    // Top Action Buttons
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
        message: 'Daftar Buku Keputusan Kepala Desa dikembalikan ke 8 data simulasi awal.',
        variant: 'primary'
      });
    });

    // Print Full Filtered Ledger (Renders all matching rows for print, then restores page slice)
    const preparePrintView = () => {
      const state = tableCtrl.getState();
      if (tbody) {
        tbody.innerHTML = renderRowsHtml(state.allFilteredRows, 0);
      }
      const printMetaEl = document.getElementById('printMetaTimestamp');
      if (printMetaEl) {
        try {
          const nowStr = new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
          }).format(new Date());
          printMetaEl.textContent = `Dicetak pada: ${nowStr} • Total: ${state.allFilteredRows.length} data keputusan (Data Contoh Simulasi)`;
        } catch (_) {
          printMetaEl.textContent = `Total: ${state.allFilteredRows.length} data keputusan (Data Contoh Simulasi)`;
        }
      }
    };

    const restorePaginatedView = () => {
      tableCtrl.render();
    };

    window.addEventListener('beforeprint', preparePrintView);
    window.addEventListener('afterprint', restorePaginatedView);

    printBtn?.addEventListener('click', () => {
      preparePrintView();
      window.print();
    });

    // Initial Render
    updateSummaryStats();
    syncYearFilterOptions();
    tableCtrl.render();
  }

  document.addEventListener('DOMContentLoaded', initBukuKeputusanPage);
})();

