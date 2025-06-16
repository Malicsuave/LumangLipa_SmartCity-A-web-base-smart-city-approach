@extends('layouts.public.master')

@section('title', 'Barangay Lumanglipa - Official Website')

@push('styles')
<style>
    /* Hero Section Background */
    .hero-section {
        background-image: url('{{ asset("images/bglumanglipa.jpeg") }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        position: relative;
        min-height: 80vh;
        display: flex;
        align-items: center;
    }
    
    /* Add overlay to hero section for better text readability */
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
        z-index: 1;
    }
    
    /* Ensure hero content is above the overlay */
    .hero-section .container {
        position: relative;
        z-index: 2;
    }
    
    /* Make text white for better visibility on dark overlay */
    .hero-section h1,
    .hero-section p {
        color: white;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }
</style>
@endpush

@section('content')
<!-- Hero Section with Gradient Background -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 fade-in">
                <h1 class="display-4 fw-bold mb-4">Welcome to Barangay Lumanglipa</h1>
                <p class="lead mb-4">Your community, your government. We're committed to providing excellent services to all our residents.</p>
                <div class="d-flex gap-3">
                    <a href="{{ route('public.services') }}" class="btn btn-light">Our eServices</a>
                    <a href="{{ route('public.contact') }}" class="btn btn-outline-light">Contact Us</a>
                </div>
            </div>
            <!-- <div class="col-lg-6 text-center fade-in">
                <img src="{{ asset('images/bglumanglipa.jpeg') }}" alt="Barangay Lumanglipa" class="img-fluid rounded shadow-lg">
            </div> -->
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 mt-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">eServices We Provide</h2>
            <p class="lead text-muted">Convenient access to essential barangay services</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow text-center p-4 border-primary">
                    <div class="card-body">
                        <i class="fe fe-user-plus fe-48 text-primary mb-4"></i>
                        <h4>Resident Pre-Registration</h4>
                        <p>Register as a new resident online and get your digital barangay ID after approval.</p>
                        <a href="{{ route('public.pre-registration.create') }}" class="btn btn-primary mt-2">Register Now</a>
                        <div class="mt-2">
                            <small><a href="{{ route('public.pre-registration.check-status') }}" class="text-muted">Check Registration Status</a></small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 shadow text-center p-4">
                    <div class="card-body">
                        <i class="fe fe-file-text fe-48 text-primary mb-4"></i>
                        <h4>Document Requests</h4>
                        <p>Request official documents online including Barangay Clearance, Certificate of Residency, and more.</p>
                        <a href="{{ route('documents.request') }}" class="btn btn-sm btn-primary mt-2">Request Now</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 shadow text-center p-4">
                    <div class="card-body">
                        <i class="fe fe-heart fe-48 text-danger mb-4"></i>
                        <h4>Health Services</h4>
                        <p>Access medical assistance, health check-ups, vaccination programs, and other health services.</p>
                       <a href="{{ route('documents.request') }}" class="btn btn-sm btn-danger mt-2">Request Now</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row g-4 mt-2">
            <div class="col-md-4">
                <div class="card h-100 shadow text-center p-4">
                    <div class="card-body">
                        <i class="fe fe-message-circle fe-48 text-warning mb-4"></i>
                        <h4>File Complaints</h4>
                        <p>Report issues and concerns in the community for prompt resolution by barangay officials.</p>
                        <a href="{{ route('public.services') }}#complaints" class="btn btn-sm btn-primary mt-2">Learn More</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 shadow text-center p-4">
                    <div class="card-body">
                        <i class="fe fe-info fe-48 text-info mb-4"></i>
                        <h4>Community Updates</h4>
                        <p>Stay informed about barangay announcements, events, and important community news.</p>
                        <a href="{{ route('public.about') }}" class="btn btn-sm btn-primary mt-2">Learn More</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card h-100 shadow text-center p-4">
                    <div class="card-body">
                        <i class="fe fe-phone fe-48 text-success mb-4"></i>
                        <h4>Emergency Hotline</h4>
                        <p>Quick access to emergency contacts and hotlines for urgent situations and assistance.</p>
                        <a href="{{ route('public.contact') }}" class="btn btn-sm btn-primary mt-2">View Contacts</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Latest Updates Section -->


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