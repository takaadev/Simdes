@props([
    'label' => 'Filter',
    'id' => 'filterSelect',
    'name' => 'filter',
    'options' => [],
    'value' => '',
])

<div class="ledger-filter-item">

    <label for="{{ $id }}">
        {{ $label }}
    </label>

    <select
        id="{{ $id }}"
        name="{{ $name }}"
        class="form-select form-select-sm"
        data-simdes-filter
        data-default-value="all"
        {{ $attributes }}
    >
        @if (!$value)
            <option value="all">
                Semua
            </option>
        @endif

        @foreach ($options as $optionValue => $optionLabel)
            @if ($optionValue !== 'all')
                <option
                    value="{{ $optionValue }}"
                    @selected((string) old($name, $value) === (string) $optionValue)
                >
                    {{ $optionLabel }}
                </option>
            @endif
        @endforeach

    </select>

</div>
