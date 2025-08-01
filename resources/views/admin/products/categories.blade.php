@extends('layouts.app')

@section('title', 'Kategori Produk - Apotek')

@push('styles')
<link rel="stylesheet" href="{{ asset('sneat-assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('sneat-assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('sneat-assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endpush

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Produk /</span> Kategori
</h4>

<!-- Kategori Produk Card -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Kategori Produk</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            <i class="bx bx-plus me-1"></i> Tambah Kategori
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="categoriesTable">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Jumlah Produk</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Obat Keras</td>
                        <td>Obat yang hanya bisa diberikan dengan resep dokter</td>
                        <td><span class="badge bg-primary">24</span></td>
                        <td>
                            <div class="d-flex">
                                <button type="button" class="btn btn-sm btn-icon btn-primary me-2" 
                                        data-bs-toggle="modal" data-bs-target="#editCategoryModal">
                                    <i class="bx bx-edit text-white"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-danger" 
                                        onclick="confirmDelete(1)">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Obat Bebas</td>
                        <td>Obat yang dapat dibeli tanpa resep dokter</td>
                        <td><span class="badge bg-primary">36</span></td>
                        <td>
                            <div class="d-flex">
                                <button type="button" class="btn btn-sm btn-icon btn-primary me-2" 
                                        data-bs-toggle="modal" data-bs-target="#editCategoryModal">
                                    <i class="bx bx-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-danger" 
                                        onclick="confirmDelete(2)">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Obat Bebas Terbatas</td>
                        <td>Obat bebas dengan penggunaan terbatas</td>
                        <td><span class="badge bg-primary">18</span></td>
                        <td>
                            <div class="d-flex">
                                <button type="button" class="btn btn-sm btn-icon btn-primary me-2" 
                                        data-bs-toggle="modal" data-bs-target="#editCategoryModal">
                                    <i class="bx bx-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-danger" 
                                        onclick="confirmDelete(3)">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Suplemen</td>
                        <td>Produk yang berfungsi sebagai suplemen kesehatan</td>
                        <td><span class="badge bg-primary">42</span></td>
                        <td>
                            <div class="d-flex">
                                <button type="button" class="btn btn-sm btn-icon btn-primary me-2" 
                                        data-bs-toggle="modal" data-bs-target="#editCategoryModal">
                                    <i class="bx bx-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-danger" 
                                        onclick="confirmDelete(4)">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>Alat Kesehatan</td>
                        <td>Alat-alat untuk menunjang kesehatan</td>
                        <td><span class="badge bg-primary">15</span></td>
                        <td>
                            <div class="d-flex">
                                <button type="button" class="btn btn-sm btn-icon btn-primary me-2" 
                                        data-bs-toggle="modal" data-bs-target="#editCategoryModal">
                                    <i class="bx bx-edit"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-icon btn-danger" 
                                        onclick="confirmDelete(5)">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kategori Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addCategoryForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="categoryName" name="name" required />
                    </div>
                    <div class="mb-3">
                        <label for="categoryDescription" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="categoryDescription" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCategoryForm">
                <div class="modal-body">
                    <input type="hidden" id="editCategoryId" name="id" />
                    <div class="mb-3">
                        <label for="editCategoryName" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="editCategoryName" name="name" value="Obat Keras" required />
                    </div>
                    <div class="mb-3">
                        <label for="editCategoryDescription" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="editCategoryDescription" name="description" rows="3">Obat yang hanya bisa diberikan dengan resep dokter</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('sneat-assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('sneat-assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
<script>
    $(function() {
        $('#categoriesTable').DataTable({
            responsive: true,
            lengthMenu: [5, 10, 25, 50],
            language: {
                search: '',
                searchPlaceholder: 'Cari Kategori...',
                paginate: {
                    previous: '<i class="bx bx-chevron-left"></i>',
                    next: '<i class="bx bx-chevron-right"></i>'
                }
            }
        });

        // Form submit handlers (dalam aplikasi nyata akan mengirim AJAX request)
        $('#addCategoryForm').on('submit', function(e) {
            e.preventDefault();
            // Submit form logic would go here
            $('#addCategoryModal').modal('hide');
            showSuccessMessage('Kategori berhasil ditambahkan');
        });

        $('#editCategoryForm').on('submit', function(e) {
            e.preventDefault();
            // Submit form logic would go here
            $('#editCategoryModal').modal('hide');
            showSuccessMessage('Kategori berhasil diperbarui');
        });
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Kategori?',
            text: "Kategori yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-danger me-2',
                cancelButton: 'btn btn-outline-secondary'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.isConfirmed) {
                // Delete logic would go here
                showSuccessMessage('Kategori berhasil dihapus');
            }
        });
    }

    function showSuccessMessage(message) {
        Swal.fire({
            title: 'Berhasil!',
            text: message,
            icon: 'success',
            customClass: {
                confirmButton: 'btn btn-primary'
            },
            buttonsStyling: false
        });
    }
</script>
@endpush
