@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-4">

    <div class="flex items-end justify-between">
        <div>
            <h1 class="text-2xl font-black">📦 Pesanan Saya</h1>
            <p class="text-sm text-gray-500">Liat status dan detail pesanan kamu.</p>
        </div>
    </div>

    <div class="bg-white border rounded-3xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 text-gray-600 text-sm">
                    <tr>
                        <th class="text-left p-4">Order</th>
                        <th class="text-left p-4">Tanggal</th>
                        <th class="text-left p-4">Items</th>
                        <th class="text-left p-4">Total</th>
                        <th class="text-left p-4">Status</th>
                        <th class="text-right p-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="border-t">
                            <td class="p-4 font-semibold">#{{ $order->id }}</td>
                            <td class="p-4 text-gray-600">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="p-4">{{ $order->items_count }}</td>
                            <td class="p-4 font-black text-green-600">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                            <td class="p-4">
                                @php
                                    $status = strtolower($order->status);
                                    $badge = 'bg-gray-100 text-gray-700';
                                    if ($status === 'pending') $badge = 'bg-yellow-100 text-yellow-800';
                                    if ($status === 'paid') $badge = 'bg-blue-100 text-blue-800';
                                    if ($status === 'shipped') $badge = 'bg-green-100 text-green-800';
                                @endphp
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold {{ $badge }}">
                                    {{ strtoupper($order->status) }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <a href="{{ route('orders.show', $order) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-gray-900 text-white font-semibold hover:bg-black transition">
                                    Detail →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500">
                                Belum ada pesanan.
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
