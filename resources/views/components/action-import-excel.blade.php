@props([
    'label' => 'Import',
    'icon' => 'print',
    'type' => 'button',
    'href' => '',
    'modalTitle' => 'Tambah Data',
    'size' => 'md',
    'color' => 'btn-simdes-primary',
])

@php
    $componentId = 'simdes-create-' . \Illuminate\Support\Str::uuid();

    $modalId = $componentId . '-modal';
    $formId = $componentId . '-form';
    $buttonId = $componentId . '-button';
    $titleId = $componentId . '-title';
    $submitId = $componentId . '-submit';

    $modalSize = in_array($size, ['sm', 'lg', 'xl', 'fullscreen']) ? 'modal-' . $size : '';
@endphp

@if ($type === 'button')
    <button type="button" id="{{ $buttonId }}" class="{{ $color }}"
        data-simdes-modal-open="{{ $modalId }}">
        <i class="fa-solid fa-{{ $icon }}" aria-hidden="true"></i>
        <span>{{ $label }}</span>
    </button>

    <div class="modal fade simdes-modal" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $titleId }}"
        aria-hidden="true">
        <div class="modal-dialog {{ $modalSize }} modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h2 class="modal-title" id="{{ $titleId }}">
                        <i class="fa-solid fa-{{ $icon }} text-simdes-primary me-2" aria-hidden="true"></i>
                        {{ $modalTitle }}
                    </h2>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Tutup formulir"></button>
                </div>

                <form id="{{ $formId }}" action="{{ $href }}" method="POST"
                    enctype="multipart/form-data" novalidate data-simdes-ajax-form data-simdes-form-type="create"
                    data-simdes-submit-label="Simpan" data-simdes-submit-icon="floppy-disk">
                    @csrf

                    <div class="modal-body p-4">
                        {{ $slot }}
                    </div>

                    <div class="modal-footer bg-light border-top py-1"
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
@else
    <a href="{{ $href ?: '#' }}" class="btn-simdes-primary">
        <i class="fa-solid fa-{{ $icon }}" aria-hidden="true"></i>
        <span>{{ $label }}</span>
    </a>
@endif
