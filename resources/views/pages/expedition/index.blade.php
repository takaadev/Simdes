<x-appcontent>
    {{-- @include('pages.expedition.comp.widgets') --}}
    @include('pages.expedition.comp.filter')
    <x-table id="expeditionEntriesTable">
        <tr>
            <th scope="col">NO.</th>
            <th scope="col">TANGGAL PENGIRIMAN</th>
            <th scope="col">TANGGAL SURAT</th>
            <th scope="col">NOMOR SURAT</th>
            <th scope="col">ISI SINGKAT SURAT YANG DIKIRIM</th>
            <th scope="col">DITUJUKAN KEPADA</th>
            <th scope="col">DOKUMEN SURAT</th>
            <th scope="col">KETERANGAN</th>
            <th scope="col">AKSI</th>
        </tr>
    </x-table>

    @push('footer')
        <script>
            $(document).ready(function() {
                const tableElement = $('#expeditionEntriesTable');
                if (!tableElement.length) return;
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
                        url: @json(route('expeditions.index')),
                        type: 'GET',
                    },

                    pageLength: 10,

                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            searchable: false,
                            orderable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'tanggal_kirim',
                            name: 'tanggal_kirim',
                            searchable: false
                        },
                        {
                            data: 'tgl_surat',
                            name: 'tgl_surat',
                            searchable: false
                        },
                        {
                            data: 'nomor_surat',
                            name: 'nomor_surat'
                        },
                        {
                            data: 'isi_surat',
                            name: 'isi_surat'
                        },
                        {
                            data: 'penerima',
                            name: 'penerima'
                        },
                        {
                            data: 'dokumen',
                            name: 'dokumen',
                            searchable: false,
                            orderable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'catatan',
                            name: 'catatan'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            searchable: false,
                            orderable: false,
                            className: 'text-center'
                        }
                    ],

                    // =====================================================
                    // DEFAULT ORDER
                    // =====================================================
                    order: [],

                    // =====================================================
                    // LANGUAGE
                    // =====================================================
                    language: {
                        emptyTable: 'Belum ada data Buku Ekspedisi',
                        zeroRecords: 'Data Buku Ekspedisi tidak ditemukan',
                        processing: 'Memproses data...',
                        info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                        infoEmpty: 'Menampilkan 0 data',
                        infoFiltered: '(difilter dari _MAX_ total data)',
                        lengthMenu: 'Tampilkan _MENU_ data',
                        search: 'Pencarian:',
                        paginate: {
                            first: 'Pertama',
                            last: 'Terakhir',
                            next: 'Berikutnya',
                            previous: 'Sebelumnya'
                        }
                    },

                    // =====================================================
                    // LAYOUT
                    // =====================================================
                    layout: {
                        topStart: 'pageLength',
                        topEnd: 'search',
                        bottomStart: 'info',
                        bottomEnd: 'paging'
                    },

                    // =====================================================
                    // DRAW CALLBACK
                    // =====================================================
                    drawCallback: function() {
                        wrapTableWithScroll(table.table().node());
                    }
                });
                wrapTableWithScroll(table.table().node());
            });
        </script>
    @endpush
</x-appcontent>
