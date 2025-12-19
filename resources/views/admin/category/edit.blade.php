use App\Models\Category;
@extends('admin.layouts.master')

@section('title','Edit Item')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Edit Data Item</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Nama Item -->
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Nama Kategori</label>
                        <input 
                            type="text" 
                            name="category_name" 
                            class="form-control" 
                            value="{{ old('category_name', $category->category_name) }}"
                        >
                    </div>
                </div>
                <!-- Deskripsi -->
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description) }}</textarea>
                    </div>
                </div>

                <!-- Submit -->
                <div class="col-md-12">
                    <button class="btn btn-primary" type="submit">
                        Update Item
                    </button>
                    {{-- <a href="{{ route('items.index') }}" class="btn btn-secondary">
                        Kembali
                    </a> --}}
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
