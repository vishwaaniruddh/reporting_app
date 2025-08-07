<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm" id="mainNavbar">
    <div class="container-fluid px-3">
        <!-- Sidebar Toggle -->
        <button class="navbar-toggler me-2" type="button" id="menuToggle">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Brand -->
        <a class="navbar-brand fw-bold" href="#">
            <span>eSurvTrack</span>
        </a>
        
        <!-- Right Side Controls -->
        <div class="d-flex align-items-center ms-auto">
            <!-- User Dropdown -->
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
                    <div class="me-2">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    <span class="d-none d-md-inline">Admin</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" ><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<style>
    #mainNavbar {
        height: var(--navbar-height);
        z-index: 1030;
    }
    
    .navbar-brand {
        font-size: 1.25rem;
    }
</style>