@extends('layouts.app')

@section('title', 'Buat Transaksi Penjualan')

@section('content')
<div class="flex-grow-1">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Penjualan /</span> Buat Transaksi Baru
    </h4>

    <div class="row">
        <!-- Form Transaksi -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Form Transaksi Penjualan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.sales.store') }}" method="POST" id="salesForm">
                        @csrf
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label" for="transaction_date">Tanggal Transaksi</label>
                                <input type="datetime-local" class="form-control" id="transaction_date" name="transaction_date" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="payment_method">Metode Pembayaran</label>
                                <select class="form-select" id="payment_method" name="payment_method" required>
                                    <option value="">Pilih Metode Pembayaran</option>
                                    <option value="cash">Cash</option>
                                    <option value="transfer">Transfer</option>
                                    <option value="credit_card">Kartu Kredit</option>
                                    <option value="debit_card">Kartu Debit</option>
                                    <option value="e_wallet">E-Wallet</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label" for="customer_id">Pelanggan</label>
                                <select class="form-select" id="customer_id" name="customer_id" required>
                                    <option value="">Pilih Pelanggan</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="notes">Catatan</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Catatan tambahan untuk transaksi ini"></textarea>
                        </div>

                        <h6 class="fw-bold mt-4 mb-3">Daftar Item</h6>
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal">
                                <i class="bx bx-plus"></i> Tambah Item
                            </button>
                        </div>

                        <!-- Items Table -->
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th width="100">Jumlah</th>
                                        <th width="150">Harga</th>
                                        <th width="150">Total</th>
                                        <th width="50">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsContainer">
                                    <tr id="emptyRow">
                                        <td colspan="5" class="text-center py-3">Belum ada item yang ditambahkan</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">Total</td>
                                        <td class="fw-bold" id="grandTotal">Rp 0</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <input type="hidden" name="products" id="itemsInput">
                        <input type="hidden" name="total_amount" id="totalAmountInput">

                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('admin.sales.history') }}" class="btn btn-outline-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Simpan Transaksi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Ringkasan Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Subtotal</span>
                            <span id="summarySubtotal">Rp 0</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Diskon</span>
                            <span>Rp 0</span>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span id="summaryTotal">Rp 0</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Info Transaksi</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><i class="bx bx-user text-primary me-2"></i> <span id="customerInfo">Belum dipilih</span></p>
                    <p class="mb-1"><i class="bx bx-calendar text-primary me-2"></i> <span id="dateInfo">{{ now()->format('d M Y H:i') }}</span></p>
                    <p><i class="bx bx-credit-card text-primary me-2"></i> <span id="paymentInfo">Belum dipilih</span></p>
                    <div class="alert alert-primary mt-3 mb-0">
                        <div class="d-flex">
                            <i class="bx bx-info-circle me-2 mt-1"></i>
                            <div>
                                <p class="mb-0 fw-semibold">Tips:</p>
                                <p class="mb-0">Gunakan tombol "Tambah Item" untuk menambahkan produk dengan cepat.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Selection Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="modalSearchProduct" placeholder="Cari produk...">
                            <button class="btn btn-outline-primary" type="button" id="modalSearchProductBtn">
                                <i class="bx bx-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Stok</th>
                                    <th>Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="productsList">
                                @foreach($products ?? [] as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>{{ $product->stock }}</td>
                                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary select-product" 
                                            data-product-id="{{ $product->id }}" 
                                            data-product-name="{{ $product->name }}" 
                                            data-product-price="{{ $product->price }}"
                                            data-product-stock="{{ $product->stock }}">
                                            Pilih
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables
    let items = [];
    let grandTotal = 0;
    
    // Elements
    const itemsContainer = document.getElementById('itemsContainer');
    const emptyRow = document.getElementById('emptyRow');
    const grandTotalElement = document.getElementById('grandTotal');
    const summarySubtotal = document.getElementById('summarySubtotal');
    const summaryTotal = document.getElementById('summaryTotal');
    const submitBtn = document.getElementById('submitBtn');
    const itemsInput = document.getElementById('itemsInput');
    const totalAmountInput = document.getElementById('totalAmountInput');
    const customerInfo = document.getElementById('customerInfo');
    const dateInfo = document.getElementById('dateInfo');
    const paymentInfo = document.getElementById('paymentInfo');
    const customerIdSelect = document.getElementById('customer_id');
    
    // Update customer info when customer is selected
    customerIdSelect.addEventListener('change', function() {
        if (this.value) {
            const selectedText = this.options[this.selectedIndex].text;
            customerInfo.textContent = selectedText;
        } else {
            customerInfo.textContent = 'Belum dipilih';
        }
    });
    
    // Update payment info
    document.getElementById('payment_method').addEventListener('change', function() {
        const paymentMethods = {
            'cash': 'Cash',
            'transfer': 'Transfer',
            'credit_card': 'Kartu Kredit',
            'debit_card': 'Kartu Debit',
            'e_wallet': 'E-Wallet',
            'qris': 'QRIS'
        };
        
        paymentInfo.textContent = paymentMethods[this.value] || 'Belum dipilih';
    });
    
    // Update date info
    document.getElementById('transaction_date').addEventListener('change', function() {
        const date = new Date(this.value);
        dateInfo.textContent = date.toLocaleDateString('id-ID', { 
            day: '2-digit', 
            month: 'short', 
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    });
    
    // Product Selection - Event delegation untuk handle dinamis
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('select-product')) {
            const button = event.target;
            const productId = button.getAttribute('data-product-id');
            const productName = button.getAttribute('data-product-name');
            const productPrice = parseFloat(button.getAttribute('data-product-price'));
            const productStock = parseInt(button.getAttribute('data-product-stock'));
            
            addProductToCart(productId, productName, productPrice, productStock);
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
            if (modal) {
                modal.hide();
            }
        }
        
        // Handle remove item
        if (event.target.classList.contains('remove-item') || event.target.closest('.remove-item')) {
            const button = event.target.classList.contains('remove-item') ? event.target : event.target.closest('.remove-item');
            const productId = button.getAttribute('data-product-id');
            removeItem(productId);
        }
    });
    
    // Handle quantity change - Event delegation
    document.addEventListener('change', function(event) {
        if (event.target.classList.contains('item-quantity')) {
            const productId = event.target.getAttribute('data-product-id');
            const newQuantity = parseInt(event.target.value);
            updateItemQuantity(productId, newQuantity);
        }
    });
    
    // Add product to cart
    function addProductToCart(productId, productName, productPrice, productStock, quantity = 1) {
        // Check if product already exists in cart
        const existingItemIndex = items.findIndex(item => item.product_id === productId);
        
        if (existingItemIndex !== -1) {
            // Update existing item quantity
            const newQuantity = items[existingItemIndex].quantity + quantity;
            
            // Check stock
            if (newQuantity > productStock) {
                alert(`Stok tidak mencukupi! Stok tersedia: ${productStock}`);
                return;
            }
            
            items[existingItemIndex].quantity = newQuantity;
            items[existingItemIndex].total = newQuantity * productPrice;
            
            // Update DOM
            const quantityInput = document.getElementById(`quantity-${productId}`);
            const totalElement = document.getElementById(`total-${productId}`);
            
            if (quantityInput && totalElement) {
                quantityInput.value = newQuantity;
                totalElement.textContent = formatCurrency(newQuantity * productPrice);
            }
        } else {
            // Check stock
            if (quantity > productStock) {
                alert(`Stok tidak mencukupi! Stok tersedia: ${productStock}`);
                return;
            }
            
            // Add new item
            const total = quantity * productPrice;
            items.push({
                product_id: productId,
                name: productName,
                price: productPrice,
                quantity: quantity,
                total: total,
                stock: productStock
            });
            
            // Hide empty row
            if (emptyRow) {
                emptyRow.style.display = 'none';
            }
            
            // Create new row
            const newRow = document.createElement('tr');
            newRow.id = `item-${productId}`;
            newRow.innerHTML = `
                <td>${productName}</td>
                <td>
                    <input type="number" class="form-control form-control-sm item-quantity" 
                        id="quantity-${productId}" 
                        min="1" 
                        max="${productStock}" 
                        value="${quantity}" 
                        data-product-id="${productId}">
                </td>
                <td>${formatCurrency(productPrice)}</td>
                <td id="total-${productId}">${formatCurrency(total)}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-item" data-product-id="${productId}">
                        <i class="bx bx-trash"></i>
                    </button>
                </td>
            `;
            
            itemsContainer.appendChild(newRow);
        }
        
        // Update totals
        updateTotals();
    }
    
    // Update item quantity
    function updateItemQuantity(productId, newQuantity) {
        const itemIndex = items.findIndex(item => item.product_id === productId);
        
        if (itemIndex !== -1) {
            // Check stock
            if (newQuantity > items[itemIndex].stock) {
                alert(`Stok tidak mencukupi! Stok tersedia: ${items[itemIndex].stock}`);
                const quantityInput = document.getElementById(`quantity-${productId}`);
                if (quantityInput) {
                    quantityInput.value = items[itemIndex].quantity;
                }
                return;
            }
            
            items[itemIndex].quantity = newQuantity;
            items[itemIndex].total = newQuantity * items[itemIndex].price;
            
            // Update total in DOM
            const totalElement = document.getElementById(`total-${productId}`);
            if (totalElement) {
                totalElement.textContent = formatCurrency(items[itemIndex].total);
            }
            
            // Update totals
            updateTotals();
        }
    }
    
    // Remove item
    function removeItem(productId) {
        const itemIndex = items.findIndex(item => item.product_id === productId);
        
        if (itemIndex !== -1) {
            items.splice(itemIndex, 1);
            
            // Remove row from DOM
            const row = document.getElementById(`item-${productId}`);
            if (row) {
                row.remove();
            }
            
            // Show empty row if no items
            if (items.length === 0 && emptyRow) {
                emptyRow.style.display = '';
            }
            
            // Update totals
            updateTotals();
        }
    }
    
    // Update totals
    function updateTotals() {
        grandTotal = items.reduce((sum, item) => sum + item.total, 0);
        
        grandTotalElement.textContent = formatCurrency(grandTotal);
        summarySubtotal.textContent = formatCurrency(grandTotal);
        summaryTotal.textContent = formatCurrency(grandTotal);
        
        // Update hidden inputs
        itemsInput.value = JSON.stringify(items.map(item => ({
            id: item.product_id,
            quantity: item.quantity,
            price: item.price
        })));
        totalAmountInput.value = grandTotal;
        
        // Enable/disable submit button
        submitBtn.disabled = items.length === 0;
    }
    
    // Format currency
    function formatCurrency(amount) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
    }
    
    // Modal product search
    const modalSearchProduct = document.getElementById('modalSearchProduct');
    const modalSearchProductBtn = document.getElementById('modalSearchProductBtn');
    
    if (modalSearchProductBtn) {
        modalSearchProductBtn.addEventListener('click', function() {
            const searchTerm = modalSearchProduct.value.trim();
            filterProducts(searchTerm);
        });
    }
    
    if (modalSearchProduct) {
        modalSearchProduct.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                modalSearchProductBtn.click();
            }
        });
    }
    
    function filterProducts(searchTerm) {
        const productRows = document.querySelectorAll('#productsList tr');
        let foundProducts = false;
        
        productRows.forEach(row => {
            const productName = row.querySelector('td:first-child')?.textContent.toLowerCase() || '';
            const category = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
            
            if (productName.includes(searchTerm.toLowerCase()) || category.includes(searchTerm.toLowerCase()) || searchTerm === '') {
                row.style.display = '';
                foundProducts = true;
            } else {
                row.style.display = 'none';
            }
        });
        
        // Show message if no products found
        const existingNoResultsRow = document.getElementById('noProductResults');
        if (!foundProducts && searchTerm !== '') {
            if (!existingNoResultsRow) {
                const tbody = document.getElementById('productsList');
                const newRow = document.createElement('tr');
                newRow.id = 'noProductResults';
                newRow.innerHTML = `<td colspan="5" class="text-center">Tidak ada produk yang ditemukan</td>`;
                tbody.appendChild(newRow);
            }
        } else if (existingNoResultsRow) {
            existingNoResultsRow.remove();
        }
    }
});
</script>
@endsection