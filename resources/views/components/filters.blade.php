@props([
    'url' => '',
    'table' => '#dataTable',
    'trashed' => true,
])

@php
    $toolbarId = 'ledger-toolbar-' . \Illuminate\Support\Str::uuid();
    $filterId = $toolbarId . '-filter';
    $toggleId = $toolbarId . '-toggle';
    $countId = $toolbarId . '-count';
    $resetId = $toolbarId . '-reset';
    $trashedId = $toolbarId . '-trashed';
    $applyId = $toolbarId . '-apply';
@endphp

<div
    id="{{ $toolbarId }}"
    class="ledger-toolbar"
    data-ledger-toolbar
    data-url="{{ $url }}"
    data-table="{{ $table }}"
    data-trashed="false"
>
    {{-- KIRI: FILTER --}}
    @if (isset($filter))
        <div class="ledger-filters-group">
            <div class="ledger-filter-dropdown">

                <button
                    type="button"
                    id="{{ $toggleId }}"
                    class="btn-simdes-outline btn-sm"
                    data-ledger-filter-toggle
                    aria-expanded="false"
                    aria-controls="{{ $filterId }}"
                >
                    <i class="fa-solid fa-filter" aria-hidden="true"></i>
                    <span>Filter</span>

                    <span
                        id="{{ $countId }}"
                        class="filter-count"
                        data-ledger-filter-count
                        hidden
                    >0</span>

                    <i class="fa-solid fa-chevron-down filter-chevron"
                        aria-hidden="true"></i>
                </button>

                <div
                    id="{{ $filterId }}"
                    class="ledger-filter-menu"
                    data-ledger-filter-menu
                >
                    <div class="ledger-filter-header">
                        <div>
                            <strong>Filter Data</strong>
                            <small>Yang ingin ditampilkan.</small>
                        </div>

                        <div class="ledger-filter-header-actions">
                            <button
                                type="button"
                                id="{{ $resetId }}"
                                class="filter-reset-btn"
                                data-ledger-filter-reset
                            >
                                Reset
                            </button>

                            @if ($trashed)
                                <button
                                    type="button"
                                    id="{{ $trashedId }}"
                                    class="filter-trashed-btn"
                                    data-ledger-filter-trashed
                                    aria-pressed="false"
                                    title="Tampilkan data yang sudah dihapus"
                                >
                                    <i class="fa-solid fa-trash-can" aria-hidden="true"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="ledger-filter-body">
                        {{ $filter }}
                    </div>

                    <div class="ledger-filter-footer">
                        <button
                            type="button"
                            id="{{ $applyId }}"
                            class="btn-simdes-primary w-100"
                            data-ledger-filter-apply
                        >
                            <i class="fa-solid fa-filter me-1"></i>
                            Terapkan Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- KANAN: ACTIONS --}}
    @if (isset($actions))
        <div class="ledger-actions-group">
            {{ $actions }}
        </div>
    @endif
</div>

