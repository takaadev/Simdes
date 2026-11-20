<x-appcontent>
    {{-- @include('pages.villagetreasuryland.comp.widgets') --}}
    @include('pages.recapt.comp.filter')

    <x-table id="residentRecaptTable">
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
            <th rowspan="3" class="text-center align-middle">Jml Jiwa</th>

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
        {{-- <tr class="text-center text-muted" style="font-size: 0.85rem;">
            @for ($i = 1; $i <= 33; $i++)
                <th>{{ $i }}</th>
            @endfor
            <th>-</th>
        </tr> --}}
    </x-table>

    @push('footer')
        <script>
            $(document).ready(function() {
                const tableElement = $('#residentRecaptTable');

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
                        url: @json(route('recapts.index')),
                        type: 'GET'
                    },

                    pageLength: 10,

                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            searchable: false,
                            orderable: false,
                            className: 'text-center'
                        }, // 1
                        {
                            data: 'lingkungan',
                            name: 'lingkungan'
                        }, // 2

                        // Awal Bulan: WNI (3-4), WNA (5-6), KK & Jiwa (7-9)
                        {
                            data: 'awb_wni_l',
                            name: 'awb_wni_l',
                            className: 'text-center'
                        }, // 3
                        {
                            data: 'awb_wni_p',
                            name: 'awb_wni_p',
                            className: 'text-center'
                        }, // 4
                        {
                            data: 'awb_wna_l',
                            name: 'awb_wna_l',
                            className: 'text-center'
                        }, // 5
                        {
                            data: 'awb_wna_p',
                            name: 'awb_wna_p',
                            className: 'text-center'
                        }, // 6
                        {
                            data: 'awb_jml_kk',
                            name: 'awb_jml_kk',
                            className: 'text-center'
                        }, // 7
                        {
                            data: 'awb_jml_anggota_kk',
                            name: 'awb_jml_anggota_kk',
                            className: 'text-center'
                        }, // 8
                        {
                            data: 'awb_jml_penduduk',
                            name: 'awb_jml_penduduk',
                            className: 'text-center fw-semibold'
                        }, // 9

                        // Tambahan Bulan Ini — Lahir: WNA (10-11), WNI (12-13)
                        {
                            data: 'tbl_wna_l',
                            name: 'tbl_wna_l',
                            className: 'text-center'
                        }, // 10
                        {
                            data: 'tbl_wna_p',
                            name: 'tbl_wna_p',
                            className: 'text-center'
                        }, // 11
                        {
                            data: 'tbl_wni_l',
                            name: 'tbl_wni_l',
                            className: 'text-center'
                        }, // 12
                        {
                            data: 'tbl_wni_p',
                            name: 'tbl_wni_p',
                            className: 'text-center'
                        }, // 13

                        // Tambahan Bulan Ini — Datang: WNA (14-15), WNI (16-17)
                        {
                            data: 'tbd_wna_l',
                            name: 'tbd_wna_l',
                            className: 'text-center'
                        }, // 14
                        {
                            data: 'tbd_wna_p',
                            name: 'tbd_wna_p',
                            className: 'text-center'
                        }, // 15
                        {
                            data: 'tbd_wni_l',
                            name: 'tbd_wni_l',
                            className: 'text-center'
                        }, // 16
                        {
                            data: 'tbd_wni_p',
                            name: 'tbd_wni_p',
                            className: 'text-center'
                        }, // 17

                        // Pengurangan Bulan Ini — Meninggal: WNA (18-19), WNI (20-21)
                        {
                            data: 'pbm_wna_l',
                            name: 'pbm_wna_l',
                            className: 'text-center'
                        }, // 18
                        {
                            data: 'pbm_wna_p',
                            name: 'pbm_wna_p',
                            className: 'text-center'
                        }, // 19
                        {
                            data: 'pbm_wni_l',
                            name: 'pbm_wni_l',
                            className: 'text-center'
                        }, // 20
                        {
                            data: 'pbm_wni_p',
                            name: 'pbm_wni_p',
                            className: 'text-center'
                        }, // 21

                        // Pengurangan Bulan Ini — Pindah: WNA (22-23), WNI (24-25)
                        {
                            data: 'pbp_wna_l',
                            name: 'pbp_wna_l',
                            className: 'text-center'
                        }, // 22
                        {
                            data: 'pbp_wna_p',
                            name: 'pbp_wna_p',
                            className: 'text-center'
                        }, // 23
                        {
                            data: 'pbp_wni_l',
                            name: 'pbp_wni_l',
                            className: 'text-center'
                        }, // 24
                        {
                            data: 'pbp_wni_p',
                            name: 'pbp_wni_p',
                            className: 'text-center'
                        }, // 25

                        // Akhir Bulan: WNA (26-27), WNI (28-29), KK & Jiwa (30-32)
                        {
                            data: 'ahb_wna_l',
                            name: 'ahb_wna_l',
                            className: 'text-center'
                        }, // 26
                        {
                            data: 'ahb_wna_p',
                            name: 'ahb_wna_p',
                            className: 'text-center'
                        }, // 27
                        {
                            data: 'ahb_wni_l',
                            name: 'ahb_wni_l',
                            className: 'text-center'
                        }, // 28
                        {
                            data: 'ahb_wni_p',
                            name: 'ahb_wni_p',
                            className: 'text-center'
                        }, // 29
                        {
                            data: 'ahb_jml_kk',
                            name: 'ahb_jml_kk',
                            className: 'text-center'
                        }, // 30
                        {
                            data: 'ahb_jml_anggota_kk',
                            name: 'ahb_jml_anggota_kk',
                            className: 'text-center'
                        }, // 31
                        {
                            data: 'ahb_jml_penduduk',
                            name: 'ahb_jml_penduduk',
                            className: 'text-center fw-semibold'
                        }, // 32

                        {
                            data: 'catatan',
                            name: 'catatan',
                            orderable: false
                        }, // 33
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        }, // AKSI
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
