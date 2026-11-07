<x-appcontent>
    <x-app-heading />
    @include('pages.villageofficial.comp.widgets')
    @include('pages.villageofficial.comp.filter')
    <x-table id="villageOfficialTable">
        <tr >
            <th>No.</th>
            <th>Nama Aparat Desa</th>
            <th>NIAP</th>
            <th>NIP</th>
            <th>Jenis Kelamin</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>Pangkat / Golongan</th>
            <th>Jabatan</th>
            <th>Pendidikan</th>
            <th>Nomor Pengangkatan</th>
            <th>Tanggal Pengangkatan</th>
            <th>Nomor Pemberhentian</th>
            <th>Tanggal Pemberhentian</th>
            <th>Alamat</th>
            <th>Telepon</th>
            <th>Status</th>
            <th>Catatan</th>
            <th>Aksi</th>
            </tr>
    </x-table>
@push('footer')
    <script>
        $(document).ready(function() {
            const tableElement = $('#villageOfficialTable');

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
                    url: @json(route('village-officials.index')),
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

                    // 2. Nama aparat desa
                    {
                        data: 'nama',
                        name: 'nama'
                    },

                    // 3. NIAP
                    {
                        data: 'niap',
                        name: 'niap',
                        className: 'text-center'
                    },

                    // 4. NIP
                    {
                        data: 'nip',
                        name: 'nip',
                        className: 'text-center'
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

                    // 8. Pangkat / Golongan
                    {
                        data: 'pangkat_golongan',
                        name: 'pangkat_golongan'
                    },

                    // 9. Jabatan
                    {
                        data: 'position',
                        name: 'position.nama'
                    },

                    // 10. Pendidikan
                    {
                        data: 'pendidikan',
                        name: 'pendidikan',
                        className: 'text-center'
                    },

                    // 11. Nomor pengangkatan
                    {
                        data: 'nomor_pengangkatan',
                        name: 'nomor_pengangkatan'
                    },

                    // 12. Tanggal pengangkatan
                    {
                        data: 'tgl_pengangkatan',
                        name: 'tgl_pengangkatan',
                        className: 'text-center'
                    },

                    // 13. Nomor pemberhentian
                    {
                        data: 'nomor_pemberhentian',
                        name: 'nomor_pemberhentian',
                        defaultContent: '-'
                    },

                    // 14. Tanggal pemberhentian
                    {
                        data: 'tgl_pemberhentian',
                        name: 'tgl_pemberhentian',
                        defaultContent: '-',
                        className: 'text-center'
                    },

                    // 15. Alamat
                    {
                        data: 'alamat',
                        name: 'alamat',
                        orderable: false
                    },

                    // 16. Nomor telepon
                    {
                        data: 'telpon',
                        name: 'telpon'
                    },

                    // 17. Status
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center'
                    },

                    // 18. Catatan
                    {
                        data: 'notes',
                        name: 'notes',
                        orderable: false,
                        defaultContent: '-'
                    },

                    // 19. Aksi
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],

                // Urutkan berdasarkan nama aparat desa
                order: [
                    [1, 'asc']
                ],

                // =================================================
                // LANGUAGE
                // =================================================
                language: {
                    emptyTable: 'Belum ada data aparat pemerintah desa',
                    zeroRecords: 'Data aparat pemerintah desa tidak ditemukan',
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
