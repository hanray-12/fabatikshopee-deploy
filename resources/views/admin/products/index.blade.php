@extends('layouts.admin')

@section('content')

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">
        Daftar Produk
    </h1>

    <a href="{{ route('admin.products.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        + Tambah Produk
    </a>
</div>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full border border-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 border text-center">No</th>
                <th class="p-3 border">Nama</th>
                <th class="p-3 border">Harga</th>
                <th class="p-3 border text-center">Stok</th>
                <th class="p-3 border text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="p-3 border text-center">
                        {{ $loop->iteration }}
                    </td>

                    <td class="p-3 border">
                        {{ $product->name }}
                    </td>

                    <td class="p-3 border">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </td>

                    <td class="p-3 border text-center">
                        {{ $product->stock }}
                    </td>

                    <td class="p-3 border text-center space-x-2">

                        <!-- EDIT -->
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="inline-block bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                            Edit
                        </a>

                        <!-- HAPUS -->
                        <form action="{{ route('admin.products.destroy', $product) }}"
                              method="POST"
                              class="inline"
                              onsubmit="return confirm('Yakin hapus produk ini?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                Hapus
                            </button>
                        </form>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-6 text-gray-500">
                        Produk kosong
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- PAGINATION -->
<div class="mt-6">
    {{ $products->links() }}
</div>

@endsection
