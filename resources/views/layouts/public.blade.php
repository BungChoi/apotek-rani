<!DOCTYPE html>
@php
    use Illuminate\Support\Facades\Session;
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Apotek Online')</title>
    
    <!-- Bootstrap CSS (Local) -->
    <link href="{{ asset('sneat-assets/vendor/css/core.css') }}" rel="stylesheet">
    <!-- Font Awesome (Local) -->
    <link href="{{ asset('sneat-assets/vendor/fonts/iconify-icons.css') }}" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            padding: 1rem 0;
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #667eea !important;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            padding: 10px;
        }

        .dropdown-item {
            border-radius: 10px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50px;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            transform: translateY(-2px);
        }

        .btn-outline-secondary {
            border: 2px solid #6c757d;
            color: #6c757d;
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-secondary:hover {
            background: #6c757d;
            border-color: #6c757d;
            color: white;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
            border-radius: 50px;
            padding: 15px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            outline: none;
            border-color: #667eea;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control, .form-select {
            transition: all 0.3s ease;
            background-color: #ffffff;
            border-radius: 10px;
            padding: 12px 15px;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin-top: 100px;
        }

        .section-title {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 2rem;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }

        .alert {
            border-radius: 15px;
            border: none;
            padding: 15px 20px;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }

        .pagination .page-link {
            border-radius: 8px;
            margin: 0 2px;
            border: 1px solid #e3e6f0;
            color: #667eea;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
            transform: translateY(-1px);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
        }

        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #f8f9fa;
            border-color: #e3e6f0;
        }

        /* Icon and text alignment fix */
        .icon-text-aligned {
            display: flex;
            align-items: center;
        }

        .icon-text-aligned i {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.2em;
            height: 1.2em;
        }

        /* Ensure icons in buttons and other elements are properly aligned */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn i {
            margin: 0 !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1em;
            line-height: 1;
        }
        
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .dropdown-item i {
            margin: 0 !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1em;
            line-height: 1;
        }
        
        /* Fix breadcrumb alignment */
        .breadcrumb-item {
            display: flex;
            align-items: center;
        }
        
        .breadcrumb-item i {
            vertical-align: middle;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Fix alert and status alignment */
        .alert {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .alert i {
            margin: 0 !important;
            margin-top: 0.1rem !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .status-badge i {
            margin: 0 !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Tab buttons alignment */
        .tab-button {
            display: flex !important;
            align-items: center;
            gap: 0.5rem;
        }
        
        .tab-button i {
            margin: 0 !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        @yield('styles')
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('sneat-assets/img/favicon/favicon.ico') }}" alt="" class="me-2">
                Apotek
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    @auth
                        @php
                            $cartItems = Session::get('cart', []);
                            $cartCount = count($cartItems);
                        @endphp
                        
                        <!-- Cart Link -->
                        <a href="{{ route('public.cart.index') }}" class="btn btn-outline-primary me-2 position-relative">
                            <i class="bx bx-cart me-1"></i>Keranjang
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                        
                        <div class="dropdown me-2">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                <i class="bx bx-user me-1"></i>{{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('public.profile') }}">
                                        <i class="bx bx-user me-2"></i>Profil
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('public.transactions') }}">
                                        <i class="bx bx-history me-2"></i>Riwayat Pembelian
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bx bx-log-out me-2"></i>Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">
                            <i class="bx bx-log-in me-1"></i>Login
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">
                                <i class="bx bx-user-plus me-1"></i>Register
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    @hasSection('breadcrumb')
        <!-- Breadcrumb -->
        <div class="container mt-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>
    @endif

    <!-- Main Content -->
    @yield('content')

    <!-- Bootstrap JS (Local) -->
    <script src="{{ asset('sneat-assets/vendor/js/bootstrap.js') }}"></script>
    
    <script>
        // Add smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>

    <script>
        @yield('scripts')
    </script>
</body>
</html> 