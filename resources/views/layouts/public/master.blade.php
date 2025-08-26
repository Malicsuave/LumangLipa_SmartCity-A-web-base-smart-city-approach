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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
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