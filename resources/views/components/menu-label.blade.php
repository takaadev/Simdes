@props([
    'label' => 'MODUL',
    'class' => '',
])

<div {{ $attributes->merge(['class' => 'sidebar-section-label ' . $class]) }}>
    {{ $label }}
</div>
