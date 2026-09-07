<?php

namespace App\Http\Controllers;

// use Illuminate\Contracts\Session\Session;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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
            'cart' => $cart,
            'cartCount' => $this->getCartCount(),
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
                'success' => true,
                'cartCount' => $this->getCartCount(),
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
                'success' => true,
                'cartCount' => $this->getCartCount(),
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


    /**
     * Hitung total qty item di cart (dipakai untuk badge navbar).
     */
    private function getCartCount()
    {
        return collect(Session::get('cart', []))->sum('qty');
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
    $cart = Session::get('cart', []);

    if (empty($cart)) {

        return redirect()
            ->route('cart')
            ->with('error', 'Keranjang kosong.');
    }

    $validator = Validator::make($request->all(), [
        'fullname' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'payment_method' => 'required|in:qris,tunai',
    ]);

    if ($validator->fails()) {

        return redirect()
            ->back()
            ->withErrors($validator)
            ->withInput();
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL
    |--------------------------------------------------------------------------
    */

    $totalAmount = 0;
    $itemDetails = [];

    foreach ($cart as $item) {

        $totalAmount += $item['price'] * $item['qty'];

        $itemDetails[] = [
            'id' => $item['id'],
            'price' => (int) $item['price'],
            'quantity' => (int) $item['qty'],
            'name' => $item['item_name'],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */

        if (auth()->check()) {

            $user = auth()->user();

        } else {

            $user = User::firstOrCreate(

                [
                    'phone' => $request->phone,
                ],

                [
                    'fullname' => $request->fullname,
                    'role_id' => 4,
                    'email' => 'guest_' . time() . '@guest.com',
                    'password' => bcrypt('guest123'),
                ]
            );
        }

    /*
    |--------------------------------------------------------------------------
    | ORDER
    |--------------------------------------------------------------------------
    */

    $tax = round($totalAmount * 0.1);

    // $queue = $this->generateQueue();

    $order = Order::create([
        'order_code' => 'ORD' . time(),
        'user_id' => $user->id,
        'subtotal' => $totalAmount,
        'tax' => $tax,
        'grand_total' => $totalAmount + $tax,
        'status' => 'pending',
        'payment_method' => $request->payment_method,
        'notes' => $request->notes,

        

        // generete antrian
        'queue_number' => null,
        'queue_time' => null,
    ]);

//     if ($request->payment_method === 'tunai') {

//     $queue = $this->generateQueue();

//     $order->update([
//         'queue_number' => $queue['queue_number'],
//         'queue_time' => $queue['queue_time'],
//     ]);
// }
    /*
    |--------------------------------------------------------------------------
    | ORDER ITEMS
    |--------------------------------------------------------------------------
    */

    foreach ($cart as $item) {

        // FIX: hitung pajak per-item, bukan pakai $tax (total pajak seluruh order)
        $itemSubtotal = $item['price'] * $item['qty'];
        $itemTax = round($itemSubtotal * 0.1);

        OrderItem::create([
            'order_id' => $order->id,
            'item_id' => $item['id'],
            'quantity' => $item['qty'],
            'price' => $item['price'],
            'tax' => $itemTax,
            'total_price' => $itemSubtotal + $itemTax,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | TUNAI
    |--------------------------------------------------------------------------
    */

    if ($request->payment_method == 'tunai') {

        Session::forget('cart');

        return redirect()->route(
            'order.success',
            ['orderId' => $order->order_code]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MIDTRANS
    |--------------------------------------------------------------------------
    */

    \Midtrans\Config::$serverKey = config('midtrans.server_key');
    \Midtrans\Config::$isProduction = config('midtrans.is_production');
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    // FIX: Midtrans mewajibkan gross_amount = jumlah seluruh item_details
    // (price x quantity). Karena gross_amount memakai grand_total (sudah
    // termasuk pajak), pajak harus ditambahkan sebagai baris item_details
    // tersendiri, kalau tidak Snap akan menolak/transaksi mismatch.
    if ($tax > 0) {
        $itemDetails[] = [
            'id' => 'TAX',
            'price' => (int) $tax,
            'quantity' => 1,
            'name' => 'Pajak (10%)',
        ];
    }

    $params = [

        'transaction_details' => [
            'order_id' => $order->order_code,
            'gross_amount' => (int) $order->grand_total,
        ],

        'customer_details' => [
            'first_name' => $user->fullname,
            'phone' => $user->phone,
        ],

        'item_details' => $itemDetails,
    ];

    try {

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        return response()->json([
            'status' => 'success',
            'snap_token' => $snapToken,
            'order_id' => $order->order_code,
        ]);

    } catch (\Exception $e) {

        \Log::error($e->getMessage());

        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
}

    // public function orderSuccess($orderId)
    // {
    //   $order = Order::where('order_code', $orderId)->first();

    //   if(!$order){
    //     return redirect()->route('menu')->with('error', 'Pesanan tidak ditemukan.');
    //   }


    //   $orderItems = OrderItem::where('order_id', $order->id)->get();
      
    //   if($order->payment_method == 'qris'){
    //     $order->status = 'settlement';
    //     $order->save();
    //   }


    //    Session::forget('cart');

    //     return view('customer.success', compact('order', 'orderItems'));
    // }


    public function orderSuccess($orderId)
{
    $order = Order::where('order_code', $orderId)->first();

    if (!$order) {
        return redirect()
            ->route('menu')
            ->with('error', 'Pesanan tidak ditemukan.');
    }

    $orderItems = OrderItem::where('order_id', $order->id)->get();

    Session::forget('cart');

    return view('customer.success', compact('order', 'orderItems'));
}



public function midtransNotification(Request $request)
{
    \Log::info('=== MIDTRANS NOTIFICATION MASUK ===', $request->all());

    $orderId = $request->input('order_id');
    $transactionStatus = $request->input('transaction_status');
    $fraudStatus = $request->input('fraud_status');
    $statusCode = $request->input('status_code');
    $grossAmount = $request->input('gross_amount');

    /*
    |--------------------------------------------------------------------------
    | VALIDASI DATA DASAR
    |--------------------------------------------------------------------------
    */

    if (!$orderId) {
        return response()->json([
            'status' => 'error',
            'message' => 'Order ID tidak ditemukan.'
        ], 400);
    }

    /*
    |--------------------------------------------------------------------------
    | CARI ORDER
    |--------------------------------------------------------------------------
    */

    $order = Order::where('order_code', $orderId)->first();

    /*
    |--------------------------------------------------------------------------
    | NOTIFICATION TEST DARI MIDTRANS
    |--------------------------------------------------------------------------
    |
    | Notification Test menggunakan order_id khusus dari Midtrans,
    | sehingga order tersebut tidak ada di database kita.
    |
    | Tetap balas 200 agar Midtrans menganggap endpoint berhasil.
    |
    */

    if (!$order) {

        \Log::warning('ORDER TIDAK DITEMUKAN - KEMUNGKINAN NOTIFICATION TEST', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Notification diterima. Order tidak ditemukan di database.'
        ], 200);
    }

    /*
    |--------------------------------------------------------------------------
    | SETTLEMENT
    |--------------------------------------------------------------------------
    */

    if (
        $transactionStatus === 'settlement' &&
        $statusCode === '200' &&
        $fraudStatus === 'accept'
    ) {

        /*
        |--------------------------------------------------------------------------
        | UBAH STATUS ORDER
        |--------------------------------------------------------------------------
        */

        $order->status = 'settlement';
        $order->save();

        /*
        |--------------------------------------------------------------------------
        | GENERATE NOMOR ANTRIAN
        |--------------------------------------------------------------------------
        |
        | Hanya jika belum mempunyai nomor antrian.
        |
        */

        if (is_null($order->queue_number)) {

            $queue = $this->generateQueue();

            $order->queue_number = $queue['queue_number'];
            $order->queue_time = $queue['queue_time'];

            $order->save();

            \Log::info('NOMOR ANTRIAN BERHASIL DIBUAT', [
                'order_id' => $order->order_code,
                'queue_number' => $order->queue_number,
                'queue_time' => $order->queue_time,
            ]);
        }

        \Log::info('PEMBAYARAN BERHASIL SETTLEMENT', [
            'order_id' => $order->order_code,
            'status' => $order->status,
            'queue_number' => $order->queue_number,
            'queue_time' => $order->queue_time,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PENDING
    |--------------------------------------------------------------------------
    */

    elseif ($transactionStatus === 'pending') {

        $order->status = 'pending';
        $order->save();

        \Log::info('PEMBAYARAN MASIH PENDING', [
            'order_id' => $order->order_code,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | EXPIRE
    |--------------------------------------------------------------------------
    */

    elseif (
        $transactionStatus === 'expire' ||
        $transactionStatus === 'expired'
    ) {

        $order->status = 'expire';
        $order->save();

        \Log::info('PEMBAYARAN EXPIRED', [
            'order_id' => $order->order_code,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | CANCEL / DENY
    |--------------------------------------------------------------------------
    */

    elseif (
        $transactionStatus === 'cancel' ||
        $transactionStatus === 'deny'
    ) {

        $order->status = 'pending';
        $order->save();

        \Log::info('PEMBAYARAN CANCEL / DENY', [
            'order_id' => $order->order_code,
            'transaction_status' => $transactionStatus,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | RESPONSE KE MIDTRANS
    |--------------------------------------------------------------------------
    */

    return response()->json([
        'status' => 'success',
        'message' => 'Notification berhasil diproses.'
    ], 200);
}





private function generateQueue()
{
    /*
    |------------------------------------------------------------------
    | TIMEZONE
    |------------------------------------------------------------------
    */
 
    date_default_timezone_set('Asia/Jakarta');
 
    /*
    |------------------------------------------------------------------
    | JAM OPERASIONAL
    |------------------------------------------------------------------
    */
 
    $openTime = Carbon::today()->setTime(8, 0);   // 08:00
    $closeTime = Carbon::today()->setTime(22, 0); // 22:00
 
    /*
    |------------------------------------------------------------------
    | WAKTU SHOLAT
    |------------------------------------------------------------------
    */
 
    $dzuhurStart = Carbon::today()->setTime(12, 0);
    $dzuhurEnd   = Carbon::today()->setTime(13, 0);
 
    $asharStart  = Carbon::today()->setTime(15, 15);
    $asharEnd    = Carbon::today()->setTime(15, 45);
 
    /*
    |------------------------------------------------------------------
    | DURASI LAYANAN
    |------------------------------------------------------------------
    */
 
    $serviceDuration = 30; // menit
 
    /*
    |------------------------------------------------------------------
    | ORDER TERAKHIR HARI INI
    |------------------------------------------------------------------
    */
 
    $lastOrder = Order::whereDate('created_at', today())
        ->whereNotNull('queue_number')
        ->orderBy('queue_number', 'desc')
        ->first();
 
    /*
    |------------------------------------------------------------------
    | NOMOR ANTRIAN
    |------------------------------------------------------------------
    */
 
    if ($lastOrder) {
 
        $queueNumber = $lastOrder->queue_number + 1;
 
    } else {
 
        $queueNumber = 1;
    }
 
    /*
    |------------------------------------------------------------------
    | WAKTU ANTRIAN
    |------------------------------------------------------------------
    | Slot antrian dihitung dari order terakhir + durasi layanan.
    | Tapi slot ini tidak boleh mundur dari waktu SEKARANG — kalau
    | antrian sebelumnya sudah pasti selesai dilayani (mis. order
    | terakhir jam 10:00 tapi sekarang sudah jam 14:00), maka order
    | baru harus mulai dari waktu sekarang, bukan dari slot lama yang
    | sudah lewat.
    */
 
    $now = Carbon::now();
 
    if ($lastOrder && $lastOrder->queue_time) {
 
        $nextSlot = Carbon::parse($lastOrder->queue_time)
            ->addMinutes($serviceDuration);
 
    } else {
 
        $nextSlot = $openTime->copy();
    }
 
    // Ambil mana yang lebih telat: slot antrian berikutnya, atau sekarang
    $queueTime = $nextSlot->greaterThan($now) ? $nextSlot : $now->copy();
 
    /*
    |------------------------------------------------------------------
    | JIKA SEBELUM BUKA
    |------------------------------------------------------------------
    */
 
    if ($queueTime->lessThan($openTime)) {
 
        $queueTime = $openTime->copy();
    }
 
    /*
    |------------------------------------------------------------------
    | SKIP DZUHUR
    |------------------------------------------------------------------
    */
 
    if ($queueTime->between($dzuhurStart, $dzuhurEnd)) {
 
        $queueTime = $dzuhurEnd->copy();
    }
 
    /*
    |------------------------------------------------------------------
    | SKIP ASHAR
    |------------------------------------------------------------------
    */
 
    if ($queueTime->between($asharStart, $asharEnd)) {
 
        $queueTime = $asharEnd->copy();
    }
 
    /*
    |------------------------------------------------------------------
    | JIKA LEWAT JAM TUTUP
    |------------------------------------------------------------------
    */
 
    if ($queueTime->greaterThan($closeTime)) {
 
        $queueTime = Carbon::tomorrow()->setTime(8, 0);
 
        $queueNumber = 1;
    }
 
    /*
    |------------------------------------------------------------------
    | RETURN
    |------------------------------------------------------------------
    */
 
    return [
 
        'queue_number' => $queueNumber,
 
        'queue_time' => $queueTime->format('Y-m-d H:i:s'),
 
    ];
}


// orederan saya sesaui dengan user yang login
public function myOrders()
{
    $orders = Order::with('orderItems.item')
        ->where('user_id', auth()->id())
        ->latest()
        ->get();

    return view('customer.my-orders', compact('orders'));
}


public function downloadReceipt($orderId)
{
    $order = Order::with('orderItems.item')
        ->where('order_code', $orderId)
        ->first();

    if (!$order) {
        return redirect()->route('my.orders')->with('error', 'Pesanan tidak ditemukan.');
    }

    // Batasi hanya pemilik order (atau guest tanpa login) yang bisa download
    if (auth()->check() && $order->user_id !== auth()->id()) {
        abort(403);
    }

    $pdf = Pdf::loadView('customer.receipt', compact('order'));

    return $pdf->download('Resi-' . $order->order_code . '.pdf');
}

private function assignQueue(Order $order)
{
    // Jangan berikan antrian jika belum settlement
    if ($order->status !== 'settlement') {
        return;
    }

    // Jangan membuat nomor antrian baru jika sudah ada
    if ($order->queue_number !== null) {
        return;
    }

    $queue = $this->generateQueue();

    $order->update([
        'queue_number' => $queue['queue_number'],
        'queue_time' => $queue['queue_time'],
    ]);
}
}