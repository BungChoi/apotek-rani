@extends('layouts.app')

@section('title', 'Detail Transaksi Penjualan')

@section('content')
<div class="flex-grow-1">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Penjualan /</span> Detail Transaksi
    </h4>

    <div class="row">
        <!-- Transaction Info -->
        <div class="w-full">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Informasi Transaksi</h5>
                    <div>
                        <a href="{{ route('admin.sales.print', $sale->id) }}" class="btn btn-outline-primary btn-sm me-2" target="_blank">
                             Cetak Struk
                        </a>
                        <a href="{{ route('admin.sales.history') }}" class="btn btn-outline-secondary btn-sm">
                           Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="small text-muted mb-1">No. Transaksi</h6>
                                <p class="mb-0 fw-semibold">{{ $sale->sale_number }}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-muted mb-1">Tanggal Transaksi</h6>
                                <p class="mb-0">{{ $sale->sale_date->format('d-m-Y H:i') }}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-muted mb-1">Status</h6>
                                @if($sale->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($sale->status == 'refunded')
                                    <span class="badge bg-danger">Refunded</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($sale->status) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="small text-muted mb-1">Pelanggan</h6>
                                <p class="mb-0">
                                    @if($sale->customer)
                                        {{ $sale->customer->name }}
                                    @else
                                        <span class="text-muted">Walk-in Customer</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-muted mb-1">Dilayani Oleh</h6>
                                <p class="mb-0">{{ $sale->servedBy->name }}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="small text-muted mb-1">Metode Pembayaran</h6>
                                <p class="mb-0">
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
                            </div>
                        </div>
                    </div>

                    @if($sale->notes)
                    <div class="mb-4">
                        <h6 class="small text-muted mb-1">Catatan</h6>
                        <p class="mb-0">{{ $sale->notes }}</p>
                    </div>
                    @endif

                    <h6 class="fw-bold mb-3">Item yang Dibeli</h6>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Harga Satuan</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sale->details as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($detail->product)
                                            {{ $detail->product->name }}
                                        @else
                                            <span class="text-muted">{{ $detail->product_name_snapshot ?? 'Produk tidak tersedia' }}</span>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>Rp {{ number_format($detail->total_price, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-3">Tidak ada detail transaksi</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Total</td>
                                    <td class="fw-bold">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Dibuat pada: {{ $sale->created_at->format('d-m-Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
