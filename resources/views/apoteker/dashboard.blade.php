@extends('layouts.app')

@section('title', 'Dashboard - Apoteker')

@section('content')
<!-- Quick Stats Cards -->
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img src="{{ asset('sneat-assets/img/icons/unicons/chart-success.png') }}" alt="sales" class="rounded" />
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Penjualan Hari Ini</span>
                <h3 class="card-title mb-2">Rp {{ number_format($todaySalesAmount, 0, ',', '.') }}</h3>
                <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> {{ $todaySalesCount }} Transaksi</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img src="{{ asset('sneat-assets/img/icons/unicons/wallet-info.png') }}" alt="products" class="rounded" />
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Total Produk</span>
                <h3 class="card-title text-nowrap mb-1">{{ number_format($totalProducts) }}</h3>
                <small class="text-info fw-semibold"><i class="bx bx-package"></i> {{ number_format($availableProducts) }} Tersedia</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img src="{{ asset('sneat-assets/img/icons/unicons/cc-warning.png') }}" alt="low stock" class="rounded" />
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Stok Rendah</span>
                <h3 class="card-title text-nowrap mb-2 text-warning">{{ number_format($lowStockProducts) }}</h3>
                <small class="text-warning fw-semibold"><i class="bx bx-error-circle"></i> Perlu Perhatian</small>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img src="{{ asset('sneat-assets/img/icons/unicons/cc-primary.png') }}" alt="expired" class="rounded" />
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Kadaluarsa</span>
                <h3 class="card-title text-nowrap mb-2 text-danger">{{ number_format($expiredProducts) }}</h3>
                <small class="text-danger fw-semibold"><i class="bx bx-time"></i> Perlu Dibuang</small>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Overview Section -->
<div class="row">
    <div class="w-full mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Penjualan 7 Hari Terakhir</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8 pe-md-0">
                        <div id="totalRevenueChart" style="height: 300px;">
                            <!-- Chart will render here -->
                        </div>
                    </div>
                    <div class="col-md-4 mt-3 mt-md-0">
                        <div class="border-start ps-md-4 h-100">
                            <div class="text-center">
                                <h6 class="mb-4 fw-semibold">Ringkasan Bulan Ini</h6>
                                <div class="mb-4">
                                    <h4 class="text-primary mb-1">Rp {{ number_format($monthlySalesAmount, 0, ',', '.') }}</h4>
                                    <small class="text-muted">Total Penjualan</small>
                                </div>
                                <div class="mb-4">
                                    <h5 class="text-success mb-1">{{ number_format($monthlySalesCount) }}</h5>
                                    <small class="text-muted">Total Transaksi</small>
                                </div>
                                <div class="mb-4">
                                    <h5 class="text-info mb-1">Rp {{ number_format($dailyAverage, 0, ',', '.') }}</h5>
                                    <small class="text-muted">Rata-rata/Hari</small>
                                </div>
                                <a href="{{ route('apoteker.reports.sales') }}" class="btn btn-primary">
                                    <i class="bx bx-bar-chart-alt-2 me-1"></i>Lihat Laporan Lengkap
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Tables Section -->
<div class="row">
    <!-- Recent Transactions -->
    <div class="col-lg-8 col-md-12 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0">Transaksi Terbaru</h5>
                <a href="{{ route('apoteker.sales.history') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-history me-1"></i>Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                @if($recentTransactions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-borderless">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3">No. Transaksi</th>
                                <th class="py-3">Pelanggan</th>
                                <th class="py-3">Tanggal</th>
                                <th class="py-3">Total</th>
                                <th class="py-3">Status</th>
                                <th class="py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTransactions as $transaction)
                            <tr>
                                <td class="py-3">{{ $transaction->sale_number }}</td>
                                <td class="py-3">{{ $transaction->customer ? $transaction->customer->name : 'Umum' }}</td>
                                <td class="py-3">{{ $transaction->sale_date->format('d M Y') }}</td>
                                <td class="py-3 fw-semibold">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                <td class="py-3">
                                    @if($transaction->status == 'completed')
                                    <span class="badge bg-label-success">Selesai</span>
                                    @elseif($transaction->status == 'refunded')
                                    <span class="badge bg-label-warning">Dikembalikan</span>
                                    @else
                                    <span class="badge bg-label-info">{{ ucfirst($transaction->status) }}</span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    <a href="{{ route('apoteker.sales.show', $transaction->id) }}" class="btn btn-sm btn-icon btn-primary">
                                        <i class="bx bx-show text-white"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bx bx-receipt text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="mb-0">Belum ada transaksi.</h6>
                    <p class="text-muted mb-0">Transaksi baru akan muncul di sini.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Top Selling Products -->
    <div class="col-lg-4 col-md-12 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title m-0">Produk Terlaris</h5>
                <a href="{{ route('apoteker.products.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-package me-1"></i>Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                @if($topProducts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-borderless">
                        <thead class="table-light">
                            <tr>
                                <th class="py-3">Produk</th>
                                <th class="py-3 text-center">Terjual</th>
                                <th class="py-3 text-center">Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $product)
                            <tr>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="36" height="36" class="me-2 rounded">
                                        @else
                                        <div class="avatar avatar-sm me-2 bg-label-primary">
                                            <span class="avatar-initial rounded">{{ substr($product->name, 0, 1) }}</span>
                                        </div>
                                        @endif
                                        <div>
                                            <span class="fw-medium d-block">{{ $product->name }}</span>
                                            <small class="text-muted">{{ $product->category->name ?? 'Tanpa Kategori' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 text-center fw-semibold">{{ $product->total_quantity }}</td>
                                <td class="py-3 text-center">
                                    @if($product->stock > 10)
                                    <span class="badge bg-label-success">{{ $product->stock }}</span>
                                    @elseif($product->stock > 0)
                                    <span class="badge bg-label-warning">{{ $product->stock }}</span>
                                    @else
                                    <span class="badge bg-label-danger">Habis</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bx bx-package text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="mb-0">Belum ada data penjualan produk.</h6>
                    <p class="text-muted mb-0">Data produk terlaris akan muncul di sini.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('sneat-assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const totalRevenueChartEl = document.querySelector('#totalRevenueChart');
        
        if (totalRevenueChartEl) {
            // Get chart data from PHP
            const chartData = @json($chartData);
            const labels = Object.keys(chartData).map(date => {
                const d = new Date(date);
                return d.getDate() + '/' + (d.getMonth() + 1);
            });
            const values = Object.values(chartData);
            
            const totalRevenueChart = new ApexCharts(totalRevenueChartEl, {
                series: [{
                    name: 'Penjualan',
                    data: values
                }],
                chart: {
                    height: 300,
                    type: 'area',
                    toolbar: { show: false },
                    fontFamily: 'inherit',
                    sparkline: {
                        enabled: false
                    }
                },
                colors: ['#696cff'],
                dataLabels: { 
                    enabled: false 
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.5,
                        opacityTo: 0.2,
                        stops: [0, 90, 100]
                    }
                },
                grid: {
                    borderColor: '#e7e7e7',
                    padding: {
                        left: 10,
                        right: 10
                    }
                },
                xaxis: {
                    categories: labels,
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    labels: {
                        style: {
                            colors: '#a1acb8',
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#a1acb8',
                            fontSize: '12px'
                        },
                        formatter: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    theme: 'dark',
                    y: {
                        formatter: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                },
                markers: {
                    size: 5,
                    colors: '#696cff',
                    strokeColors: '#fff',
                    strokeWidth: 2,
                    hover: {
                        size: 7
                    }
                }
            });
            
            totalRevenueChart.render();
        }
    });
</script>
@endpush 