@props(['title' => 'Nothing here yet', 'message' => 'New records will appear here when they are available.'])

<div {{ $attributes->class('empty-state') }}>
    <span aria-hidden="true">□</span>
    <div>
        <strong>{{ $title }}</strong>
        <p>{{ $message }}</p>
    </div>
</div>
