import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';

export function initDataTables(container = document) {
    container.querySelectorAll('.datatable').forEach((table) => {
        if (DataTable.isDataTable(table)) {
            return;
        }

        new DataTable(table, {
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],

            language: {
                search: 'Cari:',
                searchPlaceholder: 'Cari data...',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data',
                infoFiltered: '(difilter dari _MAX_ total data)',
                zeroRecords: 'Data tidak ditemukan',
                emptyTable: 'Belum ada data tersedia',

                paginate: {
                    first: 'Pertama',
                    last: 'Terakhir',
                    next: 'Selanjutnya',
                    previous: 'Sebelumnya'
                }
            }
        });
    });
}