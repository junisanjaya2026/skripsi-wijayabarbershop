<?php

namespace App\Providers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Sediakan $cartCount otomatis ke navbar tanpa perlu compact() manual
        // di setiap controller. Sesuaikan nama view di bawah dengan nama
        // partial navbar milikmu (lihat php artisan route:list / cek folder
        // resources/views/customer/layouts).
        View::composer('customer.layouts.__navbar', function ($view) {
            $cart = Session::get('cart', []);
            $cartCount = collect($cart)->sum('qty');
            $view->with('cartCount', $cartCount);
        });
    }
}