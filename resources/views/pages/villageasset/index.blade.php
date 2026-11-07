<x-appcontent>
    <x-app-heading />
    @include('pages.villageasset.comp.widgets')
    @include('pages.villageasset.comp.filter')
    <x-table id="villageAssetsTable">
        <tr>
            <th rowspan="3" scope="col" style="width:58px;">No.</th>
            <th rowspan="3" scope="col" style="min-width:230px;" class="text-center">
                Jenis Barang/Bangunan
            </th>
            <th colspan="5" class="text-center">Asal Barang/Bangunan</th>
            <th colspan="2" class="text-center">Kondisi Awal Tahun</th>
            <th colspan="4" class="text-center">Penghapusan Barang dan Bangunan</th>
            <th colspan="2" class="text-center">Kondisi Akhir Tahun</th>
            <th rowspan="3" scope="col" style="min-width:185px;" class="text-center">Keterangan</th>
            <th rowspan="3" scope="col" style="min-width:150px;" class="text-center">
                Dokumen
            </th>
            <th rowspan="3" scope="col" style="min-width:100px;" class="text-center">
                Aksi
            </th>
        </tr>
        <tr>
            <!-- Asal Barang/Bangunan Subheaders -->
            <th rowspan="2" style="min-width:86px;">Dibeli Sendiri</th>
            <th colspan="3" class="text-center">Bantuan</th>
            <th rowspan="2" style="min-width:86px;">Sumbangan</th>
            <!-- Keadaan Awal Tahun Subheaders -->
            <th rowspan="2" scope="col" style="min-width:74px;" class="text-center">Baik</th>
            <th rowspan="2" scope="col" style="min-width:74px;" class="text-center">Rusak</th>
            <!-- Penghapusan Barang dan Bangunan Subheaders -->
            <th rowspan="2" scope="col" style="min-width:74px;">Rusak</th>
            <th rowspan="2" scope="col" style="min-width:74px;">Dijual</th>
            <th rowspan="2" scope="col" style="min-width:96px;">Disumbangkan</th>
            <th rowspan="2" scope="col">Tanggal Penghapusan</th>
            <!-- Keadaan Akhir Tahun Subheaders -->
            <th rowspan="2" scope="col" style="min-width:74px;" class="text-center">Baik</th>
            <th rowspan="2" scope="col" style="min-width:74px;" class="text-center">Rusak</th>
        </tr>
        <tr>
            <!-- Rincian Keterangan Asal Bantuan -->
            <th scope="col" style="min-width:86px;">Pemerintah</th>
            <th scope="col" style="min-width:78px;">Provinsi</th>
            <th scope="col" style="min-width:108px;">Kabupaten/Kota</th>
        </tr>
    </x-table>


    @push('footer')
        <script>
            $(document).ready(function() {
                const tableElement = $('#villageAssetsTable');

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
                        url: @json(route('village-assets.index')),
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

                        // 2. Jenis Barang/Bangunan
                        {
                            data: 'nama_asset',
                            name: 'nama_asset'
                        },

                        // 3. Dibeli Sendiri
                        {
                            data: 'asal_beli',
                            name: 'asal_beli',
                            className: 'text-center'
                        },

                        // 4. Bantuan Pemerintah
                        {
                            data: 'asal_pemerintah',
                            name: 'asal_pemerintah',
                            className: 'text-center'
                        },

                        // 5. Bantuan Provinsi
                        {
                            data: 'asal_provinsi',
                            name: 'asal_provinsi',
                            className: 'text-center'
                        },

                        // 6. Bantuan Kabupaten/Kota
                        {
                            data: 'asal_kabupaten',
                            name: 'asal_kabupaten',
                            className: 'text-center'
                        },

                        // 7. Sumbangan
                        {
                            data: 'asal_sumbangan',
                            name: 'asal_sumbangan',
                            className: 'text-center'
                        },

                        // 8. Kondisi Awal - Baik
                        {
                            data: 'kondisi_awal_baik',
                            name: 'kondisi_awal_baik',
                            className: 'text-center'
                        },

                        // 9. Kondisi Awal - Rusak
                        {
                            data: 'kondisi_awal_rusak',
                            name: 'kondisi_awal_rusak',
                            className: 'text-center'
                        },

                        // 10. Penghapusan - Rusak
                        {
                            data: 'hapus_rusak',
                            name: 'hapus_rusak',
                            className: 'text-center'
                        },

                        // 11. Penghapusan - Dijual
                        {
                            data: 'hapus_dijual',
                            name: 'hapus_dijual',
                            className: 'text-center'
                        },

                        // 12. Penghapusan - Disumbangkan
                        {
                            data: 'hapus_disumbangkan',
                            name: 'hapus_disumbangkan',
                            className: 'text-center'
                        },

                        // 13. Tanggal Penghapusan
                        {
                            data: 'tgl_hapus',
                            name: 'tgl_hapus',
                            defaultContent: '-',
                            className: 'text-center'
                        },

                        // 14. Kondisi Akhir - Baik
                        {
                            data: 'kondisi_ahir_baik',
                            name: 'kondisi_ahir_baik',
                            className: 'text-center'
                        },

                        // 15. Kondisi Akhir - Rusak
                        {
                            data: 'kondisi_ahir_rusak',
                            name: 'kondisi_ahir_rusak',
                            className: 'text-center'
                        },

                        // 16. Keterangan
                        {
                            data: 'catatan',
                            name: 'catatan',
                            orderable: false,
                            defaultContent: '-'
                        },

                        // 17. Dokumen
                        {
                            data: 'dokumen',
                            name: 'dokumen',
                            defaultContent: '-',
                            searchable: false,
                            orderable: false,
                            className: 'text-center'
                        },

                        // 18. Aksi
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }
                    ],

                    // Urutkan berdasarkan nama aset
                    order: [
                        [1, 'asc']
                    ],

                    // =================================================
                    // LANGUAGE
                    // =================================================
                    language: {
                        emptyTable: 'Belum ada data inventaris',
                        zeroRecords: 'Data inventaris tidak ditemukan',
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
