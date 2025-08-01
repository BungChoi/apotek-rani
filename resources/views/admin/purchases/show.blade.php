@extends('layouts.app')

@section('title', 'Detail Pembelian')

@section('content')
<div class="flex-grow-1">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Pembelian /</span> Detail Pembelian
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

    <div class="row">
        <!-- Purchase Information -->
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Informasi Pembelian</h5>
                    <div>
                        <a href="{{ route('admin.purchases.history') }}" class="btn btn-outline-secondary me-2">
                            <i class="bx bx-arrow-back me-1"></i> Kembali
                        </a>
                        @if($purchase->status == 'ordered' || $purchase->status == 'received')
                            <a href="{{ route('admin.purchases.receive', $purchase->id) }}" class="btn btn-success me-2">
                                <i class="bx bx-package me-1"></i> Terima Barang
                            </a>
                        @endif
                        @if($purchase->status == 'ordered')
                            <a href="{{ route('admin.purchases.edit', $purchase->id) }}" class="btn btn-primary me-2">
                                <i class="bx bx-edit me-1"></i> Edit
                            </a>
                        @endif
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="bx bx-trash me-1"></i> Hapus
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="fw-semibold">Nomor Pembelian:</h6>
                                <p class="fs-5 fw-bold text-primary">{{ $purchase->purchase_number }}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="fw-semibold">Tanggal Pembelian:</h6>
                                <p>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d F Y') }}</p>
                            </div>
                            <div class="mb-3">
                                <h6 class="fw-semibold">Status:</h6>
                                <p>
                                    @if($purchase->status == 'completed')
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($purchase->status == 'ordered')
                                        <span class="badge bg-warning text-dark">Menunggu Penerimaan</span>
                                    @elseif($purchase->status == 'received')
                                        <span class="badge bg-info">Diterima Sebagian</span>
                                    @elseif($purchase->status == 'cancelled')
                                        <span class="badge bg-danger">Dibatalkan</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $purchase->status }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h6 class="fw-semibold">Supplier:</h6>
                                <p>
                                    <strong>{{ $purchase->supplier->name }}</strong><br>
                                    @if($purchase->supplier->phone)
                                        <i class="bx bx-phone me-1"></i> {{ $purchase->supplier->phone }}<br>
                                    @endif
                                    @if($purchase->supplier->address)
                                        <i class="bx bx-map me-1"></i> {{ $purchase->supplier->address }}
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <h6 class="fw-semibold">Metode Pembayaran:</h6>
                                <p>
                                    @if($purchase->payment_method == 'cash')
                                        <span class="badge bg-primary">Tunai</span>
                                    @elseif($purchase->payment_method == 'transfer')
                                        <span class="badge bg-info">Transfer</span>
                                    @elseif($purchase->payment_method == 'credit')
                                        <span class="badge bg-warning text-dark">Kredit</span>
                                    @elseif($purchase->payment_method == 'check')
                                        <span class="badge bg-secondary">Cek</span>
                                    @endif
                                </p>
                            </div>
                            <div class="mb-3">
                                <h6 class="fw-semibold">Dibuat Oleh:</h6>
                                <p>{{ $purchase->createdBy->name ?? 'Admin' }} ({{ \Carbon\Carbon::parse($purchase->created_at)->format('d M Y H:i') }})</p>
                            </div>
                        </div>
                    </div>

                    @if($purchase->notes)
                        <div class="alert alert-info">
                            <h6 class="fw-semibold mb-2">Catatan:</h6>
                            <p class="mb-0">{{ $purchase->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Purchase Items -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detail Produk</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="30%">Produk</th>
                                    <th width="10%">Harga Beli</th>
                                    <th width="10%">Jumlah</th>
                                    <th width="10%">Diterima</th>
                                    <th width="15%">Total</th>
                                    <th width="20%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchase->purchaseDetails as $index => $detail)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ $detail->product_name_snapshot }}</div>
                                            <small class="text-muted">
                                                @if($detail->batch_number)
                                                    <span class="badge bg-info">Batch: {{ $detail->batch_number }}</span>
                                                @endif
                                                @if($detail->expiry_date)
                                                    <span class="badge bg-warning text-dark">Exp: {{ \Carbon\Carbon::parse($detail->expiry_date)->format('d/m/Y') }}</span>
                                                @endif
                                            </small>
                                        </td>
                                        <td>{{ 'Rp ' . number_format($detail->unit_cost, 0, ',', '.') }}</td>
                                        <td>{{ $detail->quantity_ordered }}</td>
                                        <td>{{ $detail->quantity_received }}</td>
                                        <td class="fw-semibold text-primary">{{ 'Rp ' . number_format($detail->total_cost, 0, ',', '.') }}</td>
                                        <td>
                                            @if($detail->quantity_received >= $detail->quantity_ordered)
                                                <span class="badge bg-success">Diterima Penuh</span>
                                            @elseif($detail->quantity_received > 0)
                                                <span class="badge bg-info">Diterima Sebagian</span>
                                                <div class="progress mt-1" style="height: 6px;">
                                                    <div class="progress-bar" role="progressbar" 
                                                        style="width: {{ ($detail->quantity_received / $detail->quantity_ordered) * 100 }}%;" 
                                                        aria-valuenow="{{ ($detail->quantity_received / $detail->quantity_ordered) * 100 }}" 
                                                        aria-valuemin="0" 
                                                        aria-valuemax="100"></div>
                                                </div>
                                            @else
                                                <span class="badge bg-warning text-dark">Belum Diterima</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-3 text-muted">
                                            <i class="bx bx-package fs-1 d-block mb-2"></i>
                                            Tidak ada detail produk tersedia
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="5" class="text-end">Total Keseluruhan:</th>
                                    <th colspan="2" class="text-primary fw-bold">{{ 'Rp ' . number_format($purchase->total_amount, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data pembelian ini?</p>
                <p class="text-danger"><strong>Perhatian:</strong> Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait pembelian ini.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('admin.purchases.destroy', $purchase->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus Permanen</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .badge {
        font-size: 0.75em;
    }
    
    .progress-bar {
        background-color: #696cff;
    }
</style>
@endpush
