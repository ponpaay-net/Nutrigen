@props([
    'name',
    'class' => '',
    'weight' => 'regular' // regular, bold, fill
])

@php
    $weightPrefix = match($weight) {
        'bold' => 'ph-bold',
        'fill' => 'ph-fill',
        default => 'ph',
    };
@endphp

<i {{ $attributes->except(['class']) }} class="{{ $weightPrefix }} ph-{{ $name }} {{ $class }}" aria-hidden="true"></i>
