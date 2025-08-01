@extends('layouts.public')

@section('title', 'Keranjang Belanja - Apotek')

@section('styles')
    .cart-container {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
    }

    .product-image {
    height: 80px;
    width: 80px;
    object-fit: contain;
    background: #ffffff;
    padding: 5px;
    border-radius: 10px;
    border: 1px solid #e3e6f0;
    }

    .product-image-placeholder {
    height: 80px;
    width: 80px;
    border-radius: 10px;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border: 1px solid #e3e6f0;
    display: flex;
    justify-content: center;
    align-items: center;
    }

    .cart-item {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 15px;
    margin-bottom: 15px;
    border: 1px solid #e3e6f0;
    transition: all 0.3s ease;
    }

    .cart-item:hover {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .quantity-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    max-width: 120px;
    margin: 0 auto;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
    }

    .quantity-btn {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    border: none;
    font-size: 14px;
    color: #6c757d;
    cursor: pointer;
    transition: all 0.2s;
    }

    .quantity-btn:hover {
    background-color: #e9ecef;
    color: #495057;
    }

    .quantity-input {
    width: 40px;
    text-align: center;
    border: none;
    font-size: 14px;
    font-weight: 500;
    padding: 4px 0;
    -moz-appearance: textfield;
    -webkit-appearance: none;
    margin: 0;
    background-color: #fff;
    }

    .quantity-input::-webkit-outer-spin-button,
    .quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
    }

    .total-price {
    font-size: 1.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    }

    .empty-cart {
    text-align: center;
    padding: 2rem;
    }

    .empty-cart i {
    font-size: 5rem;
    color: #d1d1d1;
    margin-bottom: 1rem;
    }

    .quantity-input:disabled {
    background-color: #f8f9fa;
    opacity: 0.7;
    cursor: not-allowed;
    }
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('home') }}" class="text-decoration-none">
            Home
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Keranjang Belanja</li>
@endsection

