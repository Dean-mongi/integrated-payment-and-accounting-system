@props(['status'])
@php
    $value = strtolower(str_replace('_', ' ', $status));
    $tone = in_array($value, ['completed', 'paid', 'active', 'matched', 'success'], true) ? 'success' : (in_array($value, ['failed', 'overdue', 'unpaid', 'discrepancy', 'inactive'], true) ? 'danger' : 'warning');
    $icon = $tone === 'success' ? '✓' : ($tone === 'danger' ? '!' : '•');
@endphp
<span {{ $attributes->class("status-badge status-badge-{$tone}") }}><span aria-hidden="true">{{ $icon }}</span><span>{{ ucwords($value) }}</span></span>
