@props([
    'avatar'    => null,
    'name'   => '',
    'size'   => 40,
    'cursor' => 'pointer',
])

@php
    $initials = collect(explode(' ', $name))
        ->map(fn($w) => strtoupper(substr($w, 0, 1)))
        ->implode('');
@endphp

@if ($avatar)
    <img
        src="{{ asset('storage/avatar/' . $avatar) }}"
        class="rounded-circle"
        width="{{ $size }}"
        height="{{ $size }}"
        style="cursor: {{ $cursor }}; object-fit: cover;"
        alt="{{ $name }}"
    >
@else
    <div
        class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center"
        style="width: {{ $size }}px; height: {{ $size }}px; cursor: {{ $cursor }}; font-size: {{ $size * 0.4 }}px;"
    >
        {{ $initials }}
    </div>
@endif
