
<x-appcontent>
    @include('pages.resident.comp.widgets')
    @include('pages.resident.comp.filter')
    <x-table id="residentTable">
        <tr>
            <th>No.</th>
            <th>Nama Lengkap</th>
            <th>NIK</th>
            <th>No. KK</th>
            <th>Jenis Kelamin</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>Status Perkawinan</th>
            <th>Agama</th>
            <th>Pendidikan</th>
            <th>Pekerjaan</th>
            <th>Dapat Membaca</th>
            <th>Kewarganegaraan</th>
            <th>Kebangsaan</th>
            <th>Status dalam Keluarga</th>
            <th>Dusun</th>
            <th>RT</th>
            <th>RW</th>
            <th>Catatan</th>
            <th>Aksi</th>
        </tr>
    </x-table>

    @push('footer')
        <script>
            $(document).ready(function() {
                const tableElement = $('#residentTable');

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
                        url: @json(route('residents.index')),
                        type: 'GET'
                    },

                    pageLength: 15,

                    columns: [
                        // 1. Nomor urut
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            searchable: false,
                            orderable: false
                        },

                        // 2. Nama lengkap
                        {
                            data: 'nama_lengkap',
                            name: 'nama_lengkap'
                        },

                        // 3. NIK
                        {
                            data: 'nik',
                            name: 'nik',
                            className: 'text-start'
                        },

                        // 4. Nomor KK
                        {
                            data: 'kk',
                            name: 'kk',
                            className: 'text-start'
                        },

                        // 5. Jenis kelamin
                        {
                            data: 'jenis_kelamin',
                            name: 'jenis_kelamin',
                            className: 'text-center'
                        },

                        // 6. Tempat lahir
                        {
                            data: 'tempat_lahir',
                            name: 'tempat_lahir'
                        },

                        // 7. Tanggal lahir
                        {
                            data: 'tgl_lahir',
                            name: 'tgl_lahir',
                            className: 'text-center'
                        },

                        // 8. Status perkawinan
                        {
                            data: 'status_perkawinan',
                            name: 'status_perkawinan',
                            className: 'text-center'
                        },

                        // 9. Agama
                        {
                            data: 'agama',
                            name: 'agama'
                        },

                        // 10. Pendidikan
                        {
                            data: 'pendidikan',
                            name: 'pendidikan'
                        },

                        // 11. Pekerjaan
                        {
                            data: 'pekerjaan',
                            name: 'pekerjaan'
                        },

                        // 12. Dapat membaca
                        {
                            data: 'dapat_membaca',
                            name: 'dapat_membaca',
                            searchable: false,
                            className: 'text-center'
                        },

                        // 13. Kewarganegaraan
                        {
                            data: 'kewarganegaraan',
                            name: 'kewarganegaraan'
                        },

                        // 14. Kebangsaan
                        {
                            data: 'kebangsaan',
                            name: 'kebangsaan'
                        },

                        // 15. Status dalam keluarga
                        {
                            data: 'status_dalam_keluarga',
                            name: 'status_dalam_keluarga'
                        },

                        // 16. Dusun
                        {
                            data: 'dusun',
                            name: 'dusun.nama_dusun',
                            orderable: false
                        },

                        // 17. RT
                        {
                            data: 'rt',
                            name: 'rt',
                            className: 'text-center'
                        },

                        // 18. RW
                        {
                            data: 'rw',
                            name: 'rw',
                            className: 'text-center'
                        },

                        // 19. Catatan
                        {
                            data: 'catatan',
                            name: 'catatan',
                            orderable: false,
                            defaultContent: '-'
                        },

                        // 20. Aksi
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
                        emptyTable: 'Belum ada data penduduk',
                        zeroRecords: 'Data penduduk tidak ditemukan',
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
