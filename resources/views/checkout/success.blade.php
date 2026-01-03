@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto text-center bg-white p-8 rounded shadow">
    <h1 class="text-3xl font-bold text-green-600 mb-4">
        ✅ Checkout Berhasil
    </h1>

    <p class="text-gray-600 mb-6">
        Pesanan kamu berhasil diproses.
    </p>

    <a href="{{ route('orders.index') }}"
       class="bg-blue-600 text-white px-6 py-3 rounded hover:bg-blue-700">
        Lihat Pesanan
    </a>
</div>
@endsection
