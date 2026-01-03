@extends('layouts.app')

@section('content')
@php
    $status = strtolower($order->status);

    $stepPending = true;
    $stepPaid = in_array($status, ['paid', 'shipped']);
    $stepShipped = $status === 'shipped';

    $dot = fn($on) => $on ? 'bg-green-600' : 'bg-gray-300';
    $line = fn($on) => $on ? 'bg-green-600' : 'bg-gray-200';
@endphp

<div class="max-w-5xl mx-auto space-y-4">

    <div class="flex items-end justify-between">
        <div>
            <h1 class="text-2xl font-black">Detail Pesanan #{{ $order->id }}</h1>
            <p class="text-sm text-gray-500">Tanggal: {{ $order->created_at->format('d M Y') }}</p>
        </div>
        <div>
            <x-status-badge :status="$order->status" />
        </div>
    </div>

    <!-- Timeline -->
    <div class="bg-white border rounded-3xl shadow-sm p-5">
        <div class="font-black mb-4">Timeline</div>

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

    <!-- Items -->
    <div class="bg-white border rounded-3xl shadow-sm overflow-hidden">
        <div class="p-5 border-b">
            <div class="font-black">Item Pesanan</div>
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
                                Rp {{ number_format($item->price,0,',','.') }}
                            </td>
                            <td class="p-4">{{ $item->quantity }}</td>
                            <td class="p-4 font-bold text-green-600">
                                Rp {{ number_format($item->price * $item->quantity,0,',','.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-5 border-t text-right">
            <div class="text-sm text-gray-500">Total</div>
            <div class="text-2xl font-black text-green-600">
                Rp {{ number_format($order->total_price,0,',','.') }}
            </div>
        </div>
    </div>

</div>
@endsection
