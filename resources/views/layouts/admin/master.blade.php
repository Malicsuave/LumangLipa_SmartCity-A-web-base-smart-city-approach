<!doctype html>
<html lang="en" data-theme="{{ session('theme', 'dark') }}">
  <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>@yield('page-title', 'Lumanglipa Barangay Management System')</title>
        <!--begin::Accessibility Meta Tags-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
        <meta name="color-scheme" content="light dark" />
        <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
        <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
        <!--end::Accessibility Meta Tags-->
        <!--begin::Primary Meta Tags-->
        <meta name="title" content="Lumanglipa Barangay Management System" />
        <meta name="author" content="Lumanglipa" />
        <meta name="description" content="Lumanglipa Barangay Management System Admin Panel" />
        <meta name="keywords" content="barangay, admin, dashboard, lumanglipa, management, system" />
        <!--end::Primary Meta Tags-->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="{{ asset('images/logo-bg.jpg') }}">
        <!--begin::Accessibility Features-->
        <meta name="supported-color-schemes" content="light dark" />
        <link rel="preload" href="{{ asset('adminlte/css/adminlte.css') }}" as="style" />
        <!--end::Accessibility Features-->
        <!--begin::Fonts-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" media="print" onload="this.media='all'" />
        <!--end::Fonts-->
        <!--begin::Third Party Plugin(OverlayScrollbars)-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
        <!--end::Third Party Plugin(OverlayScrollbars)-->
        <!--begin::Third Party Plugin(Bootstrap Icons)-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
        <!--end::Third Party Plugin(Bootstrap Icons)-->
        <!--begin::Required Plugin(AdminLTE)-->
        <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.css') }}" />
        <!--end::Required Plugin(AdminLTE)--></div>
        <!-- apexcharts -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" crossorigin="anonymous" />
        
        
     
    </head>
  <body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <li class="nav-item d-none d-md-block"><a href="{{ route('admin.dashboard') }}" class="nav-link">Home</a></li>
            <li class="nav-item d-none d-md-block"><a href="#" class="nav-link">Contact</a></li>
          </ul>
          <!--end::Start Navbar Links-->

          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
            <!--begin::Navbar Search-->
            <li class="nav-item">
              <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="bi bi-search"></i>
              </a>
            </li>
            <!--end::Navbar Search-->

            <!--begin::Messages Dropdown Menu-->
            <li class="nav-item dropdown">
              <a class="nav-link" data-bs-toggle="dropdown" href="#">
                <i class="bi bi-chat-text"></i>
                <span class="navbar-badge badge text-bg-danger">3</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <a href="#" class="dropdown-item">
                  <div class="d-flex">
                    <div class="flex-shrink-0">
                      <img src="{{ asset('images/logo.png') }}" alt="User Avatar" class="img-size-50 rounded-circle me-3" />
                    </div>
                    <div class="flex-grow-1">
                      <h3 class="dropdown-item-title">System</h3>
                      <p class="fs-7">You have a new message</p>
                      <p class="fs-7 text-secondary"><i class="bi bi-clock-fill me-1"></i> 4 Hours Ago</p>
                    </div>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
              </div>
            </li>
            <!--end::Messages Dropdown Menu-->

            <!--begin::Notifications Dropdown Menu-->
            <li class="nav-item dropdown">
              <a class="nav-link" data-bs-toggle="dropdown" href="#">
                <i class="bi bi-bell-fill"></i>
                <span class="navbar-badge badge text-bg-warning">{{ $totalNotifications ?? 0 }}</span>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <span class="dropdown-item dropdown-header">{{ $totalNotifications ?? 0 }} Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="{{ route('admin.documents') }}" class="dropdown-item">
                  <i class="bi bi-envelope me-2"></i> Document Requests
                  <span class="float-end text-secondary fs-7">Now</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('admin.pre-registrations.index') }}" class="dropdown-item">
                  <i class="bi bi-people-fill me-2"></i> Pre-Registrations
                  <span class="float-end text-secondary fs-7">1 hr</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('admin.complaints') }}" class="dropdown-item">
                  <i class="bi bi-exclamation-triangle me-2"></i> Complaints
                  <span class="float-end text-secondary fs-7">2 hrs</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
              </div>
            </li>
            <!--end::Notifications Dropdown Menu-->

            <!--begin::Color Mode Toggler-->
            <li class="nav-item dropdown">
              <button
                class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center"
                id="bd-theme"
                type="button"
                aria-expanded="false"
                data-bs-toggle="dropdown"
                data-bs-display="static"
              >
                <span class="theme-icon-active">
                  <i class="my-1"></i>
                </span>
                <span class="d-lg-none ms-2" id="bd-theme-text">Toggle theme</span>
              </button>
              <ul
                class="dropdown-menu dropdown-menu-end"
                aria-labelledby="bd-theme-text"
                style="--bs-dropdown-min-width: 8rem;"
              >
                <li>
                  <button
                    type="button"
                    class="dropdown-item d-flex align-items-center active"
                    data-bs-theme-value="light"
                    aria-pressed="false"
                  >
                    <i class="bi bi-sun-fill me-2"></i>
                    Light
                    <i class="bi bi-check-lg ms-auto d-none"></i>
                  </button>
                </li>
                <li>
                  <button
                    type="button"
                    class="dropdown-item d-flex align-items-center"
                    data-bs-theme-value="dark"
                    aria-pressed="false"
                  >
                    <i class="bi bi-moon-fill me-2"></i>
                    Dark
                    <i class="bi bi-check-lg ms-auto d-none"></i>
                  </button>
                </li>
                <li>
                  <button
                    type="button"
                    class="dropdown-item d-flex align-items-center"
                    data-bs-theme-value="auto"
                    aria-pressed="true"
                  >
                    <i class="bi bi-circle-fill-half-stroke me-2"></i>
                    Auto
                    <i class="bi bi-check-lg ms-auto d-none"></i>
                  </button>
                </li>
              </ul>
            </li>
            <!--end::Color Mode Toggler-->

            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
              <a class="nav-link" href="#" id="fullscreen-btn" role="button">
                <i class="bi bi-arrows-fullscreen"></i>
              </a>
            </li>
            <!--end::Fullscreen Toggle-->

            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img src="{{ Auth::user()->profile_photo_url ?? asset('images/logo.png') }}" class="user-image rounded-circle shadow" alt="User Image" style="width:32px;height:32px;" />
                <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'Admin' }}</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <li class="user-header text-bg-primary">
                  <img src="{{ Auth::user()->profile_photo_url ?? asset('images/logo.png') }}" class="rounded-circle shadow" alt="User Image" />
                  <p>
                    {{ Auth::user()->name ?? 'Admin' }}
                    <small>Member since {{ Auth::user()->created_at?->format('M Y') ?? '' }}</small>
                  </p>
                </li>
                <li class="user-body">
                  <div class="row">
                    <div class="col-4 text-center"><a href="#">Followers</a></div>
                    <div class="col-4 text-center"><a href="#">Sales</a></div>
                    <div class="col-4 text-center"><a href="#">Friends</a></div>
                  </div>
                </li>
                <li class="user-footer">
                  <a href="{{ route('admin.profile') }}" class="btn btn-default btn-flat">Profile</a>
                  <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-default btn-flat float-end">Sign out</button>
                  </form>
                </li>
              </ul>
            </li>
            <!--end::User Menu Dropdown-->
          </ul>
          <!--end::End Navbar Links-->
        </div>
      </nav>
      <!--end::Header-->

      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <a href="{{ route('admin.dashboard') }}" class="brand-link">
            <img src="{{ asset('images/logo.png') }}" alt="Lumanglipa Logo" class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">LumangLipa Admin</span>
          </a>
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="navigation"
              aria-label="Main navigation"
              data-accordion="false"
              id="navigation"
            >
              <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link  {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>
                    Dashboard
                  </p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.documents') }}" class="nav-link {{ Request::routeIs('admin.documents') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-file-earmark-fill"></i>
                  <p>Document Requests</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.pre-registrations.index') }}" class="nav-link {{ Request::routeIs('admin.pre-registrations.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-user-plus"></i>
                  <p>Pre-Registrations</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.access-requests.index') }}" class="nav-link {{ Request::routeIs('admin.access-requests.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-key-fill"></i>
                  <p>Access Requests</p>
                </a>
              </li>

              <!-- Replace simple Residents link with treeview dropdown -->
              <li class="nav-item has-treeview {{ Request::routeIs('admin.residents.*') || Request::routeIs('admin.gad.*') || Request::routeIs('admin.senior-citizens.*') ? 'menu-open' : '' }}">
                <a href="#" class="nav-link {{ Request::routeIs('admin.residents.*') || Request::routeIs('admin.gad.*') || Request::routeIs('admin.senior-citizens.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-people-fill"></i>
                  <p>
                    Residents
                    <i class="nav-arrow bi bi-chevron-right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item"><a class="nav-link {{ Request::routeIs('admin.residents.index') ? 'active' : '' }}" href="{{ route('admin.residents.index') }}"><i class="{{ Request::routeIs('admin.residents.index') ? 'nav-icon bi bi-circle-fill' : 'nav-icon bi bi-circle' }}"></i><p>All Residents</p></a></li>
                  <li class="nav-item"><a class="nav-link {{ Request::routeIs('admin.residents.census-data') ? 'active' : '' }}" href="{{ route('admin.residents.census-data') }}"><i class="{{ Request::routeIs('admin.residents.census-data') ? 'nav-icon bi bi-circle-fill' : 'nav-icon bi bi-circle' }}"></i><p>Census Data</p></a></li>
                  <li class="nav-item"><a class="nav-link {{ Request::routeIs('admin.residents.id.pending') ? 'active' : '' }}" href="{{ route('admin.residents.id.pending') }}"><i class="{{ Request::routeIs('admin.residents.id.pending') ? 'nav-icon bi bi-circle-fill' : 'nav-icon bi bi-circle' }}"></i><p>ID Card Management</p></a></li>
                  <li class="nav-item"><a class="nav-link {{ Request::routeIs('admin.gad.*') ? 'active' : '' }}" href="{{ route('admin.gad.index') }}"><i class="{{ Request::routeIs('admin.gad.*') ? 'nav-icon bi bi-circle-fill' : 'nav-icon bi bi-circle' }}"></i><p>Gender &amp; Development</p></a></li>
                  <li class="nav-item"><a class="nav-link {{ Request::routeIs('admin.senior-citizens.*') ? 'active' : '' }}" href="{{ route('admin.senior-citizens.index') }}"><i class="{{ Request::routeIs('admin.senior-citizens.*') ? 'nav-icon bi bi-circle-fill' : 'nav-icon bi bi-circle' }}"></i><p>Senior Citizens</p></a></li>
                </ul>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.health') }}" class="nav-link {{ Request::routeIs('admin.health') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-heart-pulse"></i>
                  <p>Health Services</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.complaints') }}" class="nav-link {{ Request::routeIs('admin.complaints') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-exclamation-triangle"></i>
                  <p>Complaints</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.analytics') }}" class="nav-link {{ Request::routeIs('admin.analytics') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-bar-chart"></i>
                  <p>Analytics</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.profile') }}" class="nav-link {{ Request::routeIs('admin.profile') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-person"></i>
                  <p>Profile</p>
                </a>
              </li>
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->
      <!--begin::App Main-->
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Dashboard</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <div class="app-content">
          <div class="container-fluid pt-3">
            @yield('content')
          </div>
        </div>
      </main>
      <!--end::App Main-->
      <footer class="app-footer text-center">
        <strong>&copy; {{ date('Y') }} Lumanglipa Barangay Management System.</strong> All rights reserved.
      </footer>
    </div>
    <!--end::App Wrapper-->
    <!-- Core JavaScript (loaded immediately) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es.min.js"></script>
    <script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        OverlayScrollbars(document.querySelectorAll('.sidebar-wrapper'), {});
        // Fullscreen toggle
        document.getElementById('fullscreen-btn').addEventListener('click', function() {
          if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
          } else {
            document.exitFullscreen();
          }
        });
      });
    </script>
    <!-- Color Mode toggler script -->
    <script>
