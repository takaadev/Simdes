
<x-appcontent>
    @include('pages.mutation.comp.widgets')
    @include('pages.mutation.comp.filter')

    <x-table id="residentMutationTable">
        <tr>
            <th rowspan="2">No.</th>
            <th rowspan="2">NAMA LENGKAP</th>
            <th colspan="2">TEMPAT &amp; TANGGAL LAHIR</th>
            <th rowspan="2">JENIS KELAMIN</th>
            <th rowspan="2">KEWARGANEGARAAN</th>
            <th rowspan="2">KEBANGSAAN</th>
            <th colspan="2">PENAMBAHAN</th>
            <th colspan="4">PENGURANGAN</th>
            <th rowspan="2">KETERANGAN</th>
            <th rowspan="2">AKSI</th>
        </tr>
        <tr>
            {{-- Identitas --}}
            <th>TEMPAT</th>
            <th>TANGGAL</th>

            {{-- Penambahan --}}
            <th>DATANG DARI</th>
            <th>TANGGAL</th>

            {{-- Pengurangan --}}
            <th>PINDAH KE</th>
            <th>TANGGAL</th>
            <th>MENINGGAL</th>
            <th>TANGGAL</th>
        </tr>
    </x-table>

    @push('footer')
        <script>
            $(document).ready(function() {
                const tableElement = $('#residentMutationTable');

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
                        url: @json(route('mutations.index')),
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

                        // 3. Tempat lahir
                        {
                            data: 'tempat_lahir',
                            name: 'tempat_lahir'
                        },

                        // 4. Tanggal lahir
                        {
                            data: 'tgl_lahir',
                            name: 'tgl_lahir',
                            className: 'text-center'
                        },

                        // 5. Jenis kelamin
                        {
                            data: 'jenis_kelamin',
                            name: 'jenis_kelamin',
                            className: 'text-center'
                        },

                        // 6. Kewarganegaraan
                        {
                            data: 'kewarganegaraan',
                            name: 'kewarganegaraan'
                        },

                        // 7. Kebangsaan
                        {
                            data: 'kebangsaan',
                            name: 'kebangsaan'
                        },

                        // 8. Datang dari (Penambahan)
                        {
                            data: 'datang_dari',
                            name: 'datang_dari',
                            defaultContent: '-'
                        },

                        // 9. Tanggal penambahan
                        {
                            data: 'tanggal_penambahan',
                            name: 'tanggal_penambahan',
                            className: 'text-center',
                            defaultContent: '-'
                        },

                        // 10. Pindah ke (Pengurangan)
                        {
                            data: 'pindah_ke',
                            name: 'pindah_ke',
                            defaultContent: '-'
                        },

                        // 11. Tanggal pindah
                        {
                            data: 'tanggal_pindah',
                            name: 'tanggal_pindah',
                            className: 'text-center',
                            defaultContent: '-'
                        },

                        // 12. Meninggal
                        {
                            data: 'meninggal',
                            name: 'meninggal',
                            defaultContent: '-'
                        },

                        // 13. Tanggal meninggal
                        {
                            data: 'tanggal_meninggal',
                            name: 'tanggal_meninggal',
                            className: 'text-center',
                            defaultContent: '-'
                        },

                        // 14. Keterangan
                        {
                            data: 'catatan',
                            name: 'catatan',
                            orderable: false,
                            defaultContent: '-'
                        },

                        // 15. Aksi
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ],

                    order: [
                        [1, 'asc']
                    ],

                    // =================================================
                    // LANGUAGE
                    // =================================================
                    language: {
                        emptyTable: 'Belum ada data mutasi penduduk',
                        zeroRecords: 'Data mutasi penduduk tidak ditemukan',
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
