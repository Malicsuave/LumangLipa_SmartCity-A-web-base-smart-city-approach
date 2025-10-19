<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Scheduled - Case #{{ $blotterComplaint->case_number }}</title>
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
        .meeting-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
            margin: 10px 0;
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #2A7BC4;
        }
        .meeting-details {
            background: #e7f3ff;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
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
        .important-note {
            background: #fff3cd;
            padding: 15px;
            border-radius: 6px;
            border-left: 4px solid #ffc107;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">
                <i class="fas fa-calendar-check"></i>
            </div>
            <h2 style="margin: 0; color: #2A7BC4;">Barangay Lumanglipa</h2>
            <p style="margin: 5px 0 0; color: #666;">Meeting Scheduled</p>
        </div>

        <div style="margin-bottom: 25px;">
            <h3>Hello {{ $blotterComplaint->resident ? $blotterComplaint->resident->first_name . ' ' . $blotterComplaint->resident->last_name : 'Resident' }},</h3>
            
            <p>We are writing to inform you that a meeting has been <strong>scheduled</strong> for your blotter/complaint case.</p>
            <div class="meeting-badge">📅 MEETING SCHEDULED</div>
        </div>

        <div class="info-box">
            <h4 style="margin-top: 0; color: #2A7BC4;">Case Information</h4>
            <div class="info-row">
                <span class="info-label">Case Number:</span>
                <strong>{{ $blotterComplaint->case_number }}</strong>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <strong>Meeting Scheduled</strong>
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

        <div class="meeting-details">
            <h4 style="margin-top: 0; color: #007bff;">Meeting Details</h4>
            <div class="info-row">
                <span class="info-label">📅 Date:</span>
                <strong>{{ \Carbon\Carbon::parse($blotterComplaint->meeting_date)->format('F d, Y (l)') }}</strong>
            </div>
            <div class="info-row">
                <span class="info-label">🕐 Time:</span>
                <strong>{{ \Carbon\Carbon::parse($blotterComplaint->meeting_time)->format('h:i A') }}</strong>
            </div>
            <div class="info-row">
                <span class="info-label">📍 Location:</span>
                <strong>{{ $blotterComplaint->meeting_location }}</strong>
            </div>
            @if($blotterComplaint->meeting_notes)
            <div class="info-row" style="margin-top: 15px;">
                <span class="info-label">📝 Notes:</span>
                <div style="margin-top: 5px;">{{ $blotterComplaint->meeting_notes }}</div>
            </div>
            @endif
        </div>

        <div class="important-note">
            <h4 style="margin-top: 0; color: #856404;">Important Reminders</h4>
            <ul style="color: #856404; margin: 0; padding-left: 20px;">
                <li><strong>Please arrive 15 minutes early</strong> to allow time for check-in</li>
                <li>Bring a <strong>valid ID</strong> and any supporting documents related to your complaint</li>
                <li>Both parties (complainant and respondent) are expected to attend</li>
                <li>If you cannot attend, please contact the barangay office immediately</li>
                <li>The meeting aims to facilitate dialogue and seek resolution</li>
            </ul>
        </div>

        <div style="background: #d4edda; padding: 20px; border-radius: 6px; margin: 20px 0; border-left: 4px solid #28a745;">
            <h4 style="margin-top: 0; color: #155724;">What to Expect</h4>
            <ul style="color: #155724; margin: 0; padding-left: 20px;">
                <li>The meeting will be facilitated by barangay officials</li>
                <li>Both parties will have the opportunity to present their side</li>
                <li>The goal is to reach an amicable settlement if possible</li>
                <li>If no resolution is reached, further legal options will be discussed</li>
            </ul>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="tel:{{ config('app.barangay_contact', '+63-XXX-XXXX') }}" class="btn">Contact Barangay Office</a>
        </div>

        <div style="background: #e9ecef; padding: 15px; border-radius: 6px; margin: 20px 0;">
            <h4 style="margin-top: 0; color: #495057;">Need to Reschedule?</h4>
            <p style="margin: 0; color: #6c757d;">
                <strong>Contact us immediately:</strong><br>
                📞 Office Hours: Monday to Friday, 8:00 AM - 5:00 PM<br>
                📍 Barangay Lumanglipa Office<br>
                <em>Rescheduling requests must be made at least 24 hours in advance</em>
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