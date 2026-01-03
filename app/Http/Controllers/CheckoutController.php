<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (count($cart) === 0) {
            return redirect()->route('cart.index');
        }

        return view('checkout.index', [
            'cartItems' => $cart
        ]);
    }

    public function process(Request $request)
    {
        $cart = session()->get('cart', []);

        if (count($cart) === 0) {
            return redirect()->route('cart.index');
        }

        // =========================
        // HITUNG TOTAL
        // =========================
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // =========================
        // SIMPAN ORDER
        // =========================
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $total,
            'status' => 'pending'
        ]);

        // =========================
        // SIMPAN ORDER ITEMS
        // =========================
        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id'  => $order->id,
                'product_id'=> $productId,
                'price'     => $item['price'],
                'quantity'  => $item['quantity']
            ]);
        }

        // =========================
        // KOSONGKAN CART
        // =========================
        session()->forget('cart');
        session()->forget('cart_count');

        return redirect()
            ->route('orders.index')
            ->with('success', 'Checkout berhasil, pesanan dibuat');
    }
}
