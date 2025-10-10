<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('page-title', 'Lumanglipa Barangay Management System')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" href="{{ asset('images/logo-bg.jpg') }}">

  <!-- Google Font: Source Sans Pro -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=swap">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte/css/adminlte.min.css') }}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <!-- Feather Icons -->
  <link rel="stylesheet" href="{{ asset('admin/dark/css/feather.css') }}">
  <!-- Custom Admin Fonts -->
  <link rel="stylesheet" href="{{ asset('css/admin-fonts.css') }}" />
  <!-- User Chatbot Styles for Admin -->
  <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}?v={{ time() }}">
  {{-- DataTables and shared admin table styles --}}
  @include('admin.components.datatable-styles')
  
  <!-- Custom Control Sidebar Styles -->
  <style>
    .control-sidebar {
      scrollbar-width: thin;
      scrollbar-color: #6c757d #495057;
    }
    .control-sidebar::-webkit-scrollbar {
      width: 6px;
    }
    .control-sidebar::-webkit-scrollbar-track {
      background: #495057;
    }
    .control-sidebar::-webkit-scrollbar-thumb {
      background: #6c757d;
      border-radius: 3px;
    }
    .control-sidebar::-webkit-scrollbar-thumb:hover {
      background: #adb5bd;
    }
    .control-sidebar .p-3 {
      padding-bottom: 2rem !important;
    }
    .control-sidebar input[type="checkbox"]:checked + span,
    .control-sidebar input[type="radio"]:checked + span {
      color: #28a745;
      font-weight: 500;
    }
    .text-success {
      transition: color 0.3s ease;
    }
  </style>
  
  <!-- Toastr CSS for global notifications -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/toastr/toastr.min.css') }}">
  
  @stack('styles')
