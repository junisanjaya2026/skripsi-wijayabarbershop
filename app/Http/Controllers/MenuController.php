<?php

namespace App\Http\Controllers;

// use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Session;
use App\Models\Item;
use Illuminate\Http\Request;


class MenuController extends Controller
{
    public function index()
    {

        $items = Item::where('is_active', 1)->orderBy('item_name','asc')->get();


        return view('customer.menu', compact('items'));

    }



    public function cart()
    {

        $cart = Session::get('cart');

        return view('customer.cart', compact ('cart'));
    }


    public function addToCart(Request $request)
    {
        $menuId = $request->input('id');
        $menu = Item::find($menuId);

        if(!$menu) {
            return response()->json([
                'status' => 'error',
                'message' => 'Item Tidak Ditemukan'
            ]);
        }

        $cart = Session::get('cart');


        if(isset($cart[$menuId])) {
            $cart[$menuId]['qty'] += 1;
        } else {
            $cart[$menuId] = [
                'id' => $menu->id,
                'item_name' => $menu->item_name,
                'price' => $menu->price,
                'image' => $menu->image_path,
                'qty' => 1
            ];
        }


        Session::put('cart', $cart);

        return response()->json([
            'status' => 'success',
            'message' => 'Item Ditambahkan ke Keranjang',
            'cart' => $cart
        ]);
    }

    public function updateCart(Request $request)
    {
      $itemId = $request->input('id');
      $newQty = $request->input('qty');


      if($newQty <= 0) {
          return response()->json([
              'success' => false,
            
          ]);
      }

      $cart = Session::get('cart');
      if(isset($cart[$itemId])) {
            $cart[$itemId]['qty'] = $newQty;
            Session::put('cart', $cart);
            Session::flash('success', 'Keranjang berhasil diperbarui.');
            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false,
        ]);

    }

    public function removeFromCart(Request $request)
    {
        $itemId = $request->input('id');
        $cart = Session::get('cart');

        if(isset($cart[$itemId])) {
            unset($cart[$itemId]);
            Session::put('cart', $cart);
            Session::flash('success', 'Item berhasil dihapus dari keranjang.');
            return response()->json([
                'success' => true
            ]);
        }

        return response()->json([
            'success' => false,
        ]);
    }

    public function clearCart()
    {
        Session::forget('cart');
        Session::flash('success', 'Keranjang berhasil dikosongkan.');
        return redirect()->route('cart');
    }
}
