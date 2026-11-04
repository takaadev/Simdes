@props([
'src' => null,
'title' => 'Pratinjau Dokumen PDF',
'size' => 'xl',
'label' => 'Lihat dokumen',
])

@if (filled($src))
@php
$modalId = 'view-pdf-' . \Illuminate\Support\Str::uuid();
$pdfUrl = asset('storage/' . ltrim($src, '/'));
@endphp

{{-- Trigger --}}
<button type="button" {{ $attributes->merge(['class' => 'btn badge btn-sm bg-danger']) }}
    data-bs-toggle="modal"
    data-bs-target="#{{ $modalId }}"
    >
    <i class="fa-solid fa-file-pdf me-1"></i>
    {{ filled($label) ? $label : 'Lihat dokumen' }}
</button>
{{-- Modal --}}
<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}-title" aria-hidden="true">
    <div class="modal-dialog modal-{{ $size }} modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content simdes-modal">

            {{-- Header --}}
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-semibold" id="{{ $modalId }}-title">
                        <i class="fa-solid fa-file-pdf text-danger me-2"></i>
                        {{ $title }}
                    </h5>

                    <small class="text-muted">
                        Pratinjau dokumen PDF
                    </small>
                </div>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-0">
                <div class="view-pdf-container">
                    <embed src="{{ $pdfUrl }}" type="application/pdf" class="view-pdf-embed">
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer">
                <a href="{{ $pdfUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-simdes-outline">
                    <i class="fa-solid fa-up-right-from-square me-1"></i>
                    Buka Tab Baru
                </a>

                <button type="button" class="btn btn-simdes-primary" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>
@else
<span {{ $attributes->only(['class']) }}>
    {{ $hasSlot ? $slot : '-' }}
</span>
@endif

@once
<style>
    .view-pdf-container {
        width: 100%;
        height: 75vh;
        min-height: 400px;
        background: #525659;
    }

    .view-pdf-embed {
        display: block;
        width: 100%;
        height: 100%;
        border: 0;
    }

    @media (max-width: 576px) {
        .view-pdf-container {
            height: 70vh;
            min-height: 350px;
        }
    }
</style>
@endonce