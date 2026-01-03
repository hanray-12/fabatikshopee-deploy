@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
        <div>
            <h1 class="text-3xl font-black tracking-tight">Admin Dashboard</h1>
            <p class="text-sm text-gray-500">By Rayhan.</p>
        </div>

        <!-- Quick Actions -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.products.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-gray-900 text-white font-semibold hover:bg-black transition">
                ➕ Tambah Produk
            </a>
            <a href="{{ route('admin.orders.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-white border font-semibold hover:bg-gray-50 transition">
                📦 Lihat Orders
            </a>
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-white border font-semibold hover:bg-gray-50 transition">
                ← Kembali ke Toko
            </a>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white border rounded-3xl p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Produk</p>
            <p class="text-3xl font-black mt-1">{{ $totalProducts }}</p>
            <p class="text-xs text-gray-400 mt-2">All time</p>
        </div>

        <div class="bg-white border rounded-3xl p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Order</p>
            <p class="text-3xl font-black mt-1">{{ $totalOrders }}</p>
            <p class="text-xs text-gray-400 mt-2">All time</p>
        </div>

        <div class="bg-white border rounded-3xl p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Omzet</p>
            <p class="text-2xl md:text-3xl font-black mt-1 text-green-600">
                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-400 mt-2">All time</p>
        </div>

        <div class="bg-white border rounded-3xl p-5 shadow-sm">
            <p class="text-sm text-gray-500">Order Pending</p>
            <p class="text-3xl font-black mt-1 text-yellow-600">{{ $pendingOrders }}</p>
            <p class="text-xs text-gray-400 mt-2">Butuh diproses</p>
        </div>
    </div>

    <!-- Today / Month Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white border rounded-3xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="font-black">Hari ini</p>
                <span class="text-xs text-gray-400">{{ now()->format('d M Y') }}</span>
            </div>
            <div class="mt-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Order masuk</span>
                    <span class="font-bold">{{ $ordersToday }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Omzet</span>
                    <span class="font-bold text-green-600">Rp {{ number_format($revenueToday, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white border rounded-3xl p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="font-black">Bulan ini</p>
                <span class="text-xs text-gray-400">{{ now()->format('M Y') }}</span>
            </div>
            <div class="mt-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Order</span>
                    <span class="font-bold">{{ $ordersThisMonth }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Omzet</span>
                    <span class="font-bold text-green-600">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white border rounded-3xl p-5 shadow-sm">
            <p class="font-black">Status Overview</p>
            <div class="mt-4 flex flex-wrap gap-2">
                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-sm font-bold">
                    Pending: {{ $pendingOrders }}
                </span>
                <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-bold">
                    Paid: {{ $paidOrders }}
                </span>
                <span class="px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-bold">
                    Shipped: {{ $shippedOrders }}
                </span>
            </div>

            <div class="mt-4 text-sm text-gray-500">
                <div>Stok menipis: <span class="font-bold text-gray-900">{{ $lowStockCount }}</span></div>
                <div>Stok habis: <span class="font-bold text-gray-900">{{ $outOfStockCount }}</span></div>
            </div>
        </div>
    </div>

    <!-- Inventory Alerts + Latest Orders -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">

        <!-- Inventory Alerts -->
        <div class="bg-white border rounded-3xl shadow-sm overflow-hidden">
            <div class="p-5 border-b">
                <h2 class="font-black">Inventory Alerts</h2>
                <p class="text-sm text-gray-500">Biar admin tau apa yang perlu dibenerin.</p>
            </div>

            <div class="p-5 space-y-4">
                <div class="rounded-2xl bg-gray-50 border p-4">
                    <div class="font-bold">Stok menipis (≤ 5)</div>
                    <div class="text-sm text-gray-500 mb-3">Top 5 paling kritis</div>

                    @if($lowStockProducts->count() === 0)
                        <div class="text-sm text-gray-500">Aman, gak ada stok menipis ✅</div>
                    @else
                        <div class="space-y-2">
                            @foreach($lowStockProducts as $p)
                                <div class="flex items-center justify-between text-sm">
                                    <div class="truncate pr-3 font-semibold">{{ $p->name }}</div>
                                    <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 font-bold">
                                        {{ $p->stock }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="rounded-2xl bg-gray-50 border p-4">
                    <div class="font-bold">Stok habis</div>
                    <div class="text-sm text-gray-500 mb-3">Produk perlu restock</div>

                    @if($outOfStockProducts->count() === 0)
                        <div class="text-sm text-gray-500">Aman, gak ada stok habis ✅</div>
                    @else
                        <div class="space-y-2">
                            @foreach($outOfStockProducts as $p)
                                <div class="flex items-center justify-between text-sm">
                                    <div class="truncate pr-3 font-semibold">{{ $p->name }}</div>
                                    <span class="px-2 py-1 rounded-full bg-red-100 text-red-700 font-bold">
                                        HABIS
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Latest Orders Table -->
        <div class="xl:col-span-2 bg-white border rounded-3xl shadow-sm overflow-hidden">
            <div class="p-5 border-b">
                <h2 class="font-black">Order Terbaru</h2>
                <p class="text-sm text-gray-500">7 pesanan terakhir (lebih informatif).</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 text-sm text-gray-600">
                        <tr>
                            <th class="text-left p-4">Order</th>
                            <th class="text-left p-4">User</th>
                            <th class="text-left p-4">Items</th>
                            <th class="text-left p-4">Total</th>
                            <th class="text-left p-4">Status</th>
                            <th class="text-right p-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestOrders as $order)
                            <tr class="border-t">
                                <td class="p-4 font-semibold">
                                    #{{ $order->id }}
                                    <div class="text-xs text-gray-400">
                                        {{ $order->created_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="p-4">
                                    {{ $order->user?->name ?? '-' }}
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-bold">
                                        {{ $order->items_count }}
                                    </span>
                                </td>
                                <td class="p-4 font-black text-green-600">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>
                                <td class="p-4">
                                    <x-status-badge :status="$order->status" />
                                </td>
                                <td class="p-4 text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                       class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-gray-900 text-white font-semibold hover:bg-black transition">
                                        Detail →
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-500">
                                    Belum ada order.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-5 border-t flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="text-sm text-gray-500">
                    Tips: Pantau “Pending” & stok menipis biar flow toko lancar.
                </div>
                <a href="{{ route('admin.orders.index') }}"
                   class="inline-flex justify-center px-4 py-2 rounded-2xl bg-white border font-semibold hover:bg-gray-50 transition">
                    Lihat semua order →
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
