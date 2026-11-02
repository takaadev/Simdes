/* ==========================================================================
   SIMDES DESA TIARO — Buku Ekspedisi (Kode: A.8)
   File: assets/js/buku-ekspedisi.js
   Features:
   - Official 6-column table structure with combined Tanggal & Nomor Surat cell
   - Search by nomor surat, tujuan, or isi singkat surat
   - Filter by date range of Tanggal Pengiriman (Dari – Sampai)
   - Sorting by Tanggal Pengiriman, Tanggal & Nomor Surat, and Ditujukan Kepada
   - CRUD with localStorage persistence, validation, pagination, detail & print
   ========================================================================== */

(() => {
  'use strict';

  const STORAGE_KEY = 'simdes_tiaro_buku_ekspedisi_v1';

  /**
   * Data Contoh Fiktif Simulasi UI (8 pengiriman surat contoh)
   * Tidak menggunakan nomor surat resmi Pemerintah Desa Tiaro.
   */
  const SEED_DATA = [
    {
      id: 'eks-sim-001',
      tanggalPengiriman: '2026-10-28',
      nomorSurat: 'SIM-005/048/PEM-TR/X/2026',
      tanggalSurat: '2026-10-27',
      isiSingkat: 'Penyampaian undangan rapat koordinasi evaluasi program pembangunan semester II tingkat desa (Data Contoh).',
      ditujukanKepada: 'Ketua Badan Permusyawaratan Desa (BPD)',
      keterangan: 'Diterima staf sekretariat BPD — Bukti tanda terima simulasi'
    },
    {
      id: 'eks-sim-002',
      tanggalPengiriman: '2026-10-22',
      nomorSurat: 'SIM-140/044/SEK-TR/X/2026',
      tanggalSurat: '2026-10-21',
      isiSingkat: 'Pengantar laporan realisasi penyerapan anggaran dan capaian fisik kegiatan triwulan III (Data Contoh).',
      ditujukanKepada: 'Camat Wilayah Kecamatan (Up. Kasi Tata Pemerintahan)',
      keterangan: 'Dikirim langsung melalui kurir perangkat desa (contoh)'
    },
    {
      id: 'eks-sim-003',
      tanggalPengiriman: '2026-10-15',
      nomorSurat: 'SIM-412/039/KESRA-TR/X/2026',
      tanggalSurat: '2026-10-14',
      isiSingkat: 'Permohonan dukungan fasilitasi jadwal pelayanan kesehatan terpadu dan posyandu balita (Data Contoh).',
      ditujukanKepada: 'Kepala UPTD Puskesmas Kecamatan',
      keterangan: 'Data contoh simulasi antarmuka'
    },
    {
      id: 'eks-sim-004',
      tanggalPengiriman: '2026-09-29',
      nomorSurat: 'SIM-470/035/PEL-TR/IX/2026',
      tanggalSurat: '2026-09-28',
      isiSingkat: 'Penyampaian rekapitulasi laporan bulanan peristiwa kependudukan dan pemutakhiran data keluarga (Data Contoh).',
      ditujukanKepada: 'Dinas Kependudukan dan Pencatatan Sipil Kabupaten',
      keterangan: 'Tanda terima loket pelayanan instansi (contoh)'
    },
    {
      id: 'eks-sim-005',
      tanggalPengiriman: '2026-09-18',
      nomorSurat: 'SIM-520/031/EK-TR/IX/2026',
      tanggalSurat: '2026-09-17',
      isiSingkat: 'Usulan verifikasi kelompok tani penerima bantuan bibit tanaman pangan dan hortikultura desa (Data Contoh).',
      ditujukanKepada: 'Kepala Balai Penyuluhan Pertanian (BPP)',
      keterangan: 'Disertai 1 berkas lampiran daftar kelompok simulasi'
    },
    {
      id: 'eks-sim-006',
      tanggalPengiriman: '2026-08-26',
      nomorSurat: 'SIM-188/027/HUK-TR/VIII/2026',
      tanggalSurat: '2026-08-25',
      isiSingkat: 'Penyampaian salinan rancangan peraturan desa tentang pengelolaan aset desa untuk evaluasi (Data Contoh).',
      ditujukanKepada: 'Bagian Hukum Sekretariat Daerah Kabupaten',
      keterangan: 'Data contoh simulasi UI'
    },
    {
      id: 'eks-sim-007',
      tanggalPengiriman: '2026-08-11',
      nomorSurat: 'SIM-003/022/UM-TR/VIII/2026',
      tanggalSurat: '2026-08-10',
      isiSingkat: 'Pemberitahuan jadwal kerja bakti kebersihan lingkungan dan penataan batas jalan poros dusun (Data Contoh).',
      ditujukanKepada: 'Para Kepala Dusun & Ketua RT se-Desa (Simulasi)',
      keterangan: 'Edaran terdistribusi lengkap ke 4 dusun (contoh)'
    },
    {
      id: 'eks-sim-008',
      tanggalPengiriman: '2026-07-24',
      nomorSurat: 'SIM-900/018/KEU-TR/VII/2026',
      tanggalSurat: '2026-07-23',
      isiSingkat: 'Penyampaian dokumen pertanggungjawaban administrasi kegiatan semester I tahun berjalan (Data Contoh).',
      ditujukanKepada: 'Dinas Pemberdayaan Masyarakat dan Desa Kabupaten',
      keterangan: 'Diterima bidang bina pemerintahan desa (contoh)'
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
      // Ignore storage errors
    }
  }

  function initBukuEkspedisi() {
    const pageRoot = document.getElementById('bukuEkspedisiPageRoot');
    if (!pageRoot) return;

    const esc = window.SimdesApp ? window.SimdesApp.escapeHtml : (s) => String(s ?? '');
    const formatDate = window.SimdesTable ? window.SimdesTable.formatDateIndo : (s) => s;

    let records = loadRecords();
    let activeDetailId = null;
    let pendingDeleteId = null;

    // DOM References
    const statTotalEkspedisi = document.getElementById('statTotalEkspedisi');
    const statEkspedisiBulanIni = document.getElementById('statEkspedisiBulanIni');
    const statTujuanUnik = document.getElementById('statTujuanUnik');
    const printMetaEl = document.getElementById('printEkspedisiMeta');

    const tableEl = document.getElementById('ekspedisiTable');
    const tbodyEl = document.getElementById('ekspedisiTableBody');
    const paginationInfoEl = document.getElementById('tablePaginationInfo');
    const paginationListEl = document.getElementById('tablePaginationList');

    const searchInput = document.getElementById('tableSearchInput');
    const clearSearchBtn = document.getElementById('clearSearchInputBtn');
    const filterTanggalMulai = document.getElementById('filterTanggalMulai');
    const filterTanggalSelesai = document.getElementById('filterTanggalSelesai');
    const pageSizeSelect = document.getElementById('pageSizeSelect');
    const resetFilterBtn = document.getElementById('resetTableFilterBtn');
    const resetSampleBtn = document.getElementById('btnResetSampleEkspedisi');

    const btnTambah = document.getElementById('btnTambahEkspedisi');
    const btnCetak = document.getElementById('btnCetakEkspedisi');

    // Form Modal Elements
    const formModalEl = document.getElementById('ekspedisiFormModal');
    const formModalTitle = document.getElementById('ekspedisiFormModalLabel');
    const formEl = document.getElementById('ekspedisiForm');
    const formAlertBox = document.getElementById('ekspedisiFormAlert');
    const formAlertText = document.getElementById('ekspedisiFormAlertText');
    const formSubmitBtn = document.getElementById('ekspedisiFormSubmitBtn');

    const fId = document.getElementById('eksRecordId');
    const fTanggalPengiriman = document.getElementById('eksTanggalPengiriman');
    const fNomorSurat = document.getElementById('eksNomorSurat');
    const fTanggalSurat = document.getElementById('eksTanggalSurat');
    const fIsiSingkat = document.getElementById('eksIsiSingkat');
    const fDitujukanKepada = document.getElementById('eksDitujukanKepada');
    const fKeterangan = document.getElementById('eksKeterangan');

    // Detail & Delete Modals
    const detailModalEl = document.getElementById('ekspedisiDetailModal');
    const btnEditFromDetail = document.getElementById('btnEditEkspedisiFromDetail');
    const deleteModalEl = document.getElementById('ekspedisiDeleteModal');
    const deleteTargetNameEl = document.getElementById('deleteEkspedisiTargetName');
    const deleteTargetMetaEl = document.getElementById('deleteEkspedisiTargetMeta');
    const confirmDeleteBtn = document.getElementById('confirmDeleteEkspedisiBtn');

    function updateSummaryStats() {
      const total = records.length;
      const latestMonthPrefix = records.length > 0
        ? records
            .map((r) => String(r.tanggalPengiriman || '').slice(0, 7))
            .filter(Boolean)
            .sort()
            .pop() || '2026-10'
        : '2026-10';
      const inLatestMonth = records.filter((r) =>
        String(r.tanggalPengiriman || '').startsWith(latestMonthPrefix)
      ).length;
      const uniqueTargets = new Set(
        records.map((r) => String(r.ditujukanKepada || '').trim().toLowerCase()).filter(Boolean)
      ).size;

      if (statTotalEkspedisi) statTotalEkspedisi.textContent = total.toLocaleString('id-ID');
      if (statEkspedisiBulanIni) statEkspedisiBulanIni.textContent = inLatestMonth.toLocaleString('id-ID');
      if (statTujuanUnik) statTujuanUnik.textContent = uniqueTargets.toLocaleString('id-ID');

      if (printMetaEl) {
        const nowStr = new Intl.DateTimeFormat('id-ID', {
          day: 'numeric',
          month: 'long',
          year: 'numeric'
        }).format(new Date());
        printMetaEl.textContent = `Total pengiriman tercatat: ${total} surat • Dicetak pada ${nowStr}`;
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

    function buildCombinedSuratCell(row) {
      const nomor = esc(row.nomorSurat || '-');
      const tgl = row.tanggalSurat ? esc(formatDate(row.tanggalSurat)) : '-';
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
      defaultSortKey: 'tanggalPengiriman',
      defaultSortDirection: 'desc',
      searchFields: ['nomorSurat', 'ditujukanKepada', 'isiSingkat', 'keterangan'],
      comparators: {
        tanggalPengiriman: (a, b) =>
          String(a.tanggalPengiriman || '').localeCompare(String(b.tanggalPengiriman || '')),
        surat: (a, b) => {
          const cmpDate = String(a.tanggalSurat || '').localeCompare(String(b.tanggalSurat || ''));
          if (cmpDate !== 0) return cmpDate;
          return String(a.nomorSurat || '').localeCompare(String(b.nomorSurat || ''), 'id', { numeric: true });
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
              <td colspan="6" class="p-0">
                <div class="table-empty-state">
                  <div class="table-empty-icon">
                    <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
                  </div>
                  <div class="fw-bold mb-1" style="color:var(--simdes-text); font-size:0.95rem;">
                    ${state.hasActiveQuery ? 'Data ekspedisi surat tidak ditemukan' : 'Belum ada catatan pengiriman surat'}
                  </div>
                  <div class="mb-3" style="font-size:0.82rem;">
                    ${
                      state.hasActiveQuery
                        ? 'Tidak ada pengiriman surat yang sesuai dengan pencarian atau rentang tanggal yang dipilih.'
                        : 'Silakan tambahkan catatan pengiriman baru melalui tombol Tambah Ekspedisi.'
                    }
                  </div>
                  ${
                    state.hasActiveQuery
                      ? `<button type="button" class="btn-simdes-outline" id="emptyResetEkspedisiBtn">
                           <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                           <span>Reset Pencarian &amp; Filter</span>
                         </button>`
                      : ''
                  }
                </div>
              </td>
            </tr>
          `;
          document.getElementById('emptyResetEkspedisiBtn')?.addEventListener('click', handleResetAllFilters);
        } else {
          tbodyEl.innerHTML = state.rows
            .map((row, idx) => {
              const noUrut = state.startIndex + idx + 1;
              const tglKirim = row.tanggalPengiriman ? esc(formatDate(row.tanggalPengiriman)) : '-';

              return `
                <tr>
                  <td class="col-no-urut">${noUrut}</td>
                  <td class="tabular-nums fw-semibold" style="white-space:nowrap;">
                    <i class="fa-regular fa-paper-plane text-simdes-secondary me-1 no-print" aria-hidden="true"></i>
                    ${tglKirim}
                  </td>
                  <td>
                    ${buildCombinedSuratCell(row)}
                  </td>
                  <td>
                    <button type="button"
                            class="tentang-title-btn fw-semibold"
                            data-row-action="detail"
                            data-id="${esc(row.id)}"
                            title="Lihat rincian ekspedisi surat">
                      ${esc(row.isiSingkat)}
                    </button>
                  </td>
                  <td class="fw-medium">
                    ${esc(row.ditujukanKepada)}
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
                                aria-label="Lihat detail surat ${esc(row.nomorSurat)}">
                          <i class="fa-regular fa-eye" aria-hidden="true"></i>
                        </button>
                        <button type="button"
                                class="btn-row-action"
                                data-row-action="edit"
                                data-id="${esc(row.id)}"
                                title="Edit Data"
                                aria-label="Edit ekspedisi surat ${esc(row.nomorSurat)}">
                          <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
                        </button>
                        <button type="button"
                                class="btn-row-action action-delete"
                                data-row-action="delete"
                                data-id="${esc(row.id)}"
                                title="Hapus Data"
                                aria-label="Hapus ekspedisi surat ${esc(row.nomorSurat)}">
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
              ? 'Menampilkan <strong>0</strong> dari <strong>0</strong> data ekspedisi'
              : `Menampilkan <strong>${state.startEntry}&ndash;${state.endEntry}</strong> dari <strong>${state.totalItems}</strong> pengiriman surat${
                  state.hasActiveQuery ? ` (disaring dari total ${state.totalAllItems} pengiriman)` : ''
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

    function applyDateRangeFilter() {
      const startVal = filterTanggalMulai?.value || '';
      const endVal = filterTanggalSelesai?.value || '';

      if (!startVal && !endVal) {
        tableCtrl.setFilter('dateRange', null);
        return;
      }

      tableCtrl.setFilter('dateRange', (_v, row) => {
        const d = String(row.tanggalPengiriman || '');
        if (!d) return false;
        if (startVal && d < startVal) return false;
        if (endVal && d > endVal) return false;
        return true;
      });
    }

    function handleResetAllFilters() {
      if (searchInput) searchInput.value = '';
      clearSearchBtn?.classList.remove('visible');
      if (filterTanggalMulai) filterTanggalMulai.value = '';
      if (filterTanggalSelesai) filterTanggalSelesai.value = '';
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

    filterTanggalMulai?.addEventListener('change', applyDateRangeFilter);
    filterTanggalSelesai?.addEventListener('change', applyDateRangeFilter);

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
          '<i class="fa-solid fa-plus-circle text-simdes-primary me-2" aria-hidden="true"></i>Tambah Catatan Ekspedisi Surat';
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML =
          '<i class="fa-solid fa-floppy-disk" aria-hidden="true"></i><span>Simpan Ekspedisi</span>';
      }

      window.bootstrap?.Modal.getOrCreateInstance(formModalEl).show();
      formModalEl.addEventListener('shown.bs.modal', () => fTanggalPengiriman?.focus(), { once: true });
    }

    function openEditModal(id) {
      const item = records.find((r) => r.id === id);
      if (!item || !formEl || !formModalEl) return;

      formEl.reset();
      hideFormBanner();

      fId.value = item.id;
      fTanggalPengiriman.value = item.tanggalPengiriman || '';
      fNomorSurat.value = item.nomorSurat || '';
      fTanggalSurat.value = item.tanggalSurat || '';
      fIsiSingkat.value = item.isiSingkat || '';
      fDitujukanKepada.value = item.ditujukanKepada || '';
      fKeterangan.value = item.keterangan || '';

      if (formModalTitle) {
        formModalTitle.innerHTML =
          '<i class="fa-regular fa-pen-to-square text-simdes-primary me-2" aria-hidden="true"></i>Edit Catatan Ekspedisi Surat';
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML =
          '<i class="fa-solid fa-check" aria-hidden="true"></i><span>Simpan Perubahan</span>';
      }

      window.bootstrap?.Modal.getOrCreateInstance(formModalEl).show();
      formModalEl.addEventListener('shown.bs.modal', () => fTanggalPengiriman?.focus(), { once: true });
    }

    function openDetailModal(id) {
      const item = records.find((r) => r.id === id);
      if (!item || !detailModalEl) return;
      activeDetailId = item.id;

      const setText = (elId, val) => {
        const el = document.getElementById(elId);
        if (el) el.textContent = val;
      };

      setText(
        'detEksTanggalPengiriman',
        item.tanggalPengiriman ? formatDate(item.tanggalPengiriman) : '-'
      );
      setText('detEksNomorSurat', item.nomorSurat || '-');
      setText('detEksTanggalSurat', item.tanggalSurat ? formatDate(item.tanggalSurat) : '-');
      setText('detEksIsiSingkat', item.isiSingkat || '-');
      setText('detEksDitujukanKepada', item.ditujukanKepada || '-');
      setText('detEksKeterangan', item.keterangan || 'Tidak ada keterangan tambahan');

      window.bootstrap?.Modal.getOrCreateInstance(detailModalEl).show();
    }

    function openDeleteModal(id) {
      const item = records.find((r) => r.id === id);
      if (!item || !deleteModalEl) return;
      pendingDeleteId = item.id;

      if (deleteTargetNameEl) deleteTargetNameEl.textContent = item.nomorSurat;
      if (deleteTargetMetaEl) {
        const tgl = item.tanggalPengiriman ? formatDate(item.tanggalPengiriman) : '-';
        deleteTargetMetaEl.textContent = `Dikirim ${tgl} • Tujuan: ${item.ditujukanKepada}`;
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
          showFormBanner('Mohon lengkapi seluruh kolom wajib pengiriman dan surat terlebih dahulu.');
          return;
        }

        if (
          fTanggalPengiriman.value &&
          fTanggalSurat.value &&
          fTanggalPengiriman.value < fTanggalSurat.value
        ) {
          window.SimdesForms.InteractionValidator.syncFieldState(
            fTanggalPengiriman,
            'Tanggal pengiriman tidak boleh lebih awal dari tanggal surat.'
          );
          fTanggalPengiriman.focus();
          showFormBanner('Tanggal pengiriman tidak boleh mendahului tanggal surat dibuat.');
          return;
        }

        const existingId = fId.value.trim();
        const payload = {
          id: existingId || `eks-${Date.now()}`,
          tanggalPengiriman: fTanggalPengiriman.value,
          nomorSurat: fNomorSurat.value.trim(),
          tanggalSurat: fTanggalSurat.value,
          isiSingkat: fIsiSingkat.value.trim(),
          ditujukanKepada: fDitujukanKepada.value.trim(),
          keterangan: fKeterangan.value.trim()
        };

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
          title: existingId ? 'Ekspedisi Diperbarui' : 'Ekspedisi Ditambahkan',
          message: `Surat "${payload.nomorSurat}" kepada ${payload.ditujukanKepada} berhasil disimpan.`,
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
        title: 'Ekspedisi Surat Dihapus',
        message: target
          ? `Catatan surat "${target.nomorSurat}" telah dihapus dari Buku Ekspedisi.`
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
        message: '8 data contoh simulasi Buku Ekspedisi berhasil dimuat ulang.',
        variant: 'primary'
      });
    });

    btnCetak?.addEventListener('click', () => {
      window.print();
    });

    // Initial Boot
    updateSummaryStats();
    tableCtrl.render();
  }

  document.addEventListener('DOMContentLoaded', initBukuEkspedisi);
})();