</head>
<body class="sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message start -->
            <div class="media">
              <img src="{{ asset('images/logo.png') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">System
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">You have a new message</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message end -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">{{ $totalNotifications ?? 0 }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">{{ $totalNotifications ?? 0 }} Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="{{ route('admin.documents') }}" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> Document Requests
            <span class="float-right text-muted text-sm">now</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="{{ route('admin.pre-registrations.index') }}" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> Pre-Registrations
            <span class="float-right text-muted text-sm">1 hr</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="{{ route('admin.complaints') }}" class="dropdown-item">
            <i class="fas fa-exclamation-triangle mr-2"></i> Complaints
            <span class="float-right text-muted text-sm">2 hrs</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
      <!-- User Dropdown -->
      <li class="nav-item dropdown user-menu">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
          <img src="{{ Auth::user()->profile_photo_url ?? asset('images/logo.png') }}" class="user-image img-circle elevation-2" alt="User Image">
          <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'Admin' }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <!-- User image -->
          <li class="user-header bg-primary">
            <img src="{{ Auth::user()->profile_photo_url ?? asset('images/logo.png') }}" class="img-circle elevation-2" alt="User Image">
            <p>
              {{ Auth::user()->name ?? 'Admin' }}
              <small>{{ Auth::user()->role->name ?? 'Administrator' }}</small>
            </p>
          </li>
          <!-- Menu Footer-->
          <li class="user-footer">
            <a href="{{ route('admin.profile') }}" class="btn btn-default btn-flat">Profile</a>
            <form method="POST" action="{{ route('logout') }}" class="float-right">
              @csrf
              <button type="submit" class="btn btn-default btn-flat">Sign out</button>
            </form>
          </li>
        </ul>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-light-lightblue elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link navbar-white">
      <img src="{{ asset('images/logo.png') }}" alt="Lumanglipa Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">LumangLipa Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('admin.documents') }}" class="nav-link {{ Request::routeIs('admin.documents') ? 'active' : '' }}">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>Document Requests</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('admin.pre-registrations.index') }}" class="nav-link {{ Request::routeIs('admin.pre-registrations.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-plus"></i>
              <p>Pre-Registrations</p>
            </a>
          </li>

          <!-- Approvals dropdown menu -->
          <li class="nav-item {{ Request::routeIs('admin.approvals*') || Request::routeIs('admin.access-requests*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::routeIs('admin.approvals*') || Request::routeIs('admin.access-requests*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-check-circle"></i>
              <p>
                Approvals
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.approvals.index') ? 'active' : '' }}" href="{{ route('admin.approvals.index') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>All Approvals</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.access-requests.*') ? 'active' : '' }}" href="{{ route('admin.access-requests.index') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Access Requests</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Residents dropdown menu -->
          <li class="nav-item {{ Request::routeIs('admin.residents.*') || Request::routeIs('admin.gad.*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::routeIs('admin.residents.*') || Request::routeIs('admin.gad.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Manage Residents
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.residents.index') ? 'active' : '' }}" href="{{ route('admin.residents.index') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Residents</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.residents.create.*') ? 'active' : '' }}" href="{{ route('admin.residents.create.step1') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Register Resident</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.residents.census-data') ? 'active' : '' }}" href="{{ route('admin.residents.census-data') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Census Data</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.residents.id.pending') ? 'active' : '' }}" href="{{ route('admin.residents.id.pending') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>ID Card Management</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.gad.*') ? 'active' : '' }}" href="{{ route('admin.gad.index') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Gender & Development</p>
                </a>
              </li>
            </ul>
          </li>

          <!-- Manage Senior Citizen dropdown menu -->
          <li class="nav-item {{ Request::routeIs('admin.senior-citizens.*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::routeIs('admin.senior-citizens.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-blind"></i>
              <p>
                Manage Senior Citizen
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.senior-citizens.index') ? 'active' : '' }}" href="{{ route('admin.senior-citizens.index') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Senior Citizens</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.senior-citizens.register*') ? 'active' : '' }}" href="{{ route('admin.senior-citizens.register') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Register Senior Citizen</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.senior-citizens.id.pending') ? 'active' : '' }}" href="{{ route('admin.senior-citizens.id.pending') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>ID Card Management</p>
                </a>
              </li>
            </ul>
          </li>
             <li class="nav-item">
            <a href="{{ route('admin.officials.edit-single') }}" class="nav-link {{ Request::routeIs('admin.officials.edit-single') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>Officials</p>
            </a>
          </li>

         

          <li class="nav-item">
            <a href="{{ route('admin.health') }}" class="nav-link {{ Request::routeIs('admin.health') ? 'active' : '' }}">
              <i class="nav-icon fas fa-heartbeat"></i>
              <p>Health Services</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{ route('admin.complaints') }}" class="nav-link {{ Request::routeIs('admin.complaints') ? 'active' : '' }}">
              <i class="nav-icon fas fa-exclamation-triangle"></i>
              <p>Complaints</p>
            </a>
          </li>

          @if(Auth::check() && Auth::user()->role && Auth::user()->role->name === 'Barangay Captain')
          <li class="nav-item {{ Request::routeIs('admin.analytics*') || Request::routeIs('admin.security.*') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::routeIs('admin.analytics*') || Request::routeIs('admin.security.*') ? 'active' : '' }}">
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>
                Analytics
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.analytics') ? 'active' : '' }}" href="{{ route('admin.analytics') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Analytics</p>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ Request::routeIs('admin.security.*') ? 'active' : '' }}" href="{{ route('admin.security.dashboard') }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Security</p>
                </a>
              </li>
            </ul>
          </li>
          @endif


       

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            @hasSection('page-header')
              <h1 class="m-0">@yield('page-header')</h1>
              @hasSection('page-header-extra')
                <div class="mt-1">@yield('page-header-extra')</div>
              @endif
            @endif
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              @hasSection('breadcrumbs')
                @yield('breadcrumbs')
              @else
                @hasSection('page-header')
                  <li class="breadcrumb-item active">@yield('page-header')</li>
                @endif
              @endif
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @yield('content')
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <footer class="main-footer">
    <strong>&copy; {{ date('Y') }} <a href="#">Lumanglipa Smart System</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
    
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark" style="overflow-y: auto; max-height: 100vh;">
    <!-- Control sidebar content goes here -->
    <div class="p-3" style="height: auto; min-height: 100vh;">
      <h5>Customize AdminLTE</h5>
      <hr class="mb-2">
      
      <h6>Layout Options</h6>
      <div class="mb-1">
        <input type="checkbox" value="1" data-widget="pushmenu" class="mr-1">
        <span>Toggle Sidebar</span>
      </div>
      <div class="mb-1">
        <input type="checkbox" value="1" data-layout="layout-fixed" class="mr-1">
        <span>Fixed Layout</span>
      </div>
      <div class="mb-1">
        <input type="checkbox" value="1" data-layout="layout-navbar-fixed" class="mr-1">
        <span>Fixed Navbar</span>
      </div>
      <div class="mb-1">
        <input type="checkbox" value="1" data-layout="layout-footer-fixed" class="mr-1">
        <span>Fixed Footer</span>
      </div>
      <div class="mb-1">
        <input type="checkbox" value="1" data-layout="layout-top-nav" class="mr-1">
        <span>Top Navigation</span>
      </div>
      
      <h6>Header Options</h6>
      <div class="mb-1">
        <input type="checkbox" value="1" data-layout="layout-navbar-fixed" class="mr-1">
        <span>Fixed Navbar</span>
      </div>
      <div class="mb-1">
        <input type="checkbox" value="1" data-layout="layout-sm-navbar-fixed" class="mr-1">
        <span>SM+ Fixed Navbar</span>
      </div>
      
      <h6>Sidebar Options</h6>
      <div class="mb-1">
        <input type="checkbox" value="1" data-sidebar="sidebar-collapse" class="mr-1">
        <span>Collapsed</span>
      </div>
      <div class="mb-1">
        <input type="checkbox" value="1" data-sidebar="sidebar-mini" class="mr-1">
        <span>Mini Sidebar</span>
      </div>
      <div class="mb-1">
        <input type="checkbox" value="1" data-sidebar="sidebar-mini-md" class="mr-1">
        <span>Sidebar Mini MD</span>
      </div>
      <div class="mb-1">
        <input type="checkbox" value="1" data-sidebar="sidebar-mini-xs" class="mr-1">
        <span>Sidebar Mini XS</span>
      </div>
      <div class="mb-1">
        <input type="checkbox" value="1" data-sidebar="nav-flat" class="mr-1">
        <span>Nav Flat Style</span>
      </div>
      <div class="mb-1">
        <input type="checkbox" value="1" data-sidebar="nav-legacy" class="mr-1">
        <span>Nav Legacy Style</span>
      </div>
      <div class="mb-1">
        <input type="checkbox" value="1" data-sidebar="nav-compact" class="mr-1">
        <span>Nav Compact</span>
      </div>
      <div class="mb-1">
        <input type="checkbox" value="1" data-sidebar="nav-child-indent" class="mr-1">
        <span>Nav Child Indent</span>
      </div>
      <div class="mb-1">
        <input type="checkbox" value="1" data-sidebar="nav-collapse-hide-child" class="mr-1">
        <span>Nav Collapse Hide Child</span>
      </div>
      <div class="mb-1">
        <input type="checkbox" value="1" data-sidebar="sidebar-no-expand" class="mr-1">
        <span>Disable Hover/Focus Auto-Expand</span>
      </div>
      
      <h6>Footer Options</h6>
      <div class="mb-1">
        <input type="checkbox" value="1" data-layout="layout-footer-fixed" class="mr-1">
        <span>Fixed Footer</span>
      </div>
      <div class="mb-1">
        <input type="checkbox" value="1" data-layout="layout-sm-footer-fixed" class="mr-1">
        <span>SM+ Fixed Footer</span>
      </div>
      
      <h6>Sidebar Theme</h6>
      <div class="mb-1">
        <input type="radio" name="sidebar-theme" value="light" data-sidebar-theme="sidebar-light-lightblue" class="mr-1" checked>
        <span>Light Blue (Default)</span>
      </div>
      <div class="mb-1">
        <input type="radio" name="sidebar-theme" value="dark" data-sidebar-theme="sidebar-dark-primary" class="mr-1">
        <span>Dark Primary</span>
      </div>
      <div class="mb-1">
        <input type="radio" name="sidebar-theme" value="light-primary" data-sidebar-theme="sidebar-light-primary" class="mr-1">
        <span>Light Primary</span>
      </div>
      <div class="mb-1">
        <input type="radio" name="sidebar-theme" value="dark-info" data-sidebar-theme="sidebar-dark-info" class="mr-1">
        <span>Dark Info</span>
      </div>
    </div>
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('adminlte/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- overlayScrollbars -->
<script src="{{ asset('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('adminlte/js/adminlte.min.js') }}"></script>
<!-- Mobile Debug Script - Load before other scripts -->
<script>
// Mobile Debug Logger (console only - no visual indicator)
window.mobileDebug = {
    log: function(message, data) {
        if (window.innerWidth <= 768) {
            console.log('[MOBILE DEBUG]', message, data || '');
            // Visual debug indicator removed - functionality preserved
        }
    }
};

// Check if AdminLTE is loading
document.addEventListener('DOMContentLoaded', function() {
    mobileDebug.log('DOM Loaded');
    
    // Check jQuery
    if (typeof $ === 'undefined') {
        mobileDebug.log('ERROR: jQuery not loaded!');
        return;
    } else {
        mobileDebug.log('✓ jQuery loaded: v' + $.fn.jquery);
    }
    
    // Check Bootstrap
    if (typeof $.fn.dropdown === 'undefined') {
        mobileDebug.log('ERROR: Bootstrap JS not loaded!');
    } else {
        mobileDebug.log('✓ Bootstrap loaded');
    }
    
    // Check AdminLTE
    if (typeof $.AdminLTE === 'undefined') {
        mobileDebug.log('ERROR: AdminLTE not loaded!');
    } else {
        mobileDebug.log('✓ AdminLTE loaded');
    }
    
    // Force initialize AdminLTE components
    setTimeout(function() {
        try {
            // Force initialize all AdminLTE widgets
            $('[data-widget]').each(function() {
                const widget = $(this).data('widget');
                mobileDebug.log('Initializing widget: ' + widget);
                
                if (widget === 'pushmenu' && typeof $.fn.PushMenu !== 'undefined') {
                    $(this).PushMenu();
                }
                if (widget === 'treeview' && typeof $.fn.Treeview !== 'undefined') {
                    $(this).Treeview();
                }
                if (widget === 'dropdown' && typeof $.fn.dropdown !== 'undefined') {
                    $(this).dropdown();
                }
            });
            
            mobileDebug.log('✓ AdminLTE widgets initialized');
        } catch (error) {
            mobileDebug.log('ERROR initializing widgets: ' + error.message);
        }
    }, 500);
});

// Error tracking
window.addEventListener('error', function(e) {
    mobileDebug.log('JS ERROR: ' + e.message + ' at ' + e.filename + ':' + e.lineno);
});
</script>
<!-- Toastr JS for global notifications -->
<script src="{{ asset('adminlte/plugins/toastr/toastr.min.js') }}"></script>
<!-- Global Toastr Configuration -->
<script>
// Configure Toastr globally
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
};

// Global notification helper functions
window.showSuccess = function(message, title) {
    toastr.success(message, title || 'Success');
};

window.showError = function(message, title) {
    toastr.error(message, title || 'Error');
};

window.showWarning = function(message, title) {
    toastr.warning(message, title || 'Warning');
};

window.showInfo = function(message, title) {
    toastr.info(message, title || 'Info');
};

// Helper function for handling AJAX responses
window.handleAjaxResponse = function(response, successCallback, errorCallback) {
    if (response.success) {
        showSuccess(response.message);
        if (successCallback && typeof successCallback === 'function') {
            successCallback(response);
        }
    } else {
        showError(response.message || 'An error occurred');
        if (errorCallback && typeof errorCallback === 'function') {
            errorCallback(response);
        }
    }
};

// Helper function for handling AJAX errors
window.handleAjaxError = function(xhr, status, error, customMessage) {
    let errorMessage = customMessage || 'An unexpected error occurred';
    
    if (xhr.responseJSON && xhr.responseJSON.message) {
        errorMessage = xhr.responseJSON.message;
    } else if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
        // Laravel validation errors
        const errors = xhr.responseJSON.errors;
        const firstError = Object.values(errors)[0];
        errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
    } else if (xhr.status === 403) {
        errorMessage = 'You do not have permission to perform this action';
    } else if (xhr.status === 404) {
        errorMessage = 'The requested resource was not found';
    } else if (xhr.status === 500) {
        errorMessage = 'Internal server error. Please try again later';
    }
    
    showError(errorMessage);
    console.error('AJAX Error:', error, xhr);
};

