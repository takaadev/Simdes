
@props([
    'name',
    'label',
    'id' => null,
    'number' => null,
    'options' => [],
    'value' => null,
    'required' => false,
    'disabled' => false,
    'hint' => null,
    'error' => null,
])

@php
    $id = $id ?? 'radio-' . \Illuminate\Support\Str::slug($name);
    $hintId = $id . 'Hint';
    $errorId = $id . 'Error';

    $selectedValue = old($name, $value);
    $errorMessage = $error ?? $errors->first($name);

    $hasError = (bool) $errorMessage;
@endphp

<div class="simdes-field mb-2">
    <span class="simdes-label" id="{{ $id }}Label">
        @if ($number)
            {{ $number }}.
        @endif

        {{ $label }}

        @if ($required)
            <span class="req-star" aria-hidden="true">*</span>
        @endif
    </span>

    @if ($hint)
        <span id="{{ $hintId }}" class="simdes-field-hint my-0">
            {{ $hint }}
        </span>
    @endif

    <div
        class="simdes-radio-group {{ $hasError ? 'is-invalid' : '' }}"
        role="radiogroup"
        aria-labelledby="{{ $id }}Label"
        @if ($required) aria-required="true" @endif
        @if ($hint || $hasError)
            aria-describedby="{{ implode(' ', array_filter([
                $hint ? $hintId : null,
                $hasError ? $errorId : null,
            ])) }}"
        @endif
        {{ $attributes }}
    >
        @foreach ($options as $option)
            @php
                $optionValue = is_array($option) ? $option['value'] : $option;
                $optionLabel = is_array($option) ? $option['label'] : $option;
                $optionIcon = is_array($option) ? ($option['icon'] ?? null) : null;

                $optionId = $id . '-' . \Illuminate\Support\Str::slug($optionValue);
                $isChecked = (string) $selectedValue === (string) $optionValue;
            @endphp

            <div class="simdes-radio-option">
                <input
                    type="radio"
                    id="{{ $optionId }}"
                    name="{{ $name }}"
                    value="{{ $optionValue }}"
                    class="simdes-radio-input"
                    @checked($isChecked)
                    @required($required)
                    @disabled($disabled)
                    @if ($hasError)
                        aria-describedby="{{ $errorId }}"
                    @endif
                >

                <label for="{{ $optionId }}" class="simdes-radio-pill">
                    @if ($optionIcon)
                        <i class="{{ $optionIcon }}" aria-hidden="true"></i>
                    @endif

                    <span>{{ $optionLabel }}</span>
                </label>
            </div>
        @endforeach
    </div>

    <div
        id="{{ $errorId }}"
        class="field-error-msg"
        role="status"
        @if (!$hasError) hidden @endif
    >
        <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>

        <span class="field-error-text">
            {{ $errorMessage }}
        </span>
    </div>
</div>
