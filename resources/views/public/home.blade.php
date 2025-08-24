@extends('layouts.public.master')

@section('title', 'Barangay Lumanglipa - Official Website')

@push('styles')
<style>
    /* Hero Section (simplified clean look) */
    .hero-section {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        position: relative;
        padding: 80px 0 60px 0;
        min-height: 85vh;
        display: flex;
        align-items: center;
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
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        letter-spacing: 1px;
        line-height: 1.15;
        color: #2A7BC4;
        text-shadow: none;
    }
    
    .hero-subtitle {
        font-size: 1.05rem;
        margin-bottom: 1.25rem;
        font-weight: 500;
        letter-spacing: .5px;
        color: #555;
        text-shadow: none;
    }
    
    .hero-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-bottom: 2.5rem;
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
    
    /* Scroll Indicator */
    .scroll-indicator {
        animation: bounce 2s infinite;
    }
    
    /* New Hero Section Styles */
    .service-icon-sm {
        font-size: 1.2rem;
        width: 30px;
        text-align: center;
    }
    
    .service-item {
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }
    
    .service-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border-color: #3F9EDD;
    }
    
    .floating-stat {
        animation: float 3s ease-in-out infinite;
    }
    
    .floating-stat:nth-child(2) {
        animation-delay: 1s;
    }
    
    .floating-stat:nth-child(3) {
        animation-delay: 2s;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .scroll-arrow {
        font-size: 1.2rem;
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
        60% {
            transform: translateY(-5px);
        }
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

    /* Officials Section (Requested Tree Structure) */
    .officials-section { padding:70px 0 90px; background:#fff; }
    .officials-tree-structure { max-width:980px; margin:40px auto 0; position:relative; }
    .tree-level { display:flex; justify-content:center; gap:28px; flex-wrap:wrap; position:relative; }
    .tree-card { background:#fff; border:2px solid #e2e8f0; border-radius:16px; min-width:170px; padding:14px 14px 16px; text-align:center; position:relative; box-shadow:0 5px 18px -6px rgba(0,0,0,.14); transition:.25s; }
    .tree-card:hover { transform:translateY(-4px); box-shadow:0 12px 28px -6px rgba(0,0,0,.18); }
    .tree-card.captain { border-color:#2A7BC4; box-shadow:0 8px 28px -6px rgba(42,123,196,.4); }
    .tree-card.sk { border-style:dashed; }
    .tree-initial { width:60px; height:60px; border-radius:14px; margin:0 auto 10px; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:1.05rem; color:#2A7BC4; background:linear-gradient(135deg,#eef6ff,#e2effd); letter-spacing:.5px; }
    .tree-card.captain .tree-initial { width:70px; height:70px; font-size:1.25rem; }
    .tree-name { font-size:.78rem; font-weight:700; color:#1e293b; line-height:1.25; }
    .tree-role { font-size:.6rem; font-weight:600; color:#2563eb; letter-spacing:1px; margin-top:4px; text-transform:uppercase; }
    .connector-vertical { position:relative; width:100%; height:48px; }
    .connector-vertical:before { content:""; position:absolute; left:50%; top:0; transform:translateX(-50%); width:3px; height:100%; background:linear-gradient(#2A7BC4,#3F9EDD); border-radius:3px; }
    .kagawad-wrapper { position:relative; padding:36px 32px 10px; }
    .kagawad-wrapper:before { content:""; position:absolute; top:0; left:32px; right:32px; height:2px; background:linear-gradient(90deg,#2A7BC4,#3F9EDD); }
    .kagawad-wrapper:after { content:""; position:absolute; top:0; left:50%; transform:translate(-50%,-48px); width:3px; height:48px; background:linear-gradient(#2A7BC4,#3F9EDD); border-radius:3px; }
    .tree-level.staff { padding-top:32px; }
    .staff-branch { position:relative; }
    .staff-branch:before { content:""; position:absolute; top:-32px; left:50%; transform:translateX(-50%); width:3px; height:32px; background:linear-gradient(#2A7BC4,#3F9EDD); border-radius:3px; }
    .staff-children { display:flex; gap:26px; justify-content:center; flex-wrap:wrap; margin-top:26px; position:relative; }
    .staff-children:before { content:""; position:absolute; top:-20px; left:50%; transform:translateX(-50%); width:180px; max-width:60%; height:2px; background:linear-gradient(90deg,#2A7BC4,#3F9EDD); }
    @media (max-width:820px){ .kagawad-wrapper { padding:32px 10px 6px; } .kagawad-wrapper:before { left:10px; right:10px; } }
    @media (max-width:620px){
        .kagawad-wrapper:after, .kagawad-wrapper:before, .staff-children:before { display:none; }
        .kagawad-wrapper { padding-top:10px; }
        .connector-vertical, .staff-branch:before { display:none; }
    }
    .officials-subnote { font-size:.65rem; letter-spacing:.5px; color:#94a3b8; margin-top:18px; text-align:center; }
    @media (max-width: 576px){
        .hero-title { font-size: 2.2rem; }
        .hero-section { padding: 60px 0 30px 0; }
    }

    /* Floating Chatbot Styles */
    .chatbot-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        font-family: 'Nunito', sans-serif;
    }

    .chatbot-toggle {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #3F9EDD, #2A7BC4);
        border-radius: 50%;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(63, 158, 221, 0.4);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .chatbot-toggle:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 25px rgba(63, 158, 221, 0.6);
    }

    .chatbot-pulse {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: rgba(63, 158, 221, 0.3);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        100% {
            transform: scale(1.4);
            opacity: 0;
        }
    }

    .chatbot-window {
        position: absolute;
        bottom: 80px;
        right: 0;
        width: 350px;
        height: 500px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        display: none;
        flex-direction: column;
        overflow: hidden;
        transform: translateY(20px);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .chatbot-window.active {
        display: flex;
        transform: translateY(0);
        opacity: 1;
    }

    .chatbot-header {
        background: linear-gradient(135deg, #3F9EDD, #2A7BC4);
        color: white;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chatbot-header h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
    }

    .chatbot-close {
        background: none;
        border: none;
        color: white;
        font-size: 18px;
        cursor: pointer;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background 0.3s ease;
    }

    .chatbot-close:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .chatbot-messages {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        max-height: 350px;
    }

    .message {
        margin-bottom: 15px;
        display: flex;
        align-items: flex-start;
    }

    .message.user {
        flex-direction: row-reverse;
    }

    .message.bot .message-content {
        background: #f1f3f5;
        color: #333;
        border-radius: 18px 18px 18px 5px;
    }

    .message.user .message-content {
        background: linear-gradient(135deg, #3F9EDD, #2A7BC4);
        color: white;
        border-radius: 18px 18px 5px 18px;
    }

    .message-content {
        max-width: 80%;
        padding: 12px 16px;
        font-size: 14px;
        line-height: 1.4;
        word-wrap: break-word;
    }

    .message-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        margin: 0 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .message.bot .message-avatar {
        background: linear-gradient(135deg, #3F9EDD, #2A7BC4);
        color: white;
    }

    .message.user .message-avatar {
        background: #e9ecef;
        color: #495057;
    }

    .chatbot-input-area {
        padding: 15px 20px;
        border-top: 1px solid #e9ecef;
        display: flex;
        gap: 10px;
    }

    .chatbot-input {
        flex: 1;
        border: 1px solid #ddd;
        border-radius: 20px;
        padding: 10px 15px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.3s ease;
    }

    .chatbot-input:focus {
        border-color: #3F9EDD;
    }

    .chatbot-send {
        background: linear-gradient(135deg, #3F9EDD, #2A7BC4);
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.3s ease;
    }

    .chatbot-send:hover {
        transform: scale(1.1);
    }

    .chatbot-send:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .typing-indicator {
        display: flex;
        align-items: center;
        padding: 10px 16px;
        background: #f1f3f5;
        border-radius: 18px 18px 18px 5px;
        margin-left: 40px;
    }

    .typing-dots {
        display: flex;
        gap: 4px;
    }

    .typing-dot {
        width: 8px;
        height: 8px;
        background: #999;
        border-radius: 50%;
        animation: typing 1.4s infinite ease-in-out;
    }

    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }

    @keyframes typing {
        0%, 80%, 100% {
            transform: scale(0);
            opacity: 0.5;
        }
        40% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .quick-action-btn {
        background: transparent;
        border: 1px solid #3F9EDD;
        color: #3F9EDD;
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        user-select: none;
        outline: none;
        display: inline-block;
        text-decoration: none;
        font-family: inherit;
        font-weight: 500;
        line-height: 1.2;
        white-space: nowrap;
        min-height: 32px;
        vertical-align: middle;
    }

    .quick-action-btn:hover {
        background: #3F9EDD;
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(63, 158, 221, 0.3);
    }

    .quick-action-btn:active {
        transform: translateY(0);
        box-shadow: 0 1px 4px rgba(63, 158, 221, 0.3);
    }

    .quick-action-btn:focus {
        outline: 2px solid #3F9EDD;
        outline-offset: 2px;
    }

    @media (max-width: 768px) {
        .chatbot-window {
            width: 320px;
            height: 450px;
        }
        
        .chatbot-container {
            bottom: 15px;
            right: 15px;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="hero-content text-start">
                    <h1 class="hero-title mb-2" style="color:#1e293b; font-size: 2.8rem; font-weight: 800; line-height: 1.1;">
                        Barangay Lumanglipa
                    </h1>
                    <p class="hero-location mb-3" style="color:#3F9EDD; font-size: 0.95rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px;">
                        📍 Mataas na Kahoy, Batangas
                    </p>
                    <p class="hero-description mb-4" style="font-size: 1.1rem; line-height: 1.6; color: #64748b; font-weight: 400;">
                        Your digital gateway to efficient government services. Experience seamless online transactions and stay connected with your community.
                    </p>
                    
                    <!-- Services Grid -->
                    <div class="services-preview mb-4">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="service-item p-3 bg-light rounded-3 h-100">
                                    <div class="d-flex align-items-center">
                                        <div class="service-icon-sm me-2">📄</div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 0.85rem;">Document Requests</div>
                                            <div class="text-muted" style="font-size: 0.7rem;">Clearance, Residency & More</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="service-item p-3 bg-light rounded-3 h-100">
                                    <div class="d-flex align-items-center">
                                        <div class="service-icon-sm me-2">🏥</div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 0.85rem;">Health Services</div>
                                            <div class="text-muted" style="font-size: 0.7rem;">Medical Assistance</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="service-item p-3 bg-light rounded-3 h-100">
                                    <div class="d-flex align-items-center">
                                        <div class="service-icon-sm me-2">📝</div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 0.85rem;">File Complaints</div>
                                            <div class="text-muted" style="font-size: 0.7rem;">24/7 Online System</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="service-item p-3 bg-light rounded-3 h-100">
                                    <div class="d-flex align-items-center">
                                        <div class="service-icon-sm me-2">🆔</div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 0.85rem;">Resident Registration</div>
                                            <div class="text-muted" style="font-size: 0.7rem;">Barangay ID & More</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image-container position-relative">
                    <div class="main-image" style="border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.1);">
                        <img src="{{ asset('images/bglumanglipa.jpeg') }}" alt="Barangay Hall" style="width:100%; height:350px; object-fit:cover;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
</section>

<!-- Recent Announcements Section (Redesigned) -->
<section class="py-4" style="background:#f8fafc; border-top: 3px solid #e5e7eb;">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 gap-2">
            <div>
                <h2 class="fw-bold mb-1" style="color:#2A7BC4; font-size: 2rem;">Recent Announcements</h2>
                <p class="text-muted mb-0" style="font-size:.9rem;">Stay updated with the latest barangay news and events</p>
            </div>
            <a href="#" class="btn btn-outline-primary btn-sm" style="border-radius:8px;">View All</a>
        </div>
        @php
            $feature = [
                'title' => 'Scheduled Power Interruption Advisory',
                'excerpt' => 'Magkakaroon ng pansamantalang pagkawala ng kuryente sa ilang purok para sa line maintenance ng BATELEC II.',
                'date' => 'Feb 18, 2025',
                'category' => 'Advisory',
                'cta' => 'Read Advisory',
                'image' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=800&q=60'
            ];
            $announcements = [
                [
                    'title' => 'Free Medical & Dental Mission',
                    'excerpt' => 'Libreng konsultasyon, basic dental care at BP monitoring. First come, first served.',
                    'date' => 'Feb 22, 2025',
                    'category' => 'Health',
                    'image' => 'https://images.unsplash.com/photo-1580281658629-149f02f32b25?auto=format&fit=crop&w=800&q=60'
                ],
                [
                    'title' => 'Linggo ng Kabataan Sports Clinic',
                    'excerpt' => 'Open registration para sa basketball, volleyball at chess training sessions.',
                    'date' => 'Feb 20, 2025',
                    'category' => 'Youth',
                    'image' => 'https://images.unsplash.com/photo-1521412644187-c49fa049e84d?auto=format&fit=crop&w=800&q=60'
                ],
                [
                    'title' => 'Barangay Clean-Up & Waste Segregation Drive',
                    'excerpt' => 'Dalhin ang sariling gloves at supot. Focus: drainage clearing & plastic recovery.',
                    'date' => 'Feb 19, 2025',
                    'category' => 'Environment',
                    'image' => 'https://images.unsplash.com/photo-1503596476-1c12a8ba09a8?auto=format&fit=crop&w=800&q=60'
                ],
                [
                    'title' => 'Monthly Barangay Assembly',
                    'excerpt' => 'Pag-uusapan ang proposed livelihood projects at infra status reports.',
                    'date' => 'Feb 25, 2025',
                    'category' => 'Assembly',
                    'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=60'
                ],
            ];
        @endphp
        <div class="row g-4">
            <!-- Feature Card -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100" style="border-radius:22px; overflow:hidden; background:#ffffff;">
                    <div style="position:relative;">
                        <img src="{{ $feature['image'] }}" alt="Feature" style="width:100%; height:240px; object-fit:cover;">
                        <span class="badge bg-danger" style="position:absolute; top:14px; left:14px;">{{ $feature['category'] }}</span>
                        <span style="position:absolute; bottom:14px; left:14px; background:rgba(0,0,0,.55); color:#fff; padding:4px 12px; border-radius:20px; font-size:.7rem; letter-spacing:.5px;">FEATURED</span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h5 class="fw-bold" style="line-height:1.25; color:#1e293b;">{{ $feature['title'] }}</h5>
                        <p class="text-muted small flex-grow-1 mb-3" style="line-height:1.5;">{{ $feature['excerpt'] }}</p>
                        <div class="d-flex align-items-center justify-content-between mt-auto pt-2">
                            <span class="text-muted small"><i class="far fa-calendar-alt me-1"></i>{{ $feature['date'] }}</span>
                            <a href="#" class="btn btn-sm btn-primary" style="border-radius:8px; font-weight:600; font-size:.65rem; letter-spacing:.5px;">{{ strtoupper($feature['cta']) }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- List / Grid of other announcements -->
            <div class="col-lg-7">
                <div class="row g-4">
                    @foreach($announcements as $a)
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100" style="border-radius:18px; overflow:hidden; background:#ffffff;">
                                <div style="position:relative;">
                                    <img src="{{ $a['image'] }}" alt="{{ $a['category'] }}" style="width:100%; height:140px; object-fit:cover;">
                                    <span class="badge bg-secondary" style="position:absolute; top:10px; left:10px; background:#2A7BC4;">{{ $a['category'] }}</span>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h6 class="fw-semibold mb-2" style="line-height:1.25; color:#1e293b;">{{ $a['title'] }}</h6>
                                    <p class="text-muted small flex-grow-1 mb-2" style="line-height:1.4;">{{ $a['excerpt'] }}</p>
                                    <div class="d-flex align-items-center justify-content-between mt-auto pt-1">
                                        <span class="text-muted small"><i class="far fa-clock me-1"></i>{{ $a['date'] }}</span>
                                        <a href="#" class="text-primary small fw-semibold text-decoration-none" style="letter-spacing:.3px;">Details →</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Officials Section -->
<section class="officials-section">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-3 gap-2">
            <div>
                <h2 class="fw-bold mb-1" style="color:#2A7BC4;">Barangay Officials</h2>
                
            </div>
            <a href="{{ route('public.about') }}" class="btn btn-outline-primary btn-sm" style="border-radius:8px;">Tingnan Pa</a>
        </div>
        @php
            $captain = ['HON. NOVILITO M. MANALO' => 'Barangay Captain'];
            $kagawad = [
                'HON. ROLDAN A. ROSITA',
                'HON. LEXTER D. MAQUINTO',
                'HON. RICHARD C. CANOSA',
                'HON. RODOLFO U. MANALO JR',
                'HON. ROSENDO T. BABADILLA',
                'HON. JAIME C. LAQUI',
                'HON. RECHEL R. CIRUELAS',
            ];
            $sk = ['HON. JOHN MARCO C. ARRIOLA' => 'SK Chairman'];
            $staff = ['APRIL JANE J. SISCAR' => 'Secretary', 'JOSEPHINE R. QUISTO' => 'Treasurer'];
            $makeInitials = function($full){ $parts = preg_split('/\s+/', preg_replace('/[^A-Z\s]/','',$full)); return collect($parts)->filter()->map(fn($p)=> substr($p,0,1))->take(3)->implode(''); };
        @endphp
        <div class="officials-tree-structure">
            <!-- Captain -->
            <div class="tree-level">
                @foreach($captain as $n=>$r)
                    <div class="tree-card captain">
                        <div class="tree-initial">{{ $makeInitials($n) }}</div>
                        <div class="tree-name">{{ $n }}</div>
                        <div class="tree-role">{{ strtoupper($r) }}</div>
                    </div>
                @endforeach
            </div>
            <div class="connector-vertical"></div>
            <!-- Kagawad Group (7) -->
            <div class="kagawad-wrapper">
                <div class="tree-level">
                    @foreach($kagawad as $n)
                        <div class="tree-card">
                            <div class="tree-initial">{{ $makeInitials($n) }}</div>
                            <div class="tree-name">{{ $n }}</div>
                            <div class="tree-role">COUNCILOR</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <!-- SK Chairman Branch -->
            <div class="connector-vertical"></div>
            <div class="tree-level">
                @foreach($sk as $n=>$r)
                    <div class="tree-card sk staff-branch">
                        <div class="tree-initial">{{ $makeInitials($n) }}</div>
                        <div class="tree-name">{{ $n }}</div>
                        <div class="tree-role">{{ strtoupper($r) }}</div>
                        <!-- Staff children -->
                        <div class="staff-children">
                            @foreach($staff as $sn=>$sr)
                                <div class="tree-card" style="min-width:150px;">
                                    <div class="tree-initial" style="width:54px;height:54px;font-size:.9rem;">{{ $makeInitials($sn) }}</div>
                                    <div class="tree-name">{{ $sn }}</div>
                                    <div class="tree-role">{{ strtoupper($sr) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            
        </div>
    </div>
</section>




<!-- Floating Chatbot -->
<div class="chatbot-container">
    <div class="chatbot-window" id="chatbotWindow">
        <div class="chatbot-header">
            <h4><i class="fas fa-robot me-2"></i>Barangay Assistant</h4>
            <button class="chatbot-close" id="chatbotClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="chatbot-messages" id="chatbotMessages">
            <div class="message bot">
                <div class="message-avatar">
                    <i class="fas fa-robot"></i>
                </div>
                <div class="message-content">
                    Hello! I'm your Barangay Lumanglipa assistant. I can help you with information about our services, document requests, health programs, and other barangay concerns. How can I assist you today?
                    <div class="quick-actions">
                        <button class="quick-action-btn" onclick="sendQuickMessage('Document request')">Document Request</button>
                        <button class="quick-action-btn" onclick="sendQuickMessage('Health services')">Health Services</button>
                        <button class="quick-action-btn" onclick="sendQuickMessage('Contact information')">Contact Info</button>
                        <button class="quick-action-btn" onclick="sendQuickMessage('Office hours')">Office Hours</button>
                        <button class="quick-action-btn" onclick="sendQuickMessage('File Complaint')">File Complaint</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="chatbot-input-area">
            <input type="text" class="chatbot-input" id="chatbotInput" placeholder="Type your message..." maxlength="500">
            <button class="chatbot-send" id="chatbotSend">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
    <button class="chatbot-toggle" id="chatbotToggle">
        <div class="chatbot-pulse"></div>
        <i class="fas fa-comments"></i>
    </button>
</div>

@push('scripts')
<script>
class BarangayChatbot {
    constructor() {
        this.isOpen = false;
        this.isTyping = false;
        this.init();
        this.knowledgeBase = this.initKnowledgeBase();
    }

    init() {
        this.toggle = document.getElementById('chatbotToggle');
        this.window = document.getElementById('chatbotWindow');
        this.close = document.getElementById('chatbotClose');
        this.input = document.getElementById('chatbotInput');
        this.send = document.getElementById('chatbotSend');
        this.messages = document.getElementById('chatbotMessages');

        this.bindEvents();
    }

    bindEvents() {
        this.toggle.addEventListener('click', () => this.toggleChat());
        this.close.addEventListener('click', () => this.closeChat());
        this.send.addEventListener('click', () => this.sendMessage());
        this.input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        });
    }

    toggleChat() {
        if (this.isOpen) {
            this.closeChat();
        } else {
            this.openChat();
        }
    }

    openChat() {
        this.window.classList.add('active');
        this.isOpen = true;
        this.input.focus();
        
        // Hide pulse animation when opened
        const pulse = this.toggle.querySelector('.chatbot-pulse');
        if (pulse) pulse.style.display = 'none';

        // If strict mode and no AI key, show unavailable message once
        const body = document.body;
        const strict = body.getAttribute('data-chatbot-strict') === '1';
        const hasKey = body.getAttribute('data-chatbot-has-key') === '1';
        if (strict && !hasKey && !this._notifiedNoAI) {
            this.addMessage('AI is unavailable right now. Please try again later or contact the barangay.', 'bot');
            this._notifiedNoAI = true;
        }
    }

    closeChat() {
        this.window.classList.remove('active');
        this.isOpen = false;
        
        // Show pulse animation when closed
        const pulse = this.toggle.querySelector('.chatbot-pulse');
        if (pulse) pulse.style.display = 'block';
    }

    sendMessage() {
        const message = this.input.value.trim();
        if (!message || this.isTyping) return;

        const body = document.body;
        const strict = body.getAttribute('data-chatbot-strict') === '1';
        const hasKey = body.getAttribute('data-chatbot-has-key') === '1';
        if (strict && !hasKey) {
            this.addMessage('AI is unavailable right now. Please try again later or contact the barangay.', 'bot');
            return;
        }

        this.addMessage(message, 'user');
        this.input.value = '';
        this.send.disabled = true;
        
        this.showTyping();
        // Try real AI via backend first; on failure, fall back to local rule-based responses
        this.askAI(message)
            .then(aiText => {
                this.hideTyping();
                if (aiText) {
                    // Check if backend wants to trigger frontend service options
                    if (aiText.startsWith('TRIGGER_SERVICE_OPTIONS:')) {
                        const serviceType = aiText.replace('TRIGGER_SERVICE_OPTIONS:', '');
                        this.addMessage(this.getServiceOptions(serviceType), 'bot');
                    } else if (aiText === 'TRIGGER_BARANGAY_ID_OPTIONS') {
                        // Show specific Barangay ID options
                        const barangayIdResponse = `What would you like to know about Barangay ID?

<div class="quick-actions" style="margin-top: 15px;">
    <button class="quick-action-btn" data-action="what-is-barangay-id">❓ What is Barangay ID?</button>
    <button class="quick-action-btn" data-action="get-barangay-id">📋 Get Barangay ID</button>
</div>`;
                        this.addMessage(barangayIdResponse, 'bot');
                    } else {
                        this.addMessage(aiText, 'bot');
                    }
                } else {
                    this.processMessage(message);
                }
            })
            .catch((err) => {
                this.hideTyping();
                
                // Handle different error types with appropriate messages
                if (err && err.message === 'strict_mode_active') {
                    this.addMessage('AI is unavailable right now. Please try again later or contact the barangay.', 'bot');
                } else if (err && err.message && err.message.includes('AI service')) {
                    this.addMessage(err.message, 'bot');
                } else if (err && err.message === 'I can only help with barangay-related questions.') {
                    this.addMessage('I can only help with barangay-related questions. Please ask about documents, office hours, complaints, or other barangay services.', 'bot');
                } else if (err && err.message && err.message.length > 10) {
                    // If we have a meaningful error message from the server
                    this.addMessage(err.message, 'bot');
                } else {
                    // Fallback to local processing for other errors
                    this.processMessage(message);
                }
            })
            .finally(() => {
                this.send.disabled = false;
            });
    }

    // Call backend Hugging Face proxy
    async askAI(message) {
        try {
            const payload = {
                message: message,
                language: this.detectLanguage(message),
                context: 'public'
            };
            
            const res = await fetch('/api/chatbot/chat', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(payload)
            });
            
            // Always try to parse JSON response
            let data = null;
            try {
                data = await res.json();
            } catch (parseError) {
                console.error('Failed to parse response:', parseError);
                throw new Error('Invalid response format');
            }
            
            if (res.ok && data && data.success && data.response) {
                return data.response;
            }
            
            // Handle error responses with proper messages
            if (data && data.strict) {
                throw new Error('strict_mode_active');
            }
            
            if (data && data.message) {
                throw new Error(data.message);
            }
            
            // Handle HTTP error status
            if (!res.ok) {
                const errorMsg = data?.error || `Server error (${res.status})`;
                throw new Error(errorMsg);
            }
            
            return '';
        } catch (e) {
            console.error('AI request failed:', e.message);
            throw e;
        }
    }

    detectLanguage(text) {
        const t = (text || '').toLowerCase();
        const filipinoHints = ['po', 'opo', 'barangay', 'dokumento', 'serbisyo', 'oras', 'reklamo', 'saan', 'nasaan'];
        return filipinoHints.some(w => t.includes(w)) ? 'tl' : 'en';
    }

    addMessage(content, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;
        
        const avatar = document.createElement('div');
        avatar.className = 'message-avatar';
        avatar.innerHTML = sender === 'bot' ? '<i class="fas fa-robot"></i>' : '<i class="fas fa-user"></i>';
        
        const messageContent = document.createElement('div');
        messageContent.className = 'message-content';
        messageContent.innerHTML = content;
        
        messageDiv.appendChild(avatar);
        messageDiv.appendChild(messageContent);
        
        this.messages.appendChild(messageDiv);
        
        // Add event listeners to any buttons in the message
        this.attachButtonListeners(messageDiv);
        
        this.scrollToBottom();
    }

    attachButtonListeners(messageElement) {
        const buttons = messageElement.querySelectorAll('.quick-action-btn');
        buttons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                // Block quick actions if strict mode and no AI key
                const body = document.body;
                const strict = body.getAttribute('data-chatbot-strict') === '1';
                const hasKey = body.getAttribute('data-chatbot-has-key') === '1';
                if (strict && !hasKey) {
                    this.addMessage('AI is unavailable right now. Please try again later or contact the barangay.', 'bot');
                    return;
                }
                
                const buttonText = button.textContent.trim();
                
                // Add user message for the button clicked
                this.addMessage(buttonText, 'user');
                
                // Handle service choice buttons
                const service = button.getAttribute('data-service');
                const method = button.getAttribute('data-method');
                const action = button.getAttribute('data-action');
                
                if (service && method) {
                    this.showTyping();
                    setTimeout(() => {
                        this.hideTyping();
                        const response = this.handleServiceChoice(service, method);
                        this.addMessage(response, 'bot');
                    }, 1000);
                    return;
                }

                // Handle specific actions
                if (action) {
                    this.showTyping();
                    setTimeout(() => {
                        this.hideTyping();
                        let response = '';
                        switch(action) {
                            case 'barangay-id':
                                response = this.generateResponse('barangay id requirements');
                                break;
                            case 'what-is-barangay-id':
                                response = this.getBarangayIdInfo();
                                break;
                            case 'get-barangay-id':
                                response = this.getBarangayIdOptions();
                                break;
                            case 'document-types':
                                response = this.getDocumentTypesInfo();
                                break;
                            case 'emergency':
                                response = this.generateResponse('emergency');
                                break;
                            case 'complaint-types':
                                response = this.generateResponse('complaint types');
                                break;
                            case 'requirements':
                                response = this.getGeneralRequirements();
                                break;
                            default:
                                response = this.generateResponse(action);
                        }
                        this.addMessage(response, 'bot');
                    }, 1000);
                    return;
                }
                
                // Handle other button actions
                const onclickAttr = button.getAttribute('onclick');
                if (onclickAttr) {
                    try {
                        if (onclickAttr.includes('window.open')) {
                            const urlMatch = onclickAttr.match(/window\.open\('([^']+)'/);
                            if (urlMatch) {
                                window.open(urlMatch[1], '_blank');
                                this.addMessage('Opening the document request system for you! You can now proceed with your online application.', 'bot');
                            }
                        } else if (onclickAttr.includes('addMessage')) {
                            const messageMatch = onclickAttr.match(/addMessage\('([^']+)',\s*'user'\)/);
                            const processMatch = onclickAttr.match(/processMessage\('([^']+)'\)/);
                            if (messageMatch && processMatch) {
                                const processMsg = processMatch[1];
                                this.showTyping();
                                setTimeout(() => {
                                    this.hideTyping();
                                    this.processMessage(processMsg);
                                }, 1000);
                            }
                        } else if (onclickAttr.includes('sendQuickMessage')) {
                            const messageMatch = onclickAttr.match(/sendQuickMessage\('([^']+)'\)/);
                            if (messageMatch) {
                                const message = messageMatch[1];
                                this.showTyping();
                                setTimeout(() => {
                                    this.hideTyping();
                                    this.processMessage(message.toLowerCase());
                                }, 1000);
                            }
                        }
                    } catch (error) {
                        console.error('Error executing button action:', error);
                        this.addMessage('Sorry, there was an error processing your request. Please try again or contact our office directly.', 'bot');
                    }
                }
            });
        });
    }

    getGeneralRequirements() {
        return `📋 **General Requirements for All Documents:**

**Basic Requirements:**
• Valid Barangay ID
• Government-issued ID (Driver's License, Passport, etc.)
• Cedula (Community Tax Certificate)
• Proof of residency (Utility bill, lease contract)

**For Specific Documents:**
• **Indigency:** Income certification, Medical needs
• **Business:** Business plan, Location clearance
• **Good Moral:** Character references

**Important Notes:**
• All documents must be clear copies
• Photos/scans should be readable
• Bring originals for verification

<div class="quick-actions" style="margin-top: 15px;">
    <button class="quick-action-btn" onclick="window.open('${window.location.origin}/request-document', '_blank')">📋 Apply Now</button>
</div>`;
    }

    getBarangayIdInfo() {
        return `🆔 What is Barangay ID?

        <br><br>    

Barangay ID is your official identification as a resident of Barangay Lumanglipa.

<br><br>

Purpose:
<br> <br>
• Proof of residency in Lumanglipa Barangay
<br>
• Required for all barangay services and concerns
<br>
• Access to documents, health services, and complaints

<div class="quick-actions" style="margin-top: 15px;">
    <button class="quick-action-btn" data-action="get-barangay-id">📋 Get Barangay ID</button>
</div>`;
    }

    getBarangayIdOptions() {
        return `🆔 Get Barangay ID
        <br>

How would you like to get your Barangay ID?

<div class="quick-actions" style="margin-top: 15px;">
    <button class="quick-action-btn" data-service="barangay-id" data-method="online">📱 Online Application</button>
    <button class="quick-action-btn" data-service="barangay-id" data-method="walkin">🚶 Walk-in Application</button>
</div>`;
    }

    showTyping() {
        this.isTyping = true;
        const typingDiv = document.createElement('div');
        typingDiv.className = 'typing-indicator';
        typingDiv.id = 'typingIndicator';
        typingDiv.innerHTML = `
            <div class="typing-dots">
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
                <div class="typing-dot"></div>
            </div>
        `;
        this.messages.appendChild(typingDiv);
        this.scrollToBottom();
    }

    hideTyping() {
        this.isTyping = false;
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }

    processMessage(message) {
        const response = this.generateResponse(message.toLowerCase());
        this.addMessage(response, 'bot');
    }

    generateResponse(message) {
        // Check for greetings
        if (message.match(/^(hi|hello|hey|good morning|good afternoon|good evening)/i)) {
            return "Hello! Welcome to Barangay Lumanglipa. I'm here to help you with information about our services. What would you like to know about?";
        }

        // Check knowledge base
        for (const [keywords, response] of this.knowledgeBase) {
            if (keywords.some(keyword => message.includes(keyword))) {
                return response;
            }
        }

        // Check if inquiry is related to barangay services
        const barangayRelatedKeywords = [
            'barangay', 'document', 'clearance', 'certificate', 'residency', 'indigency',
            'health', 'medical', 'clinic', 'doctor', 'medicine', 'complaint', 'problem',
            'issue', 'concern', 'report', 'captain', 'councilor', 'official', 'office',
            'hours', 'schedule', 'contact', 'address', 'location', 'phone', 'email',
            'registration', 'register', 'resident', 'id', 'identification', 'service',
            'assistance', 'help', 'application', 'request', 'file', 'submit', 'process',
            'emergency', 'urgent', 'mediation', 'conciliation', 'dispute', 'lumanglipa',
            'mataas na kahoy', 'batangas', 'purok', 'secretary', 'requirements'
        ];

        const isBarangayRelated = barangayRelatedKeywords.some(keyword => 
            message.toLowerCase().includes(keyword.toLowerCase())
        );

        if (!isBarangayRelated) {
            return `Thank you for reaching out! 😊
<br><br>
Unfortunately, the inquiry you've made is outside the scope of our current services.
<br><br>
If there's anything else we can help you with or if you have questions related to our services offerings, feel free to ask!
<br><br>
We're here to assist you the best we can.`;
        }

        // Default response for barangay-related but unmatched queries
        return `I understand you're asking about "${message}". For specific inquiries about barangay services, you can:
        
        📞 Call us at (043) 123-4567
        📧 Email: info@lumanglipa.gov.ph
        🏢 Visit our office: Mon-Fri, 8:00 AM - 5:00 PM
        
        Is there anything else about our standard services I can help you with?`;
    }

    // Enhanced method to handle service choices
    handleServiceChoice(service, method) {
        if (method === 'online') {
            return this.getOnlineGuide(service);
        } else if (method === 'walkin') {
            return this.getWalkInGuide(service);
        }
        return this.getServiceOptions(service);
    }

    getServiceOptions(service) {
        const serviceKey = service.toLowerCase().replace(' services', '').replace(' filing', '').replace('document services', 'document').trim();
        
        return `How would you like to proceed with <strong>${service}</strong>?
        
        <div class="quick-actions" style="margin-top: 15px;">
            <button class="quick-action-btn" data-service="${serviceKey}" data-method="online">📱 Online Service</button>
            <button class="quick-action-btn" data-service="${serviceKey}" data-method="walkin">🚶 Walk-in Service</button>
        </div>`;
    }

    getOnlineGuide(service) {
        const guides = {
            'document': `Online Document Request
            <br><br>

What you need:
<br><br>

• Barangay ID
<br><br>

1-3 Business Days for Processing
<br><br>



<div class="quick-actions" style="margin-top: 15px;">
    <button class="quick-action-btn" onclick="window.open('${window.location.origin}/request-document', '_blank')">🚀 Request Document Now</button>
    <button class="quick-action-btn" data-action="barangay-id">❓ Barangay ID</button>
</div>`,

            'barangay-id': `📱 Online Barangay ID Application
            <br><br>

What you need:
<br><br>


• Any ID
<br>
• Proof of Residency
<br><br>

Processing: 1-3 business days after admin approval
<br><br>

Click "Register Now" to submit your pre-application

<div class="quick-actions" style="margin-top: 15px;">
    <button class="quick-action-btn" onclick="window.open('/pre-registration', '_blank')">📝 Register Now</button>
    <button class="quick-action-btn" data-action="what-is-barangay-id">❓ What is Barangay ID?</button>
</div>`,

            'health': `Online Health Services
            <br><br>

What you need:
<br><br>

• Barangay ID
<br><br>

Available Services: Medical consultation, Health certificates, BP monitoring
<br><br>

<div class="quick-actions" style="margin-top: 15px;">
    <button class="quick-action-btn" onclick="window.open('${window.location.origin}/health/request', '_blank')">🩺 Request Health Service</button>
    <button class="quick-action-btn" onclick="chatbot.addMessage('How to get Barangay ID?', 'user'); chatbot.processMessage('barangay id requirements')">🆔 Barangay ID</button>
</div>`,

            'complaint': `Online Complaint Filing
            <br><br>

What you need:
<br><br>

• Barangay ID
<br><br>

1-3 Business Days for Processing

<br><br>

<div class="quick-actions" style="margin-top: 15px;">
    <button class="quick-action-btn" onclick="window.open('${window.location.origin}/complaints/file', '_blank')">📋 File Complaint Now</button>
    <button class="quick-action-btn" onclick="chatbot.addMessage('How to get Barangay ID?', 'user'); chatbot.processMessage('barangay id requirements')">🆔 Barangay ID</button>
</div>`
        };

        return guides[service] || this.getServiceOptions(service);
    }

    getWalkInGuide(service) {
        const guides = {
            'document': `🚶 Walk-in Document Request

            <br>
            <br>

Location: Barangay Hall of Lumanglipa, located in Purok 1

<br>
<br>

What to Bring:
<br>
• ✅ Barangay ID (or any valid ID if you don't have Barangay ID yet)

<br>
<br>
Process:

1. Go to Barangay Hall in Purok 1
<br>
2. Find the Secretary
<br>
3. Tell them what document you need
<br>
4. Fill up the form
<br>
5. Pay ₱50 for the document
<br>
6. Get your document same day
<br>
<br>

Office Hours: Mon-Fri 8AM-5PM, Sat 8AM-12PM
<br>
Processing: Same day

<div class="quick-actions" style="margin-top: 15px;">
    <button class="quick-action-btn" onclick="chatbot.addMessage('office location', 'user'); chatbot.processMessage('contact location')">📍 Office Location</button>
    <button class="quick-action-btn" onclick="chatbot.addMessage('How to get Barangay ID?', 'user'); chatbot.processMessage('barangay id requirements')">🆔 Barangay ID</button>
</div>`,

            'health': `🚶 Walk-in Health Services

            <br>
            <br>

Location: Barangay Hall, Purok 1

<br>
<br>

What to Bring:
<br>
• ✅ Barangay ID (or any valid ID if you don't have Barangay ID yet)

<br>
<br>
Process:

1. Go to Health Center in Purok 1
<br>
2. Tell the barangay health workers what service you need

<br>
<br>

Office Hours: Mon-Fri 8AM-5PM, Sat 8AM-12PM

<div class="quick-actions" style="margin-top: 15px;">
    <button class="quick-action-btn" onclick="chatbot.addMessage('How to get Barangay ID?', 'user'); chatbot.processMessage('barangay id requirements')">🆔 Barangay ID</button>
</div>`,

            'complaint': `� Walk-in Complaint Filing

            <br>
            <br>

Location: Barangay Hall of Lumanglipa, Purok 1

<br>
<br>

What to Bring:
<br>
• ✅ Barangay ID (or any valid ID if you don't have Barangay ID yet)

<br>
<br>
Process:

1. Go to Barangay Hall in Purok 1
<br>
2. Find the secretary or Barangay Captain
<br>
3. Explain your complaint
<br>
4. Fill up the complaint form
<br>
5. Submit any evidence you have
<br>
<br>

Office Hours: Mon-Fri 8AM-5PM, Sat 8AM-12PM
<br>
Response: 1-3 business days

<div class="quick-actions" style="margin-top: 15px;">
    <button class="quick-action-btn" onclick="chatbot.addMessage('How to get Barangay ID?', 'user'); chatbot.processMessage('barangay id requirements')">🆔 Barangay ID</button>
</div>`,

            'barangay-id': `🚶 Walk-in Barangay ID Application
<br>
<br>

What to Bring:
<br>

• ✅ Any ID  
<br>
• ✅ Proof of Residency 

<br>
<br>

Process:
<br>
<br>
1. Go to secretary and ask for registration of barangay ID
<br>
2. Submit the ID and proof of residency
<br>

3. Wait for the processing of your barangay ID


<br>
<br>
Office Hours: Mon-Fri 8AM-5PM, Sat 8AM-12PM

<br>
<br>



<div class="quick-actions" style="margin-top: 15px;">
    <button class="quick-action-btn" onclick="chatbot.addMessage('office location', 'user'); chatbot.processMessage('contact location')">📍 Office Location</button>
    <button class="quick-action-btn" data-action="what-is-barangay-id">❓ What is Barangay ID?</button>
</div>`
        };

        return guides[service] || this.getServiceOptions(service);
    }

    initKnowledgeBase() {
        return new Map([
            [['document', 'document request', 'documents', 'clearance', 'certificate', 'residency', 'indigency', 'request', 'filing', 'services', 'service', 'dokumento', 'documento', 'sertipiko', 'clearanse', 'patunay', 'papeles', 'papel', 'barangay clearance', 'certificate of residency'], 
             this.getServiceOptions('Document Services')],
            
            [['health', 'health services', 'medical', 'clinic', 'medicine', 'doctor', 'kalusugan', 'medisina', 'doktor'],
             this.getServiceOptions('Health Services')],
            
            [['complaint', 'file complaint', 'problem', 'issue', 'concern', 'report', 'reklamo', 'problema', 'hinaing'],
             this.getServiceOptions('Complaint Filing')],

            [['barangay id', 'id card', 'resident id', 'identification'],
             `

What would you like to know about Barangay ID?

<div class="quick-actions" style="margin-top: 15px;">
    <button class="quick-action-btn" data-action="what-is-barangay-id">❓ What is Barangay ID?</button>
    <button class="quick-action-btn" data-action="get-barangay-id">📋 Get Barangay ID</button>
</div>`],

            [['lost id', 'replace id', 'id replacement'],
             `🔄 **Lost/Damaged Barangay ID Replacement:**
             
             **Requirements:**
             • Affidavit of Loss (if lost)
             • Valid government ID
             • 2x2 ID picture (2 pieces)
             • Police report (for lost ID)
             • Replacement fee: ₱150
             
             **Processing Time:** 3-5 business days
             **Temporary ID:** Available for urgent needs (₱20)
             
             <div class="quick-actions" style="margin-top: 15px;">
                 <button class="quick-action-btn" onclick="chatbot.addMessage('temporary id', 'user'); chatbot.processMessage('temporary id')">⚡ Temporary ID Info</button>
             </div>`],

            [['emergency', 'urgent', '911', 'emergency contact'],
             `🚨 **Emergency Contacts:**
             
             **Barangay Emergency Hotline:** (043) 123-4567
             **Police:** 117 or (043) 456-7890
             **Fire Department:** 116 or (043) 456-7891
             **Medical Emergency:** 911 or (043) 456-7892
             
             **Barangay Emergency Response:**
             • Available 24/7
             • First aid response
             • Disaster coordination
             • Security concerns
             
             **For non-life threatening health issues:**
             Walk-in to Barangay Health Center during office hours.`],

            [['track complaint', 'complaint status', 'follow up'],
             `📊 **Track Your Complaint:**
             
             **Online Tracking:**
             • Use your tracking number at our complaint portal
             • Receive SMS/email updates automatically
             
             **Walk-in Inquiry:**
             • Bring your claim stub to the office
             • Ask for status update at Complaints desk
             
             **Response Timeline:**
             • Acknowledgment: Within 24 hours
             • Initial action: 3-5 business days
             • Resolution: 7-14 business days (depending on complexity)
             
             <div class="quick-actions" style="margin-top: 15px;">
                 <button class="quick-action-btn" onclick="window.open('${window.location.origin}/complaints/track', '_blank')">🔍 Track Online</button>
             </div>`],

            [['mediation', 'conciliation', 'dispute resolution'],
             `⚖️ **Barangay Mediation Services:**
             
             **Free Conciliation Services:**
             • Neighbor disputes
             • Property boundary issues
             • Minor civil conflicts
             • Family disputes
             
             **Process:**
             1. File complaint
             2. Summon both parties
             3. Mediation session
             4. Agreement drafting
             5. Legal documentation
             
             **Benefits:**
             • Free service
             • Faster resolution
             • Preserve relationships
             • Avoid court proceedings
             
             **Schedule:** Every Tuesday and Thursday, 2:00 PM - 5:00 PM`],

            [['complaint types', 'what complaints'],
             `📋 **Types of Complaints We Handle:**
             
             **Public Order & Safety:**
             • Noise disturbance
             • Public nuisance
             • Illegal activities
             • Safety hazards
             
             **Property & Civil Issues:**
             • Boundary disputes
             • Right of way issues
             • Property damage
             • Rental disputes
             
             **Infrastructure:**
             • Poor road conditions
             • Drainage problems
             • Street lighting
             • Water supply issues
             
             **Environmental:**
             • Improper waste disposal
             • Water pollution
             • Air quality concerns
             
             **Note:** Criminal cases should be reported directly to police.`],
            
            [['contact', 'phone', 'address', 'location', 'office'],
             `📍 Office Location:
             <br>
             
             Purok 1, Lumanglipa, Mataas na Kahoy, Batangas
             <br>
             <br>
             
             
             Office Hours:
             <br>
            <br>
             Monday-Friday: 8:00 AM - 5:00 PM
             <br>
             Saturday: 8:00 AM - 12:00 PM
             <br>
             Sunday: Closed`],
            
            [['schedule', 'hours', 'time', 'open', 'closed'],
             `🕐 **Office Schedule:**
             
             **Regular Hours:**
             • Monday-Friday: 8:00 AM - 5:00 PM
             • Saturday: 8:00 AM - 12:00 PM
             • Sunday: Closed
             
             **Lunch Break:** 12:00 PM - 1:00 PM
             **Emergency Services:** 24/7 available`],
            
            [['officials', 'captain', 'councilor', 'barangay official'],
             `👥 **Barangay Officials:**
             
             Our dedicated officials serve the community. You can learn more about them on our <a href="${window.location.origin}/about" target="_blank">About Page</a>.
             
             **How to reach officials:**
             • Schedule appointment at the office
             • Attend monthly barangay assembly
             • Submit written concerns`],
            
            [['registration', 'register', 'new resident', 'move'],
             `📋 **New Resident Registration:**
             
             Welcome to Barangay Lumanglipa! To register as a new resident:
             
             **Requirements:**
             • Transfer Certificate/Clearance from previous barangay
             • Valid ID
             • Proof of address
             
             **Start here:** <a href="${window.location.origin}/pre-registration" target="_blank">Registration Form</a>`],
            
            [['thank', 'thanks', 'salamat'],
             `You're welcome! Is there anything else you'd like to know about Barangay Lumanglipa services? I'm here to help! 😊`],
            
            [['bye', 'goodbye', 'see you'],
             `Thank you for contacting Barangay Lumanglipa! Have a great day and feel free to reach out anytime you need assistance. 👋`]
        ]);
    }

    scrollToBottom() {
        this.messages.scrollTop = this.messages.scrollHeight;
    }
}

// Quick message function for action buttons
function sendQuickMessage(message) {
    const chatbot = window.barangayChatbot;
    if (chatbot) {
        chatbot.addMessage(message, 'user');
        chatbot.showTyping();
        setTimeout(() => {
            chatbot.hideTyping();
            chatbot.processMessage(message.toLowerCase());
        }, 1000);
    }
}

// Initialize chatbot when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.barangayChatbot = new BarangayChatbot();
    // Make chatbot globally accessible for button callbacks
    window.chatbot = window.barangayChatbot;
});
</script>
@endpush

@endsection