// Handle Laravel Flash Messages with Toastr
@if(session('success'))
    showSuccess('{{ session('success') }}');
@endif

@if(session('error'))
    showError('{{ session('error') }}');
@endif

@if(session('warning'))
    showWarning('{{ session('warning') }}');
@endif

@if(session('info'))
    showInfo('{{ session('info') }}');
@endif
</script>
{{-- DataTables and shared admin table scripts --}}
@include('admin.components.datatable-scripts')

<!-- Custom AdminLTE Customization Script -->
<script>
$(document).ready(function() {
    // Handle layout changes
    $('[data-layout]').on('change', function() {
        var layout = $(this).data('layout');
        if ($(this).is(':checked')) {
            // Handle conflicting layouts
            if (layout === 'layout-top-nav') {
                $('body').removeClass('layout-fixed layout-navbar-fixed');
                $('[data-layout="layout-fixed"], [data-layout="layout-navbar-fixed"]').prop('checked', false);
            }
            $('body').addClass(layout);
        } else {
            $('body').removeClass(layout);
        }
    });
    
    // Handle sidebar changes
    $('[data-sidebar]').on('change', function() {
        var sidebar = $(this).data('sidebar');
        var $sidebar = $('.main-sidebar');
        var $nav = $('.nav-sidebar');
        var $body = $('body');
        
        if ($(this).is(':checked')) {
            if (sidebar.startsWith('nav-')) {
                $nav.addClass(sidebar);
            } else {
                // Handle conflicting sidebar options
                if (sidebar === 'sidebar-mini') {
                    $body.removeClass('sidebar-mini-md sidebar-mini-xs');
                    $('[data-sidebar="sidebar-mini-md"], [data-sidebar="sidebar-mini-xs"]').prop('checked', false);
                } else if (sidebar === 'sidebar-mini-md') {
                    $body.removeClass('sidebar-mini sidebar-mini-xs');
                    $('[data-sidebar="sidebar-mini"], [data-sidebar="sidebar-mini-xs"]').prop('checked', false);
                } else if (sidebar === 'sidebar-mini-xs') {
                    $body.removeClass('sidebar-mini sidebar-mini-md');
                    $('[data-sidebar="sidebar-mini"], [data-sidebar="sidebar-mini-md"]').prop('checked', false);
                }
                $body.addClass(sidebar);
            }
        } else {
            if (sidebar.startsWith('nav-')) {
                $nav.removeClass(sidebar);
            } else {
                $body.removeClass(sidebar);
            }
        }
    });
    
    // Handle sidebar theme changes
    $('[data-sidebar-theme]').on('change', function() {
        if ($(this).is(':checked')) {
            var newTheme = $(this).data('sidebar-theme');
            var $sidebar = $('.main-sidebar');
            var $brandLink = $('.brand-link');
            
            // Remove existing sidebar theme classes
            $sidebar.removeClass('sidebar-dark-primary sidebar-light-lightblue sidebar-dark-info sidebar-light-primary');
            $brandLink.removeClass('navbar-primary navbar-lightblue navbar-info navbar-light navbar-white');
            
            // Add new theme class
            $sidebar.addClass(newTheme);
            
            // Update brand link color based on theme
            if (newTheme.includes('light')) {
                $brandLink.addClass('navbar-white');
            } else {
                if (newTheme.includes('primary')) {
                    $brandLink.addClass('navbar-primary');
                } else if (newTheme.includes('info')) {
                    $brandLink.addClass('navbar-info');
                } else {
                    $brandLink.addClass('navbar-primary');
                }
            }
        }
    });
    
    // Handle pushmenu toggle
    $('[data-widget="pushmenu"]').on('change', function() {
        if ($(this).is(':checked')) {
            $('body').addClass('sidebar-collapse');
        } else {
            $('body').removeClass('sidebar-collapse');
        }
    });
    
    // Set initial state based on current body classes
    var bodyClasses = $('body').attr('class');
    if (bodyClasses) {
        // Layout options
        if (bodyClasses.includes('layout-navbar-fixed')) {
            $('[data-layout="layout-navbar-fixed"]').prop('checked', true);
        }
        if (bodyClasses.includes('layout-footer-fixed')) {
            $('[data-layout="layout-footer-fixed"]').prop('checked', true);
        }
        if (bodyClasses.includes('layout-fixed')) {
            $('[data-layout="layout-fixed"]').prop('checked', true);
        }
        if (bodyClasses.includes('layout-top-nav')) {
            $('[data-layout="layout-top-nav"]').prop('checked', true);
        }
        if (bodyClasses.includes('layout-sm-navbar-fixed')) {
            $('[data-layout="layout-sm-navbar-fixed"]').prop('checked', true);
        }
        if (bodyClasses.includes('layout-sm-footer-fixed')) {
            $('[data-layout="layout-sm-footer-fixed"]').prop('checked', true);
        }
        
        // Sidebar options
        if (bodyClasses.includes('sidebar-collapse')) {
            $('[data-sidebar="sidebar-collapse"]').prop('checked', true);
            $('[data-widget="pushmenu"]').prop('checked', true);
        }
        if (bodyClasses.includes('sidebar-mini') && !bodyClasses.includes('sidebar-mini-md') && !bodyClasses.includes('sidebar-mini-xs')) {
            $('[data-sidebar="sidebar-mini"]').prop('checked', true);
        }
        if (bodyClasses.includes('sidebar-mini-md')) {
            $('[data-sidebar="sidebar-mini-md"]').prop('checked', true);
        }
        if (bodyClasses.includes('sidebar-mini-xs')) {
            $('[data-sidebar="sidebar-mini-xs"]').prop('checked', true);
        }
        if (bodyClasses.includes('sidebar-no-expand')) {
            $('[data-sidebar="sidebar-no-expand"]').prop('checked', true);
        }
    }
    
    // Check nav classes
    var navClasses = $('.nav-sidebar').attr('class');
    if (navClasses) {
        if (navClasses.includes('nav-flat')) {
            $('[data-sidebar="nav-flat"]').prop('checked', true);
        }
        if (navClasses.includes('nav-legacy')) {
            $('[data-sidebar="nav-legacy"]').prop('checked', true);
        }
        if (navClasses.includes('nav-compact')) {
            $('[data-sidebar="nav-compact"]').prop('checked', true);
        }
        if (navClasses.includes('nav-child-indent')) {
            $('[data-sidebar="nav-child-indent"]').prop('checked', true);
        }
        if (navClasses.includes('nav-collapse-hide-child')) {
            $('[data-sidebar="nav-collapse-hide-child"]').prop('checked', true);
        }
    }
    
    // Set initial sidebar theme radio button based on current sidebar class
    var $sidebar = $('.main-sidebar');
    if ($sidebar.hasClass('sidebar-light-lightblue')) {
        $('[data-sidebar-theme="sidebar-light-lightblue"]').prop('checked', true);
    } else if ($sidebar.hasClass('sidebar-dark-primary')) {
        $('[data-sidebar-theme="sidebar-dark-primary"]').prop('checked', true);
    } else if ($sidebar.hasClass('sidebar-light-primary')) {
        $('[data-sidebar-theme="sidebar-light-primary"]').prop('checked', true);
    } else if ($sidebar.hasClass('sidebar-dark-info')) {
        $('[data-sidebar-theme="sidebar-dark-info"]').prop('checked', true);
    }
    
    // Add visual feedback for changes
    $('.control-sidebar input[type="checkbox"], .control-sidebar input[type="radio"]').on('change', function() {
        var $this = $(this);
        $this.closest('div').addClass('text-success');
        setTimeout(function() {
            $this.closest('div').removeClass('text-success');
        }, 300);
    });
    
    // Debug logging (remove in production)
    console.log('AdminLTE Customization Panel initialized successfully');
});

