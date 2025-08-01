@extends('layouts.app')

@section('title', 'Laporan Penjualan - Apoteker')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Laporan Penjualan</h5>
        <div>
            <a href="#" onclick="window.print()" class="btn btn-primary btn-sm">
                <i class="bx bx-printer me-1"></i> Cetak Laporan
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Date Range Filter -->
        <form action="{{ route('apoteker.reports.sales') }}" method="GET" class="mb-4 row g-2">
            <div class="col-md-4">
                <label class="form-label" for="start_date">Dari Tanggal</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ $start_date ?? '' }}">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="end_date">Sampai Tanggal</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ $end_date ?? '' }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Filter</button>
                <a href="{{ route('apoteker.reports.sales') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title text-white">Total Pendapatan</h5>
                        <h2 class="mb-0">Rp {{ number_format($total_revenue ?? 0, 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title text-white">Total Transaksi</h5>
                        <h2 class="mb-0">{{ number_format($total_transactions ?? 0, 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title text-white">Total Item Terjual</h5>
                        <h2 class="mb-0">{{ number_format($total_items_sold ?? 0, 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Tanggal</th>
                        <th>Petugas</th>
                        <th>Pelanggan</th>
                        <th>Total Item</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($sales) && $sales->count() > 0)
                        @foreach($sales as $sale)
                        <tr>
                            <td>{{ $sale->invoice_number }}</td>
                            <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}</td>
                            <td>{{ $sale->servedBy->name }}</td>
                            <td>{{ $sale->customer_name ?: 'Umum' }}</td>
                            <td>{{ $sale->saleDetails->sum('quantity') }}</td>
                            <td>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                            <td>
                                @if($sale->status == 'completed')
                                <span class="badge bg-success">Selesai</span>
                                @elseif($sale->status == 'refunded')
                                <span class="badge bg-danger">Refund</span>
                                @else
                                <span class="badge bg-secondary">{{ ucfirst($sale->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('apoteker.sales.show', $sale->id) }}" class="btn btn-sm btn-info">
                                    <i class="bx bx-detail"></i>
                                </a>
                                <a href="{{ route('apoteker.sales.print', $sale->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bx bx-printer"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="text-center py-3">Tidak ada data penjualan dalam periode ini</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
