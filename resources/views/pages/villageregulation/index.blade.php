<x-appcontent>
    <x-app-heading />
    @include('pages.villageregulation.comp.widgets')
    @include('pages.villageregulation.comp.filter')
    <x-table>
        <tr class="text-center">
            <th style="width: 20px;">
                No.
            </th>
            <th style="min-width: 200px;">
                Jenis Peraturan
            </th>
            <th style="min-width: 180px;">
                Nomor Peraturan
            </th>
            <th style="min-width: 200px;">
                Tanggal Ditetapkan
            </th>
            <th style="min-width: 200px;">
                Tentang
            </th>
            <th style="min-width: 250px;">
                Uraian
            </th>
            <th style="min-width: 220px;">
                Tanggal Kesepakatan
            </th>
            <th style="min-width: 220px;">
                Nomor Dilaporkan
            </th>
            <th style="min-width: 220px;">
                Tanggal Dilaporkan
            </th>
            <th style="min-width: 220px;">
                Nomor Diundang
            </th>
            <th style="min-width: 220px;">
                Tanggal Diundang
            </th>
            <th style="min-width: 220px;">
                Nomor Berita Desa
            </th>
            <th style="min-width: 220px;">
                Tanggal Berita Desa
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
                const tableElement = $('#simdesDataTable');

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
                        url: @json(route('village-regulations.index')),
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

                        // 3. Nomor Peraturan
                        {
                            data: 'nomor_peraturan',
                            name: 'nomor_peraturan'
                        },

                        // 4. Tanggal Ditetapkan
                        {
                            data: 'tgl_ditetapkan',
                            name: 'tgl_ditetapkan'
                        },

                        // 5. Nama Peraturan
                        {
                            data: 'nama_peraturan',
                            name: 'nama_peraturan'
                        },

                        // 6. Uraian
                        {
                            data: 'uraian',
                            name: 'uraian',
                            defaultContent: '-'
                        },

                        // 7. Tanggal Kesepakatan
                        {
                            data: 'tgl_kesepakatan',
                            name: 'tgl_kesepakatan',
                            defaultContent: '-'
                        },

                        // 8. Nomor Dilaporkan
                        {
                            data: 'nomor_dilaporkan',
                            name: 'nomor_dilaporkan',
                            defaultContent: '-'
                        },

                        // 9. Tanggal Dilaporkan
                        {
                            data: 'tgl_dilaporkan',
                            name: 'tgl_dilaporkan',
                            defaultContent: '-'
                        },

                        // 10. Nomor Diundang
                        {
                            data: 'nomor_diundang',
                            name: 'nomor_diundang',
                            defaultContent: '-'
                        },

                        // 11. Tanggal Diundang
                        {
                            data: 'tgl_diundang',
                            name: 'tgl_diundang',
                            defaultContent: '-'
                        },

                        // 12. Nomor Berita Desa
                        {
                            data: 'nomor_berita',
                            name: 'nomor_berita',
                            defaultContent: '-'
                        },

                        // 13. Tanggal Berita Desa
                        {
                            data: 'tgl_berita',
                            name: 'tgl_berita',
                            defaultContent: '-'
                        },

                        // 14. Dokumen
                        {
                            data: 'dokumen',
                            name: 'dokumen',
                            defaultContent: '-'
                        },

                        // 15. Keterangan
                        {
                            data: 'catatan',
                            name: 'catatan',
                            defaultContent: '-'
                        },

                        // 16. Status
                        {
                            data: 'status',
                            name: 'status'
                        },

                        // 17. Aksi
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
