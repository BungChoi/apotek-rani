@extends('layouts.public')

@section('title', 'Profil Saya - Apotek')

@section('styles')
    <style>
        .profile-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
        }

        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            text-align: center;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2.5rem;
        }

        .tab-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 2rem;
            border-bottom: 1px solid #e3e6f0;
            padding-bottom: 1rem;
        }

        .tab-button {
            background: none;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: #6c757d;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 44px;
        }

        .tab-button i {
            font-size: 1.1rem;
        }

        .tab-button.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .tab-button:hover {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .tab-button.active:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .section-title {
            color: #495057;
            margin-bottom: 1.5rem;
            font-weight: 600;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 0.5rem;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .password-requirements {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0 8px 8px 0;
        }

        .password-requirements h6 {
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .password-requirements ul {
            margin-bottom: 0;
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('home') }}" class="text-decoration-none">
            Home
        </a>
    </li>
    <li class="breadcrumb-item active" aria-current="page">Profil Saya</li>
@endsection

@section('content')
    <!-- Profile Section -->
    <section class="py-4">
        <div class="container">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class="bx bx-user"></i>
                </div>
                <h2 class="mb-2 text-white">{{ $user->name }}</h2>
                <p class="mb-0">{{ $user->email }}</p>
            </div>

            <!-- Alerts for Profile Updates -->
            @if (session('success') && !session('password_updated'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Alerts for Password Updates -->
            @if (session('password_updated'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>
                    {{ session('success') ?? 'Password berhasil diperbarui!' }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Profile-specific errors -->
            @if ($errors->has('name') || $errors->has('email') || $errors->has('phone') || $errors->has('address'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>
                    <ul class="mb-0">
                        @if($errors->has('name'))
                            <li>{{ $errors->first('name') }}</li>
                        @endif
                        @if($errors->has('email'))
                            <li>{{ $errors->first('email') }}</li>
                        @endif
                        @if($errors->has('phone'))
                            <li>{{ $errors->first('phone') }}</li>
                        @endif
                        @if($errors->has('address'))
                            <li>{{ $errors->first('address') }}</li>
                        @endif
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Password-specific errors -->
            @if ($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>
                    <ul class="mb-0">
                        @if($errors->has('current_password'))
                            <li>{{ $errors->first('current_password') }}</li>
                        @endif
                        @if($errors->has('password'))
                            <li>{{ $errors->first('password') }}</li>
                        @endif
                        @if($errors->has('password_confirmation'))
                            <li>{{ $errors->first('password_confirmation') }}</li>
                        @endif
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="profile-container">
                <!-- Tab Buttons -->
                <div class="tab-buttons">
                    <button type="button" class="tab-button {{ !($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation') || session('password_updated')) ? 'active' : '' }}" onclick="showTab('profile', this)">
                        <span>Informasi Profil</span>
                    </button>
                    <button type="button" class="tab-button {{ ($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation') || session('password_updated')) ? 'active' : '' }}" onclick="showTab('password', this)">
                        <span>Ubah Password</span>
                    </button>
                </div>

                <!-- Profile Tab -->
                <div id="profile-tab" class="tab-content {{ !($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation') || session('password_updated')) ? 'active' : '' }}">
                    <h3 class="section-title">Informasi Profil</h3>
                    <form method="POST" action="{{ route('public.profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label fw-semibold">Nomor Telepon</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                                    value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label fw-semibold">Alamat</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="Alamat lengkap">{{ old('address', $user->address) }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('public.transactions') }}" class="btn btn-outline-secondary">
                                Lihat Riwayat Transaksi
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Password Tab -->
                <div id="password-tab" class="tab-content {{ ($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation') || session('password_updated')) ? 'active' : '' }}">
                    <h3 class="section-title">Ubah Password</h3>
                    
                    <!-- Password Requirements -->
                    <div class="password-requirements">
                        <h6><i class="bx bx-info-circle"></i> Persyaratan Password:</h6>
                        <ul>
                            <li>Minimal 8 karakter</li>
                            <li>Mengandung huruf besar dan kecil</li>
                            <li>Mengandung angka</li>
                            <li>Mengandung karakter khusus (!@#$%^&*)</li>
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('public.password.update') }}" id="password-form" onsubmit="return validatePasswordForm()">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="current_password" class="form-label fw-semibold">Password Saat Ini</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label fw-semibold">Password Baru</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-lock"></i>Update Password
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="showTab('profile', document.querySelector('.tab-button'))">
                                <i class="bx bx-arrow-back"></i>Kembali
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Tab switching function
    function showTab(tabName, buttonElement) {
        console.log('Switching to tab:', tabName);
        
        // Hide all tab contents
        var allTabs = document.querySelectorAll('.tab-content');
        for (var i = 0; i < allTabs.length; i++) {
            allTabs[i].classList.remove('active');
        }
        
        // Remove active class from all buttons
        var allButtons = document.querySelectorAll('.tab-button');
        for (var i = 0; i < allButtons.length; i++) {
            allButtons[i].classList.remove('active');
        }
        
        // Show target tab
        var targetTab = document.getElementById(tabName + '-tab');
        if (targetTab) {
            targetTab.classList.add('active');
        }
        
        // Add active class to clicked button
        if (buttonElement) {
            buttonElement.classList.add('active');
        } else {
            // Find the correct button for this tab
            var targetButton = null;
            if (tabName === 'profile') {
                targetButton = document.querySelector('button[onclick*="profile"]');
            } else if (tabName === 'password') {
                targetButton = document.querySelector('button[onclick*="password"]');
            }
            if (targetButton) {
                targetButton.classList.add('active');
            }
        }
    }

    // Form validation
    function validatePasswordForm() {
        var currentPassword = document.getElementById('current_password').value;
        var newPassword = document.getElementById('password').value;
        var confirmPassword = document.getElementById('password_confirmation').value;

        if (!currentPassword || !newPassword || !confirmPassword) {
            alert('Semua field password harus diisi!');
            return false;
        }

        if (newPassword !== confirmPassword) {
            alert('Password baru dan konfirmasi password tidak sama!');
            return false;
        }

        if (newPassword.length < 8) {
            alert('Password baru minimal 8 karakter!');
            return false;
        }

        return true;
    }

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Initializing profile page tabs');
        
        // If there are password-related errors or success, show password tab
        @if($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation') || session('password_updated'))
            showTab('password');
        @endif

        // Auto-focus on first error field
        var errorField = document.querySelector('.is-invalid');
        if (errorField) {
            errorField.focus();
        }
    });
</script>
@endsection
