<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eSurvTrack - @yield('title', 'Dashboard')</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
/* Pagination styling */
.pagination {
    justify-content: center;
    margin-top: 20px;
}

.page-item.active .page-link {
    background-color: #4361ee;
    border-color: #4361ee;
}

.page-link {
    color: #4361ee;
}

/* Table action buttons */
.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

/* Responsive table */
@media (max-width: 768px) {
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .btn-group {
        flex-wrap: wrap;
        gap: 5px;
    }
}
        th,td{
            white-space: nowrap;
        }
        #mainContent{
            padding: 7% 2%;
        }
        :root {
            --sidebar-width: 280px;
            --navbar-height: 60px;
            --primary-color: #4361ee;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            overflow-x: hidden;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding-top: var(--navbar-height);
            min-height: 100vh;
            transition: margin-left 0.2s;
        }
        
        /* Sidebar Styles */
        .sidebar-nav {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
            z-index: 1000;
            overflow-y: auto;
        }
        
        /* Responsive Behavior */
        @media (max-width: 991.98px) {
            .main-content {
                margin-left: 0;
            }
            
            .sidebar-nav {
                transform: translateX(-100%);
                transition: transform 0.2s;
            }
            
            .sidebar-nav.show {
                transform: translateX(0);
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    @include('partials.sidebar')
    @include('partials.header')
    
    <main class="main-content" id="mainContent">
        @yield('content')
    </main>
    
    @include('partials.footer')
    
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.getElementById('menuToggle');
            
            if (window.innerWidth < 992 && 
                !sidebar.contains(event.target) && 
                !menuToggle.contains(event.target)) {
                sidebar.classList.remove('show');
            }
        });
        
        // Auto-close sidebar when resizing to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 992) {
                document.getElementById('sidebar').classList.remove('show');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>