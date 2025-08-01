<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
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
        <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        <!-- Users -->
        <li class="menu-item {{ request()->routeIs('admin.users.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Users">Manajemen User</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}" class="menu-link">
                        <div data-i18n="Users List">Daftar User</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.create') }}" class="menu-link">
                        <div data-i18n="Add User">Tambah User</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Products Management -->
        <li class="menu-item {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-capsule"></i>
                <div data-i18n="Products">Manajemen Obat</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.products.index') }}" class="menu-link">
                        <div data-i18n="Products List">Daftar Obat</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.products.create') }}" class="menu-link">
                        <div data-i18n="Add Product">Tambah Obat</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.categories.index') }}" class="menu-link">
                        <div data-i18n="Categories">Kategori Obat</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Suppliers -->
        <li class="menu-item {{ request()->routeIs('admin.suppliers.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div data-i18n="Suppliers">Manajemen Supplier</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.suppliers.index') && !request()->is('*/create') && !request()->is('*/edit') ? 'active' : '' }}">
                    <a href="{{ route('admin.suppliers.index') }}" class="menu-link">
                        <div data-i18n="Suppliers List">Daftar Supplier</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.suppliers.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.suppliers.create') }}" class="menu-link">
                        <div data-i18n="Add Supplier">Tambah Supplier</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Penjualan -->
        <li class="menu-item {{ request()->routeIs('admin.sales.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-cart"></i>
                <div data-i18n="Sales">Penjualan</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.sales.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.sales.create') }}" class="menu-link">
                        <div data-i18n="New Sale">Penjualan Baru</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.sales.history') ? 'active' : '' }}">
                    <a href="{{ route('admin.sales.history') }}" class="menu-link">
                        <div data-i18n="Sales History">Riwayat Penjualan</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Pembelian -->
        <li class="menu-item {{ request()->routeIs('admin.purchases.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-shopping-bag"></i>
                <div data-i18n="Purchases">Pembelian</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.purchases.create') ? 'active' : '' }}">
                    <a href="{{ route('admin.purchases.create') }}" class="menu-link">
                        <div data-i18n="New Purchase">Pembelian Baru</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.purchases.history') ? 'active' : '' }}">
                    <a href="{{ route('admin.purchases.history') }}" class="menu-link">
                        <div data-i18n="Purchase History">Riwayat Pembelian</div>
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
        </a>
        </li>
    </ul>
    </li>
    </ul>
</aside>