// Comprehensive Mobile Fix for AdminLTE
$(document).ready(function() {
    // Mobile device detection
    function isMobile() {
        return window.innerWidth <= 768 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }
    
    if (isMobile()) {
        mobileDebug.log('Mobile device detected, applying fixes...');
        
        // Fix 1: Ensure touch events work for all interactive elements
        $('body').addClass('touch-device');
        
        // Fix 2: Force dropdown functionality
        $('.dropdown-toggle').off('click.mobile').on('click.mobile touchstart.mobile', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const $this = $(this);
            const $dropdown = $this.next('.dropdown-menu');
            
            // Close other dropdowns
            $('.dropdown-menu').not($dropdown).hide();
            
            // Toggle current dropdown
            $dropdown.toggle();
            
            mobileDebug.log('Dropdown toggled for: ' + $this.text().trim());
        });
        
        // Fix 3: Sidebar treeview (nav-treeview) functionality
        $('.nav-link').off('click.mobile-tree').on('click.mobile-tree', function(e) {
            const $this = $(this);
            const $parent = $this.parent('.nav-item');
            const $treeview = $parent.find('.nav-treeview');
            
            if ($treeview.length > 0) {
                e.preventDefault();
                
                // Toggle the menu-open class
                $parent.toggleClass('menu-open');
                
                // Slide toggle the treeview
                $treeview.slideToggle(300);
                
                mobileDebug.log('Treeview toggled: ' + $this.find('p').text());
            }
        });
        
        // Fix 4: Control sidebar (customize panel)
        $('[data-widget="control-sidebar"]').off('click.mobile-control').on('click.mobile-control', function(e) {
            e.preventDefault();
            const $controlSidebar = $('.control-sidebar');
            $('body').toggleClass('control-sidebar-slide-open');
            mobileDebug.log('Control sidebar toggled');
        });
        
        // Fix 5: Fullscreen functionality
        $('[data-widget="fullscreen"]').off('click.mobile-fullscreen').on('click.mobile-fullscreen', function(e) {
            e.preventDefault();
            
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().then(() => {
                    $(this).find('i').removeClass('fa-expand-arrows-alt').addClass('fa-compress-arrows-alt');
                    mobileDebug.log('Entered fullscreen');
                }).catch(err => {
                    mobileDebug.log('Fullscreen error: ' + err.message);
                });
            } else {
                document.exitFullscreen().then(() => {
                    $(this).find('i').removeClass('fa-compress-arrows-alt').addClass('fa-expand-arrows-alt');
                    mobileDebug.log('Exited fullscreen');
                });
            }
        });
        
        // Fix 6: Navbar search
        $('[data-widget="navbar-search"]').off('click.mobile-search').on('click.mobile-search', function(e) {
            e.preventDefault();
            $('.navbar-search-block').toggle();
            const isVisible = $('.navbar-search-block').is(':visible');
            if (isVisible) {
                $('.navbar-search-block input').focus();
            }
            mobileDebug.log('Navbar search toggled: ' + isVisible);
        });
        
        // Fix 7: Close dropdowns when clicking outside
        $(document).off('click.mobile-close').on('click.mobile-close', function(e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('.dropdown-menu').hide();
            }
            if (!$(e.target).closest('.navbar-search-block, [data-widget="navbar-search"]').length) {
                $('.navbar-search-block').hide();
            }
        });
        
        // Fix 8: Ensure all AdminLTE widgets are properly initialized
        setTimeout(function() {
            // Re-initialize AdminLTE
            if (typeof $.AdminLTE !== 'undefined' && $.AdminLTE.init) {
                $.AdminLTE.init();
                mobileDebug.log('AdminLTE re-initialized');
            }
            
            // Force re-bind all data-widget elements
            $('[data-widget]').each(function() {
                const $this = $(this);
                const widget = $this.data('widget');
                
                try {
                    if (widget === 'pushmenu' && typeof $.fn.PushMenu !== 'undefined') {
                        $this.PushMenu();
                    } else if (widget === 'treeview' && typeof $.fn.Treeview !== 'undefined') {
                        $this.Treeview();
                    }
                } catch (error) {
                    mobileDebug.log('Widget init error for ' + widget + ': ' + error.message);
                }
            });
        }, 1000);
    }
    
    // Enhanced mobile support for sidebar toggle
    function initMobileSidebar() {
        // Check if we're on mobile/tablet
        function isMobile() {
            return window.innerWidth <= 768;
        }
        
        // Force sidebar to work on mobile devices
                // Enhanced mobile support for sidebar toggle with touch events
        $('[data-widget="pushmenu"]').off('click.mobileFix touchstart.mobileFix').on('click.mobileFix touchstart.mobileFix', function(e) {
            if (isMobile()) {
                e.preventDefault();
                e.stopPropagation();
                
                var $body = $('body');
                
                // Toggle sidebar collapse state
                if ($body.hasClass('sidebar-open')) {
                    $body.removeClass('sidebar-open').addClass('sidebar-collapse');
                } else if ($body.hasClass('sidebar-collapse')) {
                    $body.removeClass('sidebar-collapse').addClass('sidebar-open');
                } else {
                    // Default state - open the sidebar
                    $body.addClass('sidebar-open').removeClass('sidebar-collapse');
                }
                
                // Add mobile overlay
                if (!$('.mobile-sidebar-overlay').length) {
                    $('body').append('<div class="mobile-sidebar-overlay"></div>');
                }
                
                if ($body.hasClass('sidebar-open')) {
                    $('.mobile-sidebar-overlay').fadeIn(200);
                    $body.css('overflow', 'hidden');
                } else {
                    $('.mobile-sidebar-overlay').fadeOut(200);
                    $body.css('overflow', '');
                }
            }
        });
        
        // Close sidebar when clicking overlay
        $(document).off('click.mobileOverlay').on('click.mobileOverlay', '.mobile-sidebar-overlay', function() {
            $('body').removeClass('sidebar-open').addClass('sidebar-collapse');
            $(this).fadeOut(200);
            $('body').css('overflow', '');
        });
        
        // Handle window resize
        $(window).off('resize.mobileSidebar').on('resize.mobileSidebar', function() {
            if (!isMobile()) {
                $('.mobile-sidebar-overlay').remove();
                $('body').css('overflow', '');
            }
        });
        
        // Ensure AdminLTE pushmenu widget is properly initialized
        if (typeof $.fn.PushMenu !== 'undefined') {
            $('[data-widget="pushmenu"]').PushMenu('init');
        }
        
        // Fallback for touch devices - ensure button is properly clickable
        $('[data-widget="pushmenu"]').css({
            'touch-action': 'manipulation',
            'user-select': 'none',
            '-webkit-user-select': 'none',
            '-moz-user-select': 'none',
            '-ms-user-select': 'none'
        });
        
        // Debug logging for mobile
        if (isMobile()) {
            console.log('Mobile sidebar initialized for screen width:', window.innerWidth);
        }
    }
    
    // Initialize mobile sidebar after AdminLTE loads
    setTimeout(initMobileSidebar, 100);
    
    // Reinitialize if AdminLTE reloads
    $(document).on('adminlte.ready', initMobileSidebar);
});
</script>

