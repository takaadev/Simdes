@props([
    'label' => 'Ringkasan Data',
])

<section class="row g-3 mb-4 ledger-kpi-row" aria-label="{{ $label }}">
    {{ $slot }}
</section>
