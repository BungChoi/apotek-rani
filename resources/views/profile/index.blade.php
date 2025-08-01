@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
    <div class="flex-grow-1">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Pengaturan Akun /</span> Profil
        </h4>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Detail Profil</h5>
                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                            <img src="{{ asset('sneat-assets/img/avatars/1.png') }}" alt="user-avatar"
                                class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                        </div>
                    </div>
                    <hr class="my-0" />

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <ul class="nav nav-pills" id="profileTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link {{ !request('tab') || request('tab') == 'personal' ? 'active' : '' }}"
                                            id="personal-tab" data-bs-toggle="tab" href="#personal" role="tab"
                                            aria-controls="personal"
                                            aria-selected="{{ !request('tab') || request('tab') == 'personal' ? 'true' : 'false' }}">
                                            <i class="bx bx-user me-1"></i> Informasi Pribadi
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request('tab') == 'security' ? 'active' : '' }}"
                                            id="security-tab" data-bs-toggle="tab" href="#security" role="tab"
                                            aria-controls="security"
                                            aria-selected="{{ request('tab') == 'security' ? 'true' : 'false' }}">
                                            <i class="bx bx-lock-alt me-1"></i> Keamanan
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="">
                            <!-- Tab Informasi Pribadi -->
                            <div class="tab-pane fade {{ !request('tab') || request('tab') == 'personal' ? 'show active' : '' }}"
                                id="personal" role="tabpanel" aria-labelledby="personal-tab">
                                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label for="name" class="form-label">Nama Lengkap</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                id="name" name="name" value="{{ old('name', $user->name) }}">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                id="email" name="email" value="{{ old('email', $user->email) }}">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="phone" class="form-label">Nomor Telepon</label>
                                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                                id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-md-12">
                                            <label for="address" class="form-label">Alamat</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Tab Keamanan -->
                            <div class="tab-pane fade {{ request('tab') == 'security' ? 'show active' : '' }}"
                                id="security" role="tabpanel" aria-labelledby="security-tab">
                                <form action="{{ route('profile.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="mb-3 col-md-6">
                                            <label for="current_password" class="form-label">Password Saat Ini</label>
                                            <input type="password"
                                                class="form-control @error('current_password') is-invalid @enderror"
                                                id="current_password" name="current_password">
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="password" class="form-label">Password Baru</label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                id="password" name="password">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Minimal 8 karakter</small>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label for="password_confirmation" class="form-label">Konfirmasi Password
                                                Baru</label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation">
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <button type="submit" class="btn btn-primary me-2">Perbarui Password</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
