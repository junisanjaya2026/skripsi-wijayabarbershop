<?php

namespace App\Http\Controllers;

// use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Session;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;

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


    #checkout
    public function checkout(){
        $cart=Session::get('cart');
        if(empty($cart)){
            return redirect()->route('cart')->with('error','Keranjang Anda kosong. Silakan tambahkan item sebelum melanjutkan ke checkout.');
        }

        return view('customer.checkout');
    }



    public function storeOrder(Request $request)
    {
      $cart = Session::get('cart');
        if(empty($cart)){
            return redirect()->route('cart')->with('error','Keranjang Anda kosong. Silakan tambahkan item sebelum melanjutkan ke checkout.');
        }

        $validator = Validator::make($request->all(), [
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['qty'];
        }

        $totalAmount = 0; 
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['qty'];

            $itemDetails[] = [
                'item_id' => $item['id'],
                'item_name' => $item['item_name'],
                'price' => $item['price'] + ($item['price'] * 0.1), // including 10% tax
                'quantity' => $item['qty'],
            ];
        }


        $user = User::firstOrCreate([
            'fullname' => $request->input('fullname'),
            'phone' => $request->input('phone'),
            'role_id' => 4

            ]);

            $order = Order::create([
                'order_code' => 'ORD' . time(),
                'user_id' => $user->id,
                'subtotal' => $totalAmount,
                'tax' => round($totalAmount * 0.1),
                'grand_total' => $totalAmount + round($totalAmount * 0.1),
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'notes' => $request->notes
            ]);

            foreach($cart as $itemId =>$item ){
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'] + ($item['price'] * 0.1), // including 10% tax
                    'tax' => $item['price'] * 0.1 * $item['qty'],
                    'total_price' => ($item['price'] + ($item['price'] * 0.1)) * $item['qty'],
                ]);
            }

        Session::forget('cart');

        return redirect()->route('order.success',['orderId'=>$order->order_code])->with('success', 'Pesanan Anda telah berhasil diproses. Terima kasih!');
    }

    public function orderSuccess($orderId)
    {
      $order = Order::where('order_code', $orderId)->first();

      if(!$order){
        return redirect()->route('menu')->with('error', 'Pesanan tidak ditemukan.');
      }


      $orderItems = OrderItem::where('order_id', $order->id)->get();
      
      if($order->payment_mehtod == 'qris'){
        $order->status = 'settlement';
        $order->save();
      }

        return view('customer.success', compact('order', 'orderItems'));
    }
}
