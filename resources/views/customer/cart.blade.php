@extends('customer.layouts.master')

@section('content')
<!-- Single Page Header start -->
<div class="container-fluid page-header py-5" style="background: url('https://images.unsplash.com/photo-1524504388940-b1c1722653e1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80') center/cover no-repeat;">
    <h1 class="text-center text-white display-6">Layanan Wijaya Barber</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item active text-primary">Pilih layanan grooming favorit Anda</li>
    </ol>
</div>
<!-- Single Page Header End -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                @if (@session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (empty($cart))
                    <h4 class="text-center"> Keranjang anda kosong</h4>
                @else
                <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                  <tr>
                    <th scope="col">Gambar</th>
                    <th scope="col">Menu</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Total</th>
                    <th scope="col">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                    @php
                        $subtotal = 0;
                    @endphp

                    @foreach ($cart as $id => $item)
                        @php
                            $lineTotal = $item['price'] * $item['qty'];
                            $subtotal += $lineTotal;
                        @endphp
                        <tr data-id="{{ $id }}">
                            <td style="width:100px;">
                                @if(!empty($item['image']))
                                    <img src="{{ asset($item['image']) }}" class="img-fluid rounded-circle" style="width:80px;height:80px;object-fit:cover;" alt="{{ $item['item_name'] }}">
                                @else
                                    <img src="https://via.placeholder.com/80" class="img-fluid rounded-circle" style="width:80px;height:80px;object-fit:cover;" alt="{{ $item['item_name'] }}">
                                @endif
                            </td>
                            <td>
                                <p class="mb-0">{{ $item['item_name'] }}</p>
                            </td>
                            <td>
                                <p class="mb-0">Rp{{ number_format($item['price'], 0, ',', '.') }},00</p>
                            </td>
                            <td>
                                <div class="input-group quantity" style="width:120px;">
                                    <button class="btn btn-sm btn-minus rounded-circle bg-light border decrease-qty" type="button" onclick="updateQuantity('{{ $item['id'] }}',-1)"><i class="fa fa-minus"></i></button>
                                    <input id="qty-{{ $item['id'] }}" type="text" class="form-control form-control-sm text-center border-0 qty-input" value="{{ $item['qty'] }}" style="max-width:60px;">
                                    <button class="btn btn-sm btn-plus rounded-circle bg-light border increase-qty" type="button" onclick="updateQuantity('{{ $item['id'] }}', 1)"><i class="fa fa-plus"></i></button>
                                </div>
                            </td>
                            <td>
                                <p class="mb-0 line-total">Rp{{ number_format($lineTotal, 0, ',', '.') }},00</p>
                            </td>
                            <td>
                                <button class="btn btn-md rounded-circle bg-light border remove-item" title="Hapus item" onclick="if(confirm('apakah anda yakin ingin mengahpus item ini')) {removeItemFromCart('{{ $item['id'] }}')}">
                                    <i class="fa fa-times text-danger"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        @php
            $tax = round($subtotal * 0.10);
            $total = $subtotal + $tax;
        @endphp


<div class="d-flex justify-content-end">
    <a class="btn btn-danger" href="{{ route('clear.cart') }}" onclick="return confirm('Apakah yakin ingin mengosongkan kernjang')">Kosongkan keranjang</a>
</div>
                <div class="row g-4 justify-content-end mt-1">
                    <div class="col-8"></div>
                    <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                        <div class="bg-light rounded">
                                 <div class="p-4">
                        <h2 class="display-6 mb-4">Total <span class="fw-normal">Pesanan</span></h2>
                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="mb-0 me-4">Subtotal</h5>
                            <p class="mb-0">Rp{{ number_format($subtotal,0,',','.') }},00</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class="mb-0 me-4">Pajak (10%)</p>
                            <div class="">
                                <p class="mb-0">Rp{{ number_format($tax,0,',','.') }},00</p>
                            </div>
                        </div>
                    </div>
                    <div class="py-4 mb-4 border-top d-flex justify-content-between">
                        <h4 class="mb-0 ps-4 me-4">Total</h4>
                        <h5 class="mb-0 pe-4">Rp{{ number_format($total,0,',','.') }},00</h5>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <div class="mb-0 mb-3">
                        <a href="{{ route('checkout') }}" class="btn border-secondary py-3 text-primary text-uppercase mb-4">Lanjut ke Pembayaran</a>
                    </div>  
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
@endsection 

@section('script')
<script>
function updateQuantity(itemId, change) {

    var qtyInput = document.getElementById('qty-' + itemId);
    var currentQty = parseInt(qtyInput.value);
    var newQty = currentQty + change;

    if (newQty <= 0) {
        if (confirm('Apakah anda yakin ingin menghapus item ini dari keranjang?')) {
            removeItemFromCart(itemId);
        }
        return;
    }

    fetch("{{ route('update.cart') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            id: itemId,
            qty: newQty
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            qtyInput.value = newQty;
            location.reload(); // sementara reload dulu
        } else {
            alert(data.message || 'Gagal update');
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memperbarui jumlah.');
    });

}

function removeItemFromCart(itemId) {
    fetch("{{ route('remove.from.cart') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            id: itemId,
            qty: 0
        })
    })
     .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // sementara reload dulu
        } else {
            alert(data.message || 'Gagal update');
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memperbarui jumlah.');
    });
}

    </script>

    @endsection