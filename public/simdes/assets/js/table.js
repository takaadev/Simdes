/* ==========================================================================
   SIMDES DESA TIARO — Reusable Frontend Table Controller (table.js)
   Provides client-side search, multi-criteria filtering, column sorting,
   pagination, and Indonesian date formatting utilities for register pages.
   ========================================================================== */

(() => {
  'use strict';

  const BULAN_INDO = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
  ];

  function formatDateIndo(isoDate) {
    if (!isoDate || typeof isoDate !== 'string') return '';
    const parts = isoDate.trim().split('-');
    if (parts.length !== 3) return isoDate;
    const year = Number(parts[0]);
    const monthIdx = Number(parts[1]) - 1;
    const day = Number(parts[2]);
    if (!year || monthIdx < 0 || monthIdx > 11 || !day) return isoDate;
    return `${day} ${BULAN_INDO[monthIdx]} ${year}`;
  }

  function renderPagination(listEl, state, onPageSelect) {
    if (!listEl) return;

    const { currentPage, totalPages } = state;
    const pages = [];

    if (totalPages <= 7) {
      for (let i = 1; i <= totalPages; i += 1) pages.push(i);
    } else {
      pages.push(1);
      if (currentPage > 3) pages.push('...');
      const start = Math.max(2, currentPage - 1);
      const end = Math.min(totalPages - 1, currentPage + 1);
      for (let i = start; i <= end; i += 1) pages.push(i);
      if (currentPage < totalPages - 2) pages.push('...');
      pages.push(totalPages);
    }

    const prevDisabled = currentPage <= 1 ? 'disabled' : '';
    const nextDisabled = currentPage >= totalPages ? 'disabled' : '';

    listEl.innerHTML = `
      <li>
        <button type="button" class="simdes-page-btn" data-page="${currentPage - 1}" ${prevDisabled} aria-label="Halaman sebelumnya">
          <i class="fa-solid fa-chevron-left" style="font-size:0.7rem;" aria-hidden="true"></i>
        </button>
      </li>
      ${pages
        .map((p) => {
          if (p === '...') {
            return `<li><span class="px-2 text-simdes-muted" aria-hidden="true">&hellip;</span></li>`;
          }
          const active = p === currentPage;
          return `
            <li>
              <button type="button"
                      class="simdes-page-btn${active ? ' active' : ''}"
                      data-page="${p}"
                      ${active ? 'aria-current="page"' : ''}
                      aria-label="Halaman ${p}">
                ${p}
              </button>
            </li>
          `;
        })
        .join('')}
      <li>
        <button type="button" class="simdes-page-btn" data-page="${currentPage + 1}" ${nextDisabled} aria-label="Halaman berikutnya">
          <i class="fa-solid fa-chevron-right" style="font-size:0.7rem;" aria-hidden="true"></i>
        </button>
      </li>
    `;

    listEl.querySelectorAll('button[data-page]').forEach((btn) => {
      btn.addEventListener('click', () => {
        if (btn.disabled) return;
        const next = Number(btn.dataset.page);
        if (typeof onPageSelect === 'function') {
          onPageSelect(next);
        }
      });
    });
  }

  class SimdesTableController {
    constructor(options = {}) {
      this.data = Array.isArray(options.data) ? [...options.data] : [];
      this.pageSize = options.pageSize || 10;
      this.currentPage = 1;
      this.searchQuery = '';
      this.searchFields = options.searchFields || [];
      this.filters = {};
      this.sortKey = options.defaultSortKey || null;
      this.sortDirection = options.defaultSortDirection || 'asc';
      this.comparators = options.comparators || {};
      this.onRender = typeof options.onRender === 'function' ? options.onRender : null;
    }

    setData(rows, preservePage = false) {
      this.data = Array.isArray(rows) ? [...rows] : [];
      if (!preservePage) {
        this.currentPage = 1;
      }
      this.render();
    }

    setSearch(query) {
      this.searchQuery = String(query || '').trim().toLowerCase();
      this.currentPage = 1;
      this.render();
    }

    setFilter(key, value) {
      if (value === '' || value === null || value === undefined || value === 'ALL') {
        delete this.filters[key];
      } else {
        this.filters[key] = value;
      }
      this.currentPage = 1;
      this.render();
    }

    resetAll() {
      this.searchQuery = '';
      this.filters = {};
      this.currentPage = 1;
      this.render();
    }

    toggleSort(key) {
      if (this.sortKey === key) {
        this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
      } else {
        this.sortKey = key;
        this.sortDirection = 'asc';
      }
      this.render();
    }

    setPage(page) {
      const totalPages = this.getTotalPages(this.getProcessedRows().length);
      this.currentPage = Math.min(Math.max(1, Number(page) || 1), totalPages);
      this.render();
    }

    setPageSize(size) {
      this.pageSize = Math.max(1, Number(size) || 10);
      this.currentPage = 1;
      this.render();
    }

    getProcessedRows() {
      let rows = [...this.data];

      // 1. Search
      if (this.searchQuery) {
        rows = rows.filter((row) => {
          const keys = this.searchFields.length > 0 ? this.searchFields : Object.keys(row);
          return keys.some((k) =>
            String(row[k] ?? '')
              .toLowerCase()
              .includes(this.searchQuery)
          );
        });
      }

      // 2. Filter
      const activeFilters = Object.entries(this.filters);
      if (activeFilters.length > 0) {
        rows = rows.filter((row) =>
          activeFilters.every(([k, expected]) => {
            if (typeof expected === 'function') return expected(row[k], row);
            return String(row[k] ?? '') === String(expected);
          })
        );
      }

      // 3. Sort
      if (this.sortKey) {
        const dir = this.sortDirection === 'asc' ? 1 : -1;
        const key = this.sortKey;
        const customCmp = this.comparators[key];

        rows.sort((a, b) => {
          if (typeof customCmp === 'function') {
            return customCmp(a, b) * dir;
          }
          const va = a[key] ?? '';
          const vb = b[key] ?? '';
          if (typeof va === 'number' && typeof vb === 'number') {
            return (va - vb) * dir;
          }
          return String(va).localeCompare(String(vb), 'id', { numeric: true, sensitivity: 'base' }) * dir;
        });
      }

      return rows;
    }

    getTotalPages(totalItems) {
      return Math.max(1, Math.ceil(totalItems / this.pageSize));
    }

    getState() {
      const filtered = this.getProcessedRows();
      const totalItems = filtered.length;
      const totalPages = this.getTotalPages(totalItems);
      const safePage = Math.min(this.currentPage, totalPages);
      const startIndex = (safePage - 1) * this.pageSize;
      const pageRows = filtered.slice(startIndex, startIndex + this.pageSize);

      return {
        rows: pageRows,
        allFilteredRows: filtered,
        totalItems,
        totalAllItems: this.data.length,
        currentPage: safePage,
        pageSize: this.pageSize,
        totalPages,
        startIndex,
        startEntry: totalItems === 0 ? 0 : startIndex + 1,
        endEntry: Math.min(startIndex + this.pageSize, totalItems),
        sortKey: this.sortKey,
        sortDirection: this.sortDirection,
        hasActiveQuery: Boolean(this.searchQuery) || Object.keys(this.filters).length > 0
      };
    }

    render() {
      if (this.onRender) {
        this.onRender(this.getState());
      }
    }
  }

  window.SimdesTable = {
    create: (options) => new SimdesTableController(options),
    formatDateIndo,
    renderPagination
  };
})();
