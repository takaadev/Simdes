@props([
    'icon' => 'bi-info-circle',
    'color' => 'blue',
    'circle' => false,
    'size' => 'md',
])

@php
    $colors = [
        'blue' => 'badge-glass-blue',
        'red' => 'badge-glass-red',
        'green' => 'badge-glass-green',
        'yellow' => 'badge-glass-yellow',
        'orange' => 'badge-glass-orange',
        'purple' => 'badge-glass-purple',
        'pink' => 'badge-glass-pink',
        'cyan' => 'badge-glass-cyan',
        'gray' => 'badge-glass-gray',
        'dark' => 'badge-glass-dark',
    ];

    $sizes = [
        'sm' => 'badge-sm',
        'md' => 'badge-md',
        'lg' => 'badge-lg',
    ];

    $colorClass = $colors[$color] ?? $colors['blue'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<span {{ $attributes->class(['badge-glass', 'badge-glass-icon', $colorClass, $sizeClass, 'badge-circle' => $circle]) }}>
    <i class="bi {{ $icon }}"></i>
    @if (trim($slot))
        <span>{{ $slot }}</span>
    @endif
</span>
@push('header')
    <link rel="stylesheet" href="{{ asset('assets/css/badge.css') }}">
@endpush
