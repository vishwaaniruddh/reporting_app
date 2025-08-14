<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm" id="mainNavbar">
    <div class="container-fluid px-3">
        <!-- Sidebar Toggle -->
        <button class="btn btn-sm btn-outline-secondary me-2" id="menuToggle" title="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Brand / Project Info -->
        <a class="navbar-brand fw-bold d-flex align-items-center" href="#">
            <i class="fas fa-map-marker-alt text-primary me-2"></i>
            <span>eSurvTrack</span>
        </a>

        <!-- Right Side Controls -->
        <div class="d-flex align-items-center ms-auto gap-3">
            

            <!-- Fullscreen -->
            <button class="btn btn-sm btn-outline-secondary" id="fullscreenToggle" title="Toggle Fullscreen">
                <i class="fas fa-expand" id="fullscreenIcon"></i>
            </button>

           

            <!-- User Dropdown -->
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
                    <div class="me-2">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    <span class="d-none d-md-inline">{{ ucwords(Auth::user()->name) }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<style>
    #mainNavbar {
        height: var(--navbar-height, 56px);
        z-index: 1030;
    }

    .navbar-brand {
        font-size: 1.25rem;
    }

    .btn i {
        font-size: 16px;
    }

    .dropdown-toggle::after {
        display: none;
    }
    body.sidebar-collapsed .sidebar-nav .nav-link span {
    display: none;
}

body.sidebar-open .sidebar-nav {
    transform: translateX(0); /* example */
}
body.sidebar-collapsed .sidebar-nav {
    transform: translateX(-100%);
    transition: transform 0.3s ease-in-out;
}

/* Optional overlay for mobile view */
.sidebar-overlay {
    display: none;
    position: fixed;
    top: var(--navbar-height, 56px);
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.3);
    z-index: 1029;
}

body.sidebar-collapsed .sidebar-overlay {
    display: block;
}

</style>
