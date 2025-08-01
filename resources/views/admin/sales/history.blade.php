@extends('layouts.app')

@section('title', 'Riwayat Penjualan')

@section('content')
<div class="flex-grow-1">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Penjualan /</span> Riwayat Penjualan
    </h4>

    <!-- Search and Filter -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title">Filter Riwayat</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.sales.history') }}" method="GET" id="search-form">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label" for="search">No. Transaksi</label>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Cari transaksi..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="customer_name">Nama Pelanggan</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Nama pelanggan..." value="{{ request('customer_name') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="date_start">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="date_start" name="date_start" value="{{ request('date_start') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="date_end">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="date_end" name="date_end" value="{{ request('date_end') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="payment_method">Metode Pembayaran</label>
                        <select class="form-select" id="payment_method" name="payment_method">
                            <option value="">Semua Metode</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="transfer" {{ request('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="credit_card" {{ request('payment_method') == 'credit_card' ? 'selected' : '' }}>Kartu Kredit</option>
                            <option value="debit_card" {{ request('payment_method') == 'debit_card' ? 'selected' : '' }}>Kartu Debit</option>
                            <option value="e_wallet" {{ request('payment_method') == 'e_wallet' ? 'selected' : '' }}>E-Wallet</option>
                            <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            Filter
                        </button>
                        <a href="{{ route('admin.sales.history') }}" class="btn btn-outline-secondary">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Sales History -->
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <h5 class="card-title">Riwayat Penjualan Obat</h5>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Transaksi</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Apoteker</th>
                        <th>Total Item</th>
                        <th>Total Harga</th>
                        <th>Metode Pembayaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($sales ?? [] as $index => $sale)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $sale->sale_number }}</td>
                        <td>{{ $sale->sale_date->format('d-m-Y H:i') }}</td>
                        <td>
                            @if($sale->customer)
                                {{ $sale->customer->name }}
                            @else
                                <span class="text-muted">Walk-in Customer</span>
                            @endif
                        </td>
                        <td>{{ $sale->servedBy->name }}</td>
                        <td>{{ $sale->details->sum('quantity') }}</td>
                        <td>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $paymentLabels = [
                                    'cash' => 'Cash',
                                    'transfer' => 'Transfer',
                                    'credit_card' => 'Kartu Kredit',
                                    'debit_card' => 'Kartu Debit',
                                    'e_wallet' => 'E-Wallet',
                                    'qris' => 'QRIS'
                                ];
                            @endphp
                            {{ $paymentLabels[$sale->payment_method] ?? $sale->payment_method }}
                        </td>
                        <td>
                            @if($sale->status == 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($sale->status == 'refunded')
                                <span class="badge bg-danger">Refunded</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($sale->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item sale-detail-btn" href="{{ route('admin.sales.show', $sale->id) }}" data-sale-id="{{ $sale->id }}">
                                        <i class="bx bx-show-alt me-1"></i> Detail
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.sales.print', $sale->id) }}">
                                        <i class="bx bx-printer me-1"></i> Cetak Struk
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-3">Tidak ada data transaksi yang ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    @if(isset($sales))
                        Menampilkan <span class="fw-semibold">{{ $sales->count() }}</span> dari <span class="fw-semibold">{{ $sales->total() }}</span> transaksi
                    @else
                        Menampilkan <span class="fw-semibold">0</span> dari <span class="fw-semibold">0</span> transaksi
                    @endif
                </div>
                @if(isset($sales))
                    {{ $sales->appends(request()->except('page'))->links() }}
                @endif
            </div>
        </div>
    </div>

    <!-- Sale Detail Modal -->
    <div class="modal fade" id="saleDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBodyContent">
                    <div class="text-center p-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data transaksi...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="printReceiptBtn">Cetak Struk</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit form on select/input changes
        const filterInputs = document.querySelectorAll('#date_start, #date_end, #payment_method');
        filterInputs.forEach(input => {
            input.addEventListener('change', () => {
                document.getElementById('search-form').submit();
            });
        });
        
        // Setup for sale detail modal with AJAX
        const saleDetailBtns = document.querySelectorAll('.sale-detail-btn');
        const modalBodyContent = document.getElementById('modalBodyContent');
        const printReceiptBtn = document.getElementById('printReceiptBtn');
        let currentSaleId = null;

        saleDetailBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const saleId = this.getAttribute('data-sale-id');
                currentSaleId = saleId;
                
                // Show modal with loading state
                const modal = new bootstrap.Modal(document.getElementById('saleDetailModal'));
                modal.show();
                
                // Fetch sale details with AJAX
                fetch(`{{ url('admin/sales') }}/${saleId}/details`)
                    .then(response => response.json())
                    .then(data => {
                        // Format payment method
                        const paymentLabels = {
                            'cash': 'Cash',
                            'transfer': 'Transfer',
                            'credit_card': 'Kartu Kredit',
                            'debit_card': 'Kartu Debit',
                            'e_wallet': 'E-Wallet',
                            'qris': 'QRIS'
                        };
                        
                        // Format status badge
                        let statusBadgeClass = 'bg-secondary';
                        if (data.sale.status === 'completed') {
                            statusBadgeClass = 'bg-success';
                        } else if (data.sale.status === 'refunded') {
                            statusBadgeClass = 'bg-danger';
                        }
                        
                        // Generate item rows
                        let itemRows = '';
                        data.details.forEach((item, index) => {
                            itemRows += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.product_name}</td>
                                    <td>${item.quantity}</td>
                                    <td>Rp ${new Intl.NumberFormat('id-ID').format(item.unit_price)}</td>
                                    <td>Rp ${new Intl.NumberFormat('id-ID').format(item.total_price)}</td>
                                </tr>
                            `;
                        });
                        
                        // Update modal content
                        modalBodyContent.innerHTML = `
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p><strong>No. Transaksi:</strong> <span id="detail-trx-number">${data.sale.sale_number}</span></p>
                                    <p><strong>Tanggal:</strong> <span id="detail-date">${data.sale.formatted_date}</span></p>
                                    <p><strong>Pelanggan:</strong> <span id="detail-customer">${data.customer_name}</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Apoteker:</strong> <span id="detail-apoteker">${data.apoteker_name}</span></p>
                                    <p><strong>Metode Pembayaran:</strong> <span id="detail-payment">${paymentLabels[data.sale.payment_method] || data.sale.payment_method}</span></p>
                                    <p><strong>Status:</strong> <span id="detail-status" class="badge ${statusBadgeClass}">${data.sale.status.charAt(0).toUpperCase() + data.sale.status.slice(1)}</span></p>
                                </div>
                            </div>
                            <h6 class="fw-bold mb-3">Item yang Dibeli:</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Obat</th>
                                            <th>Jumlah</th>
                                            <th>Harga Satuan</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail-items">
                                        ${itemRows}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-end fw-bold">Total</td>
                                            <td class="fw-bold" id="detail-total">Rp ${new Intl.NumberFormat('id-ID').format(data.sale.total_amount)}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            ${data.sale.notes ? `
                            <div class="mt-3">
                                <h6 class="fw-bold mb-2">Catatan:</h6>
                                <p class="mb-0">${data.sale.notes}</p>
                            </div>
                            ` : ''}
                            ${data.sale.status === 'refunded' ? `
                            <div class="alert alert-danger mt-3">
                                <i class="bx bx-info-circle me-1"></i>
                                <strong>Transaksi ini telah di-refund</strong> pada ${data.refund_date}
                                ${data.refund_reason ? `<p class="mb-0 mt-1"><strong>Alasan:</strong> ${data.refund_reason}</p>` : ''}
                            </div>
                            ` : ''}
                        `;
                        
                        // Update print receipt button URL
                        printReceiptBtn.setAttribute('data-sale-id', saleId);
                    })
                    .catch(error => {
                        modalBodyContent.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="bx bx-error-circle me-1"></i>
                                Terjadi kesalahan saat memuat data. Silakan coba lagi.
                            </div>
                        `;
                        console.error('Error fetching sale details:', error);
                    });
            });
        });
        
        // Print Receipt Button
        printReceiptBtn.addEventListener('click', function() {
            if (currentSaleId) {
                window.open(`{{ url('apoteker/sales') }}/${currentSaleId}/print`, '_blank');
            }
        });
    });
</script>
@endsection 