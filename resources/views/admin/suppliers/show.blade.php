@extends('layouts.app')

@section('title', 'Detail Supplier - Apotek')

@push('styles')
    <style>
        table.table thead {
            background-color: #f5f5f9;
        }
        table.table thead th {
            color: #566a7f;
            font-weight: 600;
        }
        table.table tbody tr {
            background-color: #fff;
        }
        table.table tbody td {
            color: #697a8d;
        }
    </style>
@endpush

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Supplier /</span> Detail Supplier
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Informasi Supplier</h5>
                    <div>
                        <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn btn-primary me-2">
                            <i class="bx bx-edit me-1"></i> Edit
                        </a>
                        <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-semibold">Nama Supplier</h6>
                            <p>{{ $supplier->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-semibold">Nama Kontak</h6>
                            <p>{{ $supplier->contact_person }}</p>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-semibold">Nomor Telepon</h6>
                            <p>{{ $supplier->phone }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="fw-semibold">Email</h6>
                            <p>{{ $supplier->email }}</p>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-semibold">Alamat</h6>
                        <p>{{ $supplier->address }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-semibold">Tanggal Dibuat</h6>
                        <p>{{ $supplier->created_at->format('d F Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daftar Produk dari Supplier -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Daftar Produk dari Supplier Ini</h5>
                </div>
                <div class="card-body">
                    @if($supplier->products->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Kode Produk</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($supplier->products as $index => $product)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $product->code }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category->name ?? 'Tidak ada kategori' }}</td>
                                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td>{{ $product->stock }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bx bx-info-circle me-1"></i>
                            Belum ada produk yang terkait dengan supplier ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