(() => {
  "use strict";

  const storedTheme = localStorage.getItem("theme");

  const getPreferredTheme = () => {
    if (storedTheme) {
      return storedTheme;
    }

    return window.matchMedia("(prefers-color-scheme: dark)").matches
      ? "dark"
      : "light";
  };

  const setTheme = function (theme) {
    if (
      theme === "auto" &&
      window.matchMedia("(prefers-color-scheme: dark)").matches
    ) {
      document.documentElement.setAttribute("data-bs-theme", "dark");
    } else {
      document.documentElement.setAttribute("data-bs-theme", theme);
    }
  };

  setTheme(getPreferredTheme());

  const showActiveTheme = (theme, focus = false) => {
    const themeSwitcher = document.querySelector("#bd-theme");

    if (!themeSwitcher) {
      return;
    }

    const themeSwitcherText = document.querySelector("#bd-theme-text");
    const activeThemeIcon = document.querySelector(".theme-icon-active i");
    const btnToActive = document.querySelector(
      `[data-bs-theme-value="${theme}"]`
    );
    const svgOfActiveBtn = btnToActive.querySelector("i").getAttribute("class");

    for (const element of document.querySelectorAll("[data-bs-theme-value]")) {
      element.classList.remove("active");
      element.setAttribute("aria-pressed", "false");
    }

    btnToActive.classList.add("active");
    btnToActive.setAttribute("aria-pressed", "true");
    activeThemeIcon.setAttribute("class", svgOfActiveBtn);
    const themeSwitcherLabel = `${themeSwitcherText.textContent} (${btnToActive.dataset.bsThemeValue})`;
    themeSwitcher.setAttribute("aria-label", themeSwitcherLabel);

    if (focus) {
      themeSwitcher.focus();
    }
  };

  window
    .matchMedia("(prefers-color-scheme: dark)")
    .addEventListener("change", () => {
      if (storedTheme !== "light" || storedTheme !== "dark") {
        setTheme(getPreferredTheme());
      }
    });

  window.addEventListener("DOMContentLoaded", () => {
    showActiveTheme(getPreferredTheme());

    for (const toggle of document.querySelectorAll("[data-bs-theme-value]")) {
      toggle.addEventListener("click", () => {
        const theme = toggle.getAttribute("data-bs-theme-value");
        localStorage.setItem("theme", theme);
        setTheme(theme);
        showActiveTheme(theme, true);
      });
    }
  });
})();
</script>
    @stack('scripts')
  </body>
</html>
