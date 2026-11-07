
<x-appcontent>
    <x-app-heading />

    @include('pages.villagedecree.comp.widgets')
    @include('pages.villagedecree.comp.filter')

    <x-table id="villageDecreesTable">
        <tr >
            <th>No.</th>
            <th>Nomor Keputusan</th>
            <th>Tanggal Keputusan</th>
            <th>Nama Keputusan</th>
            <th>Uraian</th>
            <th>Nomor Dilaporkan</th>
            <th>Tanggal Dilaporkan</th>
            <th>Dokumen</th>
            <th>Keterangan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </x-table>

    @push('footer')
        <script>
            $(document).ready(function () {
                const tableElement = $('#villageDecreesTable');

                if (!tableElement.length) return;

                // =====================================================
                // HORIZONTAL SCROLL WRAPPER
                // =====================================================
                function wrapTableWithScroll(tableNode) {
                    if (!tableNode) return;

                    const parent = tableNode.parentElement;

                    if (!parent?.classList.contains('wide-scroll-ledger')) {
                        const wrapper = document.createElement('div');

                        wrapper.className = 'wide-scroll-ledger';

                        parent.insertBefore(wrapper, tableNode);
                        wrapper.appendChild(tableNode);
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
                        url: @json(route('village-decrees.index')),
                        type: 'GET'
                    },

                    pageLength: 10,

                    columns: [
                        // 1. Nomor urut
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            searchable: false,
                            orderable: false
                        },

                        // 2. Nomor Keputusan
                        {
                            data: 'nomor_keputusan',
                            name: 'nomor_keputusan'
                        },

                        // 3. Tanggal Keputusan
                        {
                            data: 'tgl_keputusan',
                            name: 'tgl_keputusan'
                        },

                        // 4. Nama Keputusan
                        {
                            data: 'nama_keputusan',
                            name: 'nama_keputusan'
                        },

                        // 5. Uraian
                        {
                            data: 'uraian',
                            name: 'uraian',
                            defaultContent: '-'
                        },

                        // 6. Nomor Dilaporkan
                        {
                            data: 'nomor_dilaporkan',
                            name: 'nomor_dilaporkan',
                            defaultContent: '-'
                        },

                        // 7. Tanggal Dilaporkan
                        {
                            data: 'tgl_dilaporkan',
                            name: 'tgl_dilaporkan',
                            defaultContent: '-'
                        },

                        // 8. Dokumen
                        {
                            data: 'dokumen',
                            name: 'dokumen',
                            defaultContent: '-',
                            searchable: false,
                            orderable: false
                        },

                        // 9. Keterangan
                        {
                            data: 'catatan',
                            name: 'catatan',
                            defaultContent: '-'
                        },

                        // 10. Status
                        {
                            data: 'status',
                            name: 'status'
                        },

                        // 11. Aksi
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],

                    // Urutkan berdasarkan tanggal keputusan
                    order: [
                        [2, 'desc']
                    ],

                    // =================================================
                    // LANGUAGE
                    // =================================================
                    language: {
                        emptyTable: 'Belum ada data keputusan',
                        zeroRecords: 'Data tidak ditemukan',
                        processing: 'Memproses data...',
                        info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                        infoEmpty: 'Menampilkan 0 data',
                        infoFiltered: '(difilter dari _MAX_ total data)',
                        lengthMenu: 'Tampilkan _MENU_ data',
                        search: 'Pencarian:'
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
                    drawCallback: function () {
                        wrapTableWithScroll(table.table().node());
                    }
                });

                // Wrapper awal
                wrapTableWithScroll(table.table().node());
            });
        </script>
    @endpush
</x-appcontent>
