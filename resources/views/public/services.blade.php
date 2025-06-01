@extends('layouts.public.master')

@section('title', 'Services - Barangay Lumanglipa')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1>Our Services</h1>
        <p class="lead">Convenient access to essential barangay services</p>
    </div>
    
    <div class="row g-4">
        <!-- Document Services -->
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 shadow">
                <div class="card-body text-center">
                    <i class="fe fe-file-text fe-48 text-primary mb-3"></i>
                    <h4>Document Requests</h4>
                    <p>Request official documents online:</p>
                    <ul class="list-unstyled text-start">
                        <li>• Barangay Clearance</li>
                        <li>• Certificate of Residency</li>
                        <li>• Certificate of Indigency</li>
                        <li>• Business Permit</li>
                        <li>• Other Official Documents</li>
                    </ul>
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">Request Now</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">Register to Request</a>
                    @endauth
                </div>
            </div>
        </div>
        
        <!-- Health Services -->
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 shadow">
                <div class="card-body text-center">
                    <i class="fe fe-heart fe-48 text-primary mb-3"></i>
                    <h4>Health Services</h4>
                    <p>Access health programs:</p>
                    <ul class="list-unstyled text-start">
                        <li>• Medical Assistance</li>
                        <li>• Health Check-ups</li>
                        <li>• Vaccination Programs</li>
                        <li>• Maternal Care</li>
                        <li>• Senior Citizen Programs</li>
                    </ul>
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">Access Services</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">Register to Access</a>
                    @endauth
                </div>
            </div>
        </div>
        
        <!-- Complaint Services -->
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 shadow">
                <div class="card-body text-center">
                    <i class="fe fe-message-circle fe-48 text-primary mb-3"></i>
                    <h4>File Complaints</h4>
                    <p>Report issues and concerns:</p>
                    <ul class="list-unstyled text-start">
                        <li>• Noise Complaints</li>
                        <li>• Property Disputes</li>
                        <li>• Public Safety Issues</li>
                        <li>• Infrastructure Problems</li>
                        <li>• Other Community Concerns</li>
                    </ul>
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">File Complaint</a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary">Register to File</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-5">
        <div class="col-lg-8 mx-auto">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h3>Service Hours</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Regular Office Hours</h5>
                            <p>Monday - Friday: 8:00 AM - 5:00 PM<br>
                               Saturday: 8:00 AM - 12:00 PM</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Emergency Services</h5>
                            <p>Available 24/7<br>
                               Call: [Emergency Number]</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection