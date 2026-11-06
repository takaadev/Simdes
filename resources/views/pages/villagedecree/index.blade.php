<x-appcontent>
    <x-app-heading />
    @include('pages.villagedecree.comp.widgets')
    @include('pages.villagedecree.comp.filter')
    <x-table id="villageDecreesTable">
        <tr class="text-center">
            <th style="width: 20px;">
                No.
            </th>
            <th style="min-width: 180px;">
                Nomor Keputusan
            </th>
            <th style="min-width: 180px;">
                Tanggal Keputusan
            </th>
            <th style="min-width: 250px;">
                Nama Keputusan
            </th>
            <th style="min-width: 250px;">
                Uraian
            </th>
            <th style="min-width: 200px;">
                Nomor Dilaporkan
            </th>
            <th style="min-width: 200px;">
                Tanggal Dilaporkan
            </th>
            <th style="min-width: 160px;">
                Dokumen
            </th>
            <th style="min-width: 220px;">
                Keterangan
            </th>
            <th style="min-width: 180px;" class="text-center">
                Status
            </th>
            <th>
                <small>Aksi</small>
            </th>
        </tr>
    </x-table>

    @push('footer')
        <script>
            $(document).ready(function() {
                const tableElement = $('#villageDecreesTable');

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


                    // Urutkan berdasarkan tanggal ditetapkan
                    order: [
                        [3, 'desc']
                    ],

                    // =================================================
                    // LANGUAGE
                    // =================================================
                    language: {
                        emptyTable: 'Belum ada data peraturan',
                        zeroRecords: 'Data tidak ditemukan',
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
