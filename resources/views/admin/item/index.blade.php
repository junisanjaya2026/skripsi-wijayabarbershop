@extends('admin.layouts.master')

@section('title','Categories')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/admin/extensions/simple-datatables/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/compiled/css/table-datatable.css') }}">
@endsection

@section('content')
         <div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Item</h3>
                  @if (@session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                {{-- <p class="text-subtitle text-muted">A sortable, searchable, paginated table without dependencies thanks to simple-datatables.</p> --}}
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
               <a href="{{ route('items.create') }}" class="btn btn-primary float-start float-lg-end">
               {{-- <a href="#" class="btn btn-primary float-start float-lg-end"> --}}
                <i class="bi bi-plus" >Tambah Item</i>
               </a>
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
                            <th data-sortable="false">Gambar</th>
                            <th>Nama Item</th>
                            <th>Deskripsi</th>
                            <th>Harga</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th data-sortable="false">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><img 
                                            src="{{ $item['image_path'] 
                                                ? asset('storage/' . $item['image_path']) 
                                                : asset($item['image_path']) }}" 
                                            class="img-fluid rounded-circle"
                                            style="width:80px;height:80px;object-fit:cover;"
                                        ></td>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->price }}</td>
                                <td>{{ $item->category->category_name }}</td>
                          <td>
                                @if($item->is_active)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                @endif
                            </td>
                               <td>
                                <a href="{{ route('items.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus data ini?')">
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