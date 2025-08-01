@auth
    @if(auth()->user()->isAdmin())
        @include('layouts.sidebar-admin')
    @elseif(auth()->user()->isApoteker())
        @include('layouts.sidebar-apoteker')
    @else
        <!-- Pelanggan tidak memiliki sidebar - redirect atau error -->
        <div class="alert alert-warning">
            <strong>Akses Terbatas:</strong> Role pelanggan tidak memiliki akses ke dashboard admin.
        </div>
    @endif
@endauth 