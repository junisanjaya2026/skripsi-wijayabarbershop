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
<!-- Services Start-->
<div class="container-fluid fruite py-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="row g-3">
                    <div class="col-lg">
                        <div class="row g-4 justify-content-center">

                            @foreach ($items as $item )
                            <!-- Service Card 1 -->
                            <div class="col-md-6 col-lg-6 col-xl-4">
                                <div class="rounded position-relative fruite-item">
                                    <div class="fruite-img">
                                        <img src="{{ asset('img_item_upload/'.$item->image_path) }}" class="img-fluid w-100 rounded-top" alt="Potong Rambut" onerror="this.onerror=null;this.src='{{ $item->image_path }}';">
                                    </div>
                                    <div class="text-white bg-secondary px-3 py-1 rounded position-absolute
                                    @if ($item->category->category_name == 'Product')
                                    bg-warning
                                    @elseif ($item->category->category_name == 'Service')
                                    bg-info
                                    @else
                                    bg-primary
                                    @endif"
                                    style= "top: 10px; left: 10px;">
                                        {{ $item->category->category_name }}
                                </div>
                                    <div class="p-4 border border-primary border-top-0 rounded-bottom">
                                        <h4>{{ $item->item_name }}</h4>
                                        <p class="text-limited">{{ $item->description }}</p>
                                        <div class="d-flex justify-content-between flex-lg-wrap">
                                            <p class="text-dark fs-5 fw-bold mb-0">{{ 'Rp'. number_format($item->price ,0,',','.') }}</p>
                                            <a href="" onclick="addToCart({{ $item->id }})" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-scissors me-2 text-primary"></i> Pesan Sekarang</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @endforeach

                            <!-- PAGINATION -->
                            <!-- <div class="col-12">
                                <div class="pagination d-flex justify-content-center mt-5">
                                    <a href="#" class="rounded">&laquo;</a>
                                    <a href="#" class="active rounded">1</a>
                                    <a href="#" class="rounded">2</a>
                                    <a href="#" class="rounded">3</a>
                                    <a href="#" class="rounded">&raquo;</a>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- Services End-->
@endsection

@section('script')
<script>
    function addToCart(menuId) {
        fetch("{{ route('add.to.cart') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id: menuId })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>
@endsection