@extends('layouts.app')

@section('title', 'Tambah Pembelian Baru')

@section('content')
    <div class="flex-grow-1">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Pembelian /</span> Tambah Pembelian Baru
        </h4>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form Pembelian -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Form Pembelian Baru</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.purchases.store') }}" method="POST" id="purchaseForm">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="supplier_id">Supplier <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id"
                                        name="supplier_id" required>
                                        <option value="">Pilih Supplier</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('supplier_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="purchase_date">Tanggal Pembelian <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('purchase_date') is-invalid @enderror"
                                        id="purchase_date" name="purchase_date"
                                        value="{{ old('purchase_date', date('Y-m-d')) }}" required>
                                    @error('purchase_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="payment_method">Metode Pembayaran <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('payment_method') is-invalid @enderror"
                                        id="payment_method" name="payment_method" required>
                                        <option value="">Pilih Metode Pembayaran</option>
                                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai
                                        </option>
                                        <option value="transfer"
                                            {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                        <option value="credit" {{ old('payment_method') == 'credit' ? 'selected' : '' }}>
                                            Kredit</option>
                                        <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Cek
                                        </option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="notes">Catatan</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2"
                                        placeholder="Catatan pembelian (opsional)">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Produk Pembelian -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Detail Produk</h6>
                                <button type="button" id="addProductBtn" class="btn btn-primary">
                                    <i class="bx bx-plus me-1"></i> Tambah Produk
                                </button>
                            </div>

                            <div class="table-responsive mb-3">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-primary">
                                        <tr>
                                            <th width="5%" class="text-center">#</th>
                                            <th width="30%">Produk</th>
                                            <th width="18%">Harga Beli (Rp)</th>
                                            <th width="12%">Jumlah</th>
                                            <th width="20%">Total (Rp)</th>
                                            <th width="15%" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product-items">
                                        <tr id="empty-row">
                                            <td colspan="6" class="text-center text-muted py-4">
                                                <i class="bx bx-package fs-1 d-block mb-2"></i>
                                                Belum ada produk yang dipilih
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="4" class="text-end">Total Keseluruhan:</th>
                                            <th colspan="2" class="text-primary fw-bold">
                                                <span id="grand-total">Rp 0</span>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="row">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary me-2" id="submitBtn">
                                        <i class="bx bx-save me-1"></i> Simpan Pembelian
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>  
                </div>
            </div>
        </div>
    </div>

    <!-- Product Selection Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">
                        <i class="bx bx-search-alt me-2"></i>Pilih Produk
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Search -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bx-search"></i></span>
                                <input type="text" class="form-control" id="searchProduct"
                                    placeholder="Cari nama produk, kategori, atau supplier...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-outline-secondary w-100" id="clearSearch">
                                <i class="bx bx-x me-1"></i>Reset Pencarian
                            </button>
                        </div>
                    </div>

                    <!-- Products count info -->
                    <div class="alert alert-info mb-3">
                        <i class="bx bx-info-circle me-2"></i>
                        Total produk tersedia: <strong>{{ count($products) }}</strong>
                        @if (count($products) == 0)
                            <div class="mt-2">
                                <small class="text-muted">Belum ada produk dalam database. Silakan tambahkan produk
                                    terlebih dahulu.</small>
                            </div>
                        @endif
                    </div>

                    <!-- Products table -->
                    <div class="table-responsive" style="max-height: 400px;">
                        <table class="table table-hover table-sm" id="productsTable">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Supplier</th>
                                    <th>Stok Saat Ini</th>
                                    <th>Harga Jual</th>
                                    <th width="100">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($products as $product)
                                    <tr data-product-id="{{ $product->id }}">
                                        <td>
                                            <div class="fw-semibold">{{ $product->name }}</div>
                                            @if (isset($product->description))
                                                <small
                                                    class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ $product->category->name ?? 'Tidak ada kategori' }}
                                            </span>
                                        </td>
                                        <td>{{ $product->supplier->name ?? 'Tidak ada supplier' }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $product->stock <= 10 ? 'bg-danger' : ($product->stock <= 50 ? 'bg-warning' : 'bg-success') }}">
                                                {{ $product->stock }}
                                            </span>
                                        </td>
                                        <td>{{ 'Rp ' . number_format($product->price, 0, ',', '.') }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary select-product"
                                                data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                                data-stock="{{ $product->stock }}" data-price="{{ $product->price }}"
                                                data-category="{{ $product->category->name ?? 'Tidak ada kategori' }}"
                                                data-supplier="{{ $product->supplier->name ?? 'Tidak ada supplier' }}">
                                                <i class="bx bx-check me-1"></i>Pilih
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="bx bx-package fs-1 d-block mb-2"></i>
                                            Tidak ada produk tersedia
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x me-1"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .sticky-top {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .product-row:hover {
            background-color: #f8f9fa;
        }

        .table-responsive {
            border-radius: 0.375rem;
        }

        .badge {
            font-size: 0.75em;
        }

        .input-group-text {
            background-color: #fff;
            border-right: 0;
        }

        .input-group .form-control {
            border-left: 0;
        }

        .input-group .form-control:focus {
            box-shadow: none;
            border-color: #d4e4f7;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            let rowIndex = 0;
            let grandTotal = 0;
            let selectedProducts = []; // Track selected products to prevent duplicates

            // Format number to rupiah
            function formatRupiah(number) {
                if (isNaN(number) || number === null || number === undefined) {
                    return 'Rp 0';
                }
                return 'Rp ' + parseInt(number).toLocaleString('id-ID');
            }

            // Parse rupiah to number
            function parseRupiah(rupiahStr) {
                if (typeof rupiahStr === 'string') {
                    return parseInt(rupiahStr.replace(/[^\d]/g, '')) || 0;
                }
                return parseInt(rupiahStr) || 0;
            }

            // Calculate grand total
            function calculateGrandTotal() {
                grandTotal = 0;
                $('.row-total').each(function() {
                    const total = parseFloat($(this).data('total')) || 0;
                    grandTotal += total;
                });
                $('#grand-total').text(formatRupiah(grandTotal));

                // Show/hide product alert
                if ($('#product-items tr:not(#empty-row)').length === 0) {
                    $('#productAlert').removeClass('d-none');
                    $('#empty-row').show();
                } else {
                    $('#productAlert').addClass('d-none');
                    $('#empty-row').hide();
                }
            }

            // Renumber table rows
            function renumberRows() {
                $('#product-items tr:not(#empty-row)').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });
            }

            // Handle adding a new product row
            $('#addProductBtn').click(function() {
                // Show modal using Bootstrap 5 syntax
                const productModal = new bootstrap.Modal(document.getElementById('productModal'));
                productModal.show();
            });

            // Search product in modal
            $('#searchProduct').on('input', function() {
                const searchValue = $(this).val().toLowerCase().trim();

                $("#productsTable tbody tr").each(function() {
                    const row = $(this);
                    if (row.find('td').length === 1 && row.find('td').attr('colspan')) {
                        // Skip empty state row
                        return;
                    }

                    const text = row.text().toLowerCase();
                    if (text.indexOf(searchValue) > -1 || searchValue === '') {
                        row.show();
                    } else {
                        row.hide();
                    }
                });
            });

            // Clear search
            $('#clearSearch').click(function() {
                $('#searchProduct').val('');
                $("#productsTable tbody tr").show();
            });

            // Select product from modal
            $(document).on('click', '.select-product', function() {
                const productId = $(this).data('id');
                const productName = $(this).data('name');
                const productPrice = $(this).data('price');
                const productStock = $(this).data('stock');
                const productCategory = $(this).data('category');
                const productSupplier = $(this).data('supplier');

                // Check if product already selected
                if (selectedProducts.includes(productId)) {
                    alert(
                        'Produk ini sudah dipilih. Silakan pilih produk lain atau ubah kuantitas pada tabel.');
                    return;
                }

                addProductRow(productId, productName, productPrice, productStock, productCategory,
                    productSupplier);

                // Close modal
                const productModal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
                if (productModal) {
                    productModal.hide();
                }

                // Clear search after selection
                $('#searchProduct').val('');
                $("#productsTable tbody tr").show();
            });

            // Add product row
            function addProductRow(productId, productName, productPrice, productStock, productCategory,
                productSupplier) {
                rowIndex++;
                selectedProducts.push(productId);

                const stockBadgeClass = productStock <= 10 ? 'bg-danger' : (productStock <= 50 ? 'bg-warning' :
                    'bg-success');

                const row = `
            <tr id="product-row-${rowIndex}" class="product-row" data-product-id="${productId}">
                <td class="text-center fw-bold">1</td>
                <td>
                    <div class="fw-semibold">${productName}</div>
                    <small class="text-muted">
                        <i class="bx bx-category me-1"></i>${productCategory} | 
                        <i class="bx bx-store me-1"></i>${productSupplier}
                    </small>
                    <div class="mt-1">
                        <span class="badge ${stockBadgeClass}">
                            <i class="bx bx-package me-1"></i>Stok: ${productStock}
                        </span>
                    </div>
                    <input type="hidden" name="product_id[${rowIndex}]" value="${productId}" />
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control unit-cost" name="unit_cost[${rowIndex}]" 
                               value="${productPrice}" min="0" step="100" required 
                               data-index="${rowIndex}" />
                    </div>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm quantity" 
                           name="quantity[${rowIndex}]" value="1" min="1" 
                           required data-index="${rowIndex}" />
                </td>
                <td>
                    <span class="row-total fw-bold text-primary" data-total="${productPrice}">
                        ${formatRupiah(productPrice)}
                    </span>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-product" 
                            data-index="${rowIndex}" data-product-id="${productId}"
                            title="Hapus produk">
                        <i class="bx bx-trash"></i>
                    </button>
                </td>
            </tr>
        `;

                $('#product-items').append(row);
                renumberRows();
                calculateGrandTotal();

                // Focus on quantity input for quick editing
                $(`#product-row-${rowIndex} .quantity`).focus().select();
            }

            // Handle quantity or unit cost change
            $(document).on('input change', '.quantity, .unit-cost', function() {
                const row = $(this).closest('tr');
                const quantity = parseInt(row.find('.quantity').val()) || 0;
                const unitCost = parseFloat(row.find('.unit-cost').val()) || 0;
                const total = quantity * unitCost;

                // Update row total
                row.find('.row-total').data('total', total);
                row.find('.row-total').text(formatRupiah(total));

                calculateGrandTotal();
            });

            // Handle removing a product row
            $(document).on('click', '.remove-product', function() {
                const index = $(this).data('index');
                const productId = $(this).data('product-id');

                // Confirm deletion
                if (confirm('Apakah Anda yakin ingin menghapus produk ini dari daftar pembelian?')) {
                    // Remove from selected products
                    selectedProducts = selectedProducts.filter(id => id !== productId);

                    // Remove row
                    $(`#product-row-${index}`).fadeOut(300, function() {
                        $(this).remove();
                        renumberRows();
                        calculateGrandTotal();
                    });
                }
            });

            // Form validation before submit
            $('#purchaseForm').on('submit', function(e) {
                let isValid = true;
                let errorMessage = '';

                // Check if any products are selected
                if ($('#product-items tr:not(#empty-row)').length === 0) {
                    isValid = false;
                    errorMessage = 'Silakan tambahkan minimal satu produk untuk melanjutkan pembelian.';
                }

                // Check for invalid quantities
                $('.quantity.is-invalid, .unit-cost.is-invalid').each(function() {
                    isValid = false;
                    if (errorMessage === '') {
                        errorMessage =
                            'Terdapat kesalahan pada input produk. Silakan periksa kembali.';
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert(errorMessage);
                    $('#productAlert').removeClass('d-none').find('i').removeClass('bx-info-circle')
                        .addClass('bx-error-circle');
                    $('#productAlert').removeClass('alert-warning').addClass('alert-danger');
                    $('#productAlert').text(errorMessage);
                    return false;
                }

                // Show loading state
                $('#submitBtn').prop('disabled', true).html(
                    '<i class="bx bx-loader-alt bx-spin me-1"></i> Menyimpan...');
            });

            // Initialize
            calculateGrandTotal();

            // Auto-calculate on page load if there are old values
            @if (old('product_id'))
                // Handle old input restoration if validation fails
                setTimeout(function() {
                    $('.quantity, .unit-cost').each(function() {
                        $(this).trigger('change');
                    });
                }, 100);
            @endif
        });
    </script>
@endpush
