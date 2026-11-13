<x-appcontent>
    @include('pages.resident.comp.widgets')
    @include('pages.resident.comp.filter')

    <x-table id="residentTable">
        <tr>
            <th>No.</th>
            <th>Nama Lengkap</th>
            <th>Jenis Kelamin</th>
            <th>Status Perkawinan</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>Agama</th>
            <th>Pendidikan</th>
            <th>Pekerjaan</th>
            <th>Dapat Membaca Huruf</th>
            <th>Kewarganegaraan</th>
            <th>Kedudukan dalam Keluarga</th>
            <th>NIK</th>
            <th>Nomor KK</th>
            <th>Alamat</th>
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
                        // 1. Nomor urut.
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            searchable: false,
                            orderable: false,
                            className: 'text-center'
                        },

                        // 2. Nama lengkap.
                        {
                            data: 'nama_lengkap',
                            name: 'nama_lengkap'
                        },

                        // 3. Jenis kelamin.
                        {
                            data: 'jenis_kelamin',
                            name: 'jenis_kelamin',
                            className: 'text-center'
                        },

                        // 4. Status perkawinan.
                        {
                            data: 'status_perkawinan',
                            name: 'status_perkawinan'
                        },

                        // 5. Tempat lahir.
                        {
                            data: 'tempat_lahir',
                            name: 'tempat_lahir'
                        },

                        // 6. Tanggal lahir.
                        {
                            data: 'tgl_lahir',
                            name: 'tgl_lahir',
                            className: 'text-center'
                        },

                        // 7. Agama.
                        {
                            data: 'agama',
                            name: 'agama'
                        },

                        // 8. Pendidikan.
                        {
                            data: 'pendidikan',
                            name: 'pendidikan'
                        },

                        // 9. Pekerjaan.
                        {
                            data: 'pekerjaan',
                            name: 'pekerjaan'
                        },

                        // 10. Dapat membaca huruf.
                        {
                            data: 'dapat_membaca',
                            name: 'dapat_membaca',
                            searchable: false,
                            className: 'text-center'
                        },

                        // 11. Kewarganegaraan.
                        {
                            data: 'kewarganegaraan',
                            name: 'kewarganegaraan'
                        },

                        // 12. Kedudukan dalam keluarga.
                        {
                            data: 'status_dalam_keluarga',
                            name: 'status_dalam_keluarga'
                        },

                        // 13. NIK.
                        {
                            data: 'nik',
                            name: 'nik'
                        },

                        // 14. Nomor KK.
                        {
                            data: 'kk',
                            name: 'kk'
                        },

                        // 15. Alamat beserta RT dan RW.
                        {
                            data: 'alamat',
                            name: 'alamat',
                            orderable: false
                        },

                        // 16. Aksi.
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ],

                    // Urutkan berdasarkan nama lengkap.
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

                // Wrapper awal.
                wrapTableWithScroll(table.table().node());
            });
        </script>
    @endpush
</x-appcontent>