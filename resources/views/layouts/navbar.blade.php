<!-- Navbar -->
@php
    use Illuminate\Support\Facades\Session;
@endphp
<nav class="layout-navbar container-fluid navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar" style="padding: 0; margin: 0;">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none" style="padding-left: 1rem;">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <!-- Welcome Message -->
    <div class="navbar-nav flex-grow-1 d-none d-md-block" style="padding-left: 1rem;">
        <span class="navbar-text text-muted text-nowrap font-semibold">
            Selamat datang, <span class="fw-bold text-primary">{{ Auth::user()->name }}</span>
        </span>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse" style="padding-right: 1rem;">

        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <!-- Cart Icon (For customer only, when they visit admin pages) -->
            @auth
                @if(Auth::user()->role === 'customer')
                    @php
                        $cartItems = Session::get('cart', []);
                        $cartCount = count($cartItems);
                    @endphp
                    <li class="nav-item me-3">
                        <a class="nav-link position-relative" href="{{ route('public.cart.index') }}" title="Keranjang Belanja">
                            <i class="bx bx-cart fs-4"></i>
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>
                @endif
            @endauth

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('sneat-assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('sneat-assets/img/avatars/1.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                                    <small class="text-muted">{{ Auth::user()->email }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li><div class="dropdown-divider"></div></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.index') }}">
                            <i class="icon-user-check me-2"></i>
                            <span class="align-middle">Profil Saya</span>
                        </a>
                    </li>
                    <li><div class="dropdown-divider"></div></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="icon-logout me-2"></i>
                                <span class="align-middle">Log Out</span>
                            </a>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>