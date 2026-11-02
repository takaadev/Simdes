/* ==========================================================================
   SIMDES DESA TIARO — Buku Aparat Pemerintah Desa Controller (Kode: A.4)
   File: assets/js/buku-aparat-pemerintah-desa.js
   Features:
   - Exact 13-column official register rendering (combined Tempat & Tgl Lahir,
     Nomor & Tanggal Keputusan Pengangkatan, Nomor & Tanggal Keputusan Pemberhentian)
   - CRUD via large modal form (5 sections, 15 inputs) + localStorage persistence
   - Optional Keputusan Pemberhentian for active village officials
   - Search (nama, NIAP, NIP, jabatan), Filter Jenis Kelamin & Jabatan, Reset
   - Column sorting, pagination, auto row numbering, structured detail modal,
     delete confirmation modal, sample data reset, and landscape print mode
   ========================================================================== */

(() => {
  'use strict';

  const STORAGE_KEY = 'simdes_tiaro_buku_aparat_pemerintah_desa_v1';

  /**
   * Data Dummy Fiktif (8 Contoh Aparat Pemerintah Desa)
   * Seluruh nama, NIAP, NIP, dan nomor keputusan adalah data simulasi fiktif,
   * bukan data pribadi aparat Pemerintah Desa Tiaro yang sebenarnya.
   */
  const SEED_DATA = [
    {
      id: 'apr-sim-001',
      nama: 'Drs. Hendra Pratama (Data Contoh)',
      niap: 'SIM-7401-2021-001',
      nip: '-',
      jenisKelamin: 'Laki-laki',
      tempatLahir: 'Kendari',
      tanggalLahir: '1976-04-18',
      agama: 'Islam',
      pangkatGolongan: '-',
      jabatan: 'Kepala Desa',
      pendidikanTerakhir: 'D-IV/S-1',
      nomorPengangkatan: '141.1/105/KEP-BUP-CONTOH/2021',
      tanggalPengangkatan: '2021-11-15',
      nomorPemberhentian: '',
      tanggalPemberhentian: '',
      keterangan: 'Data contoh fiktif — Aktif menjabat periode simulasi'
    },
    {
      id: 'apr-sim-002',
      nama: 'Arman Saputra, S.AP (Data Contoh)',
      niap: 'SIM-7401-2019-002',
      nip: '19850812 201101 1 099',
      jenisKelamin: 'Laki-laki',
      tempatLahir: 'Bau-Bau',
      tanggalLahir: '1985-08-12',
      agama: 'Islam',
      pangkatGolongan: 'Penata Muda Tk. I (III/b)',
      jabatan: 'Sekretaris Desa',
      pendidikanTerakhir: 'D-IV/S-1',
      nomorPengangkatan: '01/KEP-SIMULASI/I/2019',
      tanggalPengangkatan: '2019-01-10',
      nomorPemberhentian: '',
      tanggalPemberhentian: '',
      keterangan: 'Data contoh fiktif — PNS diperbantukan (simulasi)'
    },
    {
      id: 'apr-sim-003',
      nama: 'Nadia Kusuma Wardani, A.Md (Data Contoh)',
      niap: 'SIM-7401-2020-003',
      nip: '-',
      jenisKelamin: 'Perempuan',
      tempatLahir: 'Kolaka',
      tanggalLahir: '1992-03-24',
      agama: 'Islam',
      pangkatGolongan: '-',
      jabatan: 'Kaur Tata Usaha dan Umum',
      pendidikanTerakhir: 'D-III',
      nomorPengangkatan: '02/KEP-SIMULASI/I/2020',
      tanggalPengangkatan: '2020-01-14',
      nomorPemberhentian: '',
      tanggalPemberhentian: '',
      keterangan: 'Data contoh fiktif simulasi UI'
    },
    {
      id: 'apr-sim-004',
      nama: 'Rina Puspitasari, S.E. (Data Contoh)',
      niap: 'SIM-7401-2020-004',
      nip: '-',
      jenisKelamin: 'Perempuan',
      tempatLahir: 'Raha',
      tanggalLahir: '1990-11-06',
      agama: 'Islam',
      pangkatGolongan: '-',
      jabatan: 'Kaur Keuangan',
      pendidikanTerakhir: 'D-IV/S-1',
      nomorPengangkatan: '03/KEP-SIMULASI/I/2020',
      tanggalPengangkatan: '2020-01-14',
      nomorPemberhentian: '',
      tanggalPemberhentian: '',
      keterangan: 'Data contoh fiktif simulasi UI'
    },
    {
      id: 'apr-sim-005',
      nama: 'Yohanes Daud Prakoso, S.T. (Data Contoh)',
      niap: 'SIM-7401-2021-005',
      nip: '-',
      jenisKelamin: 'Laki-laki',
      tempatLahir: 'Makassar',
      tanggalLahir: '1989-07-19',
      agama: 'Kristen',
      pangkatGolongan: '-',
      jabatan: 'Kaur Perencanaan',
      pendidikanTerakhir: 'D-IV/S-1',
      nomorPengangkatan: '04/KEP-SIMULASI/II/2021',
      tanggalPengangkatan: '2021-02-08',
      nomorPemberhentian: '',
      tanggalPemberhentian: '',
      keterangan: 'Data contoh fiktif simulasi UI'
    },
    {
      id: 'apr-sim-006',
      nama: 'Fajar Ramadhan, S.IP (Data Contoh)',
      niap: 'SIM-7401-2021-006',
      nip: '-',
      jenisKelamin: 'Laki-laki',
      tempatLahir: 'Unaaha',
      tanggalLahir: '1991-09-02',
      agama: 'Islam',
      pangkatGolongan: '-',
      jabatan: 'Kasi Pemerintahan',
      pendidikanTerakhir: 'D-IV/S-1',
      nomorPengangkatan: '05/KEP-SIMULASI/II/2021',
      tanggalPengangkatan: '2021-02-08',
      nomorPemberhentian: '',
      tanggalPemberhentian: '',
      keterangan: 'Data contoh fiktif simulasi UI'
    },
    {
      id: 'apr-sim-007',
      nama: 'Dewi Sartika Lestari (Data Contoh)',
      niap: 'SIM-7401-2022-007',
      nip: '-',
      jenisKelamin: 'Perempuan',
      tempatLahir: 'Pasarwajo',
      tanggalLahir: '1994-05-29',
      agama: 'Islam',
      pangkatGolongan: '-',
      jabatan: 'Kasi Pelayanan',
      pendidikanTerakhir: 'SMA/SMK/Sederajat',
      nomorPengangkatan: '02/KEP-SIMULASI/III/2022',
      tanggalPengangkatan: '2022-03-11',
      nomorPemberhentian: '',
      tanggalPemberhentian: '',
      keterangan: 'Data contoh fiktif simulasi UI'
    },
    {
      id: 'apr-sim-008',
      nama: 'Ruslan Efendi (Data Contoh)',
      niap: 'SIM-7401-2016-008',
      nip: '-',
      jenisKelamin: 'Laki-laki',
      tempatLahir: 'Wangi-Wangi',
      tanggalLahir: '1965-12-10',
      agama: 'Islam',
      pangkatGolongan: '-',
      jabatan: 'Kepala Wilayah Dusun I',
      pendidikanTerakhir: 'SMA/SMK/Sederajat',
      nomorPengangkatan: '06/KEP-SIMULASI/I/2016',
      tanggalPengangkatan: '2016-01-18',
      nomorPemberhentian: '09/KEP-SIMULASI/XII/2025',
      tanggalPemberhentian: '2025-12-15',
      keterangan: 'Data contoh fiktif — Purna tugas batas usia (simulasi)'
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

  function isDismissed(record) {
    return Boolean(
      String(record.nomorPemberhentian || '').trim() ||
      String(record.tanggalPemberhentian || '').trim()
    );
  }

  function normalizeDashField(val) {
    const trimmed = String(val ?? '').trim();
    return trimmed && trimmed !== '-' ? trimmed : '';
  }

  function initBukuAparatPage() {
    const pageRoot = document.getElementById('bukuAparatPageRoot');
    if (!pageRoot) return;

    const esc = window.SimdesApp ? window.SimdesApp.escapeHtml : (s) => String(s ?? '');
    const formatDate = window.SimdesTable ? window.SimdesTable.formatDateIndo : (s) => s;

    let records = loadRecords();
    let activeDetailId = null;
    let pendingDeleteId = null;

    // DOM References
    const tbody = document.getElementById('aparatTableBody');
    const searchInput = document.getElementById('tableSearchInput');
    const clearSearchBtn = document.getElementById('clearSearchInputBtn');
    const filterGenderSelect = document.getElementById('filterGenderSelect');
    const filterJabatanSelect = document.getElementById('filterJabatanSelect');
    const pageSizeSelect = document.getElementById('pageSizeSelect');
    const resetFilterBtn = document.getElementById('resetTableFilterBtn');

    const paginationInfo = document.getElementById('tablePaginationInfo');
    const paginationList = document.getElementById('tablePaginationList');

    // KPI Summary Elements
    const statTotalEl = document.getElementById('statTotalAparat');
    const statAktifEl = document.getElementById('statAparatAktif');
    const statGenderSplitEl = document.getElementById('statGenderSplit');
    const statBerhentiEl = document.getElementById('statAparatBerhenti');

    // Top Buttons
    const addBtn = document.getElementById('btnTambahAparat');
    const printBtn = document.getElementById('btnCetakAparat');
    const restoreSeedBtn = document.getElementById('btnResetSampleAparat');

    // Modals & Form Controls
    const formModalEl = document.getElementById('aparatFormModal');
    const detailModalEl = document.getElementById('aparatDetailModal');
    const deleteModalEl = document.getElementById('aparatDeleteModal');

    const formEl = document.getElementById('aparatForm');
    const formModalTitle = document.getElementById('aparatFormModalLabel');
    const formSubmitBtn = document.getElementById('aparatFormSubmitBtn');
    const formAlertBox = document.getElementById('aparatFormAlert');
    const formAlertText = document.getElementById('aparatFormAlertText');

    // 15 Form Controls across Sections A–E
    const inputId = document.getElementById('inputRecordId');
    const inputNama = document.getElementById('inputNama');
    const inputNiap = document.getElementById('inputNiap');
    const inputNip = document.getElementById('inputNip');
    const radioJkLaki = document.getElementById('jkLakiLaki');
    const radioJkPerempuan = document.getElementById('jkPerempuan');
    const inputTempatLahir = document.getElementById('inputTempatLahir');
    const inputTanggalLahir = document.getElementById('inputTanggalLahir');
    const inputAgama = document.getElementById('inputAgama');
    const inputPendidikan = document.getElementById('inputPendidikan');
    const inputPangkatGolongan = document.getElementById('inputPangkatGolongan');
    const inputJabatan = document.getElementById('inputJabatan');
    const inputNomorPengangkatan = document.getElementById('inputNomorPengangkatan');
    const inputTanggalPengangkatan = document.getElementById('inputTanggalPengangkatan');
    const inputNomorPemberhentian = document.getElementById('inputNomorPemberhentian');
    const inputTanggalPemberhentian = document.getElementById('inputTanggalPemberhentian');
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

    function getSelectedGender() {
      if (radioJkLaki?.checked) return 'Laki-laki';
      if (radioJkPerempuan?.checked) return 'Perempuan';
      return '';
    }

    function setSelectedGender(val) {
      if (radioJkLaki) radioJkLaki.checked = val === 'Laki-laki';
      if (radioJkPerempuan) radioJkPerempuan.checked = val === 'Perempuan';
    }

    function updateSummaryStats() {
      const total = records.length;
      const stopped = records.filter(isDismissed).length;
      const active = total - stopped;
      const countLaki = records.filter((r) => r.jenisKelamin === 'Laki-laki').length;
      const countPerempuan = records.filter((r) => r.jenisKelamin === 'Perempuan').length;

      if (statTotalEl) statTotalEl.textContent = total.toLocaleString('id-ID');
      if (statAktifEl) statAktifEl.textContent = active.toLocaleString('id-ID');
      if (statGenderSplitEl) statGenderSplitEl.textContent = `${countLaki} L / ${countPerempuan} P`;
      if (statBerhentiEl) statBerhentiEl.textContent = stopped.toLocaleString('id-ID');
    }

    function syncJabatanFilterOptions() {
      if (!filterJabatanSelect) return;
      const currentVal = filterJabatanSelect.value || 'ALL';
      const jabatans = Array.from(
        new Set(records.map((r) => String(r.jabatan || '').trim()).filter(Boolean))
      ).sort((a, b) => a.localeCompare(b, 'id', { sensitivity: 'base' }));

      filterJabatanSelect.innerHTML = `
        <option value="ALL">Semua Jabatan</option>
        ${jabatans.map((jb) => `<option value="${esc(jb)}">${esc(jb)}</option>`).join('')}
      `;

      filterJabatanSelect.value = jabatans.includes(currentVal) ? currentVal : 'ALL';
    }

    function buildTempatTglLahirCell(row) {
      const tempat = String(row.tempatLahir || '').trim();
      const tgl = row.tanggalLahir ? formatDate(row.tanggalLahir) : '';
      if (!tempat && !tgl) return '-';
      if (tempat && tgl) {
        return `
          <div>
            <div class="fw-semibold" style="color:var(--simdes-text);">${esc(tempat)}</div>
            <div class="doc-date-line mt-1">
              <i class="fa-regular fa-calendar" aria-hidden="true"></i>
              <span>${esc(tgl)}</span>
            </div>
          </div>
        `;
      }
      return esc(tempat || tgl);
    }

    function buildKeputusanPengangkatanCell(row) {
      const nomor = normalizeDashField(row.nomorPengangkatan);
      const tgl = String(row.tanggalPengangkatan || '').trim();
      if (!nomor && !tgl) return '-';

      return `
        <div>
          <span class="doc-num-badge">${nomor ? esc(nomor) : 'Nomor belum diisi'}</span>
          <div class="doc-date-line">
            <i class="fa-regular fa-calendar-check" aria-hidden="true"></i>
            <span>${tgl ? esc(formatDate(tgl)) : 'Tanggal belum diisi'}</span>
          </div>
        </div>
      `;
    }

    function buildKeputusanPemberhentianCell(row) {
      const nomor = normalizeDashField(row.nomorPemberhentian);
      const tgl = String(row.tanggalPemberhentian || '').trim();

      if (!nomor && !tgl) {
        return `
          <span class="badge-simdes badge-simdes-success no-print">Masih Aktif</span>
          <span class="print-only-inline">-</span>
        `;
      }

      return `
        <div>
          <span class="doc-num-badge report-badge">${nomor ? esc(nomor) : 'Nomor belum diisi'}</span>
          <div class="doc-date-line">
            <i class="fa-regular fa-calendar" aria-hidden="true"></i>
            <span>${tgl ? esc(formatDate(tgl)) : 'Tanggal belum diisi'}</span>
          </div>
        </div>
      `;
    }

    function renderRowsHtml(rowsList, startIndex = 0) {
      if (rowsList.length === 0) {
        return `
          <tr>
            <td colspan="13">
              <div class="table-empty-state">
                <div class="table-empty-icon">
                  <i class="fa-solid fa-user-tie" aria-hidden="true"></i>
                </div>
                <div class="fw-bold mb-1" style="color:var(--simdes-text); font-size:0.95rem;">
                  Data Aparat Pemerintah Desa Tidak Ditemukan
                </div>
                <p class="mb-3" style="font-size:0.83rem; max-width:46ch; margin-inline:auto;">
                  Tidak ada data aparat yang sesuai dengan pencarian nama/NIAP/NIP/jabatan atau filter yang dipilih.
                </p>
                <div class="d-inline-flex flex-wrap justify-content-center gap-2 no-print">
                  <button type="button" class="btn-simdes-outline" data-empty-action="reset-filter">
                    <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                    <span>Reset Filter</span>
                  </button>
                  <button type="button" class="btn-simdes-primary" data-empty-action="add-new">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i>
                    <span>Tambah Aparat</span>
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
          const niapText = normalizeDashField(row.niap) ? esc(row.niap) : '-';
          const nipText = normalizeDashField(row.nip) ? esc(row.nip) : '-';
          const pangkatText = normalizeDashField(row.pangkatGolongan) ? esc(row.pangkatGolongan) : '-';
          const ketText = normalizeDashField(row.keterangan) ? esc(row.keterangan) : '-';

          const jkBadgeCls =
            row.jenisKelamin === 'Perempuan'
              ? 'badge-simdes badge-simdes-accent'
              : 'badge-simdes badge-simdes-primary';

          return `
            <tr data-row-id="${esc(row.id)}">
              <td class="col-no-urut sticky-col-first">${nomorUrut}</td>
              <td>
                <button type="button" class="tentang-title-btn" data-row-action="detail" data-id="${esc(row.id)}">
                  ${esc(row.nama)}
                </button>
              </td>
              <td class="tabular-nums fw-semibold" style="font-size:0.815rem;">${niapText}</td>
              <td class="tabular-nums text-simdes-muted" style="font-size:0.815rem;">${nipText}</td>
              <td><span class="${jkBadgeCls}">${esc(row.jenisKelamin || '-')}</span></td>
              <td>${buildTempatTglLahirCell(row)}</td>
              <td>${esc(row.agama || '-')}</td>
              <td>${pangkatText}</td>
              <td><span class="fw-semibold" style="color:var(--simdes-deep-green);">${esc(row.jabatan || '-')}</span></td>
              <td><span class="badge-simdes badge-simdes-neutral">${esc(row.pendidikanTerakhir || '-')}</span></td>
              <td>${buildKeputusanPengangkatanCell(row)}</td>
              <td>${buildKeputusanPemberhentianCell(row)}</td>
              <td>
                <div class="ket-cell-content">
                  <span class="ket-text">${ketText}</span>
                  <div class="row-action-group no-print" role="group" aria-label="Aksi baris aparat nomor urut ${nomorUrut}">
                    <button type="button" class="btn-row-action" data-row-action="detail" data-id="${esc(row.id)}" title="Lihat Detail" aria-label="Lihat detail ${esc(row.nama)}">
                      <i class="fa-regular fa-eye" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn-row-action" data-row-action="edit" data-id="${esc(row.id)}" title="Edit Aparat" aria-label="Edit ${esc(row.nama)}">
                      <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn-row-action action-delete" data-row-action="delete" data-id="${esc(row.id)}" title="Hapus Aparat" aria-label="Hapus ${esc(row.nama)}">
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
        'nama',
        'niap',
        'nip',
        'jabatan',
        'pangkatGolongan',
        'pendidikanTerakhir',
        'nomorPengangkatan'
      ],
      defaultSortKey: null,
      defaultSortDirection: 'asc',
      comparators: {
        nama: (a, b) =>
          String(a.nama || '').localeCompare(String(b.nama || ''), 'id', { sensitivity: 'base' }),
        niap: (a, b) =>
          String(a.niap || '').localeCompare(String(b.niap || ''), 'id', { numeric: true, sensitivity: 'base' }),
        tanggalLahir: (a, b) =>
          String(a.tanggalLahir || '').localeCompare(String(b.tanggalLahir || '')),
        jabatan: (a, b) =>
          String(a.jabatan || '').localeCompare(String(b.jabatan || ''), 'id', { sensitivity: 'base' }),
        pengangkatan: (a, b) => {
          const cmpDate = String(a.tanggalPengangkatan || '').localeCompare(String(b.tanggalPengangkatan || ''));
          if (cmpDate !== 0) return cmpDate;
          return String(a.nomorPengangkatan || '').localeCompare(String(b.nomorPengangkatan || ''), 'id', { numeric: true });
        }
      },
      onRender: (state) => {
        if (tbody) {
          tbody.innerHTML = renderRowsHtml(state.rows, state.startIndex);
        }

        if (paginationInfo) {
          if (state.totalItems === 0) {
            paginationInfo.textContent = 'Menampilkan 0 data aparat';
          } else {
            paginationInfo.innerHTML = `Menampilkan <strong>${state.startEntry}&ndash;${state.endEntry}</strong> dari <strong>${state.totalItems}</strong> data aparat`;
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
      setSelectedGender('');

      if (formModalTitle) {
        formModalTitle.innerHTML = `<i class="fa-solid fa-plus-circle text-simdes-primary me-2" aria-hidden="true"></i>Tambah Aparat Pemerintah Desa`;
      }
      if (formSubmitBtn) {
        formSubmitBtn.innerHTML = `<i class="fa-solid fa-floppy-disk" aria-hidden="true"></i><span>Simpan Data Aparat</span>`;
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
      if (inputNama) inputNama.value = record.nama || '';
      if (inputNiap) inputNiap.value = normalizeDashField(record.niap);
      if (inputNip) inputNip.value = normalizeDashField(record.nip);
      setSelectedGender(record.jenisKelamin || '');
      if (inputTempatLahir) inputTempatLahir.value = record.tempatLahir || '';
      if (inputTanggalLahir) inputTanggalLahir.value = record.tanggalLahir || '';
      if (inputAgama) inputAgama.value = record.agama || '';
      if (inputPendidikan) inputPendidikan.value = record.pendidikanTerakhir || '';
      if (inputPangkatGolongan) inputPangkatGolongan.value = normalizeDashField(record.pangkatGolongan);
      if (inputJabatan) inputJabatan.value = record.jabatan || '';
      if (inputNomorPengangkatan) inputNomorPengangkatan.value = normalizeDashField(record.nomorPengangkatan);
      if (inputTanggalPengangkatan) inputTanggalPengangkatan.value = record.tanggalPengangkatan || '';
      if (inputNomorPemberhentian) inputNomorPemberhentian.value = normalizeDashField(record.nomorPemberhentian);
      if (inputTanggalPemberhentian) inputTanggalPemberhentian.value = record.tanggalPemberhentian || '';
      if (inputKeterangan) inputKeterangan.value = normalizeDashField(record.keterangan);

      if (formModalTitle) {
        formModalTitle.innerHTML = `<i class="fa-regular fa-pen-to-square text-simdes-primary me-2" aria-hidden="true"></i>Edit Data Aparat Pemerintah Desa`;
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

      const stopped = isDismissed(record);
      const statusBadge = document.getElementById('detStatusBadge');
      if (statusBadge) {
        statusBadge.className = stopped
          ? 'badge-simdes badge-simdes-warning'
          : 'badge-simdes badge-simdes-success';
        statusBadge.textContent = stopped ? 'Purna Tugas / Diberhentikan' : 'Masih Aktif Menjabat';
      }

      const tempat = String(record.tempatLahir || '').trim();
      const tglLahirStr = record.tanggalLahir ? formatDate(record.tanggalLahir) : '';
      const ttlCombined =
        tempat && tglLahirStr ? `${tempat}, ${tglLahirStr}` : tempat || tglLahirStr || '-';

      setTxt('detNama', record.nama || '-');
      setTxt('detJabatanHeader', record.jabatan || '-');
      setTxt('detNiap', normalizeDashField(record.niap) || '-');
      setTxt('detNip', normalizeDashField(record.nip) || 'Tidak ada / Non-PNS (-)');
      setTxt('detJenisKelamin', record.jenisKelamin || '-');
      setTxt('detTempatTglLahir', ttlCombined);
      setTxt('detAgama', record.agama || '-');
      setTxt('detPendidikan', record.pendidikanTerakhir || '-');
      setTxt('detPangkatGolongan', normalizeDashField(record.pangkatGolongan) || '-');
      setTxt('detJabatan', record.jabatan || '-');

      setTxt('detNomorPengangkatan', normalizeDashField(record.nomorPengangkatan) || '-');
      setTxt(
        'detTanggalPengangkatan',
        record.tanggalPengangkatan ? formatDate(record.tanggalPengangkatan) : '-'
      );

      setTxt(
        'detNomorPemberhentian',
        normalizeDashField(record.nomorPemberhentian) || 'Belum ada (Aparat masih aktif)'
      );
      setTxt(
        'detTanggalPemberhentian',
        record.tanggalPemberhentian ? formatDate(record.tanggalPemberhentian) : '-'
      );

      setTxt('detKeterangan', normalizeDashField(record.keterangan) || '-');

      if (detailModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(detailModalEl).show();
      }
    }

    function openDeleteModal(id) {
      const record = records.find((r) => r.id === id);
      if (!record) return;
      pendingDeleteId = record.id;

      const namaEl = document.getElementById('deleteTargetNama');
      const jabatanEl = document.getElementById('deleteTargetJabatan');
      const niapEl = document.getElementById('deleteTargetNiap');

      if (namaEl) namaEl.textContent = record.nama;
      if (jabatanEl) jabatanEl.textContent = record.jabatan;
      if (niapEl) niapEl.textContent = `NIAP: ${normalizeDashField(record.niap) || '-'}`;

      if (deleteModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getOrCreateInstance(deleteModalEl).show();
      }
    }

    function validateLogicalDates() {
      inputTanggalPengangkatan?.setCustomValidity('');
      inputTanggalPemberhentian?.setCustomValidity('');

      const tglLahir = inputTanggalLahir?.value || '';
      const tglAngkat = inputTanggalPengangkatan?.value || '';
      const tglHenti = inputTanggalPemberhentian?.value || '';

      if (tglLahir && tglAngkat && tglAngkat <= tglLahir) {
        const msg = 'Tanggal keputusan pengangkatan harus setelah tanggal lahir.';
        window.SimdesForms?.InteractionValidator?.syncFieldState(inputTanggalPengangkatan, msg);
        showFormBanner(msg);
        inputTanggalPengangkatan?.focus();
        return false;
      }

      if (tglAngkat && tglHenti && tglHenti < tglAngkat) {
        const msg = 'Tanggal keputusan pemberhentian tidak boleh mendahului tanggal pengangkatan.';
        window.SimdesForms?.InteractionValidator?.syncFieldState(inputTanggalPemberhentian, msg);
        showFormBanner(msg);
        inputTanggalPemberhentian?.focus();
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
        showFormBanner('Mohon lengkapi kolom wajib yang ditandai bintang (*). Keputusan pemberhentian boleh dikosongkan apabila aparat masih aktif.');
        return;
      }

      if (!validateLogicalDates()) return;

      const editingId = inputId?.value.trim();
      const payload = {
        id: editingId || `apr-${Date.now()}`,
        nama: inputNama.value.trim(),
        niap: inputNiap.value.trim(),
        nip: inputNip?.value.trim() || '-',
        jenisKelamin: getSelectedGender(),
        tempatLahir: inputTempatLahir.value.trim(),
        tanggalLahir: inputTanggalLahir.value,
        agama: inputAgama.value.trim(),
        pangkatGolongan: inputPangkatGolongan?.value.trim() || '-',
        jabatan: inputJabatan.value.trim(),
        pendidikanTerakhir: inputPendidikan.value.trim(),
        nomorPengangkatan: inputNomorPengangkatan.value.trim(),
        tanggalPengangkatan: inputTanggalPengangkatan.value,
        nomorPemberhentian: inputNomorPemberhentian?.value.trim() || '',
        tanggalPemberhentian: inputTanggalPemberhentian?.value || '',
        keterangan: inputKeterangan?.value.trim() || ''
      };

      if (editingId) {
        records = records.map((r) => (r.id === editingId ? payload : r));
      } else {
        records = [...records, payload];
      }

      saveRecords(records);
      updateSummaryStats();
      syncJabatanFilterOptions();
      tableCtrl.setData(records, Boolean(editingId));

      if (formModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getInstance(formModalEl)?.hide();
      }

      window.SimdesApp?.showToast({
        title: editingId ? 'Data Aparat Diperbarui' : 'Aparat Baru Ditambahkan',
        message: `Data "${payload.nama}" (${payload.jabatan}) berhasil disimpan.`,
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
    document.getElementById('btnEditAparatFromDetail')?.addEventListener('click', () => {
      if (!activeDetailId) return;
      const id = activeDetailId;
      if (detailModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getInstance(detailModalEl)?.hide();
      }
      window.setTimeout(() => openEditModal(id), 180);
    });

    // Confirm Delete Handler
    document.getElementById('confirmDeleteAparatBtn')?.addEventListener('click', () => {
      if (!pendingDeleteId) return;
      const target = records.find((r) => r.id === pendingDeleteId);
      records = records.filter((r) => r.id !== pendingDeleteId);
      pendingDeleteId = null;

      saveRecords(records);
      updateSummaryStats();
      syncJabatanFilterOptions();
      tableCtrl.setData(records, true);

      if (deleteModalEl && window.bootstrap?.Modal) {
        window.bootstrap.Modal.getInstance(deleteModalEl)?.hide();
      }

      window.SimdesApp?.showToast({
        title: 'Data Aparat Dihapus',
        message: target
          ? `"${target.nama}" telah dihapus dari Buku Aparat Pemerintah Desa.`
          : 'Data aparat berhasil dihapus.',
        variant: 'warning'
      });
    });

    // Search & Filters
    function resetAllFilters() {
      if (searchInput) searchInput.value = '';
      if (filterGenderSelect) filterGenderSelect.value = 'ALL';
      if (filterJabatanSelect) filterJabatanSelect.value = 'ALL';
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

    filterGenderSelect?.addEventListener('change', () => {
      const val = filterGenderSelect.value;
      if (val === 'ALL') {
        tableCtrl.setFilter('jenisKelamin', null);
      } else {
        tableCtrl.setFilter('jenisKelamin', val);
      }
    });

    filterJabatanSelect?.addEventListener('change', () => {
      const val = filterJabatanSelect.value;
      if (val === 'ALL') {
        tableCtrl.setFilter('jabatan', null);
      } else {
        tableCtrl.setFilter('jabatan', val);
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
      syncJabatanFilterOptions();
      tableCtrl.setData(records, false);

      window.SimdesApp?.showToast({
        title: 'Data Contoh Dipulihkan',
        message: 'Buku Aparat Pemerintah Desa dikembalikan ke 8 data fiktif awal.',
        variant: 'primary'
      });
    });

    // Print Full Filtered Table
    const preparePrintView = () => {
      const state = tableCtrl.getState();
      if (tbody) {
        tbody.innerHTML = renderRowsHtml(state.allFilteredRows, 0);
      }
      const printMetaEl = document.getElementById('printAparatMeta');
      if (printMetaEl) {
        try {
          const nowStr = new Intl.DateTimeFormat('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
          }).format(new Date());
          printMetaEl.textContent = `Dicetak pada: ${nowStr} • Total: ${state.allFilteredRows.length} data aparat (Data Contoh Fiktif)`;
        } catch (_) {
          printMetaEl.textContent = `Total: ${state.allFilteredRows.length} data aparat (Data Contoh Fiktif)`;
        }
      }
    };

    window.addEventListener('beforeprint', preparePrintView);
    window.addEventListener('afterprint', () => tableCtrl.render());

    printBtn?.addEventListener('click', () => {
      preparePrintView();
      window.print();
    });

    // Initial Render
    updateSummaryStats();
    syncJabatanFilterOptions();
    tableCtrl.render();
  }

  document.addEventListener('DOMContentLoaded', initBukuAparatPage);
})();

