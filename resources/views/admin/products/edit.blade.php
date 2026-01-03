<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Produk
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <form method="POST"
                      action="{{ route('admin.products.update', $product) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block">Nama Produk</label>
                        <input type="text" name="name"
                               value="{{ $product->name }}"
                               class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block">Harga</label>
                        <input type="number" name="price"
                               value="{{ $product->price }}"
                               class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block">Stok</label>
                        <input type="number" name="stock"
                               value="{{ $product->stock }}"
                               class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block">Deskripsi</label>
                        <textarea name="description"
                                  class="w-full border rounded px-3 py-2">{{ $product->description }}</textarea>
                    </div>

                    <button type="submit"
                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Update
                    </button>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
