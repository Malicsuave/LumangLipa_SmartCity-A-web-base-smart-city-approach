@extends('layouts.public.master')

@section('title', 'Barangay Lumanglipa - Official Website')

@push('styles')
<style>
    /* Hero Section Background */
    .hero-section {
        background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ asset("images/bglumanglipa.jpeg") }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding-top: 20px; /* Add space from navigation */
    }
    
    /* Carousel Navigation Arrows */
    .carousel-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
    }
    
    .carousel-nav:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.5);
    }
    
    .carousel-nav.prev {
        left: 30px;
    }
    
    .carousel-nav.next {
        right: 30px;
    }
    
    .hero-content {
        text-align: center;
        color: white;
        z-index: 2;
        position: relative;
    }
    
    .hero-title {
        font-size: 4rem;
        font-weight: bold;
        margin-bottom: 1rem;
        text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.9), 0 0 20px rgba(255, 255, 255, 0.1);
        letter-spacing: 2px;
        line-height: 1.2;
    }
    
    .hero-subtitle {
        font-size: 1.5rem;
        margin-bottom: 3rem;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8), 0 0 15px rgba(255, 255, 255, 0.1);
        font-weight: 500;
        letter-spacing: 1px;
    }
    
    .hero-buttons {
        display: flex;
        gap: 20px;
        justify-content: center;
        margin-bottom: 4rem;
    }
    
    .hero-btn {
        padding: 15px 30px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s ease;
        text-transform: uppercase;
    }
    
    .btn-contact {
        background-color: #3F9EDD;
        color: white;
        border: 2px solid #3F9EDD;
    }
    
    .btn-login {
        background-color: transparent;
        color: white;
        border: 2px solid white;
    }
    
    .btn-contact:hover {
        background-color: #2A7BC4;
        transform: translateY(-2px);
        color: white;
    }
    
    .btn-login:hover {
        background-color: white;
        color: #333;
        transform: translateY(-2px);
    }
    
    /* Service Cards Container */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin-top: 50px;
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .service-card {
        background: white;
        border-radius: 15px;
        padding: 40px 30px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
    }
    
    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .service-icon {
        width: 80px;
        height: 80px;
        background-color: #3F9EDD;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2rem;
        color: white;
    }
    
    .service-card h4 {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: #333;
    }
    
    .service-card p {
        color: #666;
        line-height: 1.6;
        margin-bottom: 20px;
    }
    
    .service-btn {
        background-color: #3F9EDD;
        color: white;
        padding: 10px 25px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.3s ease;
        text-transform: uppercase;
        font-size: 0.9rem;
    }
    
    .service-btn:hover {
        background-color: #2A7BC4;
        color: white;
    }
    
    /* Section spacing */
    .services-section {
        padding: 100px 0;
        background-color: #f8f9fa;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .hero-section {
            padding-top: 100px; /* Adjust for mobile */
        }
        
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
        }
        
        .hero-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .services-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }
    
    @media (max-width: 992px) {
        .services-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <!-- Carousel Navigation Arrows -->
    <div class="carousel-nav prev">
        <i class="fas fa-chevron-left"></i>
    </div>
    <div class="carousel-nav next">
        <i class="fas fa-chevron-right"></i>
    </div>
    
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title"><span style="color: white;">WELCOME TO</span><br><span style="color: #3F9EDD;">BARANGAY LUMANGLIPA</span></h1>
            <p class="hero-subtitle" style="font-size: 1.2rem; margin-bottom: 2rem;">MATAAS NA KAHOY, BATANGAS</p>
            <p class="hero-subtitle">Your community, your government. We're committed to providing excellent services to all our residents.</p>
            
            <div class="hero-buttons">
                <a href="{{ route('public.contact') }}" class="hero-btn btn-contact">CONTACT US</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="hero-btn btn-login">GO TO DASHBOARD</a>
                @else
                    <a href="{{ route('login') }}" class="hero-btn btn-login">LOGIN NOW</a>
                @endauth
            </div>
            
            <!-- Service Cards Grid -->
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h4>Barangay Officials</h4>
                    <p>Meet your elected barangay officials and learn about their roles in serving the community.</p>
                    <a href="{{ route('public.about') }}" class="service-btn">Learn More</a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <h4>Announcements</h4>
                    <p>Stay informed about barangay announcements, events, and important community news.</p>
                    <a href="{{ route('public.about') }}" class="service-btn">Learn More</a>
                </div>
                
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h4>Document Issuance</h4>
                    <p>Request official documents online including Barangay Clearance, Certificate of Residency, and more.</p>
                    <a href="{{ route('documents.request') }}" class="service-btn">Learn More</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Recent Announcements Section -->
<section class="py-5" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="mb-5">
            <h2 class="fw-bold text-primary mb-3">| Recent Announcements</h2>
            <p class="text-muted">Check out the latest news, events and announcements here.</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <i class="fe fe-user-plus fe-48 text-primary mb-4"></i>
                        <h4>Resident Pre-Registration</h4>
                        <p>Register as a new resident online and get your digital barangay ID after approval.</p>
                        <a href="{{ route('public.pre-registration.create') }}" class="btn btn-primary mt-2">Register Now</a>
                        <div class="mt-2">
                            <small><a href="{{ route('public.pre-registration.check-status') }}" class="text-muted">Check Registration Status</a></small>
                        </div>
                        <h5 class="card-title">Community Health Program</h5>
                        <p class="card-text text-muted">Free medical check-up and vaccination program for all residents. Schedule your appointment today.</p>
                        <a href="#" class="text-primary text-decoration-none">See More</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <span class="fw-bold">15</span>
                            </div>
                            <div class="ms-3">
                                <small class="text-muted">December</small>
                            </div>
                        </div>
                        <h5 class="card-title">Barangay Assembly Meeting</h5>
                        <p class="card-text text-muted">Monthly assembly meeting to discuss community issues and development plans.</p>
                        <a href="#" class="text-primary text-decoration-none">See More</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <span class="fw-bold">10</span>
                            </div>
                            <div class="ms-3">
                                <small class="text-muted">December</small>
                            </div>
                        </div>
                        <h5 class="card-title">Clean-up Drive</h5>
                        <p class="card-text text-muted">Community-wide clean-up drive to maintain the cleanliness and beauty of our barangay.</p>
                        <a href="#" class="text-primary text-decoration-none">See More</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('public.about') }}" class="btn btn-primary">View All Announcements</a>
        </div>
    </div>
</section>


<!-- Call to Action -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card bg-primary text-white shadow">
                    <div class="card-body text-center p-5">
                        <h3 class="fw-bold">Join Our Community Portal</h3>
                        <p class="mb-4">Register to access online eServices, submit requests, and stay connected with your barangay.</p>
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-light">Go to Dashboard</a>
                        @else
                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ route('login') }}" class="btn btn-light">Login</a>
                                <a href="{{ route('register') }}" class="btn btn-outline-light">Register Now</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection