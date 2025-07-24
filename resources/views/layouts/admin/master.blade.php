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

    <!-- Admin Chatbot -->
    <div class="admin-chatbot-container">
        <button class="admin-chatbot-button" id="adminChatbotToggle">
            🤖
        </button>
        
        <div class="admin-chatbot-window" id="adminChatbotWindow">
            <div class="admin-chatbot-header">
                <div class="header-left">
                    <span class="robot-icon">🤖</span>
                    <h5 id="adminChatbotTitle">Live Chat Support</h5>
                </div>
                <div class="header-right">
                    <button class="language-toggle" id="adminLanguageToggle">EN</button>
                    <button class="admin-chatbot-minimize" id="adminChatbotMinimize">−</button>
                    <button class="admin-chatbot-close" id="adminChatbotClose">&times;</button>
                </div>
            </div>
            
            <!-- Conversation List View -->
            <div class="conversation-list-view" id="conversationListView">
                <div class="conversation-header">
                    <h6>Active Conversations</h6>
                    <button class="refresh-btn" id="refreshConversations">
                        <i class="fe fe-refresh-cw"></i>
                    </button>
                </div>
                
                <div class="conversation-search">
                    <input type="text" placeholder="Search conversations..." id="conversationSearch">
                </div>
                
                <div class="conversations-container" id="conversationsContainer">
                    <div class="loading-conversations">
                        <div class="loading-spinner"></div>
                        <p>Loading conversations...</p>
                    </div>
                </div>
            </div>
            
            <!-- Individual Conversation View -->
            <div class="conversation-detail-view" id="conversationDetailView" style="display: none;">
                <div class="conversation-back">
                    <button class="back-btn" id="backToList">
                        <i class="fe fe-arrow-left"></i> Back to List
                    </button>
                    <div class="conversation-user-info" id="conversationUserInfo">
                        <!-- User info will be populated here -->
                    </div>
                </div>
                
                <div class="conversation-messages" id="conversationMessages">
                    <!-- Messages will be populated here -->
                </div>
                
                <div class="admin-response-area">
                    <textarea 
                        class="admin-response-input" 
                        id="adminResponseInput" 
                        placeholder="Type your response to the user..."
                        rows="2"
                    ></textarea>
                    <button class="admin-response-send" id="adminResponseSend">
                        <i class="fe fe-send"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Chatbot Styles -->
    <style>
    .admin-chatbot-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1050;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    .admin-chatbot-button {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3F9EDD 0%, #2A7BC4 100%);
        color: white;
        border: none;
        cursor: pointer;
        font-size: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 25px rgba(63, 158, 221, 0.3);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .admin-chatbot-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
        border-radius: 50%;
    }
    
    .admin-chatbot-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(63, 158, 221, 0.4);
        background: linear-gradient(135deg, #2A7BC4 0%, #1E5A9A 100%);
    }
    
    .admin-chatbot-button:active {
        transform: translateY(-1px);
    }
    
    .admin-chatbot-window {
        position: absolute;
        bottom: 80px;
        right: 0;
        width: 350px;
        height: 500px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        display: none;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid rgba(63, 158, 221, 0.1);
    }
    
    .dark .admin-chatbot-window {
        background: white;
        border-color: rgba(63, 158, 221, 0.1);
    }
    
    .admin-chatbot-header {
        background: linear-gradient(135deg, #3F9EDD 0%, #2A7BC4 100%);
        color: white;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 20px 20px 0 0;
    }
    
    .header-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .header-left h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }
    
    .robot-icon {
        font-size: 20px;
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-5px); }
    }
    
    .chat-icon {
        font-size: 18px;
    }
    
    .header-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .language-toggle {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .language-toggle:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.5);
    }
    
    .admin-chatbot-minimize,
    .admin-chatbot-close {
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
    }
    
    .admin-chatbot-minimize:hover,
    .admin-chatbot-close:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg);
    }
    
    /* Conversation List View Styles */
    .conversation-list-view {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #f8f9fa;
    }
    
    .dark .conversation-list-view {
        background: #f8f9fa;
    }
    
    .conversation-header {
        padding: 15px 20px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .dark .conversation-header {
        border-color: #e9ecef;
    }
    
    .conversation-header h6 {
        margin: 0;
        font-size: 14px;
        font-weight: 600;
        color: #212529;
    }
    
    .dark .conversation-header h6 {
        color: #212529;
    }
    
    .refresh-btn {
        background: none;
        border: none;
        color: #3F9EDD;
        cursor: pointer;
        padding: 5px;
        border-radius: 50%;
        transition: background-color 0.2s;
    }
    
    .refresh-btn:hover {
        background: rgba(63, 158, 221, 0.1);
    }
    
    .conversation-search {
        padding: 10px 20px;
        border-bottom: 1px solid #e9ecef;
    }
    
    .dark .conversation-search {
        border-color: #e9ecef;
    }
    
    .conversation-search input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e9ecef;
        border-radius: 20px;
        font-size: 12px;
        background: white;
        color: #212529;
    }
    
    .dark .conversation-search input {
        background: white;
        border-color: #e9ecef;
        color: #212529;
    }
    
    .conversations-container {
        flex: 1;
        overflow-y: auto;
        padding: 10px 0;
        height: calc(100vh - 300px);
        max-height: calc(100vh - 300px);
        min-height: 200px;
    }
    
    .conversations-container::-webkit-scrollbar {
        width: 6px;
    }
    
    .conversations-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .conversations-container::-webkit-scrollbar-thumb {
        background: #3F9EDD;
        border-radius: 10px;
    }
    
    .conversation-item {
        padding: 12px 20px;
        border-bottom: 1px solid var(--bs-border-color, #dee2e6);
        cursor: pointer;
        transition: background-color 0.2s;
        position: relative;
    }
    
    .dark .conversation-item {
        border-color: var(--bs-border-color-dark, #495057);
    }
    
    .conversation-item:hover {
        background: rgba(63, 158, 221, 0.05);
    }
    
    .conversation-item.active {
        background: rgba(63, 158, 221, 0.1);
        border-left: 3px solid #3F9EDD;
    }
    
    .conversation-user {
        font-weight: 600;
        font-size: 13px;
        color: #212529;
        margin-bottom: 4px;
    }
    
    .dark .conversation-user {
        color: #212529;
    }
    
    .conversation-preview {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 4px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .conversation-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 11px;
        color: #6c757d;
    }
    
    .conversation-status {
        padding: 2px 6px;
        border-radius: 10px;
        font-size: 10px;
        font-weight: 500;
    }
    
    .conversation-status.active {
        background: #d4edda;
        color: #155724;
    }
    
    .conversation-status.pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .conversation-status.resolved {
        background: #d1ecf1;
        color: #0c5460;
    }
    
    .loading-conversations {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        color: #6c757d;
    }
    
    .loading-spinner {
        width: 30px;
        height: 30px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #3F9EDD;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 10px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Conversation Detail View Styles */
    .conversation-detail-view {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #f8f9fa;
        height: 100%;
        max-height: 100%;
        overflow: hidden;
    }
    
    .dark .conversation-detail-view {
        background: #f8f9fa;
    }
    
    .conversation-back {
        padding: 15px 20px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .dark .conversation-back {
        border-color: #e9ecef;
    }
    
    .back-btn {
        background: none;
        border: none;
        color: #3F9EDD;
        cursor: pointer;
        padding: 5px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        transition: background-color 0.2s;
    }
    
    .back-btn:hover {
        background: rgba(63, 158, 221, 0.1);
    }
    
    .conversation-user-info {
        flex: 1;
    }
    
    .conversation-user-name {
        font-weight: 600;
        font-size: 14px;
        color: #212529;
        margin-bottom: 2px;
    }
    
    .dark .conversation-user-name {
        color: #212529;
    }
    
    .conversation-user-email {
        font-size: 11px;
        color: #6c757d;
    }
    
    .conversation-messages {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #f8f9fa;
        display: flex;
        flex-direction: column;
        gap: 15px;
        max-height: calc(100% - 140px);
        min-height: 300px;
    }
    
    .conversation-messages::-webkit-scrollbar {
        width: 6px;
    }
    
    .conversation-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .conversation-messages::-webkit-scrollbar-thumb {
        background: #3F9EDD;
        border-radius: 10px;
    }
    
    .message {
        margin-bottom: 15px;
        padding: 12px 16px;
        border-radius: 18px;
        max-width: 85%;
        word-wrap: break-word;
        font-size: 14px;
        line-height: 1.4;
        animation: fadeIn 0.3s ease;
        position: relative;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .message.user {
        background: white;
        color: #333;
        border: 1px solid #e9ecef;
        border-bottom-left-radius: 5px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        align-self: flex-start;
        margin-right: auto;
        margin-left: 0;
    }
    
    .dark .message.user {
        background: white;
        color: #333;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    
    .message.bot {
        background: white;
        color: #333;
        border: 1px solid #e9ecef;
        border-bottom-left-radius: 5px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        align-self: flex-start;
        margin-right: auto;
        margin-left: 0;
    }
    
    .dark .message.bot {
        background: white;
        color: #333;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    
    .message.admin {
        background: linear-gradient(135deg, #3F9EDD 0%, #2A7BC4 100%);
        color: white;
        margin-left: auto;
        border-bottom-right-radius: 5px;
        box-shadow: 0 2px 8px rgba(63, 158, 221, 0.2);
        align-self: flex-end;
        margin-right: 0;
    }
    
    .message-timestamp {
        font-size: 10px;
        opacity: 0.7;
        margin-top: 4px;
    }
    
    .message.user .message-timestamp {
        text-align: left;
    }
    
    .message.admin .message-timestamp {
        text-align: right;
    }
    
    .admin-response-area {
        padding: 15px 20px;
        background: white;
        border-top: 1px solid #e9ecef;
        display: flex;
        align-items: end;
        gap: 10px;
        border-radius: 0 0 20px 20px;
        flex-shrink: 0;
        min-height: 70px;
        position: sticky;
        bottom: 0;
        z-index: 10;
    }
    
    .dark .admin-response-area {
        background: white;
        border-color: #e9ecef;
    }
    
    .admin-response-input {
        flex: 1;
        border: 1px solid #e9ecef;
        border-radius: 20px;
        padding: 10px 15px;
        font-size: 14px;
        resize: none;
        outline: none;
        transition: all 0.3s ease;
        background: #f8f9fa;
        min-height: 20px;
        max-height: 80px;
        color: #212529;
        line-height: 1.4;
        overflow-y: auto;
    }
    
    .dark .admin-response-input {
        background: #f8f9fa;
        border-color: #e9ecef;
        color: #212529;
    }
    
    .admin-response-input:focus {
        border-color: #3F9EDD;
        background: white;
        box-shadow: 0 0 0 3px rgba(63, 158, 221, 0.1);
    }
    
    .admin-response-send {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3F9EDD 0%, #2A7BC4 100%);
        color: white;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    
    .admin-response-send:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(63, 158, 221, 0.3);
    }
    
    .admin-response-send:active {
        transform: scale(0.95);
    }
    
    .admin-response-send:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .admin-chatbot-window {
            width: 320px;
            height: 450px;
            bottom: 70px;
            right: 10px;
        }
        
        .admin-chatbot-button {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }
        
        .admin-chatbot-container {
            bottom: 15px;
            right: 15px;
        }
    }
    
    @media (max-width: 480px) {
        .admin-chatbot-window {
            width: 320px;
            height: 450px;
        }
    }
    </style>

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
    
    <!-- Admin Chatbot JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const adminChatbotToggle = document.getElementById('adminChatbotToggle');
        const adminChatbotWindow = document.getElementById('adminChatbotWindow');
        const adminChatbotClose = document.getElementById('adminChatbotClose');
        const adminChatbotMinimize = document.getElementById('adminChatbotMinimize');
        
        // Conversation list elements
        const conversationListView = document.getElementById('conversationListView');
        const conversationDetailView = document.getElementById('conversationDetailView');
        const conversationsContainer = document.getElementById('conversationsContainer');
        const refreshConversations = document.getElementById('refreshConversations');
        const conversationSearch = document.getElementById('conversationSearch');
        
        // Conversation detail elements
        const backToList = document.getElementById('backToList');
        const conversationUserInfo = document.getElementById('conversationUserInfo');
        const conversationMessages = document.getElementById('conversationMessages');
        const adminResponseInput = document.getElementById('adminResponseInput');
        const adminResponseSend = document.getElementById('adminResponseSend');
        
        let isOpen = false;
        let isMinimized = false;
        let conversations = [];
        let currentConversation = null;
        
        // Toggle chatbot window
        adminChatbotToggle.addEventListener('click', function() {
            if (isOpen && !isMinimized) {
                closeChatbot();
            } else {
                openChatbot();
            }
        });
        
        adminChatbotClose.addEventListener('click', closeChatbot);
        adminChatbotMinimize.addEventListener('click', toggleMinimize);
        
        function openChatbot() {
            adminChatbotWindow.style.display = 'flex';
            isOpen = true;
            isMinimized = false;
            showConversationList();
            loadConversations();
        }
        
        function closeChatbot() {
            if (adminPollingInterval) {
                clearInterval(adminPollingInterval);
                adminPollingInterval = null;
            }
            adminChatbotWindow.style.display = 'none';
            isOpen = false;
            isMinimized = false;
        }
        
        function toggleMinimize() {
            if (isMinimized) {
                adminChatbotWindow.style.height = '500px';
                isMinimized = false;
            } else {
                adminChatbotWindow.style.height = '60px';
                isMinimized = true;
            }
        }
        
        function showConversationList() {
            conversationListView.style.display = 'flex';
            conversationDetailView.style.display = 'none';
        }
        
        function showConversationDetail() {
            conversationListView.style.display = 'none';
            conversationDetailView.style.display = 'flex';
        }
        
        // Load live chat escalations from API
        async function loadConversations() {
            try {
                conversationsContainer.innerHTML = `
                    <div class="loading-conversations">
                        <div class="loading-spinner"></div>
                        <p>Loading live chat escalations...</p>
                    </div>
                `;
                
                console.log('Attempting to load escalations from API...');
                
                // First try to get auth status
                try {
                    const authResponse = await fetch('/debug-auth');
                    const authData = await authResponse.json();
                    console.log('Auth status:', authData);
                } catch (e) {
                    console.log('Could not check auth status:', e);
                }
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                console.log('CSRF token found:', !!csrfToken);
                
                const headers = {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                };
                
                if (csrfToken) {
                    headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
                }
                
                let response;
                let data;
                
                try {
                    // Try authenticated endpoint first
                    response = await fetch('/api/admin/live-chat/escalations', {
                        method: 'GET',
                        headers: headers,
                        credentials: 'same-origin'
                    });
                    
                    console.log('Auth endpoint response status:', response.status);
                    
                    if (response.ok) {
                        data = await response.json();
                    } else {
                        throw new Error(`Auth endpoint failed: ${response.status}`);
                    }
                } catch (authError) {
                    console.log('Auth endpoint failed, trying test endpoint:', authError);
                    
                    // Fallback to test endpoint
                    response = await fetch('/api/admin/live-chat/escalations-test', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    console.log('Test endpoint response status:', response.status);
                    
                    if (!response.ok) {
                        throw new Error(`Both endpoints failed. Test endpoint: ${response.status}`);
                    }
                    
                    data = await response.json();
                }
                
                console.log('Response data:', data);
                
                if (data.success) {
                    conversations = data.escalations;
                    displayConversations(conversations);
                } else {
                    console.error('API returned error:', data.error);
                    conversationsContainer.innerHTML = `
                        <div class="loading-conversations">
                            <p>Failed to load escalations: ${data.error || 'Unknown error'}</p>
                            <p><small>Check browser console for details</small></p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading escalations:', error);
                conversationsContainer.innerHTML = `
                    <div class="loading-conversations">
                        <p>Error loading escalations: ${error.message}</p>
                        <p><small>Check browser console for details</small></p>
                    </div>
                `;
            }
        }
        
        function displayConversations(conversationList) {
            if (conversationList.length === 0) {
                conversationsContainer.innerHTML = `
                    <div class="loading-conversations">
                        <p>No active escalations</p>
                    </div>
                `;
                return;
            }
            
            conversationsContainer.innerHTML = conversationList.map(escalation => `
                <div class="conversation-item" data-session-id="${escalation.session_id}">
                    <div class="conversation-user">User (${escalation.user_ip})</div>
                    <div class="conversation-preview">${escalation.last_message}</div>
                    <div class="conversation-meta">
                        <span class="unread-count">${escalation.unread_count} unread</span>
                        <span>${escalation.last_activity}</span>
                    </div>
                </div>
            `).join('');
            
            // Add click listeners to escalation items
            document.querySelectorAll('.conversation-item').forEach(item => {
                item.addEventListener('click', function() {
                    const sessionId = this.dataset.sessionId;
                    loadConversationDetail(sessionId);
                });
            });
        }
        
        // Load specific live chat session detail
        async function loadConversationDetail(sessionId) {
            try {
                conversationMessages.innerHTML = `
                    <div class="loading-conversations">
                        <div class="loading-spinner"></div>
                        <p>Loading conversation...</p>
                    </div>
                `;
                
                const response = await fetch(`/api/live-chat/conversation/${sessionId}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success && data.messages) {
                    currentConversation = { session_id: sessionId, messages: data.messages };
                    displayConversationDetail(currentConversation);
                    showConversationDetail();
                    startPollingForAdminMessages(sessionId);
                } else {
                    alert('Failed to load conversation details');
                }
            } catch (error) {
                console.error('Error loading conversation detail:', error);
                alert('Error loading conversation details');
            }
        }
        
        function displayConversationDetail(conversation) {
            // Update user info
            conversationUserInfo.innerHTML = `
                <div class="conversation-user-name">Live Chat Session</div>
                <div class="conversation-user-email">Session ID: ${conversation.session_id}</div>
            `;
            
            // Display messages
            conversationMessages.innerHTML = conversation.messages.map(message => `
                <div class="message ${message.sender_type}">
                    ${message.message}
                    <div class="message-timestamp">${message.created_at}</div>
                </div>
            `).join('');
            
            // Auto-scroll to bottom with smooth behavior
            setTimeout(() => {
                conversationMessages.scrollTo({
                    top: conversationMessages.scrollHeight,
                    behavior: 'smooth'
                });
            }, 100);
        }
        
        // Send admin response
        adminResponseSend.addEventListener('click', sendAdminResponse);
        adminResponseInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendAdminResponse();
            }
        });
        
        // Auto-resize textarea
        adminResponseInput.addEventListener('input', function() {
            // Reset height to auto to get the correct scrollHeight
            this.style.height = 'auto';
            // Set height based on content, with min and max limits
            const newHeight = Math.min(Math.max(this.scrollHeight, 40), 120);
            this.style.height = newHeight + 'px';
        });
        
        // Prevent textarea from disappearing by setting initial height
        adminResponseInput.style.height = '40px';
        
        let adminPollingInterval = null;
        
        function startPollingForAdminMessages(sessionId) {
            if (adminPollingInterval) {
                clearInterval(adminPollingInterval);
            }
            
            adminPollingInterval = setInterval(async () => {
                if (currentConversation && currentConversation.session_id === sessionId) {
                    try {
                        const response = await fetch(`/api/live-chat/conversation/${sessionId}`, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                        
                        const data = await response.json();
                        if (data.success && data.messages.length > currentConversation.messages.length) {
                            const previousMessageCount = currentConversation.messages.length;
                            currentConversation.messages = data.messages;
                            displayConversationDetail(currentConversation);
                            
                            // Only scroll if new messages were added
                            if (data.messages.length > previousMessageCount) {
                                setTimeout(() => {
                                    conversationMessages.scrollTo({
                                        top: conversationMessages.scrollHeight,
                                        behavior: 'smooth'
                                    });
                                }, 100);
                            }
                        }
                    } catch (error) {
                        console.error('Polling error:', error);
                    }
                }
            }, 3000); // Poll every 3 seconds
        }
        
        async function sendAdminResponse() {
            const message = adminResponseInput.value.trim();
            if (!message || !currentConversation) {
                console.log('Cannot send message: empty message or no conversation');
                return;
            }
            
            console.log('Sending admin response:', {
                session_id: currentConversation.session_id,
                message: message
            });
            
            // Add admin message to UI immediately
            const adminMessage = document.createElement('div');
            adminMessage.className = 'message admin';
            adminMessage.innerHTML = `
                ${message}
                <div class="message-timestamp">${new Date().toLocaleTimeString()}</div>
            `;
            conversationMessages.appendChild(adminMessage);
            
            // Auto-scroll to bottom with smooth behavior
            setTimeout(() => {
                conversationMessages.scrollTo({
                    top: conversationMessages.scrollHeight,
                    behavior: 'smooth'
                });
            }, 50);
            
            // Clear input but keep it visible
            adminResponseInput.value = '';
            adminResponseInput.style.height = '40px'; // Reset to default height
            adminResponseInput.focus(); // Keep focus on input
            
            // Disable send button
            adminResponseSend.disabled = true;
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                console.log('CSRF token found:', !!csrfToken);
                
                if (!csrfToken) {
                    throw new Error('CSRF token not found');
                }
                
                const requestData = {
                    session_id: currentConversation.session_id,
                    message: message
                };
                
                console.log('Making request to /api/admin/live-chat/respond with data:', requestData);
                
                let response;
                let responseData;
                
                try {
                    // Try the simplified endpoint first (no auth required)
                    response = await fetch('/api/admin/live-chat/respond-simple', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify(requestData)
                    });
                    
                    console.log('Simple endpoint response status:', response.status);
                    
                    if (!response.ok) {
                        // Fallback to authenticated endpoint
                        console.log('Simple endpoint failed, trying authenticated endpoint');
                        
                        response = await fetch('/api/admin/live-chat/respond', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify(requestData)
                        });
                        
                        console.log('Auth endpoint response status:', response.status);
                    }
                    
                } catch (fetchError) {
                    console.log('Both endpoints failed:', fetchError);
                    throw fetchError;
                }
                
                console.log('Response status:', response.status);
                console.log('Response headers:', [...response.headers.entries()]);
                
                const responseText = await response.text();
                console.log('Raw response text:', responseText);
                
                try {
                    responseData = JSON.parse(responseText);
                } catch (parseError) {
                    console.error('Failed to parse response as JSON:', parseError);
                    throw new Error(`Invalid JSON response: ${responseText.substring(0, 200)}`);
                }
                
                console.log('Parsed response data:', responseData);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${responseData.error || responseData.message || 'Unknown error'}`);
                }
                
                if (!responseData.success) {
                    throw new Error(responseData.error || 'Failed to send message');
                }
                
                console.log('Message sent successfully');
                
            } catch (error) {
                console.error('Error sending message:', error);
                console.error('Error details:', {
                    name: error.name,
                    message: error.message,
                    stack: error.stack
                });
                
                // Remove the message if it failed to send
                adminMessage.remove();
                
                // Show detailed error to user
                alert(`Failed to send message: ${error.message}`);
            }
            
            adminResponseSend.disabled = false;
        }
        
        // Refresh escalations
        refreshConversations.addEventListener('click', loadConversations);
        
        // Search conversations
        conversationSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const filteredConversations = conversations.filter(conversation => 
                conversation.user_name.toLowerCase().includes(searchTerm) ||
                conversation.last_message.toLowerCase().includes(searchTerm)
            );
            displayConversations(filteredConversations);
        });
        
        // Back to list
        backToList.addEventListener('click', function() {
            // Stop polling when going back to list
            if (adminPollingInterval) {
                clearInterval(adminPollingInterval);
                adminPollingInterval = null;
            }
            showConversationList();
        });
        
        // Utility functions
        function formatTimeAgo(timestamp) {
            const now = new Date();
            const time = new Date(timestamp);
            const diffInMs = now - time;
            const diffInMinutes = Math.floor(diffInMs / (1000 * 60));
            const diffInHours = Math.floor(diffInMs / (1000 * 60 * 60));
            const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));
            
            if (diffInMinutes < 60) {
                return `${diffInMinutes}m ago`;
            } else if (diffInHours < 24) {
                return `${diffInHours}h ago`;
            } else {
                return `${diffInDays}d ago`;
            }
        }
        
        function formatTime(timestamp) {
            const time = new Date(timestamp);
            return time.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
    });
    </script>
  </body>
</html>
