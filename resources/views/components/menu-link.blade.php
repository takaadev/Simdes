@props([
    'href' => '#',
    'label' => 'Menu',
    'icon' => null,
    'active' => true,
    'tooltip' => null,
])

<li {{ $attributes->only(['class'])->merge() }}>
    <a href="{{ $href }}" @class(['sidebar-link', 'active' => $active])
        @if ($active) aria-current="page" @endif data-sidebar-tooltip="{{ $tooltip ?? $label }}">
        <span class="sidebar-link-icon">
            @if ($icon)
                <i class="fa-solid fa-{{ $icon }}" aria-hidden="true"></i>
            @endif
        </span>

        <span class="sidebar-link-text">{{ $label }}</span>
    </a>
</li>
