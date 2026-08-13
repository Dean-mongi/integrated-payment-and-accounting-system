@props(['variant' => 'primary', 'type' => 'submit'])
<button type="{{ $type }}" {{ $attributes->class(['button', 'button-secondary' => $variant === 'secondary', 'button-ghost' => $variant === 'ghost']) }}>{{ $slot }}</button>
