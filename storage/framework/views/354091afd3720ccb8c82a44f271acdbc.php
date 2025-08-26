<!doctype html>
<html lang="en" data-theme="<?php echo e(session('theme', 'dark')); ?>">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Lumanglipa Barangay Management System">
    <meta name="author" content="">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="icon" href="<?php echo e(asset('images/logo-bg.jpg')); ?>">
    <title><?php echo $__env->yieldContent('page-title', 'Lumanglipa Barangay Management System'); ?></title>
    
    <!-- Performance monitoring script -->
    <script src="<?php echo e(asset('js/performance-monitor.js')); ?>"></script>
    
    
    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('admin/dark/css/simplebar.css')); ?>">
    <!-- Fonts CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Icons CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('admin/dark/css/feather.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('admin/dark/css/select2.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('admin/dark/css/dropzone.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('admin/dark/css/uppy.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('admin/dark/css/jquery.steps.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('admin/dark/css/jquery.timepicker.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('admin/dark/css/quill.snow.css')); ?>">
    <!-- Date Range Picker CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('admin/dark/css/daterangepicker.css')); ?>">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('admin/dark/css/dataTables.bootstrap4.css')); ?>">
    
    <!-- CSS Validation Fixes - Suppresses browser console warnings -->
    <link rel="stylesheet" href="<?php echo e(asset('css/css-validation-fixes.css')); ?>">
    
    <!-- App CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('admin/dark/css/app-light.css')); ?>" id="lightTheme">
   <link rel="stylesheet" href="<?php echo e(asset('css/admin-layout.css')); ?>">
    <!-- Search icon CSS - works in both themes -->
    <link rel="stylesheet" href="<?php echo e(asset('css/search-icon.css')); ?>">
    <!-- UI Enhancements CSS - works in both themes -->
    <link rel="stylesheet" href="<?php echo e(asset('css/ui-enhancements.css')); ?>">
    <!-- Complaint metrics CSS - specifically for complaint cards -->
    <link rel="stylesheet" href="<?php echo e(asset('css/complaint-metrics.css')); ?>">
    <!-- Health metrics CSS - specifically for health service cards -->
    <link rel="stylesheet" href="<?php echo e(asset('css/health-metrics.css')); ?>">
    <!-- Complaint table styling - for improved table appearance -->
    <link rel="stylesheet" href="<?php echo e(asset('css/complaint-table.css')); ?>">
    <!-- Metric cards fix - overrides other styles -->
    
    <!-- Hover card effects - prevents loss of styling on hover -->
    <link rel="stylesheet" href="<?php echo e(asset('css/hover-card.css')); ?>">
    <!-- Light mode cards - forces proper background color in light mode -->
    <link rel="stylesheet" href="<?php echo e(asset('css/light-mode-cards.css')); ?>">
    <!-- Form validation styling - consistent validation error messages -->
    <link rel="stylesheet" href="<?php echo e(asset('css/validation.css')); ?>">
    <!-- Metric card animations - consistent animations across all admin pages -->
    <link rel="stylesheet" href="<?php echo e(asset('css/admin/metric-card-animations.css')); ?>">
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/admin.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/admin-layout.css')); ?>">
    
    <!-- Admin Layout Styles - contains all layout-specific CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('css/admin-common.css')); ?>">

    <!-- Alpine.js for custom dropdowns -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <?php echo $__env->yieldPushContent('styles'); ?>
    <?php $__env->startPush('styles'); ?>
    <style>
    .glassmorph-card {
        background: #2d3748 !important;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
        backdrop-filter: blur(18px) saturate(180%);
        -webkit-backdrop-filter: blur(18px) saturate(180%);
        border-radius: 18px;
        border: 1.5px solid rgba(0,0,0,0.18);
    }
    
    /* Global button hover effects - lighter colors instead of movement */
    .btn:hover {
        transform: none !important;
        transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease !important;
    }
    
    /* Primary button hover - lighter blue */
    .btn-primary:hover {
        background-color: #4a90e2 !important;
        border-color: #4a90e2 !important;
        color: #ffffff !important;
    }
    
    /* Secondary button hover - lighter gray */
    .btn-secondary:hover {
        background-color: #95a5a6 !important;
        border-color: #95a5a6 !important;
        color: #ffffff !important;
    }
    
    /* Success button hover - lighter green */
    .btn-success:hover {
        background-color: #5cb85c !important;
        border-color: #5cb85c !important;
        color: #ffffff !important;
    }
    
    /* Info button hover - lighter blue */
    .btn-info:hover {
        background-color: #5bc0de !important;
        border-color: #5bc0de !important;
        color: #ffffff !important;
    }
    
    /* Warning button hover - lighter orange */
    .btn-warning:hover {
        background-color: #f0ad4e !important;
        border-color: #f0ad4e !important;
        color: #212529 !important;
    }
    
    /* Danger button hover - lighter red */
    .btn-danger:hover {
        background-color: #e74c3c !important;
        border-color: #e74c3c !important;
        color: #ffffff !important;
    }
    
    /* Light button hover - lighter gray */
    .btn-light:hover {
        background-color: #ecf0f1 !important;
        border-color: #ecf0f1 !important;
        color: #212529 !important;
    }
    
    /* Dark button hover - lighter dark */
    .btn-dark:hover {
        background-color: #5a6268 !important;
        border-color: #5a6268 !important;
        color: #ffffff !important;
    }
    
    /* Outline buttons - keep their existing hover behavior but remove movement */
    .btn-outline-primary:hover,
    .btn-outline-secondary:hover,
    .btn-outline-success:hover,
    .btn-outline-info:hover,
    .btn-outline-warning:hover,
    .btn-outline-danger:hover,
    .btn-outline-light:hover,
    .btn-outline-dark:hover {
        transform: none !important;
        transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease !important;
    }
    
    /* Link button hover - lighter color */
    .btn-link:hover {
        color: #4a90e2 !important;
        text-decoration: none !important;
        transform: none !important;
    }
    
    /* Force all buttons to never move on hover or focus, overriding all other CSS */
    .btn:hover,
    .btn:focus,
    .btn-primary:hover,
    .btn-primary:focus,
    .btn-secondary:hover,
    .btn-secondary:focus,
    .btn-success:hover,
    .btn-success:focus,
    .btn-info:hover,
    .btn-info:focus,
    .btn-warning:hover,
    .btn-warning:focus,
    .btn-danger:hover,
    .btn-danger:focus,
    .btn-light:hover,
    .btn-light:focus,
    .btn-dark:hover,
    .btn-dark:focus,
    .btn-outline-primary:hover,
    .btn-outline-secondary:hover,
    .btn-outline-success:hover,
    .btn-outline-info:hover,
    .btn-outline-warning:hover,
    .btn-outline-danger:hover,
    .btn-outline-light:hover,
    .btn-outline-dark:hover {
        transform: none !important;
        transition: background-color 0.2s, border-color 0.2s, color 0.2s !important;
    }
    </style>
    <?php $__env->stopPush(); ?>
  </head>
  <body style="display: flex; flex-direction: column; min-height: 100vh;">
    <div class="vertical <?php echo e(session('theme', 'dark')); ?>" style="flex: 1 0 auto; display: flex; flex-direction: column; min-height: 100vh;">
      <div class="wrapper" style="flex: 1 0 auto; display: flex; flex-direction: column; min-height: 100vh;">
        <nav class="topnav navbar navbar-light">
          <!-- Sidebar Toggle Button - moved to top left -->
          <button type="button" class="btn btn-link text-muted collapseSidebar p-0 mr-3" id="sidebarToggle">
            <i class="fe fe-menu fe-20"></i>
            <i class="fe fe-x fe-20"></i>
          </button>
          
          <div class="mr-auto d-flex align-items-center">
            <div>
              <h5 class="mb-0 text-dark">
                <?php if(Request::routeIs('admin.dashboard')): ?>
                  Dashboard
                <?php elseif(Request::routeIs('admin.residents.*')): ?>
                  Residents Management
                <?php elseif(Request::routeIs('admin.pre-registrations.*')): ?>
                  Pre-Registration Management
                <?php elseif(Request::routeIs('admin.documents')): ?>
                  Document Requests
                <?php elseif(Request::routeIs('admin.health')): ?>
                  Health Services
                <?php elseif(Request::routeIs('admin.complaints')): ?>
                  Complaints Management
                <?php elseif(Request::routeIs('admin.analytics')): ?>
                  Analytics & Reports
                <?php elseif(Request::routeIs('admin.security.*')): ?>
                  Security Dashboard
                <?php else: ?>
                  Admin Panel
                <?php endif; ?>
              </h5>
              <small class="text-muted"><?php echo e(Auth::user()->role->name); ?> • <?php echo e(\Carbon\Carbon::now()->format('M d, Y • g:i A')); ?></small>
            </div>
          </div>
          
          <ul class="nav">
            <!-- Notifications Bell -->
            <?php
              // Fetch actual records with timestamps instead of just counts
              $pendingDocuments = collect();
              $pendingPreRegistrations = collect();
              $pendingComplaints = collect();
              $pendingHealthRequests = collect();
              $pendingApprovals = collect();
              
              // Check if models exist and user has permission to see them
              if (class_exists('\App\Models\DocumentRequest') && in_array(Auth::user()->role->name, ['Barangay Captain', 'Barangay Secretary'])) {
                $pendingDocuments = \App\Models\DocumentRequest::where('status', 'pending')
                  ->orderBy('created_at', 'desc')
                  ->limit(5)
                  ->get();
              }
              
              if (class_exists('\App\Models\PreRegistration') && in_array(Auth::user()->role->name, ['Barangay Captain', 'Barangay Secretary'])) {
                $pendingPreRegistrations = \App\Models\PreRegistration::where('status', 'pending')
                  ->orderBy('created_at', 'desc')
                  ->limit(5)
                  ->get();
              }
              
              if (class_exists('\App\Models\Complaint') && in_array(Auth::user()->role->name, ['Barangay Captain', 'Complaint Manager'])) {
                $pendingComplaints = \App\Models\Complaint::where('status', 'pending')
                  ->orderBy('created_at', 'desc')
                  ->limit(5)
                  ->get();
              }
              
              if (class_exists('\App\Models\HealthService') && in_array(Auth::user()->role->name, ['Barangay Captain', 'Health Worker'])) {
                $pendingHealthRequests = \App\Models\HealthService::where('status', 'pending')
                  ->orderBy('created_at', 'desc')
                  ->limit(5)
                  ->get();
              }
              
              if (in_array(Auth::user()->role->name, ['Barangay Captain', 'Barangay Secretary', 'Admin'])) {
                // Add any other pending approvals here
                $pendingApprovals = collect(); // Placeholder for approval system
              }
              
              $totalNotifications = $pendingDocuments->count() + $pendingPreRegistrations->count() + $pendingComplaints->count() + $pendingHealthRequests->count() + $pendingApprovals->count();
            ?>
            
            <li class="nav-item nav-notif mr-3">
              <a class="nav-link text-muted my-2" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="fe fe-bell fe-16"></span>
                <?php if($totalNotifications > 0): ?>
                  <span class="dot dot-md bg-success"></span>
                <?php endif; ?>
              </a>
              <div class="dropdown-menu dropdown-menu-right notification-dropdown" aria-labelledby="notificationDropdown">
                <div class="notification-header">
                  <i class="fe fe-bell mr-2"></i>Notifications
                  <?php if($totalNotifications > 0): ?>
                    <span class="float-right badge badge-light"><?php echo e($totalNotifications); ?></span>
                  <?php endif; ?>
                </div>
                
                <?php if($totalNotifications > 0): ?>
                  <!-- Document Requests -->
                  <?php if($pendingDocuments->count() > 0): ?>
                    <?php
                      $latestDocument = $pendingDocuments->first();
                    ?>
                    <a href="<?php echo e(route('admin.documents')); ?>" class="notification-item d-flex">
                      <div class="notification-icon document">
                        <i class="fe fe-file"></i>
                      </div>
                      <div class="notification-content">
                        <div class="notification-title">Document Requests</div>
                        <div class="notification-text"><?php echo e($pendingDocuments->count()); ?> pending document<?php echo e($pendingDocuments->count() > 1 ? 's' : ''); ?> need<?php echo e($pendingDocuments->count() > 1 ? '' : 's'); ?> review</div>
                        <div class="notification-time"><?php echo e($latestDocument ? $latestDocument->created_at->diffForHumans() : 'Just now'); ?></div>
                      </div>
                    </a>
                  <?php endif; ?>
                  
                  <!-- Pre-Registrations -->
                  <?php if($pendingPreRegistrations->count() > 0): ?>
                    <?php
                      $latestPreReg = $pendingPreRegistrations->first();
                    ?>
                    <a href="<?php echo e(route('admin.pre-registrations.index')); ?>" class="notification-item d-flex">
                      <div class="notification-icon resident">
                        <i class="fe fe-user-plus"></i>
                      </div>
                      <div class="notification-content">
                        <div class="notification-title">Pre-Registrations</div>
                        <div class="notification-text"><?php echo e($pendingPreRegistrations->count()); ?> new registration<?php echo e($pendingPreRegistrations->count() > 1 ? 's' : ''); ?> awaiting approval</div>
                        <div class="notification-time"><?php echo e($latestPreReg ? $latestPreReg->created_at->diffForHumans() : 'Just now'); ?></div>
                      </div>
                    </a>
                  <?php endif; ?>
                  
                  <!-- Complaints -->
                  <?php if($pendingComplaints->count() > 0): ?>
                    <?php
                      $latestComplaint = $pendingComplaints->first();
                    ?>
                    <a href="<?php echo e(route('admin.complaints')); ?>" class="notification-item d-flex">
                      <div class="notification-icon complaint">
                        <i class="fe fe-alert-triangle"></i>
                      </div>
                      <div class="notification-content">
                        <div class="notification-title">Complaints</div>
                        <div class="notification-text"><?php echo e($pendingComplaints->count()); ?> unresolved complaint<?php echo e($pendingComplaints->count() > 1 ? 's' : ''); ?> require<?php echo e($pendingComplaints->count() > 1 ? '' : 's'); ?> attention</div>
                        <div class="notification-time"><?php echo e($latestComplaint ? $latestComplaint->created_at->diffForHumans() : 'Just now'); ?></div>
                      </div>
                    </a>
                  <?php endif; ?>
                  
                  <!-- Health Services -->
                  <?php if($pendingHealthRequests->count() > 0): ?>
                    <?php
                      $latestHealth = $pendingHealthRequests->first();
                    ?>
                    <a href="<?php echo e(route('admin.health')); ?>" class="notification-item d-flex">
                      <div class="notification-icon health">
                        <i class="fe fe-heart"></i>
                      </div>
                      <div class="notification-content">
                        <div class="notification-title">Health Services</div>
                        <div class="notification-text"><?php echo e($pendingHealthRequests->count()); ?> health request<?php echo e($pendingHealthRequests->count() > 1 ? 's' : ''); ?> pending review</div>
                        <div class="notification-time"><?php echo e($latestHealth ? $latestHealth->created_at->diffForHumans() : 'Just now'); ?></div>
                      </div>
                    </a>
                  <?php endif; ?>
                  
                  <div class="notification-footer">
                    <a href="<?php echo e(route('admin.dashboard')); ?>">View All Notifications</a>
                  </div>
                <?php else: ?>
                  <div class="empty-notifications">
                    <i class="fe fe-check-circle"></i>
                    <div><strong>All caught up!</strong></div>
                    <div>No pending notifications</div>
                  </div>
                <?php endif; ?>
              </div>
            </li>
            
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-muted pr-0" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="avatar avatar-sm mt-2">
                  <img src="<?php echo e(Auth::user()->profile_photo_url); ?>" alt="<?php echo e(Auth::user()->name); ?>" class="avatar-img rounded-circle">
                </span>
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="<?php echo e(route('admin.profile')); ?>">Settings</a>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                  <?php echo csrf_field(); ?>
                  <button type="submit" class="dropdown-item">Logout</button>
                </form>
              </div>
            </li>
          </ul>
        </nav>
        <aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
          <nav class="vertnav navbar navbar-light">
            <!-- nav bar -->
            <div class="w-100 mb-4 d-flex flex-column">
              <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="<?php echo e(route('admin.dashboard')); ?>">
                <!-- Only one logo visible at a time based on sidebar state -->
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Lumanglipa Logo" class="navbar-brand-img logo-expanded">
                
                <img src="<?php echo e(asset('images/logo-small.png')); ?>" alt="Lumanglipa Logo" class="navbar-brand-img logo-collapsed">
              </a>
              <!-- Modified text div with collapse-hide class -->
              <div class="text-center mb-3 collapse-hide">
                <h6 class="text-uppercase font-weight-bold">LumangLipa Admin</h6>
              </div>
            </div>

            <!-- Sidebar Navigation Menu -->
            <ul class="navbar-nav flex-fill w-100 mb-2">
              <li class="nav-item <?php echo e(Request::routeIs('admin.dashboard') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e(Request::routeIs('admin.dashboard') ? 'active' : ''); ?>">
                  <i class="fe fe-home fe-16"></i>
                  <span class="ml-3 item-text">Dashboard</span>
                </a>
              </li>

              <?php if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Barangay Secretary'])): ?>
              <li class="nav-item <?php echo e(Request::routeIs('admin.documents') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.documents')); ?>" class="nav-link <?php echo e(Request::routeIs('admin.documents') ? 'active' : ''); ?>">
                  <i class="fe fe-file fe-16"></i>
                  <span class="ml-3 item-text">Document Requests</span>
                </a>
              </li>

              <li class="nav-item <?php echo e(Request::routeIs('admin.pre-registrations.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.pre-registrations.index')); ?>" class="nav-link <?php echo e(Request::routeIs('admin.pre-registrations.*') ? 'active' : ''); ?>">
                  <i class="fe fe-user-plus fe-16"></i>
                  <span class="ml-3 item-text">Pre-Registrations</span>
                </a>
              </li>
              
              <li class="nav-item dropdown <?php echo e(Request::routeIs('admin.residents.*') || Request::routeIs('admin.gad.*') || Request::routeIs('admin.senior-citizens.*') ? 'active' : ''); ?>">
                <a href="#residents" data-toggle="collapse" aria-expanded="<?php echo e(Request::routeIs('admin.residents.*') || Request::routeIs('admin.gad.*') || Request::routeIs('admin.senior-citizens.*') ? 'true' : 'false'); ?>" 
                  class="dropdown-toggle nav-link <?php echo e(Request::routeIs('admin.residents.*') || Request::routeIs('admin.gad.*') || Request::routeIs('admin.senior-citizens.*') ? 'active' : ''); ?>">
                  <i class="fe fe-users fe-16"></i>
                  <span class="ml-3 item-text">Residents</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100 <?php echo e(Request::routeIs('admin.residents.*') || Request::routeIs('admin.gad.*') || Request::routeIs('admin.senior-citizens.*') ? 'show' : ''); ?>" id="residents">
                  <li class="nav-item <?php echo e(Request::routeIs('admin.residents.index') ? 'active' : ''); ?>">
                    <a class="nav-link pl-3" href="<?php echo e(route('admin.residents.index')); ?>">
                      <span class="ml-1 item-text">All Residents</span>
                    </a>
                  </li>
                  <li class="nav-item <?php echo e(Request::routeIs('admin.residents.census-data') ? 'active' : ''); ?>">
                    <a class="nav-link pl-3" href="<?php echo e(route('admin.residents.census-data')); ?>">
                      <span class="ml-1 item-text">Census Data</span>
                    </a>
                  </li>
                  <li class="nav-item <?php echo e(Request::routeIs('admin.residents.id.pending') ? 'active' : ''); ?>">
                    <a class="nav-link pl-3" href="<?php echo e(route('admin.residents.id.pending')); ?>">
                      <span class="ml-1 item-text">ID Card Management</span>
                    </a>
                  </li>
                  <li class="nav-item <?php echo e(Request::routeIs('admin.gad.*') ? 'active' : ''); ?>">
                    <a class="nav-link pl-3" href="<?php echo e(route('admin.gad.index')); ?>">
                      <span class="ml-1 item-text">Gender & Development</span>
                    </a>
                  </li>
                  <li class="nav-item <?php echo e(Request::routeIs('admin.senior-citizens.*') ? 'active' : ''); ?>">
                    <a class="nav-link pl-3" href="<?php echo e(route('admin.senior-citizens.index')); ?>">
                      <span class="ml-1 item-text">Senior Citizens</span>
                    </a>
                  </li>
                
                </ul>
              </li>
              <?php endif; ?>

              <?php if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Barangay Secretary'])): ?>
              <!-- Officials Management Link -->
              <li class="nav-item <?php echo e(Request::routeIs('admin.officials.edit-single') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.officials.edit-single')); ?>" class="nav-link <?php echo e(Request::routeIs('admin.officials.edit-single') ? 'active' : ''); ?>">
                  <i class="fe fe-user-check fe-16"></i>
                  <span class="ml-3 item-text">Officials</span>
                </a>
              </li>
              <?php endif; ?>

              <?php if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Barangay Secretary', 'Admin'])): ?>
              <li class="nav-item dropdown <?php echo e(Request::routeIs('admin.approvals*') || Request::routeIs('admin.access-requests*') ? 'active' : ''); ?>">
                <a href="#approvals" data-toggle="collapse" aria-expanded="<?php echo e(Request::routeIs('admin.approvals*') || Request::routeIs('admin.access-requests*') ? 'true' : 'false'); ?>" 
                  class="dropdown-toggle nav-link <?php echo e(Request::routeIs('admin.approvals*') || Request::routeIs('admin.access-requests*') ? 'active' : ''); ?>">
                  <i class="fe fe-check-circle fe-16"></i>
                  <span class="ml-3 item-text">Approvals</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100 <?php echo e(Request::routeIs('admin.approvals*') || Request::routeIs('admin.access-requests*') ? 'show' : ''); ?>" id="approvals">
                  <li class="nav-item approval-menu-item <?php echo e(Request::routeIs('admin.approvals.index') ? 'active' : ''); ?>">
                    <a class="nav-link pl-3" href="<?php echo e(route('admin.approvals.index')); ?>">
                      <span class="ml-1 item-text">All Approvals</span>
                    </a>
                  </li>
                  <li class="nav-item access-request-menu-item <?php echo e(Request::routeIs('admin.access-requests.*') ? 'active' : ''); ?>">
                    <a class="nav-link pl-3" href="<?php echo e(route('admin.access-requests.index')); ?>">
                      <span class="ml-1 item-text">Access Requests</span>
                    </a>
                  </li>
                </ul>
              </li>
              <?php endif; ?>

              <?php if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Health Worker', 'Barangay Secretary'])): ?>
              <li class="nav-item <?php echo e(Request::routeIs('admin.health') || Request::routeIs('admin.health-services.*') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.health')); ?>" class="nav-link <?php echo e(Request::routeIs('admin.health') || Request::routeIs('admin.health-services.*') ? 'active' : ''); ?>">
                  <i class="fe fe-heart fe-16"></i>
                  <span class="ml-3 item-text">Health Services</span>
                </a>
              </li>
              <?php endif; ?>

              <?php if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Complaint Manager'])): ?>
              <li class="nav-item <?php echo e(Request::routeIs('admin.complaints') || Request::routeIs('admin.complaint-management') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.complaints')); ?>" class="nav-link <?php echo e(Request::routeIs('admin.complaints') || Request::routeIs('admin.complaint-management') ? 'active' : ''); ?>">
                  <i class="fe fe-alert-triangle fe-16"></i>
                  <span class="ml-3 item-text">Complaints</span>
                </a>
              </li>
              <?php endif; ?>

              <?php if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Barangay Secretary'])): ?>
              <li class="nav-item <?php echo e(Request::routeIs('admin.analytics') ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.analytics')); ?>" class="nav-link <?php echo e(Request::routeIs('admin.analytics') ? 'active' : ''); ?>">
                  <i class="fe fe-bar-chart-2 fe-16"></i>
                  <span class="ml-3 item-text">Analytics</span>
                </a>
              </li>
              
              <?php if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Barangay Secretary', 'Admin'])): ?>
              <li class="nav-item dropdown <?php echo e(Request::routeIs('admin.security.dashboard') || Request::routeIs('admin.security.audit-logs') ? 'active' : ''); ?>">
                <a href="#security" data-toggle="collapse" aria-expanded="<?php echo e(Request::routeIs('admin.security.dashboard') || Request::routeIs('admin.security.audit-logs') ? 'true' : 'false'); ?>" 
                  class="dropdown-toggle nav-link <?php echo e(Request::routeIs('admin.security.dashboard') || Request::routeIs('admin.security.audit-logs') ? 'active' : ''); ?>">
                  <i class="fe fe-shield fe-16"></i>
                  <span class="ml-3 item-text">Security</span>
                </a>
                <ul class="collapse list-unstyled pl-4 w-100 <?php echo e(Request::routeIs('admin.security.dashboard') || Request::routeIs('admin.security.audit-logs') ? 'show' : ''); ?>" id="security">
                  <li class="nav-item <?php echo e(Request::routeIs('admin.security.dashboard') ? 'active' : ''); ?>">
                    <a class="nav-link pl-3" href="<?php echo e(route('admin.security.dashboard')); ?>">
                      <span class="ml-1 item-text">Security Dashboard</span>
                    </a>
                  </li>
                  <li class="nav-item <?php echo e(Request::routeIs('admin.security.audit-logs') ? 'active' : ''); ?>">
                    <a class="nav-link pl-3" href="<?php echo e(route('admin.security.audit-logs')); ?>">
                      <span class="ml-1 item-text">Audit Logs</span>
                    </a>
                  </li>
                </ul>
              </li>
              <?php endif; ?>
              <?php endif; ?>
            </ul>
          </nav>
        </aside>

        <main role="main" class="main-content" style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); flex: 1 0 auto; display: flex; flex-direction: column; min-height: 0;">
          <div class="container-fluid" style="flex: 1 1 auto; padding-bottom: 0; display: flex; flex-direction: column; min-height: 0;">
            <div class="row justify-content-center" style="flex: 1 1 auto;">
              <div class="col-12" style="flex: 1 1 auto; display: flex; flex-direction: column;">
                <div class="card shadow rounded-lg border-0 glassmorph-card" style="padding: 2rem 1.5rem; margin-bottom: 0; flex: 1 1 auto;">
                  
                  <?php echo $__env->yieldContent('content'); ?>
                </div>
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
                      <p>Settings</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>
        <footer class="admin-footer text-center py-3" style="background: #f8f9fa; border-top: 1px solid #e0e0e0; flex-shrink: 0; margin-top: 0;">
          <span class="text-muted">&copy; <?php echo e(date('Y')); ?> Lumanglipa Barangay Management System. All rights reserved.</span>
        </footer>
      </div>

      <!-- Core JavaScript (loaded immediately) -->

      <script src="<?php echo e(asset('admin/dark/js/jquery.min.js')); ?>"></script>
      <script src="<?php echo e(asset('js/sidebar-fix.js')); ?>"></script>
      <script src="<?php echo e(asset('admin/dark/js/popper.min.js')); ?>"></script>
      <script src="<?php echo e(asset('admin/dark/js/bootstrap.min.js')); ?>"></script>

      <!-- DataTables JavaScript -->
      <script src="<?php echo e(asset('admin/dark/js/jquery.dataTables.min.js')); ?>"></script>
      <script src="<?php echo e(asset('admin/dark/js/dataTables.bootstrap4.min.js')); ?>"></script>
      <script src="<?php echo e(asset('js/table-init.js')); ?>"></script>

      <!-- Theme and sidebar management - with error handling -->
      <script>
        // Error handling wrapper for script loading
        function loadScriptSafely(url, callback) {
          var script = document.createElement('script');
          script.src = url;
          
          script.onload = function() {
            if (callback) callback(true);
          };
          
          script.onerror = function() {
            console.error('Failed to load script: ' + url);
            if (callback) callback(false);
          };
          
          document.body.appendChild(script);
        }
        
        // NOTE: Sidebar toggle functionality is now handled by sidebar-toggle-fix.js
        // This prevents conflicts between multiple toggle implementations
        console.log('Master layout loaded - sidebar toggle handled by external script');
      </script>
      
      <!-- Deferred JavaScript (loaded after page renders) -->
      <script>
        // Performance-optimized script loading
        document.addEventListener('DOMContentLoaded', function() {
          // Scripts to load in order, but without blocking page rendering
          const deferredScripts = [
      
            '<?php echo e(asset("admin/dark/js/simplebar.min.js")); ?>',
            '<?php echo e(asset("admin/dark/js/daterangepicker.js")); ?>',
            '<?php echo e(asset("admin/dark/js/jquery.stickOnScroll.js")); ?>',
            '<?php echo e(asset("admin/dark/js/tinycolor-min.js")); ?>',
            '<?php echo e(asset("js/admin-custom.js")); ?>'
          ];
          
          // Function to load a script with error handling
          function loadScript(src, callback) {
            const script = document.createElement('script');
            script.src = src;
            
            // When script loads, run callback
            script.onload = callback;
            
            // If script fails, still try to continue
            script.onerror = function() {
              console.error('Failed to load script: ' + src);
              callback(); // Continue despite error
            };
            
            // Add script to document
            document.body.appendChild(script);
          }
          
          // Load scripts in sequence
          function loadNextScript(index) {
            if (index >= deferredScripts.length) {
              return;
            }
            
            loadScript(deferredScripts[index], function() {
              loadNextScript(index + 1);
            });
          }
          
          // Start loading scripts
          loadNextScript(0);
        });
      </script>

      <?php echo $__env->yieldPushContent('scripts'); ?>
      <style>
      html, body, .vertical, .wrapper {
        height: 100%;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
      }
      .main-content, .container-fluid {
        flex: 1 0 auto;
        min-height: 0;
        display: flex;
        flex-direction: column;
      }
      .admin-footer {
        flex-shrink: 0;
        margin-top: 0 !important;
      }
      body .btn:hover,
      body .btn:focus,
      body .btn-primary:hover,
      body .btn-primary:focus,
      body .btn-secondary:hover,
      body .btn-secondary:focus,
      body .btn-success:hover,
      body .btn-success:focus,
      body .btn-info:hover,
      body .btn-info:focus,
      body .btn-warning:hover,
      body .btn-warning:focus,
      body .btn-danger:hover,
      body .btn-danger:focus,
      body .btn-light:hover,
      body .btn-light:focus,
      body .btn-dark:hover,
      body .btn-dark:focus,
      body .btn-outline-primary:hover,
      body .btn-outline-secondary:hover,
      body .btn-outline-success:hover,
      body .btn-outline-info:hover,
      body .btn-outline-warning:hover,
      body .btn-outline-danger:hover,
      body .btn-outline-light:hover,
      body .btn-outline-dark:hover {
          transform: none !important;
          transition: background-color 0.2s, border-color 0.2s, color 0.2s !important;
      }
      </style>
      <!-- Sidebar behavior from template's app.js -->
      
      </body>
    </div>
</html>
<?php /**PATH /var/www/html/lumanglipa/resources/views/layouts/admin/master.blade.php ENDPATH**/ ?>