@props([
    'href' => '#',
    'label' => 'Submenu',
    'icon' => 'angle-right',
    'active' => false,
    'ready' => true,
])

<li {{ $attributes->only(['class']) }}>
    <a href="{{ $href }}"
        @class([
            'sidebar-sublink',
            'active' => $active,
        ])
        @if ($active) aria-current="page" @endif
        data-book-ready="{{ $ready ? 'true' : 'false' }}"
        data-book-label="{{ $label }}">

        <span class="sidebar-sublink-icon">
            <i class="fa-solid fa-{{ $icon }}" aria-hidden="true"></i>
        </span>

        <span>{{ $label }}</span>
    </a>
</li>