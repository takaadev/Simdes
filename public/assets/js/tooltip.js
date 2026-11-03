(function () {
    'use strict';

    function initTooltips() {
        // Pastikan Bootstrap Tooltip tersedia
        if (!window.bootstrap?.Tooltip) {
            console.warn('Bootstrap Tooltip belum dimuat.');
            return;
        }

        // Hindari inisialisasi berulang
        if (document.body.dataset.tooltipInitialized === 'true') {
            return;
        }

        // Aktifkan tooltip secara global
        new bootstrap.Tooltip(document.body, {
            selector: '[data-bs-toggle="tooltip"]',
            trigger: 'hover focus',
            container: 'body'
        });

        document.body.dataset.tooltipInitialized = 'true';
    }

    // Jalankan setelah DOM siap
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTooltips);
    } else {
        initTooltips();
    }

    // Opsional: akses inisialisasi melalui JavaScript
    window.SimdesTooltip = {
        init: initTooltips
    };

})();