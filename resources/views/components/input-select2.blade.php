@props([
    'name' => '',
    'label' => '',
    'options' => [],
    'placeholder' => '-- Pilih --',
    'hint' => null,
    'id' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'number' => null,
])

@php
    $id = $id ?? 'input-' . \Illuminate\Support\Str::slug($name);
    $hintId = $id . 'Hint';
    $errorId = $id . 'Error';

    $selectedValue = old($name, $value);
    $errorMessage = $error ?? $errors->first($name);
@endphp

<div class="simdes-field mb-1">
    <label for="{{ $id }}" class="simdes-label mb-0">
        @if ($number)
            {{ $number }}.
        @endif

        {{ $label }}

        @if ($required)
            <span class="req-star" aria-hidden="true">*</span>
        @endif
    </label>

    @if ($hint)
        <span id="{{ $hintId }}" class="simdes-field-hint my-0">
            {{ $hint }}
        </span>
    @endif

    <select id="{{ $id }}" name="{{ $name }}"
        class="simdes-select simdes-select2 {{ $errorMessage ? 'is-invalid' : '' }}"
        data-placeholder="{{ $placeholder }}" @required($required) @disabled($disabled)
        @if ($hint || $errorMessage) aria-describedby="{{ implode(' ', array_filter([$hint ? $hintId : null, $errorMessage ? $errorId : null])) }}" @endif
        @if ($errorMessage) aria-invalid="true" @endif {{ $attributes }}>
        <option value=""></option>

        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) $selectedValue === (string) $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    <div id="{{ $errorId }}" class="field-error-msg" role="status"
        @if (!$errorMessage) hidden @endif>
        <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
        <span class="field-error-text">{{ $errorMessage }}</span>
    </div>
</div>

@once
    @push('header')
        <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    @endpush

    @push('footer')
        <script src="{{ asset('assets/js/select2.min.js') }}"></script>

        <script>
            (function($) {
                'use strict';

                function initSimdesSelect2(context = document) {
                    $(context).find('.simdes-select2').each(function() {
                        const $select = $(this);

                        // Hindari inisialisasi berulang
                        if ($select.hasClass('select2-hidden-accessible')) {
                            return;
                        }

                        // Deteksi modal Bootstrap
                        const $modal = $select.closest('.modal');

                        $select.select2({
                            width: '100%',

                            placeholder: $select.data('placeholder') || '-- Pilih --',

                            allowClear: !$select.prop('required'),

                            // Dropdown mengikuti modal jika berada di dalam modal
                            dropdownParent: $modal.length ?
                                $modal : $(document.body),

                            language: {
                                noResults: function() {
                                    return 'Data tidak ditemukan';
                                },
                                searching: function() {
                                    return 'Mencari...';
                                },
                                inputTooShort: function() {
                                    return 'Masukkan kata pencarian';
                                }
                            }
                        });
                    });
                }

                // Inisialisasi awal
                $(document).ready(function() {
                    initSimdesSelect2();
                });

                // Inisialisasi ulang untuk konten AJAX
                window.initSimdesSelect2 = initSimdesSelect2;

            })
            (jQuery);
        </script>
    @endpush
@endonce
