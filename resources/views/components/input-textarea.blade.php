@props([
    'name',
    'label',
    'id' => null,
    'placeholder' => '',
    'hint' => null,
    'value' => null,
    'number' => null,
    'rows' => 3,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'minlength' => null,
    'maxlength' => null,
    'error' => null,
])

@php
    $id = $id ?? 'input-' . \Illuminate\Support\Str::slug($name);
    $hintId = $id . 'Hint';
    $errorId = $id . 'Error';

    $inputValue = old($name, $value);
    $errorMessage = $error ?? $errors->first($name);
@endphp

<div class="simdes-field mb-2">
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

    <textarea id="{{ $id }}" name="{{ $name }}"
        class="simdes-textarea {{ $errorMessage ? 'is-invalid' : '' }}" rows="{{ $rows }}"
        placeholder="{{ $placeholder }}" @required($required) @disabled($disabled) @readonly($readonly)
        @if ($minlength !== null) minlength="{{ $minlength }}" @endif
        @if ($maxlength !== null) maxlength="{{ $maxlength }}" @endif
        @if ($hint || $errorMessage) aria-describedby="{{ implode(' ', array_filter([$hint ? $hintId : null, $errorMessage ? $errorId : null])) }}" @endif
        {{ $attributes }}>{{ $inputValue }}</textarea>

    <div id="{{ $errorId }}" class="field-error-msg" role="status"
        @if (!$errorMessage) hidden @endif>
        <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
        <span class="field-error-text">{{ $errorMessage }}</span>
    </div>
</div>
