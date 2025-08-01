@extends('layouts.public')

@section('title', 'Riwayat Transaksi - Apotek')

@section('styles')
    <style>
        .transactions-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .filter-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .transaction-card {
            border: 1px solid #e3e6f0;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .transaction-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
            text-align: center;
            min-width: 120px;
        }

        .status-completed {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-refunded {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .price-highlight {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .transaction-info {
            margin-bottom: 0.75rem;
        }

        .transaction-info .label {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        .transaction-info .value {
            font-weight: 600;
            color: #2c3e50;
        }

        .transaction-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 0.5rem;
        }

        @media (max-width: 768px) {
            .transaction-card {
                padding: 1rem;
            }

            .transaction-actions {
                justify-content: center;
                margin-top: 1rem;
            }

            .status-badge {
                min-width: auto;
                padding: 6px 12px;
                font-size: 0.8rem;
            }

            .price-highlight {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 576px) {

            .transactions-container,
            .filter-card {
                padding: 1rem;
                margin-left: -0.5rem;
                margin-right: -0.5rem;
            }
        }
    </style>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('home') }}" class="text-decoration-none">
            Home
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('public.profile') }}" class="text-decoration-none">Profil</a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Riwayat Transaksi</li>
@endsection

@section('content')
    <!-- Filter Section -->
    <section class="py-4">
        <div class="container">
            <div class="filter-card">
                <h4 class="section-title mb-3">Filter Transaksi</h4>
                <form method="GET" action="{{ route('public.transactions') }}" class="row g-3 align-items-end">
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select" name="status">
                            <option value="">Semua Status</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                Selesai
                            </option>
                            <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>
                                Dikembalikan
                            </option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-semibold">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label class="form-label fw-semibold">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bx bx-filter"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Transactions Section -->
    <section class="pb-5">
        <div class="container">
            <div class="transactions-container">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
                    <h3 class="section-title mb-2 mb-md-0">Riwayat Transaksi</h3>
                    <div class="text-muted">
                        <i class="bx bx-receipt"></i>
                        {{ $sales->total() }} transaksi ditemukan
                    </div>
                </div>

                @if ($sales->count() > 0)
                    <!-- Sales List -->
                    @foreach ($sales as $sale)
                        <div class="transaction-card">
                            <!-- Desktop Layout -->
                            <div class="d-none d-lg-block">
                                <div class="row align-items-center">
                                    <div class="col-lg-2">
                                        <div class="transaction-info">
                                            <div class="label">ID Transaksi</div>
                                            <div class="value">#{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="transaction-info">
                                            <div class="label">Tanggal</div>
                                            <div class="value">{{ $sale->sale_date->format('d M Y') }}</div>
                                            <div class="small text-muted">{{ $sale->sale_date->format('H:i') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="transaction-info">
                                            <div class="label">Status</div>
                                            <span class="status-badge status-{{ $sale->status }}">
                                                @if ($sale->status === 'completed')
                                                    Selesai
                                                @else
                                                    Dikembalikan
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="transaction-info">
                                            <div class="label">Items</div>
                                            <div class="value">{{ $sale->saleDetails->sum('quantity') }} item</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="transaction-info">
                                            <div class="label">Total</div>
                                            <div class="price-highlight">Rp
                                                {{ number_format($sale->total_amount, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="transaction-actions">
                                            <a href="{{ route('public.transaction-detail', $sale->id) }}"
                                                class="btn btn-outline-primary btn-sm">
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile/Tablet Layout -->
                            <div class="d-lg-none">
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="transaction-info">
                                            <div class="label">ID Transaksi</div>
                                            <div class="value">#{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6 text-end">
                                        <span class="status-badge status-{{ $sale->status }}">
                                            @if ($sale->status === 'completed')
                                                Selesai
                                            @else
                                                Dikembalikan
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-6">
                                        <div class="transaction-info">
                                            <div class="label">Tanggal</div>
                                            <div class="value">{{ $sale->sale_date->format('d M Y') }}</div>
                                            <div class="small text-muted">{{ $sale->sale_date->format('H:i') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="transaction-info">
                                            <div class="label">Items</div>
                                            <div class="value">{{ $sale->saleDetails->sum('quantity') }} item</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="transaction-info">
                                            <div class="label">Total</div>
                                            <div class="price-highlight">Rp
                                                {{ number_format($sale->total_amount, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="transaction-actions">
                                    <a href="{{ route('public.transaction-detail', $sale->id) }}"
                                        class="btn btn-outline-primary btn-sm">
                                        Detail
                                    </a>
                                </div>
                            </div>

                            @if ($sale->notes)
                                <div class="row mt-3 pt-3 border-top">
                                    <div class="col-12">
                                        <div class="transaction-info">
                                            <div class="label">Catatan:</div>
                                            <div class="small text-muted">{{ $sale->notes }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    @if ($sales->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            <nav aria-label="Page navigation">
                                <ul class="pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($sales->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">Previous</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ $sales->appends(request()->query())->previousPageUrl() }}">Previous</a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($sales->getUrlRange(1, $sales->lastPage()) as $page => $url)
                                        @if ($page == $sales->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link"
                                                    href="{{ $sales->appends(request()->query())->url($page) }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($sales->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="{{ $sales->appends(request()->query())->nextPageUrl() }}">Next</a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">Next</span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>

                        <!-- Page Info -->
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                Halaman {{ $sales->currentPage() }} dari {{ $sales->lastPage() }} •
                                {{ $sales->total() }} transaksi total
                            </small>
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="empty-state">
                        <i class="bx bx-receipt"></i>
                        <h4>Belum Ada Transaksi</h4>
                        <p class="mb-4">Anda belum memiliki riwayat transaksi. Mulai berbelanja sekarang!</p>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            Mulai Berbelanja
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
