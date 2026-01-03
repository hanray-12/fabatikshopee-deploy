@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto space-y-4">

    <div class="bg-white border rounded-3xl shadow-sm p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="rounded-3xl overflow-hidden bg-gray-100">
            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" class="w-full h-[320px] md:h-[420px] object-cover" alt="{{ $product->name }}">
            @else
                <div class="h-[320px] md:h-[420px] flex items-center justify-center text-gray-400">
                    No Image
                </div>
            @endif
        </div>

        <div class="flex flex-col">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h1 class="text-2xl md:text-3xl font-black">{{ $product->name }}</h1>
                    <div class="mt-2 text-sm text-gray-500 flex items-center gap-2">
                        <span>⭐ 4.7</span>
                        <span>•</span>
                        <span>Stok: {{ $product->stock }}</span>
                    </div>
                </div>

                @if($product->stock <= 0)
                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-bold">Habis</span>
                @else
                    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-bold">Ready</span>
                @endif
            </div>

            <div class="mt-5 text-2xl font-black text-green-600">
                Rp {{ number_format($product->price,0,',','.') }}
            </div>

            <p class="mt-4 text-gray-700 leading-relaxed">
                {{ $product->description }}
            </p>

            <div class="mt-auto pt-6">
                @if($product->stock <= 0)
                    <button disabled class="w-full py-3 rounded-2xl bg-gray-200 text-gray-500 font-semibold cursor-not-allowed">
                        Stok Habis
                    </button>
                @else
                    <form x-data="{loading:false}" @submit="loading=true" action="{{ route('cart.add', $product) }}" method="POST">
                        @csrf
                        <button
                            type="submit"
                            :disabled="loading"
                            class="w-full py-3 rounded-2xl font-semibold transition
                                   bg-red-600 text-white hover:bg-red-700 disabled:opacity-70 disabled:cursor-not-allowed"
                        >
                            <span x-show="!loading">+ Tambah ke Keranjang</span>
                            <span x-show="loading">⏳ Menambahkan...</span>
                        </button>
                    </form>
                @endif

                <a href="{{ route('home') }}" class="block mt-3 text-center text-sm text-gray-600 hover:text-gray-900">
                    ← Kembali ke katalog
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
