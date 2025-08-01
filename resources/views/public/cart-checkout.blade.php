@extends('layouts.public')

@section('title', 'Checkout - Apotek')

@section('styles')
.checkout-container {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
}

.product-image {
    height: 60px;
    width: 60px;
    object-fit: contain;
    background: #ffffff;
    padding: 5px;
    border-radius: 8px;
    border: 1px solid #e3e6f0;
}

.product-image-placeholder {
    height: 60px;
    width: 60px;
    border-radius: 8px;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border: 1px solid #e3e6f0;
    display: flex;
    justify-content: center;
    align-items: center;
}

.cart-items-table {
    border-radius: 15px;
    overflow: hidden;
    border: 1px solid #e3e6f0;
}

.order-summary {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 20px;
    border: 1px solid #e3e6f0;
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
    <a href="{{ route('public.cart.index') }}" class="text-decoration-none">
        Keranjang Belanja
    </a>
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
                <i class="bx bx-credit-card me-2"></i>Checkout
            </h2>

            <div class="row">
                <!-- Cart Items -->
                <div class="col-md-7 mb-4">
                    <h5 class="mb-3">Daftar Produk</h5>
                    
                    <div class="table-responsive cart-items-table">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 60px"></th>
                                    <th scope="col">Produk</th>
                                    <th scope="col" class="text-center">Qty</th>
                                    <th scope="col" class="text-end">Harga</th>
                                    <th scope="col" class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    @php
                                        $quantity = $cartItems[array_search($product->id, array_column($cartItems, 'id'))]['quantity'];
                                        $subtotal = $product->price * $quantity;
                                    @endphp
                                    <tr>
                                        <td>
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" 
                                                     class="product-image" 
                                                     alt="{{ $product->name }}">
                                            @else
                                                <div class="product-image-placeholder">
                                                    <i class="bx bx-plus-medical text-primary"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $product->name }}</div>
                                            <div class="small text-muted">{{ $product->supplier->name }}</div>
                                        </td>
                                        <td class="text-center">{{ $quantity }}</td>
                                        <td class="text-end">{{ $product->formatted_price }}</td>
                                        <td class="text-end">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('public.cart.index') }}" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back me-2"></i>Kembali ke Keranjang
                        </a>
                    </div>
                </div>

                <!-- Order Form -->
                <div class="col-md-5">
                    <form method="POST" action="{{ route('public.cart.process-checkout') }}">
                        @csrf
                        
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
                                <span>Total Produk:</span>
                                <span>{{ count($products) }} item</span>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Kuantitas:</span>
                                <span>{{ collect($cartItems)->sum('quantity') }} unit</span>
                            </div>
                            
                            <hr>
                            
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold">Total Pembayaran:</span>
                                <span class="total-price">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bx bx-check-circle me-2"></i>Konfirmasi Pesanan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
