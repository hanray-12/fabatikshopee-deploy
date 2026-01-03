@props(['status'])

@php
    $classes = match($status) {
        'pending' => 'bg-yellow-500',
        'paid'    => 'bg-blue-600',
        'shipped' => 'bg-green-600',
        default   => 'bg-gray-500',
    };

    $label = match($status) {
        'pending' => 'PENDING',
        'paid'    => 'PAID',
        'shipped' => 'SHIPPED',
        default   => strtoupper($status),
    };
@endphp

<span class="px-3 py-1 rounded text-white text-sm font-semibold {{ $classes }}">
    {{ $label }}
</span>
