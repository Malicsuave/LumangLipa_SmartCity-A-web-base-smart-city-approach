<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blotter/Complaint Status Update</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2A7BC4;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
            background: #2A7BC4;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            margin: 10px 0;
        }
        .status-accepted {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #2A7BC4;
        }
        .info-row {
            margin: 8px 0;
        }
        .info-label {
            font-weight: bold;
            color: #666;
            display: inline-block;
            min-width: 120px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #2A7BC4;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 15px 0;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #1e5a8a;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">
                <i class="fas fa-balance-scale"></i>
            </div>
            <h2 style="margin: 0; color: #2A7BC4;">Barangay Lumanglipa</h2>
            <p style="margin: 5px 0 0; color: #666;">Blotter/Complaint Status Update</p>
        </div>

        <div style="margin-bottom: 25px;">
            <h3>Hello {{ $blotterComplaint->resident ? $blotterComplaint->resident->first_name . ' ' . $blotterComplaint->resident->last_name : 'Resident' }},</h3>
            
            @if($status === 'accepted')
                <p>We are writing to inform you that your blotter/complaint has been <strong>accepted</strong> for review and investigation.</p>
                <div class="status-badge status-accepted">✓ ACCEPTED</div>
            @elseif($status === 'rejected')
                <p>We are writing to inform you that your blotter/complaint has been <strong>rejected</strong> after initial review.</p>
                <div class="status-badge status-rejected">✗ REJECTED</div>
            @endif
        </div>

        <div class="info-box">
            <h4 style="margin-top: 0; color: #2A7BC4;">Case Details</h4>
            <div class="info-row">
                <span class="info-label">Case Number:</span>
                <strong>{{ $blotterComplaint->case_number }}</strong>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <strong>{{ ucwords($status) }}</strong>
            </div>
            <div class="info-row">
                <span class="info-label">Filed Date:</span>
                {{ $blotterComplaint->created_at->format('F d, Y \a\t h:i A') }}
            </div>
            <div class="info-row">
                <span class="info-label">Complainants:</span>
                {{ $blotterComplaint->complainants }}
            </div>
            <div class="info-row">
                <span class="info-label">Respondents:</span>
                {{ $blotterComplaint->respondents }}
            </div>
        </div>

        @if($status === 'accepted')
            <div style="background: #d4edda; padding: 20px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #28a745;">
                <h4 style="margin-top: 0; color: #155724;">What happens next?</h4>
                <ul style="color: #155724; margin: 0;">
                    <li>Your case has been assigned for investigation</li>
                    <li>Our barangay officials will review the details thoroughly</li>
                    <li>You may be contacted for additional information if needed</li>
                    <li>A meeting or mediation session may be scheduled</li>
                    <li>You will receive updates on the progress of your case</li>
                </ul>
            </div>
        @elseif($status === 'rejected' && $rejectionReason)
            <div style="background: #f8d7da; padding: 20px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #dc3545;">
                <h4 style="margin-top: 0; color: #721c24;">Reason for Rejection:</h4>
                <p style="color: #721c24; margin: 0;">{{ $rejectionReason }}</p>
            </div>
            <div style="background: #fff3cd; padding: 20px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #ffc107;">
                <h4 style="margin-top: 0; color: #856404;">Need to Appeal?</h4>
                <p style="color: #856404; margin: 0;">
                    If you believe this rejection was made in error or you have additional information to support your case, 
                    you may visit the barangay office during business hours to discuss your concern with our officials.
                </p>
            </div>
        @endif

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('blotter-complaint.request') }}" class="btn">View Public Form</a>
        </div>

        <div style="background: #e9ecef; padding: 15px; border-radius: 6px; margin: 20px 0;">
            <h4 style="margin-top: 0; color: #495057;">Contact Information</h4>
            <p style="margin: 5px 0; color: #6c757d;">
                <strong>Barangay Lumanglipa Office</strong><br>
                Office Hours: Monday to Friday, 8:00 AM - 5:00 PM<br>
                For inquiries about your case, please visit our office or contact us during business hours.
            </p>
        </div>

        <div class="footer">
            <p><strong>Barangay Lumanglipa Management System</strong></p>
            <p>This is an automated message. Please do not reply to this email.</p>
            <p style="font-size: 12px; margin-top: 15px;">
                © {{ date('Y') }} Barangay Lumanglipa. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>