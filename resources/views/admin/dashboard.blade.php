@extends('layouts.app')

@section('title', 'Dashboard Admin - Apotek')

@push('styles')
    <link rel="stylesheet" href="{{ asset('sneat-assets/vendor/libs/apex-charts/apex-charts.css') }}" />
@endpush

@section('content')
    <div class="row">
        <!-- Statistik Card -->
        <div class="col-lg-4 col-md-4 order-1 mb-4">
            <div class="card h-100">
                <div class="card-body pb-3">
                    <div class="card-title d-flex align-items-start justify-content-between mb-3">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-package"></i>
                            </span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-2">Total Produk</span>
                    <h3 class="card-title mb-3">{{ rand(50, 200) }}</h3>
                    <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +{{ rand(5, 15) }}%</small>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 order-1 mb-4">
            <div class="card h-100">
                <div class="card-body pb-3">
                    <div class="card-title d-flex align-items-start justify-content-between mb-3">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-success">
                                <i class="bx bx-store"></i>
                            </span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-2">Total Supplier</span>
                    <h3 class="card-title mb-3">{{ rand(10, 30) }}</h3>
                    <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +{{ rand(1, 5) }}%</small>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 order-1 mb-4">
            <div class="card h-100">
                <div class="card-body pb-3">
                    <div class="card-title d-flex align-items-start justify-content-between mb-3">
                        <div class="avatar flex-shrink-0">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-user"></i>
                            </span>
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-2">Total Pengguna</span>
                    <h3 class="card-title mb-3">{{ rand(5, 20) }}</h3>
                    <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +{{ rand(1, 3) }}%</small>
                </div>
            </div>
        </div>

        <!-- Grafik Penjualan -->
        <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between pb-3">
                    <h5 class="card-title m-0 me-2">Statistik Penjualan</h5>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="salesStatistics" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="salesStatistics">
                            <a class="dropdown-item" href="javascript:void(0);">Minggu Ini</a>
                            <a class="dropdown-item" href="javascript:void(0);">Bulan Ini</a>
                            <a class="dropdown-item" href="javascript:void(0);">Tahun Ini</a>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-2">
                    <div id="salesChart"></div>
                </div>
            </div>
        </div>

        <!-- Tabel Produk Terlaris -->
        <div class="col-12 col-lg-4 order-3 order-md-2 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between pb-3">
                    <h5 class="card-title m-0 me-2">Produk Terlaris</h5>
                    <div class="dropdown">
                        <button class="btn p-0" type="button" id="topProducts" data-bs-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="topProducts">
                            <a class="dropdown-item" href="javascript:void(0);">Hari Ini</a>
                            <a class="dropdown-item" href="javascript:void(0);">Minggu Ini</a>
                            <a class="dropdown-item" href="javascript:void(0);">Bulan Ini</a>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-2">
                    <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-2">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="bx bx-capsule"></i>
                                </span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-1">Paracetamol</h6>
                                    <small class="text-muted d-block">Obat Sakit Kepala</small>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-2">
                                    <h6 class="mb-0">{{ rand(100, 300) }}</h6>
                                    <span class="text-muted">Terjual</span>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-2">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="bx bx-capsule"></i>
                                </span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-1">Amoxicillin</h6>
                                    <small class="text-muted d-block">Antibiotik</small>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-2">
                                    <h6 class="mb-0">{{ rand(80, 250) }}</h6>
                                    <span class="text-muted">Terjual</span>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-4 pb-2">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-warning">
                                    <i class="bx bx-capsule"></i>
                                </span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-1">Vitamin C</h6>
                                    <small class="text-muted d-block">Suplemen</small>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-2">
                                    <h6 class="mb-0">{{ rand(50, 200) }}</h6>
                                    <span class="text-muted">Terjual</span>
                                </div>
                            </div>
                        </li>
                        <li class="d-flex mb-1">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-danger">
                                    <i class="bx bx-capsule"></i>
                                </span>
                            </div>
                            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                <div class="me-2">
                                    <h6 class="mb-1">Omeprazole</h6>
                                    <small class="text-muted d-block">Obat Maag</small>
                                </div>
                                <div class="user-progress d-flex align-items-center gap-2">
                                    <h6 class="mb-0">{{ rand(40, 180) }}</h6>
                                    <span class="text-muted">Terjual</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('sneat-assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Grafik Penjualan
            let cardColor = config.colors.white;
            let headingColor = config.colors.headingColor;
            let axisColor = config.colors.axisColor;
            let borderColor = config.colors.borderColor;

            // Grafik Penjualan
            const salesChartEl = document.querySelector('#salesChart');
            const salesChartOptions = {
                series: [{
                    name: 'Penjualan',
                    data: [{{ rand(50, 80) }}, {{ rand(40, 70) }}, {{ rand(60, 90) }},
                        {{ rand(50, 80) }}, {{ rand(70, 100) }}, {{ rand(50, 80) }},
                        {{ rand(40, 70) }}
                    ]
                }],
                chart: {
                    height: 350,
                    type: 'line',
                    toolbar: {
                        show: false
                    }
                },
                colors: [config.colors.primary],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth'
                },
                grid: {
                    borderColor: borderColor,
                    padding: {
                        top: 0,
                        bottom: -8,
                        left: 20,
                        right: 20
                    }
                },
                legend: {
                    show: true,
                    horizontalAlign: 'left',
                    position: 'top',
                    markers: {
                        height: 8,
                        width: 8,
                        radius: 12,
                        offsetX: -3
                    },
                    labels: {
                        colors: axisColor
                    },
                    itemMargin: {
                        horizontal: 10
                    }
                },
                tooltip: {
                    theme: 'dark'
                },
                xaxis: {
                    categories: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                    labels: {
                        style: {
                            colors: axisColor
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: axisColor
                        }
                    }
                }
            };

            if (salesChartEl) {
                const salesChart = new ApexCharts(salesChartEl, salesChartOptions);
                salesChart.render();
            }
        });
    </script>
@endpush
