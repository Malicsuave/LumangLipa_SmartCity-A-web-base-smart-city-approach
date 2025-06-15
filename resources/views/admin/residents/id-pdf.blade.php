<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident ID Card</title>
    <!-- Import Google Fonts for Poppins and Open Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Open+Sans:wght@400;500;600;700&display=swap');
        
        @page {
            margin: 8mm;
            padding: 0;
            size: 148mm 180mm; /* Custom size: shorter height */
        }
        
        body {
            font-family: 'Poppins', 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
        }
        
        * {
            font-family: 'Poppins', 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
        }
        
        /* Document header - professional info above the ID cards */
        .document-header {
            text-align: center;
            margin-bottom: 12px;
            padding: 8px;
            border-bottom: 2px solid #001a4e; /* Changed from #007bff to dark navy */
        }
        
        .document-title {
            color: #001a4e; /* Changed from #007bff to dark navy */
            font-size: 18px;
            font-weight: bold;
            margin: 3px 0;
        }
        
        .document-subtitle {
            color: #666;
            font-size: 12px;
            margin: 1px 0;
        }
        
        .document-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 11px;
            color: #666;
        }
        
        /* ID card styling with blue theme */
        .id-card-container {
            max-width: 450px;
            margin: 0 auto;
            position: relative;
        }
        .id-card {
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            background: white;
            height: 250px;
            margin-bottom: 20px;
        }
        
        /* Transparent background logo for front side - match senior citizen exactly */
        .id-card-front-bg {
            position: absolute;
            top: 60%; /* Same as senior citizen ID */
            left: 50%;
            transform: translate(-50%, -50%);
            width: 280px; /* Exact same size as senior citizen */
            height: 280px; /* Exact same size as senior citizen */
            opacity: 0.08; /* Same opacity as senior citizen */
            z-index: 1;
            pointer-events: none;
        }
        
        .id-card-front-bg img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .id-card-header {
            background: linear-gradient(to right, #e3f2fd, #90caf9); /* Blue gradient instead of yellow */
            padding: 5px;
            border-bottom: 1px solid #1976d2; /* Blue border */
            display: flex;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        .barangay-logo-left {
            width: 45px;
            height: 45px;
            object-fit: cover;
            margin-left: 5px;
            margin-top: 2px;
            margin-bottom: 2px;
        }
        
        .barangay-logo-right {
            width: 45px;
            height: 45px;
            object-fit: cover;
            margin-right: 5px;
            margin-top: 2px;
            margin-bottom: 2px;
            margin-left: auto;
        }
        
        .id-card-title {
            text-align: center;
            flex: 1;
            color: #001a4e !important; /* Same dark navy as senior citizen ID */
        }
        .id-card-title h6 {
            margin: 0;
            font-weight: bold;
            font-size: 12px;
            color: #001a4e !important; /* Same dark navy as senior citizen ID */
        }
        .id-card-title h6.small {
            font-size: 10px;
            color: #001a4e !important; /* Same dark navy as senior citizen ID */
        }
        .id-card-body {
            padding: 15px;
            text-align: left;
            position: relative;
            z-index: 2;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
        }
        .no-gutters {
            margin-right: 0;
            margin-left: 0;
        }
        .col-md-8 {
            flex: 0 0 66.666667%;
            max-width: 66.666667%;
        }
        .col-md-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
        }
        .col-7 {
            flex: 0 0 58.333333%;
            max-width: 58.333333%;
        }
        .col-5 {
            flex: 0 0 41.666667%;
            max-width: 41.666667%;
        }
        .col-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
        .id-card-photo-container {
            width: 100px;
            height: 100px;
            overflow: hidden;
            border: 1px solid #ddd;
            border-radius: 10%;
            margin: 0 auto;
        }
        .id-card-photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .no-photo {
            width: 100%;
            height: 100%;
            background-color: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #aaa;
            font-size: 48px;
        }
        .id-card-details {
            font-size: 11px;
            padding-left: 20px;
        }
        .idno {
            font-weight: bold;
            color: #001a4e !important; /* Same dark navy as senior citizen ID */
            font-size: 12px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .text-center {
            text-align: center;
        }
        .mt-2 {
            margin-top: 10px;
        }
        .mb-2 {
            margin-bottom: 8px;
        }
        .text-uppercase {
            text-transform: uppercase;
        }
        .font-weight-bold {
            font-weight: bold !important;
            color: #001a4e !important; /* Same dark navy as senior citizen ID */
        }
        
        /* Back side styles */
        .id-card-back {
            background: #f5f5f5;
        }
        .id-card-back-body {
            padding: 15px;
            text-align: left;
        }
        .id-card-back-details {
            font-size: 11px;
            padding-left: 10px;
        }
        .qr-code-container {
            text-align: center;
            margin: 10px auto;
        }
        .qr-code-container img {
            width: 150px;
            height: 150px;
        }
        .id-signature {
            margin-top: 5px;
            text-align: center;
        }
        .signature-line {
            width: 100px;
            height: 1px;
            background: #333;
            margin: 3px auto;
        }
        .id-signature img {
            display: block;
            max-height: 30px;
            max-width: 100px;
            margin: 0 auto;
        }
        .no-signature {
            width: 100px;
            height: 30px;
            border-bottom: 1px dashed #aaa;
            margin: 0 auto;
        }
        .small {
            font-size: 85%;
            margin-top: -2px;
        }
        strong {
            font-weight: bold;
            color: #001a4e !important; /* Same dark navy as senior citizen ID */
        }
        .d-flex {
            display: flex;
        }
        .align-items-center {
            align-items: center;
        }
        .img-fluid {
            max-width: 100%;
            height: auto;
        }
        
        /* Document footer - professional info below the ID cards */
        .document-footer {
            margin-top: 12px;
            padding: 6px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
        }
        
        .footer-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        
        .footer-note {
            text-align: center;
            font-style: italic;
            margin-top: 6px;
        }
        
        /* Security features info */
        .security-info {
            background: #f8f9fa;
            padding: 5px;
            border-left: 3px solid #001a4e; /* Changed from #007bff to dark navy */
            margin: 6px 0;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <!-- Document Header - Professional info above ID cards -->
    <div class="document-header">
        <div class="document-title">RESIDENT IDENTIFICATION CARD</div>
        <div class="document-subtitle">Republic of the Philippines</div>
        <div class="document-subtitle">Province of Batangas - Barangay Lumanglipa</div>
    </div>
    
    <!-- Document Information -->
    <div class="document-info">
        <span>Document No: RC-{{ $resident->barangay_id }}</span>
        <span>Generated: {{ date('F d, Y H:i:s') }}</span>
        <span>Valid Government ID</span>
    </div>
    
    <!-- ID Card Design -->
    <div class="id-card-container">
        <div id="idCardFront" class="id-card">
            <div class="id-card-front-bg">
                <img src="{{ public_path('images/logo.png') }}" alt="Barangay Logo">
            </div>
            <div class="id-card-header">
                <img src="{{ public_path('images/logo.png') }}" alt="Barangay Logo" class="barangay-logo-left">
                <div class="id-card-title">
                    <h6 class="mb-0">Barangay Lumanglipa</h6>
                    <h6 class="small mb-0">Matasnakahoy, Batangas</h6>
                    <h6 class="mb-0">Residence Card</h6>
                </div>
                <img src="{{ public_path('images/citylogo.png') }}" alt="City Logo" class="barangay-logo-right">
            </div>
            <div class="id-card-body">
                <div class="row no-gutters">
                    <div class="col-md-8">
                        <div class="id-card-details">
                            <div class="mb-2">
                                <strong>Pangalan/Name</strong><br>
                                <span class="text-uppercase font-weight-bold">{{ $resident->full_name }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Petsa ng Kapanganakan/Date of birth</strong><br>
                                <span>{{ $resident->birthdate ? $resident->birthdate->format('M d, Y') : 'N/A' }}</span>
                            </div>
                            
                            <div class="mb-2">
                                <strong>Telepono/Phone</strong><br>
                                <span>{{ $resident->contact_number ?: 'N/A' }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Tirahan/Address</strong><br>
                                <span>{{ $resident->address }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="id-card-photo-container">
                            @if($resident->photo)
                                <img src="{{ public_path('storage/residents/photos/' . $resident->photo) }}" alt="{{ $resident->full_name }}">
                            @else
                                <div class="no-photo">
                                    <i class="fe fe-user"></i>
                                </div>
                            @endif
                        </div>
                        <div class="text-center mt-2">
                            <span class="idno">{{ $resident->barangay_id }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Back Side -->
        <div id="idCardBack" class="id-card id-card-back">
            <div class="id-card-back-body">
                <div class="row">
                    <div class="col-7">
                        <div class="id-card-back-details">
                            <div class="mb-2">
                                <strong>Kasarian/Sex</strong><br>
                                <span>{{ $resident->sex }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Katayuang Sibil/Civil Status</strong><br>
                                <span>{{ $resident->civil_status }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Place of Birth</strong><br>
                                <span>{{ $resident->birthplace ?: 'N/A' }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Emergency Contact</strong><br>
                                <span>{{ $resident->household ? $resident->household->emergency_contact_name : 'N/A' }}</span><br>
                                <span>{{ $resident->household ? $resident->household->emergency_contact_number : '' }}</span>
                            </div>
                            
                            <!-- Validation Date -->
                            <div class="row no-gutters">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <strong>Date Issued</strong><br>
                                        <span>{{ $resident->id_issued_at ? $resident->id_issued_at->format('m/d/Y') : date('m/d/Y') }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <strong>Valid Until</strong><br>
                                        <span>{{ $resident->id_expires_at ? $resident->id_expires_at->format('m/d/Y') : date('m/d/Y', strtotime('+3 years')) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="qr-code-container">
                            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" class="img-fluid" style="width: 150px; height: 150px;">
                        </div>
                        <div class="id-signature mt-2 text-center">
                            @if($resident->signature)
                                <img src="{{ public_path('storage/residents/signatures/' . $resident->signature) }}" alt="Signature">
                            @else
                                <div class="no-signature"></div>
                            @endif
                            <div class="signature-line"></div>
                            <div class="small">May-ari/Card Holder</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Security Information -->
    <div class="security-info">
        <strong>Security Features:</strong> This document contains QR code verification and official seals. Any unauthorized reproduction is prohibited by law.
    </div>
    
    <!-- Document Footer - Professional info below the ID cards -->
    <div class="document-footer">
        <div class="footer-info">
            <span>Issued by: Barangay Lumanglipa Office</span>
            <span>Contact: {{ $resident->contact_number ?: 'N/A' }}</span>
        </div>
        <div class="footer-info">
            <span>Address: Matasnakahoy, Batangas</span>
            <span>Reference: {{ $resident->barangay_id }}</span>
        </div>
        <div class="footer-note">
            Official government document. Handle with care and report if lost or stolen.
            <br>Generated: {{ date('F d, Y') }} | Valid until: {{ $resident->id_expires_at ? $resident->id_expires_at->format('F d, Y') : date('F d, Y', strtotime('+3 years')) }}
        </div>
    </div>
</body>
</html>