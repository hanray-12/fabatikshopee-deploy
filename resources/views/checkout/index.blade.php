@extends('layouts.app')

@section('content')
@php
    $subtotal = 0;
    $totalItems = 0;

    foreach($cartItems as $item){
        $subtotal += $item['price'] * $item['quantity'];
        $totalItems += $item['quantity'];
    }

    // ongkir dummy
    $shipping = $subtotal > 0 ? 15000 : 0;
    $grandTotal = $subtotal + $shipping;
@endphp

<div class="max-w-5xl mx-auto space-y-4">

    <div class="flex items-end justify-between">
        <div>
            <h1 class="text-2xl font-black">🧾 Checkout</h1>
            <p class="text-sm text-gray-500">Cek lagi sebelum pesanan dibuat.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        <!-- items -->
        <div class="lg:col-span-2 bg-white border rounded-3xl shadow-sm overflow-hidden">
            <div class="p-5 border-b">
                <div class="font-black">Item di Keranjang</div>
                <div class="text-sm text-gray-500">{{ $totalItems }} item</div>
            </div>

            <div class="divide-y">
                @foreach($cartItems as $item)
                    <div class="p-5 flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-gray-100 overflow-hidden flex-shrink-0">
                            @if(!empty($item['image']))
                                <img src="{{ asset('storage/'.$item['image']) }}" class="w-full h-full object-cover" alt="">
                            @endif
                        </div>

                        <div class="flex-1">
                            <div class="font-semibold">{{ $item['name'] }}</div>
                            <div class="text-sm text-gray-500">
                                {{ $item['quantity'] }} x Rp {{ number_format($item['price'],0,',','.') }}
                            </div>
                        </div>

                        <div class="font-black text-green-600">
                            Rp {{ number_format($item['price'] * $item['quantity'],0,',','.') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- summary -->
        <div class="bg-white border rounded-3xl shadow-sm p-5 h-fit">
            <div class="font-black text-lg">Ringkasan</div>
            <div class="mt-4 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Total item</span>
                    <span class="font-semibold">{{ $totalItems }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-semibold">Rp {{ number_format($subtotal,0,',','.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Ongkir (dummy)</span>
                    <span class="font-semibold">Rp {{ number_format($shipping,0,',','.') }}</span>
                </div>

                <div class="pt-3 mt-3 border-t flex justify-between text-base">
                    <span class="font-black">Total akhir</span>
                    <span class="font-black text-green-600">Rp {{ number_format($grandTotal,0,',','.') }}</span>
                </div>
            </div>

            <form x-data="{loading:false}" @submit="loading=true" action="{{ route('checkout.process') }}" method="POST" class="mt-5">
                @csrf
                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full py-3 rounded-2xl font-semibold transition
                           bg-gray-900 text-white hover:bg-black disabled:opacity-70 disabled:cursor-not-allowed"
                >
                    <span x-show="!loading">✅ Proses Checkout</span>
                    <span x-show="loading">⏳ Memproses...</span>
                </button>
            </form>

            <div class="text-xs text-gray-500 mt-3">
                * Ongkir dummy buat kebutuhan demo.
            </div>
        </div>

    </div>
</div>
@endsection
