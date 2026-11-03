<x-appcontent>
    <x-app-heading>
        <x-slot:actions>
            <button type="button" id="btnCetakPeraturan" class="btn-simdes-outline">
                <i class="fa-solid fa-print" aria-hidden="true"></i>
                <span>Cetak</span>
            </button>
            <button type="button" id="btnTambahPeraturan" class="btn-simdes-primary">
                <i class="fa-solid fa-plus" aria-hidden="true"></i>
                <span>Tambah Peraturan</span>
            </button>
        </x-slot:actions>
    </x-app-heading>
    <section class="row g-3 mb-4 ledger-kpi-row" aria-label="Ringkasan Jumlah Peraturan di Desa">
        <div class="col-12 col-sm-6 col-xl-3">
            <article class="stat-card py-3">
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <div>
                        <div class="stat-label mb-1">Total Peraturan Tercatat</div>
                        <div class="stat-value fs-3" id="statTotalPeraturan">0</div>
                    </div>
                    <div class="stat-icon-box stat-icon-primary">
                        <i class="fa-solid fa-scale-balanced" aria-hidden="true"></i>
                    </div>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="stat-card py-3">
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <div>
                        <div class="stat-label mb-1">Peraturan Desa</div>
                        <div class="stat-value fs-3" id="statTotalPerdes">0</div>
                    </div>
                    <div class="stat-icon-box stat-icon-secondary">
                        <i class="fa-solid fa-gavel" aria-hidden="true"></i>
                    </div>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="stat-card accent-gold py-3">
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <div>
                        <div class="stat-label mb-1">Peraturan Kepala Desa</div>
                        <div class="stat-value fs-3" id="statTotalPerkades">0</div>
                    </div>
                    <div class="stat-icon-box stat-icon-accent">
                        <i class="fa-solid fa-file-shield" aria-hidden="true"></i>
                    </div>
                </div>
            </article>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <article class="stat-card py-3">
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <div>
                        <div class="stat-label mb-1">Peraturan Bersama Kades</div>
                        <div class="stat-value fs-3" id="statTotalPermaKades">0</div>
                    </div>
                    <div class="stat-icon-box stat-icon-info">
                        <i class="fa-solid fa-handshake-simple" aria-hidden="true"></i>
                    </div>
                </div>
            </article>
        </div>
    </section>
    <section class="simdes-table-container">
        <table class="simdes-ledger-table table table-bordered table-hover align-middle mb-0" id="myTable">
            <thead>
                <tr class="text-center">
                    <th style="width: 20px;">
                        No.
                    </th>
                    <th style="min-width: 200px;">
                        Jenis Peraturan
                    </th>
                    <th style="min-width: 180px;">
                        Nomor Peraturan
                    </th>
                    <th style="min-width: 180px;">
                        Tanggal Ditetapkan
                    </th>
                    <th style="min-width: 250px;">
                        Tentang
                    </th>
                    <th style="min-width: 220px;">
                        Uraian
                    </th>
                    <th style="min-width: 220px;">
                        Tanggal Kesepakatan
                    </th>
                    <th style="min-width: 220px;">
                        Nomor Dilaporkan
                    </th>
                    <th style="min-width: 220px;">
                        Tanggal Dilaporkan
                    </th>
                    <th style="min-width: 220px;">
                        Nomor Diundang
                    </th>
                    <th style="min-width: 220px;">
                        Tanggal Diundang
                    </th>
                    <th style="min-width: 220px;">
                        Nomor Berita Desa
                    </th>
                    <th style="min-width: 220px;">
                        Tanggal Berita Desa
                    </th>
                    <th style="min-width: 160px;">
                        Dokumen
                    </th>
                    <th style="min-width: 220px;">
                        Keterangan
                    </th>
                    <th style="min-width: 180px;">
                        Status
                    </th>
                    <th >
                        <small>Aksi</small>
                    </th>
                </tr>
            </thead>
        </table>
    </section>



    @push('footer')
        <script>
            $(document).ready(function() {
                const tableElement = $('#myTable');

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
                        url: @json(route('village-regulations.index')),
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

                        // 2. Jenis Peraturan
                        {
                            data: 'jenis_peraturan',
                            name: 'jenis_peraturan'
                        },

                        // 3. Nomor Peraturan
                        {
                            data: 'nomor_peraturan',
                            name: 'nomor_peraturan'
                        },

                        // 4. Tanggal Ditetapkan
                        {
                            data: 'tgl_ditetapkan',
                            name: 'tgl_ditetapkan'
                        },

                        // 5. Nama Peraturan
                        {
                            data: 'nama_peraturan',
                            name: 'nama_peraturan'
                        },

                        // 6. Uraian
                        {
                            data: 'uraian',
                            name: 'uraian',
                            defaultContent: '-'
                        },

                        // 7. Tanggal Kesepakatan
                        {
                            data: 'tgl_kesepakatan',
                            name: 'tgl_kesepakatan',
                            defaultContent: '-'
                        },

                        // 8. Nomor Dilaporkan
                        {
                            data: 'nomor_dilaporkan',
                            name: 'nomor_dilaporkan',
                            defaultContent: '-'
                        },

                        // 9. Tanggal Dilaporkan
                        {
                            data: 'tgl_dilaporkan',
                            name: 'tgl_dilaporkan',
                            defaultContent: '-'
                        },

                        // 10. Nomor Diundang
                        {
                            data: 'nomor_diundang',
                            name: 'nomor_diundang',
                            defaultContent: '-'
                        },

                        // 11. Tanggal Diundang
                        {
                            data: 'tgl_diundang',
                            name: 'tgl_diundang',
                            defaultContent: '-'
                        },

                        // 12. Nomor Berita Desa
                        {
                            data: 'nomor_berita',
                            name: 'nomor_berita',
                            defaultContent: '-'
                        },

                        // 13. Tanggal Berita Desa
                        {
                            data: 'tgl_berita',
                            name: 'tgl_berita',
                            defaultContent: '-'
                        },

                        // 14. Dokumen
                        {
                            data: 'dokumen',
                            name: 'dokumen',
                            defaultContent: '-'
                        },

                        // 15. Keterangan
                        {
                            data: 'catatan',
                            name: 'catatan',
                            defaultContent: '-'
                        },

                        // 16. Status
                        {
                            data: 'status',
                            name: 'status'
                        },

                        // 17. Aksi
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],


                    // Urutkan berdasarkan tanggal ditetapkan
                    order: [
                        [3, 'desc']
                    ],

                    // =================================================
                    // LANGUAGE
                    // =================================================
                    language: {
                        emptyTable: 'Belum ada data peraturan',
                        zeroRecords: 'Data tidak ditemukan',
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
