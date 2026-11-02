/* ==========================================================================
   SIMDES DESA TIARO — Ledger Book Engine (ledger-common.js)
   Mesin bersama untuk halaman buku register berbasis tahun (B.4, B.5):
   penyimpanan localStorage, filter tahun, pencarian, filter kategori, sorting,
   pagination, ringkasan, CRUD (modal), detail, hapus, reset, dan cetak.

   Setiap halaman hanya menyediakan konfigurasi: seed, sanitasi, render baris
   (struktur kolom sumber), form (baca/isi/validasi), detail, dan ringkasan.
   ========================================================================== */

(() => {
  'use strict';

  const escFallback = (val) =>
    String(val ?? '')
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;');

  const esc = (val) => (window.SimdesApp?.escapeHtml ? window.SimdesApp.escapeHtml(val) : escFallback(val));
  const DASH = '<span class="text-simdes-muted">&mdash;</span>';
  const dashOr = (val) => (String(val ?? '').trim() ? esc(val) : DASH);

  function formatDate(iso) {
    if (!iso) return '';
    return window.SimdesTable?.formatDateIndo ? window.SimdesTable.formatDateIndo(iso) : iso;
  }

  function yearOfDate(iso) {
    const m = String(iso || '').match(/^(\d{4})-\d{2}-\d{2}$/);
    return m ? m[1] : '';
  }

  /** Umur penuh (tahun) pada tanggal acuan; kosong jika tanggal tidak valid. */
  function calcAge(birthIso, refIso) {
    const b = String(birthIso || '').match(/^(\d{4})-(\d{2})-(\d{2})$/);
    const r = String(refIso || '').match(/^(\d{4})-(\d{2})-(\d{2})$/);
    if (!b || !r) return '';
    let age = Number(r[1]) - Number(b[1]);
    if (Number(r[2]) < Number(b[2]) || (Number(r[2]) === Number(b[2]) && Number(r[3]) < Number(b[3]))) age -= 1;
    return age >= 0 ? age : '';
  }

  function todayIso() {
    const d = new Date();
    const pad = (n) => String(n).padStart(2, '0');
    return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
  }

  function fillSelect(selectEl, options, placeholder = null) {
    if (!selectEl) return;
    const head = placeholder === null ? '' : `<option value="">${esc(placeholder)}</option>`;
    selectEl.innerHTML = head + options.map((o) => `<option value="${esc(o)}">${esc(o)}</option>`).join('');
  }

  function getRadio(formEl, name, fallback = '') {
    const checked = formEl?.querySelector(`input[type="radio"][name="${name}"]:checked`);
    return checked ? checked.value : fallback;
  }

  function setRadio(formEl, name, value) {
    formEl?.querySelectorAll(`input[type="radio"][name="${name}"]`).forEach((r) => {
      r.checked = r.value === value;
    });
  }

  function rowActions(id, name, no) {
    const n = esc(name);
    return `
      <div class="row-action-group no-print" role="group" aria-label="Aksi baris ${esc(no)}">
        <button type="button" class="btn-row-action" data-row-action="detail" data-id="${esc(id)}" title="Lihat Detail" aria-label="Lihat detail ${n}"><i class="fa-regular fa-eye" aria-hidden="true"></i></button>
        <button type="button" class="btn-row-action" data-row-action="edit" data-id="${esc(id)}" title="Edit Data" aria-label="Edit data ${n}"><i class="fa-regular fa-pen-to-square" aria-hidden="true"></i></button>
        <button type="button" class="btn-row-action action-delete" data-row-action="delete" data-id="${esc(id)}" title="Hapus Data" aria-label="Hapus data ${n}"><i class="fa-regular fa-trash-can" aria-hidden="true"></i></button>
      </div>`;
  }

  /** Sel KET: teks keterangan + menu aksi (di luar struktur kolom resmi). */
  function ketCell(text, id, name, no) {
    return `<td>
      <div class="ket-cell-content">
        <div class="ket-text">${dashOr(text)}</div>
        ${rowActions(id, name, no)}
      </div>
    </td>`;
  }

  function emptyState({ colspan, title, text, canReset, addLabel }) {
    return `
      <tr>
        <td colspan="${colspan}">
          <div class="table-empty-state">
            <div class="table-empty-icon"><i class="fa-solid fa-folder-open" aria-hidden="true"></i></div>
            <div class="fw-bold mb-1" style="color:var(--simdes-text); font-size:0.95rem;">${esc(title)}</div>
            <p class="mb-3" style="font-size:0.83rem; max-width:48ch; margin-inline:auto;">${esc(text)}</p>
            <div class="d-inline-flex flex-wrap justify-content-center gap-2 no-print">
              ${
                canReset
                  ? `<button type="button" class="btn-simdes-outline" data-empty-action="reset-filter">
                       <i class="fa-solid fa-rotate-left" aria-hidden="true"></i><span>Reset Filter &amp; Tahun</span>
                     </button>`
                  : ''
              }
              <button type="button" class="btn-simdes-primary" data-empty-action="add-new">
                <i class="fa-solid fa-plus" aria-hidden="true"></i><span>${esc(addLabel)}</span>
              </button>
            </div>
          </div>
        </td>
      </tr>`;
  }

  function createBook(cfg) {
    const $ = (id) => document.getElementById(id);

    function run() {
      const pageRoot = $(cfg.rootId);
      if (!pageRoot || !window.SimdesTable) return;

      // ---------- Penyimpanan ----------
      const saveRecords = (list) => {
        try {
          localStorage.setItem(cfg.storageKey, JSON.stringify(list));
        } catch (_) {
          // penyimpanan penuh / mode privat
        }
      };
      const loadRecords = () => {
        try {
          const raw = localStorage.getItem(cfg.storageKey);
          const parsed = raw ? JSON.parse(raw) : null;
          if (Array.isArray(parsed)) return parsed.map((r, i) => cfg.sanitize(r, i));
        } catch (_) {
          // gunakan data contoh
        }
        const seeded = cfg.seed().map((r, i) => cfg.sanitize(r, i));
        saveRecords(seeded);
        return seeded;
      };

      let records = loadRecords();
      let activeDetailId = null;
      let pendingDeleteId = null;

      // ---------- Elemen ----------
      const yearSelect = $('periodTahunSelect');
      const titleEl = $('bookPeriodTitle');
      const printTitleEl = $('printPeriodTitle');
      const printMetaEl = $('printMeta');
      const searchInput = $('tableSearchInput');
      const clearSearchBtn = $('clearSearchInputBtn');
      const pageSizeSelect = $('pageSizeSelect');
      const resetBtn = $('resetTableFilterBtn');
      const tableEl = $('ledgerTable');
      const tbodyEl = $('ledgerTableBody');
      const infoEl = $('tablePaginationInfo');
      const pagEl = $('tablePaginationList');
      const addBtn = $('btnAdd');
      const printBtn = $('btnPrint');
      const sampleBtn = $('btnResetSample');

      const formModalEl = $('formModal');
      const formTitleEl = $('formModalLabel');
      const formEl = $('ledgerForm');
      const alertEl = $('formAlert');
      const alertTextEl = $('formAlertText');
      const submitBtn = $('formSubmitBtn');
      const idInput = $('recordId');
      const urutPreview = $('nomorUrutPreview');
      const detailModalEl = $('detailModal');
      const editFromDetailBtn = $('btnEditFromDetail');
      const deleteModalEl = $('deleteModal');
      const deleteNameEl = $('deleteTargetName');
      const deleteMetaEl = $('deleteTargetMeta');
      const confirmDeleteBtn = $('confirmDeleteBtn');

      const filterEls = cfg.filters.map((f) => ({ ...f, el: $(f.selectId) }));

      window.SimdesForms?.InteractionValidator?.bindForm(formEl);
      cfg.setup?.({ $, formEl });

      // ---------- Tahun ----------
      const currentYear = () => yearSelect?.value || cfg.defaultYear;
      const yearOf = (rec) => cfg.getYear(rec);

      function collectYears(extra = []) {
        const set = new Set([cfg.defaultYear, '2025', ...extra.filter(Boolean)]);
        records.forEach((r) => yearOf(r) && set.add(yearOf(r)));
        return Array.from(set).sort((a, b) => b.localeCompare(a));
      }

      function syncYearSelect(preferred = null) {
        if (!yearSelect) return;
        const current = preferred || yearSelect.value || cfg.defaultYear;
        const years = collectYears([current]);
        yearSelect.innerHTML = years.map((y) => `<option value="${esc(y)}">${esc(y)}</option>`).join('');
        yearSelect.value = years.includes(current) ? current : cfg.defaultYear;
      }

      function updateTitles() {
        const text = cfg.titleText(currentYear());
        if (titleEl) titleEl.textContent = text;
        if (printTitleEl) printTitleEl.textContent = text;
      }

      /** Urutan buku: kronologis berdasarkan tanggal acuan (stabil untuk tanggal sama), per tahun. */
      function yearRows() {
        const year = currentYear();
        const idx = new Map(records.map((r, i) => [r.id, i]));
        return records
          .filter((r) => yearOf(r) === year)
          .sort((a, b) => {
            const c = String(cfg.orderDate(a) || '').localeCompare(String(cfg.orderDate(b) || ''));
            return c !== 0 ? c : idx.get(a.id) - idx.get(b.id);
          })
          .map((r, i) => ({ ...r, ...(cfg.decorate ? cfg.decorate(r) : {}), urutan: i + 1 }));
      }

      const isDefaultYear = () => currentYear() === cfg.defaultYear;

      function syncSortHeaders(state) {
        tableEl?.querySelectorAll('th [data-sort-key]').forEach((btn) => {
          const key = btn.getAttribute('data-sort-key');
          const th = btn.closest('th');
          const icon = btn.querySelector('.th-sort-icon');
          const active = state.sortKey === key;
          btn.classList.toggle('is-sorted', active);
          th?.setAttribute('aria-sort', active ? (state.sortDirection === 'asc' ? 'ascending' : 'descending') : 'none');
          if (icon) {
            icon.className = active
              ? `fa-solid ${state.sortDirection === 'asc' ? 'fa-sort-up' : 'fa-sort-down'} th-sort-icon no-print`
              : 'fa-solid fa-sort th-sort-icon no-print';
          }
        });
      }

      function renderBody(rows, state) {
        if (rows.length === 0) {
          return emptyState({
            colspan: cfg.columnCount,
            title: `Belum Ada Data pada Tahun ${currentYear()}`,
            text: 'Tidak ditemukan data yang cocok dengan tahun, pencarian, atau filter yang dipilih. Tambahkan data baru atau ubah filter.',
            canReset: Boolean(state?.hasActiveQuery) || !isDefaultYear(),
            addLabel: cfg.labels.addButton
          });
        }
        return rows.map((row) => cfg.renderRow(row, row.urutan)).join('');
      }

      const tableCtrl = window.SimdesTable.create({
        data: yearRows(),
        pageSize: 5,
        defaultSortKey: 'urutan',
        defaultSortDirection: 'asc',
        searchFields: cfg.searchFields,
        comparators: { urutan: (a, b) => a.urutan - b.urutan, ...(cfg.comparators || {}) },
        onRender: (state) => {
          syncSortHeaders(state);
          cfg.updateSummary(state.allFilteredRows);
          if (resetBtn) resetBtn.disabled = !(state.hasActiveQuery || !isDefaultYear());
          if (tbodyEl) tbodyEl.innerHTML = renderBody(state.rows, state);
          if (infoEl) {
            infoEl.innerHTML =
              state.totalItems === 0
                ? `Menampilkan <strong>0</strong> dari <strong>0</strong> ${esc(cfg.labels.unit)}`
                : `Menampilkan <strong>${state.startEntry}&ndash;${state.endEntry}</strong> dari <strong>${state.totalItems}</strong> ${esc(cfg.labels.unit)}${
                    state.hasActiveQuery ? ` (disaring dari ${state.totalAllItems} pada tahun ${esc(currentYear())})` : ''
                  }`;
          }
          window.SimdesTable.renderPagination(pagEl, state, (p) => tableCtrl.setPage(p));
        }
      });

      function refresh(preservePage = false) {
        updateTitles();
        tableCtrl.setData(yearRows(), preservePage);
      }

      // ---------- Form ----------
      const showAlert = (msg) => {
        if (alertTextEl) alertTextEl.textContent = msg;
        alertEl?.classList.add('show');
      };
      const hideAlert = () => {
        alertEl?.classList.remove('show');
        if (alertTextEl) alertTextEl.textContent = '';
      };

      function setUrutPreview(year, editId) {
        if (!urutPreview) return;
        const inYear = records.filter((r) => yearOf(r) === year);
        if (editId) {
          const sorted = yearRows().filter((r) => r.id === editId)[0];
          urutPreview.value = `#${sorted ? sorted.urutan : inYear.length} (Tahun ${year})`;
        } else {
          urutPreview.value = `Otomatis (#${inYear.length + 1} — Tahun ${year})`;
        }
      }

      function openAdd() {
        if (!formEl || !formModalEl) return;
        formEl.reset();
        if (idInput) idInput.value = '';
        hideAlert();
        cfg.form.fillNew({ year: currentYear() });
        setUrutPreview(currentYear(), '');
        if (formTitleEl) {
          formTitleEl.innerHTML = `<i class="fa-solid fa-plus-circle text-simdes-primary me-2" aria-hidden="true"></i>${esc(cfg.labels.addTitle)}`;
        }
        if (submitBtn) submitBtn.innerHTML = `<i class="fa-solid fa-floppy-disk" aria-hidden="true"></i><span>${esc(cfg.labels.saveButton)}</span>`;
        window.bootstrap?.Modal.getOrCreateInstance(formModalEl).show();
        formModalEl.addEventListener('shown.bs.modal', () => $(cfg.form.focusId)?.focus(), { once: true });
      }

      function openEdit(id) {
        const rec = records.find((r) => r.id === id);
        if (!rec || !formEl || !formModalEl) return;
        formEl.reset();
        hideAlert();
        if (idInput) idInput.value = rec.id;
        cfg.form.fillEdit(rec);
        setUrutPreview(yearOf(rec), rec.id);
        if (formTitleEl) {
          formTitleEl.innerHTML = `<i class="fa-regular fa-pen-to-square text-simdes-primary me-2" aria-hidden="true"></i>${esc(cfg.labels.editTitle)}`;
        }
        if (submitBtn) submitBtn.innerHTML = '<i class="fa-solid fa-check" aria-hidden="true"></i><span>Simpan Perubahan</span>';
        window.bootstrap?.Modal.getOrCreateInstance(formModalEl).show();
        formModalEl.addEventListener('shown.bs.modal', () => $(cfg.form.focusId)?.focus(), { once: true });
      }

      function openDetail(id) {
        const rec = yearRows().find((r) => r.id === id) || records.find((r) => r.id === id);
        if (!rec || !detailModalEl) return;
        activeDetailId = rec.id;
        cfg.detail(rec);
        window.bootstrap?.Modal.getOrCreateInstance(detailModalEl).show();
      }

      function openDelete(id) {
        const rec = records.find((r) => r.id === id);
        if (!rec || !deleteModalEl) return;
        pendingDeleteId = rec.id;
        const info = cfg.deleteInfo(rec);
        if (deleteNameEl) deleteNameEl.textContent = info.name;
        if (deleteMetaEl) deleteMetaEl.textContent = info.meta;
        window.bootstrap?.Modal.getOrCreateInstance(deleteModalEl).show();
      }

      editFromDetailBtn?.addEventListener('click', () => {
        if (!activeDetailId) return;
        const target = activeDetailId;
        window.bootstrap?.Modal.getOrCreateInstance(detailModalEl)?.hide();
        window.setTimeout(() => openEdit(target), 180);
      });

      formEl?.addEventListener('submit', (e) => {
        e.preventDefault();
        hideAlert();
        const valid = window.SimdesForms?.InteractionValidator
          ? window.SimdesForms.InteractionValidator.validateAll(formEl)
          : formEl.checkValidity();
        if (!valid) {
          showAlert('Mohon lengkapi seluruh kolom wajib dan perbaiki isian yang ditandai sebelum menyimpan.');
          return;
        }
        const existingId = (idInput?.value || '').trim();
        const draft = cfg.form.read();
        const error = cfg.form.validate(draft, records, existingId);
        if (error) {
          showAlert(error.message);
          if (error.focusId) $(error.focusId)?.focus();
          return;
        }
        const payload = cfg.sanitize({ ...draft, id: existingId || `${cfg.idPrefix}-${Date.now()}` });
        const isEdit = Boolean(existingId);
        records = isEdit ? records.map((r) => (r.id === existingId ? payload : r)) : [...records, payload];
        saveRecords(records);

        const savedYear = yearOf(payload);
        const switched = savedYear !== currentYear();
        syncYearSelect(savedYear);
        if (switched && yearSelect) yearSelect.value = savedYear;
        refresh(isEdit && !switched);
        window.bootstrap?.Modal.getOrCreateInstance(formModalEl)?.hide();
        window.SimdesApp?.showToast({
          title: isEdit ? 'Data Diperbarui' : 'Data Ditambahkan',
          message: switched
            ? `"${cfg.recordName(payload)}" disimpan dan tahun dialihkan ke ${savedYear}.`
            : `"${cfg.recordName(payload)}" berhasil ${isEdit ? 'diperbarui' : 'ditambahkan'}.`,
          variant: 'success'
        });
      });

      confirmDeleteBtn?.addEventListener('click', () => {
        if (!pendingDeleteId) return;
        const target = records.find((r) => r.id === pendingDeleteId);
        records = records.filter((r) => r.id !== pendingDeleteId);
        pendingDeleteId = null;
        saveRecords(records);
        syncYearSelect();
        refresh(true);
        window.bootstrap?.Modal.getOrCreateInstance(deleteModalEl)?.hide();
        window.SimdesApp?.showToast({
          title: 'Data Dihapus',
          message: target ? `"${cfg.recordName(target)}" dihapus dan nomor urut disesuaikan otomatis.` : 'Data berhasil dihapus.',
          variant: 'warning'
        });
      });

      // ---------- Pencarian, filter, sorting, pagination, reset ----------
      function resetAll() {
        if (searchInput) searchInput.value = '';
        clearSearchBtn?.classList.remove('visible');
        filterEls.forEach((f) => {
          if (f.el) f.el.value = 'ALL';
        });
        syncYearSelect(cfg.defaultYear);
        if (yearSelect) yearSelect.value = cfg.defaultYear;
        updateTitles();
        tableCtrl.searchQuery = '';
        tableCtrl.filters = {};
        tableCtrl.setData(yearRows(), false);
      }

      yearSelect?.addEventListener('change', () => refresh(false));
      searchInput?.addEventListener('input', () => {
        clearSearchBtn?.classList.toggle('visible', searchInput.value.length > 0);
        tableCtrl.setSearch(searchInput.value);
      });
      clearSearchBtn?.addEventListener('click', () => {
        if (!searchInput) return;
        searchInput.value = '';
        clearSearchBtn.classList.remove('visible');
        tableCtrl.setSearch('');
        searchInput.focus();
      });
      filterEls.forEach((f) =>
        f.el?.addEventListener('change', () => tableCtrl.setFilter(f.key, f.el.value === 'ALL' ? null : f.el.value))
      );
      pageSizeSelect?.addEventListener('change', () => tableCtrl.setPageSize(Number(pageSizeSelect.value) || 5));
      resetBtn?.addEventListener('click', resetAll);
      tableEl?.querySelectorAll('th [data-sort-key]').forEach((btn) =>
        btn.addEventListener('click', () => {
          const key = btn.getAttribute('data-sort-key');
          if (key) tableCtrl.toggleSort(key);
        })
      );
      addBtn?.addEventListener('click', openAdd);

      tbodyEl?.addEventListener('click', (e) => {
        const actionBtn = e.target.closest('[data-row-action]');
        if (actionBtn) {
          const id = actionBtn.getAttribute('data-id');
          const action = actionBtn.getAttribute('data-row-action');
          if (!id) return;
          if (action === 'detail') openDetail(id);
          else if (action === 'edit') openEdit(id);
          else if (action === 'delete') openDelete(id);
          return;
        }
        const emptyBtn = e.target.closest('[data-empty-action]');
        if (emptyBtn) {
          const act = emptyBtn.getAttribute('data-empty-action');
          if (act === 'reset-filter') resetAll();
          else if (act === 'add-new') openAdd();
        }
      });

      sampleBtn?.addEventListener('click', () => {
        records = cfg.seed().map((r, i) => cfg.sanitize(r, i));
        saveRecords(records);
        resetAll();
        window.SimdesApp?.showToast({
          title: 'Data Contoh Dipulihkan',
          message: `${records.length} data contoh fiktif berhasil dimuat ulang.`,
          variant: 'primary'
        });
      });

      // ---------- Cetak ----------
      function preparePrint() {
        updateTitles();
        const state = tableCtrl.getState();
        if (tbodyEl) {
          tbodyEl.innerHTML = renderBody(state.allFilteredRows, { ...state, hasActiveQuery: false });
        }
        if (printMetaEl) {
          let now = '';
          try {
            now = new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }).format(new Date());
          } catch (_) {
            now = todayIso();
          }
          printMetaEl.textContent = `${cfg.printMeta(state.allFilteredRows)} • Dicetak pada ${now}`;
        }
      }

      window.addEventListener('beforeprint', preparePrint);
      window.addEventListener('afterprint', () => tableCtrl.render());
      printBtn?.addEventListener('click', () => {
        preparePrint();
        window.print();
      });

      // ---------- Inisialisasi ----------
      syncYearSelect(cfg.defaultYear);
      updateTitles();
      tableCtrl.render();
    }

    document.addEventListener('DOMContentLoaded', run);
  }

  window.SimdesLedger = {
    createBook,
    esc,
    DASH,
    dashOr,
    formatDate,
    yearOfDate,
    calcAge,
    todayIso,
    fillSelect,
    getRadio,
    setRadio,
    rowActions,
    ketCell,
    emptyState
  };
})();

