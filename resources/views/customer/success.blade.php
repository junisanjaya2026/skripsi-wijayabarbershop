@extends('customer.layouts.master')

@section('title', 'Pesanan Berhasil')

@section('content')

<!-- Page Header -->

<div class="container-fluid page-header py-5"
    style="background: url('https://images.unsplash.com/photo-1524504388940-b1c1722653e1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80') center/cover no-repeat;">
    <h1 class="text-center text-white display-6">Receipt Wijaya Barber</h1>

```
<ol class="breadcrumb justify-content-center mb-0">
    <li class="breadcrumb-item active text-primary">
        Receipt Your Order
    </li>
</ol>
```

</div>
<!-- Page Header End -->

<section>
    <div class="container-fluid py-5 d-flex justify-content-center">

```
    <div class="receipt border p-4 bg-white shadow"
        style="width: 450px; margin-top: 2rem;">

        <!-- STATUS PESANAN -->
        <div class="text-center mb-4">

            <h5 class="fw-bold mb-2">
                Pesanan Berhasil Dibuat
            </h5>

            @if ($order->payment_method === 'tunai' && $order->status === 'pending')

                <span class="badge bg-danger px-3 py-2">
                    Menunggu Pembayaran Tunai
                </span>

            @elseif ($order->payment_method === 'qris' && $order->status === 'pending')

                <span class="badge bg-warning text-dark px-3 py-2">
                    Menunggu Konfirmasi Pembayaran
                </span>

            @elseif ($order->status === 'settlement')

                <span class="badge bg-success px-3 py-2">
                    Pembayaran Berhasil
                </span>

            @else

                <span class="badge bg-secondary px-3 py-2">
                    {{ ucfirst($order->status) }}
                </span>

            @endif

        </div>


        <!-- NOMOR ANTRIAN -->
        @if ($order->status === 'settlement' && $order->queue_number)

            <div class="alert alert-success text-center">

                <div class="small text-uppercase fw-semibold mb-1">
                    Nomor Antrian
                </div>

                <div class="display-6 fw-bold">
                    A-{{ str_pad($order->queue_number, 3, '0', STR_PAD_LEFT) }}
                </div>

                @if ($order->queue_time)

                    <p class="mb-1 mt-2">
                        <strong>Estimasi Layanan</strong>
                    </p>

                    <p class="mb-2">
                        {{ \Carbon\Carbon::parse($order->queue_time)->format('d M Y, H:i') }}
                    </p>

                @endif

                <p class="text-danger fw-bold mb-0">
                    Silahkan datang sesuai waktu yang telah ditentukan.
                </p>

            </div>

        @else

            <!-- BELUM MENDAPAT ANTRIAN -->
            <div class="alert alert-warning text-center">

                @if ($order->payment_method === 'qris')

                    <strong>Pembayaran Sedang Diproses</strong>

                    <p class="mb-0 mt-2 small">
                        Nomor antrian akan diberikan setelah pembayaran
                        berhasil dikonfirmasi.
                    </p>

                @elseif ($order->payment_method === 'tunai')

                    <strong>Menunggu Pembayaran Tunai</strong>

                    <p class="mb-0 mt-2 small">
                        Silahkan melakukan pembayaran kepada kasir.
                        Nomor antrian diberikan setelah pembayaran
                        dikonfirmasi.
                    </p>

                @else

                    <strong>Nomor Antrian Belum Tersedia</strong>

                @endif

            </div>

        @endif


        <!-- KODE ORDER -->
        <div class="text-center mb-3">

            <div class="small text-muted">
                Kode Bayar
            </div>

            <h4 class="fw-bold mb-0">
                <span class="text-primary">
                    {{ $order->order_code }}
                </span>
            </h4>

        </div>


        <hr>


        <!-- DETAIL PESANAN -->
        <h5 class="mb-3 text-center fw-bold">
            Detail Pesanan
        </h5>

        <table class="table table-borderless mb-2">

            <tbody>

                @foreach ($orderItems as $orderItem)

                    <tr>

                        <td>
                            {{ Str::limit($orderItem->item->item_name, 25) }}

                            <span class="text-muted">
                                × {{ $orderItem->quantity }}
                            </span>
                        </td>

                        <td class="text-end">
                            Rp {{ number_format($orderItem->price, 0, ',', '.') }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>


        <hr>


        <!-- TOTAL -->
        <table class="table table-borderless mb-2">

            <tbody>

                <tr>
                    <td>Subtotal</td>

                    <td class="text-end">
                        Rp {{ number_format($order->subtotal, 0, ',', '.') }}
                    </td>
                </tr>

                <tr>
                    <td>Pajak (10%)</td>

                    <td class="text-end">
                        Rp {{ number_format($order->tax, 0, ',', '.') }}
                    </td>
                </tr>

                <tr class="border-top">

                    <td class="fw-bold pt-3">
                        Grand Total
                    </td>

                    <td class="text-end fw-bold pt-3">
                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                    </td>

                </tr>

            </tbody>

        </table>


        <!-- INFORMASI PEMBAYARAN -->
        @if ($order->payment_method === 'tunai' && $order->status === 'pending')

            <div class="text-center mt-3">

                <p class="small text-muted mb-0">
                    Tunjukkan kode bayar
                    <strong>{{ $order->order_code }}</strong>
                    kepada kasir untuk menyelesaikan pembayaran.
                </p>

            </div>

        @elseif ($order->payment_method === 'qris' && $order->status === 'pending')

            <div class="text-center mt-3">

                <p class="small text-muted mb-0">
                    Setelah pembayaran QRIS berhasil dikonfirmasi,
                    nomor antrian akan diberikan secara otomatis.
                </p>

            </div>

        @elseif ($order->status === 'settlement')

            <div class="text-center mt-3">

                <p class="small text-success fw-semibold mb-0">
                    Pembayaran berhasil.
                    Silahkan datang sesuai jadwal antrian.
                </p>

            </div>

        @endif


        <hr>


        <!-- BUTTON -->
        <div class="text-center mt-3">

            <a href="{{ route('order.receipt', $order->order_code) }}"
                target="_blank"
                class="btn btn-warning me-1">

                Download Resi

            </a>

            <a href="{{ route('menu') }}"
                class="btn btn-primary">

                Kembali ke Menu

            </a>

        </div>


        <!-- FOOTER -->
        <div class="text-center mt-4">

            <small class="text-muted">
                Terima kasih telah menggunakan layanan
                Wijaya Barber.
            </small>

        </div>

    </div>

</div>
```

</section>

@endsection
