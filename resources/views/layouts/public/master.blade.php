<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no, user-scalable=yes, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Lumanglipa Barangay Management System">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>@yield('title', 'Barangay Lumanglipa')</title>
    
    <!-- Performance monitoring script -->
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
        .navbar-brand .logo-img {
            max-width: 60px;
            max-height: 60px;
            object-fit: contain;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.3);
        }
        
        .nav-link.active {
            background: linear-gradient(135deg, #4A90E2, #357ABD) !important;
            box-shadow: 0 4px 15px rgba(74, 144, 226, 0.4) !important;
            color: white !important;
        }
        .nav-link:not(.active):hover {
            background: rgba(74, 144, 226, 0.1) !important;
            color: #4A90E2 !important;
        }
        
        /* Mobile Responsiveness - Clean and simplified */
        @media (max-width: 1200px) {
            /* Custom burger icon positioning */
            .navbar-toggler {
                display: block !important;
                position: absolute !important;
                left: 15px !important;
                top: 50% !important;
                transform: translateY(-50%) !important;
                z-index: 1060 !important;
                padding: 4px 6px !important;
                width: 35px !important;
                height: 35px !important;
                border-radius: 6px !important;
                border: none !important;
                background: rgba(255,255,255,0.2) !important;
            }
            
            /* Hide the normal navigation on mobile */
            .navbar-nav {
                display: none !important;
            }
            
            /* When sidebar container exists, always show nav items in sidebar layout */
            .navbar-collapse .navbar-nav {
                display: flex !important;
            }
            
            .navbar-toggler:focus {
                box-shadow: none !important;
                outline: none !important;
            }
            
            /* Custom burger icon */
            .navbar-toggler-icon {
                background-image: none !important;
                width: 20px !important;
                height: 15px !important;
                position: relative !important;
                border: none !important;
            }
            
            /* Create burger lines */
            .navbar-toggler-icon,
            .navbar-toggler-icon::before,
            .navbar-toggler-icon::after {
                display: block !important;
                height: 3px !important;
                background-color: white !important;
                border-radius: 2px !important;
                transition: all 0.3s ease !important;
            }
            
            .navbar-toggler-icon::before,
            .navbar-toggler-icon::after {
                content: '' !important;
                position: absolute !important;
                left: 0 !important;
                width: 100% !important;
            }
            
            .navbar-toggler-icon::before {
                top: -7px !important;
            }
            
            .navbar-toggler-icon::after {
                bottom: -7px !important;
            }
            
            /* Animated burger to X transformation */
            .navbar-toggler[aria-expanded="true"] .navbar-toggler-icon {
                background-color: transparent !important;
            }
            
            .navbar-toggler[aria-expanded="true"] .navbar-toggler-icon::before {
                transform: rotate(45deg) translate(5px, 5px) !important;
                top: 0 !important;
            }
            
            .navbar-toggler[aria-expanded="true"] .navbar-toggler-icon::after {
                transform: rotate(-45deg) translate(5px, -5px) !important;
                bottom: 0 !important;
            }
            
            /* Enhanced mobile navigation sidebar */
            .navbar-collapse {
                position: fixed !important;
                top: 0 !important;
                left: -320px !important;
                width: 320px !important;
                height: 100vh !important;
                z-index: 1055 !important;
                transition: left 0.3s ease !important;
                overflow-y: auto !important;
                background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
            }
            
            .navbar-collapse.show {
                left: 0 !important;
            }
            
            /* Ensure navbar-nav takes full width and height when sidebar is open */
            .navbar-collapse .navbar-nav {
                width: 100% !important;
                height: 100vh !important;
                padding: 80px 0 20px 0 !important;
                margin: 0 !important;
                border-radius: 0 !important;
                border: none !important;
                box-shadow: none !important;
                background: transparent !important;
                flex-direction: column !important;
                align-items: flex-start !important;
                text-align: left !important;
                display: flex !important;
            }
            
            /* Sidebar overlay */
            .navbar-collapse::before {
                content: '';
                position: fixed;
                top: 0;
                left: 320px;
                width: calc(100vw - 320px);
                height: 100vh;
                background: rgba(0, 0, 0, 0.5);
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
                z-index: -1;
            }
            
            .navbar-collapse.show::before {
                opacity: 1;
                visibility: visible;
            }
            
            /* Sidebar header */
            .navbar-nav::before {
                content: 'MENU';
                color: #2c3e50;
                font-weight: bold;
                font-size: 18px;
                text-align: left;
                padding: 20px 0 20px 40px;
                border-bottom: 1px solid rgba(44, 62, 80, 0.1);
                margin-bottom: 20px;
                width: 100%;
            }
            
            .nav-link {
                margin: 2px 20px 2px 20px !important;
                color: #2c3e50 !important;
                background: transparent !important;
                border-radius: 8px !important;
                font-weight: 500 !important;
                padding: 12px 20px !important;
                text-align: left !important;
                transition: all 0.3s ease !important;
                font-size: 14px !important;
                border-left: 3px solid transparent !important;
                display: flex !important;
                align-items: center !important;
                justify-content: flex-start !important;
                width: calc(100% - 40px) !important;
                box-sizing: border-box !important;
            }
            
            .nav-link:hover {
                background: rgba(74, 144, 226, 0.1) !important;
                color: #4A90E2 !important;
                transform: translateX(5px) !important;
                border-left-color: #4A90E2 !important;
            }
            
            .nav-link.active {
                background: rgba(74, 144, 226, 0.15) !important;
                color: #4A90E2 !important;
                border-left-color: #4A90E2 !important;
                font-weight: 600 !important;
                margin: 2px 2px 2px 20px !important;
                width: calc(100% - 22px) !important;
                box-sizing: border-box !important;
            }
            
            /* Mobile login button styling */
            .login-btn {
                background: linear-gradient(135deg, #4A90E2, #357ABD) !important;
                color: white !important;
                border: 2px solid #4A90E2 !important;
                font-weight: 600 !important;
                margin: 20px 20px 0 20px !important;
                border-radius: 8px !important;
                text-align: center !important;
                width: calc(100% - 40px) !important;
                padding: 12px 20px !important;
                box-sizing: border-box !important;
            }
            
            .login-btn:hover {
                background: linear-gradient(135deg, #357ABD, #2E5F8A) !important;
                color: white !important;
                transform: translateX(5px) !important;
                border-left-color: #4A90E2 !important;
            }
            
            /* Dropdown menu mobile styling - Keep card design */
            .dropdown-menu {
                background: rgba(248, 249, 250, 0.98) !important;
                border: 1px solid rgba(44, 62, 80, 0.1) !important;
                border-radius: 8px !important;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
                margin: 5px 20px !important;
                backdrop-filter: blur(10px) !important;
                width: calc(100% - 40px) !important;
                padding: 10px !important;
                position: static !important;
                left: 0 !important;
                display: block !important;
            }
            
            /* Show dropdown when parent is hovered or active */
            .nav-item.dropdown:hover .dropdown-menu,
            .nav-item.dropdown.show .dropdown-menu {
                display: block !important;
            }
            
            .dropdown-item {
                color: #2c3e50 !important;
                padding: 8px 10px !important;
                border-radius: 6px !important;
                margin-bottom: 2px !important;
                transition: all 0.3s ease !important;
                background: white !important;
                border: 1px solid rgba(44, 62, 80, 0.1) !important;
                display: flex !important;
                align-items: center !important;
                text-decoration: none !important;
                font-size: 13px !important;
                justify-content: flex-start !important;
            }
            
            .dropdown-item:hover {
                transform: translateX(3px) !important;
                background: rgba(74, 144, 226, 0.1) !important;
                border-color: #4A90E2 !important;
                color: #4A90E2 !important;
            }
            
            /* Mobile navbar brand adjustments */
            .navbar-brand {
                font-size: 14px !important;
            }
            .navbar-brand .logo-img {
                width: 40px !important;
                height: 40px !important;
            }
            .navbar-brand div {
                padding-left: 8px !important;
            }
            .navbar-brand .text-white {
                font-size: 16px !important;
            }
            .navbar-brand .text-white-50 {
                font-size: 11px !important;
            }
        }
        
        @media (max-width: 768px) {
            /* Extra small devices */
            .navbar-brand .text-white {
                font-size: 16px !important;
                letter-spacing: 0.5px !important;
            }
            .navbar-brand .text-white-50 {
                font-size: 11px !important;
            }
            
            /* Footer mobile adjustments */
            .footer-social-icons {
                gap: 20px !important;
                justify-content: center !important;
                margin-top: 20px !important;
            }
            
            /* General mobile text sizing */
            h1 { font-size: 1.8rem !important; }
            h2 { font-size: 1.6rem !important; }
            h3 { font-size: 1.4rem !important; }
            h4 { font-size: 1.2rem !important; }
            h5 { font-size: 1.1rem !important; }
            
            /* Card mobile adjustments */
            .card-body {
                padding: 1rem !important;
            }
            
            /* Button mobile adjustments */
            .btn {
                padding: 10px 20px !important;
                font-size: 14px !important;
            }
        }
        
        @media (max-width: 576px) {
            /* Small devices */
            .container {
                padding-left: 15px !important;
                padding-right: 15px !important;
            }
            
            .navbar-brand .text-white {
                font-size: 14px !important;
                letter-spacing: 0.3px !important;
            }
            .navbar-brand .text-white-50 {
                font-size: 10px !important;
            }
            
            /* Footer adjustments for very small screens */
            .footer-social-icons {
                gap: 15px !important;
            }
            
            /* Even smaller text for tiny screens */
            h1 { font-size: 1.5rem !important; }
            h2 { font-size: 1.3rem !important; }
            h3 { font-size: 1.2rem !important; }
            
            /* Navigation adjustments for small screens */
            .nav-link {
                font-size: 13px !important;
                padding: 8px 12px !important;
            }
        }
        /* Always apply sidebar styles when .force-sidebar is present */
        .navbar-collapse.force-sidebar {
            position: fixed !important;
            top: 0 !important;
            left: -320px !important;
            width: 320px !important;
            height: 100vh !important;
            z-index: 1055 !important;
            transition: left 0.3s ease !important;
            overflow-y: auto !important;
        }
        .navbar-collapse.force-sidebar.show {
            left: 0 !important;
        }
        .navbar-collapse.force-sidebar .navbar-nav {
            flex-direction: column !important;
            align-items: flex-start !important;
            width: 100% !important;
            height: 100vh !important;
        }
        
        /* Desktop navigation - hide burger on large screens */
        @media (min-width: 1201px) {
            .navbar-toggler {
                display: none !important;
            }
            .navbar-nav {
                display: flex !important;
            }
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: linear-gradient(135deg, #4A90E2 0%, #357ABD 50%, #2E5F8A 100%); padding: 8px 0; box-shadow: 0 4px 20px rgba(74, 144, 226, 0.3); backdrop-filter: blur(10px); transition: all 0.3s ease;">
        <div class="container">
            <a href="{{ route('public.home') }}" class="navbar-brand d-flex align-items-center me-auto pe-4 text-decoration-none" style="margin-right: auto;">
                <img src="{{ asset('/images/logo.png') }}" alt="Barangay Lumanglipa Logo" class="me-3 logo-img" style="width: 60px; height: 60px; border-radius: 12px; box-shadow: 0 4px 12px rgba(255,255,255,0.2); transition: transform 0.3s ease; object-fit: contain; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.3);" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'" onerror="console.error('Logo failed to load'); this.style.display='none'; this.nextElementSibling.style.paddingLeft='0';">
                <div style="padding-left: 10px;">
                    <div class="text-white fw-bold" style="font-size: 22px; letter-spacing: 0.8px; line-height: 1.2;">BARANGAY LUMANGLIPA</div>
                    <div class="text-white-50" style="font-size: 14px; margin-top: 2px; letter-spacing: 0.5px;">MATAAS NA KAHOY, BATANGAS</div>
                </div>
            </a>
            
            <button class="navbar-toggler border-0 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"
                    style="padding: 8px; border-radius: 8px; background: rgba(255,255,255,0.2); transition: all 0.3s ease; position: absolute; left: 15px; top: 50%; transform: translateY(-50%); width: 40px; height: 40px;"
                    onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center" style="gap: 6px; padding: 8px 16px; background: rgba(255, 255, 255, 0.9); border-radius: 50px; margin-top: 0; box-shadow: inset 0 2px 10px rgba(0,0,0,0.1);">
                    <li class="nav-item">
                        <a class="nav-link text-dark px-4 py-2 rounded-pill modern-nav-link {{ Route::currentRouteName() == 'public.home' ? 'active' : '' }}" 
                           href="{{ route('public.home') }}"
                           style="font-weight: 500; letter-spacing: 0.5px; font-size: 14px; transition: all 0.3s ease; position: relative;">
                           HOME
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark px-4 py-2 rounded-pill modern-nav-link {{ Route::currentRouteName() == 'public.about' ? 'active' : '' }}" 
                           href="{{ route('public.about') }}"
                           style="font-weight: 500; letter-spacing: 0.5px; font-size: 14px; transition: all 0.3s ease;">
                           ABOUT
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-dark px-4 py-2 rounded-pill modern-nav-link {{ in_array(Route::currentRouteName(), ['public.services', 'documents.request', 'complaints.create', 'health.request']) ? 'active' : '' }}" 
                           href="#" id="navbarDropdownServices" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false"
                           style="font-weight: 500; letter-spacing: 0.5px; font-size: 14px; transition: all 0.3s ease;">
                           E-SERVICES
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownServices">
                            <li>
                                <a class="dropdown-item {{ Route::currentRouteName() == 'health.request' ? 'active' : '' }}" 
                                   href="{{ route('health.request') }}">
                                    <i class="fas fa-heartbeat me-2"></i>
                                    Health Services
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ Route::currentRouteName() == 'documents.request' ? 'active' : '' }}" 
                                   href="{{ route('documents.request') }}">
                                    <i class="fas fa-file-alt me-2"></i>
                                    Document Request
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item {{ Route::currentRouteName() == 'complaints.create' ? 'active' : '' }}" 
                                   href="{{ route('complaints.create') }}">
                                    <i class="fas fa-flag me-2"></i>
                                    File a Complaint
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark px-4 py-2 rounded-pill modern-nav-link {{ Route::currentRouteName() == 'public.contact' ? 'active' : '' }}" 
                           href="{{ route('public.contact') }}"
                           style="font-weight: 500; letter-spacing: 0.5px; font-size: 14px; transition: all 0.3s ease;">
                           CONTACT
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark px-4 py-2 rounded-pill modern-nav-link {{ str_contains(Route::currentRouteName(), 'public.pre-registration') ? 'active' : '' }}" 
                           href="{{ route('public.pre-registration.step1') }}"
                           style="font-weight: 500; letter-spacing: 0.5px; font-size: 14px; transition: all 0.3s ease;">
                           REGISTER
                        </a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link text-dark px-4 py-2 rounded-pill modern-nav-link" 
                               href="{{ route('dashboard') }}"
                               style="font-weight: 500; letter-spacing: 0.5px; font-size: 14px; transition: all 0.3s ease;">
                               DASHBOARD
                            </a>
                        </li>
                    @else
                        <li class="nav-item ms-3">
                            <a class="nav-link btn px-5 py-2 rounded-pill login-btn" 
                               href="{{ route('login') }}"
                               style="font-weight: 600; letter-spacing: 0.8px; font-size: 14px; background: linear-gradient(135deg, #4A90E2, #357ABD); color: white; border: 2px solid rgba(255,255,255,0.2); transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(74, 144, 226, 0.3);"
                               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(74, 144, 226, 0.4)'"
                               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(74, 144, 226, 0.3)'">
                               LOGIN
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Spacer for fixed navbar -->
    <div style="height: 90px;"></div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="py-5 mt-5" style="background: white; box-shadow: 0 -4px 20px rgba(0,0,0,0.1);">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('/images/logo.png') }}" alt="Barangay Lumanglipa Logo" class="me-3" style="width: 50px; height: 50px; border-radius: 10px; object-fit: contain;">
                        <div>
                            <h5 class="text-dark fw-bold mb-0" style="font-size: 20px;">BARANGAY LUMANGLIPA</h5>
                            <small class="text-muted" style="font-size: 14px;">Mataas na Kahoy, Batangas</small>
                        </div>
                    </div>
                    <p class="text-secondary mb-3" style="font-size: 16px; line-height: 1.6;">Providing essential services and improving the quality of life for our residents.</p>
                    <div class="d-flex footer-social-icons" style="gap: 30px;">
                        <a href="#" class="text-primary" style="font-size: 28px; transition: all 0.3s ease;" onmouseover="this.style.color='#4A90E2'; this.style.transform='scale(1.1)'" onmouseout="this.style.color='#6c757d'; this.style.transform='scale(1)'"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-primary" style="font-size: 28px; transition: all 0.3s ease;" onmouseover="this.style.color='#4A90E2'; this.style.transform='scale(1.1)'" onmouseout="this.style.color='#6c757d'; this.style.transform='scale(1)'"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-primary" style="font-size: 28px; transition: all 0.3s ease;" onmouseover="this.style.color='#4A90E2'; this.style.transform='scale(1.1)'" onmouseout="this.style.color='#6c757d'; this.style.transform='scale(1)'"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-dark fw-bold mb-4" style="font-size: 20px;">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('public.home') }}" class="text-secondary text-decoration-none" style="font-size: 16px; transition: all 0.3s ease;" onmouseover="this.style.color='#4A90E2'; this.style.paddingLeft='8px'" onmouseout="this.style.color='#6c757d'; this.style.paddingLeft='0'">Home</a></li>
                        <li class="mb-2"><a href="{{ route('public.about') }}" class="text-secondary text-decoration-none" style="font-size: 16px; transition: all 0.3s ease;" onmouseover="this.style.color='#4A90E2'; this.style.paddingLeft='8px'" onmouseout="this.style.color='#6c757d'; this.style.paddingLeft='0'">About Us</a></li>
                        <li class="mb-2"><a href="{{ route('documents.request') }}" class="text-secondary text-decoration-none" style="font-size: 16px; transition: all 0.3s ease;" onmouseover="this.style.color='#4A90E2'; this.style.paddingLeft='8px'" onmouseout="this.style.color='#6c757d'; this.style.paddingLeft='0'">eServices</a></li>
                        <li class="mb-2"><a href="{{ route('public.contact') }}" class="text-secondary text-decoration-none" style="font-size: 16px; transition: all 0.3s ease;" onmouseover="this.style.color='#4A90E2'; this.style.paddingLeft='8px'" onmouseout="this.style.color='#6c757d'; this.style.paddingLeft='0'">Contact Us</a></li>
                    </ul>
                </div>
                
                <div class="col-md-4">
                    <h5 class="text-dark fw-bold mb-4" style="font-size: 20px;">Contact Info</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3 d-flex align-items-start">
                            <i class="fas fa-map-marker-alt me-3 mt-1" style="color: #6c757d; width: 18px; font-size: 16px;"></i>
                            <span class="text-secondary" style="font-size: 16px;">Lumanglipa, Mataas na Kahoy, Batangas</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fas fa-phone me-3" style="color: #6c757d; width: 18px; font-size: 16px;"></i>
                            <span class="text-secondary" style="font-size: 16px;">(043) 123-4567</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fas fa-envelope me-3" style="color: #6c757d; width: 18px; font-size: 16px;"></i>
                            <span class="text-secondary" style="font-size: 16px;">info@lumanglipa.gov.ph</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center">
                            <i class="fas fa-clock me-3" style="color: #6c757d; width: 18px; font-size: 16px;"></i>
                            <span class="text-secondary" style="font-size: 16px;">Mon-Fri: 8:00 AM - 5:00 PM</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    
    <div class="py-3" style="background: linear-gradient(135deg, #357ABD 0%, #2E5F8A 50%, #1E4A6F 100%); border-top: 1px solid rgba(255,255,255,0.1);">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-white">
                <div>
                    <small style="color: rgba(255,255,255,0.9);">&copy; {{ date('Y') }} Barangay Lumanglipa. All rights reserved.</small>
                </div>
                <div>
                    <small style="color: rgba(255,255,255,0.8);">A government website of the Republic of the Philippines</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Mobile Sidebar Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navbarToggler = document.querySelector('.navbar-toggler');
            const navbarCollapse = document.querySelector('.navbar-collapse');
            const navbarNav = document.querySelector('.navbar-nav');
            const body = document.body;
            
            // Function to handle responsive navigation
            function handleResponsiveNav() {
                const screenWidth = window.innerWidth;
                
                // CSS media queries handle the display, but we need to ensure proper state
                if (screenWidth <= 1200) {
                    // Mobile/tablet mode - burger should be visible (handled by CSS)
                    // Ensure sidebar is closed when switching to mobile
                    if (!navbarCollapse.classList.contains('show')) {
                        navbarCollapse.classList.remove('show');
                        body.style.overflow = 'auto';
                        navbarToggler.setAttribute('aria-expanded', 'false');
                    }
                } else {
                    // Desktop mode - hide sidebar and show normal nav
                    navbarCollapse.classList.remove('show');
                    body.style.overflow = 'auto';
                    navbarToggler.setAttribute('aria-expanded', 'false');
                }
            }
            
            // Run on load
            handleResponsiveNav();
            
            // Handle sidebar toggle
            if (navbarToggler) {
                navbarToggler.addEventListener('click', function(e) {
                    e.preventDefault();
                    const isExpanded = this.getAttribute('aria-expanded') === 'true';
                    
                    if (!isExpanded) {
                        // Show sidebar
                        navbarCollapse.classList.add('show');
                        body.style.overflow = 'hidden';
                        this.setAttribute('aria-expanded', 'true');
                    } else {
                        // Hide sidebar
                        navbarCollapse.classList.remove('show');
                        body.style.overflow = 'auto';
                        this.setAttribute('aria-expanded', 'false');
                    }
                });
            }
            
            // Close sidebar when clicking on overlay
            if (navbarCollapse) {
                navbarCollapse.addEventListener('click', function(e) {
                    if (e.target === this) {
                        navbarCollapse.classList.remove('show');
                        body.style.overflow = 'auto';
                        navbarToggler.setAttribute('aria-expanded', 'false');
                    }
                });
            }
            
            // Close sidebar when clicking nav links
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 1200) {
                        navbarCollapse.classList.remove('show');
                        body.style.overflow = 'auto';
                        navbarToggler.setAttribute('aria-expanded', 'false');
                    }
                });
            });
            
            // Handle window resize with debouncing
            let resizeTimeout;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(function() {
                    handleResponsiveNav();
                }, 100); // Debounce resize events
            });
            
            // Handle orientation change for mobile devices
            window.addEventListener('orientationchange', function() {
                setTimeout(handleResponsiveNav, 200);
            });
        });
    </script>
    
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