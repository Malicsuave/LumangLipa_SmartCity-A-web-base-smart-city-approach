<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Lumanglipa Barangay Management System">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/css/favicon.png') }}">
    <title>@yield('title', 'Barangay Lumanglipa')</title>

    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Use same FontAwesome as admin for consistency -->
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Backup CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
 

    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('assets/css/material-kit.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}?v={{ time() }}">
    <style>
      .navbar-toggler,
      .navbar-toggler:focus,
      .navbar-toggler:active,
      .navbar-toggler:focus-visible {
        outline: none !important;
        box-shadow: none !important;
        border: none !important;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        height: 48px;
        width: 48px;
      }
      .navbar-toggler-icon {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        width: 100%;
      }
      .navbar-toggler-bar {
        display: block;
        width: 28px;
        height: 3px;
        margin: 4px 0;
        background: #333;
        border-radius: 2px;
      }
    </style>
</head>
<body class="index-page bg-gray-200" data-chatbot-strict="{{ config('services.huggingface.strict') ? '1' : '0' }}" data-chatbot-has-key="{{ config('services.huggingface.api_key') ? '1' : '0' }}">
    <!-- Navbar -->
    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg blur border-radius-xl top-0 z-index-fixed shadow position-absolute my-3 p-2 start-0 end-0 mx-4">
                    <div class="container-fluid px-0">
                        <a class="navbar-brand fw-bold ms-sm-3 text-sm" href="{{ route('public.home') }}">
                            <img src="{{ asset('images/logo.png') }}" alt="Barangay Logo" class="align-middle me-2" style="height:2rem; vertical-align:middle;"> Barangay Lumanglipa
                        </a>
                        <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
                          <span class="navbar-toggler-icon">
                            <span class="navbar-toggler-bar bar1"></span>
                            <span class="navbar-toggler-bar bar2"></span>
                            <span class="navbar-toggler-bar bar3"></span>
                          </span>
                        </button>
                        <div class="collapse navbar-collapse pt-3 pb-2 py-lg-0 w-100" id="navigation">
                            <ul class="navbar-nav navbar-nav-hover ms-auto">
                                <li class="nav-item mx-2">
                                    <a class="nav-link d-flex align-items-center fw-bold" href="{{ route('public.home') }}">
                                        <span class="material-symbols-rounded opacity-6 align-middle me-2 text-md" style="font-variation-settings: 'wght' 100;">home</span> Home
                                    </a>
                                </li>
                                <li class="nav-item dropdown dropdown-hover mx-2">
                                    <a class="nav-link ps-2 d-flex cursor-pointer align-items-center fw-bold" id="dropdownMenuAbout" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="material-symbols-rounded opacity-6 align-middle me-2 text-md" style="font-variation-settings: 'wght' 100;">info</span>
                                        About
                                        <img src="{{ asset('assets/img/down-arrow-dark.svg') }}" alt="down-arrow" class="arrow ms-auto ms-md-2">
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-animation ms-n3 dropdown-md p-3 border-radius-xl mt-0 mt-lg-3" aria-labelledby="dropdownMenuAbout">
                                        <li><a class="dropdown-item" href="{{ route('public.about') }}">About Barangay</a></li>
                                        <li><a class="dropdown-item" href="{{ route('public.officials') }}">Officials</a></li>
                                        <li><a class="dropdown-item" href="{{ route('public.announcements') }}">Announcements</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown dropdown-hover mx-2">
                                    <a class="nav-link ps-2 d-flex cursor-pointer align-items-center fw-bold" id="dropdownMenuServices" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="material-symbols-rounded opacity-6 align-middle me-2 text-md" style="font-variation-settings: 'wght' 100;">view_day</span>
                                        eServices
                                        <img src="{{ asset('assets/img/down-arrow-dark.svg') }}" alt="down-arrow" class="arrow ms-auto ms-md-2">
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-animation ms-n3 dropdown-md p-3 border-radius-xl mt-0 mt-lg-3" aria-labelledby="dropdownMenuServices">
                                        <li><a class="dropdown-item" href="{{ route('health.request') }}">Health Services</a></li>
                                        <li><a class="dropdown-item" href="{{ route('documents.request') }}">Document Request</a></li>
                                        <li><a class="dropdown-item" href="{{ route('complaints.create') }}">File a Complaint</a></li>
                                        <li><a class="dropdown-item" href="{{ route('blotter.request') }}">Blotter Report</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item mx-2">
                                    <a class="nav-link d-flex align-items-center fw-bold" href="{{ route('public.contact') }}">
                                        <span class="material-symbols-rounded opacity-6 align-middle me-2 text-md" style="font-variation-settings: 'wght' 100;">call</span> Contact
                                    </a>
                                </li>
                                <li class="nav-item mx-2">
                                    <a class="nav-link d-flex align-items-center fw-bold" href="{{ route('public.pre-registration.step1') }}">
                                        <span class="material-symbols-rounded opacity-6 align-middle me-2 text-md" style="font-variation-settings: 'wght' 100;">person_add</span> Register
                                    </a>
                                </li>
                                  <li class="nav-item my-auto ms-3 ms-lg-0">
    <a href="{{route('login') }}" class="btn mb-0 mt-2 mt-md-0" style="background-color:#2A7BC4; color:#fff; border:none; border-radius:8px; font-weight:600;">Login</a>
</li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- End Navbar -->

    <main>
        @yield('content')
      
    </main>

   <footer class="footer pt-5 mt-5">
    <div class="container">
      <div class="row">
        <div class="col-md-3 mb-4 ms-auto">
          <div>
            <a href="{{ route('public.home') }}">
              <img src="{{ asset('images/logo.png') }}" class="mb-3 footer-logo" alt="Barangay Lumanglipa Logo" style="height:3.2rem; max-width:100%;">
            </a>
            <h6 class="font-weight-bolder mb-4">Barangay Lumanglipa</h6>
            <p class="text-dark text-sm">Mataas na Kahoy, Batangas<br>Official Website</p>
          </div>
          <div>
            <ul class="d-flex flex-row ms-n3 nav">
              <li class="nav-item">
                <a class="nav-link pe-1" href="#" target="_blank">
                  <i class="fab fa-facebook text-lg opacity-8"></i>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link pe-1" href="#" target="_blank">
                  <i class="fab fa-twitter text-lg opacity-8"></i>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link pe-1" href="#" target="_blank">
                  <i class="fab fa-instagram text-lg opacity-8"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-md-2 col-sm-6 col-6 mb-4">
          <div>
            <h6 class="text-sm">Quick Links</h6>
            <ul class="flex-column ms-n3 nav">
              <li class="nav-item">
                <a class="nav-link" href="{{ route('public.home') }}">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('public.about') }}">About</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('public.services') }}">Services</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('public.contact') }}">Contact</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-md-2 col-sm-6 col-6 mb-4">
          <div>
            <h6 class="text-sm">eServices</h6>
            <ul class="flex-column ms-n3 nav">
              <li class="nav-item">
                <a class="nav-link" href="{{ route('health.request') }}">Health Services</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('documents.request') }}">Document Request</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('complaints.create') }}">File a Complaint</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('public.pre-registration.step1') }}">Register</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-md-2 col-sm-6 col-6 mb-4">
          <div>
            <h6 class="text-sm">Help & Support</h6>
            <ul class="flex-column ms-n3 nav">
              <li class="nav-item">
                <a class="nav-link" href="mailto:info@lumanglipa.gov.ph">Email Us</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="tel:+63431234567">Call: (043) 123-4567</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('public.contact') }}">Office Location</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-md-2 col-sm-6 col-6 mb-4 me-auto">
          <div>
            <h6 class="text-sm">Legal</h6>
            <ul class="flex-column ms-n3 nav">
              <li class="nav-item">
                <a class="nav-link" href="#">Terms & Conditions</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Privacy Policy</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="col-12">
          <div class="text-center">
            <p class="text-dark my-4 text-sm font-weight-normal">&copy; {{ date('Y') }} Barangay Lumanglipa. All rights reserved. Mataas na Kahoy, Batangas</p>
          </div>
        </div>
      </div>
    </div>
  </footer> <!-- Footer -->
    <!-- Core JS Files -->
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/material-kit.min.js') }}"></script>
    @stack('scripts')

    <!-- Floating Chatbot -->
    <div class="chatbot-container">
        <div class="chatbot-window" id="chatbotWindow">
            <div class="chatbot-header">
                <h4><i class="fas fa-robot me-2"></i>Barangay Assistant</h4>
                <button class="chatbot-close" id="chatbotClose" style="display:flex;align-items:center;justify-content:center;width:32px;height:32px;background:transparent;border:none;outline:none;cursor:pointer;">
                    <i class="fas fa-times" style="color:white;font-size:16px;"></i>
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
      <button class="chatbot-toggle" id="chatbotToggle" style="position:relative;">
        <div class="chatbot-pulse"></div>
        <img src="{{ asset('images/logo.png') }}" alt="Barangay Logo" style="width:38px; height:38px; border-radius:50%; box-shadow:0 2px 8px rgba(42,123,196,0.18); background:#fff; object-fit:cover; position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);">
    </button>
    </div>
    <script src="{{ asset('js/chatbot.js') }}?v=2"></script>
</body>
</html>
