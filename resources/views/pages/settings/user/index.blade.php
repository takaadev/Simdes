<x-appcontent>
    @include('pages.settings.user.comp.widgets')
    @include('pages.settings.user.comp.filter')

    <x-table id="userTable">
        <tr>
            <th class="text-center text-nowrap" style="width: 40px; min-width: 40px;">
                No.
            </th>
            <th>Nama Lengkap</th>
            <th>Akun Login</th>
            <th>Email</th>
            <th>Role</th>
            <th>Jabatan</th>
            <th>Telepon</th>
            <th>Status</th>
            <th>Dibuat</th>
            <th>Last Login</th>
            <th>Status Login</th>
            <th>Aksi</th>
        </tr>
    </x-table>

    @push('footer')
        <script>
            $(document).ready(function() {
                const tableElement = $('#userTable');

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
                // FILTER MAP
                // =====================================================
                const filterMap = {
                    role: '#filterRoleSelect',
                    login_status: '#filterLoginStatusSelect',
                };

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
                        url: @json(route('settings.users.index')),
                        type: 'GET',
                        data: function(d, settings) {
                            const rawUrl = typeof settings?.ajax === 'string' ?
                                settings.ajax :
                                (settings?.ajax?.url || @json(route('settings.users.index')));
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

                    pageLength: 10,

                    columns: [
                        // 1. No.
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            searchable: false,
                            orderable: false,
                            className: 'text-center text-nowrap',
                            width: '40px'
                        },

                        // 2. Nama Lengkap
                        {
                            data: 'nama',
                            name: 'name'
                        },

                        // 3. Akun Login
                        {
                            data: 'username',
                            name: 'username',
                            className: 'text-start'
                        },

                        // 4. Email
                        {
                            data: 'email',
                            name: 'email'
                        },

                        // 5. Role
                        {
                            data: 'role',
                            name: 'role',
                            className: 'text-center'
                        },

                        // 6. Jabatan
                        {
                            data: 'position',
                            name: 'position',
                            orderable: false
                        },

                        // 7. Telepon
                        {
                            data: 'telpon',
                            name: 'telpon',
                            orderable: false
                        },

                        // 8. Status Aparat
                        {
                            data: 'status',
                            name: 'status',
                            orderable: false,
                            className: 'text-center'
                        },

                        // 9. Dibuat
                        {
                            data: 'created_at',
                            name: 'created_at',
                            className: 'text-center text-nowrap'
                        },

                        // 10. Last Login
                        {
                            data: 'last_login',
                            name: 'last_login_at',
                            className: 'text-center text-nowrap'
                        },

                        // 11. Status Login
                        {
                            data: 'login_status',
                            name: 'login_status',
                            orderable: false,
                            searchable: false,
                            className: 'text-center'
                        },

                        // 12. Aksi
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
                        emptyTable: 'Belum ada data pengguna',
                        zeroRecords: 'Data pengguna tidak ditemukan',
                        processing: 'Memproses data...',
                        info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                        infoEmpty: 'Menampilkan 0 data',
                        infoFiltered: '(difilter dari _MAX_ total data)',
                        lengthMenu: 'Tampilkan _MENU_ data',
                        search: 'Pencarian:',
                        paginate: {
                            first: 'Pertama',
                            last: 'Terakhir',
                            next: 'Selanjutnya',
                            previous: 'Sebelumnya'
                        }
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
                        const tableNode = this.api().table().node();
                        wrapTableWithScroll(tableNode);
                        initTooltips(tableNode);
                    }
                });

                // Wrapper awal
                wrapTableWithScroll(table.table().node());
            });
        </script>
    @endpush
</x-appcontent>
