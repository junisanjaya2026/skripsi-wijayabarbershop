<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;

Route::get('/', function () {
    return view ('customer.home');
});

// Route::get('/', [MenuController::class, 'index'])->name('menu');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');
Route::get('/cart', [MenuController::class, 'cart'])->name('cart');
Route::post('/add-to-cart', [MenuController::class, 'addToCart'])->name('add.to.cart');
Route::post('/update-cart', [MenuController::class, 'updateCart'])->name('update.cart');
Route::post('/remove-from-cart', [MenuController::class, 'removeFromCart'])->name('remove.from.cart');
Route::get('/clear-cart', [MenuController::class, 'clearCart'])->name('clear.cart');

Route::get('/checkout', [MenuController::class, 'checkout'])->name('checkout');
Route::post('/checkout/store', [MenuController::class, 'storeOrder'])->name('checkout.store');
Route::get('/order/success/{orderId}', [MenuController::class, 'orderSuccess'])->name('order.success');
