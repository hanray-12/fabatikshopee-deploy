<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = session()->get('cart', []);

        return view('cart.index', compact('cartItems'));
    }

    public function add(Product $product)
    {
        $cart = session()->get('cart', []);

        // kalau belum ada di cart
        if (!isset($cart[$product->id])) {
            $cart[$product->id] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => 1,
                'image' => $product->image ?? null,
                'stock' => (int) ($product->stock ?? 0),
            ];
        } else {
            // kalau ada, naikin qty tapi jangan lewat stock (kalau stock ada)
            $currentQty = (int) $cart[$product->id]['quantity'];
            $stock = (int) ($product->stock ?? 0);

            $newQty = $currentQty + 1;
            if ($stock > 0) {
                $newQty = min($newQty, $stock);
            }

            $cart[$product->id]['quantity'] = $newQty;
            $cart[$product->id]['stock'] = $stock;
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang');
    }

    /**
     * AJAX: update qty (inc/dec/set)
     * Route: PATCH /cart/{product}/qty
     */
    public function updateQty(Request $request, Product $product)
    {
        $request->validate([
            'action' => 'required|in:inc,dec,set',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$product->id])) {
            return response()->json([
                'ok' => false,
                'message' => 'Item tidak ditemukan di keranjang.',
            ], 404);
        }

        $action = $request->input('action');
        $stock = (int) ($product->stock ?? 0);

        $currentQty = (int) $cart[$product->id]['quantity'];
        $newQty = $currentQty;

        if ($action === 'inc') {
            $newQty = $currentQty + 1;
        } elseif ($action === 'dec') {
            $newQty = max(1, $currentQty - 1);
        } elseif ($action === 'set') {
            $newQty = (int) $request->input('quantity', $currentQty);
            $newQty = max(1, $newQty);
        }

        // clamp ke stock kalau stock > 0
        if ($stock > 0) {
            $newQty = min($newQty, $stock);
        }

        $cart[$product->id]['quantity'] = $newQty;
        $cart[$product->id]['stock'] = $stock;
        session()->put('cart', $cart);

        // hitung totals
        $totals = $this->calculateTotals($cart);

        $itemSubtotal = (float) $cart[$product->id]['price'] * (int) $cart[$product->id]['quantity'];

        return response()->json([
            'ok' => true,
            'message' => 'Qty updated',
            'product_id' => $product->id,
            'quantity' => (int) $cart[$product->id]['quantity'],
            'item_subtotal' => $itemSubtotal,
            'cart_subtotal' => $totals['subtotal'],
            'cart_items' => $totals['items'],
        ]);
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produk dihapus');
    }

    public function clear()
    {
        session()->forget('cart');

        return redirect()->back()->with('success', 'Keranjang dikosongkan');
    }

    private function calculateTotals(array $cart): array
    {
        $subtotal = 0.0;
        $items = 0;

        foreach ($cart as $item) {
            $qty = (int) ($item['quantity'] ?? 0);
            $price = (float) ($item['price'] ?? 0);
            $items += $qty;
            $subtotal += ($price * $qty);
        }

        return [
            'subtotal' => $subtotal,
            'items' => $items,
        ];
    }
}
