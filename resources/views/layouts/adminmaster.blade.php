<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admin Control Panel">
    <!-- CSRF Token for secure AJAX/API requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Admin Dashboard')</title>

    <link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/responsive.css') }}">
    
    @stack('styles')
</head>

<body>
    <div class="admin-shell">
        <div class="sidebar-backdrop" data-sidebar-close></div>

        <!-- ========================================== -->
        <!-- 1. SIDEBAR -->
        <!-- ========================================== -->
        <aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
            <div class="sidebar-header">
                <a class="brand-mark" href="{{ url('/adminhome') }}" aria-label="Admin Dashboard">
                    <span class="brand-icon"><i class="bi bi-grid-1x2-fill" aria-hidden="true"></i></span>
                    <span class="brand-copy">
                        <span class="brand-title">Admin Panel</span>
                        <span class="brand-subtitle">Control Center</span>
                    </span>
                </a>
            </div>

            <nav class="sidebar-nav">
                
                <!-- Dashboard -->
                <a class="nav-link {{ Request::is('adminhome') ? 'active' : '' }}" href="{{ url('/adminhome') }}">
                    <span class="nav-icon"><i class="bi bi-speedometer2" aria-hidden="true"></i></span>
                    <span class="nav-text">Dashboard</span>
                </a>

                <!-- Manage Categories (Dropdown) -->
                @php
                    $isCategoryActive = Request::is('*Expense*') || Request::is('*Income*') || Request::is('admin/categories*');
                @endphp
                <a class="nav-link {{ $isCategoryActive ? '' : 'collapsed' }} {{ $isCategoryActive ? 'active' : '' }}" 
                   data-bs-toggle="collapse" 
                   href="#categoriesDropdown" 
                   role="button" 
                   aria-expanded="{{ $isCategoryActive ? 'true' : 'false' }}" 
                   aria-controls="categoriesDropdown">
                    <span class="nav-icon"><i class="bi bi-tags" aria-hidden="true"></i></span>
                    <span class="nav-text">Manage Categories</span>
                    <span class="ms-auto"><i class="bi bi-chevron-down" style="font-size: 0.8rem;"></i></span>
                </a>
                
                <!-- Dropdown Menu Items -->
                <div class="collapse {{ $isCategoryActive ? 'show' : '' }}" id="categoriesDropdown">
                    <ul class="nav flex-column ms-3 mt-1" style="font-size: 0.95em;">
                        
                        <!-- Add Expense Category -->
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('*createExpense*') ? 'active' : '' }} py-2" href="{{ url('/createExpense') }}">
                                <span class="nav-icon"><i class="bi bi-dash-circle text-danger" aria-hidden="true"></i></span>
                                <span class="nav-text">Expense Category</span>
                            </a>
                        </li>
                        
                        <!-- Add Income Category -->
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('createIncome') ? 'active' : '' }} py-2" href="{{ route('createIncome') }}">
                                <span class="nav-icon"><i class="bi bi-plus-circle text-success" aria-hidden="true"></i></span>
                                <span class="nav-text">Income Category</span>
                            </a>
                        </li>
                            
                    </ul>
                </div>

                {{-- Financial Reports (Disabled) --}}
                {{--
                <a class="nav-link {{ Request::is('admin/reports*') ? 'active' : '' }}" href="{{ route('admin.reports') }}">
                    <span class="nav-icon"><i class="bi bi-file-earmark-bar-graph" aria-hidden="true"></i></span>
                    <span class="nav-text">Financial Reports</span>
                </a>
                --}}

                <!-- Manage Users -->
                <a class="nav-link {{ Request::is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <span class="nav-icon"><i class="bi bi-people" aria-hidden="true"></i></span>
                    <span class="nav-text">Manage Users</span>
                </a>

                <hr class="dropdown-divider my-3" style="border-color: rgba(120, 120, 120, 0.2);">

                <!-- Logout Form (Hidden) -->
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>

                <!-- Logout Button -->
                <a class="nav-link text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="nav-icon"><i class="bi bi-box-arrow-right" aria-hidden="true"></i></span>
                    <span class="nav-text">Logout</span>
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <span class="status-dot"></span>
                <span class="sidebar-footer-text">System running smoothly</span>
            </div>
        </aside>

        <!-- ========================================== -->
        <!-- 2. MAIN CONTENT WRAPPER -->
        <!-- ========================================== -->
        <div class="admin-main d-flex flex-column min-vh-100">
            
            <!-- Top Navbar -->
            <nav class="navbar admin-navbar navbar-expand bg-white">
                <div class="container-fluid px-3 px-lg-4">
                    <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar" aria-expanded="true" aria-label="Toggle sidebar">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>

                    <div class="navbar-actions ms-auto d-flex align-items-center gap-3">
                        <button class="icon-button theme-toggle" type="button" data-theme-toggle aria-label="Switch color theme" title="Switch color theme">
                            <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
                        </button>

                        <form id="direct-logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link text-danger text-decoration-none d-flex align-items-center gap-2 p-0" style="background: none; border: none;">
                                <i class="bi bi-box-arrow-right fs-5"></i>
                                <span class="d-none d-sm-inline fw-medium">Sign Out</span>
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- ========================================== -->
            <!-- 3. DYNAMIC CONTENT INJECTION -->
            <!-- ========================================== -->
            <main class="flex-grow-1">
                @yield('content')
            </main>

            <!-- ========================================== -->
            <!-- 4. FOOTER -->
            <!-- ========================================== -->
            <footer class="admin-footer mt-auto">
                <div class="container-fluid px-3 px-lg-4 py-3 d-flex justify-content-between align-items-center">
                    <span class="text-muted small">&copy; {{ date('Y') }} Admin Panel. All rights reserved.</span>
                    <span class="text-muted small">v1.0.0</span>
                </div>
            </footer>
            
        </div>
    </div>

    <script src="{{ asset('admin/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>