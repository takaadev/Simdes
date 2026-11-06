@props([
    'label' => 'Hapus',
    'icon' => 'trash-can',
    'type' => 'button',
    'href' => '',
    'modalTitle' => 'Konfirmasi Hapus',
    'id',
])

@php
    $componentId = 'simdes-delete-' . $id;

    $modalId = $componentId . '-modal';
    $formId = $componentId . '-form';
    $buttonId = $componentId . '-button';
    $titleId = $componentId . '-title';
    $submitId = $componentId . '-submit';
    $confirmId = $componentId . '-confirm';
@endphp

@if ($type === 'button')
    <button type="button" id="{{ $buttonId }}" class="btn-row-action" data-simdes-modal-open="{{ $modalId }}"
        title="{{ $label }}" aria-label="{{ $label }}">
        <i class="fa-solid fa-{{ $icon }}" aria-hidden="true"></i>
    </button>

    <div class="modal fade simdes-modal" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $titleId }}"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" id="{{ $formId }}" action="{{ $href }}" method="POST" novalidate
                data-simdes-ajax-form data-simdes-form-type="delete" data-simdes-submit-label="Hapus Permanen"
                data-simdes-submit-icon="trash-can" data-simdes-confirm-delete novalidate>
                @csrf
                @method('DELETE')

                <div class="modal-header">
                    <h2 class="modal-title" id="{{ $titleId }}" style="color: var(--simdes-danger);">
                        <i class="fa-solid fa-triangle-exclamation me-2" aria-hidden="true"></i>
                        {{ $modalTitle }}
                    </h2>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Tutup konfirmasi"></button>
                </div>

                <div class="modal-body p-4" style="font-size: 0.88rem;">
                    <p class="mb-2 text-simdes-muted">
                        Apakah Anda yakin ingin menghapus data ini?
                    </p>

                    {{ $slot }}

                    <div class="mt-3 p-3 rounded-3" style="background: #FFF9F9; border: 1px solid rgba(220,53,69,.15);">

                        <div class="form-check d-flex align-items-start gap-2">
                            <input class="form-check-input mt-1" type="checkbox" id="{{ $confirmId }}"
                                name="confirm_delete" value="1" required>
                            <label class="form-check-label" for="{{ $confirmId }}" style="font-size: .84rem;">
                                Centang untuk hapus data permanen
                            </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn-simdes-outline" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button id="{{ $submitId }}" type="submit" class="btn-simdes-primary"
                        style="background-color: var(--simdes-danger);
                               border-color: var(--simdes-danger);">
                        <i class="fa-regular fa-trash-can" aria-hidden="true"></i>
                        <span>Ya, Hapus</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@else
    <a href="{{ $href ?: '#' }}" class="btn-row-action" title="{{ $label }}"
        aria-label="{{ $label }}">
        <i class="fa-solid fa-{{ $icon }}" aria-hidden="true"></i>
    </a>
@endif
