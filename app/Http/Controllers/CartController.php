<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = $this->getCartItemsForView();

        return view('cart.index', compact('cartItems'));
    }

    public function add(Product $product)
    {
        $userId = auth()->id();

        if (!$userId) {
            // Kalau suatu saat cart dibuka tanpa login (opsional), fallback ke session.
            return $this->addToSessionCart($product);
        }

        DB::transaction(function () use ($userId, $product) {
            $cart = $this->getOrCreateUserCart($userId);

            $item = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->first();

            $stock = (int) ($product->stock ?? 0);

            if (!$item) {
                $qty = 1;
                if ($stock > 0) $qty = min($qty, $stock);

                CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                ]);
            } else {
                $newQty = (int) $item->quantity + 1;
                if ($stock > 0) $newQty = min($newQty, $stock);

                $item->quantity = $newQty;
                $item->save();
            }
        });

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

        $userId = auth()->id();

        if (!$userId) {
            return response()->json([
                'ok' => false,
                'message' => 'Harus login untuk mengubah keranjang.',
            ], 401);
        }

        $cart = $this->getOrCreateUserCart($userId);

        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if (!$item) {
            return response()->json([
                'ok' => false,
                'message' => 'Item tidak ditemukan di keranjang.',
            ], 404);
        }

        $action = $request->input('action');
        $stock = (int) ($product->stock ?? 0);

        $currentQty = (int) $item->quantity;
        $newQty = $currentQty;

        if ($action === 'inc') {
            $newQty = $currentQty + 1;
        } elseif ($action === 'dec') {
            $newQty = max(1, $currentQty - 1);
        } elseif ($action === 'set') {
            $newQty = (int) $request->input('quantity', $currentQty);
            $newQty = max(1, $newQty);
        }

        if ($stock > 0) {
            $newQty = min($newQty, $stock);
        }

        $item->quantity = $newQty;
        $item->save();

        // Totals
        $viewItems = $this->getCartItemsForView();
        $totals = $this->calculateTotalsFromViewItems($viewItems);

        $itemSubtotal = ((float) $product->price) * (int) $newQty;

        return response()->json([
            'ok' => true,
            'message' => 'Qty updated',
            'product_id' => $product->id,
            'quantity' => (int) $newQty,
            'item_subtotal' => (float) $itemSubtotal,
            'cart_subtotal' => (float) $totals['subtotal'],
            'cart_items' => (int) $totals['items'],
        ]);
    }

    /**
     * Route: DELETE /cart/{id}
     * Di versi session kamu, {id} itu product_id (key array cart)
     * Kita keep kompatibel: anggap $id = product_id.
     */
    public function remove($id)
    {
        $userId = auth()->id();

        if (!$userId) {
            return redirect()->back()->with('success', 'Produk dihapus');
        }

        $cart = $this->getOrCreateUserCart($userId);

        CartItem::where('cart_id', $cart->id)
            ->where('product_id', (int) $id)
            ->delete();

        return redirect()->back()->with('success', 'Produk dihapus');
    }

    public function clear()
    {
        $userId = auth()->id();

        if (!$userId) {
            session()->forget('cart');
            return redirect()->back()->with('success', 'Keranjang dikosongkan');
        }

        $cart = $this->getOrCreateUserCart($userId);

        CartItem::where('cart_id', $cart->id)->delete();

        return redirect()->back()->with('success', 'Keranjang dikosongkan');
    }

    // =========================
    // Helpers
    // =========================

    private function getOrCreateUserCart(int $userId): Cart
    {
        return Cart::firstOrCreate(['user_id' => $userId]);
    }

    /**
     * Format outputnya dibuat mirip versi session kamu:
     * [
     *   product_id => [
     *     product_id, name, price, quantity, image, stock
     *   ]
     * ]
     */
    private function getCartItemsForView(): array
    {
        $userId = auth()->id();

        if (!$userId) {
            return session()->get('cart', []);
        }

        $cart = $this->getOrCreateUserCart($userId);

        $items = CartItem::with('product')
            ->where('cart_id', $cart->id)
            ->get();

        $result = [];

        foreach ($items as $item) {
            $p = $item->product;
            if (!$p) continue;

            $result[$p->id] = [
                'product_id' => $p->id,
                'name' => $p->name,
                'price' => (float) $p->price,
                'quantity' => (int) $item->quantity,
                'image' => $p->image ?? null,
                'stock' => (int) ($p->stock ?? 0),
            ];
        }

        return $result;
    }

    private function calculateTotalsFromViewItems(array $cartItems): array
    {
        $subtotal = 0.0;
        $items = 0;

        foreach ($cartItems as $item) {
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

    // Fallback session (kalau suatu saat kamu buka cart tanpa auth)
    private function addToSessionCart(Product $product)
    {
        $cart = session()->get('cart', []);

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
}
