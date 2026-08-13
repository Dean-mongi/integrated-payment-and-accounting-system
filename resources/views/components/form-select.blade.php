@props(['name', 'label', 'options' => [], 'value' => null])
<div class="field">
    <label for="{{ $attributes->get('id', $name) }}">{{ $label }}</label>
    <select id="{{ $attributes->get('id', $name) }}" name="{{ $name }}" @class(['input', 'input-error' => $errors->has($name)]) {{ $attributes->except(['id', 'class']) }}>
        @foreach($options as $optionValue => $optionLabel)<option value="{{ $optionValue }}" @selected((string) old($name, $value) === (string) $optionValue)>{{ $optionLabel }}</option>@endforeach
    </select>
    @error($name)<p class="field-error">{{ $message }}</p>@enderror
</div>
