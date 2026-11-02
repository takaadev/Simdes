
<div class="page-header">
    <div>
        {{-- Breadcrumb --}}
        <nav aria-label="Breadcrumb Halaman" class="mb-0">
            <ol class="simdes-breadcrumb">
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="fa-solid fa-house-chimney me-1" aria-hidden="true"></i>
                        {{ config('app.name') ?: 'SIMDES' }}
                    </a>
                </li>

                @foreach ($breadcrumbs as $breadcrumb)
                    <li @if ($loop->last) class="active" aria-current="page" @endif>
                        {{ $breadcrumb['label'] }}
                    </li>
                @endforeach
            </ol>
        </nav>

        {{-- Judul Halaman --}}
        <h4 class="mb-0 page-header-title">
            {{ $title }}
        </h4>

        {{-- Deskripsi Halaman --}}
        <p class="page-header-desc mt-0">
            {{ $description }}
        </p>
    </div>

    {{-- Tombol Aksi --}}
    @if (isset($actions))
        <div class="page-header-actions">
            {{ $actions }}
        </div>
    @endif
</div>
