@extends('layouts.app')

@section('title', 'Manajemen Supplier - Apotek')

@push('styles')
    <link rel="stylesheet" href="{{ asset('sneat-assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('sneat-assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('sneat-assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
    <style>
        #suppliersTable {
            background-color: #fff;
        }
        #suppliersTable thead {
            background-color: #f5f5f9;
        }
        #suppliersTable thead th {
            color: #566a7f;
            font-weight: 600;
        }
        #suppliersTable tbody tr {
            background-color: #fff;
        }
        #suppliersTable tbody td {
            color: #697a8d;
        }
    </style>
@endpush

@section('content')
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Supplier /</span> Daftar Supplier
    </h4>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Daftar Supplier -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Supplier</h5>
                    <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i> Tambah Supplier
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="suppliersTable">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Supplier</th>
                                    <th>Kontak</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($suppliers as $index => $supplier)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $supplier->name }}</td>
                                        <td>{{ $supplier->contact_person }}</td>
                                        <td>{{ $supplier->phone }}</td>
                                        <td>{{ $supplier->email }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route('admin.suppliers.edit', $supplier->id) }}"
                                                    class="btn btn-sm btn-primary me-1" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Edit">
                                                    <i class="bx bx-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.suppliers.show', $supplier->id) }}"
                                                    class="btn btn-sm btn-info me-1" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Detail">
                                                    <i class="bx bx-info-circle"></i>
                                                </a>
                                                <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}"
                                                    method="POST" class="d-inline-block delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
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
    <script src="{{ asset('sneat-assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#suppliersTable').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/2.0.0/i18n/id.json'
                },
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-end"f>><"row"<"col-sm-12"tr>><"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                lengthMenu: [10, 25, 50, 75, 100],
                pageLength: 10
            });

            // Delete confirmation
            $('.delete-btn').on('click', function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data supplier akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
