<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Unauthorized Access - {{ config('app.name', 'Lumanglipa') }}</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8b 100%);
            font-family: 'Poppins', 'Nunito', Arial, sans-serif;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 16px;
        }
        .card {
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 8px 32px rgba(42,123,196,0.12);
            overflow: hidden;
            padding: 0;
        }
        .header-bar {
            background: #f8fafc;
            padding: 1.25rem 2rem 1.25rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e5e7eb;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .logo-img {
            height: 40px;
            width: 40px;
            border-radius: 8px;
            background: #e5e7eb;
            object-fit: contain;
        }
        .logo-text {
            font-weight: 700;
            font-size: 1.25rem;
            color: #2A7BC4;
        }
        .header-actions a {
            color: #2A7BC4;
            font-weight: 500;
            margin-left: 1.5rem;
            text-decoration: none;
            transition: color 0.2s;
        }
        .header-actions a:hover {
            color: #1e5f8b;
        }
        .content {
            padding: 2rem 2rem 1.5rem 2rem;
        }
        h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #2A7BC4;
            margin-bottom: 1.25rem;
            text-align: center;
        }
        .alert {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .alert-success { background: #e6f7ee; color: #22c55e; border: 1px solid #22c55e; }
        .alert-warning { background: #fffbe6; color: #f59e42; border: 1px solid #f59e42; }
        .alert-info { background: #e6f0fa; color: #2A7BC4; border: 1px solid #2A7BC4; }
        .alert-error { background: #ffe6e6; color: #ef4444; border: 1px solid #ef4444; }
        .info-box {
            background: #f8fafc;
            border-radius: 10px;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
            font-size: 1rem;
        }
        .info-primary { border-left: 4px solid #2A7BC4; }
        .info-accent { border-left: 4px solid #f59e42; }
        .pending-request {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.5rem 1.25rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(42,123,196,0.07);
        }
        .pending-icon {
            font-size: 2.5rem;
            color: #f59e42;
            text-align: center;
        }
        .pending-status {
            color: #f59e42;
            font-weight: 600;
            text-align: center;
            margin-bottom: 0.5rem;
        }
        .pending-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2A7BC4;
            text-align: center;
            margin-bottom: 0.5rem;
        }
        .pending-detail {
            margin-bottom: 0.5rem;
        }
        .detail-label {
            font-weight: 600;
            color: #1e5f8b;
            font-size: 0.95rem;
        }
        .reason-text {
            background: #e6f0fa;
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            font-size: 0.98rem;
        }
        .pending-note {
            font-size: 0.95rem;
            color: #64748b;
            margin-top: 1rem;
            text-align: center;
        }
        .steps-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
            justify-content: space-between;
        }
        .step-box {
            background: #e6f0fa;
            border-radius: 10px;
            flex: 1 1 110px;
            min-width: 110px;
            max-width: 120px;
            padding: 1rem 0.5rem;
            text-align: center;
            box-shadow: 0 1px 4px rgba(42,123,196,0.07);
        }
        @media (max-width: 600px) {
            .container { max-width: 100%; }
            .card { border-radius: 10px; }
            .header-bar, .content { padding: 1rem; }
            .steps-container { flex-direction: column; gap: 0.75rem; }
            .step-box {
                min-width: 0;
                max-width: 100%;
                width: 100%;
                padding: 1rem;
            }
        }
        .request-form {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.5rem 1.25rem;
            box-shadow: 0 2px 8px rgba(42,123,196,0.07);
            margin-bottom: 1.5rem;
        }
        .form-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #2A7BC4;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .form-row {
            margin-bottom: 1.25rem;
        }
        .form-label {
            font-weight: 600;
            color: #1e5f8b;
            margin-bottom: 0.25rem;
            display: block;
        }
        .form-control {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            font-size: 1rem;
            background: #fff;
            margin-bottom: 0.25rem;
        }
        .form-text {
            font-size: 0.92rem;
            color: #64748b;
            margin-top: 0.25rem;
        }
        .form-footer {
            text-align: right;
        }
        .btn-primary {
            background: linear-gradient(90deg, #2A7BC4 0%, #1e5f8b 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-primary:hover {
            background: linear-gradient(90deg, #1e5f8b 0%, #2A7BC4 100%);
        }
        .badge-info {
            background: #2A7BC4;
            color: #fff;
            border-radius: 6px;
            padding: 0.25rem 0.75rem;
            font-size: 0.95rem;
            font-weight: 600;
        }
        .required {
            color: #ef4444;
        }
        .footer {
            text-align: center;
            font-size: 0.98rem;
            color: #64748b;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        .footer a {
            color: #2A7BC4;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header-bar">
                <div class="logo">
                    <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }} Logo" class="logo-img">
                    <div class="logo-text">{{ config('app.name', 'Lumanglipa') }}</div>
                </div>
                <div class="header-actions">
                    <a href="{{ route('public.home') }}"><i class="fas fa-home"></i> Home</a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                    </form>
                </div>
            </div>
            <div class="content">
                <h1><i class="fas fa-lock"></i> Access Required</h1>
                <!-- Alerts -->
                @if (session('status'))
                    <div class="alert alert-success">
                        <strong><i class="fas fa-check-circle"></i> Success!</strong> {{ session('status') }}
                    </div>
                @endif
                
                @if (session('warning'))
                    <div class="alert alert-warning">
                        <strong><i class="fas fa-exclamation-triangle"></i> Notice!</strong> {{ session('warning') }}
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info">
                        <strong><i class="fas fa-info-circle"></i> Info!</strong> {{ session('info') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-error">
                        <strong><i class="fas fa-times-circle"></i> Error!</strong> {{ session('error') }}
                    </div>
                @endif
                
                <div class="info-box info-primary">
                    <p>Your account <strong>{{ $email }}</strong> needs approval before you can access the Lumanglipa Barangay Management System. Access is restricted to verified community members and officials.</p>
                </div>

                @if(isset($pendingRequest))
                    <!-- Pending Request Status -->
                    <div class="pending-request">
                        <div class="pending-icon">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        
                        <div class="pending-status">
                            <i class="fas fa-clock"></i> Request Pending
                        </div>
                        
                        <h2 class="pending-title">Your Access Request is Being Reviewed</h2>
                        
                        <p>We've received your request and it's currently being reviewed by our administrators. You'll receive an email notification once a decision has been made.</p>
                        
                        <div class="pending-detail">
                            <div class="detail-label">Name Provided</div>
                            <p>{{ $pendingRequest->name ?? 'Not provided' }}</p>
                        </div>
                        
                        <div class="pending-detail">
                            <div class="detail-label">Submitted On</div>
                            <p>{{ $pendingRequest->requested_at->format('F j, Y, g:i a') }}</p>
                        </div>
                        
                        <div class="pending-detail">
                            <div class="detail-label">Role Requested</div>
                            <span class="badge badge-info">{{ $pendingRequest->role->name }}</span>
                        </div>
                        
                        <div class="pending-detail">
                            <div class="detail-label">Your Request Reason</div>
                            <p class="reason-text">{{ $pendingRequest->reason }}</p>
                        </div>
                        
                        <p class="pending-note">
                            <i class="fas fa-info-circle"></i> For urgent matters, please contact the system administrator directly.
                        </p>
                    </div>
                @else
                    <!-- Process Overview -->
                    <div class="info-box info-accent">
                        <h3 class="access-overview-title">
                            <i class="fas fa-info-circle"></i> How to Get Access
                        </h3>
                        <p class="access-overview-text">To request access, please fill out the form below with your full name, desired role, and reason for access. Your request will be reviewed by the barangay administrator and you will be notified via email once a decision is made.</p>
                    </div>

                    <!-- Request Form -->
                    <div class="request-form">
                        <h2 class="form-title">
                            <i class="fas fa-user-shield"></i> Request System Access
                        </h2>
                        
                        <form method="POST" action="{{ route('request.access') }}">
                            @csrf

                            <!-- Name Field -->
                            <div class="form-row">
                                <label for="name" class="form-label">Full Name <span class="required">*</span></label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter your full name" required>
                                @error('name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i> Please enter your complete name as it appears on official documents.
                                </div>
                            </div>

                            <!-- Role Selection -->
                            <div class="form-row">
                                <label for="role_requested" class="form-label">Role Requested</label>
                                <select id="role_requested" name="role_requested" class="form-control">
                                    <option value="">-- Select Desired Role --</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @error('role_requested')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Reason -->
                            <div class="form-row">
                                <label for="reason" class="form-label">Reason for Access Request</label>
                                <textarea id="reason" name="reason" rows="4" class="form-control" placeholder="Please explain your position in the barangay and why you need access to this system..."></textarea>
                                @error('reason')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i> Please provide detailed information about your role in the community and why you need access to the system.
                                </div>
                            </div>

                            <div class="form-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Submit Request
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
                
                <div class="footer">
                    <p>
                        If you believe this is an error or need immediate assistance,<br>
                        please contact the system administrator at <a href="mailto:admin@lumanglipa.gov.ph">admin@lumanglipa.gov.ph</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Optional JavaScript for any interactive elements
    </script>
</body>
</html>