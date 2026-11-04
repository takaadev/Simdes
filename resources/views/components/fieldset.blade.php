@props([
    'label' => '',
])
<fieldset class="simdes-fieldset">
    @if ($label)
        <legend class="simdes-legend">{{ $label }}</legend>
    @endif
    {{ $slot }}
</fieldset>
