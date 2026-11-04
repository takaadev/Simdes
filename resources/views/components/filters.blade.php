<div class="ledger-toolbar">
    {{-- KIRI: FILTER --}}
    @if (isset($filter))
        <div class="ledger-filters-group">
            <div class="ledger-filter-dropdown">
                <button type="button" id="toggleLedgerFilter" class="btn-simdes-outline">
                    <i class="fa-solid fa-filter" aria-hidden="true"></i>
                    <span>Filter</span>
                    <span id="activeFilterCount" class="filter-count" hidden>0</span>
                    <i class="fa-solid fa-chevron-down filter-chevron" aria-hidden="true"></i>
                </button>

                <div id="ledgerFilterMenu" class="ledger-filter-menu">
                    <div class="ledger-filter-header">
                        <div>
                            <strong>Filter Data</strong>
                            <small>Sesuaikan data yang ingin ditampilkan.</small>
                        </div>

                        <button type="button" id="resetTableFilterBtn" class="filter-reset-btn">
                            Reset
                        </button>
                    </div>

                    <div class="ledger-filter-body">
                        {{ $filter }}
                    </div>
                </div>
            </div>
        </div>
    @endif
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

        .ledger-filter-dropdown>button {
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
            transition: opacity 0.2s ease, transform 0.2s ease,
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

        .ledger-filter-header>div {
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

        /* Responsive */
        @media (max-width: 576px) {
            .ledger-toolbar {
                align-items: stretch;
            }

            .ledger-filters-group,
            .ledger-actions-group {
                width: 100%;
            }

            .ledger-actions-group>button {
                flex: 1;
            }

            .ledger-filter-menu {
                width: min(310px, calc(100vw - 40px));
            }
        }
    </style>
@endpush
@push('footer')
    <script>
        const filterDropdown = document.querySelector('.ledger-filter-dropdown');
        const toggleFilter = document.getElementById('toggleLedgerFilter');

        toggleFilter.addEventListener('click', function(event) {
            event.stopPropagation();
            filterDropdown.classList.toggle('open');
        });

        document.addEventListener('click', function(event) {
            if (!filterDropdown.contains(event.target)) {
                filterDropdown.classList.remove('open');
            }
        });
    </script>
@endpush
