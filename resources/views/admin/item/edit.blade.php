@extends('admin.layouts.master')

@section('title','Edit Item')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Edit Data Item</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Nama Item -->
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Nama Item</label>
                        <input 
                            type="text" 
                            name="item_name" 
                            class="form-control" 
                            value="{{ old('item_name', $item->item_name) }}"
                        >
                    </div>
                </div>

                <!-- Harga -->
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Harga</label>
                        <input 
                            type="number" 
                            name="price" 
                            class="form-control" 
                            value="{{ old('price', $item->price) }}"
                        >
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $item->description) }}</textarea>
                    </div>
                </div>

                <!-- Kategori -->
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Kategori Item</label>
                        <select name="category_id" class="form-select">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Upload Gambar -->
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Upload Gambar Baru (Opsional)</label>
                        <input type="file" name="image_path" class="form-control">
                    </div>

                    @if($item->image_path)
                        <div class="mt-2">
                            <small>Gambar saat ini:</small><br>
                            <img 
                                src="{{ asset('storage/' . $item->image_path) }}" 
                                class="img-thumbnail"
                                style="width:100px;height:100px;object-fit:cover;"
                            >
                        </div>
                    @endif
                </div>

                <!-- Status Aktif -->
                <div class="col-md-12">
                    <div class="form-check form-switch mb-3">
                        <input 
                            class="form-check-input" 
                            type="checkbox" 
                            name="is_active" 
                            value="1"
                            {{ old('is_active', $item->is_active) ? 'checked' : '' }}
                        >
                        <label class="form-check-label">
                            Aktifkan Item
                        </label>
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
