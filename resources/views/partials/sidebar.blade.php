<div class="offcanvas offcanvas-start sidebar-nav bg-dark" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title text-white" id="sidebarLabel">Reporting App</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <nav class="navbar-dark">
            <ul class="navbar-nav">
                <li>
                    <div class="text-muted small fw-bold text-uppercase px-3 pt-3">
                        Core
                    </div>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}" class="nav-link px-3 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <span class="me-2"><i class="fas fa-tachometer-alt"></i></span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="my-2"><hr class="dropdown-divider bg-light" /></li>
                <li>
                    <div class="text-muted small fw-bold text-uppercase px-3">
                        Management
                    </div>
                </li>
                <li>
                    <a href="{{ route('reports.index') }}" class="nav-link px-3 {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <span class="me-2"><i class="fas fa-chart-bar"></i></span>
                        <span>Reports</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('clients.index') }}" class="nav-link px-3 {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                        <span class="me-2"><i class="fas fa-users"></i></span>
                        <span>Clients</span>
                    </a>
                </li>
                 <li>
                    <a href="{{ route('sites.index') }}" class="nav-link px-3 {{ request()->routeIs('sites.*') ? 'active' : '' }}">
                        <span class="me-2"><i class="fas fa-sitemap"></i></span>
                        <span>Sites</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>