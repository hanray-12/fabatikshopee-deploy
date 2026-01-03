<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products/{product}', [ProductController::class, 'show'])
    ->name('products.show');

/*
|--------------------------------------------------------------------------
| AUTH (Breeze / Jetstream)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| USER (LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    | CART
    */
    Route::get('/cart', [CartController::class, 'index'])
        ->name('cart.index');

    // ini sesuai punya lu: POST /cart/{product}
    Route::post('/cart/{product}', [CartController::class, 'add'])
        ->name('cart.add');

    Route::delete('/cart/{id}', [CartController::class, 'remove'])
        ->name('cart.remove');

    // ✅ TAMBAHAN: AJAX update qty (PATCH)
    Route::patch('/cart/{product}/qty', [CartController::class, 'updateQty'])
        ->name('cart.qty');

    // ✅ OPTIONAL: tombol "kosongkan keranjang"
    Route::post('/cart/clear', [CartController::class, 'clear'])
        ->name('cart.clear');

    /*
    | CHECKOUT
    */
    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    Route::post('/checkout', [CheckoutController::class, 'process'])
        ->name('checkout.process');

    /*
    | ORDERS
    */
    Route::get('/orders', [OrderController::class, 'index'])
        ->name('orders.index');

    Route::get('/orders/{order}', [OrderController::class, 'show'])
        ->name('orders.show');
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::resource('products', AdminProductController::class);

        Route::resource('orders', AdminOrderController::class)
            ->only(['index', 'show', 'update']);
    });
