@extends('layouts.app')

@section('title', 'Detail Kategori - Apotek')

@section('content')
<div class="flex-grow-1">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Manajemen Produk /</span> Detail Kategori
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Informasi Kategori</h5>
                    <div>
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-primary me-2">
                            <i class="bx bx-edit-alt me-1"></i> Edit
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="fw-semibold">Nama Kategori</h6>
                                <p>
                                    @if($category->icon)
                                        <i class="bx {{ $category->icon }} me-1"></i>
                                    @endif
                                    {{ $category->name }}
                                </p>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="fw-semibold">Slug</h6>
                                <p>{{ $category->slug }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="fw-semibold">Status</h6>
                                <p>
                                    @if($category->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-danger">Tidak Aktif</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="fw-semibold">Deskripsi</h6>
                                <p>{{ $category->description ?? 'Tidak ada deskripsi' }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="fw-semibold">Jumlah Produk</h6>
                                <p>{{ $category->products->count() }} produk</p>
                            </div>
                            
                            <div class="mb-3">
                                <h6 class="fw-semibold">Tanggal Dibuat</h6>
                                <p>{{ $category->created_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="fw-semibold mb-3">Produk dalam Kategori Ini</h6>
                            
                            @if($category->products->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Produk</th>
                                                <th>Stok</th>
                                                <th>Harga</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($category->products as $index => $product)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $product->name }}</td>
                                                    <td>{{ $product->stock }}</td>
                                                    <td>{{ number_format($product->price, 0, ',', '.') }}</td>
                                                    <td>
                                                        @if($product->status == 'tersedia')
                                                            <span class="badge bg-success">Tersedia</span>
                                                        @elseif($product->status == 'habis')
                                                            <span class="badge bg-danger">Habis</span>
                                                        @elseif($product->status == 'kadaluarsa')
                                                            <span class="badge bg-dark">Kadaluarsa</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-primary">
                                                            <i class="bx bx-show"></i> Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="bx bx-info-circle"></i> Tidak ada produk dalam kategori ini.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
