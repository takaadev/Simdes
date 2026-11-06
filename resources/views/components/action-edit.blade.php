
@props([
    'label' => 'Ubah',
    'icon' => 'pen-to-square',
    'type' => 'button',
    'href' => '',
    'modalTitle' => 'Ubah Data',
    'size' => 'md',
    'id',
])

@php
    $componentId = 'simdes-edit-' . $id;

    $modalId = $componentId . '-modal';
    $formId = $componentId . '-form';
    $buttonId = $componentId . '-button';
    $titleId = $componentId . '-title';
    $submitId = $componentId . '-submit';

    $modalSize = in_array($size, ['sm', 'lg', 'xl', 'fullscreen'])
        ? 'modal-' . $size
        : '';
@endphp

@if ($type === 'button')
    <button
        type="button"
        id="{{ $buttonId }}"
        class="btn-row-action"
        data-simdes-modal-open="{{ $modalId }}"
        title="{{ $label }}"
        aria-label="{{ $label }}"
    >
        <i class="fa-solid fa-{{ $icon }}" aria-hidden="true"></i>
    </button>

    <div
        class="modal fade simdes-modal"
        id="{{ $modalId }}"
        tabindex="-1"
        aria-labelledby="{{ $titleId }}"
        aria-hidden="true"
    >
        <div class="modal-dialog {{ $modalSize }} modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h2 class="modal-title" id="{{ $titleId }}">
                        <i class="fa-solid fa-{{ $icon }} text-simdes-primary me-2" aria-hidden="true"></i>
                        {{ $modalTitle }}
                    </h2>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup"
                    ></button>
                </div>

                <form
                    id="{{ $formId }}"
                    action="{{ $href }}"
                    method="POST"
                    enctype="multipart/form-data"
                    novalidate
                    data-simdes-ajax-form
                    data-simdes-form-type="edit"
                    data-simdes-submit-label="Simpan Perubahan"
                    data-simdes-submit-icon="floppy-disk"
                >
                    @csrf
                    @method('PUT')

                    <div class="modal-body p-4">
                        {{ $slot }}
                    </div>

                    <div class="modal-footer bg-light border-top px-4 py-1"
                        style="border-color:var(--simdes-border) !important;">

                        <button
                            type="button"
                            class="btn-simdes-outline"
                            data-bs-dismiss="modal"
                        >
                            Batal
                        </button>

                        <button
                            type="submit"
                            id="{{ $submitId }}"
                            class="btn-simdes-primary"
                        >
                            <i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@else
    <a
        href="{{ $href ?: '#' }}"
        class="btn-row-action"
        title="{{ $label }}"
        aria-label="{{ $label }}"
    >
        <i class="fa-solid fa-{{ $icon }}" aria-hidden="true"></i>
        <span>{{ $label }}</span>
    </a>
@endif
