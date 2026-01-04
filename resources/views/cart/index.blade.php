@extends('layouts.app')

@section('content')
@php
    $subtotal = 0;
    $totalItems = 0;

    foreach($cartItems as $it){
        $subtotal += ($it['price'] * $it['quantity']);
        $totalItems += $it['quantity'];
    }
@endphp

<div class="max-w-6xl mx-auto space-y-4">

    <div class="flex items-end justify-between">
        <div>
            <h1 class="text-2xl font-black">🛒 Keranjang</h1>
            <p class="text-sm text-gray-500">Atur qty tanpa reload, sat-set 😄</p>
        </div>

        @if(count($cartItems) > 0)
            <form action="{{ route('cart.clear') }}" method="POST">
                @csrf
                <button class="px-4 py-2 rounded-2xl bg-gray-100 hover:bg-gray-200 font-semibold transition">
                    Kosongkan
                </button>
            </form>
        @endif
    </div>

    @if(count($cartItems) === 0)
        <div class="bg-white border rounded-3xl p-10 text-center shadow-sm">
            <div class="text-3xl">🫙</div>
            <div class="mt-2 font-bold">Keranjang masih kosong</div>
            <p class="text-sm text-gray-500 mt-1">yuk belanja batik premium ✨.</p>
            <a href="{{ route('home') }}"
               class="inline-flex mt-4 px-5 py-2.5 rounded-2xl bg-gray-900 text-white font-semibold hover:bg-black transition">
                Lihat Katalog →
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

            <!-- LIST ITEMS -->
            <div class="lg:col-span-2 bg-white border rounded-3xl shadow-sm overflow-hidden">
                <div class="p-5 border-b flex items-center justify-between">
                    <div>
                        <div class="font-black">Item</div>
                        <div class="text-sm text-gray-500">
                            <span id="cartItemsCount">{{ $totalItems }}</span> item
                        </div>
                    </div>

                    <div class="text-sm text-gray-500">
                        Subtotal:
                        <span class="font-black text-green-600">
                            Rp <span id="cartSubtotalText">{{ number_format($subtotal,0,',','.') }}</span>
                        </span>
                    </div>
                </div>

                <div class="divide-y">
                    @foreach($cartItems as $id => $item)
                        @php
                            $itemSubtotal = $item['price'] * $item['quantity'];
                            $img = $item['image'] ?? null;
                            $stock = (int)($item['stock'] ?? 0);
                        @endphp

                        <div class="p-5 flex items-center gap-4"
                             data-cart-row
                             data-product-id="{{ $id }}"
                             data-stock="{{ $stock }}"
                        >
                            <div class="w-16 h-16 rounded-2xl bg-gray-100 overflow-hidden flex-shrink-0">
                                @if($img)
                                    <img src="{{ asset('storage/'.$img) }}" class="w-full h-full object-cover" alt="">
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="font-semibold truncate">{{ $item['name'] }}</div>
                                <div class="text-sm text-gray-500">
                                    Rp {{ number_format($item['price'],0,',','.') }}
                                    @if($stock > 0)
                                        • stok {{ $stock }}
                                    @endif
                                </div>

                                <!-- qty controls -->
                                <div class="mt-3 flex items-center gap-2">
                                    <button
                                        type="button"
                                        class="cart-dec w-9 h-9 rounded-xl border hover:bg-gray-50 transition font-black"
                                        aria-label="Kurangi"
                                    >−</button>

                                    <input
                                        class="cart-qty w-14 h-9 rounded-xl border text-center font-bold bg-white"
                                        value="{{ $item['quantity'] }}"
                                        inputmode="numeric"
                                        pattern="[0-9]*"
                                    />

                                    <button
                                        type="button"
                                        class="cart-inc w-9 h-9 rounded-xl border hover:bg-gray-50 transition font-black"
                                        aria-label="Tambah"
                                    >+</button>

                                    <span class="text-xs text-gray-500 ml-2 cart-loading hidden">⏳</span>
                                </div>
                            </div>

                            <div class="text-right">
                                <div class="text-xs text-gray-500">Subtotal</div>
                                <div class="font-black text-green-600">
                                    Rp <span class="itemSubtotalText">{{ number_format($itemSubtotal,0,',','.') }}</span>
                                </div>

                                <form action="{{ route('cart.remove', $id) }}" method="POST" class="mt-3">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-sm text-red-600 hover:underline">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- SUMMARY -->
            <div class="bg-white border rounded-3xl shadow-sm p-5 h-fit">
                <div class="font-black text-lg">Ringkasan</div>

                <div class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total item</span>
                        <span class="font-semibold"><span id="cartItemsCount2">{{ $totalItems }}</span></span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-semibold text-green-600">
                            Rp <span id="cartSubtotalText2">{{ number_format($subtotal,0,',','.') }}</span>
                        </span>
                    </div>

                    <div class="pt-3 mt-3 border-t flex justify-between text-base">
                        <span class="font-black">Total</span>
                        <span class="font-black text-green-600">
                            Rp <span id="cartTotalText">{{ number_format($subtotal,0,',','.') }}</span>
                        </span>
                    </div>
                </div>

                <a href="{{ route('checkout.index') }}"
                   class="mt-5 inline-flex w-full justify-center py-3 rounded-2xl bg-gray-900 text-white font-semibold hover:bg-black transition">
                    Checkout →
                </a>

                <div class="text-xs text-gray-500 mt-3">
                    * Total di atas belum termasuk ongkir (cek di checkout).
                </div>
            </div>

        </div>
    @endif

</div>
@endsection
