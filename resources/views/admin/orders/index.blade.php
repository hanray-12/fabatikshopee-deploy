@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
        <div>
            <h1 class="text-3xl font-black tracking-tight">Orders</h1>
            <p class="text-sm text-gray-500">Update status langsung dari tabel (sat-set).</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.dashboard') }}"
               class="px-4 py-2 rounded-2xl bg-white border font-semibold hover:bg-gray-50 transition">
                ← Dashboard
            </a>
            <a href="{{ route('home') }}"
               class="px-4 py-2 rounded-2xl bg-white border font-semibold hover:bg-gray-50 transition">
                Kembali ke Toko
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white border rounded-3xl p-5 shadow-sm">
            <div class="text-sm text-gray-500">Pending</div>
            <div class="text-3xl font-black text-yellow-600 mt-1">{{ $countPending }}</div>
        </div>
        <div class="bg-white border rounded-3xl p-5 shadow-sm">
            <div class="text-sm text-gray-500">Paid</div>
            <div class="text-3xl font-black text-blue-600 mt-1">{{ $countPaid }}</div>
        </div>
        <div class="bg-white border rounded-3xl p-5 shadow-sm">
            <div class="text-sm text-gray-500">Shipped</div>
            <div class="text-3xl font-black text-green-600 mt-1">{{ $countShipped }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white border rounded-3xl shadow-sm p-4 md:p-5">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">

            <div class="md:col-span-5">
                <label class="text-xs font-semibold text-gray-600">Cari (Order ID / Nama user)</label>
                <input type="text" name="q" value="{{ $q }}"
                       class="w-full mt-1 rounded-2xl border-gray-200 bg-gray-50 focus:ring-gray-200 focus:border-gray-300"
                       placeholder="contoh: 12 atau Admin">
            </div>

            <div class="md:col-span-4">
                <label class="text-xs font-semibold text-gray-600">Filter status</label>
                <select name="status"
                        class="w-full mt-1 rounded-2xl border-gray-200 bg-gray-50 focus:ring-gray-200 focus:border-gray-300">
                    <option value="">Semua</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="shipped" {{ $status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                </select>
            </div>

            <div class="md:col-span-3 flex gap-2">
                <button class="px-5 py-2.5 rounded-2xl bg-gray-900 text-white font-semibold hover:bg-black transition w-full">
                    Terapkan
                </button>
                <a href="{{ route('admin.orders.index') }}"
                   class="px-5 py-2.5 rounded-2xl bg-gray-100 text-gray-800 font-semibold hover:bg-gray-200 transition w-full text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white border rounded-3xl shadow-sm overflow-hidden">
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
                    @forelse($orders as $order)
                        <tr class="border-t">
                            <td class="p-4 font-semibold">
                                #{{ $order->id }}
                                <div class="text-xs text-gray-400">
                                    {{ $order->created_at->diffForHumans() }}
                                </div>
                            </td>

                            <td class="p-4">
                                {{ $order->user?->name ?? '-' }}
                                <div class="text-xs text-gray-400">
                                    {{ $order->user?->email ?? '' }}
                                </div>
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
                                <!-- Inline status update -->
                                <form x-data="{loading:false}"
                                      @submit="loading=true"
                                      method="POST"
                                      action="{{ route('admin.orders.update', $order) }}"
                                      class="flex items-center gap-2"
                                >
                                    @csrf
                                    @method('PUT')

                                    <select name="status"
                                            @change="$el.form.requestSubmit()"
                                            :disabled="loading"
                                            class="rounded-2xl border-gray-200 bg-gray-50 text-sm font-semibold
                                                   focus:ring-gray-200 focus:border-gray-300 disabled:opacity-70"
                                    >
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    </select>

                                    <span x-show="loading" class="text-xs text-gray-400">⏳</span>
                                </form>

                                <div class="mt-2">
                                    <x-status-badge :status="$order->status" />
                                </div>
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

        <div class="p-4">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection
