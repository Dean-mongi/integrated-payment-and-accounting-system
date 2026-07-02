@php
    /** @var string $label */
    /** @var string $name */
    /** @var string $value */
    /** @var string|null $type */
    /** @var string|null $step */
    /** @var array|null $options */
    $type = $type ?? 'text';
@endphp

<div class="field">
    <label for="{{ $name }}">{{ $label }}</label>

    @if(!empty($options))
        <select id="{{ $name }}" name="{{ $name }}">
            @foreach($options as $optValue => $optLabel)
                <option value="{{ $optValue }}" {{ (string)$value === (string)$optValue ? 'selected' : '' }}>
                    {{ $optLabel }}
                </option>
            @endforeach
        </select>
    @else
        <input
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ $value }}"
            type="{{ $type }}"
            {{ isset($step) && $step ? 'step='.$step : '' }}
        />
    @endif
</div>

