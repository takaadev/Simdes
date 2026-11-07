@props([
    'name',
    'label',
    'type' => 'text',
    'id' => null,
    'placeholder' => '',
    'hint' => null,
    'value' => null,
    'number' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'min' => null,
    'max' => null,
    'step' => null,
    'minlength' => null,
    'maxlength' => null,
    'accept' => null,
    'multiple' => false,
    'error' => null,
])

@php
    $id = $id ?? 'input-' . \Illuminate\Support\Str::slug($name);
    $hintId = $id . 'Hint';
    $errorId = $id . 'Error';

    $isFile = $type === 'file';

    $inputValue = $isFile ? null : old($name, $value);
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

    <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}"
        class="simdes-input {{ $errorMessage ? 'is-invalid' : '' }}" placeholder="{{ $placeholder }}"
        @if (!$isFile) value="{{ $inputValue }}" @endif @required($required)
        @disabled($disabled) @readonly($readonly)
        @if ($min !== null) min="{{ $min }}" @endif
        @if ($max !== null) max="{{ $max }}" @endif
        @if ($step !== null) step="{{ $step }}" @endif
        @if ($minlength !== null) minlength="{{ $minlength }}" @endif
        @if ($maxlength !== null) maxlength="{{ $maxlength }}" @endif
        @if ($accept) accept="{{ $accept }}" @endif
        @if ($multiple) multiple @endif
        @if ($hint || $errorMessage) aria-describedby="{{ implode(' ', array_filter([$hint ? $hintId : null, $errorMessage ? $errorId : null])) }}" @endif
        {{ $attributes }}>

    <div id="{{ $errorId }}" class="field-error-msg" role="status"
        @if (!$errorMessage) hidden @endif>
        <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>

        <span class="field-error-text">
            {{ $errorMessage }}
        </span>
    </div>
</div>
