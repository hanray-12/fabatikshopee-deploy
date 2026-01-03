<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();

        // KPI all time
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = (float) Order::sum('total_price');

        // KPI status
        $pendingOrders = Order::where('status', 'pending')->count();
        $paidOrders = Order::where('status', 'paid')->count();
        $shippedOrders = Order::where('status', 'shipped')->count();

        // KPI time-based
        $ordersToday = Order::whereDate('created_at', $today)->count();
        $revenueToday = (float) Order::whereDate('created_at', $today)->sum('total_price');

        $ordersThisMonth = Order::where('created_at', '>=', $monthStart)->count();
        $revenueThisMonth = (float) Order::where('created_at', '>=', $monthStart)->sum('total_price');

        // Inventory alerts
        $outOfStockCount = Product::where('stock', '<=', 0)->count();
        $lowStockCount = Product::where('stock', '>', 0)->where('stock', '<=', 5)->count();

        $lowStockProducts = Product::where('stock', '>', 0)
            ->where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get(['id', 'name', 'stock', 'price']);

        $outOfStockProducts = Product::where('stock', '<=', 0)
            ->latest()
            ->take(5)
            ->get(['id', 'name', 'stock', 'price']);

        // Latest orders (with user + items_count)
        $latestOrders = Order::with('user')
            ->withCount('items')
            ->latest()
            ->take(7)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'paidOrders',
            'shippedOrders',
            'ordersToday',
            'revenueToday',
            'ordersThisMonth',
            'revenueThisMonth',
            'outOfStockCount',
            'lowStockCount',
            'lowStockProducts',
            'outOfStockProducts',
            'latestOrders'
        ));
    }
}
