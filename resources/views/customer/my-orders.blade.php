@extends('customer.layouts.master')

@section('content')

<div class="container py-5">

    <h2 class="mb-4">History Pesanan Saya</h2>

    @forelse($orders as $order)

        <div class="card mb-4 shadow-sm border-0">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>
                        <h5 class="mb-1">
                            Order: {{ $order->order_code }}
                        </h5>
                        <p class="mb-1"> Status: 
                            <span class="badge {{ $order->status === 'pending' ? 'bg-danger' : 'bg-success' }}">
                                 {{ strtoupper($order->status) }} </span> 
                        </p>

                        <p class="mb-1">
                            Metode:
                            {{ strtoupper($order->payment_method) }}
                        </p>

                        <p class="mb-1">
                            Total:
                            Rp{{ number_format($order->grand_total,0,',','.') }}
                        </p>

                        <p class="mb-1">
                            Nomor Antrian:
                            A-{{ str_pad($order->queue_number, 3, '0', STR_PAD_LEFT) }}
                        </p>

                        <p class="mb-0">
                            Estimasi:
                            {{ \Carbon\Carbon::parse($order->queue_time)->translatedFormat('d F Y H:i') }}
                        </p>
                    </div>

                    <div class="text-end">
                        <small>
                            {{ $order->created_at->translatedFormat('d M Y H:i') }}
                        </small>
                    </div>


                       <div class="mt-2">
                            <a href="{{ route('order.receipt', $order->order_code) }}"
                               class="btn btn-sm btn-outline-primary"
                               target="_blank">
                                <i class="bi bi-download"></i> Download Resi
                            </a>
                        </div>  

                </div>

                <hr>

                <h6>Detail Item</h6>

                @foreach($order->orderItems as $detail)

                    <div class="d-flex justify-content-between border-bottom py-2">

                        <div>
                            {{ $detail->item->item_name ?? '-' }}
                        </div>

                        <div>
                            {{ $detail->quantity }} x
                            Rp{{ number_format($detail->price,0,',','.') }}
                        </div>

                    </div>

                @endforeach

            </div>

        </div>

    @empty

        <div class="alert alert-info">
            Belum ada pesanan.
        </div>

    @endforelse

</div>

@endsection