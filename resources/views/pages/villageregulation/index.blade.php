<x-appcontent>
    <x-app-heading>
        <x-slot:actions>
            <button type="button" id="btnCetakPeraturan" class="btn-simdes-outline">
                <i class="fa-solid fa-print" aria-hidden="true"></i>
                <span>Cetak</span>
            </button>
            <button type="button" id="btnTambahPeraturan" class="btn-simdes-primary">
                <i class="fa-solid fa-plus" aria-hidden="true"></i>
                <span>Tambah Peraturan</span>
            </button>
        </x-slot:actions>
    </x-app-heading>

    <section class="simdes-table-container">
        <table class="simdes-ledger-table table table-bordered table-hover align-middle mb-0" id="myTable">
            <thead>
                <tr class="text-center">
                    <th class="col-no-urut sticky-col-first text-center" style="width: 30px; min-width: 30px;">
                        <small>No.</small>
                    </th>
                    <th style="min-width: 250px;"><small>Jenis Peraturan</small></th>
                    <th style="min-width: 200px;"><small>Nomor Peraturan</small></th>
                    <th style="min-width: 200px;"><small>Tanggal Ditetapkan</small></th>
                    <th style="min-width: 220px;"><small>Tentang</small></th>
                    <th style="min-width: 220px;"><small>Uraian Singkat</small></th>
                    <th style="min-width: 150px;"><small>Tanggal KPD</small></th>
                    <th style="min-width: 180px;"><small>Dilaporkan</small></th>
                    <th style="min-width: 150px;"><small>N.T - DLD</small></th>
                    <th style="min-width: 150px;"><small>N.T DBD</small></th>
                    <th style="min-width: 150px;"><small>Ket.</small></th>

                    <th class="sticky-col-last text-center" style="width: 165px; min-width: 165px;">
                        <small>Aksi</small>
                    </th>
                </tr>
            </thead>

            <tbody id="peraturanTableBody">
                {{-- Data baris peraturan dirender di sini (Blade atau AJAX) --}}
            </tbody>
        </table>
    </section>

    @push('header')
        {{-- CSS Resmi DataTables v2 untuk Bootstrap 5 --}}
        <link href="https://cdn.datatables.net/v/bs5/dt-3.1.5/datatables.min.css" rel="stylesheet"
            integrity="sha384-Ujbks5rRsL++92Atro5FGxEpckvebBSHKgCTRUL3ZfO3JV4xqIoEvyJBMeh5tvJj" crossorigin="anonymous">

        <script src="https://cdn.datatables.net/v/bs5/dt-3.1.5/datatables.min.js"
            integrity="sha384-mSM+Eb0sHaAy8eEmAyjT8Uu2kEEcIAiDVqx68xZG9V8oTq7A774DuCHYWRMW7Hg3" crossorigin="anonymous">
        </script>

        <style>
            /* 1. Container utama TIDAK BOLEH memiliki overflow scroll agar kontrol DataTables tetap diam */
            .simdes-table-container {
                width: 100%;
                overflow: visible;
            }

            .simdes-table-container .dt-container {
                width: 100%;
                max-width: 100%;
            }

            /* Pastikan kolom grid pembungkus tabel dari DataTables tidak meluber */
            .simdes-table-container .dt-layout-table,
            .simdes-table-container .dt-layout-cell {
                width: 100%;
                max-width: 100%;
                overflow: visible;
            }

            /* 2. Area Scroll (Horizontal & Vertikal): HANYA membungkus elemen <table> */
            .wide-scroll-ledger {
                width: 100%;
                max-width: 100%;
                max-height: 70vh;
                /* Batas tinggi tabel, kontrol atas & bawah tetap di luar */
                overflow-x: auto;
                overflow-y: auto;
                position: relative;
                -webkit-overflow-scrolling: touch;
                border: 1px solid var(--bs-border-color, #dee2e6);
                border-radius: 0.375rem;
                background-color: var(--bs-body-bg, #ffffff);
            }

            /* 3. Konfigurasi Tabel Utama */
            .simdes-ledger-table {
                width: 100% !important;
                margin-bottom: 0 !important;
                border-collapse: separate !important;
                border-spacing: 0 !important;
            }

            .simdes-ledger-table th,
            .simdes-ledger-table td {
                vertical-align: middle;
                border-top: none !important;
                border-left: none !important;
                border-bottom: 1px solid var(--bs-border-color, #dee2e6) !important;
                border-right: 1px solid var(--bs-border-color, #dee2e6) !important;
                background-clip: padding-box;
            }

            /* Hilangkan border ekstra di sisi paling kanan & paling bawah */
            .simdes-ledger-table tr th:last-child,
            .simdes-ledger-table tr td:last-child {
                border-right: none !important;
            }

            .simdes-ledger-table tbody tr:last-child td {
                border-bottom: none !important;
            }

            /* 4. Sticky Header (Agar judul kolom tetap di atas saat scroll vertikal) */
            .simdes-ledger-table thead th {
                position: sticky;
                top: 0;
                z-index: 2;
                background-color: var(--bs-tertiary-bg, #f8f9fa) !important;
                box-shadow: inset 0 -1px 0 var(--bs-border-color, #dee2e6);
            }

            /* 5. Sticky Column Kiri (No.) pada Body */
            .simdes-ledger-table th.sticky-col-first,
            .simdes-ledger-table td.sticky-col-first,
            .simdes-ledger-table tbody tr>td:first-child {
                position: sticky;
                left: 0;
                z-index: 3;
                background-color: var(--bs-body-bg, #ffffff);
                box-shadow: inset -1px 0 0 var(--bs-border-color, #dee2e6), 3px 0 6px -2px rgba(0, 0, 0, 0.08);
            }

            /* 6. Sticky Column Kanan (Aksi) pada Body */
            .simdes-ledger-table th.sticky-col-last,
            .simdes-ledger-table td.sticky-col-last,
            .simdes-ledger-table tbody tr>td:last-child {
                position: sticky;
                right: 0;
                z-index: 3;
                background-color: var(--bs-body-bg, #ffffff);
                box-shadow: inset 1px 0 0 var(--bs-border-color, #dee2e6), -3px 0 6px -2px rgba(0, 0, 0, 0.08);
            }

            /* 7. Sudut Pertemuan Header + Sticky Column (No. & Aksi di <thead> wajib z-index tertinggi) */
            .simdes-ledger-table thead th.sticky-col-first {
                z-index: 4 !important;
                background-color: var(--bs-tertiary-bg, #f8f9fa) !important;
                box-shadow: inset -1px -1px 0 var(--bs-border-color, #dee2e6), 3px 0 6px -2px rgba(0, 0, 0, 0.08);
            }

            .simdes-ledger-table thead th.sticky-col-last {
                z-index: 4 !important;
                background-color: var(--bs-tertiary-bg, #f8f9fa) !important;
                box-shadow: inset 1px -1px 0 var(--bs-border-color, #dee2e6), -3px 0 6px -2px rgba(0, 0, 0, 0.08);
            }

            /* 8. Pertahankan warna solid saat baris di-hover agar teks di bawahnya tidak tembus */
            .simdes-ledger-table.table-hover tbody tr:hover>td.sticky-col-first,
            .simdes-ledger-table.table-hover tbody tr:hover>td.sticky-col-last,
            .simdes-ledger-table.table-hover tbody tr:hover>td:first-child,
            .simdes-ledger-table.table-hover tbody tr:hover>td:last-child {
                background-color: var(--bs-table-hover-bg, #f1f3f5) !important;
            }
        </style>
    @endpush

    @push('footer')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tableElement = document.querySelector('#myTable');
                if (!tableElement) return;

                const wrapTableWithScroll = function(tableNode) {
                    if (!tableNode) return;
                    const parent = tableNode.parentElement;
                    if (parent && !parent.classList.contains('wide-scroll-ledger')) {
                        const scrollWrapper = document.createElement('div');
                        scrollWrapper.className = 'wide-scroll-ledger my-2';
                        parent.insertBefore(scrollWrapper, tableNode);
                        scrollWrapper.appendChild(tableNode);
                    }
                };

                const table = new DataTable(tableElement, {
                    processing: true,
                    serverSide: true,

                    scrollX: false,
                    autoWidth: false,
                    responsive: false,

                    ajax: {
                        url: '{{ route('buku-peraturan-desa.data') }}',
                        type: 'GET'
                    },

                    pageLength: 5,

                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            searchable: false,
                            orderable: false
                        },
                        {
                            data: 'regulation_type',
                            name: 'regulation_type'
                        },
                        {
                            data: 'register_number',
                            name: 'register_number'
                        },
                        {
                            data: 'regulation_number',
                            name: 'regulation_number'
                        },
                        {
                            data: 'regulation_title',
                            name: 'regulation_title',
                            render: function(data, type, row) {
                                if (type !== 'display') return data;

                                const title = String(data ?? '-');
                                const words = title.trim().split(/\s+/);

                                const shortTitle = words.length > 3 ?
                                    words.slice(0, 3).join(' ') + '...' :
                                    title;

                                return `<span title="${title.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;')}">${shortTitle.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')}</span>`;
                            }
                        },
                        {
                            data: 'subject',
                            name: 'subject',
                            render: function(data, type, row) {
                                if (type !== 'display') return data;

                                const text = String(data ?? '-');
                                const words = text.trim().split(/\s+/);

                                const shortText = words.length > 1 ?
                                    words.slice(0, 10).join(' ') + '...' :
                                    text;

                                const escapeHtml = (value) => value
                                    .replace(/&/g, '&amp;')
                                    .replace(/</g, '&lt;')
                                    .replace(/>/g, '&gt;')
                                    .replace(/"/g, '&quot;')
                                    .replace(/'/g, '&#039;');

                                return `
            <span title="${escapeHtml(text)}" style="cursor: help;">
                ${escapeHtml(shortText)}
            </span>
        `;
                            }
                        },
                        {
                            data: 'stipulation_date',
                            name: 'stipulation_date'
                        },
                        {
                            data: 'promulgation_date',
                            name: 'promulgation_date'
                        },
                        {
                            data: 'subject',
                            name: 'subject'
                        },

                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'notes',
                            name: 'notes'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],

                    columnDefs: [{
                            targets: 0,
                            className: 'col-no-urut sticky-col-first text-center'
                        },
                        {
                            targets: 10,
                            className: 'sticky-col-last text-center',
                            orderable: false,
                            searchable: false
                        }
                    ],

                    order: [
                        [6, 'desc']
                    ],

                    language: {
                        emptyTable: 'Belum ada data peraturan',
                        zeroRecords: 'Data tidak ditemukan',
                        processing: 'Memproses data...',
                        info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                        infoEmpty: 'Menampilkan 0 data',
                        infoFiltered: '(difilter dari _MAX_ total data)',
                        lengthMenu: 'Tampilkan _MENU_ data',
                        search: 'Pencarian:',
                        // paginate: {
                        //     first: 'Pertama',
                        //     last: 'Terakhir',
                        //     next: 'Selanjutnya',
                        //     previous: 'Sebelumnya'
                        // }
                    },

                    layout: {
                        topStart: 'pageLength',
                        topEnd: 'search',
                        bottomStart: 'info',
                        bottomEnd: 'paging'
                    }
                });

                // Pastikan pembungkus langsung diterapkan bahkan jika tabel masih kosong atau memuat ulang
                wrapTableWithScroll(table.table().node());
            });
        </script>
    @endpush
</x-appcontent>
