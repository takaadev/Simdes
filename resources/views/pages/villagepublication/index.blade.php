<x-appcontent>
    @include('pages.villagepublication.comp.widgets')
    @include('pages.villagepublication.comp.filter')
    <x-table id="villagePublicationTable">
        <tr>
            <th scope="col" rowspan="2">NO</th>
            <th scope="col" rowspan="2" class="text-center">
                JENIS PERATURAN
            </th>
            <th scope="col" rowspan="2">
                NOMOR DITETAPKAN
            </th>
            <th scope="col" rowspan="2">
                TANGGAL DITETAPKAN
            </th>
            <th scope="col" rowspan="2">
                TENTANG
            </th>
            <th scope="colgroup" colspan="2" class="text-center">
                DIUNDANGKAN
            </th>
            <th scope="colgroup" colspan="2" class="text-center">
                BERITA
            </th>
            <th scope="col" rowspan="2" class="text-center">KETERANGAN</th>
            <th scope="col" rowspan="2" class="text-center">Status</th>
            <th scope="col" rowspan="2" class="text-center">Aksi</th>

        </tr>
        <tr>
            <th scope="col">
                NOMOR
            </th>
            <th scope="col">
                TANGGAL
            </th>
            <th scope="col">
                NOMOR
            </th>
            <th scope="col">
                TANGGAL
            </th>
        </tr>
    </x-table>

    @push('footer')
        <script>
            $(document).ready(function() {
                const tableElement = $('#villagePublicationTable');

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
                        url: @json(route('village-publications.index')),
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

                        // 2. Jenis Peraturan
                        {
                            data: 'jenis_peraturan',
                            name: 'jenis_peraturan'
                        },

                        // 3. Nomor Ditetapkan
                        {
                            data: 'nomor_peraturan',
                            name: 'nomor_peraturan'
                        },

                        // 4. Tanggal Ditetapkan
                        {
                            data: 'tgl_ditetapkan',
                            name: 'tgl_ditetapkan'
                        },

                        // 5. Tentang
                        {
                            data: 'nama_peraturan',
                            name: 'nama_peraturan'
                        },

                        // 6. Nomor Diundangkan
                        {
                            data: 'nomor_diundang',
                            name: 'nomor_diundang',
                            defaultContent: '-'
                        },

                        // 7. Tanggal Diundangkan
                        {
                            data: 'tgl_diundang',
                            name: 'tgl_diundang',
                            defaultContent: '-'
                        },

                        // 8. Nomor Berita
                        {
                            data: 'nomor_berita',
                            name: 'nomor_berita',
                            defaultContent: '-'
                        },

                        // 9. Tanggal Berita
                        {
                            data: 'tgl_berita',
                            name: 'tgl_berita',
                            defaultContent: '-'
                        },

                        // 10. Keterangan
                        {
                            data: 'catatan',
                            name: 'catatan',
                            defaultContent: '-'
                        },

                        // 11. Status
                        {
                            data: 'status',
                            name: 'status'
                        },

                        // 12. Aksi
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
