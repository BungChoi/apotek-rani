@extends('layouts.public')

@section('title', 'Detail Transaksi #' . str_pad($sale->id, 6, '0', STR_PAD_LEFT) . ' - Apotek')

@section('styles')
.transaction-container {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
}

.icon-text-aligned {
    display: flex;
    align-items: center;
}

.icon-text-aligned i {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 1.2em;
    height: 1.2em;
}

.status-badge {
    padding: 10px 20px;
    border-radius: 50px;
    font-size: 1rem;
    font-weight: 600;
    display: inline-block;
}

.status-completed {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

.status-refunded {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
}

.info-card {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid #e3e6f0;
}

.transaction-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 20px;
    margin-bottom: 2rem;
    text-align: center;
}

.transaction-id {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.table {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1rem;
    font-weight: 600;
}

.table tbody td {
    padding: 1rem;
    border-color: #e3e6f0;
    vertical-align: middle;
}

.product-image-sm {
    width: 60px;
    height: 60px;
    object-fit: contain;
    background: #f8f9fa;
    border-radius: 10px;
    padding: 5px;
}

.total-amount {
    font-size: 1.5rem;
    font-weight: 700;
    color: #ffffff !important;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    padding: 10px 20px;
}

.total-label {
    color: #6c757d !important;
    font-weight: 600;
}
@endsection

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ route('home') }}" class="text-decoration-none">
        Home
    </a>
</li>
<li class="breadcrumb-item">
    <a href="{{ route('public.transactions') }}" class="text-decoration-none">Riwayat Transaksi</a>
</li>
<li class="breadcrumb-item active" aria-current="page">Detail Transaksi</li>
@endsection

@section('content')
<!-- Transaction Detail Section -->
<section class="py-4">
    <div class="container">
        <!-- Success Alert -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Transaction Header -->
        <div class="transaction-header">
            <div class="transaction-id">#{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</div>
            <p class="mb-2">{{ $sale->sale_date->format('d F Y, H:i') }} WIB</p>
            <span class="status-badge status-{{ $sale->status }}">
                @if($sale->status === 'completed')
                    Transaksi Selesai
                @else
                    Transaksi Dikembalikan
                @endif
            </span>
        </div>

        <div class="transaction-container">
            <!-- Transaction Info -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="info-card">
                        <h6 class="fw-bold mb-3 icon-text-aligned">
                            <i class="bx bx-user text-primary me-2"></i>Informasi Pembeli
                        </h6>
                        <div class="mb-2">
                            <strong>Nama:</strong> {{ $sale->customer->name }}
                        </div>
                        <div class="mb-2">
                            <strong>Email:</strong> {{ $sale->customer->email }}
                        </div>
                        @if($sale->customer->phone)
                            <div class="mb-2">
                                <strong>Telepon:</strong> {{ $sale->customer->phone }}
                            </div>
                        @endif
                        @if($sale->customer->address)
                            <div>
                                <strong>Alamat:</strong> {{ $sale->customer->address }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-card">
                        <h6 class="fw-bold mb-3 icon-text-aligned">
                            <i class="bx bx-receipt text-success me-2"></i>Informasi Transaksi
                        </h6>
                        <div class="mb-2">
                            <strong>ID Transaksi:</strong> #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}
                        </div>
                        <div class="mb-2">
                            <strong>Tanggal:</strong> {{ $sale->sale_date->format('d F Y, H:i') }} WIB
                        </div>
                        <div class="mb-2">
                            <strong>Metode Pembayaran:</strong>
                            <span class="badge bg-info ms-2">
                                @if($sale->payment_method === 'cash')
                                    Tunai
                                @elseif($sale->payment_method === 'transfer')
                                    Transfer
                                @elseif($sale->payment_method === 'credit_card')
                                    Kartu Kredit
                                @elseif($sale->payment_method === 'debit_card')
                                    Kartu Debit
                                @elseif($sale->payment_method === 'e_wallet')
                                    E-Wallet
                                @elseif($sale->payment_method === 'qris')
                                    QRIS
                                @endif
                            </span>
                        </div>
                        <div class="mb-2">
                            <strong>Status:</strong>
                            <span class="status-badge status-{{ $sale->status }} ms-2">
                                @if($sale->status === 'completed')
                                    Selesai
                                @else
                                    Dikembalikan
                                @endif
                            </span>
                        </div>
                        @if($sale->notes)
                            <div>
                                <strong>Catatan:</strong> {{ $sale->notes }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Products Table -->
            <h5 class="section-title">Detail Produk</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga Satuan</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sale->saleDetails as $detail)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($detail->product && $detail->product->image)
                                            <img src="{{ asset('storage/' . $detail->product->image) }}" 
                                                 class="product-image-sm me-3" 
                                                 alt="{{ $detail->product->name }}">
                                        @else
                                            <div class="product-image-sm me-3 d-flex align-items-center justify-content-center">
                                                <i class="bx bx-plus-medical text-primary"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $detail->product ? $detail->product->name : 'Produk tidak ditemukan' }}</div>
                                            @if($detail->product && $detail->product->supplier)
                                                <small class="text-muted">{{ $detail->product->supplier->name }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $detail->getFormattedUnitPrice() }}</td>
                                <td>{{ $detail->quantity }} unit</td>
                                <td class="fw-bold">{{ $detail->getFormattedTotalPrice() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="3" class="text-end total-label">Total Keseluruhan:</td>
                            <td class="total-amount">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('public.transactions') }}" class="btn btn-outline-secondary icon-text-aligned">
                    <i class="bx bx-arrow-back"></i>Kembali ke Riwayat
                </a>
                <a href="{{ route('home') }}" class="btn btn-primary icon-text-aligned">
                    <i class="bx bx-shopping-bag"></i>Belanja Lagi
                </a>
            </div>
        </div>
    </div>
</section>
@endsection 