<!-- Admin Floating Chatbot (inbox style) -->
@auth
<div class="chatbot-container">
    <div class="chatbot-window" id="adminChatbotWindow">
        <div class="chatbot-header">
            <h4>💬 Messages</h4>
            <button class="chatbot-close" id="adminChatbotClose" style="display:flex;align-items:center;justify-content:center;width:32px;height:32px;background:transparent;border:none;outline:none;cursor:pointer;">
                <i class="fas fa-times" style="color:white;font-size:16px;"></i>
            </button>
        </div>
        
        <!-- Conversations List (Inbox Style) -->
        <div class="chatbot-messages" id="adminChatbotMessages" style="padding: 0;">
            <div id="conversationsList" style="display: block;">
                <!-- Conversation items will be loaded here -->
                <div style="padding: 20px; text-align: center; color: #666; font-size: 14px;">
                    <i class="fas fa-inbox" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                    Loading conversations...
                </div>
            </div>
            
            <!-- Chat View (hidden initially) -->
            <div id="chatView" style="display: none; height: 100%; flex-direction: column;">
                <div id="chatHeader" style="padding: 15px; border-bottom: 1px solid #eee; background: #f8f9fa; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center;">
                        <button id="backToInbox" style="background: none; border: none; margin-right: 10px; color: #007bff; cursor: pointer;">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <div>
                            <div id="chatUserName" style="font-weight: bold; font-size: 14px;"></div>
                            <div id="chatUserStatus" style="font-size: 12px; color: #666;"></div>
                        </div>
                    </div>
                    <button id="completeAndNextBtn" onclick="window.adminChatbot.completeAndNext()" 
                            style="background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 4px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 5px;"
                            onmouseover="this.style.background='#218838'"
                            onmouseout="this.style.background='#28a745'">
                        <i class="fas fa-check"></i> Complete & Next
                    </button>
                </div>
                
                <div id="chatMessages" style="flex: 1; padding: 15px; overflow-y: auto; min-height: 200px; max-height: calc(100% - 120px);">
                    <!-- Chat messages will appear here -->
                </div>
                
                <div class="chatbot-input-area">
                    <input type="text" class="chatbot-input" id="adminChatbotInput" 
                           placeholder="Type your message..." maxlength="500">
                    <button class="chatbot-send" id="adminChatbotSend">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <button class="chatbot-toggle" id="adminChatbotToggle" style="position:relative;">
        <div class="chatbot-pulse"></div>
        <i class="fas fa-envelope"></i>
    </button>
