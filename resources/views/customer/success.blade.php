@extends('customer.layouts.master')

@section('title','Pesanan Berhasil')

@section('content') 
<!-- Single Page Header start -->
<div class="container-fluid page-header py-5" style="background: url('https://images.unsplash.com/photo-1524504388940-b1c1722653e1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80') center/cover no-repeat;">
    <h1 class="text-center text-white display-6">Receipt Wijaya Barber</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item active text-primary">Receipt your order</li>
    </ol>
</div>
<!-- Single Page Header End -->
<section>
    <div class="container-fluid py-5 d-flex justify-content-center ">
    <div class="receipt border p-4 bg-white shadow" style="width: 450px margin-top:5rem">
        <h5 class="text-center mb-2">Pesanan Berhasil Dibuat</h5>
        @if ($order->payment_method == 'tunai' && $order->status == 'pending')
          <p class="text-center"><span class="badge bg-danger">Menunggu Pembayaran</span></p>

        @elseif ($order->payment_method == 'qris' && $order->status == 'pending')
            <p class="text-center"><span class="badge bg-danger text-dark">Menunggu Konfirmasi Pembayaran</span></p> 
        @else
            <p class="text-center"><span class="badge bg-success">Pembayaran Berhasil Silahkan Datang Dengan Membawa Nota ini</span></p>
        @endif
             <h4 class="fw-bold text-center">
                Kode bayar:
                <span class="text-primary">{{ $order->order_code }}</span>
             </h4>

        <hr>

        <h5 class="mb-3 text-center">Detail Pesanan</h5>

        <table class="table table-borderless">
            <tbody>
                @foreach ($orderItems as $orderItem)
                    <tr>
                        <td>{{Str::limit($orderItem->item->item_name, 25) }} ({{ $orderItem->quantity }})</td>
                        <td class="text-end">
                            {{ 'Rp. ' . number_format($orderItem->price, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="table table-borderless">
            <tbody>
                <tr>
                    <td class="fw-bold">Subtotal</td>
                    <td class="text-end">{{ 'Rp. ' . number_format($order->subtotal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Pajak (10%)</td>
                    <td class="text-end">{{ 'Rp. ' . number_format($order->tax, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Grand Total</td>
                    <td class="text-end">{{ 'Rp. ' . number_format($order->grand_total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>


        @if ($order->payment_method == 'tunai')
        <p class="text-center small" > *Tunjukan kode bayar kekasir untuk menyelesaikan pembayaran</p>
            
        @elseif($order->payment_method == 'qris')
        <p class="text-center small">Silahkan datang sesuai waktu </p>
        @endif
        <hr>
        <div class="text-center">
            <a href="#" class="btn btn-warning">Download Receipt</a>
            <a href="{{ route('menu') }}" class="btn btn-primary">Kembali ke Menu</a>
        </div>
</div>
</section>



@endsection