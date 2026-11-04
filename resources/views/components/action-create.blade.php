@props([
'label' => 'Tambah',
'icon' => 'plus-circle',
'type' => 'button',
'href' => '',
'modalTitle' => 'Tambah Data',
'size' => 'md',
])

@php
$componentId = 'simdes-form-' . \Illuminate\Support\Str::uuid();
$modalId = $componentId . '-modal';
$formId = $componentId . '-form';
$buttonId = $componentId . '-button';
$titleId = $componentId . '-title';
$submitId = $componentId . '-submit';

$modalSize = in_array($size, ['sm', 'lg', 'xl', 'fullscreen']) ? 'modal-' . $size : '';
@endphp

@if ($type === 'button')
<button type="button" id="{{ $buttonId }}" class="btn-simdes-primary">
    <i class="fa-solid fa-{{ $icon }}" aria-hidden="true"></i>
    <span>{{ $label }}</span>
</button>

<div class="modal fade simdes-modal" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $titleId }}"
    aria-hidden="true">
    <div class="modal-dialog {{ $modalSize }}  modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title" id="{{ $titleId }}">
                        <i class="fa-solid fa-{{ $icon }} text-simdes-primary me-2" aria-hidden="true"></i>
                        {{ $modalTitle }}
                    </h2>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup formulir"></button>
            </div>

            <form id="{{ $formId }}" action="{{ $href }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                <div class="modal-body p-4">
                    {{ $slot }}
                </div>

                <div class="modal-footer bg-light border-top px-4 py-3"
                    style="border-color:var(--simdes-border) !important;">
                    <button type="button" class="btn-simdes-outline" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" id="{{ $submitId }}" class="btn-simdes-primary">
                        <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                        <span>Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('footer')
<script>
    $(function() {
                const $modal = $('#{{ $modalId }}');
                const $form = $('#{{ $formId }}');
                const $button = $('#{{ $buttonId }}');
                const $submit = $('#{{ $submitId }}');

                const defaultSubmitHtml = `
                    <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                    <span>Simpan</span>
                `;

                const processingHtml = `
                    <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                    <span>Memproses...</span>
                `;

                let isSubmitting = false;
                let currentRequest = null;

                /**
                 * Buka modal tambah.
                 */
                function openCreateModal() {
                    resetForm();
                    $modal.modal('show');
                }

                /**
                 * Menghapus seluruh error validasi.
                 */
                function clearValidationErrors() {
                    $form.find('.is-invalid').removeClass('is-invalid');
                    $form.find('.field-error-msg').prop('hidden', true);
                    $form.find('.field-error-text').empty();
                    $form.find('.invalid-feedback.ajax-error').remove();
                }

                /**
                 * Reset seluruh input form.
                 */
                function resetForm() {
                    if ($form.length) {
                        $form[0].reset();
                    }

                    clearValidationErrors();

                    if (typeof window.hideFormBanner === 'function') {
                        window.hideFormBanner();
                    }

                    // Bersihkan preview file jika menggunakan preview.
                    $form.find('[data-file-preview]').empty();

                    // Kembalikan tombol submit.
                    setProcessing(false);
                }

                /**
                 * Mengatur status tombol submit.
                 */
                function setProcessing(processing) {
                    isSubmitting = processing;

                    $submit
                        .prop('disabled', processing)
                        .html(processing ? processingHtml : defaultSubmitHtml);
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
                 * Menampilkan error validasi pada input.
                 */
                function showValidationErrors(errors) {
                    clearValidationErrors();

                    $.each(errors, function(field, messages) {
                        const normalizedName = normalizeFieldName(field);
                        const message = Array.isArray(messages) ? messages[0] : messages;

                        const $input = $form.find(':input').filter(function() {
                            return this.name === field ||
                                this.name === normalizedName ||
                                this.name === field + '[]';
                        }).first();

                        if (!$input.length) {
                            return;
                        }

                        $input.addClass('is-invalid');

                        // Gunakan error bawaan component.
                        const $errorContainer = $input
                            .closest('.simdes-field')
                            .find('.field-error-msg')
                            .first();

                        if ($errorContainer.length) {
                            $errorContainer
                                .prop('hidden', false)
                                .find('.field-error-text')
                                .text(message);
                        } else {
                            // Fallback untuk input yang belum menggunakan component.
                            $input.after(
                                $('<div>', {
                                    class: 'invalid-feedback ajax-error',
                                    text: message,
                                })
                            );
                        }
                    });

                    $form.find('.is-invalid').first().trigger('focus');
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

                    window.showToast({
                        title: config.title,
                        message,
                        variant: config.variant,
                        duration: 4200,
                    });
                }
                /**
                 * Memproses respons sukses.
                 */
                function handleSuccess(response) {
                    $modal.modal('hide');

                    showNotification(
                        response.message || 'Data berhasil disimpan.',
                        'success'
                    );
                    $(document).trigger('simdes:form-saved', [response]);
                    if (response.redirect) {
                        window.location.href = response.redirect;
                        return;
                    }
                    if ($.fn.DataTable) {
                        $('.dataTable').each(function() {
                            if ($.fn.DataTable.isDataTable(this)) {
                                $(this).DataTable().ajax.reload(null, false);
                            }
                        });
                    }
                }

                /**
                 * Submit AJAX.
                 */
                $form.on('submit', function(event) {
                    event.preventDefault();

                    if (isSubmitting) {
                        return;
                    }

                    clearValidationErrors();

                    const formData = new FormData(this);
                    const url = $form.attr('action');

                    if (!url) {
                        showNotification('URL tujuan tidak ditemukan.', 'error');
                        return;
                    }

                    setProcessing(true);

                    currentRequest = $.ajax({
                        url: url,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',

                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },

                        success: function(response) {
                            handleSuccess(response);
                        },

                        error: function(xhr) {
                            const response = xhr.responseJSON;

                            // Laravel validation error.
                            if (xhr.status === 422 && response?.errors) {
                                showValidationErrors(response.errors);

                                showNotification(
                                    response.message || 'Periksa kembali data yang dimasukkan.',
                                    'error'
                                );

                                return;
                            }

                            // Session expired / unauthenticated.
                            if (xhr.status === 401 || xhr.status === 419) {
                                showNotification(
                                    'Sesi Anda telah berakhir. Silakan muat ulang halaman.',
                                    'error'
                                );

                                return;
                            }

                            showNotification(
                                response?.message || 'Terjadi kesalahan saat menyimpan data.',
                                'error'
                            );
                        },

                        complete: function() {
                            currentRequest = null;
                            setProcessing(false);
                        },
                    });
                });

                /**
                 * Bersihkan error ketika input berubah.
                 */
                $form.on('input change', ':input', function() {
                    const $input = $(this);
                    $input.removeClass('is-invalid');
                    $input
                        .closest('.simdes-field')
                        .find('.field-error-msg')
                        .prop('hidden', true)
                        .find('.field-error-text')
                        .empty();

                    $input.next('.invalid-feedback.ajax-error').remove();
                });

                /**
                 * Reset ketika modal ditutup.
                 * Berlaku untuk tombol Cancel, Close, dan backdrop.
                 */
                $modal.on('hidden.bs.modal', function() {
                    resetForm();
                });

                /**
                 * Tombol buka modal.
                 */
                $button.on('click', openCreateModal);
            });
</script>
@endpush
@else
<a href="{{ $href ?: '#' }}" class="btn-simdes-primary">
    <i class="fa-solid fa-{{ $icon }}" aria-hidden="true"></i>
    <span>{{ $label }}</span>
</a>
@endif