</div>

<script src="{{ asset('js/admin-chatbot.js') }}?v={{ time() }}"></script>
@endauth

<!-- Mobile Sidebar Styles -->
<style>
.mobile-sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1039;
    display: none;
}

/* Mobile-specific fixes */
@media (max-width: 768px) {
    /* Sidebar fixes */
    .sidebar-open .main-sidebar {
        transform: translateX(0) !important;
        margin-left: 0 !important;
    }
    
    .sidebar-collapse .main-sidebar {
        transform: translateX(-250px) !important;
    }
    
    .main-sidebar {
        transition: transform 0.3s ease-in-out !important;
    }
    
    /* Ensure sidebar is accessible on mobile */
    body.sidebar-open .main-sidebar {
        position: fixed !important;
        height: 100vh !important;
        z-index: 1040 !important;
    }
    
    /* Fix content area on mobile when sidebar is open */
    body.sidebar-open .content-wrapper,
    body.sidebar-open .main-footer {
        margin-left: 0 !important;
    }
    
    /* Touch-friendly interactive elements */
    .touch-device .nav-link,
    .touch-device .dropdown-toggle,
    .touch-device [data-widget],
    .touch-device .btn {
        min-height: 44px !important;
        display: flex !important;
        align-items: center !important;
        touch-action: manipulation !important;
        -webkit-tap-highlight-color: rgba(0,0,0,0.1) !important;
    }
    
    /* Fix dropdown menus on mobile */
    .dropdown-menu {
        position: absolute !important;
        display: none;
        min-width: 200px;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.175);
        z-index: 1000;
    }
    
    .dropdown-menu.show,
    .dropdown-menu:target {
        display: block !important;
    }
    
    /* Fix navbar search on mobile */
    .navbar-search-block {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #dee2e6;
        padding: 10px;
        z-index: 1050;
        display: none;
    }
    
    /* Control sidebar improvements */
    .control-sidebar {
        position: fixed !important;
        top: 0 !important;
        right: -400px !important;
        width: 300px !important;
        height: 100vh !important;
        transition: right 0.3s ease-in-out !important;
        z-index: 1041 !important;
    }
    
    body.control-sidebar-slide-open .control-sidebar {
        right: 0 !important;
    }
    
    /* Tree view improvements */
    .nav-treeview {
        display: none;
        background-color: rgba(255,255,255,0.1);
        padding-left: 20px;
    }
    
    .menu-open .nav-treeview {
        display: block;
    }
    
    /* Better button spacing */
    .navbar-nav .nav-item .nav-link {
        padding: 0.75rem 1rem !important;
    }
    
    /* Fullscreen button fix */
    [data-widget="fullscreen"] {
        cursor: pointer !important;
    }
}

