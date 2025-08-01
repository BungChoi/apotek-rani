@extends('layouts.public')

@section('title', 'Apotek Online - Catalog Obat')

@section('styles')
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 5rem 0;
    position: relative;
    overflow: hidden;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.1"><polygon points="1000,100 1000,0 0,100"/></svg>');
    background-size: cover;
}

.hero-content {
    position: relative;
    z-index: 2;
}

.product-card {
    transition: all 0.4s ease;
    border: none;
    border-radius: 20px;
    overflow: hidden;
    background: white;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.product-image {
    height: 180px;
    object-fit: contain;
    width: calc(100% - 20px);
    margin: 10px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 12px;
    transition: transform 0.4s ease;
    flex-shrink: 0;
}

.product-card:hover .product-image {
    transform: scale(1.02);
}

.product-placeholder {
    height: 180px;
    margin: 10px;
    padding: 15px;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.price {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 700;
    font-size: 1.1rem;
}

.stats-info {
    font-size: 0.75rem;
    color: #28a745;
    font-weight: 500;
}

.price-row {
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.buttons-row {
    height: 2.5rem;
    display: flex;
    gap: 0.5rem;
    justify-content: space-between;
}

.buttons-row .btn {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.buttons-row .dropdown {
    height: 100%;
}

.buttons-row .dropdown button {
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-sm {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    border-radius: 20px;
    font-weight: 500;
}

.category-badge {
    position: absolute;
    top: 15px;
    left: 15px;
    background: rgba(108, 117, 125, 0.95);
    color: white;
    padding: 4px 8px;
    border-radius: 15px;
    font-size: 0.7rem;
    font-weight: 500;
    backdrop-filter: blur(10px);
    z-index: 2;
}

.stock-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(40, 167, 69, 0.95);
    color: white;
    padding: 4px 8px;
    border-radius: 15px;
    font-size: 0.7rem;
    font-weight: 500;
    backdrop-filter: blur(10px);
    z-index: 2;
}

.search-section {
    background: white;
    padding: 3rem 0;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.search-input {
    border-radius: 50px;
    padding: 15px 25px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.9);
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border: 2px solid #667eea;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.search-input::placeholder {
    color: rgba(102, 126, 234, 0.6);
    font-weight: 400;
}

.search-btn {
    border-radius: 50px;
    padding: 15px 25px;
    background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%);
    border: none;
    color: white;
    font-weight: 600;
}

.filter-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.product-title {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 8px;
    font-size: 0.95rem;
    height: 2.4rem;
    line-height: 1.2;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.product-description {
    color: #6c757d;
    font-size: 0.8rem;
    line-height: 1.3;
    height: 2.6rem;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    margin-bottom: 8px;
}

.supplier-info {
    background: #f8f9fa;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    color: #6c757d;
    display: inline-block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
    height: 1.5rem;
    line-height: 1.3;
}

.card-body {
    padding: 1rem;
    display: flex;
    flex-direction: column;
    flex: 1;
    height: 260px;
    overflow: hidden;
}

.product-content {
    height: 180px;
    display: flex;
    flex-direction: column;
    margin-bottom: 1rem;
}

.product-footer {
    height: 80px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.products-row {
    display: grid;
    gap: 20px;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
}

.product-card {
    width: 100%;
    height: 460px;
    display: flex;
    flex-direction: column;
    position: relative;
}

/* Mobile */
@media (max-width: 575px) {
    .products-row {
        grid-template-columns: 1fr;
    }
}

/* Mobile Large */
@media (min-width: 576px) and (max-width: 767px) {
    .products-row {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Tablet */
@media (min-width: 768px) and (max-width: 991px) {
    .products-row {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Desktop Small */
@media (min-width: 992px) and (max-width: 1199px) {
    .products-row {
        grid-template-columns: repeat(3, 1fr);
    }
}

/* Desktop Large */
@media (min-width: 1200px) and (max-width: 1399px) {
    .products-row {
        grid-template-columns: repeat(4, 1fr);
    }
}

/* Desktop XL */
@media (min-width: 1400px) {
    .products-row {
        grid-template-columns: repeat(5, 1fr);
    }
}
@endsection

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <div class="container text-center">
            <h1 class="display-4 mb-4 fw-bold mt-12 text-white">Selamat Datang di Apotek</h1>
            <p class="lead mb-5 fs-5">Temukan obat-obatan berkualitas dengan harga terjangkau dan pelayanan
                terpercaya</p>
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <form method="GET" action="{{ route('home') }}">
                        <div class="input-group input-group-lg">
                            <input type="text" class="form-control search-input" name="search"
                                value="{{ request('search') }}" placeholder="Cari obat atau produk kesehatan...">
                            <button class="btn search-btn" type="submit">
                                <i class="bx bx-search"></i>Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search & Filter Section -->
<section class="search-section">
    <div class="container">
        <div class="filter-card">
            <form method="GET" action="{{ route('home') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Kategori</label>
                    <select class="form-select" name="category_id">
                        <option value="">Semua Kategori</option>
                        @if(isset($categories) && $categories->count() > 0)
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>Tidak ada kategori tersedia</option>
                        @endif
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Supplier</label>
                    <select class="form-select" name="supplier_id">
                        <option value="">Semua Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}"
                                {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Urutkan</label>
                    <select class="form-select" name="sort">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga
                            Terendah</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga
                            Tertinggi</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler
                        </option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru
                        </option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-filter"></i>Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Products Section -->
<section class="py-5">
    <div class="container">
        <!-- Results Info -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="section-title">Katalog Obat</h3>
            <div class="text-muted">
                <i class="bx bx-box"></i>
                Menampilkan {{ $products->firstItem() }}-{{ $products->lastItem() }} dari
                {{ $products->total() }} produk
            </div>
        </div>

        @if ($products->count() > 0)
            <!-- Products Grid -->
            <div class="products-row">
                @foreach ($products as $product)
                    <div class="card product-card">
                        <div class="position-relative" style="height: 200px; overflow: visible;">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="product-image"
                                    alt="{{ $product->name }}">
                            @else
                                <div class="product-placeholder">
                                    <i class="bx bx-plus-medical fa-3x text-primary"></i>
                                </div>
                            @endif

                            <!-- Category Badge -->
                            <div class="category-badge">
                                {{ $product->category->name }}
                            </div>
                            
                            <!-- Stock Badge -->
                            <div class="stock-badge">
                                stok: {{ $product->stock }}
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="product-content">
                                <h6 class="product-title">{{ $product->name }}</h6>
                                <p class="product-description">
                                    {{ Str::limit($product->description, 80) }}
                                </p>

                                <div class="supplier-info">
                                    {{ Str::limit($product->supplier->name, 20) }}
                                </div>
                            </div>

                            <div class="product-footer">
                                <div class="price-row">
                                    <div class="price">{{ $product->formatted_price }}</div>
                                    <div class="stats-info">
                                        Terjual: {{ $product->total_sold }}
                                    </div>
                                </div>

                                <div class="buttons-row">
                                    <a href="{{ route('public.products.show', $product->id) }}"
                                        class="btn btn-outline-primary btn-sm w-50">
                                        Detail
                                    </a>
                                    @auth
                                        <div class="dropdown d-inline-block w-50">
                                            <button class="btn btn-primary btn-sm dropdown-toggle w-100" 
                                                type="button" data-bs-toggle="dropdown">
                                                Beli
                                            </button>
                                            <ul class="dropdown-menu p-2" style="min-width: 180px;">
                                                <li>
                                                    <form action="{{ route('public.cart.add', $product->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="quantity" value="1">
                                                        <button type="submit" class="dropdown-item py-1 px-2">
                                                            Tambah ke Keranjang
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item py-1 px-2" href="#" onclick="buyNow({{ $product->id }})">
                                                        Beli Sekarang
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm w-100">
                                            <i class="bx bx-log-in me-1"></i>Login
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Simple Bootstrap Pagination -->
            @if ($products->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($products->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">Previous</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ $products->appends(request()->query())->previousPageUrl() }}">Previous</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                @if ($page == $products->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $products->appends(request()->query())->url($page) }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($products->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link"
                                        href="{{ $products->appends(request()->query())->nextPageUrl() }}">Next</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">Next</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>

                <!-- Page Info -->
                <div class="text-center mt-3 mb-4">
                    <small class="text-muted">
                        Halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }} •
                        {{ $products->total() }} produk total
                    </small>
                </div>
            @endif
        @else
            <!-- No Products Found -->
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bx bx-search fa-5x text-muted mb-3"></i>
                    <h4 class="fw-bold">Produk tidak ditemukan</h4>
                    <p class="text-muted mb-4">Coba ubah kata kunci pencarian atau filter Anda</p>
                </div>
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="bx bx-refresh"></i>Reset Pencarian
                </a>
            </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
function buyNow(productId) {
    // Redirect to checkout page
    window.location.href = `/checkout/${productId}`;
}
@endsection
