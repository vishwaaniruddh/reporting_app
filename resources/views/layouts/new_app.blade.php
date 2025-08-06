<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Reporting App') }}</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #1a73e8;
            --secondary-color: #5f6368;
            --sidebar-width: 250px;
            --header-height: 64px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        /* Header Styles */
        .main-header {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--header-height);
            background: white;
            box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            padding: 0 24px;
            z-index: 100;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: #0f172a;
            color: white;
            z-index: 101;
            transition: all 0.3s ease;
        }

        .sidebar-header {
            height: var(--header-height);
            display: flex;
            align-items: center;
            padding: 0 24px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-menu {
            padding: 24px 0;
            list-style: none;
            margin: 0;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 24px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .menu-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .menu-item.active {
            background: var(--primary-color);
            color: white;
        }

        .menu-item i {
            margin-right: 12px;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 24px;
            min-height: calc(100vh - var(--header-height));
        }

        /* Card Styles */
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1);
            margin-bottom: 24px;
        }

        .card-header {
            padding: 16px 24px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-body {
            padding: 24px;
        }

        /* Form Controls */
        .form-control, .form-select {
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 8px 12px;
            width: 100%;
            transition: all 0.3s ease;
            background-color: white;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(26,115,232,0.2);
            outline: none;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: var(--secondary-color);
            font-weight: 500;
        }

        /* Buttons */
        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: #1557b0;
        }

        .btn-info {
            background: #0ea5e9;
            color: white;
        }

        .btn-info:hover {
            background: #0284c7;
        }

        /* Table Styles */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: #f8f9fa;
            padding: 12px 16px;
            text-align: left;
            font-weight: 500;
            color: var(--secondary-color);
            border-bottom: 1px solid #e5e7eb;
        }

        .table td {
            padding: 12px 16px;
            border-bottom: 1px solid #e5e7eb;
        }

        .table tr:hover {
            background: #f8f9fa;
        }

        /* Utilities */
        .text-primary { color: var(--primary-color); }
        .font-semibold { font-weight: 600; }
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .w-full { width: 100%; }
        .gap-4 { gap: 1rem; }
        .text-xl { font-size: 1.25rem; }
        .text-secondary { color: var(--secondary-color); }
        .row { display: flex; flex-wrap: wrap; margin: -12px; }
        .row > * { padding: 12px; }
        .g-3 > * { flex: 0 0 auto; width: calc(25% - 24px); }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h1 class="text-xl font-semibold">{{ config('app.name', 'Reporting App') }}</h1>
        </div>
        <nav class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="material-icons">dashboard</i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('reports.index') }}" class="menu-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="material-icons">assessment</i>
                <span>Reports</span>
            </a>
            <a href="{{ route('clients.index') }}" class="menu-item {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                <i class="material-icons">people</i>
                <span>Clients</span>
            </a>
        </nav>
    </aside>

    <!-- Header -->
    <header class="main-header">
        <div class="flex items-center justify-between w-full">
            <div>
                <h2 class="text-xl font-semibold">@yield('title', 'Dashboard')</h2>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-secondary">{{ Auth::user()->name ?? 'Guest' }}</span>
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn">
                            <i class="material-icons">logout</i>
                            Logout
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
