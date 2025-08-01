@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="flex-grow-1">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Manajemen Pengguna /</span> Semua Pengguna
    </h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Pengguna</h5>
            <div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus me-1"></i> Tambah Pengguna Baru
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Pesan Flash -->
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
            
            <!-- Filter -->
            <div class="mb-4">
                <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="role" class="form-label">Filter berdasarkan Peran</label>
                        <select id="role" name="role" class="form-select">
                            <option value="">Semua Peran</option>
                            <option value="apoteker" {{ request('role') == 'apoteker' ? 'selected' : '' }}>Apoteker</option>
                            <option value="pelanggan" {{ request('role') == 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="search" class="form-label">Cari</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Cari berdasarkan nama, email, atau telepon" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>

            <!-- Tautan Cepat -->
            <div class="mb-4">
                <div class="btn-group" role="group" aria-label="Filter tipe pengguna">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">Semua Pengguna</a>
                    <a href="{{ route('admin.users.pharmacists') }}" class="btn btn-outline-info">Apoteker</a>
                    <a href="{{ route('admin.users.customers') }}" class="btn btn-outline-success">Pelanggan</a>
                </div>
            </div>

            <!-- Tabel Pengguna -->
            <div class="table-responsive text-nowrap">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Peran</th>
                            <th>Telepon</th>
                            <th>Terdaftar</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @forelse ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $user->name }}</strong>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->isApoteker())
                                    <span class="badge bg-label-info">Apoteker</span>
                                @elseif($user->isPelanggan())
                                    <span class="badge bg-label-success">Pelanggan</span>
                                @else
                                    <span class="badge bg-label-secondary">Tidak Diketahui</span>
                                @endif
                            </td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.users.show', $user->id) }}">
                                            <i class="bx bx-show-alt me-1"></i> Lihat
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.users.edit', $user->id) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="dropdown-item" type="submit" 
                                                    onclick="return confirm('Anda yakin ingin menghapus pengguna ini?')">
                                                <i class="bx bx-trash me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada pengguna yang ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-3">
                {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
