(function ($) {
    'use strict';

    if (!$ || !$.fn.dataTable) {
        console.warn('DataTables belum dimuat.');
        return;
    }

    // Konfigurasi default seluruh DataTables
    $.extend(true, $.fn.dataTable.defaults, {
        columnDefs: [
            {
                targets: 0,
                className: 'fixed-start sticky-col-first'
            },
            {
                targets: -1,
                className: 'fixed-end sticky-col-last'
            }
        ]
    });

})(jQuery);