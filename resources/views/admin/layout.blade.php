<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - FabatikShopee</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-b from-black via-zinc-950 to-black text-zinc-100">
<x-toast />

<div class="flex min-h-screen">

    <aside class="w-72 hidden md:flex flex-col border-r border-white/10 bg-black/40 backdrop-blur px-5 py-6">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 mb-8">
            <span class="w-11 h-11 rounded-2xl bg-white/5 border border-white/10 overflow-hidden">
                <img src="{{ asset('images/Logo-Fabatik.jpeg') }}" class="w-full h-full object-cover" alt="Logo">
            </span>
            <div>
                <div class="font-black tracking-tight">
                    <span class="text-red-500">Fabatik</span><span>Admin</span>
                </div>
                <div class="text-xs text-zinc-400 -mt-0.5">Dashboard premium</div>
            </div>
        </a>

        <nav class="space-y-2">
            <a href="{{ route('admin.dashboard') }}"
               class="block px-4 py-3 rounded-2xl border border-white/10 hover:bg-white/5 transition
                      {{ request()->routeIs('admin.dashboard') ? 'bg-white/5' : '' }}">
                📊 Dashboard
            </a>

            <a href="{{ route('admin.products.index') }}"
               class="block px-4 py-3 rounded-2xl border border-white/10 hover:bg-white/5 transition
                      {{ request()->routeIs('admin.products.*') ? 'bg-white/5' : '' }}">
                🧺 Produk
            </a>

            <a href="{{ route('admin.orders.index') }}"
               class="block px-4 py-3 rounded-2xl border border-white/10 hover:bg-white/5 transition
                      {{ request()->routeIs('admin.orders.*') ? 'bg-white/5' : '' }}">
                📦 Pesanan
            </a>

            <a href="{{ route('home') }}"
               class="block px-4 py-3 rounded-2xl border border-white/10 hover:bg-white/5 transition text-zinc-200">
                ← Kembali ke Toko
            </a>
        </nav>

        <div class="mt-auto pt-6 text-xs text-zinc-500">
            © {{ date('Y') }} FabatikShopee
        </div>
    </aside>

    <div class="flex-1 min-w-0">
        <header class="sticky top-0 z-40 border-b border-white/10 bg-black/50 backdrop-blur">
            <div class="px-4 md:px-8 h-16 flex items-center justify-between">
                <div class="font-black tracking-tight">Admin Panel</div>

                <div class="flex items-center gap-3">
                    <span class="text-sm text-zinc-300 hidden sm:block">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="px-4 py-2 rounded-2xl bg-red-600 text-white font-semibold hover:bg-red-700 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <main class="px-4 md:px-8 py-6">
            @yield('content')
        </main>
    </div>

</div>
</body>
</html>
