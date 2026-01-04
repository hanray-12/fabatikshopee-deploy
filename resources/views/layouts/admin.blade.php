<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>Admin Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900">
<x-toast />

<div class="min-h-screen flex">

    <aside class="w-72 bg-white border-r hidden md:block">
        <div class="p-5 border-b">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center">
                <x-application-logo size="sm" theme="light" />
            </a>
            <div class="text-xs text-gray-500 mt-2">Admin Panel</div>
        </div>

        <nav class="p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}"
               class="block px-4 py-3 rounded-2xl font-semibold
                      {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900 text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                Dashboard
            </a>

            <a href="{{ route('admin.products.index') }}"
               class="block px-4 py-3 rounded-2xl font-semibold
                      {{ request()->routeIs('admin.products.*') ? 'bg-gray-900 text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                Produk
            </a>

            <a href="{{ route('admin.orders.index') }}"
               class="block px-4 py-3 rounded-2xl font-semibold
                      {{ request()->routeIs('admin.orders.*') ? 'bg-gray-900 text-white' : 'hover:bg-gray-100 text-gray-700' }}">
                Order
            </a>

            <div class="pt-2"></div>

            <a href="{{ route('home') }}"
               class="block px-4 py-3 rounded-2xl font-semibold hover:bg-gray-100 text-gray-700">
                ← Kembali ke Toko
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-4 md:p-6">
        @yield('content')
    </main>

</div>
</body>
</html>
