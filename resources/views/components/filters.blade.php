
@props([
    'url' => '',
    'table' => '#dataTable',
])

@php
    $toolbarId = 'ledger-toolbar-' . \Illuminate\Support\Str::uuid();
    $filterId = $toolbarId . '-filter';
    $toggleId = $toolbarId . '-toggle';
    $countId = $toolbarId . '-count';
    $resetId = $toolbarId . '-reset';
    $applyId = $toolbarId . '-apply';
@endphp

<div
    id="{{ $toolbarId }}"
    class="ledger-toolbar"
    data-ledger-toolbar
    data-url="{{ $url }}"
    data-table="{{ $table }}"
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
                            <small>Sesuaikan data yang ingin ditampilkan.</small>
                        </div>

                        <button
                            type="button"
                            id="{{ $resetId }}"
                            class="filter-reset-btn"
                            data-ledger-filter-reset
                        >
                            Reset
                        </button>
                    </div>

                    <div class="ledger-filter-body">
                        {{ $filter }}
                    </div>

                    <div class="ledger-filter-footer">
                        <button
                            type="button"
                            id="{{ $applyId }}"
                            class="btn-simdes-primary"
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
            border-block-end: 1px solid var(--simdes-border);
        }

        .ledger-filter-header > div {
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
            border-block-start: 1px solid var(--simdes-border);
            display: flex;
            justify-content: flex-end;
        }

        .ledger-filter-footer button {
            min-height: 38px;
            font-size: 0.8rem;
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

            function getFilters($toolbar) {
                const filters = {};
                $toolbar.find('[data-simdes-filter]').each(function () {
                    const $select = $(this);
                    const name = $select.attr('name');
                    const value = $select.val();

                    if (name && value !== 'all') {
                        filters[name] = value;
                    }
                });
                return filters;
            }

            function updateFilterCount($toolbar) {
                let count = 0;

                $toolbar.find('[data-simdes-filter]').each(function () {
                    const $select = $(this);
                    const value = String($select.val() ?? '');
                    const defaultValue = String(
                        $select.attr('data-default-value') ?? 'all'
                    );

                    if (value !== defaultValue && value !== '') {
                        count++;
                    }
                });

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

            // Terapkan filter.
            $(document).on('click', '[data-ledger-filter-apply]', function () {
                const $toolbar = $(this).closest(toolbarSelector);

                applyFilters($toolbar);
            });

            // Reset filter.
            $(document).on('click', '[data-ledger-filter-reset]', function () {
                const $toolbar = $(this).closest(toolbarSelector);

                $toolbar.find('[data-simdes-filter]').each(function () {
                    $(this).val('all');
                });

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
