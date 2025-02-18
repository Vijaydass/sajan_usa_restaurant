@props(['messages'])

@if ($messages)
    @foreach ((array) $messages as $message)
        <span class="text-danger text-sm">{{ $message }}</span>
    @endforeach
@endif
