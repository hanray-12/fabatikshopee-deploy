@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <section class="rounded-3xl border bg-white shadow-sm overflow-hidden">
        <div class="p-6 md:p-10 bg-gradient-to-r from-gray-50 via-white to-gray-50">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-black tracking-tight">
                        Katalog <span class="text-red-600">FabatikShopee</span>
                    </h1>
                    <p class="mt-2 text-gray-600 max-w-2xl">
                        By Rayhan😄
                    </p>

                    @if(!empty($search))
                        <div class="mt-4 inline-flex items-center gap-2 bg-white border px-4 py-2 rounded-full">
                            <span class="text-sm text-gray-600">Hasil:</span>
                            <span class="font-semibold">"{{ $search }}"</span>
                            <a href="{{ route('home') }}" class="text-sm text-red-600 hover:underline">reset</a>
                        </div>
                    @endif
                </div>

                <div class="flex gap-3">
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

    <!-- Filters -->
    <section class="bg-white border rounded-3xl shadow-sm p-4 md:p-5">
        <form method="GET" action="{{ route('home') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
            <div class="md:col-span-4">
                <label class="text-xs font-semibold text-gray-600">Search</label>
                <input type="text" name="search"
                    value="{{ request('search') ?? request('q') }}"
                    class="w-full mt-1 rounded-2xl border-gray-200 bg-gray-50 focus:ring-red-200 focus:border-red-300"
                    placeholder="Cari produk...">
            </div>

            <div class="md:col-span-2">
                <label class="text-xs font-semibold text-gray-600">Min Harga</label>
                <input type="number" name="min_price" value="{{ $minPrice }}"
                    class="w-full mt-1 rounded-2xl border-gray-200 bg-gray-50 focus:ring-red-200 focus:border-red-300"
                    placeholder="0">
            </div>

            <div class="md:col-span-2">
                <label class="text-xs font-semibold text-gray-600">Max Harga</label>
                <input type="number" name="max_price" value="{{ $maxPrice }}"
                    class="w-full mt-1 rounded-2xl border-gray-200 bg-gray-50 focus:ring-red-200 focus:border-red-300"
                    placeholder="500000">
            </div>

            <div class="md:col-span-2">
                <label class="text-xs font-semibold text-gray-600">Sort</label>
                <select name="sort"
                    class="w-full mt-1 rounded-2xl border-gray-200 bg-gray-50 focus:ring-red-200 focus:border-red-300">
                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Termurah</option>
                    <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Termahal</option>
                </select>
            </div>

            <div class="md:col-span-2 flex items-center gap-3">
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" name="in_stock" value="1" {{ $inStock ? 'checked' : '' }}
                        class="rounded border-gray-300 text-red-600 focus:ring-red-200">
                    <span class="text-gray-700 font-semibold">Stok tersedia</span>
                </label>
            </div>

            <div class="md:col-span-12 flex gap-2">
                <button class="px-5 py-2.5 rounded-2xl bg-gray-900 text-white font-semibold hover:bg-black transition">
                    Terapkan
                </button>
                <a href="{{ route('home') }}" class="px-5 py-2.5 rounded-2xl bg-gray-100 text-gray-800 font-semibold hover:bg-gray-200 transition">
                    Reset
                </a>
            </div>
        </form>
    </section>

    <!-- Products -->
    @if($products->count() === 0)
        <div class="bg-white border rounded-3xl p-10 text-center shadow-sm">
            <div class="text-3xl">😵‍💫</div>
            <h2 class="mt-2 text-lg font-bold">Produk gak ketemu</h2>
            <p class="text-gray-600 mt-1">Coba kata kunci lain / ubah filter.</p>
        </div>
    @else
        <section class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach ($products as $product)
                <div class="bg-white border rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition">
                    <a href="{{ route('products.show', $product) }}" class="block">
                        <div class="relative aspect-[4/3] bg-gray-100 overflow-hidden">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">
                                    No Image
                                </div>
                            @endif

                            @if($product->stock <= 0)
                                <span class="absolute top-3 left-3 bg-red-600 text-white text-xs px-3 py-1 rounded-full">
                                    Habis
                                </span>
                            @else
                                <span class="absolute top-3 left-3 bg-black/70 text-white text-xs px-3 py-1 rounded-full">
                                    Stok: {{ $product->stock }}
                                </span>
                            @endif
                        </div>

                        <div class="p-3">
                            <h2 class="font-semibold text-sm line-clamp-1">{{ $product->name }}</h2>
                            <p class="mt-1 font-black text-green-600 text-sm">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            <div class="mt-2 text-xs text-gray-500 flex items-center justify-between">
                                <span>⭐ 4.7</span>
                                <span class="underline underline-offset-2">Detail</span>
                            </div>
                        </div>
                    </a>

                    <div class="p-3 pt-0">
                        @if($product->stock <= 0)
                            <button disabled class="w-full py-2 rounded-xl bg-gray-200 text-gray-500 font-semibold cursor-not-allowed">
                                Stok Habis
                            </button>
                        @else
                            <form
                                x-data="{loading:false}"
                                @submit="loading=true"
                                action="{{ route('cart.add', $product) }}"
                                method="POST"
                            >
                                @csrf
                                <button
                                    type="submit"
                                    :disabled="loading"
                                    class="w-full py-2 rounded-xl font-semibold transition shadow-sm
                                           bg-red-600 text-white hover:bg-red-700 disabled:opacity-70 disabled:cursor-not-allowed"
                                >
                                    <span x-show="!loading">+ Keranjang</span>
                                    <span x-show="loading">⏳ Menambahkan...</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </section>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @endif

</div>
@endsection
