<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('user/assets/') }}"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Expense Tracker - @yield('title', 'Dashboard')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('user/assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('user/assets/vendor/fonts/boxicons.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('user/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('user/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('user/assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('user/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- Page Specific CSS -->
    @stack('styles')

    <!-- Helpers -->
    <script src="{{ asset('user/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('user/assets/js/config.js') }}"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        
        <!-- Sidebar / Menu -->
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
            <a href="#" class="app-brand-link">
              <span class="app-brand-text demo menu-text fw-bolder ms-2">
                <i class="bx bx-brain me-1"></i>
                SmartSpend<span style="color: #696cff;">AI</span>
              </span>
            </a>
            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboard / View Balance -->
            <li class="menu-item {{ Request::is('userhome*') ? 'active' : '' }}">
              <a href="{{ route('userhome') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Dashboard">Dashboard (Balance)</div>
              </a>
            </li>

            <li class="menu-header small text-uppercase"><span class="menu-header-text">Transactions</span></li>
            
            <!-- Add Income -->
            <li class="menu-item {{ Request::is('addincome*') ? 'active' : '' }}">
              <a href="{{ route('addincome') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-trending-up"></i>
                <div data-i18n="Income">Income</div>
              </a>
            </li>

            <!-- Add Expense -->
            <li class="menu-item {{ Request::is('addexpense*') ? 'active' : '' }}">
              <a href="{{ route('addexpense') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-trending-down"></i>
                <div data-i18n="Expenses">Expenses</div>
              </a>
            </li>

            <!-- View Transactions History -->
            <li class="menu-item {{ Request::is('incomes*', 'expenses*') ? 'active open' : '' }}">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-list-ul"></i>
                <div data-i18n="Transaction History">History</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item {{ Request::is('incomes*') ? 'active' : '' }}">
                  <a href="{{ route('income.index') }}" class="menu-link">
                    <div data-i18n="Income History">Income</div>
                  </a>
                </li>
                <li class="menu-item {{ Request::is('expenses*') ? 'active' : '' }}">
                  <a href="{{ route('expense.index') }}" class="menu-link">
                    <div data-i18n="Expense History">Expenses</div>
                  </a>
                </li>
              </ul>
            </li>

            <li class="menu-header small text-uppercase"><span class="menu-header-text">Planning & Goals</span></li>

            <!-- Add Debt (Borrow/Lent) -->
            <li class="menu-item {{ Request::is('user/debts*') ? 'active' : '' }}">
              <a href="{{ url('user/debts') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-credit-card-front"></i>
                <div data-i18n="Debts">Debts (Borrow/Lend)</div>
              </a>
            </li>

            <!-- Savings Goals -->
            <li class="menu-item {{ Request::is('user/savings*') ? 'active' : '' }}">
              <a href="{{ url('user/savings') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-wallet"></i>
                <div data-i18n="Savings Goals">Savings Goals</div>
              </a>
            </li>

            <li class="menu-header small text-uppercase"><span class="menu-header-text">AI Assistance</span></li>

            <!-- Ask AI / AI Advisor -->
            <li class="menu-item {{ Request::is('ai-advisor*') ? 'active' : '' }}">
              <a href="{{ route('user.ai.advisor') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-bot"></i>
                <div data-i18n="AI Advisor">Smart AI Advisor</div>
              </a>
            </li>

            <li class="menu-header small text-uppercase"><span class="menu-header-text">Account</span></li>
            
            <!-- Profile & Settings -->
            <li class="menu-item {{ Request::is('user/profile*') ? 'active' : '' }}">
              <a href="{{ route('user.profile.edit') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-user"></i>
                <div data-i18n="Profile">My Profile</div>
              </a>
            </li>

            <!-- Sidebar Log Out Button -->
            @auth
            <li class="menu-item mt-3">
              <a href="{{ route('logout') }}" class="menu-link text-danger" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                <i class="menu-icon tf-icons bx bx-power-off text-danger"></i>
                <div data-i18n="Logout">Log Out</div>
              </a>
              <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
              </form>
            </li>
            @endauth
          </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          
          <!-- Navbar -->
          <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- User Dropdown -->
                @auth
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <span class="avatar-initial rounded-circle bg-label-primary fw-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                      </span>
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item py-2" href="{{ route('user.profile.edit') }}">
                        <div class="d-flex align-items-center">
                          <div class="flex-grow-1">
                            <span class="fw-semibold d-block text-dark">{{ Auth::user()->name }}</span>
                            <small class="text-muted text-lowercase">{{ Auth::user()->email }}</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li><div class="dropdown-divider"></div></li>
                    <li>
                      <a class="dropdown-item" href="{{ route('user.profile.edit') }}">
                        <i class="bx bx-user me-2"></i>
                        <span class="align-middle">My Profile</span>
                      </a>
                    </li>
                    <li><div class="dropdown-divider"></div></li>
                    <li>
                      <a class="dropdown-item" href="{{ route('logout') }}" 
                         onclick="event.preventDefault(); document.getElementById('navbar-logout-form').submit();">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                      </a>
                      <form id="navbar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                          @csrf
                      </form>
                    </li>
                  </ul>
                </li>
                @endauth
                <!--/ User Dropdown -->
              </ul>
            </div>
          </nav>
          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            
            <!-- Dynamic Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
               @yield('content')
            </div>
            <!-- / Dynamic Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                  © <script>document.write(new Date().getFullYear());</script> Expense Tracker
                </div>
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
        </div>
      </div>
      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <!-- Core JS -->
    <script src="{{ asset('user/assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('user/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('user/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('user/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('user/assets/vendor/js/menu.js') }}"></script>
    
    <!-- Main JS -->
    <script src="{{ asset('user/assets/js/main.js') }}"></script>

    <!-- Page Specific Scripts -->
     
    @stack('scripts')
  </body>
</html>