@section('content')
    <!-- Cart Section -->
    <section class="py-4">
        <div class="container">
            <!-- Alerts -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="cart-container">
                <h2 class="section-title icon-text-aligned">
                    <i class="bx bx-cart me-2"></i>Keranjang Belanja
                </h2>

                @if (count($products) > 0)
                    <div class="cart-items mb-4">
                        @foreach ($products as $item)
                            <div class="cart-item" data-product-id="{{ $item['product']->id }}">
                                <div class="row align-items-center">
                                    <!-- Product Image -->
                                    <div class="col-md-2 col-4 text-center mb-3 mb-md-0">
                                        @if ($item['product']->image)
                                            <img src="{{ asset('storage/' . $item['product']->image) }}"
                                                class="product-image" alt="{{ $item['product']->name }}">
                                        @else
                                            <div class="product-image-placeholder">
                                                <i class="bx bx-plus-medical fa-lg text-primary"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Details -->
                                    <div class="col-md-4 col-8 mb-3 mb-md-0">
                                        <h5 class="mb-1">{{ $item['product']->name }}</h5>
                                        <p class="text-success mb-0">{{ $item['product']->formatted_price }} / unit</p>
                                        <small class="text-muted">Stok: {{ $item['product']->stock }} tersedia</small>
                                    </div>

                                    <!-- Quantity -->
                                    <div class="col-md-2 col-5 mb-3 mb-md-0">
                                        <div class="quantity-controls">
                                            <button class="quantity-btn" type="button"
                                                onclick="updateQuantity({{ $item['product']->id }}, -1)">
                                                <i class="bx bx-minus"></i>
                                            </button>
                                            <input type="number" class="quantity-input"
                                                id="quantity-{{ $item['product']->id }}" value="{{ $item['quantity'] }}"
                                                min="1" max="{{ $item['product']->stock }}"
                                                onchange="updateCartItem({{ $item['product']->id }}, this.value)">
                                            <button class="quantity-btn" type="button"
                                                onclick="updateQuantity({{ $item['product']->id }}, 1)">
                                                <i class="bx bx-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Subtotal -->
                                    <div class="col-md-2 col-7 text-end mb-3 mb-md-0">
                                        <div class="fw-bold">Subtotal:</div>
                                        <div class="text-success" id="subtotal-{{ $item['product']->id }}">
                                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="col-md-2 col-12 text-md-end text-center">
                                        <form action="{{ route('public.cart.remove', $item['product']->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="bx bx-trash me-1"></i>Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Cart Summary -->
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Ringkasan Belanja</h5>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total Item:</span>
                                        <span id="cart-item-count">{{ count($products) }} produk</span>
                                    </div>

                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="fw-bold">Total Harga:</span>
                                        <span class="total-price" id="cart-total">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <a href="{{ route('public.cart.checkout') }}" class="btn btn-primary flex-fill">
                                            <i class="bx bx-credit-card me-2"></i>Checkout
                                        </a>
                                        <form action="{{ route('public.cart.clear') }}" method="POST" class="flex-fill">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-secondary w-100">
                                                <i class="bx bx-x me-2"></i>Kosongkan
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="empty-cart">
                        <i class="bx bx-cart"></i>
                        <h4>Keranjang Belanja Kosong</h4>
                        <p class="text-muted mb-4">Anda belum menambahkan produk apapun ke keranjang.</p>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            Belanja Sekarang
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    // Store product prices for calculations
    const productPrices = {
        @foreach($products as $item)
            {{ $item['product']->id }}: {{ $item['product']->price }},
        @endforeach
    };

    // Store product stock for validation
    const productStocks = {
        @foreach($products as $item)
            {{ $item['product']->id }}: {{ $item['product']->stock }},
        @endforeach
    };

    // Track ongoing requests to prevent multiple simultaneous updates
    const ongoingRequests = {};
    
    // Store previous quantities to prevent unnecessary updates
    const previousQuantities = {};

    // Format number to Indonesian currency
    function formatCurrency(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }

    // Calculate and update subtotal for a product
    function updateSubtotal(productId, quantity) {
        const price = productPrices[productId];
        const subtotal = price * quantity;
        const subtotalElement = document.getElementById(`subtotal-${productId}`);
        if (subtotalElement) {
            subtotalElement.textContent = formatCurrency(subtotal);
        }
        return subtotal;
    }

    // Calculate and update total cart
    function updateCartTotal() {
        let total = 0;
        let itemCount = 0;
        
        // Calculate total from all products
        Object.keys(productPrices).forEach(productId => {
            const input = document.getElementById(`quantity-${productId}`);
            if (input) {
                const quantity = parseInt(input.value) || 0;
                if (quantity > 0) {
                    total += productPrices[productId] * quantity;
                    itemCount++;
                }
            }
        });
        
        // Update total display
        const totalElement = document.getElementById('cart-total');
        if (totalElement) {
            totalElement.textContent = formatCurrency(total);
        }
        
        // Update item count
        const itemCountElement = document.getElementById('cart-item-count');
        if (itemCountElement) {
            itemCountElement.textContent = itemCount + ' produk';
        }
        
        return total;
    }

    // Update cart item quantity (silent update - no loading state, no success notification)
    function updateCartItem(productId, quantity) {
        // Prevent multiple simultaneous requests for the same product
        if (ongoingRequests[productId]) {
            return;
        }
        
        // Get the cart item container
        const cartItem = document.querySelector(`[data-product-id="${productId}"]`);
        const input = document.getElementById(`quantity-${productId}`);
        
        if (!input) return;
        
        // Mark request as ongoing
        ongoingRequests[productId] = true;
        
        // Update subtotal immediately for better UX (no loading state)
        updateSubtotal(productId, quantity);
        updateCartTotal();
        
        // Use fetch instead of jQuery with timeout
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 second timeout
        
        fetch("{{ route('public.cart.update') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            }),
            signal: controller.signal
        })
        .then(response => response.json())
        .then(data => {
            // Clear timeout
            clearTimeout(timeoutId);
            delete ongoingRequests[productId];
            
            if (data.success) {
                // Update total with server response
                if (data.formatted_total) {
                    const totalElement = document.getElementById('cart-total');
                    if (totalElement) {
                        totalElement.textContent = data.formatted_total;
                    }
                }
                // No success notification - silent update
            } else {
                showAlert('error', data.message || 'Terjadi kesalahan');
                // Revert changes if failed
                location.reload();
            }
        })
        .catch(error => {
            // Clear timeout
            clearTimeout(timeoutId);
            delete ongoingRequests[productId];
            
            console.error('Error:', error);
            if (error.name === 'AbortError') {
                showAlert('error', 'Request timeout. Silakan coba lagi.');
            } else {
                showAlert('error', 'Terjadi kesalahan saat memperbarui keranjang');
            }
            // Revert changes if failed
            location.reload();
        });
    }

    // Increment/decrement quantity (silent update - no error notification for stock limit)
    function updateQuantity(productId, change) {
        const input = document.getElementById(`quantity-${productId}`);
        if (!input) return;
        
        let currentValue = parseInt(input.value) || 0;
        let newValue = currentValue + change;
        const min = parseInt(input.min) || 1;
        const max = productStocks[productId] || 999;

        // Ensure within min/max bounds
        if (newValue < min) {
            newValue = min;
        } else if (newValue > max) {
            newValue = max;
            // No error notification - just limit to max stock
        }

        // Only update if value is valid and changed
        if (newValue > 0 && newValue !== currentValue) {
            input.value = newValue;
            previousQuantities[productId] = newValue;
            updateCartItem(productId, newValue);
        }
    }

    // Show alert message
    function showAlert(type, message) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? 'bx-check-circle' : 'bx-error-circle';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="bx ${icon} me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Insert alert at the top of the cart container
        const cartContainer = document.querySelector('.cart-container');
        if (cartContainer) {
            cartContainer.insertAdjacentHTML('afterbegin', alertHtml);
            
            // Auto remove alert after 3 seconds
            setTimeout(() => {
                const alert = cartContainer.querySelector('.alert');
                if (alert) {
                    alert.remove();
                }
            }, 3000);
        }
    }

    // Initialize cart totals on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateCartTotal();
        
        // Initialize previous quantities
        Object.keys(productPrices).forEach(productId => {
            const input = document.getElementById(`quantity-${productId}`);
            if (input) {
                previousQuantities[productId] = parseInt(input.value) || 0;
            }
        });
        
        // Add event listeners for quantity inputs
        Object.keys(productPrices).forEach(productId => {
            const input = document.getElementById(`quantity-${productId}`);
            if (input) {
                // Add debounced input event for better performance
                let timeout;
                input.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        const quantity = parseInt(this.value) || 0;
                        const min = parseInt(this.min) || 1;
                        const max = productStocks[productId] || 999;
                        
                        // Validate quantity
                        if (isNaN(quantity) || quantity < min) {
                            this.value = min;
                            quantity = min;
                        } else if (quantity > max) {
                            this.value = max;
                            quantity = max;
                            // No error notification - just limit to max stock
                        }
                        
                        // Update cart if quantity is valid and changed
                        if (quantity >= min && quantity <= max && !isNaN(quantity) && quantity > 0) {
                            // Only update if quantity actually changed
                            if (previousQuantities[productId] !== quantity) {
                                previousQuantities[productId] = quantity;
                                updateCartItem(productId, quantity);
                            }
                        }
                    }, 500); // 500ms delay
                });
            }
        });
    });
@endsection