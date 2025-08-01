<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('apoteker.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <span class="text-primary fw-bold">Apotek</span>
            </span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left icon-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item {{ request()->routeIs('apoteker.dashboard') ? 'active' : '' }}">
            <a href="{{ route('apoteker.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        <!-- Products -->
        <li class="menu-item {{ request()->routeIs('apoteker.products.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-capsule"></i>
                <div data-i18n="Products">Manajemen Obat</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('apoteker.products.index') ? 'active' : '' }}">
                    <a href="{{ route('apoteker.products.index') }}" class="menu-link">
                        <div data-i18n="Product List">Daftar Obat</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('apoteker.products.create') ? 'active' : '' }}">
                    <a href="{{ route('apoteker.products.create') }}" class="menu-link">
                        <div data-i18n="Add Product">Tambah Obat</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Penjualan -->
        <li class="menu-item {{ request()->routeIs('apoteker.sales.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cart"></i>
                <div data-i18n="Sales">Penjualan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('apoteker.sales.create') ? 'active' : '' }}">
                    <a href="{{ route('apoteker.sales.create') }}" class="menu-link">
                        <div data-i18n="New Sale">Penjualan Baru</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('apoteker.sales.history') ? 'active' : '' }}">
                    <a href="{{ route('apoteker.sales.history') }}" class="menu-link">
                        <div data-i18n="Sales History">Riwayat Penjualan</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Profile -->
        <li class="menu-item {{ request()->routeIs('profile.index') ? 'active' : '' }}">
            <a href="{{ route('profile.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user-circle"></i>
                <div data-i18n="Profile">Profil</div>
            </a>
        </li>
    </ul>
</aside>
