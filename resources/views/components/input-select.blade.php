@props([
    'name' => '',
    'label' => '',
    'options' => [],
    'placeholder' => '-- Pilih --',
    'hint' => null,
    'id' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'number' => null,
])

@php
    $id = $id ?? 'input-' . \Illuminate\Support\Str::slug($name);
    $hintId = $id . 'Hint';
    $errorId = $id . 'Error';

    $selectedValue = old($name, $value);
    $errorMessage = $error ?? $errors->first($name);
@endphp

<div class="simdes-field mb-1">
    <label for="{{ $id }}" class="simdes-label mb-0">
        @if ($number)
            {{ $number }}.
        @endif

        {{ $label }}

        @if ($required)
            <span class="req-star" aria-hidden="true">*</span>
        @endif
    </label>

    @if ($hint)
        <span id="{{ $hintId }}" class="simdes-field-hint my-0">
            {{ $hint }}
        </span>
    @endif

    <select id="{{ $id }}" name="{{ $name }}"
        class="simdes-select {{ $errorMessage ? 'is-invalid' : '' }}" @required($required) @disabled($disabled)
        @if ($hint || $errorMessage) aria-describedby="{{ implode(' ', array_filter([$hint ? $hintId : null, $errorMessage ? $errorId : null])) }}" @endif
        {{ $attributes }}>
        <option class="text-center" value="">--select--</option>

        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) $selectedValue === (string) $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>

    <div id="{{ $errorId }}" class="field-error-msg" role="status"
        @if (!$errorMessage) hidden @endif>
        <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
        <span class="field-error-text">
            {{ $errorMessage }}
        </span>
    </div>
</div>
