@extends('layouts.app')

@section('title', 'Manajemen Apoteker')

@section('content')
<div class="flex-grow-1">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Manajemen Pengguna /</span> Apoteker
    </h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Apoteker</h5>
            <div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Apoteker Baru
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible mb-4" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            <!-- Filters -->
            <div class="mb-4">
                <form action="{{ route('admin.users.pharmacists') }}" method="GET" class="row g-3">
                    <div class="col-md-8">
                        <label for="search" class="form-label">Cari</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Cari berdasarkan nama, email, atau telepon" value="{{ $search }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Cari</button>
                        <a href="{{ route('admin.users.pharmacists') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>

            <!-- Quick links -->
            <div class="mb-4">
                <div class="btn-group" role="group" aria-label="User type filters">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">Semua Pengguna</a>
                    <a href="{{ route('admin.users.pharmacists') }}" class="btn btn-outline-info active">Apoteker</a>
                    <a href="{{ route('admin.users.customers') }}" class="btn btn-outline-success">Pelanggan</a>
                </div>
            </div>

            <!-- Pharmacists Table -->
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Terdaftar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($pharmacists as $pharmacist)
                        <tr>
                            <td>{{ $pharmacist->id }}</td>
                            <td>
                                <strong>{{ $pharmacist->name }}</strong>
                            </td>
                            <td>{{ $pharmacist->email }}</td>
                            <td>{{ $pharmacist->phone ?? '-' }}</td>
                            <td>{{ $pharmacist->created_at->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-label-success">Aktif</span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.users.show', $pharmacist->id) }}">
                                            <i class="bx bx-show-alt me-1"></i> Lihat
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.users.edit', $pharmacist->id) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $pharmacist->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item" type="submit" 
                                                    onclick="return confirm('Anda yakin ingin menghapus apoteker ini?')">
                                                <i class="bx bx-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada apoteker yang ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-3">
                {{ $pharmacists->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
