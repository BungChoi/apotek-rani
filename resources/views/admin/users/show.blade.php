@extends('layouts.app')

@section('title', 'Detail Pengguna')

@section('content')
<div class="flex-grow-1">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Manajemen Pengguna /</span> Detail Pengguna
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <h5 class="card-header d-flex justify-content-between align-items-center">
                    <span>Informasi Pengguna</span>
                    <div>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                            <i class="bx bx-edit me-1"></i> Ubah
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bx bx-arrow-back me-1"></i> Kembali ke Daftar
                        </a>
                    </div>
                </h5>
                
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
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3 row">
                                <label class="col-md-3 col-form-label fw-bold">Nama Lengkap</label>
                                <div class="col-md-9">
                                    <p class="form-control-static">{{ $user->name }}</p>
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-md-3 col-form-label fw-bold">Email</label>
                                <div class="col-md-9">
                                    <p class="form-control-static">{{ $user->email }}</p>
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-md-3 col-form-label fw-bold">Peran</label>
                                <div class="col-md-9">
                                    @if($user->isApoteker())
                                        <span class="badge bg-label-info">Apoteker</span>
                                    @elseif($user->isPelanggan())
                                        <span class="badge bg-label-success">Pelanggan</span>
                                    @else
                                        <span class="badge bg-label-secondary">Tidak dikenal</span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-md-3 col-form-label fw-bold">Nomor Telepon</label>
                                <div class="col-md-9">
                                    <p class="form-control-static">{{ $user->phone ?? 'Tidak tersedia' }}</p>
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-md-3 col-form-label fw-bold">Alamat</label>
                                <div class="col-md-9">
                                    <p class="form-control-static">{{ $user->address ?? 'Tidak tersedia' }}</p>
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-md-3 col-form-label fw-bold">Terdaftar Pada</label>
                                <div class="col-md-9">
                                    <p class="form-control-static">{{ $user->created_at->format('d F Y, h:i A') }}</p>
                                </div>
                            </div>
                            
                            <div class="mb-3 row">
                                <label class="col-md-3 col-form-label fw-bold">Terakhir Diperbarui</label>
                                <div class="col-md-9">
                                    <p class="form-control-static">{{ $user->updated_at->format('d F Y, h:i A') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Aktivitas Pengguna</h5>
                                    
                                    @if($user->isPelanggan())
                                        <div class="mb-3">
                                            <strong>Jumlah Pembelian:</strong>
                                            <span class="badge bg-primary">{{ $user->salesAsCustomer->count() }}</span>
                                        </div>
                                        
                                        @if($user->salesAsCustomer->count() > 0)
                                            <div class="mb-3">
                                                <strong>Pembelian Terakhir:</strong>
                                                <p>{{ $user->salesAsCustomer->sortByDesc('created_at')->first()->created_at->format('d M Y') }}</p>
                                            </div>
                                        @endif
                                    @endif
                                    
                                    @if($user->isApoteker())
                                        <div class="mb-3">
                                            <strong>Penjualan yang Ditangani:</strong>
                                            <span class="badge bg-info">{{ $user->salesAsApoteker->count() }}</span>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <strong>Pembelian yang Dibuat:</strong>
                                            <span class="badge bg-success">{{ $user->createdPurchases->count() }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 text-end">
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini? Tindakan ini tidak bisa dibatalkan.')">
                                <i class="bx bx-trash me-1"></i> Hapus Pengguna
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
