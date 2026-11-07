@props([
    'value' => '',
    'limit' => 50,
])

@pushOnce('header')
    <style>
        .custom-tooltip {
            --bs-tooltip-bg: #10382D;
            --bs-tooltip-color: #FFFFFF;
        }
    </style>
@endPushOnce

<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip"
    data-bs-title="{{ $value }}">
    {{ \Illuminate\Support\Str::limit($value, $limit, '...') }}
</span>
