<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Search support: ?search=... (baru) dan ?q=... (lama)
        $search = $request->input('search');
        if ($search === null) {
            $search = $request->input('q');
        }

        // Filter & sort
        $sort = $request->input('sort', 'latest'); // latest|price_asc|price_desc
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $inStock = $request->boolean('in_stock'); // checkbox

        $query = Product::query();

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($minPrice !== null && $minPrice !== '') {
            $query->where('price', '>=', (float) $minPrice);
        }

        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('price', '<=', (float) $maxPrice);
        }

        if ($inStock) {
            $query->where('stock', '>', 0);
        }

        // Sorting
        if ($sort === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(10)->withQueryString();

        return view('home', [
            'products' => $products,
            'search' => $search,
            'sort' => $sort,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'inStock' => $inStock,
        ]);
    }
}
