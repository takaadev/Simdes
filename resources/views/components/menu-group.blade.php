@props([
    'group' => 'umum',
    'label' => 'Menu',
    'icon' => null,
    'badge' => null,
    'tooltip' => null,
    'expanded' => false,
    'id' => null,
])

@php
    $panelId = $id ?? 'sidebarGroup' . str($group)->studly();
@endphp

<li {{ $attributes->only(['class']) }}>
    <button type="button" @class(['sidebar-link ', 'active' => $expanded]) data-sidebar-group="{{ $group }}"
        data-sidebar-tooltip="{{ $tooltip ?? $label }}" aria-expanded="{{ $expanded ? 'true' : 'false' }}"
        aria-controls="{{ $panelId }}">
        <span class="sidebar-link-icon">
            @if ($icon)
                <i class="fa-solid fa-{{ $icon }}" aria-hidden="true"></i>
            @endif
        </span>

        <span class="sidebar-link-text">{{ $label }}</span>

        @if ($badge !== null)
            <span class="sidebar-badge">{{ $badge }}</span>
        @endif

        <i class="fa-solid fa-chevron-down sidebar-chevron" aria-hidden="true"></i>
    </button>

    <div id="{{ $panelId }}" class="sidebar-group-panel" @if (!$expanded) hidden @endif>
        <ul class="sidebar-submenu">
            {{ $slot }}
        </ul>
    </div>
</li>
