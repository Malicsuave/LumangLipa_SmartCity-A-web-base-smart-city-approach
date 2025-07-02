<!doctype html>
<html lang="en" data-theme="{{ session('theme', 'dark') }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Lumanglipa Barangay Management System">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>{{ config('app.name') }} - Admin Panel</title>
    
    <!-- Performance monitoring script -->
    <script src="{{ asset('js/performance-monitor.js') }}"></script>
    
    <!-- DEFINITIVE SIDEBAR TOGGLE FIX - Must load first -->
    <script src="{{ asset('js/sidebar-toggle-fix.js') }}"></script>
    
    <!-- Simple bar CSS -->
    <link rel="stylesheet" href="{{ asset('admin/dark/css/simplebar.css') }}">
    <!-- Fonts CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('admin/dark/css/dataTables.bootstrap4.css') }}">
    
    <!-- CSS Validation Fixes - Suppresses browser console warnings -->
    <link rel="stylesheet" href="{{ asset('css/css-validation-fixes.css') }}">
    
    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('admin/dark/css/app-light.css') }}" id="lightTheme">
   
    <!-- Search icon CSS - works in both themes -->
    <link rel="stylesheet" href="{{ asset('css/search-icon.css') }}">
    <!-- UI Enhancements CSS - works in both themes -->
    <link rel="stylesheet" href="{{ asset('css/ui-enhancements.css') }}">
    <!-- Complaint metrics CSS - specifically for complaint cards -->
    <link rel="stylesheet" href="{{ asset('css/complaint-metrics.css') }}">
    <!-- Health metrics CSS - specifically for health service cards -->
    <link rel="stylesheet" href="{{ asset('css/health-metrics.css') }}">
    <!-- Complaint table styling - for improved table appearance -->
    <link rel="stylesheet" href="{{ asset('css/complaint-table.css') }}">
    <!-- Metric cards fix - overrides other styles -->
    
    <!-- Hover card effects - prevents loss of styling on hover -->
    <link rel="stylesheet" href="{{ asset('css/hover-card.css') }}">
    <!-- Light mode cards - forces proper background color in light mode -->
    <link rel="stylesheet" href="{{ asset('css/light-mode-cards.css') }}">
    <!-- Form validation styling - consistent validation error messages -->
    <link rel="stylesheet" href="{{ asset('css/validation.css') }}">
    <!-- Metric card animations - consistent animations across all admin pages -->
    <link rel="stylesheet" href="{{ asset('css/admin/metric-card-animations.css') }}">
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    <!-- Admin Layout Styles - contains all layout-specific CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">

    @stack('styles')
  </head>
  <body class="vertical {{ session('theme', 'dark') }}">
    <div class="wrapper">
      <nav class="topnav navbar navbar-light">
        <!-- Sidebar Toggle Button - moved to top left -->
        <button type="button" class="btn btn-link text-muted collapseSidebar p-0 mr-3" id="sidebarToggle">
          <i class="fe fe-menu fe-20"></i>
          <i class="fe fe-x fe-20"></i>
        </button>
        
        <div class="mr-auto d-flex align-items-center">
          <div>
            <h5 class="mb-0 text-dark">
              @if(Request::routeIs('admin.dashboard'))
                Dashboard
              @elseif(Request::routeIs('admin.residents.*'))
                Residents Management
              @elseif(Request::routeIs('admin.pre-registrations.*'))
                Pre-Registration Management
              @elseif(Request::routeIs('admin.documents'))
                Document Requests
              @elseif(Request::routeIs('admin.health'))
                Health Services
              @elseif(Request::routeIs('admin.complaints'))
                Complaints Management
              @elseif(Request::routeIs('admin.analytics'))
                Analytics & Reports
              @elseif(Request::routeIs('admin.security.*'))
                Security Dashboard
              @else
                Admin Panel
              @endif
            </h5>
            <small class="text-muted">{{ Auth::user()->role->name }} • {{ \Carbon\Carbon::now()->format('M d, Y • g:i A') }}</small>
          </div>
        </div>
        
        <ul class="nav">
          <!-- Notifications Bell -->
          @php
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
          @endphp
          
          <li class="nav-item nav-notif mr-3">
            <a class="nav-link text-muted my-2" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="fe fe-bell fe-16"></span>
              @if($totalNotifications > 0)
                <span class="dot dot-md bg-success"></span>
              @endif
            </a>
            <div class="dropdown-menu dropdown-menu-right notification-dropdown" aria-labelledby="notificationDropdown">
              <div class="notification-header">
                <i class="fe fe-bell mr-2"></i>Notifications
                @if($totalNotifications > 0)
                  <span class="float-right badge badge-light">{{ $totalNotifications }}</span>
                @endif
              </div>
              
              @if($totalNotifications > 0)
                <!-- Document Requests -->
                @if($pendingDocuments->count() > 0)
                  @php
                    $latestDocument = $pendingDocuments->first();
                  @endphp
                  <a href="{{ route('admin.documents') }}" class="notification-item d-flex">
                    <div class="notification-icon document">
                      <i class="fe fe-file"></i>
                    </div>
                    <div class="notification-content">
                      <div class="notification-title">Document Requests</div>
                      <div class="notification-text">{{ $pendingDocuments->count() }} pending document{{ $pendingDocuments->count() > 1 ? 's' : '' }} need{{ $pendingDocuments->count() > 1 ? '' : 's' }} review</div>
                      <div class="notification-time">{{ $latestDocument ? $latestDocument->created_at->diffForHumans() : 'Just now' }}</div>
                    </div>
                  </a>
                @endif
                
                <!-- Pre-Registrations -->
                @if($pendingPreRegistrations->count() > 0)
                  @php
                    $latestPreReg = $pendingPreRegistrations->first();
                  @endphp
                  <a href="{{ route('admin.pre-registrations.index') }}" class="notification-item d-flex">
                    <div class="notification-icon resident">
                      <i class="fe fe-user-plus"></i>
                    </div>
                    <div class="notification-content">
                      <div class="notification-title">Pre-Registrations</div>
                      <div class="notification-text">{{ $pendingPreRegistrations->count() }} new registration{{ $pendingPreRegistrations->count() > 1 ? 's' : '' }} awaiting approval</div>
                      <div class="notification-time">{{ $latestPreReg ? $latestPreReg->created_at->diffForHumans() : 'Just now' }}</div>
                    </div>
                  </a>
                @endif
                
                <!-- Complaints -->
                @if($pendingComplaints->count() > 0)
                  @php
                    $latestComplaint = $pendingComplaints->first();
                  @endphp
                  <a href="{{ route('admin.complaints') }}" class="notification-item d-flex">
                    <div class="notification-icon complaint">
                      <i class="fe fe-alert-triangle"></i>
                    </div>
                    <div class="notification-content">
                      <div class="notification-title">Complaints</div>
                      <div class="notification-text">{{ $pendingComplaints->count() }} unresolved complaint{{ $pendingComplaints->count() > 1 ? 's' : '' }} require{{ $pendingComplaints->count() > 1 ? '' : 's' }} attention</div>
                      <div class="notification-time">{{ $latestComplaint ? $latestComplaint->created_at->diffForHumans() : 'Just now' }}</div>
                    </div>
                  </a>
                @endif
                
                <!-- Health Services -->
                @if($pendingHealthRequests->count() > 0)
                  @php
                    $latestHealth = $pendingHealthRequests->first();
                  @endphp
                  <a href="{{ route('admin.health') }}" class="notification-item d-flex">
                    <div class="notification-icon health">
                      <i class="fe fe-heart"></i>
                    </div>
                    <div class="notification-content">
                      <div class="notification-title">Health Services</div>
                      <div class="notification-text">{{ $pendingHealthRequests->count() }} health request{{ $pendingHealthRequests->count() > 1 ? 's' : '' }} pending review</div>
                      <div class="notification-time">{{ $latestHealth ? $latestHealth->created_at->diffForHumans() : 'Just now' }}</div>
                    </div>
                  </a>
                @endif
                
                <div class="notification-footer">
                  <a href="{{ route('admin.dashboard') }}">View All Notifications</a>
                </div>
              @else
                <div class="empty-notifications">
                  <i class="fe fe-check-circle"></i>
                  <div><strong>All caught up!</strong></div>
                  <div>No pending notifications</div>
                </div>
              @endif
            </div>
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
        <nav class="vertnav navbar navbar-light">
          <!-- nav bar -->
          <div class="w-100 mb-4 d-flex flex-column">
            <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="{{ route('admin.dashboard') }}">
              <!-- Only one logo visible at a time based on sidebar state -->
              <img src="{{ asset('images/logo.png') }}" alt="Lumanglipa Logo" class="navbar-brand-img logo-expanded">
              <img src="{{ asset('images/logo-small.png') }}" alt="Lumanglipa Logo" class="navbar-brand-img logo-collapsed">
            </a>
            <!-- Modified text div with collapse-hide class -->
            <div class="text-center mb-3 collapse-hide">
              <h6 class="text-uppercase font-weight-bold">LumangLipa Admin</h6>
            </div>
          </div>

          <!-- Sidebar Navigation Menu -->
          <ul class="navbar-nav flex-fill w-100 mb-2">
            <li class="nav-item {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
              <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fe fe-home fe-16"></i>
                <span class="ml-3 item-text">Dashboard</span>
              </a>
            </li>

            @if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Barangay Secretary']))
            <li class="nav-item {{ Request::routeIs('admin.documents') ? 'active' : '' }}">
              <a href="{{ route('admin.documents') }}" class="nav-link {{ Request::routeIs('admin.documents') ? 'active' : '' }}">
                <i class="fe fe-file fe-16"></i>
                <span class="ml-3 item-text">Document Requests</span>
              </a>
            </li>

            <li class="nav-item {{ Request::routeIs('admin.pre-registrations.*') ? 'active' : '' }}">
              <a href="{{ route('admin.pre-registrations.index') }}" class="nav-link {{ Request::routeIs('admin.pre-registrations.*') ? 'active' : '' }}">
                <i class="fe fe-user-plus fe-16"></i>
                <span class="ml-3 item-text">Pre-Registrations</span>
              </a>
            </li>
            
            <li class="nav-item dropdown {{ Request::routeIs('admin.residents.*') || Request::routeIs('admin.gad.*') || Request::routeIs('admin.senior-citizens.*') ? 'active' : '' }}">
              <a href="#residents" data-toggle="collapse" aria-expanded="{{ Request::routeIs('admin.residents.*') || Request::routeIs('admin.gad.*') || Request::routeIs('admin.senior-citizens.*') ? 'true' : 'false' }}" 
                class="dropdown-toggle nav-link {{ Request::routeIs('admin.residents.*') || Request::routeIs('admin.gad.*') || Request::routeIs('admin.senior-citizens.*') ? 'active' : '' }}">
                <i class="fe fe-users fe-16"></i>
                <span class="ml-3 item-text">Residents</span>
              </a>
              <ul class="collapse list-unstyled pl-4 w-100 {{ Request::routeIs('admin.residents.*') || Request::routeIs('admin.gad.*') || Request::routeIs('admin.senior-citizens.*') ? 'show' : '' }}" id="residents">
                <li class="nav-item {{ Request::routeIs('admin.residents.index') ? 'active' : '' }}">
                  <a class="nav-link pl-3" href="{{ route('admin.residents.index') }}">
                    <span class="ml-1 item-text">All Residents</span>
                  </a>
                </li>
                <li class="nav-item {{ Request::routeIs('admin.residents.id.pending') ? 'active' : '' }}">
                  <a class="nav-link pl-3" href="{{ route('admin.residents.id.pending') }}">
                    <span class="ml-1 item-text">ID Card Management</span>
                  </a>
                </li>
                <li class="nav-item {{ Request::routeIs('admin.gad.*') ? 'active' : '' }}">
                  <a class="nav-link pl-3" href="{{ route('admin.gad.index') }}">
                    <span class="ml-1 item-text">Gender & Development</span>
                  </a>
                </li>
                <li class="nav-item {{ Request::routeIs('admin.senior-citizens.*') ? 'active' : '' }}">
                  <a class="nav-link pl-3" href="{{ route('admin.senior-citizens.index') }}">
                    <span class="ml-1 item-text">Senior Citizens</span>
                  </a>
                </li>
              </ul>
            </li>
            @endif

            @if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Barangay Secretary', 'Admin']))
            <li class="nav-item dropdown {{ Request::routeIs('admin.approvals*') || Request::routeIs('admin.access-requests*') ? 'active' : '' }}">
              <a href="#approvals" data-toggle="collapse" aria-expanded="{{ Request::routeIs('admin.approvals*') || Request::routeIs('admin.access-requests*') ? 'true' : 'false' }}" 
                class="dropdown-toggle nav-link {{ Request::routeIs('admin.approvals*') || Request::routeIs('admin.access-requests*') ? 'active' : '' }}">
                <i class="fe fe-check-circle fe-16"></i>
                <span class="ml-3 item-text">Approvals</span>
              </a>
              <ul class="collapse list-unstyled pl-4 w-100 {{ Request::routeIs('admin.approvals*') || Request::routeIs('admin.access-requests*') ? 'show' : '' }}" id="approvals">
                <li class="nav-item approval-menu-item {{ Request::routeIs('admin.approvals.index') ? 'active' : '' }}">
                  <a class="nav-link pl-3" href="{{ route('admin.approvals.index') }}">
                    <span class="ml-1 item-text">All Approvals</span>
                  </a>
                </li>
                <li class="nav-item access-request-menu-item {{ Request::routeIs('admin.access-requests.*') ? 'active' : '' }}">
                  <a class="nav-link pl-3" href="{{ route('admin.access-requests.index') }}">
                    <span class="ml-1 item-text">Access Requests</span>
                  </a>
                </li>
              </ul>
            </li>
            @endif

            @if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Health Worker']))
            <li class="nav-item {{ Request::routeIs('admin.health') ? 'active' : '' }}">
              <a href="{{ route('admin.health') }}" class="nav-link {{ Request::routeIs('admin.health') ? 'active' : '' }}">
                <i class="fe fe-heart fe-16"></i>
                <span class="ml-3 item-text">Health Services</span>
              </a>
            </li>
            @endif

            @if(in_array(Auth::user()->role->name, ['Barangay Captain', 'Complaint Manager']))
            <li class="nav-item {{ Request::routeIs('admin.complaints') ? 'active' : '' }}">
              <a href="{{ route('admin.complaints') }}" class="nav-link {{ Request::routeIs('admin.complaints') ? 'active' : '' }}">
                <i class="fe fe-alert-triangle fe-16"></i>
                <span class="ml-3 item-text">Complaints</span>
              </a>
            </li>
            @endif

            @if(Auth::user()->role->name === 'Barangay Captain')
            <li class="nav-item {{ Request::routeIs('admin.analytics') ? 'active' : '' }}">
              <a href="{{ route('admin.analytics') }}" class="nav-link {{ Request::routeIs('admin.analytics') ? 'active' : '' }}">
                <i class="fe fe-bar-chart-2 fe-16"></i>
                <span class="ml-3 item-text">Analytics</span>
              </a>
            </li>
            
            <li class="nav-item {{ Request::routeIs('admin.security.*') ? 'active' : '' }}">
              <a href="{{ route('admin.security.dashboard') }}" class="nav-link {{ Request::routeIs('admin.security.*') ? 'active' : '' }}">
                <i class="fe fe-shield fe-16"></i>
                <span class="ml-3 item-text">Security</span>
              </a>
            </li>
            @endif
          </ul>
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

    <!-- Core JavaScript (loaded immediately) -->
    <script src="{{ asset('admin/dark/js/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/dark/js/popper.min.js') }}"></script>
    <script src="{{ asset('admin/dark/js/bootstrap.min.js') }}"></script>

    <!-- DataTables JavaScript -->
    <script src="{{ asset('admin/dark/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/dark/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/table-init.js') }}"></script>

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
    
          '{{ asset("admin/dark/js/simplebar.min.js") }}',
          '{{ asset("admin/dark/js/daterangepicker.js") }}',
          '{{ asset("admin/dark/js/jquery.stickOnScroll.js") }}',
          '{{ asset("admin/dark/js/tinycolor-min.js") }}',
          '{{ asset("js/admin-custom.js") }}'
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

    @stack('scripts')
  </body>
</html>
