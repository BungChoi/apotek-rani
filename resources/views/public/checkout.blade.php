@extends('layouts.public')

@section('title', 'Checkout - ' . $product->name . ' - Apotek')

@section('styles')
.checkout-container {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
}

.product-image {
    height: 200px;
    object-fit: contain;
    width: 100%;
    padding: 20px;
    background: #ffffff;
    border-radius: 15px;
    border: 1px solid #e3e6f0;
}

.product-image-placeholder {
    height: 200px;
    border-radius: 15px;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border: 1px solid #e3e6f0;
}

.price-display {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 15px 20px;
    border-radius: 15px;
    font-size: 1.2rem;
    font-weight: bold;
    text-align: center;
    margin: 15px 0;
}

.order-summary {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 20px;
    border: 1px solid #e3e6f0;
}

.quantity-input {
    max-width: 120px;
}

.total-price {
    font-size: 1.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('home') }}" class="text-decoration-none">
        Home
    </a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('public.products.show', $product->id) }}" class="text-decoration-none">{{ $product->name }}</a>
</li>
<li class="breadcrumb-item active" aria-current="page">Checkout</li>
@endsection

@section('content')
<!-- Checkout Section -->
<section class="py-4">
    <div class="container">
        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bx bx-error-circle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="checkout-container">
            <h2 class="section-title icon-text-aligned">
                <i class="bx bx-cart me-2"></i>Checkout Produk
            </h2>

            <div class="row">
                <!-- Product Info -->
                <div class="col-md-6 mb-4">
                    <div class="text-center mb-3">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 class="product-image" 
                                 alt="{{ $product->name }}">
                        @else
                            <div class="product-image-placeholder d-flex align-items-center justify-content-center">
                                <i class="bx bx-plus-medical fa-3x text-primary"></i>
                            </div>
                        @endif
                    </div>

                    <h4 class="fw-bold mb-3">{{ $product->name }}</h4>
                    
                    <div class="price-display">
                        {{ $product->formatted_price }} / unit
                    </div>

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="text-muted small">Stok Tersedia</div>
                            <div class="fw-bold">{{ $product->stock }} unit</div>
                        </div>
                        <div class="col-6">
                            <div class="text-muted small">Supplier</div>
                            <div class="fw-bold">{{ $product->supplier->name }}</div>
                        </div>
                    </div>

                    @if($product->description)
                        <div class="mt-3">
                            <h6 class="fw-bold">Deskripsi:</h6>
                            <p class="text-muted">{{ $product->description }}</p>
                        </div>
                    @endif
                </div>

                <!-- Order Form -->
                <div class="col-md-6">
                    <form method="POST" action="{{ route('public.process-sale', $product->id) }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="quantity" class="form-label fw-semibold">Jumlah</label>
                            <input type="number" 
                                   class="form-control quantity-input" 
                                   id="quantity" 
                                   name="quantity" 
                                   value="{{ old('quantity', 1) }}" 
                                   min="1" 
                                   max="{{ $product->stock }}" 
                                   required
                                   onchange="updateTotal()">
                            <div class="form-text">Maksimal {{ $product->stock }} unit</div>
                        </div>

                        <div class="mb-4">
                            <label for="payment_method" class="form-label fw-semibold">Metode Pembayaran</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="">Pilih metode pembayaran</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>
                                    💵 Tunai
                                </option>
                                <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>
                                    🏦 Transfer Bank
                                </option>
                                <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>
                                    💳 Kartu Kredit
                                </option>
                                <option value="debit_card" {{ old('payment_method') == 'debit_card' ? 'selected' : '' }}>
                                    💳 Kartu Debit
                                </option>
                                <option value="e_wallet" {{ old('payment_method') == 'e_wallet' ? 'selected' : '' }}>
                                    📱 E-Wallet
                                </option>
                                <option value="qris" {{ old('payment_method') == 'qris' ? 'selected' : '' }}>
                                    📱 QRIS
                                </option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label fw-semibold">Catatan (Opsional)</label>
                            <textarea class="form-control" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      placeholder="Tambahkan catatan untuk pesanan Anda...">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Order Summary -->
                        <div class="order-summary mb-4">
                            <h6 class="fw-bold mb-3">Ringkasan Pesanan</h6>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Harga per unit:</span>
                                <span id="unit-price">{{ $product->formatted_price }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Jumlah:</span>
                                <span id="quantity-display">1 unit</span>
                            </div>
                            
                            <hr>
                            
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold">Total:</span>
                                <span class="total-price" id="total-price">{{ $product->formatted_price }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="bx bx-credit-card me-2"></i>Proses Pesanan
                            </button>
                            <a href="{{ route('public.products.show', $product->id) }}" 
                               class="btn btn-outline-secondary">
                                <i class="bx bx-arrow-back me-2"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
const productPrice = {{ $product->price }};

function updateTotal() {
    const quantity = parseInt(document.getElementById('quantity').value) || 1;
    const total = productPrice * quantity;
    
    document.getElementById('quantity-display').textContent = quantity + ' unit';
    document.getElementById('total-price').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

// Initialize total on page load
updateTotal();
@endsection 