<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>Admin Panel</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

<div class="flex min-h-screen">
    <!-- SIDEBAR -->
    <aside class="w-64 bg-gray-900 text-white p-4">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 mb-6">
            <x-application-logo size="sm" theme="dark" />
        </a>

        <ul class="space-y-2">
            <li>
                <a href="{{ route('admin.dashboard') }}"
                   class="block p-2 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.products.index') }}"
                   class="block p-2 rounded {{ request()->routeIs('admin.products.*') ? 'bg-gray-700' : '' }}">
                    Produk
                </a>
            </li>
            <li>
                <a href="{{ route('admin.orders.index') }}"
                   class="block p-2 rounded {{ request()->routeIs('admin.orders.*') ? 'bg-gray-700' : '' }}">
                    Pesanan
                </a>
            </li>
        </ul>
    </aside>

    <!-- CONTENT -->
    <main class="flex-1 p-6">
        {{ $slot }}
    </main>
</div>

</body>
</html>
