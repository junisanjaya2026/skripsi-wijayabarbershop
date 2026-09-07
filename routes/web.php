<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('customer.home');
});

/*
|--------------------------------------------------------------------------
| CUSTOMER MENU
|--------------------------------------------------------------------------
| Tidak perlu login
*/

Route::get('/menu', [MenuController::class, 'index'])->name('menu');

Route::get('/cart', [MenuController::class, 'cart'])->name('cart');

Route::post('/add-to-cart', [MenuController::class, 'addToCart'])
    ->name('add.to.cart');

Route::post('/update-cart', [MenuController::class, 'updateCart'])
    ->name('update.cart');

Route::post('/remove-from-cart', [MenuController::class, 'removeFromCart'])
    ->name('remove.from.cart');

Route::get('/clear-cart', [MenuController::class, 'clearCart'])
    ->name('clear.cart');

Route::get('/checkout', [MenuController::class, 'checkout'])
    ->name('checkout');

Route::post('/checkout/store', [MenuController::class, 'storeOrder'])
    ->name('checkout.store');

Route::get('/order/success/{orderId}', [MenuController::class, 'orderSuccess'])
    ->name('order.success');
   


/*
|--------------------------------------------------------------------------
| ADMIN DASHBOARD
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'check.role'])->group(function () {

    // Route::get('/dashboard', function () {
    //     return view('admin.dashboard');
    // })->name('dashboard');

    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->name('admin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN RESOURCE
    |--------------------------------------------------------------------------
    */

    Route::resource('/admin/categories', CategoryController::class)
        ->names([
            'index' => 'admin.category.index'
        ]);

    Route::resource('/admin/orders', OrderController::class)
        ->names([
            'index' => 'admin.order.index'
        ]);

    Route::resource('/admin/reports', ReportController::class)
        ->names([
            'index' => 'admin.report.index'
        ]);

    Route::get('admin/report/cetak', [ReportController::class, 'cetakReport'])->name('report.cetak');


    Route::resource('/admin/items', ItemController::class)
        ->names([
            'index' => 'admin.item.index'
        ]);

    Route::resource('/admin/users', UserController::class)
        ->names([
            'index' => 'admin.user.index'
        ]);

    Route::resource('/admin/roles', RoleController::class)
        ->names([
            'index' => 'admin.role.index'
        ]);

    Route::patch('/users/{id}/on-duty', [UserController::class, 'updateOnDuty'])
        ->name('users.on-duty');
});

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/my-orders', [MenuController::class, 'myOrders'])
        ->name('my.orders');

    Route::get('/orders/{orderId}/receipt', [MenuController::class, 'downloadReceipt'])
    ->name('order.receipt');
});

require __DIR__.'/auth.php';