
(function ($, window, document) {
    'use strict';

    if (!$) {
        console.error('[SIMDES Form] jQuery tidak ditemukan.');
        return;
    }

    if (window.SIMDESFormInitialized) {
        return;
    }

    window.SIMDESFormInitialized = true;

    const FORM_SELECTOR = '[data-simdes-ajax-form]';
    const MODAL_SELECTOR = '.simdes-modal';

    const defaultIcon = 'floppy-disk';

    const processingHtml = `
        <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
        <span>Memproses...</span>
    `;

    /**
     * Membuat HTML tombol submit.
     */
    function getSubmitHtml(form) {
        const label = form.dataset.simdesSubmitLabel || 'Simpan';
        const icon = form.dataset.simdesSubmitIcon || defaultIcon;

        return `
            <i class="fa-solid fa-${icon}" aria-hidden="true"></i>
            <span>${label}</span>
        `;
    }

    /**
     * Mengatur status tombol submit.
     */
    function setProcessing($form, processing) {
        const $submit = $form.find('[type="submit"]').first();
        if (!$submit.length) return;
        $form.data('simdes-submitting', processing);
        $submit
            .prop('disabled', processing)
            .html(processing ? processingHtml : getSubmitHtml($form[0]));
    }

    /**
     * Menghapus seluruh error validasi.
     */
    function clearValidationErrors($form) {
        $form.find('.is-invalid').removeClass('is-invalid');

        $form.find('.field-error-msg')
            .prop('hidden', true);

        $form.find('.field-error-text')
            .empty();

        $form.find('.invalid-feedback.ajax-error')
            .remove();
    }

    /**
     * Reset form.
     */
    function resetForm($form) {
        if (!$form.length) return;

        $form[0].reset();

        clearValidationErrors($form);

        $form.find('[data-file-preview]').empty();

        if (typeof window.hideFormBanner === 'function') {
            window.hideFormBanner();
        }

        setProcessing($form, false);
    }

    /**
     * Normalisasi nama field Laravel.
     * Contoh: items.0.name menjadi items[0][name].
     */
    function normalizeFieldName(name) {
        return name
            .replace(/\.(\d+)\./g, '[$1].')
            .replace(/\.(\w+)/g, '[$1]')
            .replace(/\.\[(\d+)\]/g, '[$1]')
            .replace(/\[\]/g, '');
    }

    /**
     * Mencari input berdasarkan nama field.
     */
    function findFieldInput($form, field) {
        const normalizedName = normalizeFieldName(field);

        return $form.find(':input').filter(function () {
            return (
                this.name === field ||
                this.name === normalizedName ||
                this.name === field + '[]'
            );
        }).first();
    }

    /**
     * Menampilkan pesan error pada input.
     */

    function showFieldError($input, message) {
    if (!$input.length) return;

        $input.addClass('is-invalid-field');

        const $field = $input.closest('.simdes-field');
        const $errorContainer = $field.find('.field-error-msg').first();

        if ($errorContainer.length) {
            $errorContainer
                .prop('hidden', false)
                .removeAttr('hidden')
                .css('display', 'block');

            $errorContainer
                .find('.field-error-text')
                .text(message);

            return;
        }

        const $feedback = $('<div>', {
            class: 'invalid-feedback ajax-error',
            text: message,
        });

        $input.after($feedback);
    }

    /**
     * Menampilkan seluruh error validasi Laravel.
     */
    function showValidationErrors($form, errors) {
        clearValidationErrors($form);

        let $firstInvalid = $();

        $.each(errors, function (field, messages) {
            const message = Array.isArray(messages)
                ? messages[0]
                : messages;
            const $input = findFieldInput($form, field);
            showFieldError($input, message);
            if (!$firstInvalid.length) {
                $firstInvalid = $input;
            }
        });

        if ($firstInvalid.length) {
            $firstInvalid.trigger('focus');
        }
    }

    /**
     * Menampilkan notifikasi.
     */
    function showNotification(message, type = 'success') {
        const variantMap = {
            success: {
                title: 'Berhasil',
                variant: 'success',
            },
            error: {
                title: 'Terjadi Kesalahan',
                variant: 'danger',
            },
            danger: {
                title: 'Terjadi Kesalahan',
                variant: 'danger',
            },
            warning: {
                title: 'Perhatian',
                variant: 'warning',
            },
            info: {
                title: 'Informasi Sistem',
                variant: 'primary',
            },
            primary: {
                title: 'Informasi Sistem',
                variant: 'primary',
            },
        };

        const config = variantMap[type] || variantMap.success;

        if (typeof window.showToast === 'function') {
            window.showToast({
                title: config.title,
                message: message,
                variant: config.variant,
                duration: 4200,
            });
        } else {
            console.warn(
                '[SIMDES Form] showToast tidak ditemukan:',
                message
            );
        }
    }

    /**
     * Menutup modal menggunakan Modal Manager.
     */
    function closeModal($form) {
        const modal = $form.closest('.modal')[0];

        if (!modal) return;

        if (
            window.SIMDES &&
            window.SIMDES.Modal &&
            typeof window.SIMDES.Modal.close === 'function'
        ) {
            window.SIMDES.Modal.close(modal);
            return;
        }

        if (window.bootstrap?.Modal) {
            window.bootstrap.Modal
                .getOrCreateInstance(modal)
                .hide();

            return;
        }
    }

    /**
     * Reload seluruh DataTables aktif.
     */
    function reloadDataTables() {
        if (!$.fn.DataTable) return;

        $('.dataTable').each(function () {
            if ($.fn.DataTable.isDataTable(this)) {
                $(this).DataTable().ajax.reload(null, false);
            }
        });
    }

    /**
     * Menangani response sukses.
     */
    function handleSuccess($form, response) {
        closeModal($form);

        showNotification(
            response.message || 'Data berhasil disimpan.',
            'success'
        );

        $(document).trigger('simdes:form-saved', [response]);

        if (response.redirect) {
            window.location.href = response.redirect;
            return;
        }

        reloadDataTables();
    }

    /**
     * Menangani response error.
     */
    function handleError($form, xhr) {
        const response = xhr.responseJSON || {};

        if (xhr.status === 422 && response.errors) {
            showValidationErrors($form, response.errors);

            showNotification(
                response.message || 'Periksa kembali data yang dimasukkan.',
                'error'
            );

            return;
        }

        if (xhr.status === 401 || xhr.status === 419) {
            showNotification(
                'Sesi Anda telah berakhir. Silakan muat ulang halaman.',
                'error'
            );

            return;
        }

        showNotification(
            response.message || 'Terjadi kesalahan saat memproses data.',
            'error'
        );
    }

    /**
     * Submit form menggunakan AJAX.
     */
    $(document).on('submit', FORM_SELECTOR, function (event) {
        event.preventDefault();

        const form = this;
        const $form = $(form);

        if ($form.data('simdes-submitting')) return;

        clearValidationErrors($form);

        const url = $form.attr('action');

        if (!url) {
            showNotification('URL tujuan tidak ditemukan.', 'error');
            return;
        }

        const formData = new FormData(form);

        setProcessing($form, true);

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',

            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },

            success: function (response) {
                handleSuccess($form, response);
            },

            error: function (xhr) {
                handleError($form, xhr);
            },

            complete: function () {
                setProcessing($form, false);
            },
        });
    });

    /**
     * Hapus error ketika input berubah.
     */
    $(document).on(
        'input change',
        FORM_SELECTOR + ' :input',
        function () {
            const $input = $(this);

            $input.removeClass('is-invalid');

            const $field = $input.closest('.simdes-field');

            $field.find('.field-error-msg')
                .prop('hidden', true)
                .find('.field-error-text')
                .empty();

            $input.next('.invalid-feedback.ajax-error').remove();
        }
    );

    /**
     * Reset form ketika modal ditutup.
     */
    $(document).on('hidden.bs.modal', MODAL_SELECTOR, function () {
        const $form = $(this).find(FORM_SELECTOR);

        if ($form.length) {
            resetForm($form);
        }
    });

})(window.jQuery, window, document);
