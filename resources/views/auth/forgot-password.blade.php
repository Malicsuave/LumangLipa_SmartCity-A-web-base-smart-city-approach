<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('template/assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
  <title>{{ config('app.name', 'Laravel') }} - Forgot Password</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="{{ asset('template/assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('template/assets/css/nucleo-svg.css') }}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('template/assets/css/material-kit.css?v=3.1.0') }}" rel="stylesheet" />
</head>

<body class="sign-in-basic">
  
  <div class="page-header align-items-start min-vh-100" style="background-color: #e5e7eb;">
    <div class="container my-auto">
      <div class="row">
        <div class="col-lg-4 col-md-8 col-12 mx-auto">
          <!-- Logo for smaller screens - shows above the card -->
          <div class="text-center mb-5 d-md-none">
            <img src="{{ asset('images/logo.png') }}" alt="Barangay Lumanglipa Logo" style="width: 80px; height: 80px; object-fit: contain;">
            <h5 class="mt-0 mb-0" style="color: #000000; font-weight: 600;">Barangay Lumanglipa</h5>
          </div>
          
          <div class="card z-index-0 fadeIn3 fadeInBottom">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="border-radius-lg py-3 pe-1" style="background: #2A7BC4; box-shadow: 0 4px 6px rgba(42, 123, 196, 0.3);">
                <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Reset Password</h4>
                <p class="text-white text-center mt-2 mb-0">Enter your email to receive reset link</p>
              </div>
            </div>
            <div class="card-body">
              
              <div class="mb-4 text-sm text-muted text-center">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
              </div>

              @if (session('status'))
                <div class="alert alert-success">
                  {{ session('status') }}
                </div>
              @endif

              <!-- Validation Errors -->
              @if ($errors->any())
                <div class="alert alert-danger">
                  <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              <form role="form" method="POST" action="{{ route('password.email') }}" class="text-start">
                @csrf
                
                <div class="input-group input-group-outline my-3">
                  <label class="form-label">Email Address</label>
                  <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus autocomplete="username">
                </div>
                
                <div class="text-center">
                  <button type="submit" class="btn w-100 my-4 mb-2" style="background: #2A7BC4; color: white; border: none; border-radius: 8px;">Email Password Reset Link</button>
                </div>
                
                <p class="text-sm text-center">
                  Remember your password?
                  <a href="{{ route('login') }}" style="color: #2A7BC4; text-decoration: none;">Back to sign in</a>
                </p>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <footer class="footer position-absolute bottom-2 py-2 w-100">
      <div class="container">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-12 col-md-6 my-auto">
            <div class="copyright text-center text-sm text-white text-lg-start">
              © {{ date('Y') }}, Barangay Lumanglipa Management System
            </div>
          </div>
        </div>
      </div>
    </footer>
  </div>
  
  <!--   Core JS Files   -->
  <script src="{{ asset('template/assets/js/core/popper.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('template/assets/js/core/bootstrap.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('template/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <!-- Control Center for Material UI Kit -->
  <script src="{{ asset('template/assets/js/material-kit.min.js?v=3.1.0') }}" type="text/javascript"></script>
</body>
</html>
