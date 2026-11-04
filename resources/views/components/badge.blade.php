@props([
    'color' => 'primary',
    'circle' => false,
    'size' => 'md',
])

@php
    $colors = [
        'blue' => 'primary',
        'primary' => 'primary',
        'gray' => 'secondary',
        'secondary' => 'secondary',
        'green' => 'success',
        'success' => 'success',
        'red' => 'danger',
        'danger' => 'danger',
        'yellow' => 'warning',
        'warning' => 'warning',
        'cyan' => 'info',
        'info' => 'info',
        'light' => 'light',
        'dark' => 'dark',
        'orange' => 'warning',
        'purple' => 'primary',
        'pink' => 'danger',
    ];

    $colorClass = $colors[$color] ?? 'primary';

    $sizes = [
        'sm' => 'badge-sm',
        'md' => 'badge-md',
        'lg' => 'badge-lg',
    ];

    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp
<div class="w-100 text-center">
    <span {{ $attributes->class([
        'badge',
        'rounded-pill',
        "text-bg-{$colorClass}",
        $sizeClass,
        'badge-circle' => $circle,
    ]) }}>
        {{ $slot }}
    </span>

</div>

@once
    @push('header')
        <style>
            .badge-sm {
                font-size: 10px;
                padding: 4px 8px;
            }

            .badge-md {
                font-size: 12px;
                padding: 5px 10px;
            }

            .badge-lg {
                font-size: 14px;
                padding: 7px 13px;
            }

            .badge-circle {
                width: 32px;
                height: 32px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                padding: 0;
                border-radius: 50% !important;
            }

            .badge-circle.badge-sm {
                width: 25px;
                height: 25px;
            }

            .badge-circle.badge-lg {
                width: 40px;
                height: 40px;
            }
        </style>
    @endpush
@endonce