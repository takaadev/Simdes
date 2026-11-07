@props([
    'label' => 'Pulihkan',
    'icon' => 'rotate-left',
    'href' => '',
    'modalTitle' => 'Pulihkan Data',
    'size' => 'md',
    'id',
])

@php
    $componentId = 'simdes-restore-' . $id;
    $modalId = $componentId . '-modal';
    $formId = $componentId . '-form';
    $buttonId = $componentId . '-button';
    $titleId = $componentId . '-title';
    $submitId = $componentId . '-submit';

    $modalSize = in_array($size, ['sm', 'lg', 'xl', 'fullscreen']) ? 'modal-' . $size : '';
@endphp

<button type="button" id="{{ $buttonId }}" class="btn-row-action" data-simdes-modal-open="{{ $modalId }}"
    title="{{ $label }}" aria-label="{{ $label }}">
    <i class="fa-solid fa-{{ $icon }}" aria-hidden="true"></i>
</button>

<div class="modal fade simdes-modal" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $titleId }}"
    aria-hidden="true">
    <div class="modal-dialog {{ $modalSize }} modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="{{ $titleId }}">
                    <i class="fa-solid fa-{{ $icon }} text-simdes-primary me-2" aria-hidden="true"></i>
                    {{ $modalTitle }}
                </h2>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <form id="{{ $formId }}" action="{{ $href }}" method="POST" novalidate
                data-simdes-ajax-form data-simdes-form-type="restore" data-simdes-submit-label="Pulihkan Data"
                data-simdes-submit-icon="rotate-left">
                @csrf
                @method('PATCH')

                <div class="modal-body p-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="text-warning fs-3">
                            <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
                        </div>

                        <div>
                            <h6 class="fw-semibold mb-2">
                                Konfirmasi Pemulihan Data
                            </h6>

                            <p class="text-muted mb-0">
                                Apakah Anda yakin ingin memulihkan data ini?
                                Data yang dipulihkan akan dikembalikan ke daftar data aktif.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light border-top px-4 py-1"
                    style="border-color:var(--simdes-border) !important;">
                    <button type="button" class="btn-simdes-outline" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" id="{{ $submitId }}" class="btn-simdes-primary">
                        <i class="fa-solid fa-rotate-left" aria-hidden="true"></i>
                        <span>Pulihkan Data</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
