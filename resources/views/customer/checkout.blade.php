@extends('customer.layouts.master')

@section('content')
<!-- Single Page Header start -->
<div class="container-fluid page-header py-5" style="background: url('https://images.unsplash.com/photo-1524504388940-b1c1722653e1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1400&q=80') center/cover no-repeat;">
    <h1 class="text-center text-white display-6">Checkout Layanan dan Item</h1>
    <ol class="breadcrumb justify-content-center mb-0">
        <li class="breadcrumb-item active text-primary">Checkout layanan grooming dan item favorit Anda</li>
    </ol>
</div>
<!-- Single Page Header End -->

<div class="container-fluid py-5">
    <div class="container py-5">
        <h1 class="mb-5">Detail Pembayaran</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="row g-5">

                {{-- KOLOM KIRI : DATA DIRI + PESANAN --}}
                <div class="col-lg-7">

                    {{-- Data Diri --}}
                    <div class="bg-white rounded shadow-sm p-4 mb-4">
                        <h5 class="mb-4">Data Pemesan</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap <sup class="text-danger">*</sup></label>
                                <input
                                    type="text"
                                    name="fullname"
                                    class="form-control @error('fullname') is-invalid @enderror"
                                    value="{{ old('fullname', auth()->check() ? auth()->user()->fullname : '') }}"
                                    required
                                >
                                @error('fullname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nomor WhatsApp <sup class="text-danger">*</sup></label>
                                <input
                                    type="text"
                                    name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone') }}"
                                    placeholder="08xxxxxxxxxx"
                                    required
                                >
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Catatan Pesanan (Opsional)</label>
                                <textarea
                                    name="notes"
                                    class="form-control"
                                    spellcheck="false"
                                    rows="4"
                                    placeholder="Contoh: potong rambut model pendek, dsb."
                                >{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Detail Pesanan --}}
                    <div class="bg-white rounded shadow-sm p-4">
                        <h5 class="mb-4">Detail Pesanan</h5>
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:90px;">Gambar</th>
                                        <th>Menu</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $subtotal = 0; @endphp
                                    @forelse (session('cart', []) as $item)
                                        @php
                                            $lineTotal = $item['price'] * $item['qty'];
                                            $subtotal += $lineTotal;
                                        @endphp
                                        <tr>
                                            <td>
                                                <img src="{{ asset($item['image']) }}"
                                                     class="rounded-circle"
                                                     style="width:60px;height:60px;object-fit:cover;"
                                                     alt="{{ $item['item_name'] }}">
                                            </td>
                                            <td>{{ $item['item_name'] }}</td>
                                            <td class="text-end">Rp{{ number_format($item['price'], 0, ',', '.') }}</td>
                                            <td class="text-center">{{ $item['qty'] }}</td>
                                            <td class="text-end fw-semibold">Rp{{ number_format($lineTotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-4">Keranjang kosong.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @php
                    $tax = round($subtotal * 0.1);
                    $total = $subtotal + $tax;
                @endphp

                {{-- KOLOM KANAN : RINGKASAN & PEMBAYARAN --}}
                <div class="col-lg-5">
                    <div class="bg-light rounded shadow-sm sticky-top" style="top: 100px;">
                        <div class="p-4">
                            <h4 class="mb-4">Ringkasan Pesanan</h4>

                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Subtotal</span>
                                <span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-0">
                                <span class="text-muted">Pajak (10%)</span>
                                <span>Rp{{ number_format($tax, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="px-4 py-3 border-top border-bottom d-flex justify-content-between align-items-center">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="fw-bold fs-5 text-primary">Rp{{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <div class="p-4">
                            <h6 class="mb-3">Metode Pembayaran <sup class="text-danger">*</sup></h6>

                            <div class="d-flex flex-column gap-2">
                                <label class="payment-option d-flex align-items-center gap-3 p-3 border rounded bg-white" for="qris">
                                    <input type="radio" name="payment_method" class="form-check-input m-0" id="qris" value="qris" required>
                                    <div>
                                        <div class="fw-semibold">QRIS</div>
                                        <div class="text-muted small">Bayar via QRIS / e-wallet</div>
                                    </div>
                                </label>

                                <label class="payment-option d-flex align-items-center gap-3 p-3 border rounded bg-white" for="tunai">
                                    <input type="radio" name="payment_method" class="form-check-input m-0" id="tunai" value="tunai" required>
                                    <div>
                                        <div class="fw-semibold">Tunai</div>
                                        <div class="text-muted small">Bayar langsung di tempat</div>
                                    </div>
                                </label>
                            </div>

                            <button
                                type="button"
                                id="pay-button"
                                class="btn btn-primary w-100 py-3 mt-4 text-uppercase fw-semibold"
                            >
                                Konfirmasi Pesanan
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<style>
    .payment-option {
        cursor: pointer;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .payment-option:has(input:checked) {
        border-color: var(--bs-primary) !important;
        box-shadow: 0 0 0 1px var(--bs-primary);
    }
</style>

<script
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ ENV('MIDTRANS_CLIENT_KEY') }}">
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const payButton = document.getElementById('pay-button');
    const form = document.getElementById('checkout-form');

    payButton.addEventListener('click', function () {
        const selectedPayment = document.querySelector('input[name="payment_method"]:checked');

        if (!selectedPayment) {
            alert('Silakan pilih metode pembayaran.');
            return;
        }

        const paymentMethod = selectedPayment.value;

        // TUNAI
        if (paymentMethod === 'tunai') {
            form.submit();
            return;
        }

        // QRIS
        const formData = new FormData(form);

        fetch('{{ route('checkout.store') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            }
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) throw data;
            return data;
        })
        .then(data => {
            if (data.snap_token) {
                snap.pay(data.snap_token, {
                    onSuccess: function () {
                        window.location.href = '/order/success/' + data.order_id;
                    },
                    onPending: function () {
                        window.location.href = '/order/success/' + data.order_id;
                    },
                    onError: function (result) {
                        console.log(result);
                        alert('Pembayaran gagal.');
                    },
                    onClose: function () {
                        alert('Anda menutup popup pembayaran.');
                    }
                });
            } else {
                alert(data.message || 'Gagal membuat pembayaran.');
            }
        })
        .catch(error => {
            console.log(error);
            alert(error.message || 'Terjadi kesalahan checkout.');
        });
    });
});
</script>
@endsection