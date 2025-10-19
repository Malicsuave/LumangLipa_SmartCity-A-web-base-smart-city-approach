<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OTP Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
            text-align: center;
            background-color: #e9ecef;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            letter-spacing: 4px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Barangay Lumanglipa</h1>
        <p>
            @if(str_contains($service_type, 'blotter') || str_contains($service_type, 'complaint'))
                Blotter/Complaint Report System
            @elseif(str_contains($service_type, 'health'))
                Health Service System
            @else
                Document Verification System
            @endif
        </p>
    </div>
    
    <div class="content">
        <h2>Hello, {{ $resident_name }}!</h2>
        
        <p>You have requested to verify your identity for a {{ $service_type }}. Please use the following One-Time Password (OTP) to complete your verification:</p>
        
        <div class="otp-code">
            {{ $otp_code }}
        </div>
        
        <div class="warning">
            <strong>Important:</strong>
            <ul>
                <li>This OTP is valid for {{ $expires_minutes }} minutes only</li>
                <li>Do not share this code with anyone</li>
                <li>If you didn't request this verification, please ignore this email</li>
            </ul>
        </div>
        
        <p>If you encounter any issues, please contact the Barangay Office immediately.</p>
        
        <p>Thank you for using our services.</p>
        
        <p><strong>Barangay Lumanglipa<br>
        @if(str_contains($service_type, 'blotter') || str_contains($service_type, 'complaint'))
            Blotter/Complaint Management System
        @elseif(str_contains($service_type, 'health'))
            Health Service Management System
        @else
            Document Management System
        @endif
        </strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated email. Please do not reply to this message.</p>
        <p>&copy; {{ date('Y') }} Barangay Lumanglipa. All rights reserved.</p>
    </div>
</body>
</html>
