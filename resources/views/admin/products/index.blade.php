@extends('layouts.app')

@section('title', 'Manajemen Obat')

@section('content')
    <div>
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                Manajemen Obat
            </h1>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary shadow-sm">
                <i class="bx bx-plus"></i> Tambah Obat Baru
            </a>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bx bx-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bx bx-error-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter and Search Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bx bx-filter-alt"></i> Filter & Pencarian
                </h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.products.index') }}" class="row">
                    <div class="col-md-4 mb-3">
                        <label for="search" class="form-label">Cari Obat</label>
                        <input type="text" class="form-control" id="search" name="search"
                            value="{{ request('search') }}" placeholder="Nama obat...">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="category" class="form-label">Kategori</label>
                        <select class="form-control" id="category" name="category">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia
                            </option>
                            <option value="habis" {{ request('status') == 'habis' ? 'selected' : '' }}>Habis</option>
                            <option value="kadaluarsa" {{ request('status') == 'kadaluarsa' ? 'selected' : '' }}>Kadaluarsa
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label>&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
                @if (request()->hasAny(['search', 'category', 'status']))
                    <div class="mt-2">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bx bx-reset"></i> Reset Filter
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Products Table Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bx bx-list-ul"></i> Daftar Obat
                </h6>
                <span class="badge bg-info">Total: {{ $products->total() }} obat</span>
            </div>
            <div class="card-body">
                @if ($products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable">
                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="10%">Gambar</th>
                                    <th width="20%">Nama Obat</th>
                                    <th width="12%">Kategori</th>
                                    <th width="15%">Supplier</th>
                                    <th width="8%">Stok</th>
                                    <th width="10%">Harga Jual</th>
                                    <th width="8%">Status</th>
                                    <th width="12%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $index => $product)
                                    <tr>
                                        <td class="text-center">{{ $products->firstItem() + $index }}</td>
                                        <td class="text-center">
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                    alt="{{ $product->name }}"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center"
                                                    style="width: 50px; height: 50px; border-radius: 4px;">
                                                    <i class="bx bx-capsule text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $product->name }}</strong>
                                            @if ($product->description)
                                                <br><small
                                                    class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                            @endif
                                            <br><small class="text-info">Terjual: {{ $product->total_sold ?? 0 }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $product->category->name ?? 'Tidak ada kategori' }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $product->supplier->name ?? 'Tidak ada supplier' }}
                                            @if ($product->supplier)
                                                <br><small class="text-muted">{{ $product->supplier->phone ?? '' }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span
                                                class="badge {{ $product->stock > 10 ? 'bg-success' : ($product->stock > 0 ? 'bg-warning' : 'bg-danger') }}">
                                                {{ $product->stock }}
                                            </span>
                                            @if ($product->stock <= 10 && $product->stock > 0)
                                                <br><small class="text-warning">Stok Rendah</small>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                                        </td>
                                        <td class="text-center">
                                            @if ($product->status == 'tersedia')
                                                <span class="badge bg-success">
                                                    Tersedia
                                                </span>
                                            @elseif($product->status == 'habis')
                                                <span class="badge bg-danger">
                                                    Habis
                                                </span>
                                            @elseif($product->status == 'kadaluarsa')
                                                <span class="badge bg-dark">
                                                    Kadaluarsa
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <div class="d-flex gap-1 flex-wrap">
                                                    <a href="{{ route('admin.products.show', $product->id) }}"
                                                        class="btn btn-primary btn-sm d-flex align-items-center justify-content-center"
                                                        style="width: 32px; height: 32px;" title="Lihat Detail">
                                                        <i class="bx bx-show"></i>
                                                    </a>

                                                    <a href="{{ route('admin.products.edit', $product->id) }}"
                                                        class="btn btn-warning btn-sm d-flex align-items-center justify-content-center"
                                                        style="width: 32px; height: 32px;" title="Edit">
                                                        <i class="bx bx-edit"></i>
                                                    </a>

                                                    <form action="{{ route('admin.products.destroy', $product->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus obat {{ $product->name }}?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-danger btn-sm d-flex align-items-center justify-content-center"
                                                            style="width: 32px; height: 32px;" title="Hapus">
                                                            <i class="bx bx-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of
                            {{ $products->total() }} results
                        </div>
                        <div class="pagination">
                            @if ($products->onFirstPage())
                                <a href="#" class="page-link disabled" aria-disabled="true">
                                    « Previous
                                </a>
                            @else
                                <a href="{{ $products->previousPageUrl() }}" class="page-link">
                                    « Previous
                                </a>
                            @endif

                            @for ($i = 1; $i <= $products->lastPage(); $i++)
                                <a href="{{ $products->url($i) }}"
                                    class="page-link {{ $products->currentPage() == $i ? 'active' : '' }}">
                                    {{ $i }}
                                </a>
                            @endfor

                            @if ($products->hasMorePages())
                                <a href="{{ $products->nextPageUrl() }}" class="page-link">
                                    Next »
                                </a>
                            @else
                                <a href="#" class="page-link disabled" aria-disabled="true">
                                    Next »
                                </a>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <i class="bx bx-capsule bx-lg text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada data obat</h5>
                        <p class="text-muted">
                            @if (request()->hasAny(['search', 'category', 'status']))
                                Tidak ditemukan obat dengan kriteria pencarian yang Anda masukkan.
                                <br><a href="{{ route('admin.products.index') }}"
                                    class="btn btn-secondary btn-sm mt-2">
                                    <i class="fas fa-times"></i> Reset Filter
                                </a>
                            @else
                                Belum ada obat yang terdaftar dalam sistem.
                                <br><a href="{{ route('admin.products.create') }}"
                                    class="btn btn-primary btn-sm mt-2">
                                    <i class="fas fa-plus"></i> Tambah Obat Pertama
                                </a>
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table th {
            border-top: none;
            font-weight: 600;
            background-color: #f8f9fc;
        }

        .table-hover tbody tr:hover {
            background-color: #f5f5f5;
        }

        .btn-group .btn {
            border-radius: 0.25rem;
            margin-right: 2px;
        }

        .badge {
            font-size: 0.75rem;
        }

        .img-thumbnail {
            border: 1px solid #e3e6f0;
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }

        .empty-state {
            padding: 3rem 0;
        }

        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
        }

        /* Pagination styling */
        .pagination {
            display: flex;
            padding-left: 0;
            list-style: none;
            margin-bottom: 0;
        }

        .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #4e73df;
            background-color: #fff;
            border: 1px solid #dee2e6;
        }

        .page-link:hover {
            z-index: 2;
            color: #2e59d9;
            text-decoration: none;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        .page-link.active {
            z-index: 3;
            color: #fff;
            background-color: #4e73df;
            border-color: #4e73df;
        }

        .page-link.disabled {
            color: #858796;
            pointer-events: none;
            cursor: auto;
            background-color: #fff;
            border-color: #dee2e6;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Document ready function
        $(document).ready(function() {
            // Auto hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);

            // Initialize tooltips
            $('[title]').tooltip();

            // Initialize Bootstrap dropdowns manually
            $('.dropdown-toggle').dropdown();
        });
    </script>
@endpush
