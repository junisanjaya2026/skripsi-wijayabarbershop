@extends('admin.layouts.master')

@section('title','Tambah Role')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Tambah Data Role</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('roles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <!-- Nama Item -->
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label>Nama Role</label>
                        <input type="text" name="role_name" class="form-control" placeholder="Masukkan Nama Role">
                    </div>
                </div>
                <!-- Deskripsi -->
                <div class="col-md-12">
                    <div class="form-group mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
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
