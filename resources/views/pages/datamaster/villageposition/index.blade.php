<x-appcontent>
    {{-- @include('pages.villageposition.comp.filter') --}}
    @include('pages.datamaster.villageposition.comp.filter')
    <x-table id="villagePositionTable">
        <tr>
            <th>No.</th>
            <th>Kode</th>
            <th>Nama Jabatan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </x-table>

    @push('footer')
        <script>
            $(document).ready(function() {
                const tableElement = $('#villagePositionTable');

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
                // DATATABLES
                // =====================================================
                const table = tableElement.DataTable({
                    processing: true,
                    serverSide: true,

                    scrollX: false,
                    autoWidth: false,
                    responsive: false,

                    ajax: {
                        url: @json(route('village-positions.index')),
                        type: 'GET'
                    },

                    pageLength: 10,

                    columns: [
                        // 1. Nomor urut
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            searchable: false,
                            orderable: false,
                            className: 'text-center'
                        },

                        // 3. Kode jabatan
                        {
                            data: 'kode',
                            name: 'kode',
                            className: 'text-center'
                        },

                        // 2. Nama jabatan
                        {
                            data: 'nama',
                            name: 'nama'
                        },

                        // 5. Status aktif
                        {
                            data: 'is_active',
                            name: 'is_active',
                            className: 'text-center'
                        },

                        // 6. Aksi
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ],

                    // Urutkan berdasarkan nama jabatan
                    order: [
                        [1, 'asc']
                    ],

                    // =================================================
                    // LANGUAGE
                    // =================================================
                    language: {
                        emptyTable: 'Belum ada data jabatan desa',
                        zeroRecords: 'Data jabatan desa tidak ditemukan',
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
                        wrapTableWithScroll(table.table().node());
                    }
                });

                // Wrapper awal
                wrapTableWithScroll(table.table().node());
            });
        </script>
    @endpush
</x-appcontent>