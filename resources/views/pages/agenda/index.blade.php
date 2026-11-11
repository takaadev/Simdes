<x-appcontent>
    @include('pages.agenda.comp.widgets')
    @include('pages.agenda.comp.filter')
    <x-table id="agendaEntriesTable">
        <tr>
            <th scope="col" rowspan="2">
                NO.
            </th>
            <th scope="col" rowspan="2" class="text-center">
                TANGGAL PENERIMAAN
            </th>
            <th scope="colgroup" colspan="3" class="text-center">
                SURAT MASUK
            </th>
            {{-- <th scope="col" rowspan="2">
                KET
            </th> --}}
            <th scope="col" rowspan="2">
                Aksi
            </th>
            <th scope="col" rowspan="2">
                TANGGAL PENGIRIMAN
            </th>
            <th scope="colgroup" colspan="3" class="text-center">
                SURAT KELUAR
            </th>
            {{-- <th scope="col" rowspan="2">
                KET
            </th> --}}
            <th scope="col" rowspan="2">
                Aksi
            </th>
        </tr>
        <tr class="">
            <!-- Subkolom SURAT MASUK (Kolom 3 - 6) -->
            <th scope="col">
                NOMOR
            </th>
            <th scope="col">
                Tanggal
            </th>
            <th scope="col">
                PENGIRIM
            </th>
            <!-- Subkolom SURAT KELUAR (Kolom 7 - 10) -->
            <th scope="col">
                NOMOR
            </th>
            <th scope="col">
                TANGGAL
            </th>
            <th scope="col">
                TUJUAN
            </th>
        </tr>
    </x-table>


    @push('footer')
        <script>
            $(document).ready(function() {
                const tableElement = $('#agendaEntriesTable');

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
                        url: @json(route('agendas.index')),
                        type: 'GET',
                    },

                    pageLength: 10,

                    columns: [
                        // =================================================
                        // NOMOR URUT
                        // =================================================
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            searchable: false,
                            orderable: false,
                            className: 'text-center'
                        },

                        // =================================================
                        // SURAT MASUK - INCOMING
                        // =================================================

                        // Tanggal Penerimaan
                        {
                            data: 'incoming_tanggal_terima',
                            name: 'tanggal_terima',
                            searchable: false,
                        },

                        // Nomor Surat
                        {
                            data: 'incoming_nomor',
                            name: 'incoming_nomor',
                            searchable: true
                        },

                        // Tanggal Surat
                        {
                            data: 'incoming_tanggal',
                            name: 'incoming_tanggal',
                            searchable: false,
                        },

                        // Pengirim
                        {
                            data: 'incoming_pengirim',
                            name: 'pengirim',
                            searchable: false
                        },

                        // Keterangan Surat Masuk (opsional)
                        // {
                        //     data: 'incoming_ket',
                        //     name: 'incoming_ket',
                        //     searchable: false,
                        //     orderable: false
                        // },

                        // Aksi Surat Masuk
                        {
                            data: 'incoming_action',
                            name: 'incoming_action',
                            searchable: false,
                            orderable: false,
                            className: 'text-center'
                        },

                        // =================================================
                        // SURAT KELUAR - OUTGOING
                        // =================================================

                        // Tanggal Pengiriman
                        {
                            data: 'outgoing_tanggal_kirim',
                            name: 'tanggal_kirim',
                            searchable: false,
                        },

                        // Nomor Surat
                        {
                            data: 'outgoing_nomor',
                            name: 'outgoing_nomor',
                            searchable: true
                        },

                        // Tanggal Surat
                        {
                            data: 'outgoing_tanggal',
                            name: 'outgoing_tanggal',
                            searchable: false,
                        },

                        // Tujuan
                        {
                            data: 'outgoing_tujuan',
                            name: 'penerima',
                            searchable: false
                        },

                        // Keterangan Surat Keluar (opsional)
                        // {
                        //     data: 'outgoing_ket',
                        //     name: 'outgoing_ket',
                        //     searchable: false,
                        //     orderable: false
                        // },

                        // Aksi Surat Keluar
                        {
                            data: 'outgoing_action',
                            name: 'outgoing_action',
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
                        emptyTable: 'Belum ada data agenda surat',
                        zeroRecords: 'Data agenda tidak ditemukan',
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

                // Wrapper awal
                wrapTableWithScroll(table.table().node());
            });
        </script>
    @endpush

</x-appcontent>
