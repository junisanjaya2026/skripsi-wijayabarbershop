use App\Models\Category;
@extends('admin.layouts.master')

@section('title','Edit Role')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Edit Data Role</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('roles.update', $role->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- Nama Item -->
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Nama Role</label>
                        <input 
                            type="text" 
                            name="role_name" 
                            class="form-control" 
                            value="{{ old('role_name', $role->role_name) }}"
                        >
                    </div>
                </div>
                <!-- Deskripsi -->
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $role->description) }}</textarea>
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
