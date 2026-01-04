<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>FabatikShopee</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-b from-gray-50 via-white to-gray-50 text-gray-900">
@php
    $cart = session('cart', []);
    $cartCount = collect($cart)->sum('quantity');
    $navSearch = request('search') ?? request('q');
@endphp

<x-toast />

<!-- NAVBAR -->
<nav class="sticky top-0 z-50 border-b bg-white/80 backdrop-blur soft-grid">
    <div class="max-w-7xl mx-auto px-4">
        <div class="h-16 flex items-center justify-between gap-4">

            <!-- Brand -->
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img
                    src="{{ asset('images/Logo-Fabatik.jpeg') }}"
                    alt="FabatikShopee"
                    class="h-10 w-10 md:h-11 md:w-11 rounded-full object-cover ring-1 ring-black/5"
                />
                <div class="leading-tight">
                    <div class="font-black tracking-tight text-base md:text-lg">
                        <span class="text-red-600">Fabatik</span><span class="text-gray-900">Shopee</span>
                    </div>
                    <div class="text-[11px] text-gray-500 -mt-0.5 hidden sm:block">
                        By-Rayhan.
                    </div>
                </div>
            </a>

            <!-- Search (Desktop) -->
            <div class="hidden md:flex flex-1 justify-center">
                <form method="GET" action="{{ route('home') }}" class="w-full max-w-xl">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ $navSearch }}"
                            placeholder="Cari produk..."
                            class="ui-input !rounded-full !pr-12"
                        />
                        <button
                            type="submit"
                            class="absolute right-1 top-1/2 -translate-y-1/2 rounded-full bg-red-600 text-white px-4 py-2 shadow-sm hover:bg-red-700 transition"
                            aria-label="Search"
                        >
                            🔍
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right -->
            <div class="flex items-center gap-2">

                @auth
                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}"
                       class="relative inline-flex items-center justify-center h-10 w-10 rounded-full hover:bg-gray-100 transition"
                       title="Cart">
                        🛒
                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[11px] px-2 py-0.5 rounded-full">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Admin -->
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gray-900 text-white hover:bg-black transition shadow-sm">
                            ⚙️ <span class="font-semibold">Admin</span>
                        </a>
                    @endif

                    <!-- User dropdown -->
                    <details class="relative ui-details" data-autoclose="true">
                        <summary class="list-none cursor-pointer select-none">
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full hover:bg-gray-100 transition font-semibold">
                                {{ auth()->user()->name }}
                                <span class="text-xs text-gray-500">▼</span>
                            </div>
                        </summary>

                        <div class="ui-dropdown absolute right-0 mt-2 w-56 rounded-2xl bg-white border border-gray-100 shadow-lg overflow-hidden"
                             data-dropdown="true">
                            <a href="{{ route('orders.index') }}" class="block px-4 py-3 hover:bg-gray-50 transition">
                                📦 Pesanan Saya
                            </a>

                            <div class="h-px bg-gray-100"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 hover:bg-gray-50 transition">
                                    🚪 Logout
                                </button>
                            </form>
                        </div>
                    </details>

                @endauth

                @guest
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 rounded-full hover:bg-gray-100 transition font-semibold">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 rounded-full bg-red-600 text-white hover:bg-red-700 transition font-semibold shadow-sm">
                        Register
                    </a>
                @endguest

            </div>
        </div>

        <!-- Search (Mobile) -->
        <div class="md:hidden pb-3">
            <form method="GET" action="{{ route('home') }}">
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ $navSearch }}"
                        placeholder="Cari produk..."
                        class="ui-input !pr-12"
                    />
                    <button
                        type="submit"
                        class="absolute right-1 top-1/2 -translate-y-1/2 rounded-xl bg-red-600 text-white px-3 py-2 shadow-sm hover:bg-red-700 transition"
                        aria-label="Search"
                    >
                        🔍
                    </button>
                </div>
            </form>
        </div>
    </div>
</nav>

<!-- Page -->
<main class="max-w-7xl mx-auto px-4 py-6">
    @yield('content')
</main>

<!-- Back to Top -->
<button id="backToTop" class="backtop" aria-label="Back to top">
    ↑
</button>

<footer class="mt-20 border-t bg-white">
    <div class="max-w-7xl mx-auto px-6 py-10 grid md:grid-cols-3 gap-8">
        
        <!-- Brand -->
        <div>
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/Logo-Fabatik.jpeg') }}" class="h-10">
                <span class="font-bold text-lg text-gray-800">FabatikShopee</span>
            </div>
            <p class="mt-3 text-sm text-gray-500">
                Temukan produk yang kamu inginkan di sini. Batik lokal dengan pengalaman belanja modern.
            </p>
        </div>

        <!-- Menu -->
        <div>
            <h4 class="font-semibold mb-3">Menu</h4>
            <ul class="space-y-2 text-sm text-gray-600">
                <li>Home</li>
                <li>Produk</li>
                <li>Keranjang</li>
                <li>Pesanan</li>
            </ul>
        </div>

        <!-- Info -->
        <div>
            <h4 class="font-semibold mb-3">Project Info</h4>
            <p class="text-sm text-gray-600">
                Project E-Commerce Laravel<br>
                © 2026 FabatikShopee
            </p>
        </div>

    </div>
</footer>

</body>
</html>
