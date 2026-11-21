<x-appcontent>
    @include('pages.villageland.comp.widgets')
    @include('pages.villageland.comp.filter')

    <x-table id="villageLandTable">
        <tr class="text-center">
            <th rowspan="3">NO.</th>
            <th rowspan="3">NAMA PER-ORANGAN / BADAN HUKUM</th>
            <th rowspan="3">JML (M²)</th>

            <th colspan="8" class="text-center">
                STATUS HAK TANAH (M²)
            </th>

            <th colspan="14" class="text-center">
                PENGGUNAAN TANAH (M²)
            </th>
            <th rowspan="3">KETERANGAN</th>
            <th rowspan="3">AKSI</th>
        </tr>

        <tr class="text-center">
            <th colspan="5">SUDAH BERSERTIFIKAT</th>
            <th colspan="3">BELUM BERSERTIFIKAT</th>
            <th colspan="5">NON PERTANIAN</th>
            <th colspan="9">PERTANIAN &amp; LAINNYA</th>
        </tr>

        <tr class="text-center">
            {{-- Status Hak Tanah --}}
            <th>HM</th>
            <th>HGB</th>
            <th>HP</th>
            <th>HGU</th>
            <th>HPL</th>
            <th>MA</th>
            <th>VI</th>
            <th>TN</th>

            {{-- Penggunaan Tanah - Non Pertanian --}}
            <th>PERUMAHAN</th>
            <th>PERDAGANGAN DAN JASA</th>
            <th>PERKANTORAN</th>
            <th>INDUSTRI</th>
            <th>FASILITAS UMUM</th>

            {{-- Penggunaan Tanah - Pertanian & Lainnya --}}
            <th>SAWAH</th>
            <th>TEGALAN</th>
            <th>PERKEBUNAN</th>
            <th>PETERNAKAN/PERIKANAN</th>
            <th>HUTAN BELUKAR</th>
            <th>HUTAN LEBAT/LINDUNG</th>
            <th>TANAH KOSONG</th>
            <th>MUTASI TANAH DI DESA</th>
            <th>LAIN-LAIN</th>
        </tr>
    </x-table>

    @push('footer')
        <script>
            $(document).ready(function() {
                const tableElement = $('#villageLandTable');

                if (!tableElement.length) return;

                // =====================================================
                // WRAPPER HORIZONTAL SCROLL
                // =====================================================
                function wrapTableWithScroll(tableNode) {
                    if (!tableNode) return;

                    const parent = tableNode.parentElement;

                    if (parent && !parent.classList.contains('wide-scroll-ledger')) {
                        const scrollWrapper = document.createElement('div');
                        scrollWrapper.className = 'wide-scroll-ledger';

                        parent.insertBefore(scrollWrapper, tableNode);
                        scrollWrapper.appendChild(tableNode);
                    }
                }

                // =====================================================
                // INISIALISASI TOOLTIP BOOTSTRAP 5
                // =====================================================
                function initTooltips(container) {
                    if (!window.bootstrap?.Tooltip || !container) return;

                    container.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((el) => {
                        bootstrap.Tooltip.getOrCreateInstance(el);
                    });
                }

                // =====================================================
                // FILTER MAP
                // =====================================================
                const filterMap = {
                    status_hak: '#filterHakSelect',
                    penggunaan: '#filterPenggunaanSelect',
                    deleted: '#filterDeletedSelect',
                };

                // =====================================================
                // DATATABLES
                // =====================================================
                const table = tableElement.DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: false,
                    autoWidth: false,
                    responsive: false,

                    ajax: {
                        url: @json(route('village-lands.index')),
                        type: 'GET',
                    },

                    pageLength: 10,

                    columns: [
                        // 1. NO.
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            searchable: false,
                            orderable: false,
                            className: 'text-center'
                        },
                        // 2. NAMA PER-ORANGAN / BADAN HUKUM
                        {
                            data: 'owner_name',
                            name: 'owner_name'
                        },
                        // 3. JML (M²)
                        {
                            data: 'jml',
                            name: 'jml',
                            className: 'text-end fw-semibold'
                        },

                        // 4–8. Status Hak Tanah — Sudah Bersertifikat
                        {
                            data: 'hm',
                            name: 'hm',
                            className: 'text-end'
                        },
                        {
                            data: 'hgb',
                            name: 'hgb',
                            className: 'text-end'
                        },
                        {
                            data: 'hp',
                            name: 'hp',
                            className: 'text-end'
                        },
                        {
                            data: 'hgu',
                            name: 'hgu',
                            className: 'text-end'
                        },
                        {
                            data: 'hpl',
                            name: 'hpl',
                            className: 'text-end'
                        },

                        // 9–11. Status Hak Tanah — Belum Bersertifikat
                        {
                            data: 'ma',
                            name: 'ma',
                            className: 'text-end'
                        },
                        {
                            data: 'vi',
                            name: 'vi',
                            className: 'text-end'
                        },
                        {
                            data: 'tn',
                            name: 'tn',
                            className: 'text-end'
                        },

                        // 12–16. Penggunaan Tanah — Non Pertanian
                        {
                            data: 'perumahan',
                            name: 'perumahan',
                            className: 'text-end'
                        },
                        {
                            data: 'dagang_jasa',
                            name: 'dagang_jasa',
                            className: 'text-end'
                        },
                        {
                            data: 'perkantoran',
                            name: 'perkantoran',
                            className: 'text-end'
                        },
                        {
                            data: 'industri',
                            name: 'industri',
                            className: 'text-end'
                        },
                        {
                            data: 'fasilitas_umum',
                            name: 'fasilitas_umum',
                            className: 'text-end'
                        },

                        // 17–25. Penggunaan Tanah — Pertanian & Lainnya
                        {
                            data: 'sawah',
                            name: 'sawah',
                            className: 'text-end'
                        },
                        {
                            data: 'tegalan',
                            name: 'tegalan',
                            className: 'text-end'
                        },
                        {
                            data: 'perkebunan',
                            name: 'perkebunan',
                            className: 'text-end'
                        },
                        {
                            data: 'peternakan_perikanan',
                            name: 'peternakan_perikanan',
                            className: 'text-end'
                        },
                        {
                            data: 'hutan_belukar',
                            name: 'hutan_belukar',
                            className: 'text-end'
                        },
                        {
                            data: 'hutan_lebat',
                            name: 'hutan_lebat',
                            className: 'text-end'
                        },
                        {
                            data: 'tanah_kosong',
                            name: 'tanah_kosong',
                            className: 'text-end'
                        },
                        {
                            data: 'mutasi_tanah',
                            name: 'mutasi_tanah',
                            className: 'text-end'
                        },
                        {
                            data: 'lainnya',
                            name: 'lainnya',
                            className: 'text-end'
                        },

                        // 26. KET.
                        {
                            data: 'catatan',
                            name: 'catatan',
                            orderable: false
                        },
                        // 27. AKSI
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },
                    ],

                    // Urutkan default berdasarkan nama pemilik (A - Z)
                    order: [
                        [1, 'asc']
                    ],

                    // =================================================
                    // LANGUAGE
                    // =================================================
                    language: {
                        emptyTable: 'Belum ada data tanah di desa',
                        zeroRecords: 'Data tanah di desa tidak ditemukan',
                        processing: 'Memproses data...',
                        info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                        infoEmpty: 'Menampilkan 0 data',
                        infoFiltered: '(difilter dari _MAX_ total data)',
                        lengthMenu: 'Tampilkan _MENU_ data',
                        search: 'Pencarian:',
                    },

                    // =================================================
                    // LAYOUT
                    // =================================================
                    layout: {
                        topStart: 'pageLength',
                        topEnd: 'search',
                        bottomStart: 'info',
                        bottomEnd: 'paging'
                    },

                    // =================================================
                    // DRAW CALLBACK
                    // =================================================
                    drawCallback: function() {
                        const tableNode = this.api().table().node();
                        wrapTableWithScroll(tableNode);
                        initTooltips(tableNode);
                    }
                });

                // Wrapper awal
                wrapTableWithScroll(table.table().node());
                $('#btnCetakTanah').on('click', function() {
                    window.openSimdesPrintModal({
                        title: 'Buku Tanah di Desa',
                        url: @json(route('village-lands.print')),
                        filterMap: filterMap,
                        tableSelector: '#villageLandTable',
                        paper: '330mm 215mm', // F4 Landscape agar 26 kolom tidak terpotong
                    });
                });
            });
        </script>
    @endpush
</x-appcontent>
