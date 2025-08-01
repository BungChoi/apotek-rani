<!doctype html>
<html lang="en" class="layout-menu-fixed layout-compact" data-assets-path="{{ asset('sneat-assets/') }}/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('sneat-assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('sneat-assets/vendor/fonts/iconify-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('sneat-assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('sneat-assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('sneat-assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <!-- Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet' />

    @stack('styles')

    <!-- Helpers -->
    <script src="{{ asset('sneat-assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('sneat-assets/js/config.js') }}"></script>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('layouts.sidebar')
            <div class="layout-page">
                @include('layouts.navbar')
                <div class="content-wrapper">
                    <div class="container-fluid flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('sneat-assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('sneat-assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('sneat-assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('sneat-assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('sneat-assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('sneat-assets/js/main.js') }}"></script>

    @stack('scripts')
</body>

</html>
