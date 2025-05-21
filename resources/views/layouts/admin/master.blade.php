<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Lumanglipa Barangay Management System">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>{{ config('app.name') }} - Admin Panel</title>
    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="{{ asset('admin/dark/css/simplebar.css') }}">
    <!-- Fonts CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="{{ asset('admin/dark/css/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/dark/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/dark/css/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/dark/css/uppy.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/dark/css/jquery.steps.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/dark/css/jquery.timepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/dark/css/quill.snow.css') }}">
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="{{ asset('admin/dark/css/daterangepicker.css') }}">
    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('admin/dark/css/app-light.css') }}" id="lightTheme" disabled>
    <link rel="stylesheet" href="{{ asset('admin/dark/css/app-dark.css') }}" id="darkTheme">
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    @stack('styles')
  </head>
  <body class="vertical dark">
    <div class="wrapper">
      <nav class="topnav navbar navbar-light">
        <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
          <i class="fe fe-menu navbar-toggler-icon"></i>
        </button>
        <form class="form-inline mr-auto searchform text-muted">
          <input class="form-control mr-sm-2 bg-transparent border-0 pl-4 text-muted" type="search" placeholder="Search..." aria-label="Search">
        </form>
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link text-muted my-2" href="#" id="modeSwitcher" data-mode="dark">
              <i class="fe fe-sun fe-16"></i>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-muted pr-0" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="avatar avatar-sm mt-2">
                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="avatar-img rounded-circle">
              </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item">Logout</button>
              </form>
            </div>
          </li>
        </ul>
      </nav>
      <aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
        <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
          <i class="fe fe-x"><span class="sr-only"></span></i>
        </a>
        <nav class="vertnav navbar navbar-light">
          <!-- nav bar -->
          <div class="w-100 mb-4 d-flex flex-column">
            <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="{{ route('admin.dashboard') }}">
              <img src="{{ asset('images/logo.png') }}" alt="Lumanglipa Logo" class="navbar-brand-img brand-md mb-2">
            </a>
            <!-- Modified text div with collapse-hide class -->
            <div class="text-center mb-3 collapse-hide">
              <h6 class="text-uppercase font-weight-bold">LumangLipa Admin Page</h6>
            </div>
          </div>

          <!-- Sidebar Navigation Menu -->
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item">
              <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="fe fe-home fe-16"></i>
                <span class="ml-3 item-text">Dashboard</span>
              </a>
            </li>

            @if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Barangay Secretary']))
            <li class="nav-item">
              <a href="{{ route('admin.documents') }}" class="nav-link">
                <i class="fe fe-file fe-16"></i>
                <span class="ml-3 item-text">Document Requests</span>
              </a>
            </li>
            @endif

            @if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Health Worker']))
            <li class="nav-item">
              <a href="{{ route('admin.health') }}" class="nav-link">
                <i class="fe fe-heart fe-16"></i>
                <span class="ml-3 item-text">Health Services</span>
              </a>
            </li>
            @endif

            @if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Complaint Manager']))
            <li class="nav-item">
              <a href="{{ route('admin.complaints') }}" class="nav-link">
                <i class="fe fe-alert-triangle fe-16"></i>
                <span class="ml-3 item-text">Complaints</span>
              </a>
            </li>
            @endif

            @if(Auth::user()->role->name === 'Barangay Captain')
            <li class="nav-item">
              <a href="{{ route('admin.analytics') }}" class="nav-link">
                <i class="fe fe-bar-chart-2 fe-16"></i>
                <span class="ml-3 item-text">Analytics</span>
              </a>
            </li>
            @endif
          </ul>

          <!-- Removed User Profile Section -->

        </nav>
      </aside>

      <main role="main" class="main-content">
        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-12">
              @if(session('status'))
                <div class="alert alert-success">
                  {{ session('status') }}
                </div>
              @endif

              @yield('content')
            </div>
          </div>
        </div>

        <!-- Modal shortcut -->
        <div class="modal fade modal-shortcut modal-slide" tabindex="-1" role="dialog" aria-labelledby="defaultModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="defaultModalLabel">Shortcuts</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body px-5">
                <div class="row align-items-center">
                  <div class="col-6 text-center">
                    <div class="squircle bg-primary justify-content-center">
                      <i class="fe fe-home fe-32 align-self-center text-white"></i>
                    </div>
                    <p>Dashboard</p>
                  </div>
                  <div class="col-6 text-center">
                    <div class="squircle bg-primary justify-content-center">
                      <i class="fe fe-user fe-32 align-self-center text-white"></i>
                    </div>
                    <p>Profile</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Javascript -->
    <script src="{{ asset('admin/dark/js/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/dark/js/popper.min.js') }}"></script>
    <script src="{{ asset('admin/dark/js/moment.min.js') }}"></script>
    <script src="{{ asset('admin/dark/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin/dark/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('admin/dark/js/daterangepicker.js') }}"></script>
    <script src="{{ asset('admin/dark/js/jquery.stickOnScroll.js') }}"></script>
    <script src="{{ asset('admin/dark/js/tinycolor-min.js') }}"></script>
    <script src="{{ asset('admin/dark/js/config.js') }}"></script>
    <script src="{{ asset('admin/dark/js/apps.js') }}"></script>
    <!-- Custom Admin JS -->
    <script src="{{ asset('js/admin-custom.js') }}"></script>

    @stack('scripts')
  </body>
</html>
