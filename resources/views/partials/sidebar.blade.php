<div class="sidebar-nav" id="sidebar">
    <!-- Sidebar Header -->
    <div class="px-4 py-3 border-bottom">
        <h5 class="mb-0 fw-bold">eSurvTrack</h5>
    </div>

    <!-- Sidebar Body -->
    <div class="p-3">
        <!-- Dashboard Section -->
        <div class="mb-4">
            
        <!-- Admin Dropdown -->
<li class="nav-item mb-1">
    <a class="nav-link px-3 py-2 rounded d-flex justify-content-between align-items-center"
       data-bs-toggle="collapse" href="#adminMenu" role="button" aria-expanded="false" aria-controls="adminMenu">
        <span><i class="fas fa-user-shield me-2"></i> Admin</span>
        <i class="fas fa-chevron-down small"></i>
    </a>
    <div class="collapse {{ request()->is('admin/*') ? 'show' : '' }}" id="adminMenu">
        <ul class="nav flex-column ms-3 mt-2">
            <li class="nav-item mb-1">
                <a href="{{ route('admin.users.index') }}"
                   class="nav-link px-3 py-2 rounded {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i> Users
                </a>
            </li>
            <!-- Add more admin items here -->
        </ul>
    </div>
</li>


            <ul class="nav flex-column">
                <!-- Dashboard -->
                <li class="nav-item mb-1">
                    <a href="{{ route('dashboard') }}" class="nav-link px-3 py-2 rounded {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie me-2"></i> Dashboard
                    </a>
                </li>

                <!-- Alerts with nested Reports -->
                <li class="nav-item mb-1">
                    <a class="nav-link px-3 py-2 rounded d-flex justify-content-between align-items-center"
                       data-bs-toggle="collapse" href="#alertsMenu" role="button" aria-expanded="false" aria-controls="alertsMenu">
                        <span><i class="fas fa-shopping-cart me-2"></i> Alerts</span>
                        <i class="fas fa-chevron-down small"></i>
                    </a>
                    <div class="collapse {{ request()->is('reports*') ? 'show' : '' }}" id="alertsMenu">
                        <ul class="nav flex-column ms-3 mt-2">
                            <li class="nav-item mb-1">
                                <a href="{{ route('reports.index') }}"
                                   class="nav-link px-3 py-2 rounded {{ request()->is('reports*') ? 'active' : '' }}">
                                    <i class="fas fa-users me-2"></i> Monitoring
                                </a>
                            </li>
                            <!-- Add more items under alerts if needed -->
                        </ul>
                    </div>
                </li>

                <li class="nav-item mb-1">
                    <a class="nav-link px-3 py-2 rounded d-flex justify-content-between align-items-center"
                       data-bs-toggle="collapse" href="#sitesmenu" role="button" aria-expanded="false" aria-controls="sitesmenu">
                        <span><i class="fas fa-shopping-cart me-2"></i> Sites</span>
                        <i class="fas fa-chevron-down small"></i>
                    </a>
                    <div class="collapse {{ request()->is('sites*') ? 'show' : '' }}" id="sitesmenu">
                        <ul class="nav flex-column ms-3 mt-2">
                            <li class="nav-item mb-1">
                                <a href="{{ route('sites.index') }}"
                                   class="nav-link px-3 py-2 rounded {{ request()->is('sites*') ? 'active' : '' }}">
                                    <i class="fas fa-users me-2"></i> View Sites
                                </a>
                            </li>
                            <!-- Add more items under alerts if needed -->
                        </ul>
                    </div>
                </li>

               

                <!-- Logout -->
                <li class="nav-item mb-1">
                    <a class="dropdown-item" href="#"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
    .sidebar-nav {
        scrollbar-width: thin;
    }

    .sidebar-nav::-webkit-scrollbar {
        width: 5px;
    }

    .sidebar-nav::-webkit-scrollbar-thumb {
        background-color: #ddd;
        border-radius: 10px;
    }

    .sidebar-nav .nav-link {
        color: white;
        font-size: 14px;
    }

    .sidebar-nav .nav-link:hover {
        color: #4361ee;
        background-color: #f7fafc;
    }

    .sidebar-nav .nav-link.active {
        color: yellow;
        font-weight: 500;
    }

    .sidebar-nav .nav-link i {
        width: 20px;
        text-align: center;
    }
</style>
