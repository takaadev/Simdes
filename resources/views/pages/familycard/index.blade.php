<x-appcontent>
    @include('pages.familycard.comp.widgets')
    @include('pages.familycard.comp.filter')

    <x-table id="familyCardTable">
        <tr>
            <th rowspan="2">No.</th>
            <th rowspan="2">Nomor KK</th>
            <th rowspan="2">Nama Lengkap</th>
            <th rowspan="2">NIK</th>
            <th rowspan="2">Jenis Kelamin</th>
            <th colspan="2">TEMPAT &amp; TANGGAL LAHIR</th>
            <th rowspan="2">GOL. DARAH</th>
            <th rowspan="2">Agama</th>
            <th rowspan="2">Pendidikan</th>
            <th rowspan="2">Pekerjaan</th>
            <th rowspan="2">Alamat</th>
            <th rowspan="2">Status Perkawinan</th>
            <th colspan="2">TEMPAT &amp; TANGGAL DIKELUARKAN E-KTP</th>
            <th rowspan="2">Kedudukan dalam Keluarga</th>
            <th rowspan="2">Kewarganegaraan</th>
            <th colspan="2">ORANG TUA</th>
            <th rowspan="2">MULAI TINGGAL</th>
            <th rowspan="2">Aksi</th>
        </tr>
        <tr>
            <th>Tempat</th>
            <th>Tanggal</th>
            <th>Tempat</th>
            <th>Tanggal</th>
            <th>Ayah</th>
            <th>Ibu</th>
        </tr>
    </x-table>

    @push('header')
        <style>
            #familyCardTable tbody td[rowspan] {
                vertical-align: middle !important;
                text-align: center;
                font-weight: 600;
                background-color: #fff;
            }

            #familyCardTable tbody tr {
                vertical-align: middle;
            }

            #familyCardTable tbody td {
                white-space: nowrap;
            }
        </style>
    @endpush

    @push('footer')
        <script>
            $(document).ready(function() {
                const tableElement = $('#familyCardTable');

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

                function initTooltips(container) {
                    if (!window.bootstrap?.Tooltip || !container) return;

                    container.querySelectorAll('[data-bs-toggle="tooltip"]').forEach((el) => {
                        bootstrap.Tooltip.getOrCreateInstance(el);
                    });
                }

                const filterMap = {
                    sort_date: '#filterSortDate',
                    year: '#filterYearSelect',
                    deleted: '#filterDeletedSelect',
                };

                const table = tableElement.DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: false,
                    autoWidth: false,
                    responsive: false,

                    ajax: {
                        url: @json(route('familycards.index')),
                        type: 'GET',
                        data: function(d, settings) {
                            const rawUrl = typeof settings?.ajax === 'string' ?
                                settings.ajax :
                                (settings?.ajax?.url || @json(route('familycards.index')));
                            const currentUrl = new URL(rawUrl, window.location.origin);

                            Object.entries(filterMap).forEach(([param, selector]) => {
                                if (currentUrl.searchParams.has(param)) return;

                                const val = $(selector).val();
                                if (val !== undefined && val !== null && val !== '' && val !==
                                    'all') {
                                    d[param] = val;
                                }
                            });
                        }
                    },

                    pageLength: 15,
                    ordering: false,

                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            searchable: false,
                            orderable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'nomor_kk',
                            name: 'family_cards.nomor_kk',
                            orderable: false,
                            className: 'text-center'
                        },
                        {
                            data: 'nama_lengkap',
                            name: 'residents.nama_lengkap'
                        },
                        {
                            data: 'nik',
                            name: 'residents.nik'
                        },
                        {
                            data: 'jenis_kelamin',
                            name: 'residents.jenis_kelamin',
                            className: 'text-center'
                        },
                        {
                            data: 'tempat_lahir',
                            name: 'residents.tempat_lahir'
                        },
                        {
                            data: 'tgl_lahir',
                            name: 'residents.tgl_lahir',
                            className: 'text-center'
                        },
                        {
                            data: 'gol_darah',
                            name: 'residents.gol_darah',
                            className: 'text-center'
                        },
                        {
                            data: 'agama',
                            name: 'residents.agama'
                        },
                        {
                            data: 'pendidikan',
                            name: 'residents.pendidikan'
                        },
                        {
                            data: 'pekerjaan',
                            name: 'residents.pekerjaan'
                        },
                        {
                            data: 'alamat',
                            name: 'residents.alamat',
                            orderable: false
                        },
                        {
                            data: 'status_perkawinan',
                            name: 'residents.status_perkawinan'
                        },
                        {
                            data: 'tempat_dikeluarkan',
                            name: 'residents.tempat_dikeluarkan'
                        },
                        {
                            data: 'tgl_dikeluarkan',
                            name: 'residents.tgl_dikeluarkan',
                            className: 'text-center'
                        },
                        {
                            data: 'status_dalam_keluarga',
                            name: 'residents.status_dalam_keluarga'
                        },
                        {
                            data: 'kewarganegaraan',
                            name: 'residents.kewarganegaraan'
                        },
                        {
                            data: 'ayah',
                            name: 'residents.ayah'
                        },
                        {
                            data: 'ibu',
                            name: 'residents.ibu'
                        },
                        {
                            data: 'tgl_mulai_tinggal',
                            name: 'residents.tgl_mulai_tinggal',
                            className: 'text-center'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ],

                    order: [],

                    language: {
                        emptyTable: 'Belum ada data Kartu Keluarga',
                        zeroRecords: 'Data Kartu Keluarga tidak ditemukan',
                        processing: 'Memproses data...',
                        info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                        infoEmpty: 'Menampilkan 0 data',
                        infoFiltered: '(difilter dari _MAX_ total data)',
                        lengthMenu: 'Tampilkan _MENU_ data',
                        search: 'Pencarian:'
                    },

                    layout: {
                        topStart: 'pageLength',
                        topEnd: 'search',
                        bottomStart: 'info',
                        bottomEnd: 'paging'
                    },

                    lengthMenu: [10, 15, 25, 50, 100, 150, 200],

                    drawCallback: function() {
                        const api = this.api();
                        const tableNode = api.table().node();

                        wrapTableWithScroll(tableNode);
                        initTooltips(tableNode);

                        const rows = api.rows({
                            page: 'current'
                        });
                        const data = rows.data().toArray();
                        const nodes = rows.nodes().toArray();

                        let groupNumber = api.page.info().start;
                        let i = 0;

                        while (i < data.length) {
                            const currentCardId = data[i].family_card_id;
                            let count = 1;

                            while (
                                i + count < data.length &&
                                data[i + count].family_card_id === currentCardId
                            ) {
                                count++;
                            }

                            groupNumber++;

                            const firstCells = $(nodes[i]).children('td');

                            firstCells.eq(0)
                                .text(groupNumber)
                                .attr('rowspan', count)
                                .addClass('text-center align-middle fw-semibold');

                            firstCells.eq(1)
                                .attr('rowspan', count)
                                .addClass('text-center align-middle fw-semibold');

                            firstCells.last()
                                .attr('rowspan', count)
                                .addClass('text-center align-middle');

                            for (let j = 1; j < count; j++) {
                                const memberCells = $(nodes[i + j]).children('td');
                                memberCells.last().remove();
                                memberCells.slice(0, 2).remove();
                            }

                            i += count;
                        }
                    }
                });

                wrapTableWithScroll(table.table().node());

                $('#btnCetakFamilyCard').on('click', function() {
                    window.openSimdesPrintModal({
                        title: 'Buku KTP dan Kartu Keluarga',
                        url: @json(route('familycards.print')),
                        filterMap: filterMap,
                        tableSelector: '#familyCardTable',
                        paper: '330mm 215mm',
                    });
                });
            });
        </script>
    @endpush
</x-appcontent>
