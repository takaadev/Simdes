<x-appcontent>
    {{-- @include('pages.villagetreasuryland.comp.widgets') --}}
    @include('pages.villagetreasuryland.comp.filter')

    <x-table id="populationRecapTable">
    {{-- BARIS 1: Kategori Utama --}}
    <tr>
        <th rowspan="4" scope="col" class="text-center align-middle">No.</th>
        <th rowspan="4" scope="col" class="text-center align-middle" style="min-width: 180px;">
            Nama Dusun/Lingkungan
        </th>
        <th colspan="7" class="text-center align-middle">
            Jumlah Penduduk Awal Bulan
        </th>
        <th colspan="8" class="text-center align-middle">
            Tambahan Bulan Ini
        </th>
        <th colspan="8" class="text-center align-middle">
            Pengurangan Bulan Ini
        </th>
        <th colspan="7" class="text-center align-middle">
            Jml Penduduk Akhir Bulan
        </th>
        <th rowspan="4" scope="col" class="text-center align-middle" style="min-width: 150px;">
            Ket
        </th>
        <th rowspan="4" scope="col" class="text-center align-middle" style="min-width: 100px;">
            Aksi
        </th>
    </tr>

    {{-- BARIS 2: Sub-Kategori Level 1 --}}
    <tr>
        {{-- Rincian Awal Bulan --}}
        <th colspan="2" rowspan="2" class="text-center align-middle">WNI</th>
        <th colspan="2" rowspan="2" class="text-center align-middle">WNA</th>
        <th rowspan="3" class="text-center align-middle">Jml KK</th>
        <th rowspan="3" class="text-center align-middle">Jml Anggota Keluarga</th>
        <th rowspan="3" class="text-center align-middle">Jml Jiwa (7+8)</th>

        {{-- Rincian Tambahan Bulan Ini --}}
        <th colspan="4" class="text-center align-middle">Lahir</th>
        <th colspan="4" class="text-center align-middle">Datang</th>

        {{-- Rincian Pengurangan Bulan Ini --}}
        <th colspan="4" class="text-center align-middle">Meninggal</th>
        <th colspan="4" class="text-center align-middle">Pindah</th>

        {{-- Rincian Akhir Bulan --}}
        <th colspan="2" rowspan="2" class="text-center align-middle">WNA</th>
        <th colspan="2" rowspan="2" class="text-center align-middle">WNI</th>
        <th rowspan="3" class="text-center align-middle">Jml KK</th>
        <th rowspan="3" class="text-center align-middle">Anggota Keluarga</th>
        <th rowspan="3" class="text-center align-middle">Jml Jiwa</th>
    </tr>

    {{-- BARIS 3: Sub-Kategori Level 2 (WNA/WNI untuk Mutasi) --}}
    <tr>
        {{-- Lahir --}}
        <th colspan="2" class="text-center align-middle">WNA</th>
        <th colspan="2" class="text-center align-middle">WNI</th>

        {{-- Datang --}}
        <th colspan="2" class="text-center align-middle">WNA</th>
        <th colspan="2" class="text-center align-middle">WNI</th>

        {{-- Meninggal --}}
        <th colspan="2" class="text-center align-middle">WNA</th>
        <th colspan="2" class="text-center align-middle">WNI</th>

        {{-- Pindah --}}
        <th colspan="2" class="text-center align-middle">WNA</th>
        <th colspan="2" class="text-center align-middle">WNI</th>
    </tr>

    {{-- BARIS 4: Jenis Kelamin (L / P) --}}
    <tr>
        {{-- Awal Bulan (WNI & WNA) --}}
        <th class="text-center">L</th>
        <th class="text-center">P</th>
        <th class="text-center">L</th>
        <th class="text-center">P</th>

        {{-- Lahir (WNA & WNI) --}}
        <th class="text-center">L</th>
        <th class="text-center">P</th>
        <th class="text-center">L</th>
        <th class="text-center">P</th>

        {{-- Datang (WNA & WNI) --}}
        <th class="text-center">L</th>
        <th class="text-center">P</th>
        <th class="text-center">L</th>
        <th class="text-center">P</th>

        {{-- Meninggal (WNA & WNI) --}}
        <th class="text-center">L</th>
        <th class="text-center">P</th>
        <th class="text-center">L</th>
        <th class="text-center">P</th>

        {{-- Pindah (WNA & WNI) --}}
        <th class="text-center">L</th>
        <th class="text-center">P</th>
        <th class="text-center">L</th>
        <th class="text-center">P</th>

        {{-- Akhir Bulan (WNA & WNI) --}}
        <th class="text-center">L</th>
        <th class="text-center">P</th>
        <th class="text-center">L</th>
        <th class="text-center">P</th>
    </tr>

    {{-- BARIS 5 (Opsional): Nomor Urut Kolom (1 s/d 33) seperti di gambar --}}
    <tr class="text-center text-muted" style="font-size: 0.85rem;">
        @for ($i = 1; $i <= 33; $i++)
            <th>{{ $i }}</th>
        @endfor
        <th>-</th> {{-- Kolom Aksi --}}
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

                    // columns: [
                    //     // 1. Nomor urut
                    //     {
                    //         data: 'DT_RowIndex',
                    //         name: 'DT_RowIndex',
                    //         searchable: false,
                    //         orderable: false
                    //     },

                    //     // 2. Nomor sertifikat
                    //     {
                    //         data: 'nomor_sertifikat',
                    //         name: 'nomor_sertifikat',
                    //     },

                    //     // 3. Asal tanah
                    //     {
                    //         data: 'asal_tanah',
                    //         name: 'asal_tanah'
                    //     },

                    //     // 4. Kelas tanah
                    //     {
                    //         data: 'kelas_tanah',
                    //         name: 'kelas_tanah',
                    //         className: 'text-center'
                    //     },

                    //     // 5. Luas tanah
                    //     {
                    //         data: 'luas_tanah',
                    //         name: 'luas_tanah',
                    //     },

                    //     // 6. Asli milik desa
                    //     {
                    //         data: 'lt_asli_desa',
                    //         name: 'lt_asli_desa',
                    //     },

                    //     // 7. Bantuan pemerintah
                    //     {
                    //         data: 'lt_pemerintah',
                    //         name: 'lt_pemerintah',
                    //     },

                    //     // 8. Bantuan provinsi
                    //     {
                    //         data: 'lt_provinsi',
                    //         name: 'lt_provinsi',
                    //     },

                    //     // 9. Bantuan kabupaten/kota
                    //     {
                    //         data: 'lt_kabupaten',
                    //         name: 'lt_kabupaten',
                    //     },

                    //     // 10. Lain-lain
                    //     {
                    //         data: 'lt_lainnya',
                    //         name: 'lt_lainnya',
                    //     },

                    //     // 11. Tanggal perolehan
                    //     {
                    //         data: 'tgl_perolehan',
                    //         name: 'tgl_perolehan',
                    //         defaultContent: '-',
                    //     },

                    //     // 12. Sawah
                    //     {
                    //         data: 'lt_tipe_sawah',
                    //         name: 'lt_tipe_sawah',
                    //     },

                    //     // 13. Tegal
                    //     {
                    //         data: 'lt_tipe_tegal',
                    //         name: 'lt_tipe_tegal',
                    //     },

                    //     // 14. Kebun
                    //     {
                    //         data: 'lt_tipe_kebun',
                    //         name: 'lt_tipe_kebun',
                    //     },

                    //     // 15. Tambak/kolam
                    //     {
                    //         data: 'lt_tipe_tambak',
                    //         name: 'lt_tipe_tambak',
                    //     },

                    //     // 16. Tanah kering/darat
                    //     {
                    //         data: 'lt_tipe_kering',
                    //         name: 'lt_tipe_kering',
                    //     },

                    //     // 17. Patok tanah - Ya
                    //     {
                    //         data: 'patok_tanah',
                    //         name: 'patok_tanah',
                    //         className: 'text-center'
                    //     },

                    //     // 18. Patok tanah - Tidak
                    //     {
                    //         data: 'patok_tanah_tidak',
                    //         name: 'patok_tanah_tidak',
                    //         className: 'text-center',
                    //         searchable: false,
                    //         orderable: false,
                    //     },

                    //     // 19. Papan nama - Ya
                    //     {
                    //         data: 'papan_nama',
                    //         name: 'papan_nama',
                    //          className: 'text-center',
                    //     },

                    //     // 20. Papan nama - Tidak
                    //     {
                    //         data: 'papan_nama_tidak',
                    //         name: 'papan_nama_tidak',
                    //         className: 'text-center',
                    //         searchable: false,
                    //         orderable: false,
                    //     },

                    //     // 21. Lokasi
                    //     {
                    //         data: 'lokasi',
                    //         name: 'lokasi'
                    //     },

                    //     // 22. Peruntukan
                    //     {
                    //         data: 'peruntukan',
                    //         name: 'peruntukan',
                    //         defaultContent: '-'
                    //     },

                    //     // 23. Mutasi / keterangan
                    //     {
                    //         data: 'mutasi',
                    //         name: 'mutasi',
                    //         orderable: false,
                    //         defaultContent: '-'
                    //     },

                    //     // 24. Dokumen
                    //     {
                    //         data: 'dokumen',
                    //         name: 'dokumen',
                    //         defaultContent: '-',
                    //         searchable: false,
                    //         orderable: false,
                    //     },

                    //     // 25. Aksi
                    //     {
                    //         data: 'action',
                    //         name: 'action',
                    //         orderable: false,
                    //         searchable: false,
                    //     }
                    // ],

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