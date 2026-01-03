<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status'); // pending|paid|shipped|null
        $q = $request->input('q'); // search by order id / user name

        $orders = Order::with('user')
            ->withCount('items')
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($q, function ($query) use ($q) {
                $query->where('id', $q)
                      ->orWhereHas('user', function ($u) use ($q) {
                          $u->where('name', 'like', '%' . $q . '%');
                      });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // quick stats
        $countPending = Order::where('status', 'pending')->count();
        $countPaid = Order::where('status', 'paid')->count();
        $countShipped = Order::where('status', 'shipped')->count();

        return view('admin.orders.index', compact(
            'orders',
            'status',
            'q',
            'countPending',
            'countPaid',
            'countShipped'
        ));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid,shipped',
        ]);

        $order->status = $validated['status'];
        $order->save();

        return redirect()->back()->with('success', 'Status order #' . $order->id . ' berhasil diupdate.');
    }
}
