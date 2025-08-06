<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporting App - @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->

    <style>
        
        th,td{
            white-space: nowrap;
        }
        .main-content {
    padding-top: 4.5rem !important;
    padding-bottom: 4.5rem !important;
    padding-left: 1rem !important;
    padding-right: 1rem !important;
}
        .py-4 {
            padding-top: 3.5rem !important;
            padding-bottom: 3.5rem !important;
        }
        body {
            background-color: #f8f9fa;
        }
        .sidebar-nav {
            width: 250px !important;
        }
        .main-content {
            padding-top: 56px; /* Height of the navbar */
            transition: margin-left .3s;
        }
        @media (min-width: 992px) {
            .main-content {
                margin-left: 250px;
            }
            .sidebar-nav {
                transform: none;
                visibility: visible !important;
                height: 100vh;
                position: fixed;
            }
            .navbar-toggler {
                display: none;
            }
            .offcanvas.offcanvas-start {
                top: 0;
                transform: none;
            }
        }
        .sidebar-nav .nav-link {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }
        .sidebar-nav .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar-nav .nav-link.active {
            color: #fff;
            background-color: #0d6efd;
        }
        .main-content {
            padding: 1.5rem;
        }
    </style>

    @stack('styles')
</head>
<body>
    @auth
        @include('partials.sidebar')
    @endauth

    @include('partials.header')

    <main class="main-content">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>