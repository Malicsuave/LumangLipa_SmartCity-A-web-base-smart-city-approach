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
        max-width: 85%;
        word-wrap: break-word;
        font-size: 14px;
        line-height: 1.4;
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .message.user {
        background: linear-gradient(135deg, #3F9EDD 0%, #2A7BC4 100%);
        color: white;
        margin-left: auto;
        border-bottom-right-radius: 5px;
    }
    
    .message.bot {
        background: white;
        color: #333;
        border: 1px solid #e9ecef;
        border-bottom-left-radius: 5px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
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
                <h5 id="chatbotTitle">Lumanglipa AI Assistant</h5>
            </div>
            <div class="header-right">
                <button class="language-toggle" id="languageToggle">EN</button>
                <button class="chatbot-close" id="chatbotClose">&times;</button>
            </div>
        </div>
        
        <div class="chatbot-messages" id="chatbotMessages">
            <div class="message bot" id="welcomeMessage">
                Hello! I'm your Barangay Lumanglipa AI Assistant. I can help you with:
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
    
    let currentLanguage = 'en';
    let isOpen = false;
    
    const translations = {
        en: {
            title: 'Lumanglipa AI Assistant',
            welcome: 'Hello! I\'m your Barangay Lumanglipa AI Assistant. I can help you with:<br>• How to request documents<br>• How to file complaints<br>• How to register on the website<br>• How to get Barangay ID<br>• Office hours and contact info<br><br>How can I assist you today?',
            placeholder: 'Type your message here...',
            langButton: 'EN'
        },
        tl: {
            title: 'Lumanglipa AI Assistant',
            welcome: 'Kumusta! Ako ang inyong Barangay Lumanglipa AI Assistant. Makakatulong ako sa inyo sa:<br>• Paano mag-request ng mga dokumento<br>• Paano mag-file ng complaint<br>• Paano mag-register sa website<br>• Paano makakuha ng Barangay ID<br>• Oras ng opisina at contact info<br><br>Paano kita makakatulong ngayon?',
            placeholder: 'I-type ang inyong mensahe dito...',
            langButton: 'TL'
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
    }
    
    function openChatbot() {
        chatbotWindow.style.display = 'flex';
        isOpen = true;
        chatbotInput.focus();
    }
    
    function closeChatbot() {
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
        
        // Show typing indicator
        showTyping();
        
        // Disable send button
        chatbotSend.disabled = true;
        
        try {
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
                addMessage(data.response, 'bot');
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
    }
    
    function addMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `message ${sender}`;
        messageDiv.innerHTML = text;
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
});
</script>
@endsection