<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Barangay Lumanglipa - Digital Services Platform">
    <meta name="author" content="Barangay Lumanglipa">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>@yield('title', 'Barangay Lumanglipa')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: "#99E39E",
                        secondary: "#1DC8CD",
                        midnight_text: "#263238",
                        muted: "#d8dbdb",
                        error: "#CF3127",
                        warning: "#F7931A",
                        light_grey: "#505050",
                        grey: "#F5F7FA",
                        dark_grey: "#1E2229",
                        border: "#E1E1E1",
                        success: "#3cd278",
                        section: "#737373",
                        darkmode: "#000510",
                        darklight: "#0c372a",
                        dark_border: "#959595",
                        tealGreen: "#477E70",
                        charcoalGray: "#666C78",
                        deepSlate: "#282C36",
                        slateGray: "#2F3543",
                    },
                    fontSize: {
                        86: ["5.375rem", { lineHeight: "1.2" }],
                        76: ["4.75rem", { lineHeight: "1.2" }],
                        70: ["4.375rem", { lineHeight: "1.2" }],
                        54: ["3.375rem", { lineHeight: "1.2" }],
                        44: ["2.75rem", { lineHeight: "1.3" }],
                        40: ["2.5rem", { lineHeight: "1.3" }],
                        32: ["2rem", { lineHeight: "1.3" }],
                        28: ["1.75rem", { lineHeight: "1.3" }],
                        24: ["1.5rem", { lineHeight: "1.4" }],
                        20: ["1.25rem", { lineHeight: "1.4" }],
                        18: ["1.125rem", { lineHeight: "1.5" }],
                        16: ["1rem", { lineHeight: "1.5" }],
                        14: ["0.875rem", { lineHeight: "1.5" }],
                    },
                    spacing: {
                        '6.25': '6.25rem',
                        '70%': '70%',
                        '40%': '40%',
                        '30%': '30%',
                        '80%': '80%',
                        8.5: '8.5rem',
                        50: '50rem',
                        51: "54.375rem",
                        25: '35.625rem',
                        29: '28rem',
                        120: '120rem',
                        45: '45rem',
                        94: '22.5rem',
                        85: '21rem',
                        3.75: '3.75rem'
                    },
                    boxShadow: {
                        'cause-shadow': '0px 4px 17px 0px #00000008',
                    },
                    transitionDuration: {
                        '150': '150ms',
                    },
                }
            }
        }
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    @stack('styles')
</head>
<body class="bg-darkmode text-white font-['DM_Sans'] scroll-smooth">
    <!-- Header -->
    <header class="fixed top-0 z-40 w-full pb-5 transition-all duration-300 {{ request()->routeIs('home') ? 'shadow-none md:pt-14 pt-5' : 'shadow-lg bg-darkmode pt-5' }}">
        <div class="lg:py-0 py-2">
            <div class="container mx-auto lg:max-w-screen-xl md:max-w-screen-md flex items-center justify-between px-4">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Barangay Lumanglipa" class="h-10 w-auto">
                    <span class="ml-3 text-xl font-bold text-white">Lumanglipa</span>
                </a>
                
                <!-- Navigation -->
                <nav class="hidden lg:flex flex-grow items-center gap-8 justify-center">
                    <a href="{{ route('home') }}" class="text-white hover:text-primary transition-colors duration-300 font-medium {{ request()->routeIs('home') ? 'text-primary' : '' }}">
                        Home
                    </a>
                    <div class="relative group">
                        <a href="#" class="text-white hover:text-primary transition-colors duration-300 font-medium flex items-center">
                            Services
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </a>
                        <div class="absolute top-full left-0 mt-2 w-48 bg-darkmode border border-dark_border rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                            <a href="{{ route('barangay-clearance.create') }}" class="block px-4 py-2 text-white hover:text-primary hover:bg-darklight transition-colors duration-300">
                                Barangay Clearance
                            </a>
                            <a href="{{ route('indigency.create') }}" class="block px-4 py-2 text-white hover:text-primary hover:bg-darklight transition-colors duration-300">
                                Certificate of Indigency
                            </a>
                            <a href="{{ route('health-request.create') }}" class="block px-4 py-2 text-white hover:text-primary hover:bg-darklight transition-colors duration-300">
                                Health Request
                            </a>
                            <a href="{{ route('complaints.create') }}" class="block px-4 py-2 text-white hover:text-primary hover:bg-darklight transition-colors duration-300">
                                File Complaint
                            </a>
                        </div>
                    </div>
                    <a href="{{ route('public.contact') }}" class="text-white hover:text-primary transition-colors duration-300 font-medium {{ request()->routeIs('public.contact') ? 'text-primary' : '' }}">
                        Contact
                    </a>
                    <a href="{{ route('public.pre-registration.step1') }}" class="text-white hover:text-primary transition-colors duration-300 font-medium {{ str_contains(Route::currentRouteName(), 'public.pre-registration') ? 'text-primary' : '' }}">
                        Register
                    </a>
                </nav>
                
                <!-- Action Buttons -->
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="hidden lg:block bg-primary text-darkmode px-6 py-2 rounded-lg font-medium hover:bg-opacity-90 transition-all duration-300">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden lg:block bg-primary text-darkmode px-6 py-2 rounded-lg font-medium hover:bg-opacity-90 transition-all duration-300">
                            Sign In
                        </a>
                    @endauth
                    
                    <!-- Mobile Menu Button -->
                    <button class="lg:hidden text-white" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="lg:hidden fixed inset-0 top-0 bg-darkmode bg-opacity-95 z-50 transform -translate-x-full transition-transform duration-300">
            <div class="flex flex-col h-full">
                <div class="flex justify-between items-center p-4 border-b border-dark_border">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Barangay Lumanglipa" class="h-8 w-auto">
                        <span class="ml-3 text-lg font-bold text-white">Lumanglipa</span>
                    </a>
                    <button class="text-white" onclick="toggleMobileMenu()">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <nav class="flex-1 px-4 py-8 space-y-4">
                    <a href="{{ route('home') }}" class="block text-white hover:text-primary transition-colors duration-300 font-medium py-2">
                        Home
                    </a>
                    <div class="space-y-2">
                        <div class="text-white font-medium py-2">Services</div>
                        <div class="pl-4 space-y-2">
                            <a href="{{ route('barangay-clearance.create') }}" class="block text-muted hover:text-primary transition-colors duration-300 py-1">
                                Barangay Clearance
                            </a>
                            <a href="{{ route('indigency.create') }}" class="block text-muted hover:text-primary transition-colors duration-300 py-1">
                                Certificate of Indigency
                            </a>
                            <a href="{{ route('health-request.create') }}" class="block text-muted hover:text-primary transition-colors duration-300 py-1">
                                Health Request
                            </a>
                            <a href="{{ route('complaints.create') }}" class="block text-muted hover:text-primary transition-colors duration-300 py-1">
                                File Complaint
                            </a>
                        </div>
                    </div>
                    <a href="{{ route('public.contact') }}" class="block text-white hover:text-primary transition-colors duration-300 font-medium py-2">
                        Contact
                    </a>
                    <a href="{{ route('public.pre-registration.step1') }}" class="block text-white hover:text-primary transition-colors duration-300 font-medium py-2">
                        Register
                    </a>
                </nav>
                <div class="p-4 border-t border-dark_border">
                    @auth
                        <a href="{{ route('dashboard') }}" class="block w-full bg-primary text-darkmode px-6 py-3 rounded-lg font-medium hover:bg-opacity-90 transition-all duration-300 text-center">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="block w-full bg-primary text-darkmode px-6 py-3 rounded-lg font-medium hover:bg-opacity-90 transition-all duration-300 text-center">
                            Sign In
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-darkmode border-t border-dark_border">
        <div class="container mx-auto lg:max-w-screen-xl md:max-w-screen-md px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-1">
                    <a href="{{ route('home') }}" class="flex items-center mb-4">
                        <img src="{{ asset('images/logo.png') }}" alt="Barangay Lumanglipa" class="h-10 w-auto">
                        <span class="ml-3 text-xl font-bold text-white">Lumanglipa</span>
                    </a>
                    <p class="text-muted text-sm">
                        Digital services platform for Barangay Lumanglipa residents. Access government services online with ease.
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Services</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('barangay-clearance.create') }}" class="text-muted hover:text-primary transition-colors duration-300 text-sm">Barangay Clearance</a></li>
                        <li><a href="{{ route('indigency.create') }}" class="text-muted hover:text-primary transition-colors duration-300 text-sm">Certificate of Indigency</a></li>
                        <li><a href="{{ route('health-request.create') }}" class="text-muted hover:text-primary transition-colors duration-300 text-sm">Health Request</a></li>
                        <li><a href="{{ route('complaints.create') }}" class="text-muted hover:text-primary transition-colors duration-300 text-sm">File Complaint</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-muted hover:text-primary transition-colors duration-300 text-sm">Home</a></li>
                        <li><a href="{{ route('public.contact') }}" class="text-muted hover:text-primary transition-colors duration-300 text-sm">Contact</a></li>
                        <li><a href="{{ route('public.pre-registration.step1') }}" class="text-muted hover:text-primary transition-colors duration-300 text-sm">Register</a></li>
                        <li><a href="{{ route('login') }}" class="text-muted hover:text-primary transition-colors duration-300 text-sm">Sign In</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Contact Info</h4>
                    <ul class="space-y-2">
                        <li class="text-muted text-sm">
                            <i class="fas fa-map-marker-alt text-primary mr-2"></i>
                            Barangay Lumanglipa, Batangas
                        </li>
                        <li class="text-muted text-sm">
                            <i class="fas fa-phone text-primary mr-2"></i>
                            (043) 123-4567
                        </li>
                        <li class="text-muted text-sm">
                            <i class="fas fa-envelope text-primary mr-2"></i>
                            info@lumanglipa.gov.ph
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-dark_border mt-8 pt-8 text-center">
                <p class="text-muted text-sm">
                    &copy; {{ date('Y') }} Barangay Lumanglipa. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('-translate-x-full');
        }
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileMenuButton = event.target.closest('button');
            
            if (!mobileMenu.contains(event.target) && !mobileMenuButton) {
                mobileMenu.classList.add('-translate-x-full');
            }
        });
        
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
    
    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true
        });
    </script>
    
    @stack('scripts')
</body>
</html>
