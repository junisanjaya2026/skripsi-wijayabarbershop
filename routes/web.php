<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;

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







Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');
Route::resource('/admin/categories', CategoryController::class)->names([
    'index' => 'admin.category.index'
]);
Route::resource('/admin/orders', OrderController::class)->names([
    'index' => 'admin.order.index'
]);
Route::resource('/admin/items', ItemController::class)->names([
    'index' => 'admin.item.index'
]);
Route::resource('/admin/users', UserController::class)->names([
    'index' => 'admin.user.index'
]);
Route::resource('/admin/roles', RoleController::class)->names([
    'index' => 'admin.role.index'
]);


