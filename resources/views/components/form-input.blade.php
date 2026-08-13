@props(['name', 'label', 'type' => 'text', 'value' => null, 'hint' => null])
<div class="field">
    <label for="{{ $attributes->get('id', $name) }}">{{ $label }}</label>
    <input id="{{ $attributes->get('id', $name) }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}" @class(['input', 'input-error' => $errors->has($name)]) {{ $attributes->except(['id', 'class']) }}>
    @if ($hint && !$errors->has($name)) <p class="field-hint">{{ $hint }}</p> @endif
    @error($name)<p class="field-error">{{ $message }}</p>@enderror
</div>
