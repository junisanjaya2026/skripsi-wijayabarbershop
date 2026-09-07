<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * Khusus pembayaran tunai:
     * pending -> settlement
     * Setelah settlement, sistem memberikan nomor antrian.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,settlement,cancel'
        ]);

        $order = Order::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | VALIDASI ORDER
        |--------------------------------------------------------------------------
        |
        | Hanya order tunai dengan status pending yang boleh diproses
        | oleh kasir.
        |
        */

        if (
            $order->payment_method !== 'tunai' ||
            $order->status !== 'pending'
        ) {
            return redirect()->back()
                ->with('error', 'Status order tidak bisa diubah.');
        }

        /*
        |--------------------------------------------------------------------------
        | PEMBAYARAN BERHASIL
        |--------------------------------------------------------------------------
        |
        | Nomor antrian BARU dibuat ketika status berubah menjadi settlement.
        |
        */

        if ($request->status === 'settlement') {

            // Ubah status terlebih dahulu
            $order->status = 'settlement';

            /*
            |----------------------------------------------------------------------
            | BUAT NOMOR ANTRIAN
            |----------------------------------------------------------------------
            |
            | Pastikan order belum memiliki nomor antrian.
            |
            */

            if (is_null($order->queue_number)) {

                $queue = $this->generateQueue();

                $order->queue_number = $queue['queue_number'];
                $order->queue_time = $queue['queue_time'];
            }

            $order->save();

            return redirect()->back()
                ->with(
                    'success',
                    'Pembayaran berhasil. Nomor antrian telah diberikan.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS SELAIN SETTLEMENT
        |--------------------------------------------------------------------------
        */

        $order->status = $request->status;
        $order->save();

        return redirect()->back()
            ->with('success', 'Status order berhasil diperbarui.');
    }

    /**
     * Generate nomor dan waktu antrian.
     */
    private function generateQueue()
    {
        /*
        |--------------------------------------------------------------------------
        | TIMEZONE
        |--------------------------------------------------------------------------
        */

        date_default_timezone_set('Asia/Jakarta');

        /*
        |--------------------------------------------------------------------------
        | JAM OPERASIONAL
        |--------------------------------------------------------------------------
        */

        $openTime = Carbon::today()->setTime(8, 0);
        $closeTime = Carbon::today()->setTime(22, 0);

        /*
        |--------------------------------------------------------------------------
        | WAKTU SHALAT
        |--------------------------------------------------------------------------
        */

        $dzuhurStart = Carbon::today()->setTime(12, 0);
        $dzuhurEnd = Carbon::today()->setTime(13, 0);

        $asharStart = Carbon::today()->setTime(15, 15);
        $asharEnd = Carbon::today()->setTime(15, 45);

        /*
        |--------------------------------------------------------------------------
        | DURASI LAYANAN
        |--------------------------------------------------------------------------
        */

        $serviceDuration = 30;

        /*
        |--------------------------------------------------------------------------
        | CARI ORDER TERAKHIR YANG SUDAH MENDAPAT ANTRIAN
        |--------------------------------------------------------------------------
        |
        | Pending tidak dihitung karena queue_number masih NULL.
        |
        */

        $lastOrder = Order::whereDate('created_at', today())
            ->whereNotNull('queue_number')
            ->orderBy('queue_number', 'desc')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | NOMOR ANTRIAN
        |--------------------------------------------------------------------------
        */

        if ($lastOrder) {

            $queueNumber = $lastOrder->queue_number + 1;

        } else {

            $queueNumber = 1;
        }

        /*
        |--------------------------------------------------------------------------
        | MENENTUKAN WAKTU ANTRIAN
        |--------------------------------------------------------------------------
        */

        $now = Carbon::now();

        if ($lastOrder && $lastOrder->queue_time) {

            $nextSlot = Carbon::parse($lastOrder->queue_time)
                ->addMinutes($serviceDuration);

        } else {

            $nextSlot = $openTime->copy();
        }

        /*
        | Ambil waktu yang paling akhir antara:
        | - slot berikutnya
        | - waktu sekarang
        */

        $queueTime = $nextSlot->greaterThan($now)
            ? $nextSlot
            : $now->copy();

        /*
        |--------------------------------------------------------------------------
        | JIKA SEBELUM JAM BUKA
        |--------------------------------------------------------------------------
        */

        if ($queueTime->lessThan($openTime)) {

            $queueTime = $openTime->copy();
        }

        /*
        |--------------------------------------------------------------------------
        | LEWATI WAKTU DZUHUR
        |--------------------------------------------------------------------------
        */

        if ($queueTime->between($dzuhurStart, $dzuhurEnd)) {

            $queueTime = $dzuhurEnd->copy();
        }

        /*
        |--------------------------------------------------------------------------
        | LEWATI WAKTU ASHAR
        |--------------------------------------------------------------------------
        */

        if ($queueTime->between($asharStart, $asharEnd)) {

            $queueTime = $asharEnd->copy();
        }

        /*
        |--------------------------------------------------------------------------
        | JIKA SUDAH LEWAT JAM OPERASIONAL
        |--------------------------------------------------------------------------
        */

        if ($queueTime->greaterThan($closeTime)) {

            $queueTime = Carbon::tomorrow()->setTime(8, 0);

            $queueNumber = 1;
        }

        /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */

        return [
            'queue_number' => $queueNumber,
            'queue_time' => $queueTime->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}