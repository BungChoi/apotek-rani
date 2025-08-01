@extends('layouts.app')

@section('title', 'Detail Obat')

@section('content')
<div class="flex-grow-1">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Manajemen Obat /</span> Detail Obat
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Informasi Obat</h5>
                    <div>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary me-2">
                            <i class="bx bx-edit-alt me-1"></i> Edit
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="fw-semibold">Nama Obat</h6>
                                <p>{{ $product->name }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="fw-semibold">Kategori</h6>
                                <p>{{ $product->category->name }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="fw-semibold">Supplier</h6>
                                <p>{{ $product->supplier->name }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="fw-semibold">Stok</h6>
                                <p>{{ $product->stock }} unit</p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="fw-semibold">Harga Jual</h6>
                                <p>{{ $product->formatted_price }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="fw-semibold">Tanggal Kadaluarsa</h6>
                                <p>{{ $product->expired_date->format('d F Y') }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="fw-semibold">Status</h6>
                                <p>
                                    @if($product->status == 'tersedia')
                                        <span class="badge bg-success">Tersedia</span>
                                    @elseif($product->status == 'habis')
                                        <span class="badge bg-danger">Habis</span>
                                    @elseif($product->status == 'kadaluarsa')
                                        <span class="badge bg-warning">Kadaluarsa</span>
                                    @endif
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="fw-semibold">Total Terjual</h6>
                                <p>{{ $product->total_sold }} unit</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <h6 class="fw-semibold">Deskripsi</h6>
                                <p>{{ $product->description ?? 'Tidak ada deskripsi' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    @if($product->image)
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <h6 class="fw-semibold">Gambar</h6>
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-height: 300px;">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
