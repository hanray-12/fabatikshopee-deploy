<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>FabatikShopee</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900">
<x-toast />

<div class="min-h-screen relative overflow-hidden">
    {{-- Background gradient + subtle pattern --}}
    <div class="absolute inset-0 bg-gradient-to-br from-gray-50 via-white to-gray-100"></div>

    {{-- Soft blobs (biar nggak kosong) --}}
    <div class="absolute -top-24 -left-24 w-[420px] h-[420px] bg-red-200/40 blur-3xl rounded-full"></div>
    <div class="absolute -bottom-28 -right-28 w-[520px] h-[520px] bg-gray-900/10 blur-3xl rounded-full"></div>

    {{-- Watermark besar (lebih keliatan) --}}
    <img
        src="{{ asset('images/Logo-Fabatik.jpeg') }}"
        alt="Watermark"
        class="pointer-events-none select-none absolute -right-24 -bottom-24 w-[520px] md:w-[760px]
               opacity-[0.12] rotate-[-10deg]"
    />

    <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-5xl grid md:grid-cols-2 gap-7 items-stretch">

            {{-- LEFT PANEL --}}
            <div class="hidden md:flex rounded-3xl bg-white/80 backdrop-blur border border-gray-200 shadow-sm p-10 flex-col justify-between">
                <div>
                    <a href="{{ route('home') }}" class="inline-flex items-center">
                        <x-application-logo size="lg" theme="light" />
                    </a>

                    <p class="text-gray-600 mt-4 leading-relaxed">
                        Minimalist premium, By Rayhan.
                    </p>

                    <div class="mt-6 inline-flex items-center gap-2 text-sm text-gray-600">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-900 text-white">✓</span>
                        <span>Yulia</span>
                    </div>
                    <div class="mt-3 inline-flex items-center gap-2 text-sm text-gray-600">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-900 text-white">✓</span>
                        <span>Marisa</span>
                    </div>
                    <div class="mt-3 inline-flex items-center gap-2 text-sm text-gray-600">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-900 text-white">✓</span>
                        <span>Tiara</span>
                    </div>
                    <div class="mt-3 inline-flex items-center gap-2 text-sm text-gray-600">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-900 text-white">✓</span>
                        <span>Zahra</span>
                    </div>
                </div>

                <div class="text-sm text-gray-500">
                    © {{ date('Y') }} FabatikShopee
                </div>
            </div>

            {{-- FORM PANEL --}}
            <div class="rounded-3xl bg-white/85 backdrop-blur border border-gray-200 shadow-sm p-8 md:p-10 relative overflow-hidden">

                {{-- Watermark kecil di card (lebih keliatan juga) --}}
                <img
                    src="{{ asset('images/Logo-Fabatik.jpeg') }}"
                    alt="Watermark"
                    class="pointer-events-none select-none absolute -right-10 -top-10 w-60 opacity-[0.10] rotate-[14deg]"
                />

                {{-- Header mobile --}}
                <div class="md:hidden mb-6 relative z-10">
                    <a href="{{ route('home') }}" class="inline-flex items-center">
                        <x-application-logo size="lg" theme="light" />
                    </a>
                    <p class="text-gray-600 mt-2">Masuk dulu ya.</p>
                </div>

                <div class="relative z-10">
                    {{ $slot }}
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
