<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Apotek Sehat</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('sneat-assets/img/favicon/favicon.ico') }}" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('sneat-assets/vendor/css/core.css') }}" />

    <!-- Page CSS -->
    @yield('page-style')
    
    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
                background-color: #fff;
            }
            
            .container {
                width: 100%;
                max-width: none;
                padding: 0;
                margin: 0;
            }
        }
        
        body {
            background-color: #f5f5f9;
        }
        
        .container {
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
    
    <!-- Core JS -->
    <script src="{{ asset('sneat-assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('sneat-assets/vendor/js/bootstrap.js') }}"></script>
    
    <!-- Page JS -->
    @yield('page-script')
</body>
</html>
