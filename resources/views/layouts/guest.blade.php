<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>FabatikShopee</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900">
<x-toast />

<div class="min-h-screen flex items-center justify-center px-4 bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <div class="w-full max-w-4xl grid md:grid-cols-2 gap-6 items-stretch">

        <div class="hidden md:flex flex-col justify-between rounded-3xl p-8 bg-white border shadow-sm">
            <div>
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 font-black text-xl">
                    <span class="text-red-600">Fabatik</span><span>Shopee</span> ✨
                </a>

                <h2 class="mt-6 text-3xl font-black leading-tight">
                    Selamat datang kembali.
                </h2>
                <p class="mt-3 text-gray-600">
                    Login dulu biar bisa checkout & tracking pesanan.
                </p>
            </div>

            <div class="text-sm text-gray-500">
                © {{ date('Y') }} FabatikShopee
            </div>
        </div>

        <div class="rounded-3xl p-6 sm:p-8 bg-white border shadow-lg">
            <div class="md:hidden mb-6">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 font-black text-xl">
                    <span class="text-red-600">Fabatik</span><span>Shopee</span> ✨
                </a>
                <p class="text-gray-600 mt-2">Login dulu ya.</p>
            </div>

            {{ $slot }}
        </div>

    </div>
</div>
</body>
</html>
