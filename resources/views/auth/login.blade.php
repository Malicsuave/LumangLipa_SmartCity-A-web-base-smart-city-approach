<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('template/assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
  <title>{{ config('app.name', 'Laravel') }} - Sign In</title>
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
          <div class="card z-index-0 fadeIn3 fadeInBottom" style="border: 2px solid #000;">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="border-radius-lg py-3 pe-1" style="background: #2A7BC4; box-shadow: 0 4px 6px rgba(42, 123, 196, 0.3);">
                <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Sign in</h4>
              </div>
            </div>
            <div class="card-body">
              
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

              @if (session('status'))
                <div class="alert alert-success">
                  {{ session('status') }}
                </div>
              @endif

              <form role="form" method="POST" action="{{ route('login') }}" class="text-start">
                @csrf
                
                <div class="input-group input-group-outline my-3">
                  <label class="form-label">Email</label>
                  <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus autocomplete="username">
                </div>
                
                <div class="input-group input-group-outline mb-3">
                  <label class="form-label">Password</label>
                  <input type="password" name="password" class="form-control" required autocomplete="current-password">
                </div>
                
                <div class="form-check form-switch d-flex align-items-center justify-content-between mb-3">
                  <div class="d-flex align-items-center">
                    <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                    <label class="form-check-label mb-0 ms-3" for="rememberMe">Remember me</label>
                  </div>
                  <div>
                    <a href="{{ route('public.home') }}" class="text-decoration-none d-flex align-items-center" style="color: #2A7BC4; font-size: 0.75rem;">
                      <i class="fas fa-arrow-left me-1" style="font-size: 0.7rem;"></i>
                      Back
                    </a>
                  </div>
                </div>
                
                <div class="text-center">
                  <button type="submit" class="btn w-100 my-2 mb-1" style="background: #2A7BC4; color: white; border: none; border-radius: 8px;">Sign in</button>
                </div>
                
                <!-- Separator Line -->
                <div class="d-flex align-items-center my-2">
                  <hr class="flex-grow-1">
                  <span class="mx-2 text-muted small">or</span>
                  <hr class="flex-grow-1">
                </div>
                
                <!-- Google Sign In Button -->
                <div class="text-center mb-3">
                  <a href="{{ route('auth.google') }}" id="google-signin-btn" class="btn w-100 d-flex align-items-center justify-content-center text-decoration-none" style="background: white; color: #666; border: 1px solid #2A7BC4; border-radius: 8px; font-size: 0.875rem; padding: 0.5rem 1rem;">
                    <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google" class="me-2" style="width: 16px; height: 16px;">
                    <span>Sign in with Google</span>
                  </a>
                </div>
                
                <p class="mt-4 text-sm text-center">
                  @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="color: #2A7BC4; text-decoration: none;">
                      Forgot your password?
                    </a>
                  @endif
                </p>
                
                @if (Route::has('register'))
                  <p class="text-sm text-center">
                    Don't have an account?
                    <a href="{{ route('register') }}" style="color: #2A7BC4; text-decoration: none;">Sign up</a>
                  </p>
                @endif
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
            <div class="copyright text-center text-sm text-lg-start" style="color: #666;">
              © {{ date('Y') }} Barangay Lumanglipa SmartCity System
            </div>
          </div>
          <div class="col-12 col-md-6 my-auto">
            <div class="copyright text-center text-sm text-lg-end" style="color: #666;">
              Bayan ng Mataasnakahoy Batangas
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

  <script>
    // Clear Google cookies when Google sign-in button is clicked
    document.getElementById('google-signin-btn').addEventListener('click', function(e) {
      // Clear Google-related cookies
      const cookies = document.cookie.split(';');
      for (let i = 0; i < cookies.length; i++) {
        const cookie = cookies[i];
        const eqPos = cookie.indexOf('=');
        const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
        if (name.includes('google') || name.includes('accounts.google')) {
          document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/';
          document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;domain=.google.com';
          document.cookie = name + '=;expires=Thu, 01 Jan 1970 00:00:00 GMT;path=/;domain=.accounts.google.com';
        }
      }
      
      // Clear localStorage and sessionStorage for Google domains
      try {
        localStorage.clear();
        sessionStorage.clear();
      } catch (e) {
        // Ignore errors if storage is not available
      }
    });
  </script>
</body>
</html>
