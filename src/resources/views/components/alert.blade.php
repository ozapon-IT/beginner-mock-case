@props(['type' => 'success', 'message'])

@if ($message)
    <div class="{{ $type }}">
        <span>{{ $message }}</span>
    </div>
@endif