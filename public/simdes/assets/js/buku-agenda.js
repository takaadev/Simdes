/* ==========================================================================
   SIMDES DESA TIARO — Buku Agenda (Kode: A.7 • Arsip Surat)
   File: assets/js/buku-agenda.js
   Features:
   - Official 11-column multi-header table (SURAT MASUK colspan="4", SURAT KELUAR colspan="4")
   - Unified agenda row model supporting Surat Masuk, Surat Keluar, or Both simultaneously
   - Dynamic form fieldsets & targeted validation based on Jenis Pencatatan
   - Context-aware Detail modal hiding unrecorded mail sections cleanly
   - Search, Jenis Pencatatan filter, Date-Range filter, Date sorting, Pagination, Print
   - Dedicated localStorage persistence (`simdes_tiaro_buku_agenda`)
   ========================================================================== */

(() => {
  'use strict';

  const STORAGE_KEY = 'simdes_tiaro_buku_agenda';

  /**
   * Data Contoh Fiktif Simulasi UI (8 baris agenda contoh)
   * Menampilkan:
   * - Contoh agenda Surat Masuk saja ('MASUK')
   * - Contoh agenda Surat Keluar saja ('KELUAR')
   * - Contoh agenda Surat Masuk dan Surat Keluar sekaligus dalam satu baris ('KEDUANYA')
   * Tidak menggunakan nomor surat atau data resmi Pemerintah Desa Tiaro.
   */
  const SEED_DATA = [
    {
      id: 'agd-sim-001',
      jenisPencatatan: 'MASUK',
      tanggalAgenda: '2026-10-30',
      nomorMasuk: 'SIM-SM/005/118/X/2026',
      tanggalMasuk: '2026-10-29',
      pengirim: 'Kantor Kecamatan Wilayah (Seksi Tata Pemerintahan)',
      isiMasuk: 'Pemberitahuan jadwal pembinaan penataan kearsipan dan tata naskah dinas pemerintah desa semester II (Data Contoh).',
      nomorKeluar: '',
      tanggalKeluar: '',
      tujuanKeluar: '',
      isiKeluar: '',
      keterangan: 'Disposisi Kepala Desa kepada Sekretaris Desa (contoh)'
    },
    {
      id: 'agd-sim-002',
      jenisPencatatan: 'KELUAR',
      tanggalAgenda: '2026-10-27',
      nomorMasuk: '',
      tanggalMasuk: '',
      pengirim: '',
      isiMasuk: '',
      nomorKeluar: 'SIM-SK/140/052/X/2026',
      tanggalKeluar: '2026-10-27',
      tujuanKeluar: 'Ketua Badan Permusyawaratan Desa (BPD)',
      isiKeluar: 'Penyampaian undangan musyawarah evaluasi pelaksanaan kegiatan pembangunan triwulan III tingkat desa (Data Contoh).',
      keterangan: 'Dikirim melalui petugas sekretariat desa (contoh)'
    },
    {
      id: 'agd-sim-003',
      jenisPencatatan: 'KEDUANYA',
      tanggalAgenda: '2026-10-21',
      nomorMasuk: 'SIM-SM/412/094/X/2026',
      tanggalMasuk: '2026-10-19',
      pengirim: 'UPTD Puskesmas Kecamatan',
      isiMasuk: 'Permintaan konfirmasi jadwal dan lokasi pelaksanaan pelayanan posyandu terpadu di empat dusun (Data Contoh).',
      nomorKeluar: 'SIM-SK/412/049/X/2026',
      tanggalKeluar: '2026-10-21',
      tujuanKeluar: 'Kepala UPTD Puskesmas Kecamatan',
      isiKeluar: 'Surat balasan penetapan jadwal, kesiapan kader, serta titik balai posyandu di Dusun I s.d. Dusun IV (Data Contoh).',
      keterangan: 'Surat masuk dan surat balasan tercatat terpadu (contoh)'
    },
    {
      id: 'agd-sim-004',
      jenisPencatatan: 'MASUK',
      tanggalAgenda: '2026-10-14',
      nomorMasuk: 'SIM-SM/520/087/X/2026',
      tanggalMasuk: '2026-10-12',
      pengirim: 'Balai Penyuluhan Pertanian (BPP) Kecamatan',
      isiMasuk: 'Edaran pendataan kebutuhan sarana produksi kelompok tani untuk musim tanam berikutnya (Data Contoh).',
      nomorKeluar: '',
      tanggalKeluar: '',
      tujuanKeluar: '',
      isiKeluar: '',
      keterangan: 'Diteruskan kepada Kaur Perencanaan & Kasi Kesra (contoh)'
    },
    {
      id: 'agd-sim-005',
      jenisPencatatan: 'KELUAR',
      tanggalAgenda: '2026-09-29',
      nomorMasuk: '',
      tanggalMasuk: '',
      pengirim: '',
      isiMasuk: '',
      nomorKeluar: 'SIM-SK/470/041/IX/2026',
      tanggalKeluar: '2026-09-28',
      tujuanKeluar: 'Dinas Kependudukan dan Pencatatan Sipil Kabupaten',
      isiKeluar: 'Pengantar laporan rekapitulasi perkembangan administrasi kependudukan bulanan tingkat desa (Data Contoh).',
      keterangan: 'Arsip pertinggal tersimpan pada map laporan bulanan (contoh)'
    },
    {
      id: 'agd-sim-006',
      jenisPencatatan: 'KEDUANYA',
      tanggalAgenda: '2026-09-16',
      nomorMasuk: 'SIM-SM/900/076/IX/2026',
      tanggalMasuk: '2026-09-14',
      pengirim: 'Dinas Pemberdayaan Masyarakat dan Desa Kabupaten',
      isiMasuk: 'Permintaan kelengkapan matriks inventarisasi aset desa dan tindak lanjut pemeliharaan sarana desa (Data Contoh).',
      nomorKeluar: 'SIM-SK/900/037/IX/2026',
      tanggalKeluar: '2026-09-16',
      tujuanKeluar: 'Kepala Dinas Pemberdayaan Masyarakat dan Desa Kabupaten',
      isiKeluar: 'Penyampaian berkas rekapitulasi pemutakhiran data inventaris dan laporan kondisi fasilitas desa (Data Contoh).',
      keterangan: 'Dilampiri 1 bundel rekapitulasi simulasi (contoh)'
    },
    {
      id: 'agd-sim-007',
      jenisPencatatan: 'MASUK',
      tanggalAgenda: '2026-08-25',
      nomorMasuk: 'SIM-SM/188/063/VIII/2026',
      tanggalMasuk: '2026-08-22',
      pengirim: 'Bagian Hukum Sekretariat Daerah Kabupaten',
      isiMasuk: 'Hasil fasilitasi dan klarifikasi teknis atas rancangan produk hukum desa tahun berjalan (Data Contoh).',
      nomorKeluar: '',
      tanggalKeluar: '',
      tujuanKeluar: '',
      isiKeluar: '',
      keterangan: 'Bahan pembahasan bersama BPD (contoh)'
    },
    {
      id: 'agd-sim-008',
      jenisPencatatan: 'KELUAR',
      tanggalAgenda: '2026-08-10',
      nomorMasuk: '',
      tanggalMasuk: '',
      pengirim: '',
      isiMasuk: '',
      nomorKeluar: 'SIM-SK/003/029/VIII/2026',
      tanggalKeluar: '2026-08-10',
      tujuanKeluar: 'Para Kepala Dusun & Ketua RT se-Desa (Simulasi)',
      isiKeluar: 'Surat edaran jadwal kerja bakti kebersihan lingkungan serta penataan drainase jalan poros dusun (Data Contoh).',
      keterangan: 'Terdistribusi ke seluruh wilayah dusun (contoh)'
    }
  ];

  function resolveJenisPencatatan(row) {
    if (row.jenisPencatatan === 'MASUK' || row.jenisPencatatan === 'KELUAR' || row.jenisPencatatan === 'KEDUANYA') {
      return row.jenisPencatatan;
    }
    const hasIn = Boolean(String(row.nomorMasuk || '').trim() || String(row.isiMasuk || '').trim());
    const hasOut = Boolean(String(row.nomorKeluar || '').trim() || String(row.isiKeluar || '').trim());
    if (hasIn && hasOut) return 'KEDUANYA';
    if (hasOut) return 'KELUAR';
    return 'MASUK';
  }

  function getJenisLabel(jenis) {
    if (jenis === 'MASUK') return 'Surat Masuk';
    if (jenis === 'KELUAR') return 'Surat Keluar';
    if (jenis === 'KEDUANYA') return 'Surat Masuk dan Surat Keluar';
    return 'Surat Masuk';
  }

  function getJenisBadgeClass(jenis) {
    if (jenis === 'MASUK') return 'badge-simdes badge-simdes-primary';
    if (jenis === 'KELUAR') return 'badge-simdes badge-simdes-accent';
    return 'badge-simdes badge-simdes-info';
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

  function initBukuAgenda() {
    const pageRoot = document.getElementById('bukuAgendaPageRoot');
    if (!pageRoot) return;

    const esc = window.SimdesApp ? window.SimdesApp.escapeHtml : (s) => String(s ?? '');
    const formatDate = window.SimdesTable ? window.SimdesTable.formatDateIndo : (s) => s;

    let records = loadRecords();
    let activeDetailId = null;
    let pendingDeleteId = null;

    // DOM References
    const statTotalAgenda = document.getElementById('statTotalAgenda');
    const statAgendaMasuk = document.getElementById('statAgendaMasuk');
    const statAgendaKeluar = document.getElementById('statAgendaKeluar');
    const statAgendaKeduanya = document.getElementById('statAgendaKeduanya');
    const printMetaEl = document.getElementById('printAgendaMeta');

    const tableEl = document.getElementById('agendaTable');
    const tbodyEl = document.getElementById('agendaTableBody');
    const paginationInfoEl = document.getElementById('tablePaginationInfo');
    const paginationListEl = document.getElementById('tablePaginationList');

    const searchInput = document.getElementById('tableSearchInput');
    const clearSearchBtn = document.getElementById('clearSearchInputBtn');
    const filterJenisAgendaSelect = document.getElementById('filterJenisAgendaSelect');
    const filterTanggalMulai = document.getElementById('filterTanggalMulai');
    const filterTanggalSelesai = document.getElementById('filterTanggalSelesai');
    const pageSizeSelect = document.getElementById('pageSizeSelect');
    const resetFilterBtn = document.getElementById('resetTableFilterBtn');
    const resetSampleBtn = document.getElementById('btnResetSampleAgenda');

    const btnTambah = document.getElementById('btnTambahAgenda');
    const btnCetak = document.getElementById('btnCetakAgenda');

    // Form Modal Elements
    const formModalEl = document.getElementById('agendaFormModal');
    const formModalTitle = document.getElementById('agendaFormModalLabel');
    const formEl = document.getElementById('agendaForm');
    const formAlertBox = document.getElementById('agendaFormAlert');
    const formAlertText = document.getElementById('agendaFormAlertText');
    const formSubmitBtn = document.getElementById('agendaFormSubmitBtn');

    const fId = document.getElementById('agdRecordId');
    const fTanggalAgenda = document.getElementById('agdTanggalAgenda');

    const fieldsetMasuk = document.getElementById('fieldsetSuratMasuk');
    const fNomorMasuk = document.getElementById('agdNomorMasuk');
    const fTanggalMasuk = document.getElementById('agdTanggalMasuk');
    const fPengirim = document.getElementById('agdPengirim');
    const fIsiMasuk = document.getElementById('agdIsiMasuk');

    const fieldsetKeluar = document.getElementById('fieldsetSuratKeluar');
    const fNomorKeluar = document.getElementById('agdNomorKeluar');
    const fTanggalKeluar = document.getElementById('agdTanggalKeluar');
    const fTujuanKeluar = document.getElementById('agdTujuanKeluar');
    const fIsiKeluar = document.getElementById('agdIsiKeluar');

    const fKeterangan = document.getElementById('agdKeterangan');

    // Detail & Delete Modals
    const detailModalEl = document.getElementById('agendaDetailModal');
    const detSectionSuratMasuk = document.getElementById('detSectionSuratMasuk');
    const detSectionSuratKeluar = document.getElementById('detSectionSuratKeluar');
    const detAgdJenisBadge = document.getElementById('detAgdJenisBadge');
    const btnEditFromDetail = document.getElementById('btnEditAgendaFromDetail');

    const deleteModalEl = document.getElementById('agendaDeleteModal');
    const deleteTargetNameEl = document.getElementById('deleteAgendaTargetName');
    const deleteTargetMetaEl = document.getElementById('deleteAgendaTargetMeta');
    const confirmDeleteBtn = document.getElementById('confirmDeleteAgendaBtn');

    const masukControls = [fNomorMasuk, fTanggalMasuk, fPengirim, fIsiMasuk].filter(Boolean);
    const keluarControls = [fNomorKeluar, fTanggalKeluar, fTujuanKeluar, fIsiKeluar].filter(Boolean);

    function getSelectedJenisRadio() {
      if (!formEl) return 'MASUK';
      const checked = formEl.querySelector('input[type="radio"][name="jenisPencatatan"]:checked');
      return checked ? checked.value : 'MASUK';
    }

    function setSelectedJenisRadio(val) {
      if (!formEl) return;
      const targetVal = val === 'KELUAR' || val === 'KEDUANYA' ? val : 'MASUK';
      const radios = Array.from(formEl.querySelectorAll('input[type="radio"][name="jenisPencatatan"]'));
      radios.forEach((r) => {
        r.checked = r.value === targetVal;
      });
    }

    function clearControlValidation(input) {
      if (!input) return;
      input.setCustomValidity('');
      input.classList.remove('user-invalid-fallback', 'user-valid-fallback', 'is-invalid-field');
      input.removeAttribute('aria-invalid');
      input.closest('.simdes-field')?.classList.remove('has-error');
    }

    function syncFormSectionsByJenis() {
      const jenis = getSelectedJenisRadio();
      const showMasuk = jenis === 'MASUK' || jenis === 'KEDUANYA';
      const showKeluar = jenis === 'KELUAR' || jenis === 'KEDUANYA';

      if (fieldsetMasuk) {
        fieldsetMasuk.hidden = !showMasuk;
        fieldsetMasuk.disabled = !showMasuk;
      }
      masukControls.forEach((ctrl) => {
        if (showMasuk) {
          ctrl.setAttribute('required', '');
        } else {
          ctrl.removeAttribute('required');
          clearControlValidation(ctrl);
        }
      });

      if (fieldsetKeluar) {
        fieldsetKeluar.hidden = !showKeluar;
        fieldsetKeluar.disabled = !showKeluar;
      }
      keluarControls.forEach((ctrl) => {
        if (showKeluar) {
          ctrl.setAttribute('required', '');
        } else {
          ctrl.removeAttribute('required');
          clearControlValidation(ctrl);
        }
      });
    }

    function updateSummaryStats() {
      const total = records.length;
      const masukCount = records.filter((r) => resolveJenisPencatatan(r) === 'MASUK').length;
      const keluarCount = records.filter((r) => resolveJenisPencatatan(r) === 'KELUAR').length;
      const keduanyaCount = records.filter((r) => resolveJenisPencatatan(r) === 'KEDUANYA').length;

      if (statTotalAgenda) statTotalAgenda.textContent = total.toLocaleString('id-ID');
      if (statAgendaMasuk) statAgendaMasuk.textContent = masukCount.toLocaleString('id-ID');
      if (statAgendaKeluar) statAgendaKeluar.textContent = keluarCount.toLocaleString('id-ID');
      if (statAgendaKeduanya) statAgendaKeduanya.textContent = keduanyaCount.toLocaleString('id-ID');

      if (printMetaEl) {
        const nowStr = new Intl.DateTimeFormat('id-ID', {
          day: 'numeric',
          month: 'long',
          year: 'numeric'
        }).format(new Date());
        printMetaEl.textContent = `Total agenda tercatat: ${total} baris (${masukCount} Surat Masuk, ${keluarCount} Surat Keluar, ${keduanyaCount} Masuk & Keluar) • Dicetak pada ${nowStr}`;
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

    function compareNullableIsoDates(daRaw, dbRaw) {
      const da = String(daRaw || '').trim();
      const db = String(dbRaw || '').trim();
      if (!da && !db) return 0;
      if (!da) return -1;
      if (!db) return 1;
      return da.localeCompare(db);
    }

    const tableCtrl = window.SimdesTable.create({
      data: records,
      pageSize: 5,
      defaultSortKey: 'tanggalAgenda',
      defaultSortDirection: 'desc',
      searchFields: [
        'nomorMasuk',
        'pengirim',
        'isiMasuk',
        'nomorKeluar',
        'tujuanKeluar',
        'isiKeluar',
        'keterangan'
      ],
      comparators: {
        tanggalAgenda: (a, b) => compareNullableIsoDates(a.tanggalAgenda, b.tanggalAgenda),
        tanggalMasuk: (a, b) => compareNullableIsoDates(a.tanggalMasuk, b.tanggalMasuk),
        tanggalKeluar: (a, b) => compareNullableIsoDates(a.tanggalKeluar, b.tanggalKeluar)
      },
      onRender: (state) => {
        syncSortHeadersUI(state);

        if (resetFilterBtn) {
          resetFilterBtn.disabled = !state.hasActiveQuery;
        }

        if (state.rows.length === 0) {
          tbodyEl.innerHTML = `
            <tr>
              <td colspan="11" class="p-0">
                <div class="table-empty-state">
                  <div class="table-empty-icon">
                    <i class="fa-solid fa-folder-open" aria-hidden="true"></i>
                  </div>
                  <div class="fw-bold mb-1" style="color:var(--simdes-text); font-size:0.95rem;">
                    ${state.hasActiveQuery ? 'Data agenda surat tidak ditemukan' : 'Belum ada catatan pada Buku Agenda'}
                  </div>
                  <div class="mb-3" style="font-size:0.82rem;">
                    ${
                      state.hasActiveQuery
                        ? 'Tidak ada agenda surat yang sesuai dengan pencarian, jenis pencatatan, atau rentang tanggal.'
                        : 'Silakan tambahkan catatan surat masuk atau surat keluar menggunakan tombol Tambah Agenda Surat.'
                    }
                  </div>
                  ${
                    state.hasActiveQuery
                      ? `<button type="button" class="btn-simdes-outline" id="emptyResetAgendaBtn">
                           <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                           <span>Reset Pencarian &amp; Filter</span>
                         </button>`
                      : ''
                  }
                </div>
              </td>
            </tr>
          `;
          document.getElementById('emptyResetAgendaBtn')?.addEventListener('click', handleResetAllFilters);
        } else {
          tbodyEl.innerHTML = state.rows
            .map((row, idx) => {
              const noUrut = state.startIndex + idx + 1;
              const jenis = resolveJenisPencatatan(row);
              const hasMasuk = jenis === 'MASUK' || jenis === 'KEDUANYA';
              const hasKeluar = jenis === 'KELUAR' || jenis === 'KEDUANYA';

              const tglAgendaStr = row.tanggalAgenda ? esc(formatDate(row.tanggalAgenda)) : '&ndash;';

              const nomorMasukCell = hasMasuk && row.nomorMasuk
                ? `<button type="button" class="tentang-title-btn" data-row-action="detail" data-id="${esc(row.id)}" title="Lihat detail agenda">
                     <span class="doc-num-badge mb-0">${esc(row.nomorMasuk)}</span>
                   </button>`
                : '<span class="text-simdes-muted">&ndash;</span>';

              const tglMasukCell = hasMasuk && row.tanggalMasuk
                ? `<span class="tabular-nums fw-medium">${esc(formatDate(row.tanggalMasuk))}</span>`
                : '<span class="text-simdes-muted">&ndash;</span>';

              const pengirimCell = hasMasuk && row.pengirim
                ? `<span class="fw-medium">${esc(row.pengirim)}</span>`
                : '<span class="text-simdes-muted">&ndash;</span>';

              const isiMasukCell = hasMasuk && row.isiMasuk
                ? esc(row.isiMasuk)
                : '<span class="text-simdes-muted">&ndash;</span>';

              const nomorKeluarCell = hasKeluar && row.nomorKeluar
                ? `<button type="button" class="tentang-title-btn" data-row-action="detail" data-id="${esc(row.id)}" title="Lihat detail agenda">
                     <span class="doc-num-badge report-badge mb-0">${esc(row.nomorKeluar)}</span>
                   </button>`
                : '<span class="text-simdes-muted">&ndash;</span>';

              const tglKeluarCell = hasKeluar && row.tanggalKeluar
                ? `<span class="tabular-nums fw-medium">${esc(formatDate(row.tanggalKeluar))}</span>`
                : '<span class="text-simdes-muted">&ndash;</span>';

              const tujuanKeluarCell = hasKeluar && row.tujuanKeluar
                ? `<span class="fw-medium">${esc(row.tujuanKeluar)}</span>`
                : '<span class="text-simdes-muted">&ndash;</span>';

              const isiKeluarCell = hasKeluar && row.isiKeluar
                ? esc(row.isiKeluar)
                : '<span class="text-simdes-muted">&ndash;</span>';

              const ariaRowTarget = esc(row.nomorMasuk || row.nomorKeluar || `Baris ${noUrut}`);

              return `
                <tr>
                  <td class="col-no-urut sticky-col-first group-divider-end">${noUrut}</td>
                  <td class="tabular-nums fw-semibold text-center group-divider-end" style="white-space:nowrap;">
                    ${tglAgendaStr}
                  </td>
                  <td>${nomorMasukCell}</td>
                  <td class="text-center" style="white-space:nowrap;">${tglMasukCell}</td>
                  <td>${pengirimCell}</td>
                  <td class="group-divider-end">${isiMasukCell}</td>
                  <td>${nomorKeluarCell}</td>
                  <td class="text-center" style="white-space:nowrap;">${tglKeluarCell}</td>
                  <td>${tujuanKeluarCell}</td>
                  <td class="group-divider-end">${isiKeluarCell}</td>
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
                                aria-label="Lihat detail agenda ${ariaRowTarget}">
                          <i class="fa-regular fa-eye" aria-hidden="true"></i>
                        </button>
                        <button type="button"
                                class="btn-row-action"
                                data-row-action="edit"
                                data-id="${esc(row.id)}"
                                title="Edit Agenda"
                                aria-label="Edit agenda ${ariaRowTarget}">
                          <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
                        </button>
                        <button type="button"
                                class="btn-row-action action-delete"
                                data-row-action="delete"
                                data-id="${esc(row.id)}"
                                title="Hapus Agenda"
                                aria-label="Hapus agenda ${ariaRowTarget}">
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
              ? 'Menampilkan <strong>0</strong> dari <strong>0</strong> agenda surat'
              : `Menampilkan <strong>${state.startEntry}&ndash;${state.endEntry}</strong> dari <strong>${state.totalItems}</strong> agenda surat${
                  state.hasActiveQuery ? ` (disaring dari total ${state.totalAllItems} agenda)` : ''
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
        const d = String(row.tanggalAgenda || '');
        if (!d) return false;
        if (startVal && d < startVal) return false;
        if (endVal && d > endVal) return false;
        return true;
      });
    }

    function handleResetAllFilters() {
      if (searchInput) searchInput.value = '';
      clearSearchBtn?.classList.remove('visible');
      if (filterJenisAgendaSelect) filterJenisAgendaSelect.value = 'ALL';
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

    filterJenisAgendaSelect?.addEventListener('change', () => {
      const val = filterJenisAgendaSelect.value;
      if (val === 'ALL') {
        tableCtrl.setFilter('jenisPencatatan', null);
      } else {
        tableCtrl.setFilter('jenisPencatatan', (_v, row) => resolveJenisPencatatan(row) === val);
      }
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
      setSelectedJenisRadio('MASUK');
      syncFormSectionsByJenis();
      hideFormBanner();

      if (formModalTitle) {
        formModalTitle.innerHTML =
          '<i class="fa-solid fa-plus-circle text-simdes-primary me-2" aria-hidden="true"></i>Tambah Agenda Surat';
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML =
          '<i class="fa-solid fa-floppy-disk" aria-hidden="true"></i><span>Simpan Agenda</span>';
      }

      window.bootstrap?.Modal.getOrCreateInstance(formModalEl).show();
      formModalEl.addEventListener('shown.bs.modal', () => fTanggalAgenda?.focus(), { once: true });
    }

    function openEditModal(id) {
      const item = records.find((r) => r.id === id);
      if (!item || !formEl || !formModalEl) return;

      formEl.reset();
      hideFormBanner();

      const jenis = resolveJenisPencatatan(item);
      fId.value = item.id;
      setSelectedJenisRadio(jenis);
      syncFormSectionsByJenis();

      fTanggalAgenda.value = item.tanggalAgenda || '';
      fNomorMasuk.value = item.nomorMasuk || '';
      fTanggalMasuk.value = item.tanggalMasuk || '';
      fPengirim.value = item.pengirim || '';
      fIsiMasuk.value = item.isiMasuk || '';

      fNomorKeluar.value = item.nomorKeluar || '';
      fTanggalKeluar.value = item.tanggalKeluar || '';
      fTujuanKeluar.value = item.tujuanKeluar || '';
      fIsiKeluar.value = item.isiKeluar || '';

      fKeterangan.value = item.keterangan || '';

      if (formModalTitle) {
        formModalTitle.innerHTML =
          '<i class="fa-regular fa-pen-to-square text-simdes-primary me-2" aria-hidden="true"></i>Edit Agenda Surat';
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML =
          '<i class="fa-solid fa-check" aria-hidden="true"></i><span>Simpan Perubahan</span>';
      }

      window.bootstrap?.Modal.getOrCreateInstance(formModalEl).show();
      formModalEl.addEventListener('shown.bs.modal', () => fTanggalAgenda?.focus(), { once: true });
    }

    function openDetailModal(id) {
      const item = records.find((r) => r.id === id);
      if (!item || !detailModalEl) return;
      activeDetailId = item.id;

      const jenis = resolveJenisPencatatan(item);
      const showMasuk = jenis === 'MASUK' || jenis === 'KEDUANYA';
      const showKeluar = jenis === 'KELUAR' || jenis === 'KEDUANYA';

      const setText = (elId, val) => {
        const el = document.getElementById(elId);
        if (el) el.textContent = val;
      };

      setText('detAgdTanggalAgenda', item.tanggalAgenda ? formatDate(item.tanggalAgenda) : '-');
      if (detAgdJenisBadge) {
        detAgdJenisBadge.className = getJenisBadgeClass(jenis);
        detAgdJenisBadge.textContent = getJenisLabel(jenis);
      }

      if (detSectionSuratMasuk) {
        detSectionSuratMasuk.hidden = !showMasuk;
      }
      if (showMasuk) {
        setText('detAgdNomorMasuk', item.nomorMasuk || '-');
        setText('detAgdTanggalMasuk', item.tanggalMasuk ? formatDate(item.tanggalMasuk) : '-');
        setText('detAgdPengirim', item.pengirim || '-');
        setText('detAgdIsiMasuk', item.isiMasuk || '-');
      }

      if (detSectionSuratKeluar) {
        detSectionSuratKeluar.hidden = !showKeluar;
      }
      if (showKeluar) {
        setText('detAgdNomorKeluar', item.nomorKeluar || '-');
        setText('detAgdTanggalKeluar', item.tanggalKeluar ? formatDate(item.tanggalKeluar) : '-');
        setText('detAgdTujuanKeluar', item.tujuanKeluar || '-');
        setText('detAgdIsiKeluar', item.isiKeluar || '-');
      }

      setText('detAgdKeterangan', item.keterangan || 'Tidak ada keterangan tambahan');

      window.bootstrap?.Modal.getOrCreateInstance(detailModalEl).show();
    }

    function openDeleteModal(id) {
      const item = records.find((r) => r.id === id);
      if (!item || !deleteModalEl) return;
      pendingDeleteId = item.id;

      const jenis = resolveJenisPencatatan(item);
      const titleParts = [];
      if (item.nomorMasuk) titleParts.push(`Masuk: ${item.nomorMasuk}`);
      if (item.nomorKeluar) titleParts.push(`Keluar: ${item.nomorKeluar}`);

      if (deleteTargetNameEl) {
        deleteTargetNameEl.textContent = titleParts.join(' • ') || getJenisLabel(jenis);
      }
      if (deleteTargetMetaEl) {
        const tgl = item.tanggalAgenda ? formatDate(item.tanggalAgenda) : '-';
        deleteTargetMetaEl.textContent = `${getJenisLabel(jenis)} • Tanggal Agenda: ${tgl}`;
      }

      window.bootstrap?.Modal.getOrCreateInstance(deleteModalEl).show();
    }

    // Bind Jenis Pencatatan radio toggle
    formEl?.querySelectorAll('input[type="radio"][name="jenisPencatatan"]').forEach((radio) => {
      radio.addEventListener('change', () => {
        hideFormBanner();
        syncFormSectionsByJenis();
      });
    });

    if (formEl && window.SimdesForms?.InteractionValidator) {
      syncFormSectionsByJenis();
      window.SimdesForms.InteractionValidator.bindForm(formEl);

      formEl.addEventListener('submit', (e) => {
        e.preventDefault();
        hideFormBanner();
        syncFormSectionsByJenis();

        const validBase = window.SimdesForms.InteractionValidator.validateAll(formEl);
        if (!validBase) {
          showFormBanner('Mohon lengkapi kolom wajib sesuai jenis pencatatan agenda yang dipilih.');
          return;
        }

        const jenis = getSelectedJenisRadio();
        const includeMasuk = jenis === 'MASUK' || jenis === 'KEDUANYA';
        const includeKeluar = jenis === 'KELUAR' || jenis === 'KEDUANYA';
        const existingId = fId.value.trim();

        const payload = {
          id: existingId || `agd-${Date.now()}`,
          jenisPencatatan: jenis,
          tanggalAgenda: fTanggalAgenda.value,
          nomorMasuk: includeMasuk ? fNomorMasuk.value.trim() : '',
          tanggalMasuk: includeMasuk ? fTanggalMasuk.value : '',
          pengirim: includeMasuk ? fPengirim.value.trim() : '',
          isiMasuk: includeMasuk ? fIsiMasuk.value.trim() : '',
          nomorKeluar: includeKeluar ? fNomorKeluar.value.trim() : '',
          tanggalKeluar: includeKeluar ? fTanggalKeluar.value : '',
          tujuanKeluar: includeKeluar ? fTujuanKeluar.value.trim() : '',
          isiKeluar: includeKeluar ? fIsiKeluar.value.trim() : '',
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
          title: existingId ? 'Agenda Surat Diperbarui' : 'Agenda Surat Ditambahkan',
          message: `Pencatatan ${getJenisLabel(jenis)} berhasil disimpan pada Buku Agenda.`,
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
        title: 'Agenda Surat Dihapus',
        message: target
          ? `Catatan agenda (${getJenisLabel(resolveJenisPencatatan(target))}) telah dihapus.`
          : 'Data agenda berhasil dihapus.',
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
        message: '8 data contoh simulasi Buku Agenda berhasil dimuat ulang.',
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

  document.addEventListener('DOMContentLoaded', initBukuAgenda);
})();

