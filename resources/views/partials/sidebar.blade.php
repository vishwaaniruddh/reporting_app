<div class="sidebar-nav" id="sidebar">
    <!-- Sidebar Header -->
    <div class="px-4 py-3 border-bottom">
        <h5 class="mb-0 fw-bold">eSurvTrack</h5>
    </div>

    <!-- Sidebar Body -->
    <div class="p-3">
        <!-- Dashboard Section -->
        <div class="mb-4">

            <div class="mb-4">
                <h6 class="text-uppercase small fw-bold text-muted mb-3 d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse" href="#adminMenu" role="button">
                    <span>Admin</span>
                    <i class="fas fa-chevron-down small"></i>
                </h6>
                <div class="collapse show" id="adminMenu">
                    <ul class="nav flex-column">
                        <li class="nav-item mb-1">
                            <a href="{{ route('admin.users.index') }}"
                                class="nav-link px-3 py-2 rounded {{ request()->is('admin/users*') ? 'active' : '' }}">
                                <i class="fas fa-users me-2"></i> Users
                            </a>
                        </li>
                        <!-- Add more admin menu items here -->
                    </ul>
                </div>
            </div>

            <ul class="nav flex-column">


                <li class="nav-item mb-1">
                    <a href="{{ route('dashboard') }}" class="nav-link px-3 py-2 rounded active">
                        <i class="fas fa-chart-pie me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('reports.index') }}" class="nav-link px-3 py-2 rounded">
                        <i class="fas fa-shopping-cart me-2"></i> Alerts
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('sites.index') }}" class="nav-link px-3 py-2 rounded">
                        <i class="fas fa-utensils me-2"></i> Sites
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="{{ route('reports.index') }}" class="nav-link px-3 py-2 rounded">
                        <i class="fas fa-users me-2"></i> Reports
                    </a>
                </li>


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
        color: #4a5568;
        font-size: 14px;
    }

    .sidebar-nav .nav-link:hover {
        background-color: #f7fafc;
    }

    .sidebar-nav .nav-link.active {
        background-color: #ebf8ff;
        color: var(--primary-color);
        font-weight: 500;
    }

    .sidebar-nav .nav-link i {
        width: 20px;
        text-align: center;
    }
</style>