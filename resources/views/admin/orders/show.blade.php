@extends('layouts.admin')

@section('content')
@php
    $status = strtolower($order->status);

    $stepPending = true;
    $stepPaid = in_array($status, ['paid','shipped']);
    $stepShipped = $status === 'shipped';

    $dot = fn($on) => $on ? 'bg-green-600' : 'bg-gray-300';
    $line = fn($on) => $on ? 'bg-green-600' : 'bg-gray-200';
@endphp

<div class="space-y-6 max-w-6xl">

    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
        <div>
            <h1 class="text-3xl font-black tracking-tight">Order #{{ $order->id }}</h1>
            <p class="text-sm text-gray-500">
                {{ $order->created_at->format('d M Y, H:i') }}
                • User: <span class="font-semibold">{{ $order->user?->name ?? '-' }}</span>
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('admin.orders.index') }}"
               class="px-4 py-2 rounded-2xl bg-white border font-semibold hover:bg-gray-50 transition">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Status update -->
    <div class="bg-white border rounded-3xl shadow-sm p-5">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <div class="font-black">Status</div>
                <div class="mt-2">
                    <x-status-badge :status="$order->status" />
                </div>
            </div>

            <form x-data="{loading:false}"
                  @submit="loading=true"
                  method="POST"
                  action="{{ route('admin.orders.update', $order) }}"
                  class="flex items-center gap-2"
            >
                @csrf
                @method('PUT')

                <!-- ✅ JANGAN DISABLE select, biar field 'status' ikut terkirim -->
                <select name="status"
                        class="rounded-2xl border-gray-200 bg-gray-50 text-sm font-semibold
                               focus:ring-gray-200 focus:border-gray-300"
                >
                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                </select>

                <!-- ✅ disable tombol aja -->
                <button type="submit"
                        :disabled="loading"
                        class="px-4 py-2 rounded-2xl bg-gray-900 text-white font-semibold hover:bg-black transition
                               disabled:opacity-70 disabled:cursor-not-allowed"
                >
                    <span x-show="!loading">Update</span>
                    <span x-show="loading">⏳</span>
                </button>
            </form>
        </div>

        <!-- Timeline -->
        <div class="mt-6">
            <div class="font-black mb-3">Timeline</div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full {{ $dot($stepPending) }}"></div>
                    <div class="text-sm font-semibold">Pending</div>
                </div>

                <div class="flex-1 h-1 rounded {{ $line($stepPaid) }}"></div>

                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full {{ $dot($stepPaid) }}"></div>
                    <div class="text-sm font-semibold">Paid</div>
                </div>

                <div class="flex-1 h-1 rounded {{ $line($stepShipped) }}"></div>

                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full {{ $dot($stepShipped) }}"></div>
                    <div class="text-sm font-semibold">Shipped</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Items -->
    <div class="bg-white border rounded-3xl shadow-sm overflow-hidden">
        <div class="p-5 border-b flex items-center justify-between">
            <div>
                <div class="font-black">Items</div>
                <div class="text-sm text-gray-500">{{ $order->items->count() }} produk</div>
            </div>

            <div class="text-right">
                <div class="text-xs text-gray-500">Total</div>
                <div class="text-xl font-black text-green-600">
                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-sm text-gray-600">
                    <tr>
                        <th class="text-left p-4">Produk</th>
                        <th class="text-left p-4">Harga</th>
                        <th class="text-left p-4">Qty</th>
                        <th class="text-left p-4">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr class="border-t">
                            <td class="p-4 font-semibold">
                                {{ $item->product->name ?? 'Produk' }}
                            </td>
                            <td class="p-4">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </td>
                            <td class="p-4">{{ $item->quantity }}</td>
                            <td class="p-4 font-black text-green-600">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
