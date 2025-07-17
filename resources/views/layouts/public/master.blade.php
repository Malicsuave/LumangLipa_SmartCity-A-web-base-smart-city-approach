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
        }
        .nav-link:not(.active):hover {
            background: rgba(255,255,255,0.15) !important;
        }
        
        @media (max-width: 991px) {
            .navbar-nav {
                background: rgba(255,255,255,0.05) !important;
                border-radius: 15px !important;
                padding: 16px !important;
                margin-top: 16px !important;
            }
            .nav-link {
                margin: 4px 0 !important;
            }
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: linear-gradient(135deg, #1a1a1a 0%, #333333 50%, #1a1a1a 100%); padding: 8px 0; box-shadow: 0 4px 20px rgba(0,0,0,0.3); backdrop-filter: blur(10px); transition: all 0.3s ease;">
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
                    style="padding: 10px 14px; border-radius: 12px; background: rgba(255,255,255,0.1); transition: all 0.3s ease;"
                    onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center" style="gap: 6px; padding: 8px 16px; background: rgba(255,255,255,0.05); border-radius: 50px; margin-top: 8px; box-shadow: inset 0 2px 10px rgba(0,0,0,0.2);">
                    <li class="nav-item">
                        <a class="nav-link text-white px-4 py-2 rounded-pill modern-nav-link {{ Route::currentRouteName() == 'public.home' ? 'active' : '' }}" 
                           href="{{ route('public.home') }}"
                           style="font-weight: 500; letter-spacing: 0.5px; font-size: 14px; transition: all 0.3s ease; position: relative;">
                           HOME
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white px-4 py-2 rounded-pill modern-nav-link {{ Route::currentRouteName() == 'public.about' ? 'active' : '' }}" 
                           href="{{ route('public.about') }}"
                           style="font-weight: 500; letter-spacing: 0.5px; font-size: 14px; transition: all 0.3s ease;">
                           ABOUT
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white px-4 py-2 rounded-pill modern-nav-link {{ in_array(Route::currentRouteName(), ['public.services', 'documents.request', 'complaints.create', 'health.request']) ? 'active' : '' }}" 
                           href="#" id="navbarDropdownServices" role="button" 
                           data-bs-toggle="dropdown" aria-expanded="false"
                           style="font-weight: 500; letter-spacing: 0.5px; font-size: 14px; transition: all 0.3s ease;">
                           E-SERVICES
                        </a>
                        <ul class="dropdown-menu border-0 shadow-lg" aria-labelledby="navbarDropdownServices" 
                            style="border-radius: 16px; margin-top: 12px; background: rgba(255,255,255,0.98); min-width: 260px; backdrop-filter: blur(20px); box-shadow: 0 8px 32px rgba(0,0,0,0.15);">
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-3 px-4 {{ Route::currentRouteName() == 'health.request' ? 'active' : '' }}" 
                                   href="{{ route('health.request') }}"
                                   style="border-radius: 12px; margin: 6px 10px; transition: all 0.3s ease; font-weight: 500;"
                                   onmouseover="this.style.background='linear-gradient(135deg, #f8f9fa, #e9ecef)'; this.style.transform='translateX(8px)'"
                                   onmouseout="this.style.background='transparent'; this.style.transform='translateX(0)'">
                                    <i class="fas fa-heartbeat me-3" style="color: #4A90E2; width: 24px; font-size: 16px;"></i>
                                    <span style="color: #2c3e50;">Health Services</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-3 px-4 {{ Route::currentRouteName() == 'documents.request' ? 'active' : '' }}" 
                                   href="{{ route('documents.request') }}"
                                   style="border-radius: 12px; margin: 6px 10px; transition: all 0.3s ease; font-weight: 500;"
                                   onmouseover="this.style.background='linear-gradient(135deg, #f8f9fa, #e9ecef)'; this.style.transform='translateX(8px)'"
                                   onmouseout="this.style.background='transparent'; this.style.transform='translateX(0)'">
                                    <i class="fas fa-file-alt me-3" style="color: #4A90E2; width: 24px; font-size: 16px;"></i>
                                    <span style="color: #2c3e50;">Document Request</span>
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-3 px-4 {{ Route::currentRouteName() == 'complaints.create' ? 'active' : '' }}" 
                                   href="{{ route('complaints.create') }}"
                                   style="border-radius: 12px; margin: 6px 10px; transition: all 0.3s ease; font-weight: 500;"
                                   onmouseover="this.style.background='linear-gradient(135deg, #f8f9fa, #e9ecef)'; this.style.transform='translateX(8px)'"
                                   onmouseout="this.style.background='transparent'; this.style.transform='translateX(0)'">
                                    <i class="fas fa-flag me-3" style="color: #4A90E2; width: 24px; font-size: 16px;"></i>
                                    <span style="color: #2c3e50;">File a Complaint</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white px-4 py-2 rounded-pill modern-nav-link {{ Route::currentRouteName() == 'public.contact' ? 'active' : '' }}" 
                           href="{{ route('public.contact') }}"
                           style="font-weight: 500; letter-spacing: 0.5px; font-size: 14px; transition: all 0.3s ease;">
                           CONTACT
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white px-4 py-2 rounded-pill modern-nav-link {{ str_contains(Route::currentRouteName(), 'public.pre-registration') ? 'active' : '' }}" 
                           href="{{ route('public.pre-registration.step1') }}"
                           style="font-weight: 500; letter-spacing: 0.5px; font-size: 14px; transition: all 0.3s ease;">
                           REGISTER
                        </a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link text-white px-4 py-2 rounded-pill modern-nav-link" 
                               href="{{ route('dashboard') }}"
                               style="font-weight: 500; letter-spacing: 0.5px; font-size: 14px; transition: all 0.3s ease;">
                               DASHBOARD
                            </a>
                        </li>
                    @else
                        <li class="nav-item ms-3">
                            <a class="nav-link btn px-5 py-2 rounded-pill" 
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