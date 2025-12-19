@extends('admin.layouts.master')

@section('title','Roles')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/admin/extensions/simple-datatables/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/admin/compiled/css/table-datatable.css') }}">
@endsection

@section('content')
         <div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Data Roles</h3>
                  @if (@session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                {{-- <p class="text-subtitle text-muted">A sortable, searchable, paginated table without dependencies thanks to simple-datatables.</p> --}}
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
               <a href="{{ route('roles.create') }}" class="btn btn-primary float-start float-lg-end">
               {{-- <a href="#" class="btn btn-primary float-start float-lg-end"> --}}
                <i class="bi bi-plus" >Tambah Roles</i>
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
                            <th>Nama Roles</th>
                            <th>Deskripsi Roles</th>
                            <th data-sortable="false">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $role->role_name }}</td>
                                <td>{{ $role->description }}</td>
                               <td>
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline">
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