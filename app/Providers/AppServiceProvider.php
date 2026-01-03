<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\CartItem;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {

            $count = 0;

            if (auth()->check()) {
                $count = CartItem::whereHas('cart', function ($q) {
                    $q->where('user_id', auth()->id());
                })->sum('quantity');
            }

            $view->with('cartCount', $count);
        });
    }
}
