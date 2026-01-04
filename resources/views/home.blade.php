@extends('layouts.app')

@section('content')
@php
    $search = request('search') ?? '';
    $min = request('min_price', 0);
    $max = request('max_price', 500000);
    $sort = request('sort', 'latest');
    $inStock = request('in_stock') ? true : false;
@endphp

<div class="space-y-6">

    <!-- HERO -->
    <section class="ui-card overflow-hidden">
        <div class="p-6 md:p-10 bg-gradient-to-r from-gray-50 via-white to-gray-50">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-black tracking-tight">
                        Katalog <span class="text-red-600">FabatikShopee</span>
                    </h1>
                    <div class="mt-4 flex flex-wrap gap-3 text-sm">
    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700">
        ✨ Batik Kurasi Premium
    </span>
    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700">
        ⚡ Fast Checkout (AJAX)
    </span>
    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700">
        📦 Order Tracking
    </span>
</div>
                    <p class="text-gray-600 mt-2">
                        Anggota.
                    </p>

                    <div class="mt-4 flex flex-wrap items-center gap-2 text-xs">
                        <span class="ui-chip bg-gray-900 text-white border-gray-900">Rayhan</span>
                        <span class="ui-chip">Tiara</span>
                        <span class="ui-chip">Zahra</span>
                        <span class="ui-chip">Yulia</span>
                        <span class="ui-chip">Marisa</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="bg-white border rounded-2xl px-4 py-3 shadow-sm">
                        <div class="text-xs text-gray-500">Produk tampil</div>
                        <div class="text-xl font-black">{{ $products->count() }}</div>
                    </div>
                    <div class="bg-white border rounded-2xl px-4 py-3 shadow-sm">
                        <div class="text-xs text-gray-500">Halaman</div>
                        <div class="text-xl font-black">{{ $products->currentPage() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FILTER BAR -->
    <section class="ui-card p-4 md:p-5">
        <div class="mb-4">
    <h2 class="text-lg font-semibold text-gray-800">Filter Produk</h2>
    <p class="text-sm text-gray-500">
        Cari produk sesuai kebutuhanmu
    </p>
</div>

        <form method="GET" action="{{ route('home') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">

            <div class="md:col-span-4">
                <label class="text-xs font-semibold text-gray-600">Search</label>
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari produk..."
                    class="mt-1 ui-input"
                />
            </div>

            <div class="md:col-span-2">
                <label class="text-xs font-semibold text-gray-600">Min Harga</label>
                <input
                    type="number"
                    name="min_price"
                    value="{{ $min }}"
                    class="mt-1 ui-input"
                />
            </div>

            <div class="md:col-span-2">
                <label class="text-xs font-semibold text-gray-600">Max Harga</label>
                <input
                    type="number"
                    name="max_price"
                    value="{{ $max }}"
                    class="mt-1 ui-input"
                />
            </div>

            <div class="md:col-span-2">
                <label class="text-xs font-semibold text-gray-600">Sort</label>
                <select
                    name="sort"
                    class="mt-1 ui-input"
                >
                    <option value="latest" {{ $sort==='latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="price_asc" {{ $sort==='price_asc' ? 'selected' : '' }}>Termurah</option>
                    <option value="price_desc" {{ $sort==='price_desc' ? 'selected' : '' }}>Termahal</option>
                    <option value="name_asc" {{ $sort==='name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                </select>
            </div>

            <div class="md:col-span-2 flex items-center gap-3">
                <label class="inline-flex items-center gap-2 text-sm text-gray-700 select-none mt-6">
                    <input
                        type="checkbox"
                        name="in_stock"
                        value="1"
                        {{ $inStock ? 'checked' : '' }}
                        class="rounded border-gray-300 text-gray-900 focus:ring-gray-900/20"
                    />
                    Stok tersedia
                </label>
            </div>

            <div class="md:col-span-12 flex flex-wrap gap-2 pt-2">
                <button type="submit" class="ui-btn-dark">
                    Terapkan
                </button>

                <a href="{{ route('home') }}" class="ui-btn-ghost">
                    Reset
                </a>
            </div>
        </form>
    </section>

    <!-- PRODUCTS -->
    @if($products->count() === 0)
        <div class="ui-card p-10 text-center">
            <div class="text-2xl font-black">Produk nggak ketemu 😵</div>
            <p class="text-gray-600 mt-2">Coba kata kunci lain / ubah filter.</p>
        </div>
    @else
        <section class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">

            @foreach ($products as $product)
                <div class="product-card group">
                    <a href="{{ route('products.show', $product) }}" class="block">
                        <div class="product-img-wrap aspect-[4/3]">
                            @if ($product->image)
                                <img
                                    src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->name }}"
                                    class="product-img"
                                    loading="lazy"
                                />
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                                    No Image
                                </div>
                            @endif

                            @if($product->stock <= 0)
                                <span class="absolute top-3 left-3 bg-red-600 text-white text-xs px-3 py-1 rounded-full shadow">
                                    Habis
                                </span>
                            @else
                                <span class="absolute top-3 left-3 bg-gray-900 text-white text-xs px-3 py-1 rounded-full shadow">
                                    Stok: {{ $product->stock }}
                                </span>
                            @endif
                        </div>

                        <div class="p-3">
                            <div class="font-bold line-clamp-1">
                                {{ $product->name }}
                            </div>

                            <div class="mt-1 text-green-600 font-black">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </div>

                            <div class="mt-2 text-xs text-gray-500 flex items-center justify-between">
                                <span>⭐ 4.7</span>
                                <span class="underline underline-offset-2">Detail</span>
                            </div>
                        </div>
                    </a>

                    <div class="px-3 pb-3">
                        @if($product->stock <= 0)
                            <button
                                disabled
                                class="w-full py-2.5 rounded-2xl bg-gray-200 text-gray-500 font-semibold cursor-not-allowed"
                            >
                                Stok Habis
                            </button>
                        @else
                            <form method="POST" action="{{ route('cart.add', $product->id) }}" data-cart-form="true">
                                @csrf
                                <button type="submit" class="w-full py-2.5 rounded-2xl bg-red-600 text-white font-semibold shadow-sm hover:bg-red-700 transition">
                                    + Keranjang
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach

        </section>

        <div class="pt-4">
            {{ $products->withQueryString()->links() }}
        </div>
    @endif

</div>
@endsection
