<x-appcontent>
    @include('pages.villagetreasuryland.comp.widgets')
    @include('pages.villagetreasuryland.comp.filter')

    <x-table id="villageTreasuryLandTable">
        <tr>
            <th rowspan="3" scope="col">No.</th>

            <th rowspan="3" >
                Nomor Sertifikat 
            </th>

            <th rowspan="3">
                Asal Tanah
            </th>

            <th rowspan="3" scope="col" class="text-center">
                Kelas
            </th>

            <th rowspan="3" scope="col" >
                L.T (m²)
            </th>

            <th colspan="5" class="text-center">
                Perolehan Tanah Kas Desa (m²)
            </th>

            <th rowspan="3" scope="col" style="min-width:130px;" class="text-center">
                Tanggal Perolehan
            </th>

            <th colspan="5" class="text-center">
                Jenis Tanah Kas Desa (m²)
            </th>

            <th colspan="2" class="text-center">
                Patok Tanda Batas
            </th>

            <th colspan="2" class="text-center">
                Papan Nama
            </th>

            <th rowspan="3" scope="col" style="min-width:180px;" class="text-center">
                Lokasi
            </th>

            <th rowspan="3" scope="col" style="min-width:180px;" class="text-center">
                Peruntukan
            </th>

            <th rowspan="3" scope="col" style="min-width:185px;" class="text-center">
                Mutasi / Keterangan
            </th>

            <th rowspan="3" scope="col" style="min-width:150px;" class="text-center">
                Dokumen
            </th>

            <th rowspan="3" scope="col" style="min-width:100px;" class="text-center">
                Aksi
            </th>
        </tr>

        <tr>
            {{-- Perolehan Tanah --}}
            <th rowspan="2">AMD</th>
            <th colspan="3">Bantuan</th>
            <th rowspan="2">Lain</th>

            {{-- Jenis Tanah --}}
            <th rowspan="2" >SWH</th>
            <th rowspan="2" >TGL</th>
            <th rowspan="2" >KBN</th>
            <th rowspan="2" >TBK</th>
            <th rowspan="2" >TKD</th>

            {{-- Patok --}}
            <th rowspan="2" style="min-width:70px;">Ya</th>
            <th rowspan="2" style="min-width:70px;">Tidak</th>

            {{-- Papan Nama --}}
            <th rowspan="2" style="min-width:70px;" >Ya</th>
            <th rowspan="2" style="min-width:70px;">Tidak</th>
        </tr>

        <tr>
            {{-- Rincian Bantuan --}}
            <th>Pem</th>
            <th>Pro</th>
            <th>Kab</th>
        </tr>
    </x-table>

    @push('footer')
        <script>
            $(document).ready(function() {
                const tableElement = $('#villageTreasuryLandTable');

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
                        url: @json(route('village-treasury-lands.index')),
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

                        // 2. Nomor sertifikat
                        {
                            data: 'nomor_sertifikat',
                            name: 'nomor_sertifikat',
                        },

                        // 3. Asal tanah
                        {
                            data: 'asal_tanah',
                            name: 'asal_tanah'
                        },

                        // 4. Kelas tanah
                        {
                            data: 'kelas_tanah',
                            name: 'kelas_tanah',
                            className: 'text-center'
                        },

                        // 5. Luas tanah
                        {
                            data: 'luas_tanah',
                            name: 'luas_tanah',
                        },

                        // 6. Asli milik desa
                        {
                            data: 'lt_asli_desa',
                            name: 'lt_asli_desa',
                        },

                        // 7. Bantuan pemerintah
                        {
                            data: 'lt_pemerintah',
                            name: 'lt_pemerintah',
                        },

                        // 8. Bantuan provinsi
                        {
                            data: 'lt_provinsi',
                            name: 'lt_provinsi',
                        },

                        // 9. Bantuan kabupaten/kota
                        {
                            data: 'lt_kabupaten',
                            name: 'lt_kabupaten',
                        },

                        // 10. Lain-lain
                        {
                            data: 'lt_lainnya',
                            name: 'lt_lainnya',
                        },

                        // 11. Tanggal perolehan
                        {
                            data: 'tgl_perolehan',
                            name: 'tgl_perolehan',
                            defaultContent: '-',
                        },

                        // 12. Sawah
                        {
                            data: 'lt_tipe_sawah',
                            name: 'lt_tipe_sawah',
                        },

                        // 13. Tegal
                        {
                            data: 'lt_tipe_tegal',
                            name: 'lt_tipe_tegal',
                        },

                        // 14. Kebun
                        {
                            data: 'lt_tipe_kebun',
                            name: 'lt_tipe_kebun',
                        },

                        // 15. Tambak/kolam
                        {
                            data: 'lt_tipe_tambak',
                            name: 'lt_tipe_tambak',
                        },

                        // 16. Tanah kering/darat
                        {
                            data: 'lt_tipe_kering',
                            name: 'lt_tipe_kering',
                        },

                        // 17. Patok tanah - Ya
                        {
                            data: 'patok_tanah',
                            name: 'patok_tanah',
                            className: 'text-center'
                        },

                        // 18. Patok tanah - Tidak
                        {
                            data: 'patok_tanah_tidak',
                            name: 'patok_tanah_tidak',
                            className: 'text-center',
                            searchable: false,
                            orderable: false,
                        },

                        // 19. Papan nama - Ya
                        {
                            data: 'papan_nama',
                            name: 'papan_nama',
                             className: 'text-center',
                        },

                        // 20. Papan nama - Tidak
                        {
                            data: 'papan_nama_tidak',
                            name: 'papan_nama_tidak',
                            className: 'text-center',
                            searchable: false,
                            orderable: false,
                        },

                        // 21. Lokasi
                        {
                            data: 'lokasi',
                            name: 'lokasi'
                        },

                        // 22. Peruntukan
                        {
                            data: 'peruntukan',
                            name: 'peruntukan',
                            defaultContent: '-'
                        },

                        // 23. Mutasi / keterangan
                        {
                            data: 'mutasi',
                            name: 'mutasi',
                            orderable: false,
                            defaultContent: '-'
                        },

                        // 24. Dokumen
                        {
                            data: 'dokumen',
                            name: 'dokumen',
                            defaultContent: '-',
                            searchable: false,
                            orderable: false,
                        },

                        // 25. Aksi
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                        }
                    ],

                    // Urutkan berdasarkan nomor sertifikat
                    order: [
                        [1, 'asc']
                    ],

                    // =================================================
                    // LANGUAGE
                    // =================================================
                    language: {
                        emptyTable: 'Belum ada data tanah kas desa',
                        zeroRecords: 'Data tanah kas desa tidak ditemukan',
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