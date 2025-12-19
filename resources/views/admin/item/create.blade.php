@extends('admin.layouts.master')

@section('title','Tambah Item')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Tambah Data Item</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Nama Item -->
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Nama Item</label>
                        <input type="text" name="item_name" class="form-control" placeholder="Masukkan Nama Item">
                    </div>
                </div>

                <!-- Harga -->
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Harga</label>
                        <input type="number" name="price" class="form-control" placeholder="Masukkan Harga">
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <!-- Kategori -->
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Kategori Item</label>
                        <select name="category_id" class="form-select">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Upload Gambar -->
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Upload Gambar Item</label>
                        <input type="file" name="image_path" class="form-control">
                    </div>
                </div>

                <!-- Status Aktif -->
                <div class="col-md-12">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                        <label class="form-check-label">
                            Aktifkan Item
                        </label>
                    </div>
                </div>

                <!-- Submit -->
                <div class="col-md-12">
                    <button class="btn btn-primary" type="submit">
                        Simpan Item
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
