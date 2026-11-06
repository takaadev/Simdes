
/**
 * ==========================================================
 * SIMDES MODAL MANAGER
 * ==========================================================
 * Global modal management for SIMDES.
 * Bootstrap 5
 *
 * Features:
 * - Open / Close modal
 * - Move modal to document.body (FixedColumns fix)
 * - Dynamic DataTables support
 * - Focus management
 * - Multiple modal instances
 * - Automatic modal cleanup
 * - Public JavaScript API
 * ==========================================================
 */

(function (window, document, bootstrap) {
    'use strict';

    // ======================================================
    // 1. CONFIGURATION
    // ======================================================

    const CONFIG = {
        selector: '.simdes-modal',
        openButton: '[data-simdes-modal-open]',
        closeButton: '[data-simdes-modal-close]',
        backdrop: true,
        keyboard: true,
        focus: true,
    };

    if (!bootstrap || !bootstrap.Modal) {
        console.error('[SIMDES Modal] Bootstrap Modal belum tersedia.');
        return;
    }

    const initializedModals = new WeakSet();

    // ======================================================
    // 2. GET MODAL ELEMENT
    // ======================================================

    function getElement(target) {
        if (!target) return null;

        if (target instanceof HTMLElement) {
            return target;
        }

        const id = String(target).replace(/^#/, '');

        return document.getElementById(id);
    }

    // ======================================================
    // 3. GET MODAL INSTANCE
    // ======================================================

    function getInstance(target) {
        const element = getElement(target);

        if (!element) {
            console.warn('[SIMDES Modal] Modal tidak ditemukan:', target);
            return null;
        }

        return bootstrap.Modal.getOrCreateInstance(element, {
            backdrop: CONFIG.backdrop,
            keyboard: CONFIG.keyboard,
            focus: CONFIG.focus,
        });
    }

    // ======================================================
    // 4. MOVE MODAL TO BODY
    // ======================================================

    function moveToBody(element) {
        if (element.parentElement === document.body) {
            return;
        }

        // Simpan posisi asli hanya sekali.
        if (!element._simdesOriginalParent) {
            element._simdesOriginalParent = element.parentNode;
            element._simdesOriginalNextSibling = element.nextSibling;
        }

        // Lepaskan modal dari FixedColumns / wrapper DataTables.
        document.body.appendChild(element);
    }

    // ======================================================
    // 5. RESTORE MODAL POSITION
    // ======================================================

    function restorePosition(element) {
        const parent = element._simdesOriginalParent;

        if (!parent) return;

        // Parent masih tersedia di DOM.
        if (parent.isConnected) {
            const nextSibling = element._simdesOriginalNextSibling;

            if (nextSibling && nextSibling.parentNode === parent) {
                parent.insertBefore(element, nextSibling);
            } else {
                parent.appendChild(element);
            }

            return;
        }

        // Parent sudah dihapus oleh DataTables.
        // Hapus modal lama agar tidak terjadi duplikasi ID.
        const instance = bootstrap.Modal.getInstance(element);

        if (instance) {
            instance.dispose();
        }

        element.remove();

        element._simdesOriginalParent = null;
        element._simdesOriginalNextSibling = null;
    }

    // ======================================================
    // 6. INITIALIZE MODAL EVENTS
    // ======================================================

    function initialize(target) {
        const element = getElement(target);

        if (!element) return null;

        if (!element.matches(CONFIG.selector)) {
            console.warn('[SIMDES Modal] Elemen bukan modal SIMDES.');
            return null;
        }

        if (initializedModals.has(element)) {
            return element;
        }

        initializedModals.add(element);

        // Buat instance Bootstrap.
        getInstance(element);

        // ----------------------------------------------
        // HIDE EVENT
        // ----------------------------------------------

        element.addEventListener('hide.bs.modal', function () {
            // Hilangkan fokus dari elemen di dalam modal.
            if (element.contains(document.activeElement)) {
                document.activeElement.blur();
            }
        });

        // ----------------------------------------------
        // HIDDEN EVENT
        // ----------------------------------------------

        element.addEventListener('hidden.bs.modal', function () {
            const trigger = element._simdesTrigger;

            // Kembalikan fokus jika tombol masih tersedia.
            if (trigger && trigger.isConnected) {
                trigger.focus({
                    preventScroll: true
                });
            }

            element._simdesTrigger = null;

            // Kembalikan modal atau bersihkan modal lama.
            restorePosition(element);
        });

        return element;
    }

    // ======================================================
    // 7. OPEN MODAL
    // ======================================================

    function open(target, trigger = null) {
        const element = getElement(target);

        if (!element) {
            console.error('[SIMDES Modal] Tidak dapat membuka modal:', target);
            return;
        }

        if (!element.matches(CONFIG.selector)) {
            console.warn('[SIMDES Modal] Elemen bukan modal SIMDES.');
            return;
        }

        // Pindahkan modal keluar dari wrapper DataTables.
        moveToBody(element);

        initialize(element);

        // Simpan tombol pemicu modal.
        element._simdesTrigger = trigger || document.activeElement;

        const instance = getInstance(element);

        if (instance) {
            instance.show();
        }
    }

    // ======================================================
    // 8. CLOSE MODAL
    // ======================================================

    function close(target) {
        const element = getElement(target);

        if (!element) return;

        const instance = getInstance(element);

        if (instance) {
            instance.hide();
        }
    }

    // ======================================================
    // 9. EVENT DELEGATION
    // ======================================================

    // OPEN MODAL
    document.addEventListener('click', function (event) {
        const button = event.target.closest(CONFIG.openButton);

        if (!button) return;

        event.preventDefault();

        const modalId = button.getAttribute('data-simdes-modal-open');

        if (!modalId) return;

        open(modalId, button);
    });

    // OPTIONAL CLOSE MODAL
    document.addEventListener('click', function (event) {
        const button = event.target.closest(CONFIG.closeButton);

        if (!button) return;

        event.preventDefault();

        const modal = button.closest(CONFIG.selector);

        if (modal) {
            close(modal);
        }
    });

    // ======================================================
    // 10. INITIALIZE EXISTING MODALS
    // ======================================================

    function initializeAll(root = document) {
        root.querySelectorAll(CONFIG.selector).forEach(function (modal) {
            initialize(modal);
        });
    }

    // ======================================================
    // 11. PUBLIC API
    // ======================================================

    window.SIMDES = window.SIMDES || {};

    window.SIMDES.Modal = {
        open: open,
        close: close,
        get: getInstance,
        init: initialize,
        initAll: initializeAll,
    };
    initializeAll();

})(window, document, window.bootstrap);
