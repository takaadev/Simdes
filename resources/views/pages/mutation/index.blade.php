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
            <th>TEMPAT</th>
            <th>TANGGAL</th>
            <th>DATANG DARI</th>
            <th>TANGGAL</th>
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
                // INISIALISASI TOOLTIP BOOTSTRAP 5
                // =====================================================
                function initTooltips(container) {
                    if (!window.bootstrap?.Tooltip || !container) return;

                    container.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((el) => {
                        bootstrap.Tooltip.getOrCreateInstance(el);
                    });
                }

                // =====================================================
                // AMBIL NILAI FILTER
                // =====================================================
                const filterNames = [
                    'jenis_kelamin',
                    'kewarganegaraan',
                    'kategori_mutasi',
                    'tipe_mutasi',
                    'deleted',
                    'sort_name'
                ];

                function getFilterValue(name) {
                    const el = $(`[name="${name}"], #filter_${name}, #${name}`).first();
                    return el.length ? el.val() : '';
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
                        type: 'GET',
                        data: function(d) {
                            filterNames.forEach((name) => {
                                const val = getFilterValue(name);
                                if (val !== undefined && val !== null && val !== '') {
                                    d[name] = val;
                                }
                            });
                        }
                    },

                    pageLength: 10,

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
                            name: 'kewarganegaraan',
                            className: 'text-center'
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
                            orderable: false,
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
                            orderable: false,
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
                            orderable: false,
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
                            render: (data) => (data ? data : '-')
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

                    // Biarkan default sorting dari backend (tgl_mutasi terbaru / sort_name)
                    order: [],

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
                        const tableNode = this.api().table().node();
                        wrapTableWithScroll(tableNode);
                        initTooltips(tableNode);
                    }
                });

                // Reload tabel saat filter berubah
                const filterSelector = filterNames
                    .map((name) => `[name="${name}"], #filter_${name}, #${name}`)
                    .join(', ');

                $(document).on('change', filterSelector, function() {
                    table.ajax.reload();
                });

                // Update widget summary otomatis dari response JSON
                tableElement.on('xhr.dt', function(e, settings, json) {
                    if (!json || !json.summary) return;

                    Object.entries(json.summary).forEach(([key, value]) => {
                        $(`[data-summary="${key}"]`).text(value);
                    });

                    $(document).trigger('mutation:summary', [json.summary]);
                });

                // Wrapper awal
                wrapTableWithScroll(table.table().node());
                $('#btnCetakMutasi').on('click', function() {
                    window.openSimdesPrintModal({
                        title: 'Buku Mutasi Penduduk Desa',
                        url: @json(route('mutations.print')),
                        filterMap: {
                            sort_name: '#filterSortName',
                            jenis_kelamin: '#filterGenderSelect',
                            kewarganegaraan: '#filterCitizenshipSelect',
                            tipe_mutasi: '#filterMutationTypeSelect',
                            kategori_mutasi: '#filterMutationCategorySelect',
                            deleted: '#filterDeletedSelect',
                        },
                        tableSelector: '#residentMutationTable',
                        paper: 'A4 landscape',
                    });
                });
            });
        </script>
    @endpush
</x-appcontent>