@props([
    'label' => 'Detail',
    'icon' => 'eye',
    'type' => 'button',
    'href' => '',
    'modalTitle' => 'Detail Data',
    'size' => 'md',
    'id',
])

@php
    $componentId = 'simdes-show-' . $id;

    $modalId = $componentId . '-modal';
    $buttonId = $componentId . '-button';
    $titleId = $componentId . '-title';

    $modalSize = in_array($size, ['sm', 'lg', 'xl', 'fullscreen'])
        ? 'modal-' . $size
        : '';
@endphp

@if ($type === 'button')
    {{-- Tombol pembuka modal --}}
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

    {{-- Modal detail --}}
    <div
        class="modal fade simdes-modal"
        id="{{ $modalId }}"
        tabindex="-1"
        aria-labelledby="{{ $titleId }}"
        aria-hidden="true"
    >
        <div class="modal-dialog {{ $modalSize }} modal-dialog-scrollable">
            <div class="modal-content">

                {{-- Header --}}
                <div class="modal-header">
                    <h2 class="modal-title" id="{{ $titleId }}">
                        <i
                            class="fa-solid fa-{{ $icon }} text-simdes-primary me-2"
                            aria-hidden="true"
                        ></i>
                        {{ $modalTitle }}
                    </h2>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup"
                    ></button>
                </div>

                {{-- Body --}}
                <div class="modal-body p-4">
                    {{ $slot }}
                </div>

                {{-- Footer --}}
                <div
                    class="modal-footer bg-light border-top px-4 py-2"
                    style="border-color: var(--simdes-border) !important;"
                >
                    <button
                        type="button"
                        class="btn-simdes-outline"
                        data-bs-dismiss="modal"
                    >
                        <i class="fa-solid fa-xmark me-1"></i>
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    </div>
@else
    {{-- Mode link --}}
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