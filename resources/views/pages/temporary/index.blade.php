<x-appcontent>
    @include('pages.temporary.comp.widgets')
    @include('pages.temporary.comp.filter')

    <x-table id="temporaryResidentTable">
        <tr>
            <th rowspan="2">No.</th>
            <th rowspan="2">NAMA LENGKAP</th>
            <th rowspan="2">JENIS KELAMIN</th>
            <th rowspan="2">NO. IDENTITAS</th>
            <th colspan="3">TEMPAT, TANGGAL LAHIR &amp; UMUR</th>
            <th rowspan="2">PEKERJAAN</th>
            <th colspan="3">KEWARGANEGARAAN</th>
            <th rowspan="2">DATANG DARI</th>
            <th rowspan="2">TUJUAN</th>
            <th rowspan="2">DATANG TANGGAL</th>
            <th rowspan="2">STATUS</th>
            <th rowspan="2">PERGI TANGGAL</th>
            <th rowspan="2">KETERANGAN</th>
            <th rowspan="2">AKSI</th>
        </tr>
        <tr>
            {{-- Identitas --}}
            <th>TEMPAT</th>
            <th>TANGGAL</th>
            <th>UMUR</th>

            {{-- Kewarganegaraan --}}
            <th>WARGA</th>
            <th>KEBANGSAAN</th>
            <th>KETURUNAN</th>
        </tr>
    </x-table>

    @push('footer')
        <script>
            $(document).ready(function() {
                const tableElement = $('#temporaryResidentTable');

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
                        url: @json(route('temporaryresidents.index')),
                        type: 'GET'
                    },

                    pageLength: 15,

                    columns: [
                        // 1. Nomor urut
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            searchable: false,
                            orderable: false,
                            className: 'text-center'
                        },

                        // 2. Nama lengkap
                        {
                            data: 'nama_lengkap',
                            name: 'nama_lengkap'
                        },

                        // 3. Jenis kelamin
                        {
                            data: 'jenis_kelamin',
                            name: 'jenis_kelamin',
                            className: 'text-center'
                        },

                        // 4. Nomor identitas
                        {
                            data: 'nomor_identitas',
                            name: 'nomor_identitas',
                        },

                        // 5. Tempat lahir
                        {
                            data: 'tempat_lahir',
                            name: 'tempat_lahir'
                        },

                        // 6. Tanggal lahir
                        {
                            data: 'tgl_lahir',
                            name: 'tgl_lahir',
                            className: 'text-center'
                        },

                        // 7. Umur
                        {
                            data: 'umur',
                            name: 'umur',
                            searchable: false,
                            className: 'text-center'
                        },

                        // 8. Pekerjaan
                        {
                            data: 'pekerjaan',
                            name: 'pekerjaan'
                        },

                        // 9. Kewarganegaraan
                        {
                            data: 'kewarganegaraan',
                            name: 'kewarganegaraan',
                            className: 'text-center'
                        },

                        // 10. Kebangsaan
                        {
                            data: 'kebangsaan',
                            name: 'kebangsaan'
                        },

                        // 11. Keturunan
                        {
                            data: 'keturunan',
                            name: 'keturunan'
                        },

                        // 12. Datang dari
                        {
                            data: 'datang_dari',
                            name: 'datang_dari'
                        },

                        // 13. Tujuan kedatangan
                        {
                            data: 'tujuan_kedatangan',
                            name: 'tujuan_kedatangan'
                        },

                        // 14. Tanggal datang
                        {
                            data: 'tgl_datang',
                            name: 'tgl_datang',
                            className: 'text-center'
                        },
                        {
                            data: 'status_tinggal',
                            name: 'status_tinggal',
                            className: 'text-center'
                        },
                        // 15. Tanggal pergi
                        {
                            data: 'tgl_pergi',
                            name: 'tgl_pergi',
                            className: 'text-center'
                        },

                        // 16. Keterangan
                        {
                            data: 'catatan',
                            name: 'catatan',
                            orderable: false,
                            defaultContent: '-'
                        },

                        // 17. Aksi
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ],

                    // Urutkan berdasarkan nama lengkap
                    order: [
                        [1, 'asc']
                    ],

                    // =================================================
                    // LANGUAGE
                    // =================================================
                    language: {
                        emptyTable: 'Belum ada data penduduk sementara',
                        zeroRecords: 'Data penduduk sementara tidak ditemukan',
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

                    lengthMenu: [10, 15, 25, 50, 100, 150, 200],

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