/* Debug indicator styles */
#debug-indicator {
    font-family: monospace !important;
    z-index: 99999 !important;
    pointer-events: none !important;
    opacity: 0.9 !important;
}
</style>
</script>

<!-- Admin Floating Chatbot (inbox style) -->
@auth
<div class="chatbot-container">
    <div class="chatbot-window" id="adminChatbotWindow">
        <div class="chatbot-header">
            <h4>💬 Messages</h4>
            <button class="chatbot-close" id="adminChatbotClose" style="display:flex;align-items:center;justify-content:center;width:32px;height:32px;background:transparent;border:none;outline:none;cursor:pointer;">
                <i class="fas fa-times" style="color:white;font-size:16px;"></i>
            </button>
        </div>
        
        <!-- Conversations List (Inbox Style) -->
        <div class="chatbot-messages" id="adminChatbotMessages" style="padding: 0;">
            <div id="conversationsList" style="display: block;">
                <!-- Conversation items will be loaded here -->
                <div style="padding: 20px; text-align: center; color: #666; font-size: 14px;">
                    <i class="fas fa-inbox" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                    Loading conversations...
                </div>
            </div>
            
            <!-- Chat View (hidden initially) -->
            <div id="chatView" style="display: none; height: 100%; flex-direction: column;">
                <div id="chatHeader" style="padding: 15px; border-bottom: 1px solid #eee; background: #f8f9fa; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center;">
                        <button id="backToInbox" style="background: none; border: none; margin-right: 10px; color: #007bff; cursor: pointer;">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                        <div>
                            <div id="chatUserName" style="font-weight: bold; font-size: 14px;"></div>
                            <div id="chatUserStatus" style="font-size: 12px; color: #666;"></div>
                        </div>
                    </div>
                    <button id="completeAndNextBtn" onclick="window.adminChatbot.completeAndNext()" 
                            style="background: #28a745; color: white; border: none; padding: 6px 12px; border-radius: 4px; font-size: 12px; cursor: pointer; display: flex; align-items: center; gap: 5px;"
                            onmouseover="this.style.background='#218838'"
                            onmouseout="this.style.background='#28a745'">
                        <i class="fas fa-check"></i> Complete & Next
                    </button>
                </div>
                
                <div id="chatMessages" style="flex: 1; padding: 15px; overflow-y: auto; min-height: 200px; max-height: calc(100% - 120px);">
                    <!-- Chat messages will appear here -->
                </div>
                
                <div class="chatbot-input-area">
                    <input type="text" class="chatbot-input" id="adminChatbotInput" 
                           placeholder="Type your message..." maxlength="500">
                    <button class="chatbot-send" id="adminChatbotSend">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <button class="chatbot-toggle" id="adminChatbotToggle" style="position:relative;">
        <div class="chatbot-pulse"></div>
        <i class="fas fa-envelope"></i>
    </button>
</div>

<script src="{{ asset('js/admin-chatbot.js') }}?v={{ time() }}"></script>
@endauth

@stack('scripts')
</body>
</html>
