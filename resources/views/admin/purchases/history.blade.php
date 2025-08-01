@extends('layouts.app')

@section('title', 'Riwayat Pembelian')

@section('content')
<div class="flex-grow-1">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Pembelian /</span> Riwayat Pembelian
    </h4>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filter Card -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Filter Riwayat Pembelian</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.purchases.history') }}" method="GET" id="filter-form">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label" for="search">Pencarian</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="No. Pembelian" value="{{ request('search') }}">
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label class="form-label" for="supplier_id">Supplier</label>
                        <select class="form-select" id="supplier_id" name="supplier_id">
                            <option value="">Semua Supplier</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label class="form-label" for="status">Status Pembelian</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="ordered" {{ request('status') == 'ordered' ? 'selected' : '' }}>Menunggu Penerimaan</option>
                            <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Diterima Sebagian</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3">
                        <label class="form-label" for="date_range">Rentang Tanggal</label>
                        <input type="text" class="form-control date-range-picker" id="date_range" name="date_range" 
                               placeholder="DD/MM/YYYY - DD/MM/YYYY" value="{{ request('date_range') }}">
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12 text-end">
                        <a href="{{ route('admin.purchases.history') }}" class="btn btn-outline-secondary me-2">
                            <i class="bx bx-reset me-1"></i> Reset Filter
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-filter-alt me-1"></i> Terapkan Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Purchases List -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Daftar Riwayat Pembelian</h5>
            <a href="{{ route('admin.purchases.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Tambah Pembelian Baru
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead>
                        <tr>
                            <th width="15%">No. Pembelian</th>
                            <th width="15%">Tanggal</th>
                            <th width="20%">Supplier</th>
                            <th width="15%">Total</th>
                            <th width="10%">Status</th>
                            <th width="15%">Dibuat Oleh</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchases as $purchase)
                            <tr>
                                <td>
                                    <strong>{{ $purchase->purchase_number }}</strong>
                                    <div class="text-muted small">
                                        {{ $purchase->payment_method == 'cash' ? 'Tunai' : 
                                           ($purchase->payment_method == 'transfer' ? 'Transfer' : 
                                           ($purchase->payment_method == 'credit' ? 'Kredit' : 'Cek')) }}
                                    </div>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}
                                    <div class="text-muted small">
                                        {{ \Carbon\Carbon::parse($purchase->created_at)->format('H:i') }}
                                    </div>
                                </td>
                                <td>
                                    {{ $purchase->supplier->name ?? 'Data Supplier Tidak Ada' }}
                                    @if($purchase->supplier)
                                        <div class="text-muted small">
                                            <i class="bx bx-phone me-1"></i>{{ $purchase->supplier->phone ?? '-' }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-semibold text-primary">
                                        Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                                    </span>
                                    <div class="text-muted small">
                                        {{ $purchase->purchaseDetails->count() }} item
                                    </div>
                                </td>
                                <td>
                                    @if($purchase->status == 'ordered')
                                        <span class="badge bg-warning text-dark">Menunggu Penerimaan</span>
                                    @elseif($purchase->status == 'received')
                                        <span class="badge bg-info">Diterima Sebagian</span>
                                    @elseif($purchase->status == 'completed')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($purchase->status == 'cancelled')
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $purchase->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $purchase->createdBy->name ?? 'Admin' }}
                                    <div class="text-muted small">
                                        {{ \Carbon\Carbon::parse($purchase->created_at)->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-sm btn-icon dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="{{ route('admin.purchases.show', $purchase->id) }}">
                                                <i class="bx bx-show me-1"></i> Detail
                                            </a>
                                            
                                            
                                            @if($purchase->status == 'ordered' || $purchase->status == 'received')
                                                <a class="dropdown-item" href="{{ route('admin.purchases.receive', $purchase->id) }}">
                                                    <i class="bx bx-package me-1"></i> Terima Barang
                                                </a>
                                                
                                                <a class="dropdown-item" href="{{ route('admin.purchases.edit', $purchase->id) }}">
                                                    <i class="bx bx-edit me-1"></i> Edit
                                                </a>
                                                
                                                <a class="dropdown-item text-danger" href="#" 
                                                   onclick="event.preventDefault(); if(confirm('Apakah Anda yakin ingin membatalkan pembelian ini?')) document.getElementById('cancel-form-{{ $purchase->id }}').submit();">
                                                    <i class="bx bx-x-circle me-1"></i> Batalkan
                                                </a>
                                                <form id="cancel-form-{{ $purchase->id }}" action="{{ route('admin.purchases.update', $purchase->id) }}" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="cancelled">
                                                </form>
                                            @endif
                                            
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="#" 
                                               onclick="event.preventDefault(); if(confirm('Apakah Anda yakin ingin menghapus data pembelian ini?')) document.getElementById('delete-form-{{ $purchase->id }}').submit();">
                                                <i class="bx bx-trash me-1"></i> Hapus
                                            </a>
                                            <form id="delete-form-{{ $purchase->id }}" action="{{ route('admin.purchases.destroy', $purchase->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <img src="{{ asset('sneat-assets/img/illustrations/empty-box.png') }}" alt="Empty Data" class="mb-3" height="120">
                                    <h6 class="mt-2">Tidak ada data pembelian</h6>
                                    <p class="mb-0">Belum ada transaksi pembelian yang tercatat dalam sistem.</p>
                                    <div class="mt-3">
                                        <a href="{{ route('admin.purchases.create') }}" class="btn btn-primary">
                                            <i class="bx bx-plus me-1"></i> Tambah Pembelian Baru
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-3">
                {{ $purchases->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('sneat-assets/vendor/libs/flatpickr/flatpickr.min.css') }}">
<style>
    .dropdown-toggle::after {
        display: none !important;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('sneat-assets/vendor/libs/flatpickr/flatpickr.min.js') }}"></script>
<script>
    $(document).ready(function() {
        // Initialize date range picker
        $(".date-range-picker").flatpickr({
            mode: "range",
            dateFormat: "d/m/Y",
            allowInput: true,
            altInput: true,
            altFormat: "d M Y",
            maxDate: "today",
            locale: {
                firstDayOfWeek: 1,
                weekdays: {
                    shorthand: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
                    longhand: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"]
                },
                months: {
                    shorthand: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Ags", "Sep", "Okt", "Nov", "Des"],
                    longhand: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"]
                }
            }
        });
        
        // Quick filter buttons
        $('.quick-filter').click(function(e) {
            e.preventDefault();
            const period = $(this).data('period');
            let startDate, endDate;
            const today = new Date();
            
            switch(period) {
                case 'today':
                    startDate = endDate = today;
                    break;
                case 'yesterday':
                    startDate = endDate = new Date(today);
                    startDate.setDate(today.getDate() - 1);
                    break;
                case 'week':
                    endDate = new Date(today);
                    startDate = new Date(today);
                    startDate.setDate(today.getDate() - 7);
                    break;
                case 'month':
                    endDate = new Date(today);
                    startDate = new Date(today);
                    startDate.setMonth(today.getMonth() - 1);
                    break;
                case 'quarter':
                    endDate = new Date(today);
                    startDate = new Date(today);
                    startDate.setMonth(today.getMonth() - 3);
                    break;
                case 'year':
                    endDate = new Date(today);
                    startDate = new Date(today);
                    startDate.setFullYear(today.getFullYear() - 1);
                    break;
                default:
                    return;
            }
            
            // Format dates for flatpickr
            const formatDate = (date) => {
                const day = date.getDate().toString().padStart(2, '0');
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const year = date.getFullYear();
                return `${day}/${month}/${year}`;
            };
            
            const dateRangeValue = `${formatDate(startDate)} - ${formatDate(endDate)}`;
            $('.date-range-picker').val(dateRangeValue);
            
            // Submit form
            $('#filter-form').submit();
        });
        
        // Auto submit form when select changes
        $('#supplier_id, #status').change(function() {
            $('#filter-form').submit();
        });
    });
</script>
@endpush
