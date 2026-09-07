<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class CartCountComposer
{
    public function compose(View $view)
    {
        $cart = Session::get('cart', []);

        // total quantity (bukan cuma jumlah baris item)
        $cartCount = collect($cart)->sum('qty');

        $view->with('cartCount', $cartCount);
    }
}