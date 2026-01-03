<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100 flex">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-gray-800 text-white min-h-screen p-4">
        <nav class="space-y-2">
            <a href="{{ route('admin.dashboard') }}"
               class="block px-3 py-2 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                Dashboard
            </a>

            <a href="{{ route('admin.products.index') }}"
               class="block px-3 py-2 rounded {{ request()->routeIs('admin.products.*') ? 'bg-gray-700' : '' }}">
                Produk
            </a>

            <a href="{{ route('admin.orders.index') }}"
               class="block px-3 py-2 rounded {{ request()->routeIs('admin.orders.*') ? 'bg-gray-700' : '' }}">
                Pesanan
            </a>
        </nav>
    </aside>

    <!-- CONTENT -->
    <main class="flex-1 p-6">
        @yield('content')
    </main>

</body>
</html>
