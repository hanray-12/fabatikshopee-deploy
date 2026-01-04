@props([
    'size' => 'md',      // sm | md | lg
    'theme' => 'light',  // light | dark
    'showText' => true,
])

@php
    // Dibikin lebih gede biar "sm" tetep keliatan mantap di navbar
    $sizes = [
        'sm' => 'h-10 w-10',   // sebelumnya kecil, sekarang lebih gede
        'md' => 'h-12 w-12',
        'lg' => 'h-16 w-16',
    ];

    $imgSize = $sizes[$size] ?? $sizes['md'];

    $brandText  = $theme === 'dark' ? 'text-white'  : 'text-gray-900';
    $accentText = $theme === 'dark' ? 'text-red-400' : 'text-red-600';
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}>
    <img
        src="{{ asset('images/Logo-Fabatik.jpeg') }}"
        alt="FabatikShopee"
        class="{{ $imgSize }} object-contain"
    />

    @if($showText)
        <span class="font-black tracking-tight select-none leading-none text-lg md:text-xl">
            <span class="{{ $accentText }}">Fabatik</span><span class="{{ $brandText }}">Shopee</span>
        </span>
    @endif
</div>
