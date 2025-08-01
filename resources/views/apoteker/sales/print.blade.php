@extends('layouts.print')

@section('title', 'Cetak Struk')

@section('content')
<div class="receipt-container">
    <div class="text-center mb-3">
        <h4 class="mb-1">Apotek Sehat</h4>
        <p class="mb-1">Jl. Contoh No. 123, Jakarta</p>
        <p class="mb-0">Telp: (021) 1234567</p>
        <hr>
    </div>

    <div class="mb-3">
        <div class="d-flex justify-content-between mb-1">
            <span>No. Transaksi:</span>
            <span>{{ $sale->sale_number }}</span>
        </div>
        <div class="d-flex justify-content-between mb-1">
            <span>Tanggal:</span>
            <span>{{ $sale->sale_date->format('d-m-Y H:i') }}</span>
        </div>
        <div class="d-flex justify-content-between mb-1">
            <span>Kasir:</span>
            <span>{{ $sale->servedBy->name }}</span>
        </div>
        <div class="d-flex justify-content-between mb-1">
            <span>Pelanggan:</span>
            <span>{{ $sale->customer ? $sale->customer->name : 'Walk-in Customer' }}</span>
        </div>
    </div>

    <table class="table table-sm table-borderless receipt-items">
        <thead>
            <tr>
                <th>Item</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Harga</th>
                <th class="text-end">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->details as $detail)
            <tr>
                <td>{{ $detail->product ? $detail->product->name : ($detail->product_name_snapshot ?? 'Produk tidak tersedia') }}</td>
                <td class="text-center">{{ $detail->quantity }}</td>
                <td class="text-end">Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($detail->total_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-end">Subtotal</td>
                <td class="text-end">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="3" class="text-end">Pajak (0%)</td>
                <td class="text-end">Rp 0</td>
            </tr>
            <tr>
                <td colspan="3" class="text-end">Diskon</td>
                <td class="text-end">Rp 0</td>
            </tr>
            <tr>
                <td colspan="3" class="text-end fw-bold">Total</td>
                <td class="text-end fw-bold">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="text-center mt-3">
        <p class="mb-1">Metode Pembayaran: 
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
        </p>
        <p class="mb-3">Terima kasih atas kunjungan Anda!</p>
        <div class="barcode-container text-center">
            <!-- Barcode will be added here -->
        </div>
    </div>
</div>

<div class="d-print-none mt-4 text-center">
    <button type="button" class="btn btn-primary me-2" onclick="window.print()">
        <i class="bx bx-printer me-1"></i> Cetak
    </button>
    <a href="{{ URL::previous() }}" class="btn btn-outline-secondary">
        <i class="bx bx-arrow-back me-1"></i> Kembali
    </a>
</div>
@endsection

@section('page-style')
<style>
    @media print {
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        
        .receipt-container {
            width: 80mm;
            margin: 0 auto;
            padding: 5mm;
        }
        
        .d-print-none {
            display: none !important;
        }
        
        .table {
            width: 100%;
            margin-bottom: 1rem;
            border-collapse: collapse;
        }
        
        .table th, .table td {
            padding: 0.25rem;
        }
        
        .text-center {
            text-align: center !important;
        }
        
        .text-end {
            text-align: right !important;
        }
        
        .mb-0 {
            margin-bottom: 0 !important;
        }
        
        .mb-1 {
            margin-bottom: 0.25rem !important;
        }
        
        .mb-3 {
            margin-bottom: 1rem !important;
        }
        
        .mt-3 {
            margin-top: 1rem !important;
        }
        
        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 1rem 0;
        }
        
        .fw-bold {
            font-weight: bold !important;
        }
    }
    
    .receipt-container {
        max-width: 400px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #fff;
    }
    
    .receipt-items th, .receipt-items td {
        padding: 0.25rem;
    }
    
    .barcode-container {
        margin-top: 20px;
        text-align: center;
    }
</style>
@endsection
