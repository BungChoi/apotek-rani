@extends('layouts.app')

@section('title', 'Laporan Pengguna - Apotek')

@push('styles')
<link rel="stylesheet" href="{{ asset('sneat-assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('sneat-assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('sneat-assets/vendor/libs/apex-charts/apex-charts.css') }}" />
@endpush

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Laporan /</span> Pengguna
</h4>

<div class="row">
    <!-- Filter Card -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Filter Laporan</h5>
            </div>
            <div class="card-body">
                <form id="reportFilterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Role Pengguna</label>
                            <select class="form-select" name="role">
                                <option value="">Semua Role</option>
                                <option value="admin">Admin</option>
                                <option value="apoteker">Apoteker</option>
                                <option value="pelanggan">Pelanggan</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="">Semua Status</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="start_date">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" name="end_date">
                        </div>
                        <div class="col-12 text-end">
                            <button type="button" class="btn btn-secondary me-2" onclick="resetFilters()">Reset</button>
                            <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="card-info">
                        <p class="card-text">Total Pengguna</p>
                        <div class="d-flex align-items-end mb-2">
                            <h4 class="card-title mb-0 me-2">45</h4>
                        </div>
                        <small>12% <i class="bx bx-up-arrow-alt text-success"></i> dari bulan lalu</small>
                    </div>
                    <div class="card-icon">
                        <span class="badge bg-label-primary rounded p-2">
                            <i class="bx bx-user bx-md"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="card-info">
                        <p class="card-text">Admin</p>
                        <div class="d-flex align-items-end mb-2">
                            <h4 class="card-title mb-0 me-2">5</h4>
                        </div>
                        <small>Stabil sejak bulan lalu</small>
                    </div>
                    <div class="card-icon">
                        <span class="badge bg-label-success rounded p-2">
                            <i class="bx bx-user-check bx-md"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="card-info">
                        <p class="card-text">Apoteker</p>
                        <div class="d-flex align-items-end mb-2">
                            <h4 class="card-title mb-0 me-2">8</h4>
                        </div>
                        <small>25% <i class="bx bx-up-arrow-alt text-success"></i> dari bulan lalu</small>
                    </div>
                    <div class="card-icon">
                        <span class="badge bg-label-warning rounded p-2">
                            <i class="bx bx-briefcase bx-md"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="card-info">
                        <p class="card-text">Pelanggan</p>
                        <div class="d-flex align-items-end mb-2">
                            <h4 class="card-title mb-0 me-2">32</h4>
                        </div>
                        <small>15% <i class="bx bx-up-arrow-alt text-success"></i> dari bulan lalu</small>
                    </div>
                    <div class="card-icon">
                        <span class="badge bg-label-info rounded p-2">
                            <i class="bx bx-group bx-md"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart & Recent Users -->
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Statistik Pengguna</h5>
                <div class="dropdown">
                    <button class="btn p-0" type="button" id="userStatsDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userStatsDropdown">
                        <a class="dropdown-item" href="javascript:void(0);">Minggu Ini</a>
                        <a class="dropdown-item" href="javascript:void(0);">Bulan Ini</a>
                        <a class="dropdown-item" href="javascript:void(0);">Tahun Ini</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="userStatsChart" style="min-height: 400px;"></div>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Pengguna Baru</h5>
            </div>
            <div class="card-body">
                <ul class="p-0 m-0">
                    <li class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="bx bx-user"></i>
                            </span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <h6 class="mb-0">Ahmad Rifai</h6>
                                <small class="text-muted">Pelanggan</small>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                                <span class="badge bg-success">Baru</span>
                            </div>
                        </div>
                    </li>
                    <li class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="bx bx-user"></i>
                            </span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <h6 class="mb-0">Siti Rahayu</h6>
                                <small class="text-muted">Apoteker</small>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                                <span class="badge bg-success">Baru</span>
                            </div>
                        </div>
                    </li>
                    <li class="d-flex mb-4 pb-1">
                        <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="bx bx-user"></i>
                            </span>
                        </div>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <h6 class="mb-0">Budi Santoso</h6>
                                <small class="text-muted">Pelanggan</small>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                                <span class="badge bg-success">Baru</span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Daftar Pengguna</h5>
                <div class="d-flex">
                    <button class="btn btn-primary me-2">
                        <i class="bx bx-export me-1"></i> Export
                    </button>
                    <button class="btn btn-outline-secondary">
                        <i class="bx bx-printer me-1"></i> Print
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="usersTable">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Terakhir Login</th>
                                <th>Terdaftar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-3">
                                            <span class="avatar-initial rounded-circle bg-label-primary">JD</span>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Joko Darmawan</div>
                                        </div>
                                    </div>
                                </td>
                                <td>joko@example.com</td>
                                <td><span class="badge bg-primary">Admin</span></td>
                                <td><span class="badge bg-success">Aktif</span></td>
                                <td>{{ \Carbon\Carbon::now()->subHours(2)->format('d M Y H:i') }}</td>
                                <td>{{ \Carbon\Carbon::now()->subMonths(6)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-3">
                                            <span class="avatar-initial rounded-circle bg-label-success">AR</span>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Ani Resmi</div>
                                        </div>
                                    </div>
                                </td>
                                <td>ani@example.com</td>
                                <td><span class="badge bg-warning">Apoteker</span></td>
                                <td><span class="badge bg-success">Aktif</span></td>
                                <td>{{ \Carbon\Carbon::now()->subHours(5)->format('d M Y H:i') }}</td>
                                <td>{{ \Carbon\Carbon::now()->subMonths(8)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-3">
                                            <span class="avatar-initial rounded-circle bg-label-warning">BS</span>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Budi Santoso</div>
                                        </div>
                                    </div>
                                </td>
                                <td>budi@example.com</td>
                                <td><span class="badge bg-info">Pelanggan</span></td>
                                <td><span class="badge bg-success">Aktif</span></td>
                                <td>{{ \Carbon\Carbon::now()->subDays(1)->format('d M Y H:i') }}</td>
                                <td>{{ \Carbon\Carbon::now()->subDays(2)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-3">
                                            <span class="avatar-initial rounded-circle bg-label-info">SR</span>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Siti Rahayu</div>
                                        </div>
                                    </div>
                                </td>
                                <td>siti@example.com</td>
                                <td><span class="badge bg-warning">Apoteker</span></td>
                                <td><span class="badge bg-success">Aktif</span></td>
                                <td>{{ \Carbon\Carbon::now()->subHours(8)->format('d M Y H:i') }}</td>
                                <td>{{ \Carbon\Carbon::now()->subDays(5)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-3">
                                            <span class="avatar-initial rounded-circle bg-label-danger">DH</span>
                                        </div>
                                        <div>
                                            <div class="fw-bold">Dewi Handayani</div>
                                        </div>
                                    </div>
                                </td>
                                <td>dewi@example.com</td>
                                <td><span class="badge bg-info">Pelanggan</span></td>
                                <td><span class="badge bg-danger">Tidak Aktif</span></td>
                                <td>{{ \Carbon\Carbon::now()->subMonths(1)->format('d M Y H:i') }}</td>
                                <td>{{ \Carbon\Carbon::now()->subMonths(4)->format('d M Y') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('sneat-assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('sneat-assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
    $(function() {
        // Initialize DataTable
        $('#usersTable').DataTable({
            responsive: true,
            lengthMenu: [5, 10, 25, 50],
            language: {
                search: '',
                searchPlaceholder: 'Cari Pengguna...',
                paginate: {
                    previous: '<i class="bx bx-chevron-left"></i>',
                    next: '<i class="bx bx-chevron-right"></i>'
                }
            }
        });

        // User Statistics Chart
        const userStatsEl = document.querySelector('#userStatsChart');
        const userStatsConfig = {
            series: [{
                name: 'Admin',
                data: [3, 3, 4, 4, 5, 5, 5]
            }, {
                name: 'Apoteker',
                data: [5, 6, 6, 7, 7, 8, 8]
            }, {
                name: 'Pelanggan',
                data: [15, 18, 20, 24, 26, 28, 32]
            }],
            chart: {
                height: 400,
                type: 'line',
                toolbar: {
                    show: false
                }
            },
            colors: [config.colors.primary, config.colors.warning, config.colors.info],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            grid: {
                borderColor: config.colors.borderColor,
                padding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                }
            },
            markers: {
                size: 6,
                colors: 'transparent',
                strokeColors: 'transparent'
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    style: {
                        colors: config.colors.axisColor
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: config.colors.axisColor
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                offsetY: -10,
                markers: {
                    width: 10,
                    height: 10,
                    radius: 12
                },
                itemMargin: {
                    horizontal: 10
                }
            },
            tooltip: {
                shared: true
            }
        };

        if (userStatsEl) {
            const userStatsChart = new ApexCharts(userStatsEl, userStatsConfig);
            userStatsChart.render();
        }
    });

    // Reset filter form
    function resetFilters() {
        document.getElementById('reportFilterForm').reset();
    }
</script>
@endpush
