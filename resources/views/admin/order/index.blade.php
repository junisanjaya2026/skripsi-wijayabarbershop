@extends('admin.layouts.master')

@section('title','Data Order')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/admin/extensions/simple-datatables/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/compiled/css/table-datatable.css') }}">
@endsection

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6">
                <h3>Data Order</h3>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">

            

                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Order</th>
                            <th>Customer</th>
                            <th>Whatsapp</th>
                            <th>Subtotal</th>
                            <th>Diskon</th>
                            <th>Pajak</th>
                            <th>Grand Total</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Tanggal</th>
                            <th data-sortable="false">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $order->order_code }}</td>
                                <td>{{ $order->user->fullname ?? '-' }}</td>
                                <td>{{ $order->user->phone ?? '-' }}</td>
                                <td>Rp {{ number_format($order->subtotal) }}</td>
                                <td>Rp {{ number_format($order->discount) }}</td>
                                <td>Rp {{ number_format($order->tax) }}</td>
                                <td><strong>Rp {{ number_format($order->grand_total) }}</strong></td>
                                <td>
                                    @if($order->payment_method == 'tunai' && $order->status == 'pending')
                                        <form action="{{ route('orders.update', $order->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')

                                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                <option value="pending" selected>Pending</option>
                                                <option value="settlement">Settlement</option>
                                                <option value="cancel">Cancel</option>
                                            </select>
                                        </form>
                                    @else
                                        @if($order->status == 'settlement')
                                            <span class="badge bg-success">Settlement</span>
                                        @elseif($order->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-danger">{{ ucfirst($order->status) }}</span>
                                        @endif
                                    @endif
                                </td>


                                <td>{{ strtoupper($order->payment_method) }}</td>
                                <td>{{ $order->created_at->format('d-m-Y') }}</td>

                                <td>
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Hapus order ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/admin/extensions/simple-datatables/umd/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/admin/static/js/pages/simple-datatables.js') }}"></script>
@endsection
