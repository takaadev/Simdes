@props([
    // Tombol pemanggil.
    'label' => 'Import Excel',
    'icon' => 'file-excel',
    'type' => 'button',
    'href' => '',
    'color' => 'btn-simdes-excel',

    // Modal.
    'modalTitle' => 'Import Data',
    'size' => 'lg',

    // Form.
    'submitLabel' => 'Simpan',
    'submitIcon' => 'floppy-disk',
    'formType' => 'import',

    // Template Excel.
    'templateTitle' => null,
    'templateDescription' => null,
    'templateUrl' => null,
    'templateLabel' => 'Unduh Template (.xlsx)',

    // Upload file.
    'fileName' => 'file',
    'fileLabel' => 'Pilih Berkas Excel',
    'fileHint' => 'Mendukung format Microsoft Excel (.xlsx, .xls) maupun CSV.',
    'accept' => '.xlsx,.xls,.csv',

    // Mode import.
    'showImportMode' => false,
    'importModes' => [],

    // Alert.
    'showAlert' => true,
])

@php
    use Illuminate\Support\Str;

    $componentId = 'simdes-import-' . Str::uuid();

    $modalId = $componentId . '-modal';
    $formId = $componentId . '-form';
    $buttonId = $componentId . '-button';
    $titleId = $componentId . '-title';
    $submitId = $componentId . '-submit';
    $fileId = $componentId . '-file';
    $dropzoneId = $componentId . '-dropzone';
    $alertId = $componentId . '-alert';
    $alertTextId = $componentId . '-alert-text';

    $modalSize = in_array($size, ['sm', 'lg', 'xl', 'fullscreen']) ? 'modal-' . $size : '';

    $defaultModes = [
        [
            'value' => 'append',
            'label' => 'Tambahkan ke Data Ada',
            'icon' => 'layer-group',
        ],
        [
            'value' => 'replace',
            'label' => 'Ganti Seluruh Data',
            'icon' => 'arrows-rotate',
        ],
    ];

    $importModes = $importModes ?: $defaultModes;

    $hasTemplate = $templateTitle || $templateDescription || $templateUrl;
@endphp

