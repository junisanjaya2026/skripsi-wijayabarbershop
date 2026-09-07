<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $query = Order::with('user')->where('status', 'settlement');

    // Filter tanggal
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereDate('created_at', '>=', $request->start_date)
              ->whereDate('created_at', '<=', $request->end_date);
    }

    // Filter metode pembayaran (opsional)
    if ($request->filled('payment_method')) {
        $query->where('payment_method', $request->payment_method);
    }

    $orders = $query->orderBy('created_at', 'desc')->get();

    return view('admin.report.index', compact('orders'));
}

public function cetakReport(Request $request)
{
    $query = Order::with('user')->where('status', 'settlement');

    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereDate('created_at', '>=', $request->start_date)
              ->whereDate('created_at', '<=', $request->end_date);
    }

    if ($request->filled('payment_method')) {
        $query->where('payment_method', $request->payment_method);
    }

    $orders = $query->orderBy('created_at', 'desc')->get();
    $totalAmount = $orders->sum('grand_total');

    return view('admin.report.cetak', compact('orders', 'totalAmount'));
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
     */
    // public function update(Request $request, string $id)
    // {
        
    // $request->validate([
    //     'status' => 'required|in:pending,settlement,cancel'
    // ]);

    // $order = Order::findOrFail($id);

    // // Hanya boleh update jika tunai & pending
    // if ($order->payment_method !== 'tunai' || $order->status !== 'pending') {
    //     return redirect()->back()->with('error', 'Status tidak bisa diubah.');
    // }

    // $order->status = $request->status;
    // $order->save();

    // return redirect()->back()->with('success', 'Status order berhasil diperbarui.');
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
