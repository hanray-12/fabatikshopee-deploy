<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>FabatikShopee</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900">

@php
    $cart = session('cart', []);
    $cartCount = collect($cart)->sum('quantity');
@endphp

<x-toast />

<nav class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b">
    <div class="max-w-7xl mx-auto px-4">
        <div class="h-16 flex items-center justify-between gap-4">

            <a href="{{ route('home') }}" class="flex items-center gap-2 font-black tracking-tight">
                <span class="text-lg">
                    <span class="text-red-600">Fabatik</span><span>Shopee</span>
                </span>
                <span class="text-sm opacity-70">✨</span>
            </a>

            <!-- Search desktop -->
            <form action="{{ route('home') }}" method="GET" class="hidden md:flex flex-1 max-w-2xl">
                <div class="relative w-full">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') ?? request('q') }}"
                        placeholder="Cari produk..."
                        class="w-full rounded-full border border-gray-200 bg-gray-50 px-5 py-2.5 pr-12
                               focus:outline-none focus:ring-2 focus:ring-red-200 focus:border-red-300"
                    >
                    <button type="submit"
                        class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1.5 rounded-full
                               bg-red-600 text-white hover:bg-red-700 transition">
                        🔎
                    </button>
                </div>
            </form>

            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('cart.index') }}" class="relative px-3 py-2 rounded-full hover:bg-gray-100 transition">
                        🛒
                        @if($cartCount > 0)
                            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs px-2 rounded-full">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                           class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gray-900 text-white
                                  hover:bg-black transition">
                            ⚙ <span class="font-semibold">Admin</span>
                        </a>
                    @endif

                    <div class="relative group">
                        <button class="px-4 py-2 rounded-full hover:bg-gray-100 transition font-semibold">
                            {{ auth()->user()->name }}
                        </button>

                        <div class="absolute right-0 mt-2 w-56 bg-white border rounded-2xl shadow-lg overflow-hidden hidden group-hover:block">
                            <a href="{{ route('orders.index') }}" class="block px-4 py-3 hover:bg-gray-50">
                                📦 Pesanan Saya
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 hover:bg-gray-50">
                                    🚪 Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-full hover:bg-gray-100 transition font-semibold">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-full bg-red-600 text-white hover:bg-red-700 transition font-semibold">
                        Register
                    </a>
                @endguest
            </div>
        </div>

        <!-- Search mobile -->
        <div class="md:hidden pb-3">
            <form action="{{ route('home') }}" method="GET">
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') ?? request('q') }}"
                        placeholder="Cari produk..."
                        class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-4 py-2.5 pr-12
                               focus:outline-none focus:ring-2 focus:ring-red-200 focus:border-red-300"
                    >
                    <button type="submit"
                        class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1.5 rounded-xl
                               bg-red-600 text-white hover:bg-red-700 transition">
                        🔎
                    </button>
                </div>
            </form>
        </div>

    </div>
</nav>

<main class="max-w-7xl mx-auto py-6 md:py-8 px-4">
    @yield('content')
</main>

</body>
</html>
