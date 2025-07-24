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
    
    /* Chatbot Styles - Matching Website Design */
    .chatbot-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    .chatbot-button {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3F9EDD 0%, #2A7BC4 100%);
        color: white;
        border: none;
        cursor: pointer;
        font-size: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 25px rgba(63, 158, 221, 0.3);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .chatbot-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
        border-radius: 50%;
    }
    
    .chatbot-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 35px rgba(63, 158, 221, 0.4);
        background: linear-gradient(135deg, #2A7BC4 0%, #1E5A9A 100%);
    }
    
    .chatbot-button:active {
        transform: translateY(-1px);
    }
    
    .chatbot-window {
        position: absolute;
        bottom: 80px;
        right: 0;
        width: 350px;
        height: 500px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        display: none;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid rgba(63, 158, 221, 0.1);
    }
    
    .chatbot-header {
        background: linear-gradient(135deg, #3F9EDD 0%, #2A7BC4 100%);
        color: white;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .header-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .robot-icon {
        font-size: 20px;
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-5px); }
    }
    
    .chatbot-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }
    
    .header-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .language-toggle {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .language-toggle:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.5);
    }
    
    .chatbot-close {
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s ease;
    }
    
    .chatbot-close:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg);
    }
    
    .chatbot-messages {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        background: #f8f9fa;
        display: flex;
        flex-direction: column;
        gap: 15px;
        max-height: calc(100% - 140px);
        min-height: 300px;
    }
    
    .chatbot-messages::-webkit-scrollbar {
        width: 6px;
    }
    
    .chatbot-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .chatbot-messages::-webkit-scrollbar-thumb {
        background: #3F9EDD;
        border-radius: 10px;
    }
    
    .message {
        margin-bottom: 15px;
        padding: 12px 16px;
        border-radius: 18px;
        max-width: 75%;
        word-wrap: break-word;
        font-size: 14px;
        line-height: 1.4;
        animation: fadeIn 0.3s ease;
        position: relative;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .message.user {
        background: linear-gradient(135deg, #3F9EDD 0%, #2A7BC4 100%);
        color: white;
        margin-left: auto;
        border-radius: 18px;
        border-bottom-right-radius: 4px;
        box-shadow: 0 1px 2px rgba(63, 158, 221, 0.3);
        align-self: flex-end;
        margin-right: 0;
        max-width: 75%;
    }
    
    .message.bot {
        background: #f1f3f4;
        color: #333;
        border: 1px solid #e8eaed;
        border-radius: 18px;
        border-bottom-left-radius: 4px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        align-self: flex-start;
        margin-right: auto;
        margin-left: 0;
        max-width: 75%;
    }
    
    .message.admin {
        background: #f1f3f4;
        color: #333;
        border: 1px solid #e8eaed;
        border-radius: 18px;
        border-bottom-left-radius: 4px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        align-self: flex-start;
        margin-right: auto;
        margin-left: 0;
        max-width: 75%;
    }
    
    .message-timestamp {
        font-size: 11px;
        opacity: 0.6;
        margin-top: 4px;
        color: #5f6368;
    }
    
    .message.user .message-timestamp {
        text-align: right;
        color: rgba(255, 255, 255, 0.8);
    }
    
    .message.bot .message-timestamp {
        text-align: left;
        color: #5f6368;
    }
    
    .message.admin .message-timestamp {
        text-align: left;
        color: #5f6368;
    }
    
    .typing-indicator {
        display: none;
        padding: 12px 16px;
        background: white;
        border-radius: 18px;
        border-bottom-left-radius: 5px;
        margin-bottom: 15px;
        max-width: 85%;
        border: 1px solid #e9ecef;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    
    .typing-indicator span {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #3F9EDD;
        margin: 0 2px;
        animation: typing 1.4s infinite;
    }
    
    .typing-indicator span:nth-child(1) { animation-delay: 0s; }
    .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
    .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
    
    @keyframes typing {
        0%, 60%, 100% { transform: scale(0.8); opacity: 0.5; }
        30% { transform: scale(1); opacity: 1; }
    }
    
    .chatbot-input-area {
        padding: 15px 20px;
        background: white;
        border-top: 1px solid #e9ecef;
        display: flex;
        align-items: end;
        gap: 10px;
    }
    
    .chatbot-input {
        flex: 1;
        border: 1px solid #e9ecef;
        border-radius: 20px;
        padding: 10px 15px;
        font-size: 14px;
        resize: none;
        outline: none;
        transition: all 0.3s ease;
        background: #f8f9fa;
        min-height: 20px;
        max-height: 80px;
    }
    
    .chatbot-input:focus {
        border-color: #3F9EDD;
        background: white;
        box-shadow: 0 0 0 3px rgba(63, 158, 221, 0.1);
    }
    
    .chatbot-send {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3F9EDD 0%, #2A7BC4 100%);
        color: white;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    
    .chatbot-send:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(63, 158, 221, 0.3);
    }
    
    .chatbot-send:active {
        transform: scale(0.95);
    }
    
    .chatbot-send:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }
    
    /* Suggestion Box Styles */
    .suggestion-box {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        animation: fadeIn 0.3s ease;
    }
    
    .suggestion-title {
        font-size: 14px;
        font-weight: 600;
        color: #333;
        margin-bottom: 12px;
        text-align: center;
    }
    
    .suggestion-buttons {
        display: flex;
        flex-direction: row;
        gap: 10px;
        justify-content: center;
        margin-top: 8px;
    }

    .suggestion-btn {
        background: #fff;
        color: #3F9EDD;
        border: 1px solid #3F9EDD;
        padding: 10px 18px;
        border-radius: 20px;
        font-size: 15px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        text-align: center;
        display: flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 1px 4px rgba(63, 158, 221, 0.06);
    }

    .suggestion-btn:hover {
        background: #3F9EDD;
        color: #fff;
        box-shadow: 0 2px 8px rgba(63, 158, 221, 0.15);
    }

    .suggestion-btn i {
        margin-right: 6px;
        font-size: 16px;
    }
    
    /* Mobile responsive */
    @media (max-width: 768px) {
        .chatbot-window {
            width: 320px;
            height: 450px;
            bottom: 70px;
            right: 10px;
        }
        
        .chatbot-button {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }
        
        .chatbot-container {
            bottom: 15px;
            right: 15px;
        }
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
    
    /* Satisfaction Check Styles */
    .satisfaction-check,
    .escalation-prompt {
        padding: 15px 20px;
        background: #f8f9fa;
        border-top: 1px solid #dee2e6;
        margin-top: 10px;
        border-radius: 12px;
        box-shadow: 0 1px 4px rgba(63, 158, 221, 0.06);
    }

    .satisfaction-message,
    .escalation-message {
        text-align: center;
    }

    .satisfaction-message p,
    .escalation-message p {
        margin-bottom: 12px;
        font-size: 15px;
        color: #333;
        font-weight: 500;
    }

    .satisfaction-buttons,
    .escalation-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 8px;
    }

    .btn-yes,
    .btn-no,
    .btn-escalate,
    .btn-continue {
        padding: 8px 18px;
        border: none;
        border-radius: 18px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 500;
        background: #fff;
        color: #333;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .btn-yes {
        border: 1px solid #28a745;
        color: #28a745;
    }
    .btn-yes:hover {
        background: #28a745;
        color: #fff;
    }

    .btn-no {
        border: 1px solid #dc3545;
        color: #dc3545;
    }
    .btn-no:hover {
        background: #dc3545;
        color: #fff;
    }

    .btn-escalate {
        border: 1px solid #007bff;
        color: #007bff;
    }
    .btn-escalate:hover {
        background: #007bff;
        color: #fff;
    }

    .btn-continue {
        border: 1px solid #6c757d;
        color: #6c757d;
    }
    .btn-continue:hover {
        background: #6c757d;
        color: #fff;
    }
    
    .escalation-notice {
        background: #fff3cd;
        color: #856404;
        padding: 12px 16px;
        border-radius: 8px;
        margin: 10px 0;
        font-size: 13px;
        text-align: center;
        border: 1px solid #ffeaa7;
        animation: fadeIn 0.3s ease;
    }
    
    .escalated-mode .chatbot-header {
        background: linear-gradient(135deg, #3F9EDD 0%, #2A7BC4 100%);
    }
    
    .escalated-mode {
        border-left: 4px solid #ffc107;
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
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <span class="fw-bold">27</span>
                            </div>
                            <div class="ms-3">
                                <small class="text-muted">December</small>
                            </div>
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

<!-- AI Chatbot -->
<div class="chatbot-container">
    <button class="chatbot-button" id="chatbotToggle">
        🤖
    </button>
    
    <div class="chatbot-window" id="chatbotWindow">
        <div class="chatbot-header">
            <div class="header-left">
                <span class="robot-icon">🤖</span>
                <h5 id="chatbotTitle">AI Assistant</h5>
            </div>
            <div class="header-right">
                <button class="language-toggle" id="languageToggle">EN</button>
                <button class="chatbot-close" id="chatbotClose">&times;</button>
            </div>
        </div>
        
        <div class="chatbot-messages" id="chatbotMessages">
            <div class="message bot" id="welcomeMessage">
                Hello! I'm your AI Assistant. I can help you with:
                <br>• How to request documents
                <br>• How to file complaints  
                <br>• How to register on the website
                <br>• How to get Barangay ID
                <br>• Office hours and contact info
                <br><br>How can I assist you today?
            </div>
        </div>
        
        <div class="typing-indicator" id="typingIndicator">
            <span></span>
            <span></span>
            <span></span>
        </div>
        
        <!-- Satisfaction Check -->
        <div class="satisfaction-check" id="satisfactionCheck" style="display: none;">
            <div class="satisfaction-message">
                <p id="satisfactionText">Was this response helpful?</p>
                <div class="satisfaction-buttons">
                    <button class="btn-yes" onclick="handleSatisfaction(true)">👍 Yes</button>
                    <button class="btn-no" onclick="handleSatisfaction(false)">👎 No</button>
                </div>
            </div>
        </div>
        
        <!-- Escalation Prompt -->
        <div class="escalation-prompt" id="escalationPrompt" style="display: none;">
            <div class="escalation-message">
                <p id="escalationText">I understand that wasn't helpful. Would you like to chat directly with an admin?</p>
                <div class="escalation-buttons">
                    <button class="btn-escalate" onclick="escalateToAdmin()">💬 Chat with Admin</button>
                    <button class="btn-continue" onclick="continueChatbot()">🤖 Continue with Bot</button>
                </div>
            </div>
        </div>
        
        <div class="chatbot-input-area">
            <textarea 
                class="chatbot-input" 
                id="chatbotInput" 
                placeholder="Type your message here..."
                rows="1"
            ></textarea>
            <button class="chatbot-send" id="chatbotSend">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</div>

<script>
// Global function for handling document options
function handleDocumentOption(option) {
    // Remove suggestion box after selection
    const suggestionBoxes = document.querySelectorAll('.suggestion-box');
    suggestionBoxes.forEach(box => box.remove());
    
    // Get current language from the global variable
    const currentLang = window.chatbotLanguage || 'en';
    
    // Add user's choice as a message
    const userChoice = currentLang === 'en' ? 
        (option === 'online' ? 'Online Request' : 'Walk-in Request') :
        (option === 'online' ? 'Online Request' : 'Walk-in Request');
    
    window.addChatMessage(userChoice, 'user');
    
    // Show typing indicator
    window.showChatTyping();
    
    // Simulate delay for better UX
    setTimeout(() => {
        window.hideChatTyping();
        
        if (option === 'online') {
            window.showOnlineInstructions();
        } else {
            window.showWalkInInstructions();
        }
    }, 1000);
}

// Global function for handling complaint options
function handleComplaintOption(option) {
    // Remove suggestion box after selection
    const suggestionBoxes = document.querySelectorAll('.suggestion-box');
    suggestionBoxes.forEach(box => box.remove());
    
    // Get current language from the global variable
    const currentLang = window.chatbotLanguage || 'en';
    
    // Add user's choice as a message
    const userChoice = currentLang === 'en' ? 
        (option === 'online' ? 'Online Complaint' : 'Walk-in Complaint') :
        (option === 'online' ? 'Online Complaint' : 'Walk-in Complaint');
    
    window.addChatMessage(userChoice, 'user');
    
    // Show typing indicator
    window.showChatTyping();
    
    // Simulate delay for better UX
    setTimeout(() => {
        window.hideChatTyping();
        
        if (option === 'online') {
            window.showOnlineComplaintInstructions();
        } else {
            window.showWalkInComplaintInstructions();
        }
    }, 1000);
}

// Global function for handling Barangay ID options
function handleBarangayIdOption(option) {
    // Remove suggestion box after selection
    const suggestionBoxes = document.querySelectorAll('.suggestion-box');
    suggestionBoxes.forEach(box => box.remove());
    
    // Get current language from the global variable
    const currentLang = window.chatbotLanguage || 'en';
    
    // Add user's choice as a message
    const userChoice = currentLang === 'en' ? 
        (option === 'online' ? 'Online Pre-Registration' : 'Walk-in Application') :
        (option === 'online' ? 'Online Pre-Registration' : 'Walk-in Application');
    
    window.addChatMessage(userChoice, 'user');
    
    // Show typing indicator
    window.showChatTyping();
    
    // Simulate delay for better UX
    setTimeout(() => {
        window.hideChatTyping();
        
        if (option === 'online') {
            window.showOnlineBarangayIdInstructions();
        } else {
            window.showWalkInBarangayIdInstructions();
        }
    }, 1000);
}

document.addEventListener('DOMContentLoaded', function() {
    const chatbotToggle = document.getElementById('chatbotToggle');
    const chatbotWindow = document.getElementById('chatbotWindow');
    const chatbotClose = document.getElementById('chatbotClose');
    const chatbotInput = document.getElementById('chatbotInput');
    const chatbotSend = document.getElementById('chatbotSend');
    const chatbotMessages = document.getElementById('chatbotMessages');
    const typingIndicator = document.getElementById('typingIndicator');
    const languageToggle = document.getElementById('languageToggle');
    const chatbotTitle = document.getElementById('chatbotTitle');
    const welcomeMessage = document.getElementById('welcomeMessage');
    const satisfactionCheck = document.getElementById('satisfactionCheck');
    const escalationPrompt = document.getElementById('escalationPrompt');
    
    let currentLanguage = 'en';
    let isOpen = false;
    let currentMode = 'bot'; // 'bot' or 'admin'
    let lastBotResponse = null;
    let conversationHistory = [];
    
    // Make language available globally
    window.chatbotLanguage = currentLanguage;
    
    const translations = {
        en: {
            title: 'AI Assistant',
            welcome: 'Hello! I\'m your AI Assistant. I can help you with:<br>• How to request documents<br>• How to file complaints<br>• How to register on the website<br>• How to get Barangay ID<br>• Office hours and contact info<br><br>How can I assist you today?',
            placeholder: 'Type your message here...',
            langButton: 'EN',
            satisfactionQuestion: 'Was this response helpful?',
            escalationQuestion: 'I understand that wasn\'t helpful. Would you like to chat directly with an admin?',
            adminConnected: '📞 You are now connected to live admin support',
            adminTitle: 'Live Admin Support'
        },
        tl: {
            title: 'AI Assistant',
            welcome: 'Kumusta! Ako ang inyong AI Assistant. Makakatulong ako sa inyo sa:<br>• Paano mag-request ng mga dokumento<br>• Paano mag-file ng complaint<br>• Paano mag-register sa website<br>• Paano makakuha ng Barangay ID<br>• Oras ng opisina at contact info<br><br>Paano kita makakatulong ngayon?',
            placeholder: 'I-type ang inyong mensahe dito...',
            langButton: 'TL',
            satisfactionQuestion: 'Nakatulong ba ang sagot na ito?',
            escalationQuestion: 'Naintindihan ko na hindi nakatulong ang sagot. Gusto ninyo bang makausap directly ang admin?',
            adminConnected: '📞 Kayo ay nakakonekta na sa live admin support',
            adminTitle: 'Live Admin Support'
        }
    };
    
    // Toggle chatbot window
    chatbotToggle.addEventListener('click', function() {
        if (isOpen) {
            closeChatbot();
        } else {
            openChatbot();
        }
    });
    
    chatbotClose.addEventListener('click', closeChatbot);
    
    // Language toggle
    languageToggle.addEventListener('click', function() {
        currentLanguage = currentLanguage === 'en' ? 'tl' : 'en';
        updateLanguage();
    });
    
    function updateLanguage() {
        const lang = translations[currentLanguage];
        chatbotTitle.textContent = lang.title;
        welcomeMessage.innerHTML = lang.welcome;
        chatbotInput.placeholder = lang.placeholder;
        languageToggle.textContent = lang.langButton;
        
        // Update global variable
        window.chatbotLanguage = currentLanguage;
    }
    
    function openChatbot() {
        chatbotWindow.style.display = 'flex';
        isOpen = true;
        chatbotInput.focus();
    }
    
    function closeChatbot() {
        // If we were in admin mode, stop polling and close session
        if (currentMode === 'admin' && currentChatSessionId) {
            stopPolling();
            // Close the session on the server
            fetch(`/api/live-chat/close/${currentChatSessionId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            currentChatSessionId = null;
            lastMessageId = 0;
        }
        
        chatbotWindow.style.display = 'none';
        isOpen = false;
    }
    
    // Send message
    chatbotSend.addEventListener('click', sendMessage);
    chatbotInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
    
    // Auto-resize textarea
    chatbotInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 80) + 'px';
    });
    
    async function sendMessage() {
        const message = chatbotInput.value.trim();
        if (!message) return;
        
        // Add user message
        addMessage(message, 'user');
        chatbotInput.value = '';
        chatbotInput.style.height = 'auto';
        
        // Hide satisfaction check if visible
        hideSatisfactionCheck();
        hideEscalationPrompt();
        
        // Show typing indicator
        showTyping();
        
        // Disable send button
        chatbotSend.disabled = true;
        
        try {
            if (currentMode === 'admin') {
                // Admin mode - send to admin chat endpoint
                await handleAdminChat(message);
                chatbotSend.disabled = false;
                return;
            }
            
            // Check if user is asking about document requests
            if (isDocumentRequest(message)) {
                hideTyping();
                showDocumentRequestOptions();
                showSatisfactionCheckDelayed();
                chatbotSend.disabled = false;
                return;
            }
            
            // Check if user is asking about complaint filing
            if (isComplaintRequest(message)) {
                hideTyping();
                showComplaintRequestOptions();
                showSatisfactionCheckDelayed();
                chatbotSend.disabled = false;
                return;
            }
            
            // Check if user is asking about Barangay ID
            if (isBarangayIdRequest(message)) {
                hideTyping();
                showBarangayIdOptions();
                showSatisfactionCheckDelayed();
                chatbotSend.disabled = false;
                return;
            }
            
            // Check if user is asking about location
            if (isLocationRequest(message)) {
                hideTyping();
                showLocationInfo();
                showSatisfactionCheckDelayed();
                chatbotSend.disabled = false;
                return;
            }
            
            // Check if user is asking about office hours
            if (isOfficeHoursRequest(message)) {
                hideTyping();
                showOfficeHoursInfo();
                showSatisfactionCheckDelayed();
                chatbotSend.disabled = false;
                return;
            }
            
            // Check if user is asking about contact details
            if (isContactRequest(message)) {
                hideTyping();
                showContactInfo();
                showSatisfactionCheckDelayed();
                chatbotSend.disabled = false;
                return;
            }

            const response = await fetch('/api/chatbot/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    message: message,
                    language: currentLanguage
                })
            });
            
            const data = await response.json();
            
            // Hide typing indicator
            hideTyping();
            
            if (data.success) {
                lastBotResponse = data.response;
                addMessage(data.response, 'bot');
                conversationHistory.push({ role: 'user', content: message });
                conversationHistory.push({ role: 'bot', content: data.response });
                
                // Show satisfaction check after bot response
                showSatisfactionCheckDelayed();
            } else {
                const errorMsg = currentLanguage === 'en' 
                    ? 'Sorry, I encountered an error. Please try again.'
                    : 'Pasensya na, may problema ako. Subukan ulit.';
                addMessage(errorMsg, 'bot');
            }
        } catch (error) {
            console.error('Chat error:', error);
            hideTyping();
            const errorMsg = currentLanguage === 'en' 
                ? 'Sorry, I\'m having trouble connecting. Please try again later.'
                : 'Pasensya na, may problema sa koneksyon. Subukan mo ulit mamaya.';
            addMessage(errorMsg, 'bot');
        } finally {
            chatbotSend.disabled = false;
        }
    }    function isDocumentRequest(message) {
        const englishKeywords = ['document', 'request', 'certificate', 'clearance', 'residency', 'indigency', 'how to request'];
        const tagalogKeywords = ['dokumento', 'humingi', 'sertipiko', 'clearance', 'residensya', 'indigency', 'paano mag-request', 'paano humiling'];
        
        const lowerMessage = message.toLowerCase();
        const allKeywords = [...englishKeywords, ...tagalogKeywords];
        
        return allKeywords.some(keyword => lowerMessage.includes(keyword));
    }
    
    function isComplaintRequest(message) {
        const englishKeywords = ['complaint', 'file complaint', 'complain', 'report', 'how to file', 'how to complain'];
        const tagalogKeywords = ['reklamo', 'mag-reklamo', 'magfile ng reklamo', 'paano mag-file', 'paano mag-complain', 'paano mag-reklamo', 'sumbong'];
        
        const lowerMessage = message.toLowerCase();
        const allKeywords = [...englishKeywords, ...tagalogKeywords];
        
        return allKeywords.some(keyword => lowerMessage.includes(keyword));
    }
    
    function isBarangayIdRequest(message) {
        const englishKeywords = ['barangay id', 'get barangay id', 'how to get barangay id', 'barangay identification', 'resident id', 'how to get id'];
        const tagalogKeywords = ['barangay id', 'paano makakuha ng barangay id', 'paano kumuha ng barangay id', 'id ng barangay', 'resident id'];
        
        const lowerMessage = message.toLowerCase();
        const allKeywords = [...englishKeywords, ...tagalogKeywords];
        
        return allKeywords.some(keyword => lowerMessage.includes(keyword));
    }
    
    function isLocationRequest(message) {
        const englishKeywords = ['location', 'where is', 'address', 'barangay hall location', 'where can i find', 'saan ang'];
        const tagalogKeywords = ['saan', 'address', 'lokasyon', 'nasaan', 'saan pwede', 'kung saan'];
        
        const lowerMessage = message.toLowerCase();
        const allKeywords = [...englishKeywords, ...tagalogKeywords];
        
        return allKeywords.some(keyword => lowerMessage.includes(keyword));
    }
    
    function isOfficeHoursRequest(message) {
        const englishKeywords = ['office hours', 'what time', 'operating hours', 'open', 'close', 'schedule', 'anong oras'];
        const tagalogKeywords = ['oras', 'anong oras', 'bukas', 'sarado', 'schedule', 'kailan bukas'];
        
        const lowerMessage = message.toLowerCase();
        const allKeywords = [...englishKeywords, ...tagalogKeywords];
        
        return allKeywords.some(keyword => lowerMessage.includes(keyword));
    }
    
    function isContactRequest(message) {
        const englishKeywords = ['contact', 'phone number', 'call', 'telephone', 'number', 'contact details'];
        const tagalogKeywords = ['contact', 'numero', 'telepono', 'tawagan', 'contact number'];
        
        const lowerMessage = message.toLowerCase();
        const allKeywords = [...englishKeywords, ...tagalogKeywords];
        
        return allKeywords.some(keyword => lowerMessage.includes(keyword));
    }
    
    function showDocumentRequestOptions() {
        const optionsHtml = currentLanguage === 'en' ? `
            <div class="suggestion-box">
                <div class="suggestion-title">How would you like to request documents?</div>
                <div class="suggestion-buttons">
                    <button class="suggestion-btn" onclick="handleDocumentOption('online')">
                        <i class="fas fa-laptop"></i>
                        Online Request
                    </button>
                    <button class="suggestion-btn" onclick="handleDocumentOption('walkin')">
                        <i class="fas fa-walking"></i>
                        Walk-in Request
                    </button>
                </div>
            </div>
        ` : `
            <div class="suggestion-box">
                <div class="suggestion-title">Paano ninyo gustong mag-request ng mga dokumento?</div>
                <div class="suggestion-buttons">
                    <button class="suggestion-btn" onclick="handleDocumentOption('online')">
                        <i class="fas fa-laptop"></i>
                        Online Request
                    </button>
                    <button class="suggestion-btn" onclick="handleDocumentOption('walkin')">
                        <i class="fas fa-walking"></i>
                        Walk-in Request
                    </button>
                </div>
            </div>
        `;
        
        addMessage(optionsHtml, 'bot');
    }
    
    function showComplaintRequestOptions() {
        const optionsHtml = currentLanguage === 'en' ? `
            <div class="suggestion-box">
                <div class="suggestion-title">How would you like to file a complaint?</div>
                <div class="suggestion-buttons">
                    <button class="suggestion-btn" onclick="handleComplaintOption('online')">
                        <i class="fas fa-laptop"></i>
                        Online Complaint
                    </button>
                    <button class="suggestion-btn" onclick="handleComplaintOption('walkin')">
                        <i class="fas fa-walking"></i>
                        Walk-in Complaint
                    </button>
                </div>
            </div>
        ` : `
            <div class="suggestion-box">
                <div class="suggestion-title">Paano ninyo gustong mag-file ng reklamo?</div>
                <div class="suggestion-buttons">
                    <button class="suggestion-btn" onclick="handleComplaintOption('online')">
                        <i class="fas fa-laptop"></i>
                        Online Complaint
                    </button>
                    <button class="suggestion-btn" onclick="handleComplaintOption('walkin')">
                        <i class="fas fa-walking"></i>
                        Walk-in Complaint
                    </button>
                </div>
            </div>
        `;
        
        addMessage(optionsHtml, 'bot');
    }
    
    function showBarangayIdOptions() {
        const optionsHtml = currentLanguage === 'en' ? `
            <div class="suggestion-box">
                <div class="suggestion-title">How would you like to get your Barangay ID?</div>
                <div class="suggestion-buttons">
                    <button class="suggestion-btn" onclick="handleBarangayIdOption('online')">
                        <i class="fas fa-laptop"></i>
                        Online Pre-Registration
                    </button>
                    <button class="suggestion-btn" onclick="handleBarangayIdOption('walkin')">
                        <i class="fas fa-walking"></i>
                        Walk-in Application
                    </button>
                </div>
            </div>
        ` : `
            <div class="suggestion-box">
                <div class="suggestion-title">Paano ninyo gustong makuha ang inyong Barangay ID?</div>
                <div class="suggestion-buttons">
                    <button class="suggestion-btn" onclick="handleBarangayIdOption('online')">
                        <i class="fas fa-laptop"></i>
                        Online Pre-Registration
                    </button>
                    <button class="suggestion-btn" onclick="handleBarangayIdOption('walkin')">
                        <i class="fas fa-walking"></i>
                        Walk-in Application
                    </button>
                </div>
            </div>
        `;
        
        addMessage(optionsHtml, 'bot');
    }
    
    function showLocationInfo() {
        const locationInfo = currentLanguage === 'en' ? `
            <strong>📍 Barangay Hall Location:</strong><br><br>
            <strong>Address:</strong><br>
            Barangay Lumanglipa Hall<br>
            Mataas na Kahoy, Batangas<br><br>
            <strong>Landmarks:</strong><br>
            • Near the main road<br>
            • Across from the elementary school<br>
            • Next to the health center<br><br>
            <em>You can also ask for directions from locals in the area.</em>
        ` : `
            <strong>📍 Lokasyon ng Barangay Hall:</strong><br><br>
            <strong>Address:</strong><br>
            Barangay Lumanglipa Hall<br>
            Mataas na Kahoy, Batangas<br><br>
            <strong>Mga Landmark:</strong><br>
            • Malapit sa main road<br>
            • Tapat ng elementary school<br>
            • Katabi ng health center<br><br>
            <em>Pwede rin kayong magtanong sa mga tao sa lugar para sa directions.</em>
        `;
        
        addMessage(locationInfo, 'bot');
    }
    
    function showOfficeHoursInfo() {
        const hoursInfo = currentLanguage === 'en' ? `
            <strong>🕒 Barangay Office Hours:</strong><br><br>
            <strong>Regular Days:</strong><br>
            Monday - Friday<br>
            8:00 AM - 5:00 PM<br><br>
            <strong>Lunch Break:</strong><br>
            12:00 PM - 1:00 PM<br><br>
            <strong>Weekends:</strong><br>
            Saturday & Sunday - CLOSED<br><br>
            <strong>Holidays:</strong><br>
            Closed on national and local holidays<br><br>
            <em>For emergency concerns, you may contact our hotline.</em>
        ` : `
            <strong>🕒 Oras ng Barangay Office:</strong><br><br>
            <strong>Regular na Araw:</strong><br>
            Lunes - Biyernes<br>
            8:00 AM - 5:00 PM<br><br>
            <strong>Lunch Break:</strong><br>
            12:00 PM - 1:00 PM<br><br>
            <strong>Weekends:</strong><br>
            Sabado & Linggo - SARADO<br><br>
            <strong>Mga Holiday:</strong><br>
            Sarado sa national at local holidays<br><br>
            <em>Para sa emergency, pwede kayong tumawag sa aming hotline.</em>
        `;
        
        addMessage(hoursInfo, 'bot');
    }
    
    function showContactInfo() {
        const contactInfo = currentLanguage === 'en' ? `
            <strong>📞 Barangay Contact Information:</strong><br><br>
            <strong>Mobile Number:</strong><br>
            0917-123-4567<br><br>
            <strong>Landline:</strong><br>
            (043) 456-7890<br><br>
            <strong>Email Address:</strong><br>
            barangay.lumanglipa@gmail.com<br><br>
            <strong>Facebook Page:</strong><br>
            @BarangayLumanglipa<br><br>
            <strong>Emergency Hotline:</strong><br>
            0918-987-6543<br><br>
            <em>Available 24/7 for emergency concerns only.</em>
        ` : `
            <strong>📞 Contact Information ng Barangay:</strong><br><br>
            <strong>Mobile Number:</strong><br>
            0917-123-4567<br><br>
            <strong>Landline:</strong><br>
            (043) 456-7890<br><br>
            <strong>Email Address:</strong><br>
            barangay.lumanglipa@gmail.com<br><br>
            <strong>Facebook Page:</strong><br>
            @BarangayLumanglipa<br><br>
            <strong>Emergency Hotline:</strong><br>
            0918-987-6543<br><br>
            <em>Available 24/7 para sa emergency concerns lang.</em>
        `;
        
        addMessage(contactInfo, 'bot');
    }
    
    function formatTime(date) {
        return date.toLocaleTimeString('en-US', {
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
    }

    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;
        
        const messageContent = document.createElement('div');
        messageContent.innerHTML = text;
        
        const timestamp = document.createElement('div');
        timestamp.className = 'message-timestamp';
        timestamp.textContent = formatTime(new Date());
        
        messageDiv.appendChild(messageContent);
        messageDiv.appendChild(timestamp);
        
        chatbotMessages.appendChild(messageDiv);
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }
    
    function showTyping() {
        typingIndicator.style.display = 'flex';
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }
    
    function hideTyping() {
        typingIndicator.style.display = 'none';
    }
    
    // Make functions available globally
    window.addChatMessage = addMessage;
    window.showChatTyping = showTyping;
    window.hideChatTyping = hideTyping;
    
    window.showOnlineInstructions = function() {
        const instructions = window.chatbotLanguage === 'en' ? `
            <strong>📱 Online Document Request Instructions:</strong><br><br>
            <strong>Step 1:</strong> Go to the navigation menu and click <strong>"eServices"</strong> then <strong>"Document Request"</strong><br><br>
            <strong>📝 Important Note:</strong> You need a <strong>Barangay ID/Resident ID</strong> to proceed.<br>
            • If you don't have one, visit the barangay office first to get your ID<br>
            • OR you can <strong>pre-register</strong> by clicking <strong>"Register"</strong> in the navigation menu<br><br>
            <strong>Step 2:</strong> Enter your <strong>Barangay ID number</strong><br><br>
            <strong>Step 3:</strong> An <strong>OTP will be sent to your email</strong> - enter this code to verify and start your document request<br><br>
            <strong>Step 4:</strong> Choose the <strong>document type</strong> you need and fill out the form<br><br>
            <strong>Step 5:</strong> <strong>Upload payment receipt</strong> (₱50.00) to approve your request<br><br>
            <strong>Step 6:</strong> Wait for processing and approval<br><br>
            <em>💰 Fee: ₱50.00 per document | Processing time: 1-3 business days</em>
        ` : `
            <strong>📱 Mga Hakbang sa Online Document Request:</strong><br><br>
            <strong>Hakbang 1:</strong> Pumunta sa navigation menu at pindutin ang <strong>"eServices"</strong> tapos <strong>"Document Request"</strong><br><br>
            <strong>📝 Mahalagang Paalala:</strong> Kailangan ninyo ng <strong>Barangay ID/Resident ID</strong> para magpatuloy.<br>
            • Kung wala pa kayo, puntahan muna ang barangay office para makakuha ng ID<br>
            • O pwede kayong <strong>mag-pre-register</strong> sa pamamagitan ng pagpindot sa <strong>"Register"</strong> sa navigation menu<br><br>
            <strong>Hakbang 2:</strong> Ilagay ang inyong <strong>Barangay ID number</strong><br><br>
            <strong>Hakbang 3:</strong> Magpapadala ng <strong>OTP sa inyong email</strong> - ilagay ang code na ito para ma-verify at masimulan ang document request<br><br>
            <strong>Hakbang 4:</strong> Piliin ang <strong>uri ng dokumento</strong> na kailangan ninyo at punan ang form<br><br>
            <strong>Hakbang 5:</strong> <strong>Mag-upload ng payment receipt</strong> (₱50.00) para ma-approve ang inyong request<br><br>
            <strong>Hakbang 6:</strong> Maghintay ng processing at approval<br><br>
            <em>💰 Bayad: ₱50.00 bawat dokumento | Processing time: 1-3 araw ng trabaho</em>
        `;
        
        addMessage(instructions, 'bot');
    };
    
    window.showWalkInInstructions = function() {
        const instructions = window.chatbotLanguage === 'en' ? `
            <strong>🚶 Walk-in Document Request Instructions:</strong><br><br>
            <strong>What to bring:</strong><br>
            • Valid ID (original and photocopy)<br>
            • Proof of residency (if needed)<br>
            • Other requirements depending on document type<br><br>
            <strong>Process:</strong><br>
            <strong>Step 1:</strong> Go to Barangay Hall<br>
            <strong>Step 2:</strong> Get application form from the secretary<br>
            <strong>Step 3:</strong> Fill out the form completely<br>
            <strong>Step 4:</strong> Submit form with requirements<br>
            <strong>Step 5:</strong> Pay fees if applicable<br>
            <strong>Step 6:</strong> Wait for processing or return on scheduled date<br><br>
            <strong>📍 Location:</strong><br>
            Barangay Lumanglipa Hall<br><br>
            <strong>🕒 Office Hours:</strong><br>
            Monday-Friday, 8:00 AM - 5:00 PM<br><br>
            <strong>📞 Contact:</strong><br>
            0917-123-4567<br><br>
            <em>Processing time: Same day to 2 business days</em>
        ` : `
            <strong>🚶 Mga Hakbang sa Walk-in Document Request:</strong><br><br>
            <strong>Dalhin ninyo:</strong><br>
            • Valid ID (original at photocopy)<br>
            • Proof of residency (kung kailangan)<br>
            • Ibang requirements depende sa uri ng dokumento<br><br>
            <strong>Proseso:</strong><br>
            <strong>Hakbang 1:</strong> Pumunta sa Barangay Hall<br>
            <strong>Hakbang 2:</strong> Kumuha ng application form sa secretary<br>
            <strong>Hakbang 3:</strong> Punan ang form nang kumpleto<br>
            <strong>Hakbang 4:</strong> I-submit ang form kasama ang requirements<br>
            <strong>Hakbang 5:</strong> Magbayad ng fees kung meron<br>
            <strong>Hakbang 6:</strong> Maghintay ng processing o bumalik sa nakatakdang araw<br><br>
            <strong>📍 Location:</strong><br>
            Barangay Lumanglipa Hall<br><br>
            <strong>🕒 Office Hours:</strong><br>
            Lunes-Biyernes, 8:00 AM - 5:00 PM<br><br>
            <strong>📞 Contact:</strong><br>
            0917-123-4567<br><br>
            <em>Processing time: Same day hanggang 2 araw ng trabaho</em>
        `;
        
        addMessage(instructions, 'bot');
    };
    
    window.showOnlineComplaintInstructions = function() {
        const instructions = window.chatbotLanguage === 'en' ? `
            <strong>💻 Online Complaint Filing Instructions:</strong><br><br>
            <strong>Step 1:</strong> Go to the navigation menu and click <strong>"eServices"</strong> then <strong>"File Complaint"</strong><br><br>
            <strong>📝 Important Note:</strong> You need to be <strong>registered</strong> to file an online complaint.<br>
            • If you're not registered, click <strong>"Register"</strong> in the navigation menu first<br><br>
            <strong>Step 2:</strong> <strong>Login</strong> to your account<br><br>
            <strong>Step 3:</strong> Select the <strong>type of complaint</strong> you want to file<br><br>
            <strong>Step 4:</strong> Fill out the complaint form with detailed information:<br>
            • Description of the issue<br>
            • Date and time of incident<br>
            • Location where it occurred<br>
            • Any witnesses (if applicable)<br><br>
            <strong>Step 5:</strong> Upload supporting documents or evidence (if any)<br><br>
            <strong>Step 6:</strong> Submit your complaint<br><br>
            <strong>Step 7:</strong> You will receive a complaint reference number for tracking<br><br>
            <em>📝 Processing time: 1-3 business days | You will be notified via email for updates</em>
        ` : `
            <strong>💻 Mga Hakbang sa Online Complaint Filing:</strong><br><br>
            <strong>Hakbang 1:</strong> Pumunta sa navigation menu at pindutin ang <strong>"eServices"</strong> tapos <strong>"File Complaint"</strong><br><br>
            <strong>📝 Mahalagang Paalala:</strong> Kailangan kayong <strong>naka-register</strong> para mag-file ng online complaint.<br>
            • Kung hindi pa kayo registered, pindutin muna ang <strong>"Register"</strong> sa navigation menu<br><br>
            <strong>Hakbang 2:</strong> <strong>Mag-login</strong> sa inyong account<br><br>
            <strong>Hakbang 3:</strong> Piliin ang <strong>uri ng reklamo</strong> na gusto ninyong i-file<br><br>
            <strong>Hakbang 4:</strong> Punan ang complaint form nang detalyado:<br>
            • Paglalarawan ng problema<br>
            • Petsa at oras ng pangyayari<br>
            • Lugar kung saan nangyari<br>
            • Mga saksi (kung meron)<br><br>
            <strong>Hakbang 5:</strong> Mag-upload ng mga supporting documents o ebidensya (kung meron)<br><br>
            <strong>Hakbang 6:</strong> I-submit ang inyong reklamo<br><br>
            <strong>Hakbang 7:</strong> Makakakuha kayo ng complaint reference number para sa tracking<br><br>
            <em>📝 Processing time: 1-3 araw ng trabaho | Makakakuha kayo ng notification sa email</em>
        `;
        
        addMessage(instructions, 'bot');
    };
    
    window.showWalkInComplaintInstructions = function() {
        const instructions = window.chatbotLanguage === 'en' ? `
            <strong>🚶 Walk-in Complaint Filing Instructions:</strong><br><br>
            <strong>What to bring:</strong><br>
            • Valid ID (original and photocopy)<br>
            • Supporting documents or evidence (if any)<br>
            • Written statement of the complaint<br><br>
            <strong>Process:</strong><br>
            <strong>Step 1:</strong> Go to Barangay Hall<br>
            <strong>Step 2:</strong> Approach the secretary or duty officer<br>
            <strong>Step 3:</strong> Request for a complaint form<br>
            <strong>Step 4:</strong> Fill out the complaint form completely with:<br>
            • Your personal information<br>
            • Detailed description of the complaint<br>
            • Date, time, and location of incident<br>
            • Names of witnesses (if any)<br><br>
            <strong>Step 5:</strong> Submit the form with supporting documents<br>
            <strong>Step 6:</strong> Receive a complaint reference number<br>
            <strong>Step 7:</strong> Wait for the barangay officials to schedule hearing<br><br>
            <strong>📍 Location:</strong><br>
            Barangay Lumanglipa Hall<br><br>
            <strong>🕒 Office Hours:</strong><br>
            Monday-Friday, 8:00 AM - 5:00 PM<br><br>
            <strong>📞 Contact:</strong><br>
            0917-123-4567<br><br>
            <em>Processing time: 1-3 business days | You will be contacted for hearing</em>
        ` : `
            <strong>🚶 Mga Hakbang sa Walk-in Complaint Filing:</strong><br><br>
            <strong>Dalhin ninyo:</strong><br>
            • Valid ID (original at photocopy)<br>
            • Supporting documents o ebidensya (kung meron)<br>
            • Nakasulat na statement ng reklamo<br><br>
            <strong>Proseso:</strong><br>
            <strong>Hakbang 1:</strong> Pumunta sa Barangay Hall<br>
            <strong>Hakbang 2:</strong> Lapitan ang secretary o duty officer<br>
            <strong>Hakbang 3:</strong> Humingi ng complaint form<br>
            <strong>Hakbang 4:</strong> Punan ang complaint form nang kumpleto:<br>
            • Inyong personal information<br>
            • Detalyadong paglalarawan ng reklamo<br>
            • Petsa, oras, at lugar ng pangyayari<br>
            • Mga pangalan ng saksi (kung meron)<br><br>
            <strong>Hakbang 5:</strong> I-submit ang form kasama ang supporting documents<br>
            <strong>Hakbang 6:</strong> Makakakuha ng complaint reference number<br>
            <strong>Hakbang 7:</strong> Maghintay na i-schedule ng barangay officials ang hearing<br><br>
            <strong>📍 Location:</strong><br>
            Barangay Lumanglipa Hall<br><br>
            <strong>🕒 Office Hours:</strong><br>
            Lunes-Biyernes, 8:00 AM - 5:00 PM<br><br>
            <strong>📞 Contact:</strong><br>
            0917-123-4567<br><br>
            <em>Processing time: 1-3 araw ng trabaho | Makakakuha kayo ng tawag para sa hearing</em>
        `;
        
        addMessage(instructions, 'bot');
    };
    
    window.showOnlineBarangayIdInstructions = function() {
        const instructions = window.chatbotLanguage === 'en' ? `
            <strong>💻 Online Barangay ID Pre-Registration Instructions:</strong><br><br>
            <strong>Step 1:</strong> Go to the navigation menu and click <strong>"Register"</strong><br><br>
            <strong>Step 2:</strong> Fill out the registration form with your complete information.<br><br>
            <strong>Step 3:</strong> Submit your registration form<br><br>
            <strong>Step 4:</strong> Wait for admin approval<br><br>
            <strong>Step 5:</strong> You will receive an email notification when your registration is approved<br><br>
            <strong>Step 6:</strong> Your Barangay ID will be ready for release upon approval<br><br>
            <strong>✅ Benefits of Barangay ID:</strong><br>
            • Verifies your residency in the barangay<br>
            • Allows access to online services and document requests<br>
            • Required for most barangay transactions<br><br>
            <em>📧 Processing time: 1-3 business days | Email notification upon approval</em>
        ` : `
            <strong>💻 Mga Hakbang sa Online Barangay ID Pre-Registration:</strong><br><br>
            <strong>Hakbang 1:</strong> Pumunta sa navigation menu at pindutin ang <strong>"Register"</strong><br><br>
            <strong>Hakbang 2:</strong> Punan ang registration form ng kumpleto:<br><br>
            <strong>Hakbang 3:</strong> I-submit ang registration form<br><br>
            <strong>Hakbang 4:</strong> Maghintay ng admin approval<br><br>
            <strong>Hakbang 5:</strong> Makatanggap ng email notification kapag na-approve na ang registration<br><br>
            <strong>Hakbang 6:</strong> Handa na ang inyong Barangay ID para sa release<br><br>
            <strong>✅ Mga Benepisyo ng Barangay ID:</strong><br>
            • Nagpapatunay na residente kayo ng barangay<br>
            • Nagbibigay ng access sa online services at document requests<br>
            • Kailangan sa karamihan ng barangay transactions<br><br>
            <em>📧 Processing time: 1-3 araw ng trabaho | Email notification kapag approved na</em>
        `;
        
        addMessage(instructions, 'bot');
    };
    
    window.showWalkInBarangayIdInstructions = function() {
        const instructions = window.chatbotLanguage === 'en' ? `
            <strong>🚶 Walk-in Barangay ID Application Instructions:</strong><br><br>
            <strong>What to bring:</strong><br>
            • Valid government-issued ID (original and photocopy)<br>
            • Proof of residency (utility bills, rental contract, etc.)<br>
            • 2x2 ID pictures (2 pieces)<br>
            • Cedula or Community Tax Certificate<br><br>
            <strong>Process:</strong><br>
            <strong>Step 1:</strong> Go to Barangay Hall<br>
            <strong>Step 2:</strong> Approach the secretary or duty officer<br>
            <strong>Step 3:</strong> Request for Barangay ID application form<br>
            <strong>Step 4:</strong> Fill out the application form completely<br>
            <strong>Step 5:</strong> Submit the form with all required documents<br>
            <strong>Step 6:</strong> Pay the processing fee (if applicable)<br>
            <strong>Step 7:</strong> Wait for processing or return on scheduled date<br><br>
            <strong>✅ Benefits of Barangay ID:</strong><br>
            • Verifies your residency in the barangay<br>
            • Allows access to online services and document requests<br>
            • Required for most barangay transactions<br><br>
            <strong>📍 Location:</strong><br>
            Barangay Lumanglipa Hall<br><br>
            <strong>🕒 Office Hours:</strong><br>
            Monday-Friday, 8:00 AM - 5:00 PM<br><br>
            <strong>📞 Contact:</strong><br>
            0917-123-4567<br><br>
            <em>Processing time: 3-7 business days | Same day if all requirements are complete</em>
        ` : `
            <strong>🚶 Mga Hakbang sa Walk-in Barangay ID Application:</strong><br><br>
            <strong>Dalhin ninyo:</strong><br>
            • Valid government-issued ID (original at photocopy)<br>
            • Proof of residency (utility bills, rental contract, atbp.)<br>
            • 2x2 ID pictures (2 piraso)<br>
            • Cedula o Community Tax Certificate<br><br>
            <strong>Proseso:</strong><br>
            <strong>Hakbang 1:</strong> Pumunta sa Barangay Hall<br>
            <strong>Hakbang 2:</strong> Lapitan ang secretary o duty officer<br>
            <strong>Hakbang 3:</strong> Humingi ng Barangay ID application form<br>
            <strong>Hakbang 4:</strong> Punan ang application form nang kumpleto<br>
            <strong>Hakbang 5:</strong> I-submit ang form kasama lahat ng required documents<br>
            <strong>Hakbang 6:</strong> Magbayad ng processing fee (kung meron)<br>
            <strong>Hakbang 7:</strong> Maghintay ng processing o bumalik sa nakatakdang araw<br><br>
            <strong>✅ Mga Benepisyo ng Barangay ID:</strong><br>
            • Nagpapatunay na residente kayo ng barangay<br>
            • Nagbibigay ng access sa online services at document requests<br>
            • Kailangan sa karamihan ng barangay transactions<br><br>
            <strong>📍 Location:</strong><br>
            Barangay Lumanglipa Hall<br><br>
            <strong>🕒 Office Hours:</strong><br>
            Lunes-Biyernes, 8:00 AM - 5:00 PM<br><br>
            <strong>📞 Contact:</strong><br>
            0917-123-4567<br><br>
            <em>Processing time: 3-7 araw ng trabaho | Same day kung kumpleto ang requirements</em>
        `;
        
        addMessage(instructions, 'bot');
    };
    
    // Satisfaction checking functions
    function showSatisfactionCheckDelayed() {
        setTimeout(() => {
            showSatisfactionCheck();
        }, 1500);
    }
    
    function showSatisfactionCheck() {
        const satisfactionCheck = document.getElementById('satisfactionCheck');
        const satisfactionText = document.getElementById('satisfactionText');
        
        satisfactionText.textContent = currentLanguage === 'en' 
            ? 'Was this response helpful?' 
            : 'Nakatulong ba ang sagot na ito?';
            
        satisfactionCheck.style.display = 'block';
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }
    
    function hideSatisfactionCheck() {
        const satisfactionCheck = document.getElementById('satisfactionCheck');
        satisfactionCheck.style.display = 'none';
    }
    
    function hideEscalationPrompt() {
        const escalationPrompt = document.getElementById('escalationPrompt');
        escalationPrompt.style.display = 'none';
    }
    
    // Admin chat handling
    let currentChatSessionId = null;
    let lastMessageId = 0;
    let isPolling = false;
    
    async function handleAdminChat(message) {
        conversationHistory.push({ role: 'user', content: message });
        
        try {
            // If no session exists, escalate to admin first
            if (!currentChatSessionId) {
                const escalateResponse = await fetch('/api/live-chat/escalate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        user_message: message,
                        conversation_history: conversationHistory,
                        language: currentLanguage
                    })
                });
                
                const escalateData = await escalateResponse.json();
                
                if (escalateData.success) {
                    currentChatSessionId = escalateData.session_id;
                    addMessage(escalateData.message, 'admin');
                    startPollingForMessages();
                } else {
                    throw new Error('Failed to escalate to admin');
                }
            } else {
                // Send message to existing session
                const response = await fetch('/api/live-chat/send-message', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        session_id: currentChatSessionId,
                        message: message
                    })
                });
                
                const data = await response.json();
                if (!data.success) {
                    throw new Error('Failed to send message');
                }
            }
            
            hideTyping();
            
        } catch (error) {
            hideTyping();
            const errorMsg = currentLanguage === 'en'
                ? 'Connection to admin failed. Please try again later.'
                : 'Hindi makontak ang admin. Subukan mo ulit mamaya.';
            addMessage(errorMsg, 'admin');
        }
    }
    
    function startPollingForMessages() {
        if (isPolling || !currentChatSessionId) return;
        
        isPolling = true;
        pollForNewMessages();
    }
    
    async function pollForNewMessages() {
        if (!currentChatSessionId) {
            isPolling = false;
            return;
        }
        
        try {
            const response = await fetch(`/api/live-chat/messages/${currentChatSessionId}?last_message_id=${lastMessageId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success && data.messages.length > 0) {
                data.messages.forEach(message => {
                    if (message.sender_type === 'admin' || message.sender_type === 'system') {
                        addMessage(message.message, 'admin');
                        conversationHistory.push({ role: 'admin', content: message.message });
                    }
                    lastMessageId = Math.max(lastMessageId, message.id);
                });
            }
        } catch (error) {
            console.error('Polling error:', error);
        }
        
        // Continue polling if still in admin mode
        if (currentMode === 'admin' && isPolling) {
            setTimeout(pollForNewMessages, 2000); // Poll every 2 seconds
        } else {
            isPolling = false;
        }
    }
    
    function stopPolling() {
        isPolling = false;
    }
    
    // Global functions for satisfaction handling
    window.handleSatisfaction = function(satisfied) {
        hideSatisfactionCheck();
        
        if (satisfied) {
            const thankYouMsg = currentLanguage === 'en'
                ? 'Great! I\'m glad I could help you. 😊 Is there anything else you need assistance with?'
                : 'Salamat! Natuwa ako na nakatulong ako. 😊 May iba pa bang kailangan ninyo?';
            addMessage(thankYouMsg, 'bot');
        } else {
            showEscalationPrompt();
        }
    };
    
    function showEscalationPrompt() {
        const escalationPrompt = document.getElementById('escalationPrompt');
        const escalationText = document.getElementById('escalationText');
        
        escalationText.textContent = currentLanguage === 'en'
            ? 'I understand that wasn\'t helpful. Would you like to chat directly with an admin?'
            : 'Naintindihan ko na hindi nakatulong ang sagot. Gusto ninyo bang makausap directly ang admin?';
            
        escalationPrompt.style.display = 'block';
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }
    
    window.escalateToAdmin = function() {
        hideEscalationPrompt();
        currentMode = 'admin';
        
        // Reset chat session variables
        currentChatSessionId = null;
        lastMessageId = 0;
        
        // Add escalation notice
        const escalationNotice = document.createElement('div');
        escalationNotice.className = 'escalation-notice';
        escalationNotice.innerHTML = currentLanguage === 'en'
            ? '📞 Connecting you to live admin support...'
            : '📞 Kumukonekta sa live admin support...';
        chatbotMessages.appendChild(escalationNotice);
        
        // Change window appearance
        chatbotWindow.classList.add('escalated-mode');
        chatbotTitle.textContent = currentLanguage === 'en' 
            ? 'Live Admin Support' 
            : 'Live Admin Support';
        
        // Start typing indicator
        showTyping();
        
        // Send initial message to start escalation
        setTimeout(() => {
            const initialMessage = currentLanguage === 'en' 
                ? 'I need to speak with an admin regarding my previous questions.'
                : 'Kailangan ko makausap ang admin tungkol sa mga tanong ko.';
            handleAdminChat(initialMessage);
        }, 1000);
        
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    };
    
    window.continueChatbot = function() {
        hideEscalationPrompt();
        
        // If we were in admin mode, stop polling and close session
        if (currentMode === 'admin' && currentChatSessionId) {
            stopPolling();
            // Optionally close the session on the server
            fetch(`/api/live-chat/close/${currentChatSessionId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            currentChatSessionId = null;
            lastMessageId = 0;
        }
        
        currentMode = 'bot';
        chatbotWindow.classList.remove('escalated-mode');
        chatbotTitle.textContent = currentLanguage === 'en' 
            ? 'Barangay Assistant' 
            : 'Barangay Assistant';
        
        const continueMsg = currentLanguage === 'en'
            ? 'No problem! Let me try to help you differently. 🤖<br><br>Could you please rephrase your question or tell me more specifically what you need help with?'
            : 'Walang problema! Subukan ko kayong tulungan ng ibang paraan. 🤖<br><br>Pwede ninyo bang ulit sabihin ang tanong ninyo o mas specific na sabihin kung ano ang kailangan ninyo?';
        addMessage(continueMsg, 'bot');
    };
});
</script>
@endsection