@if ($type === 'button')
    {{-- Trigger Button --}}
    <button type="button" id="{{ $buttonId }}" class="{{ $color }}" data-simdes-modal-open="{{ $modalId }}"
        {{ $attributes }}>
        <i class="fa-solid fa-{{ $icon }}" aria-hidden="true"></i>
        <span>{{ $label }}</span>
    </button>

    {{-- Import Modal --}}
    <div class="modal fade simdes-modal" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $titleId }}"
        aria-hidden="true" data-simdes-import-modal>
        <div class="modal-dialog {{ $modalSize }} modal-dialog-scrollable">
            <div class="modal-content">

                {{-- Header --}}
                <div class="modal-header">
                    <h2 class="modal-title" id="{{ $titleId }}">
                        <i class="fa-solid fa-{{ $icon }} text-simdes-primary me-2" aria-hidden="true"></i>
                        {{ $modalTitle }}
                    </h2>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Tutup formulir"></button>
                </div>

                {{-- Form --}}
                <form id="{{ $formId }}" action="{{ $href }}" method="POST"
                    enctype="multipart/form-data" novalidate data-simdes-ajax-form
                    data-simdes-form-type="{{ $formType }}" data-simdes-submit-label="{{ $submitLabel }}"
                    data-simdes-submit-icon="{{ $submitIcon }}" data-simdes-import-form>
                    @csrf

                    <div class="modal-body p-4">

                        {{-- Alert --}}
                        @if ($showAlert)
                            <div id="{{ $alertId }}" class="login-alert login-alert-error" role="alert"
                                aria-live="assertive" data-simdes-import-alert hidden>
                                <i class="fa-solid fa-circle-exclamation mt-1" aria-hidden="true"></i>

                                <span id="{{ $alertTextId }}" data-simdes-import-alert-text></span>
                            </div>
                        @endif

                        {{-- Template Download --}}
                        @if ($hasTemplate)
                            <div class="import-template-card">
                                <div style="flex: 1 1 280px;">
                                    @if ($templateTitle)
                                        <div class="fw-bold"
                                            style="font-size: 0.88rem; color: var(--simdes-deep-green);">
                                            <i class="fa-solid fa-table-list me-1 text-simdes-primary"
                                                aria-hidden="true"></i>

                                            {{ $templateTitle }}
                                        </div>
                                    @endif

                                    @if ($templateDescription)
                                        <div class="text-simdes-muted mt-1"
                                            style="font-size: 0.78rem; line-height: 1.5;">
                                            {{ $templateDescription }}
                                        </div>
                                    @endif
                                </div>

                                @if ($templateUrl)
                                    <a href="{{ $templateUrl }}" class="btn-simdes-outline bg-white"
                                        data-simdes-template-download>
                                        <i class="fa-solid fa-download text-simdes-primary" aria-hidden="true"></i>

                                        <span>{{ $templateLabel }}</span>
                                    </a>
                                @endif
                            </div>
                        @endif

                        {{-- Upload File --}}
                        <fieldset class="simdes-fieldset">
                            <legend class="simdes-legend">
                                1. {{ $fileLabel }}
                            </legend>

                            <div class="simdes-excel-dropzone" id="{{ $dropzoneId }}" data-simdes-import-dropzone>
                                <label for="{{ $fileId }}" class="simdes-label mb-1">
                                    {{ $fileLabel }}
                                    <span class="req-star" aria-hidden="true">*</span>
                                </label>

                                <span id="{{ $fileId }}-hint" class="simdes-field-hint mb-2">
                                    {{ $fileHint }}
                                </span>

                                <input type="file" id="{{ $fileId }}" name="{{ $fileName }}"
                                    class="simdes-file-input" accept="{{ $accept }}"
                                    aria-describedby="{{ $fileId }}-hint" required data-simdes-import-file>
                            </div>
                        </fieldset>

                        {{-- Import Mode --}}
                        @if ($showImportMode)
                            <fieldset class="simdes-fieldset">
                                <legend class="simdes-legend">
                                    2. Mode Penyimpanan Import
                                </legend>

                                <div class="simdes-field mb-1" role="radiogroup" aria-label="Mode penyimpanan import">

                                    <span class="simdes-field-hint mb-2">
                                        Tentukan cara penggabungan data Excel
                                        dengan data yang sudah tersedia.
                                    </span>

                                    <div class="simdes-radio-group">
                                        @foreach ($importModes as $index => $mode)
                                            @php
                                                $modeId = $componentId . '-mode-' . $index;
                                            @endphp

                                            <div class="simdes-radio-option">
                                                <input type="radio" id="{{ $modeId }}" name="importMode"
                                                    value="{{ $mode['value'] }}" class="simdes-radio-input"
                                                    @checked($index === 0)>

                                                <label for="{{ $modeId }}" class="simdes-radio-pill">
                                                    <i class="fa-solid fa-{{ $mode['icon'] ?? 'circle' }}"
                                                        aria-hidden="true"></i>

                                                    <span>{{ $mode['label'] }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </fieldset>
                        @endif

                    </div>

                    {{-- Footer --}}
                    <div class="modal-footer bg-light border-top py-1"
                        style="border-color: var(--simdes-border) !important;">
                        <button type="button" class="btn-simdes-outline" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button type="submit" id="{{ $submitId }}" class="btn-simdes-primary"
                            data-simdes-import-submit>
                            <i class="fa-solid fa-{{ $submitIcon }}" aria-hidden="true"></i>

                            <span>{{ $submitLabel }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@else
    {{-- Link Mode --}}
    <a href="{{ $href ?: '#' }}" class="{{ $color }}" {{ $attributes }}>
        <i class="fa-solid fa-{{ $icon }}" aria-hidden="true"></i>
        <span>{{ $label }}</span>
    </a>
@endif
