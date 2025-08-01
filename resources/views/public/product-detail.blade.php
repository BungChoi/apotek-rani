@extends('layouts.public')

@section('title', $product->name . ' - Apotek')

@section('styles')
.product-image-main {
    height: 400px;
    object-fit: contain;
    width: calc(100% - 40px);
    margin: 20px;
    padding: 20px;
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid #e3e6f0;
    transition: transform 0.3s ease;
}

.product-image-main:hover {
    transform: scale(1.02);
}

.product-image-placeholder {
    height: 400px;
    border-radius: 20px;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    margin: 20px;
    border: 1px solid #e3e6f0;
}

.price-tag {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 15px 25px;
    border-radius: 15px;
    font-size: 1.5rem;
    font-weight: bold;
    text-align: center;
}

.info-card {
    background: #ffffff;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    border: 1px solid #e3e6f0;
    transition: all 0.3s ease;
}

.info-card:hover {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.related-product-card {
    transition: transform 0.3s ease;
    border: none;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
}

.related-product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.related-product-image {
    height: 150px;
    object-fit: contain;
    width: calc(100% - 20px);
    margin: 10px;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 10px;
}

/* Button Styles */
.btn-detail {
    background-color: transparent;
    border: 1px solid #6366f1;
    color: #6366f1;
    padding: 8px 12px;
    font-size: 0.9rem;
    font-weight: 500;
    border-radius: 6px;
    transition: all 0.2s ease;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.btn-detail:hover {
    background-color: rgba(99, 102, 241, 0.1);
    color: #6366f1;
}

.btn-buy {
    background-color: #6366f1;
    color: white;
    border: none;
    padding: 8px 12px;
    font-size: 0.9rem;
    font-weight: 500;
    border-radius: 6px;
    transition: all 0.2s ease;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
}

.btn-buy:hover {
    background-color: #4f46e5;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(99, 102, 241, 0.3);
}

.stock-indicator {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 8px;
}

.stock-available { background-color: #28a745; }
.stock-low { background-color: #ffc107; }
.stock-out { background-color: #dc3545; }

.product-container {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('home') }}" class="text-decoration-none">
        Home
    </a>
</li>
<li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
@endsection

@section('content')
<!-- Product Detail Section -->
<section class="py-4">
    <div class="container">
        <div class="product-container">
            <div class="row">
                <!-- Product Image -->
                <div class="col-md-6 mb-4">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             class="product-image-main" 
                             alt="{{ $product->name }}">
                    @else
                        <div class="product-image-placeholder d-flex align-items-center justify-content-center">
                            <div class="text-center">
                                <i class="bx bx-plus-medical fa-5x text-primary mb-3"></i>
                                <p class="text-muted">Gambar produk tidak tersedia</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Product Info -->
                <div class="col-md-6">
                    <div class="h-100 d-flex flex-column">
                        <!-- Product Title and Price -->
                        <div class="mb-4">
                            <h1 class="display-6 fw-bold mb-3">{{ $product->name }}</h1>
                            <div class="price-tag mb-3">
                                {{ $product->formatted_price }}
                            </div>
                        </div>

                        <!-- Product Stats -->
                        <div class="info-card">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="border-end">
                                        <h6 class="text-muted mb-1">Stok</h6>
                                        <div class="fw-bold">
                                            @if($product->stock > 10)
                                                <span class="stock-indicator stock-available"></span>
                                            @elseif($product->stock > 0)
                                                <span class="stock-indicator stock-low"></span>
                                            @else
                                                <span class="stock-indicator stock-out"></span>
                                            @endif
                                            {{ $product->stock }} tersedia
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border-end">
                                        <h6 class="text-muted mb-1">Terjual</h6>
                                        <div class="fw-bold">
                                            <i class="bx bx-trending-up text-success me-1"></i>
                                            {{ $product->total_sold }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <h6 class="text-muted mb-1">Status</h6>
                                    <div class="fw-bold">
                                        @if($product->status === 'tersedia')
                                            <span class="badge bg-success">{{ ucfirst($product->status) }}</span>
                                        @elseif($product->status === 'habis')
                                            <span class="badge bg-warning">{{ ucfirst($product->status) }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ ucfirst($product->status) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Supplier Info -->
                        <div class="info-card">
                            <h6 class="fw-bold mb-2 icon-text-aligned">
                                <i class="bx bx-building text-primary me-2"></i>Supplier
                            </h6>
                            <p class="mb-0">{{ $product->supplier->name }}</p>
                        </div>

                        <!-- Expiry Date -->
                        <div class="info-card">
                            <h6 class="fw-bold mb-2 icon-text-aligned">
                                <i class="bx bx-calendar text-warning me-2"></i>Tanggal Kadaluarsa
                            </h6>
                            <p class="mb-0">
                                {{ $product->expired_date->format('d F Y') }}
                                @if($product->isExpiringSoon())
                                    <span class="badge bg-warning ms-2">Segera Kadaluarsa</span>
                                @endif
                            </p>
                        </div>

                        <!-- Description -->
                        @if($product->description)
                            <div class="info-card flex-grow-1">
                                <h6 class="fw-bold mb-2 icon-text-aligned">
                                    <i class="bx bx-info-circle text-info me-2"></i>Deskripsi
                                </h6>
                                <p class="mb-0">{{ $product->description }}</p>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="mt-auto">
                            @if($product->isAvailable())
                                @auth
                                    <div class="d-flex gap-2 mb-3">
                                        <form action="{{ route('public.cart.add', $product->id) }}" method="POST" class="flex-fill">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-detail w-100">
                                                <span>Tambah ke Keranjang</span>
                                            </button>
                                        </form>
                                        <button class="btn btn-buy flex-fill" onclick="buyNow({{ $product->id }})">
                                            <span>Beli Sekarang</span>
                                        </button>
                                    </div>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-buy w-100 mb-3">
                                        <i class="bx bx-log-in"></i>
                                        <span>Login untuk Membeli</span>
                                    </a>
                                @endauth
                            @else
                                <button class="btn btn-secondary w-100 mb-3" disabled style="border-radius: 6px; display: flex; align-items: center; justify-content: center; gap: 6px; height: 38px; font-size: 0.9rem; font-weight: 500;">
                                    <i class="bx bx-x-circle"></i>
                                    <span>Produk Tidak Tersedia</span>
                                </button>
                            @endif
                            
                            <a href="{{ route('home') }}" class="btn btn-detail w-100">
                                <i class="bx bx-arrow-back"></i>
                                <span>Kembali ke Katalog</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
@if($relatedProducts->count() > 0)
    <section class="py-5" style="background-color: #f8f9fa;">
        <div class="container">
            <h3 class="section-title icon-text-aligned">
                <i class="bx bx-plus-medical me-2"></i>Produk Terkait
            </h3>
            <div class="row">
                @foreach($relatedProducts as $relatedProduct)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card related-product-card h-100">
                            <div class="position-relative" style="height: 160px; overflow: visible;">
                                @if($relatedProduct->image)
                                    <img src="{{ asset('storage/' . $relatedProduct->image) }}" 
                                         class="related-product-image" 
                                         alt="{{ $relatedProduct->name }}">
                                @else
                                    <div class="related-product-image d-flex align-items-center justify-content-center">
                                        <i class="bx bx-plus-medical fa-2x text-primary"></i>
                                    </div>
                                @endif
                                
                                <!-- Stock Badge -->
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-success" style="font-size: 0.7rem;">
                                        stok: {{ $relatedProduct->stock }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="card-body" style="padding: 1rem;">
                                <h6 class="card-title mb-2" style="font-weight: 600; color: #2c3e50; font-size: 0.9rem; height: 2.4rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                    {{ $relatedProduct->name }}
                                </h6>
                                <p class="text-success fw-bold mb-2" style="font-size: 1rem;">
                                    {{ $relatedProduct->formatted_price }}
                                </p>
                                <p class="small text-muted mb-3" style="font-size: 0.75rem;">
                                    {{ Str::limit($relatedProduct->supplier->name, 20) }}
                                </p>
                                
                                <div class="d-flex gap-2">
                                    <a href="{{ route('public.products.show', $relatedProduct->id) }}" 
                                       class="btn btn-detail flex-fill" style="font-size: 0.8rem; padding: 6px 8px;">
                                        <span>Detail</span>
                                    </a>
                                    @auth
                                        <div class="dropdown d-inline-block flex-fill">
                                            <button class="btn btn-buy btn-sm dropdown-toggle w-100" 
                                                type="button" data-bs-toggle="dropdown"
                                                onmouseover="this.style.color='#fff'; this.querySelectorAll('i').forEach(el => el.style.color='#fff');" 
                                                onmouseout="this.style.color=''; this.querySelectorAll('i').forEach(el => el.style.color='');">
                                                Beli
                                            </button>
                                            <ul class="dropdown-menu p-2" style="min-width: 180px;">
                                                <li>
                                                    <form action="{{ route('public.cart.add', $relatedProduct->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="quantity" value="1">
                                                        <button type="submit" class="dropdown-item py-1 px-2">
                                                            Tambah ke Keranjang
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item py-1 px-2" href="#" onclick="buyNow({{ $relatedProduct->id }})">
                                                         Beli Sekarang
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm flex-fill" style="font-size: 0.75rem;">
                                            <i class="bx bx-log-in me-1"></i>Login
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
@endsection

@section('scripts')
function buyNow(productId) {
    // Redirect to checkout page
    window.location.href = `/checkout/${productId}`;
}
@endsection 