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

    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        .header-bar {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            padding: 1.5rem 2rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .logo-img {
            height: 50px;
            width: auto;
        }
        
        .logo-text {
            font-size: 1.4rem;
            font-weight: 700;
        }
        
        .header-actions a {
            color: white;
            text-decoration: none;
            margin-left: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        
        .header-actions a:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .content {
            padding: 2rem;
        }
        
        h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #1e293b;
        }
        
        .alert {
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
        }
        
        .alert-success {
            background-color: #ecfdf5;
            border-left: 4px solid #10b981;
            color: #047857;
        }
        
        .alert-warning {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            color: #b45309;
        }
        
        .alert-info {
            background-color: #eff6ff;
            border-left: 4px solid #3b82f6;
            color: #1d4ed8;
        }
        
        .alert-error {
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
            color: #b91c1c;
        }
        
        .info-box {
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .info-primary {
            background-color: #f3f4f6;
        }
        
        .info-accent {
            background: linear-gradient(135deg, #eff6ff, #f5f3ff);
            border: 1px solid #e0e7ff;
        }
        
        .info-warning {
            background-color: #fffbeb;
            border: 1px solid #fef3c7;
        }
        
        .steps-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            margin: 2rem 0;
        }
        
        @media (min-width: 768px) {
            .steps-container {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        .step-box {
            background-color: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            position: relative;
            border-top: 4px solid #4f46e5;
        }
        
        .step-number {
            position: absolute;
            top: -1rem;
            left: 1rem;
            background: #4f46e5;
            color: white;
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3);
        }
        
        .step-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1e293b;
            font-size: 1.1rem;
        }
        
        .step-description {
            font-size: 0.9rem;
            color: #64748b;
        }
        
        .request-form {
            background-color: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
        }
        
        .form-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .form-row {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #1e293b;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #cbd5e1;
            border-radius: 0.5rem;
            font-family: 'Nunito', sans-serif;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #818cf8;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }
        
        .form-text {
            font-size: 0.875rem;
            color: #64748b;
            margin-top: 0.5rem;
        }
        
        .form-error {
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #ef4444;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            border: none;
            font-family: 'Poppins', sans-serif;
            font-size: 0.9rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca, #6d28d9);
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);
        }
        
        .btn-secondary {
            background-color: #f1f5f9;
            color: #334155;
        }
        
        .btn-secondary:hover {
            background-color: #e2e8f0;
        }
        
        .form-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 2rem;
        }
        
        .footer {
            margin-top: 3rem;
            text-align: center;
            color: #64748b;
            font-size: 0.9rem;
        }
        
        .footer a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 500;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
        
        .pending-request {
            background: linear-gradient(135deg, #fef3c7, #fffbeb);
            border-radius: 1rem;
            padding: 2rem;
            border: 1px solid #fcd34d;
            margin-top: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        .pending-icon {
            position: absolute;
            top: -2rem;
            right: -2rem;
            font-size: 8rem;
            opacity: 0.07;
            transform: rotate(15deg);
            color: #d97706;
        }
        
        .pending-status {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background-color: #fcd34d;
            color: #92400e;
            padding: 0.25rem 1rem;
            border-radius: 2rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        
        .pending-detail {
            background-color: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-top: 1rem;
            font-size: 0.95rem;
        }
        
        .detail-label {
            font-weight: 600;
            color: #92400e;
            margin-bottom: 0.25rem;
        }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.85rem;
            font-weight: 500;
            margin-top: 0.25rem;
        }
        
        .badge-info {
            background-color: #e0f2fe;
            color: #0369a1;
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
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
            
            <div class="content">
                <h1>Access Required</h1>
                
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
                        
                        <h2 style="font-size: 1.5rem; margin-bottom: 1rem; color: #92400e;">Your Access Request is Being Reviewed</h2>
                        
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
                            <p style="white-space: pre-line;">{{ $pendingRequest->reason }}</p>
                        </div>
                        
                        <p style="margin-top: 1.5rem; font-size: 0.9rem; color: #92400e;">
                            <i class="fas fa-info-circle"></i> For urgent matters, please contact the system administrator directly.
                        </p>
                    </div>
                @else
                    <!-- Process Overview -->
                    <div class="info-box info-accent">
                        <h3 style="margin-bottom: 0.5rem; font-size: 1.2rem; color: #1e40af;">
                            <i class="fas fa-info-circle"></i> How to Get Access
                        </h3>
                        <p style="font-size: 0.95rem; color: #1e40af;">Follow these simple steps to request system access:</p>
                    </div>
                    
                    <div class="steps-container">
                        <div class="step-box">
                            <div class="step-number">1</div>
                            <div class="step-title">Submit Request</div>
                            <div class="step-description">Fill out the form below with your role and reason</div>
                        </div>
                        
                        <div class="step-box">
                            <div class="step-number">2</div>
                            <div class="step-title">Admin Review</div>
                            <div class="step-description">Request is reviewed by Barangay Captain</div>
                        </div>
                        
                        <div class="step-box">
                            <div class="step-number">3</div>
                            <div class="step-title">Email Notification</div>
                            <div class="step-description">You'll be notified once decision is made</div>
                        </div>
                        
                        <div class="step-box">
                            <div class="step-number">4</div>
                            <div class="step-title">Access Granted</div>
                            <div class="step-description">If approved, log in with your new role</div>
                        </div>
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
                                <label for="name" class="form-label">Full Name <span style="color: #ef4444;">*</span></label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter your full name" required>
                                @error('name')
                                    <div class="form-error">{{ $message }}</div>
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
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Reason -->
                            <div class="form-row">
                                <label for="reason" class="form-label">Reason for Access Request</label>
                                <textarea id="reason" name="reason" rows="4" class="form-control" placeholder="Please explain your position in the barangay and why you need access to this system..."></textarea>
                                @error('reason')
                                    <div class="form-error">{{ $message }}</div>
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