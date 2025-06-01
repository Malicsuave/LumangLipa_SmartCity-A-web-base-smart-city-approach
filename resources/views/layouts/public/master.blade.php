<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Barangay Lumanglipa')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Feather Icons -->
    <link rel="stylesheet" href="{{ asset('admin/dark/css/feather.css') }}">
    
    <!-- Public Styles -->
    <link rel="stylesheet" href="{{ asset('css/public.css') }}">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="{{ route('public.home') }}">
                <img src="{{ asset('images/logo.png') }}" height="40" alt="Barangay Lumanglipa Logo">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteName() == 'public.home' ? 'active' : '' }}" 
                           href="{{ route('public.home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteName() == 'public.about' ? 'active' : '' }}" 
                           href="{{ route('public.about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteName() == 'public.services' ? 'active' : '' }}" 
                           href="{{ route('public.services') }}">eServices</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Route::currentRouteName() == 'public.contact' ? 'active' : '' }}" 
                           href="{{ route('public.contact') }}">Contact Us</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-primary ms-lg-3" href="{{ route('login') }}">Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    
    <!-- Republic Header with Time -->
    <div class="blue-header">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Barangay Logo" class="logo-small me-3">
                        <div>
                            <div class="republic-text">Republic of the Philippines</div>
                            <div class="barangay-title">Barangay Lumanglipa</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="time-display">
                        <div>Philippine Standard Time</div>
                        <div class="current-time" id="philippineTime"></div>
                        <div id="currentDate"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-4">Barangay Lumanglipa</h5>
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
                        <li class="mb-2"><i class="fe fe-map-pin me-2"></i> 123 Lumanglipa St., Lipa City, Batangas</li>
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