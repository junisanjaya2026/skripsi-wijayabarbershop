@extends('admin.layouts.master')

@section('title','User')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/admin/extensions/simple-datatables/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/compiled/css/table-datatable.css') }}">
@endsection

@section('content')
<div class="page-heading">

    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6">
                <h3>Data User</h3>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>

            <div class="col-12 col-md-6 text-end">
                <a href="#" class="btn btn-primary">
                    <i class="bi bi-plus"></i> Tambah User
                </a>
            </div>
        </div>
    </div>

    <section class="section mt-3">
        <div class="card">
            <div class="card-body">

                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <!-- <th>On Duty</th> -->
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->fullname }}</td>
                            <td>{{ $user->email }}</td>

                            <!-- ROLE (AMAN DARI NULL) -->
                            <td>
                                {{ optional($user->role)->role_name ?? '-' }}
                            </td>

                            <!-- ON DUTY
                            <td>
                                @if (optional($user->role)->role_name === 'capster')
                                    <form action="#" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <div class="form-check form-switch">
                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                name="on_duty"
                                                value="1"
                                                onchange="this.form.submit()"
                                                {{ $user->on_duty ? 'checked' : '' }}
                                            >
                                        </div>
                                    </form>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td> -->

                            <!-- AKSI -->
                            <td>
                                <a href="#" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="#" method="POST" class="d-inline">
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