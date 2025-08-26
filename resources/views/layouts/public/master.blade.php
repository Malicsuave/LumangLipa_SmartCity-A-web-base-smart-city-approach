<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Lumanglipa Barangay Management System">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>@yield('title', 'Barangay Lumanglipa')</title>
    
    <!-- Performance    <!-- Time Display Script -->
    <script>
        function updateTime() {
            const now = new Date();
            const options = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
            document.getElementById('philippineTime').textContent = now.toLocaleTimeString('en-US', options);
            
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'numeric', day: 'numeric' };
            document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', dateOptions);
        }
        
        // Update time immediately and then every second
        updateTime();
        setInterval(updateTime, 1000);
    </script>

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileMenuClose = document.getElementById('mobileMenuClose');
            const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
            const navbarCollapse = document.getElementById('navbarNav');
            
            // Open mobile menu
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    navbarCollapse.classList.add('show');
                    mobileMenuOverlay.classList.add('show');
                    document.body.style.overflow = 'hidden';
                });
            }
            
            // Close mobile menu
            function closeMobileMenu() {
                navbarCollapse.classList.remove('show');
                mobileMenuOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }
            
            if (mobileMenuClose) {
                mobileMenuClose.addEventListener('click', closeMobileMenu);
            }
            
            if (mobileMenuOverlay) {
                mobileMenuOverlay.addEventListener('click', closeMobileMenu);
            }
            
            // Close menu when clicking on nav links
            const navLinks = document.querySelectorAll('#navbarNav .nav-link:not(.dropdown-toggle)');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        closeMobileMenu();
                    }
                });
            });
            
            // Close menu when clicking on dropdown items
            const dropdownItems = document.querySelectorAll('#navbarNav .dropdown-item');
            dropdownItems.forEach(item => {
                item.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        closeMobileMenu();
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    closeMobileMenu();
                }
            });
        });
    </script>
    
    @stack('scripts')t -->
    <script src="{{ asset('js/performance-monitor.js') }}"></script>
    
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
    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('admin/dark/css/app-light.css') }}" id="lightTheme">
    
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    
    <!-- Public Styles -->
    <link rel="stylesheet" href="{{ asset('css/public.css') }}">
    
    @yield('styles')
    @stack('styles')
    
    <!-- Navigation Styles -->
    <style>
        /* Navbar height reduction */
        .navbar {
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
        }
        
        .navbar-nav .nav-link {
            padding-top: 0.25rem !important;
            padding-bottom: 0.25rem !important;
            padding-left: 1rem !important;
            padding-right: 1rem !important;
            margin-left: 0.25rem !important;
            margin-right: 0.25rem !important;
        }
        
        /* Right-aligned navbar items */
        .navbar-nav.ms-auto {
            margin-left: auto !important;
        }
        
        /* Add gap between main nav and auth nav */
        .navbar-nav.ms-auto .nav-item:first-child .nav-link {
            margin-left: 2rem !important;
        }
        
        /* Custom dropdown arrow */
        .dropdown-toggle::after {
            display: none !important;
        }
        
        .dropdown-toggle {
            position: relative;
        }
        
        .dropdown-toggle::before {
            content: "▼";
            font-size: 0.6rem;
            margin-left: 0.4rem;
            color: inherit;
            float: right;
            margin-top: 0.1rem;
        }

        /* Mobile Sliding Drawer Styles */
        @media (max-width: 991.98px) {
            /* Override Bootstrap's navbar-expand-lg behavior on mobile */
            .navbar-expand-lg .navbar-toggler {
                display: block !important;
            }

            .navbar-expand-lg .navbar-collapse {
                display: block !important;
            }

            /* Mobile toggle button - positioned on the right */
            .navbar-toggler {
                display: block !important;
                border: 1px solid rgba(0,0,0,0.1) !important;
                padding: 0.25rem 0.5rem !important;
                font-size: 1.25rem !important;
                background: transparent !important;
                margin-left: auto !important;
            }

            .navbar-toggler:focus {
                box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25) !important;
            }

            .navbar-toggler-icon {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2833, 37, 41, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='m4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e") !important;
                width: 1.5em !important;
                height: 1.5em !important;
            }

            /* Mobile navbar container layout */
            .navbar .container {
                display: flex !important;
                align-items: center !important;
                justify-content: flex-start !important;
                position: relative !important;
                flex-wrap: nowrap !important;
            }

            /* Mobile logo - fixed on left */
            .navbar-brand {
                order: 1 !important;
                flex-shrink: 0 !important;
            }

            /* Mobile barangay title - centered between logo and hamburger */
            .mobile-barangay-title {
                position: absolute !important;
                left: 50% !important;
                top: 50% !important;
                transform: translate(-50%, -50%) !important;
                text-align: center !important;
                pointer-events: none !important;
                z-index: 1 !important;
            }

            .mobile-barangay-title .barangay-name {
                font-size: 0.8rem !important;
                font-weight: 700 !important;
                color: #2c5530 !important;
                line-height: 1.1 !important;
                margin-bottom: 1px !important;
                letter-spacing: 0.3px !important;
                white-space: nowrap !important;
            }

            .mobile-barangay-title .barangay-location {
                font-size: 0.55rem !important;
                font-weight: 500 !important;
                color: #6c757d !important;
                line-height: 1 !important;
                letter-spacing: 0.2px !important;
                white-space: nowrap !important;
            }

            /* Mobile overlay */
            .mobile-menu-overlay {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100vh !important;
                background: rgba(0,0,0,0.5) !important;
                z-index: 9998 !important;
                opacity: 0 !important;
                visibility: hidden !important;
                transition: all 0.3s ease !important;
            }

            .mobile-menu-overlay.show {
                opacity: 1 !important;
                visibility: visible !important;
            }

            /* Right sliding drawer */
            .navbar-collapse {
                position: fixed !important;
                top: 0 !important;
                right: -100% !important;
                width: 320px !important;
                max-width: 85vw !important;
                height: 100vh !important;
                background: white !important;
                box-shadow: -3px 0 15px rgba(0,0,0,0.2) !important;
                z-index: 9999 !important;
                transition: right 0.3s ease !important;
                overflow-y: auto !important;
                padding: 0 !important;
                border: none !important;
                margin: 0 !important;
            }

            .navbar-collapse.show {
                right: 0 !important;
            }

            /* Mobile menu header with blue background */
            .mobile-menu-header {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                padding: 1.5rem 1rem !important;
                background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%) !important;
                color: white !important;
                border-bottom: none !important;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
            }

            .mobile-menu-header .mobile-barangay-info {
                text-align: center !important;
                flex-grow: 1 !important;
            }

            .mobile-menu-header .mobile-barangay-info .barangay-name {
                color: white !important;
                font-size: 1.1rem !important;
                font-weight: 700 !important;
                margin-bottom: 3px !important;
                letter-spacing: 0.5px !important;
            }

            .mobile-menu-header .mobile-barangay-info .barangay-location {
                color: rgba(255,255,255,0.9) !important;
                font-size: 0.75rem !important;
                font-weight: 500 !important;
                letter-spacing: 0.3px !important;
            }

            .mobile-menu-close {
                background: none !important;
                border: none !important;
                font-size: 1.8rem !important;
                color: white !important;
                cursor: pointer !important;
                padding: 0.25rem !important;
                line-height: 1 !important;
                border-radius: 50% !important;
                width: 35px !important;
                height: 35px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                transition: background-color 0.2s ease !important;
            }

            .mobile-menu-close:hover {
                background: rgba(255,255,255,0.2) !important;
            }

            /* Mobile navigation items */
            .navbar-nav {
                flex-direction: column !important;
                padding: 0 !important;
                width: 100% !important;
                margin: 0 !important;
            }

            .navbar-nav .nav-item {
                border-bottom: 1px solid #f1f3f4 !important;
                width: 100% !important;
                margin: 0 !important;
            }

            .navbar-nav .nav-item:last-child {
                border-bottom: none !important;
            }

            .navbar-nav .nav-link {
                padding: 1.2rem 1.5rem !important;
                margin: 0 !important;
                color: #333 !important;
                width: 100% !important;
                text-align: left !important;
                font-weight: 500 !important;
                display: flex !important;
                align-items: center !important;
                transition: all 0.2s ease !important;
                font-size: 1rem !important;
            }

            .navbar-nav .nav-link:hover,
            .navbar-nav .nav-link.active {
                background-color: #f8f9fa !important;
                color: #4a90e2 !important;
                padding-left: 2rem !important;
            }

            .navbar-nav .nav-link img {
                display: none !important;
            }

            /* Mobile dropdown menu */
            .navbar-nav .dropdown-menu {
                position: static !important;
                box-shadow: none !important;
                border: none !important;
                background: #f8f9fa !important;
                padding: 0 !important;
                margin: 0 !important;
                border-radius: 0 !important;
            }

            .navbar-nav .dropdown-item {
                padding: 1rem 2.5rem !important;
                color: #666 !important;
                border-bottom: 1px solid #e9ecef !important;
                font-size: 0.9rem !important;
                transition: all 0.2s ease !important;
            }

            .navbar-nav .dropdown-item:hover {
                background-color: #e9ecef !important;
                color: #4a90e2 !important;
                padding-left: 3rem !important;
            }

            .navbar-nav .dropdown-item:last-child {
                border-bottom: none !important;
            }

            /* Login section at bottom */
            .navbar-nav.ms-auto {
                margin-left: 0 !important;
                border-top: 2px solid #f1f3f4 !important;
                margin-top: 1rem !important;
                padding-top: 0 !important;
            }

            .navbar-nav.ms-auto .nav-link {
                text-align: center !important;
                background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%) !important;
                color: white !important;
                margin: 1.5rem !important;
                border-radius: 8px !important;
                font-weight: 600 !important;
                padding: 1rem !important;
                box-shadow: 0 2px 4px rgba(74, 144, 226, 0.3) !important;
            }

            .navbar-nav.ms-auto .nav-link:hover {
                background: linear-gradient(135deg, #357abd 0%, #2968a3 100%) !important;
                color: white !important;
                transform: translateY(-1px) !important;
                box-shadow: 0 4px 8px rgba(74, 144, 226, 0.4) !important;
                padding-left: 1rem !important;
            }
        }
        
        /* Chat widget positioning and visibility fixes */
        .chat-widget,
        .chat-container,
        .chat-popup,
        [class*="chat-widget"],
        [id*="chat-widget"] {
            position: fixed !important;
            bottom: 30px !important;
            right: 20px !important;
            z-index: 99999 !important;
            max-width: 400px !important;
            width: auto !important;
            height: auto !important;
            overflow: visible !important;
        }
        
        /* Chat buttons container */
        .chat-buttons,
        .chat-choices,
        [class*="chat-button"],
        [class*="chat-choice"] {
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 10px !important;
            padding: 15px !important;
            margin: 0 !important;
            overflow: visible !important;
            z-index: 99999 !important;
            justify-content: center !important;
        }
        
        /* Individual chat buttons */
        .chat-widget button,
        .chat-container button,
        [class*="chat"] button {
            min-height: 40px !important;
            padding: 10px 16px !important;
            border-radius: 20px !important;
            white-space: nowrap !important;
            z-index: 99999 !important;
            position: relative !important;
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
            font-size: 14px !important;
            font-weight: 500 !important;
        }
        
        /* Chat widget shadow and background */
        .chat-widget,
        [class*="chat-widget"] {
            box-shadow: 0 4px 20px rgba(0,0,0,0.15) !important;
            border-radius: 12px !important;
            background: white !important;
        }
    </style>
