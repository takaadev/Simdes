<x-action-create href="{{ route('agendas.store') }}" size="lg" modalTitle="Tambah Surat Keluar" label="Surat Keluar"
    icon="share" color="btn-simdes-outline bg-orange">
    <x-input-text value="outgoing" name="tipe_agenda" type="hidden" />

    {{-- A. Informasi Surat Keluar --}}
    <x-fieldset label="A. Informasi Surat Keluar">
        <div class="row g-3">

            {{-- Kategori Surat --}}
            <div class="col-12 col-md-6">
                <label class="form-label">
                    Kategori Surat <span class="text-danger">*</span>
                </label>

                <select name="kategori_agenda" id="kategori_agenda" class="form-select" required>
                    <option value="">Pilih Kategori Surat</option>

                    @foreach (\App\Enums\AgendaCategoryList::groupedOptions() as $group => $options)
                    <optgroup label="{{ $group }}">
                        @foreach ($options as $value => $label)
                        <option value="{{ $value }}">
                            {{ $label }}
                        </option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
            </div>

            {{-- Nomor Surat --}}
            <div class="col-12 col-md-6">
                <label class="form-label">Nomor Surat</label>

                <div class="input-group">
                    <input type="text" name="nomor_surat" id="nomor_surat" class="form-control"
                        placeholder="Nomor surat belum dibuat" readonly required>

                    <button type="button" id="btnGenerateNumber" class="btn btn-simdes-primary"
                        title="Generate Nomor Surat">
                        <i class="fa-solid fa-wand-magic-sparkles me-1"></i>
                        Generate
                    </button>
                </div>

                <small class="text-muted">
                    Pilih kategori surat kemudian tekan Generate.
                </small>
            </div>

            {{-- Tanggal Surat --}}
            <div class="col-12 col-md-3">
                <x-input-text type="date" name="tgl_surat" label="Tanggal Surat" required />
            </div>

            {{-- Tanggal Kirim --}}
            <div class="col-12 col-md-3">
                <x-input-text type="date" name="tanggal_kirim" label="Tanggal Kirim"
                    value="{{ now()->format('Y-m-d') }}" required />
            </div>

            {{-- Tujuan --}}
            <div class="col-12 col-md-6">
                <x-input-text name="penerima" label="Tujuan / Penerima Surat"
                    placeholder="Nama instansi atau pihak penerima" required />
            </div>

            {{-- Perihal --}}
            <div class="col-12">
                <x-input-textarea name="isi_surat" label="Perihal Surat"
                    placeholder="Tuliskan perihal atau ringkasan isi surat..." :rows="3" required />
            </div>
            <div class="col-12">
                <div class="mt-3 p-3 rounded-3" style="background: #d3eed3; border: 1px solid rgb(7, 165, 31);">
                    <div class="form-check d-flex align-items-start gap-2">
                        <input type="hidden" name="has_expedition" value="false">

                        <input class="form-check-input mt-1" type="checkbox" id="has_expedition" name="has_expedition"
                            value="true">

                        <label class="form-check-label" for="has_expedition">
                            Centang jika surat ini juga ingin dicatat dalam Buku Ekspedisi.
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </x-fieldset>

    @push('footer')
    <script>
        (function ($, window) {
                'use strict';

                const $category = $('#kategori_agenda');
                const $number = $('#nomor_surat');
                const $button = $('#btnGenerateNumber');

                if (!$category.length || !$number.length || !$button.length) {
                    return;
                }

                /**
                 * Menampilkan notifikasi SIMDES.
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
                        warning: {
                            title: 'Perhatian',
                            variant: 'warning',
                        },
                        info: {
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
                    }
                }

                /**
                 * Status tombol Generate.
                 */
                function setProcessing(processing) {
                    $button
                        .prop('disabled', processing)
                        .html(
                            processing
                                ? '<span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span> Generate...'
                                : '<i class="fa-solid fa-wand-magic-sparkles me-1"></i> Generate'
                        );
                }

                /**
                 * Generate nomor surat.
                 */
                $button.on('click', function () {
                    const category = $category.val();

                    if (!category) {
                        showNotification(
                            'Silakan pilih kategori surat terlebih dahulu.',
                            'warning'
                        );

                        $category.trigger('focus');
                        return;
                    }

                    const selectedCategory = category;

                    setProcessing(true);

                    $.ajax({
                        url: "{{ route('agendas.generate-number') }}",
                        method: 'POST',
                        dataType: 'json',

                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },

                        data: {
                            kategori_agenda: selectedCategory,
                            _token: $('meta[name="csrf-token"]').attr('content'),
                        },

                        success: function (response) {
                            // Pastikan kategori belum berubah.
                            if ($category.val() !== selectedCategory) {
                                return;
                            }

                            if (response.success && response.nomor_surat) {
                                $number
                                    .val(response.nomor_surat)
                                    .trigger('change');

                                showNotification(
                                    'Nomor surat berhasil dibuat: ' + response.nomor_surat,
                                    'success'
                                );

                                return;
                            }

                            showNotification(
                                response.message || 'Nomor surat gagal dibuat.',
                                'error'
                            );
                        },

                        error: function (xhr) {
                            const response = xhr.responseJSON || {};

                            if (xhr.status === 422 && response.errors) {
                                const firstError = Object.values(response.errors)[0];

                                showNotification(
                                    Array.isArray(firstError)
                                        ? firstError[0]
                                        : 'Kategori surat tidak valid.',
                                    'warning'
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
                                response.message || 'Terjadi kesalahan saat membuat nomor surat.',
                                'error'
                            );
                        },

                        complete: function () {
                            setProcessing(false);
                        },
                    });
                });

                /**
                 * Reset nomor surat ketika kategori berubah.
                 */
                $category.on('change', function () {
                    $number.val('').trigger('change');
                });

            })(window.jQuery, window);
    </script>
    @endpush
</x-action-create>