@push('header')
    <style>
        /* Ledger Toolbar */
        .ledger-toolbar {
            padding: 1.05rem 1.35rem;
            border-block-end: 1px solid var(--simdes-border);
            background-color: #fafbf9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.85rem;
        }

        .ledger-filters-group,
        .ledger-actions-group {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.6rem;
        }

        /* Filter Dropdown */
        .ledger-filter-dropdown {
            position: relative;
        }

        .ledger-filter-dropdown > button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.55rem;
            min-height: 42px;
        }

        .filter-chevron {
            font-size: 0.7rem;
            transition: transform 0.2s ease;
        }

        .ledger-filter-dropdown.open .filter-chevron {
            transform: rotate(180deg);
        }

        .filter-count {
            min-width: 19px;
            height: 19px;
            padding-inline: 5px;
            border-radius: 20px;
            background: var(--simdes-primary);
            color: #fff;
            font-size: 0.7rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .filter-count[hidden] {
            display: none;
        }

        /* Dropdown Menu */
        .ledger-filter-menu {
            position: absolute;
            top: calc(100% + 10px);
            left: 0;
            z-index: 1050;
            width: 310px;
            padding: 0;
            background: var(--simdes-surface);
            border: 1px solid var(--simdes-border);
            border-radius: 12px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-6px);
            transition:
                opacity 0.2s ease,
                transform 0.2s ease,
                visibility 0.2s ease;
        }

        .ledger-filter-dropdown.open .ledger-filter-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .ledger-filter-header {
            padding: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            border-block-end: 1px solid var(--simdes-border);
        }

        .ledger-filter-header > div:first-child {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .ledger-filter-header strong {
            font-size: 0.9rem;
            color: var(--simdes-text);
        }

        .ledger-filter-header small {
            font-size: 0.75rem;
            color: var(--simdes-text-muted);
        }

        .ledger-filter-header-actions {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            flex-shrink: 0;
        }

        .filter-reset-btn {
            padding: 0;
            border: 0;
            background: transparent;
            color: var(--simdes-primary);
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
        }

        .filter-reset-btn:hover {
            text-decoration: underline;
        }

        .filter-trashed-btn {
            width: 30px;
            height: 30px;
            padding: 0;
            border: 1px solid rgba(220, 53, 69, 0.25);
            border-radius: 8px;
            background: transparent;
            color: #dc3545;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
        }

        .filter-trashed-btn:hover {
            background: rgba(220, 53, 69, 0.08);
            border-color: rgba(220, 53, 69, 0.5);
        }

        .filter-trashed-btn.active {
            background: #dc3545;
            border-color: #dc3545;
            color: #fff;
        }

        .ledger-filter-body {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
        }

        .ledger-filter-item {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .ledger-filter-item label {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--simdes-text);
        }

        .ledger-filter-item .simdes-select {
            width: 100%;
            min-height: 40px;
        }

        .ledger-filter-footer {
            padding: 0.85rem 1rem;
            display: flex;
            justify-content: center;
        }

        .ledger-filter-footer button {
            font-size: 0.80rem;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .ledger-toolbar {
                align-items: stretch;
            }

            .ledger-filters-group,
            .ledger-actions-group {
                width: 100%;
            }

            .ledger-actions-group > button {
                flex: 1;
            }

            .ledger-filter-menu {
                width: min(310px, calc(100vw - 40px));
            }
        }
    </style>
@endpush

@pushOnce('footer', 'ledger-toolbar-script')
    <script>
        (function ($, document) {
            'use strict';

            if (!$) {
                console.error('[SIMDES Toolbar] jQuery tidak ditemukan.');
                return;
            }

            const toolbarSelector = '[data-ledger-toolbar]';

            function closeDropdown($toolbar) {
                $toolbar.find('.ledger-filter-dropdown').removeClass('open');
                $toolbar.find('[data-ledger-filter-toggle]')
                    .attr('aria-expanded', 'false');
            }

            function isTrashedMode($toolbar) {
                return $toolbar.attr('data-trashed') === 'true';
            }

            function setTrashedMode($toolbar, active) {
                const isActive = Boolean(active);

                $toolbar.attr('data-trashed', isActive ? 'true' : 'false');

                $toolbar.find('[data-ledger-filter-trashed]')
                    .toggleClass('active', isActive)
                    .attr('aria-pressed', isActive ? 'true' : 'false')
                    .attr(
                        'title',
                        isActive
                            ? 'Kembali ke data aktif'
                            : 'Tampilkan data yang sudah dihapus'
                    );
            }

            function getFilters($toolbar) {
                const filters = {};

                $toolbar.find('[data-simdes-filter]').each(function () {
                    const $select = $(this);
                    const name = $select.attr('name');
                    const value = $select.val();

                    if (name && value !== null && value !== '' && value !== 'all') {
                        filters[name] = value;
                    }
                });

                if (isTrashedMode($toolbar)) {
                    filters.deleted = 'trashed';
                }

                return filters;
            }

            function updateFilterCount($toolbar) {
                let count = 0;
                let hasDeletedSelect = false;

                $toolbar.find('[data-simdes-filter]').each(function () {
                    const $select = $(this);
                    const name = $select.attr('name');
                    const value = String($select.val() ?? '');
                    const defaultValue = String(
                        $select.attr('data-default-value') ?? 'all'
                    );

                    if (name === 'deleted') {
                        hasDeletedSelect = true;
                    }

                    if (value !== defaultValue && value !== '') {
                        count++;
                    }
                });

                if (isTrashedMode($toolbar) && !hasDeletedSelect) {
                    count++;
                }

                const $badge = $toolbar.find('[data-ledger-filter-count]');

                $badge.text(count);
                $badge.prop('hidden', count === 0);
            }

            function applyFilters($toolbar) {
                const url = $toolbar.attr('data-url');
                const tableSelector = $toolbar.attr('data-table');

                if (!url) {
                    console.error('[SIMDES Toolbar] URL DataTables belum ditentukan.');
                    return;
                }

                const $table = $(tableSelector);

                if (!$table.length || !$.fn.DataTable.isDataTable($table[0])) {
                    console.error('[SIMDES Toolbar] DataTables tidak ditemukan:', tableSelector);
                    return;
                }

                const filters = getFilters($toolbar);
                const query = $.param(filters);
                const requestUrl = query ? `${url}?${query}` : url;

                $table.DataTable().ajax.url(requestUrl).load();

                updateFilterCount($toolbar);
                closeDropdown($toolbar);
            }

            // Buka / tutup dropdown.
            $(document).on('click', '[data-ledger-filter-toggle]', function (event) {
                event.stopPropagation();

                const $toolbar = $(this).closest(toolbarSelector);
                const $dropdown = $toolbar.find('.ledger-filter-dropdown');
                const isOpen = $dropdown.hasClass('open');

                closeDropdown($toolbar);

                if (!isOpen) {
                    $dropdown.addClass('open');
                    $(this).attr('aria-expanded', 'true');
                }
            });

            // Klik di luar dropdown.
            $(document).on('click', function (event) {
                $(toolbarSelector).each(function () {
                    const $toolbar = $(this);

                    if (!$(event.target).closest($toolbar.find('.ledger-filter-dropdown')).length) {
                        closeDropdown($toolbar);
                    }
                });
            });

            // Toggle tampilkan data terhapus (deleted=trashed).
            $(document).on('click', '[data-ledger-filter-trashed]', function () {
                const $toolbar = $(this).closest(toolbarSelector);

                setTrashedMode($toolbar, !isTrashedMode($toolbar));
                applyFilters($toolbar);
            });

            // Terapkan filter.
            $(document).on('click', '[data-ledger-filter-apply]', function () {
                const $toolbar = $(this).closest(toolbarSelector);

                applyFilters($toolbar);
            });

            // Reset filter & kembalikan dari mode terhapus.
            $(document).on('click', '[data-ledger-filter-reset]', function () {
                const $toolbar = $(this).closest(toolbarSelector);

                $toolbar.find('[data-simdes-filter]').each(function () {
                    const defaultValue = $(this).attr('data-default-value') ?? 'all';
                    $(this).val(defaultValue);
                });

                setTrashedMode($toolbar, false);
                applyFilters($toolbar);
            });

            // Hitung filter awal.
            $(function () {
                $(toolbarSelector).each(function () {
                    updateFilterCount($(this));
                });
            });

        })(window.jQuery, document);
    </script>
@endPushOnce