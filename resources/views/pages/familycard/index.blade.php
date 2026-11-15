<x-appcontent>
    @include('pages.familycard.comp.widgets')
    @include('pages.familycard.comp.filter')

    <x-table id="familCardTable">
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
            #familCardTable tbody td[rowspan] {
                vertical-align: middle !important;
                text-align: center;
                font-weight: 600;
                background-color: #fff;
            }

            #familCardTable tbody tr {
                vertical-align: middle;
            }

            #familCardTable tbody td {
                white-space: nowrap;
            }
        </style>
    @endpush

    @push('footer')
        <script>
            $(document).ready(function() {
                const tableElement = $('#familCardTable');

                if (!tableElement.length) return;

                // =====================================================
                // WRAPPER HORIZONTAL SCROLL
                // =====================================================
                function wrapTableWithScroll(tableNode) {
                    if (!tableNode) return;

                    const parent = tableNode.parentElement;

                    if (
                        parent &&
                        !parent.classList.contains('wide-scroll-ledger')
                    ) {
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
                        url: @json(route('familycards.index')),
                        type: 'GET'
                    },

                    pageLength: 15,
                    ordering: false,

                    // =================================================
                    // COLUMNS
                    // =================================================
                    columns: [
                        // 1. Nomor urut.
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            searchable: false,
                            orderable: false,
                            className: 'text-center'
                        },

                        // 2. Nomor KK.
                        {
                            data: 'nomor_kk',
                            name: 'family_cards.nomor_kk',
                            orderable: false,
                            className: 'text-center'
                        },

                        // 3. Nama lengkap.
                        {
                            data: 'nama_lengkap',
                            name: 'residents.nama_lengkap'
                        },

                        // 4. NIK.
                        {
                            data: 'nik',
                            name: 'residents.nik'
                        },

                        // 5. Jenis kelamin.
                        {
                            data: 'jenis_kelamin',
                            name: 'residents.jenis_kelamin',
                            className: 'text-center'
                        },

                        // 6. Tempat lahir.
                        {
                            data: 'tempat_lahir',
                            name: 'residents.tempat_lahir'
                        },

                        // 7. Tanggal lahir.
                        {
                            data: 'tgl_lahir',
                            name: 'residents.tgl_lahir',
                            className: 'text-center'
                        },

                        // 8. Golongan darah.
                        {
                            data: 'gol_darah',
                            name: 'residents.gol_darah',
                            className: 'text-center'
                        },

                        // 9. Agama.
                        {
                            data: 'agama',
                            name: 'residents.agama'
                        },

                        // 10. Pendidikan.
                        {
                            data: 'pendidikan',
                            name: 'residents.pendidikan'
                        },

                        // 11. Pekerjaan.
                        {
                            data: 'pekerjaan',
                            name: 'residents.pekerjaan'
                        },

                        // 12. Alamat.
                        {
                            data: 'alamat',
                            name: 'residents.alamat',
                            orderable: false
                        },

                        // 13. Status perkawinan.
                        {
                            data: 'status_perkawinan',
                            name: 'residents.status_perkawinan'
                        },

                        // 14. Tempat dikeluarkan.
                        {
                            data: 'tempat_dikeluarkan',
                            name: 'residents.tempat_dikeluarkan'
                        },

                        // 15. Tanggal dikeluarkan.
                        {
                            data: 'tgl_dikeluarkan',
                            name: 'residents.tgl_dikeluarkan',
                            className: 'text-center'
                        },

                        // 16. Kedudukan dalam keluarga.
                        {
                            data: 'status_dalam_keluarga',
                            name: 'residents.status_dalam_keluarga'
                        },

                        // 17. Kewarganegaraan.
                        {
                            data: 'kewarganegaraan',
                            name: 'residents.kewarganegaraan'
                        },

                        // 18. Ayah.
                        {
                            data: 'ayah',
                            name: 'residents.ayah'
                        },

                        // 19. Ibu.
                        {
                            data: 'ibu',
                            name: 'residents.ibu'
                        },

                        // 20. Mulai tinggal.
                        {
                            data: 'tgl_mulai_tinggal',
                            name: 'residents.tgl_mulai_tinggal',
                            className: 'text-center'
                        },

                        // 21. Aksi.
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ],

                    // =================================================
                    // DEFAULT ORDER
                    // =================================================
                    order: [],

                    // =================================================
                    // LANGUAGE
                    // =================================================
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
                        const api = this.api();

                        wrapTableWithScroll(api.table().node());

                        const rows = api.rows({
                            page: 'current',
                            order: 'applied',
                            search: 'applied'
                        });

                        const data = rows.data().toArray();
                        const nodes = rows.nodes().toArray();

                        let groupNumber = 0;
                        let i = 0;

                        // =================================================
                        // PENGELOMPOKAN BERDASARKAN NOMOR KK
                        // =================================================
                        while (i < data.length) {
                            const currentKK = data[i].nomor_kk;
                            let count = 1;

                            // Hitung jumlah anggota dalam satu KK.
                            while (
                                i + count < data.length &&
                                data[i + count].nomor_kk === currentKK
                            ) {
                                count++;
                            }

                            groupNumber++;

                            const firstRow = $(nodes[i]);
                            const firstCells = firstRow.children('td');

                            // Gabungkan kolom nomor urut.
                            firstCells.eq(0)
                                .text(groupNumber)
                                .attr('rowspan', count)
                                .addClass('text-center align-middle fw-semibold');

                            // Gabungkan kolom nomor KK.
                            firstCells.eq(1)
                                .attr('rowspan', count)
                                .addClass('text-center align-middle fw-semibold');

                            // Hapus dua kolom pertama dari anggota berikutnya.
                            for (let j = 1; j < count; j++) {
                                const memberCells = $(nodes[i + j]).children('td');

                                // Hapus sekaligus agar indeks tidak bermasalah.
                                memberCells.slice(0, 2).remove();
                            }

                            i += count;
                        }
                    }

                });

                // Wrapper awal.
                wrapTableWithScroll(table.table().node());
            });
        </script>
    @endpush
</x-appcontent>
