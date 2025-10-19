@extends('layouts.public.master')

@section('title', 'Register - Barangay Lumanglipa')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/pre-registration.css') }}">
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="display-4 font-weight-bold text-primary mb-3">
                    <span class="material-symbols-rounded align-middle me-3" style="font-size: 3rem;">how_to_reg</span>
                    Resident Registration
                </h1>
                <p class="lead text-muted mb-4">
                    Choose your registration type to get started with your barangay registration process
                </p>
            </div>

            <!-- Registration Type Cards -->
            <div class="row g-4">
                <!-- Regular Resident Registration -->
                <div class="col-md-6">
                    <div class="registration-card h-100">
                        <div class="text-center mb-4">
                            <div class="registration-icon mb-3">
                                <span class="material-symbols-rounded" style="font-size: 4rem; color: #007bff;">person</span>
                            </div>
                            <h3 class="h4 mb-3">Regular Resident</h3>
                            <p class="text-muted mb-4">
                                Register as a regular barangay resident. This registration is for individuals who are not senior citizens.
                            </p>
                        </div>

                        <div class="registration-features mb-4">
                            <div class="feature-item">
                                <span class="material-symbols-rounded me-2" style="font-size: 1.2rem;">check_circle</span>
                                <span>Digital ID Card</span>
                            </div>
                            <div class="feature-item">
                                <span class="material-symbols-rounded me-2" style="font-size: 1.2rem;">check_circle</span>
                                <span>Document Requests</span>
                            </div>
                            <div class="feature-item">
                                <span class="material-symbols-rounded me-2" style="font-size: 1.2rem;">check_circle</span>
                                <span>Barangay Services</span>
                            </div>
                            <div class="feature-item">
                                <span class="material-symbols-rounded me-2" style="font-size: 1.2rem;">check_circle</span>
                                <span>Community Updates</span>
                            </div>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('public.pre-registration.step1') }}" class="btn btn-primary btn-lg w-100">
                                <span class="material-symbols-rounded align-middle me-2">arrow_forward</span>
                                Register as Resident
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Senior Citizen Registration -->
                <div class="col-md-6">
                    <div class="registration-card h-100">
                        <div class="text-center mb-4">
                            <div class="registration-icon mb-3">
                                <span class="material-symbols-rounded" style="font-size: 4rem; color: #28a745;">elderly</span>
                            </div>
                            <h3 class="h4 mb-3">Senior Citizen</h3>
                            <p class="text-muted mb-4">
                                Register as a senior citizen (60+ years old) to access exclusive benefits and services for the elderly.
                            </p>
                        </div>

                        <div class="registration-features mb-4">
                            <div class="feature-item">
                                <span class="material-symbols-rounded me-2" style="font-size: 1.2rem;">check_circle</span>
                                <span>Dedicated Senior ID</span>
                            </div>
                            <div class="feature-item">
                                <span class="material-symbols-rounded me-2" style="font-size: 1.2rem;">check_circle</span>
                                <span>Senior Citizen Benefits</span>
                            </div>
                            <div class="feature-item">
                                <span class="material-symbols-rounded me-2" style="font-size: 1.2rem;">check_circle</span>
                                <span>Priority Services</span>
                            </div>
                            <div class="feature-item">
                                <span class="material-symbols-rounded me-2" style="font-size: 1.2rem;">check_circle</span>
                                <span>Health & Welfare Programs</span>
                            </div>
                        </div>

                        <div class="text-center">
                            <a href="{{ route('public.senior-registration.step1') }}" class="btn btn-success btn-lg w-100">
                                <span class="material-symbols-rounded align-middle me-2">arrow_forward</span>
                                Register as Senior Citizen
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Section -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="info-card">
                        <div class="d-flex align-items-start">
                            <span class="material-symbols-rounded me-3 mt-1" style="font-size: 2rem; color: #6c757d;">info</span>
                            <div>
                                <h5 class="mb-2">Important Information</h5>
                                <ul class="mb-0 text-muted">
                                    <li>Registration is free of charge</li>
                                    <li>You will need to provide valid identification documents</li>
                                    <li>The registration process takes approximately 10-15 minutes</li>
                                    <li>You will receive a digital ID card upon approval</li>
                                    <li>Senior citizens must be 60 years old and above</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back to Home -->
            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                    <span class="material-symbols-rounded align-middle me-2">arrow_back</span>
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to registration cards
    const cards = document.querySelectorAll('.registration-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 4px 15px rgba(0,0,0,0.1)';
        });
    });
});
</script>
@endpush