</head>
<body data-chatbot-strict="{{ config('services.huggingface.strict') ? '1' : '0' }}" data-chatbot-has-key="{{ config('services.huggingface.api_key') ? '1' : '0' }}">
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <!-- Mobile Logo (visible only on mobile) -->
            <a class="navbar-brand d-lg-none" href="{{ route('public.home') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" width="45" height="45">
            </a>

            <!-- Mobile Barangay Title (centered on mobile only) -->
            <div class="mobile-barangay-title d-lg-none">
                <div class="barangay-name">BARANGAY LUMANGLIPA</div>
                <div class="barangay-location">MATAAS NA KAHOY, BATANGAS</div>
            </div>

            <button class="navbar-toggler" type="button" id="mobileMenuToggle">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <!-- Mobile menu overlay -->
            <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Mobile menu header -->
                <div class="mobile-menu-header d-lg-none">
                    <div class="mobile-barangay-info">
                        <div class="barangay-name">BARANGAY LUMANGLIPA</div>
                        <div class="barangay-location">MATAAS NA KAHOY, BATANGAS</div>
                    </div>
                    <button class="mobile-menu-close" id="mobileMenuClose">&times;</button>
                </div>

                <ul class="navbar-nav">
                    <!-- Desktop Logo (inside nav items, original position) -->
                    <li class="nav-item d-none d-lg-block">
                        <a class="nav-link" href="{{ route('public.home') }}">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo" width="60" height="60">
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteName() == 'public.home' ? 'active text-primary fw-bold' : '' }}" href="{{ route('public.home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteName() == 'public.about' ? 'active text-primary fw-bold' : '' }}" href="{{ route('public.about') }}">About</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ in_array(Route::currentRouteName(), ['public.services', 'documents.request', 'complaints.create', 'health.request']) ? 'active text-primary fw-bold' : '' }}" 
                           href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                           Services
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('health.request') }}">Health Services</a></li>
                            <li><a class="dropdown-item" href="{{ route('documents.request') }}">Document Request</a></li>
                            <li><a class="dropdown-item" href="{{ route('complaints.create') }}">File a Complaint</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteName() == 'public.contact' ? 'active text-primary fw-bold' : '' }}" href="{{ route('public.contact') }}">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ str_contains(Route::currentRouteName(), 'public.pre-registration') ? 'active text-primary fw-bold' : '' }}" href="{{ route('public.pre-registration.step1') }}">Register</a>
                    </li>
                </ul>
                
                <!-- Right-aligned Dashboard/Login -->
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary btn-sm text-white" href="{{ route('login') }}">Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Spacer for fixed navbar -->
    <div style="height: 45px;"></div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <p>Providing essential services and improving the quality of life for our residents.</p>
                    <div class="d-flex gap-3 mt-4">
                        <a href="#" class="text-white"><i class="fe fe-facebook"></i></a>
                        <a href="#" class="text-white"><i class="fe fe-twitter"></i></a>
                        <a href="#" class="text-white"><i class="fe fe-instagram"></i></a>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-4">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('public.home') }}" class="text-white">Home</a></li>
                        <li class="mb-2"><a href="{{ route('public.about') }}" class="text-white">About Us</a></li>
                        <li class="mb-2"><a href="{{ route('public.services') }}" class="text-white">eServices</a></li>
                        <li class="mb-2"><a href="{{ route('public.contact') }}" class="text-white">Contact Us</a></li>
                    </ul>
                </div>
                
                <div class="col-md-4">
                    <h5 class="text-uppercase mb-4">Contact Info</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fe fe-map-pin me-2"></i> Lumanglipa, Mataas na Kahoy, Batangas</li>
                        <li class="mb-2"><i class="fe fe-phone me-2"></i> (043) 123-4567</li>
                        <li class="mb-2"><i class="fe fe-mail me-2"></i> info@lumanglipa.gov.ph</li>
                        <li class="mb-2"><i class="fe fe-clock me-2"></i> Mon-Fri: 8:00 AM - 5:00 PM</li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    
    <div class="bg-dark py-3">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-white">
                <div>
                    <small>&copy; {{ date('Y') }} Barangay Lumanglipa. All rights reserved.</small>
                </div>
                <div>
                    <small>A government website of the Republic of the Philippines</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Time Display Script -->
    <script>
        function updateTime() {
            const now = new Date();
            const options = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
            document.getElementById('philippineTime').textContent = now.toLocaleTimeString('en-US', options);
            
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'numeric', day: 'numeric' };
            document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', dateOptions);
        }
        
        // Update time immediately and then every second
        updateTime();
        setInterval(updateTime, 1000);
    </script>
    
    @stack('scripts')
</body>
</html>