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

        this.addMessage(message, 'user');
        this.input.value = '';
        this.send.disabled = true;
        
        this.showTyping();
        setTimeout(() => {
            this.hideTyping();
            this.processMessage(message);
            this.send.disabled = false;
        }, 1500 + Math.random() * 1000);
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
            [['document', 'document request', 'clearance', 'certificate', 'residency', 'indigency', 'request'], 
             this.getServiceOptions('Document Services')],
            
            [['health', 'health services', 'medical', 'clinic', 'medicine', 'doctor'],
             this.getServiceOptions('Health Services')],
            
            [['complaint', 'file complaint', 'problem', 'issue', 'concern', 'report'],
             this.getServiceOptions('Complaint Filing')],

            [['barangay id', 'id card', 'resident id', 'identification'],
             `

What would you like to know about Barangay ID?

<div class="quick-actions" style="margin-top: 15px;">
    <button class="quick-action-btn" data-action="what-is-barangay-id">❓ What is Barangay ID?</button>
    <button class="quick-action-btn" data-action="get-barangay-id">� Get Barangay ID</button>
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
