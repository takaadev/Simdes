@props([
    'label' => 'Filter',
    'id' => 'filterSelect',
    'name' => 'filter',
    'options' => [],
])

<div class="ledger-filter-item">
    <label for="{{ $id }}">{{ $label }}</label>

    <select
        id="{{ $id }}"
        name="{{ $name }}"
        class="simdes-select"
        data-simdes-filter
        data-default-value="all"
        {{ $attributes }}
    >
        <option value="all">Semua</option>

        @foreach ($options as $optionValue => $optionLabel)
            @if ($optionValue !== 'all')
                <option value="{{ $optionValue }}">
                    {{ $optionLabel }}
                </option>
            @endif
        @endforeach
    </select>
</div>