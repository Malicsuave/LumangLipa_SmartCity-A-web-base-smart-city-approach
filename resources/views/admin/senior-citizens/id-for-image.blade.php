<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senior Citizen ID Card</title>
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
            border-bottom: 2px solid #001a4e; /* Navy blue for document header border */
        }
        
        .document-title {
            color: #001a4e; /* Navy blue for document header */
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
            min-height: 250px;
            height: auto;
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
            background: linear-gradient(to right, #fff8e1, #ffe082); /* Yellow gradient to match preview */
            padding: 5px;
            border-bottom: 1px solid #ffca28; /* Yellow border to match preview */
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
            color: #f57f17 !important; /* Orange color for senior citizen to match preview */
        }
        .id-card-title h6 {
            margin: 0;
            font-weight: bold;
            font-size: 12px;
            color: #f57f17 !important; /* Orange color for senior citizen to match preview */
        }
        .id-card-title h6.small {
            font-size: 10px;
            color: #f57f17 !important; /* Orange color for senior citizen to match preview */
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
            width: 90px;
            height: 90px;
            overflow: hidden;
            border: 2px solid #f57f17;
            border-radius: 5px;
            margin: 10px auto 5px auto;
            background: white;
        }
        .id-card-photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .no-photo {
            width: 88px;
            height: 88px;
            background-color: #e0e0e0;
            text-align: center;
            vertical-align: middle;
            color: #666;
            font-size: 12px;
            font-weight: bold;
            border: 1px solid #999;
            line-height: 88px;
            position: relative;
        }
        .id-card-details {
            font-size: 11px;
            padding-left: 20px;
            font-family: 'Poppins', 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
        }
        
        /* Font styling for all text elements - navy blue text */
        .id-card-details strong {
            font-weight: bold !important;
            font-size: 11px !important;
            color: #001a4e !important; /* Navy blue for text */
            font-family: 'Poppins', 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
        }
        
        .id-card-details span {
            font-weight: normal !important;
            font-size: 11px !important;
            color: #001a4e !important; /* Navy blue for text */
            font-family: 'Poppins', 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
        }
        
      
        /* Address text wrapping fixes - Enhanced for long addresses */
        .mb-2 span {
            word-wrap: break-word;
            word-break: break-word;
            white-space: normal;
            overflow-wrap: break-word;
            hyphens: auto;
            line-height: 1.3;
            max-width: 100%;
            display: block;
        }
        
        /* Enhanced address field for very long addresses */
        .address-text {
            font-size: 9px !important;
            line-height: 1.1 !important;
            word-wrap: break-word !important;
            word-break: break-all !important;
            white-space: normal !important;
            overflow-wrap: anywhere !important;
            hyphens: auto !important;
            max-width: 100% !important;
            display: block !important;
            /* Add word spacing for better readability */
            word-spacing: -0.5px !important;
            letter-spacing: -0.2px !important;
            /* Ensure it fits within the cell */
            box-sizing: border-box !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .idno {
            font-weight: bold;
            color: #001a4e !important; /* Navy blue for text */
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
            font-family: 'Poppins', 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
        }
        .font-weight-bold {
            font-weight: bold !important;
            color: #001a4e !important; /* Navy blue for text */
            font-family: 'Poppins', 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
        }
        
        /* Specific styling for name field to match preview exactly */
        .id-card-details .text-uppercase.font-weight-bold {
            font-weight: bold !important;
            color: #001a4e !important; /* Navy blue for text */
            font-family: 'Poppins', 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
            font-size: 11px !important;
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
            font-family: 'Poppins', 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
        }
        
        /* Back side font styling - navy blue text */
        .id-card-back-details strong {
            font-weight: bold !important;
            font-size: 11px !important;
            color: #001a4e !important; /* Navy blue for text */
            font-family: 'Poppins', 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
        }
        
        .id-card-back-details span {
            font-weight: normal !important;
            font-size: 11px !important;
            color: #001a4e !important; /* Navy blue for text */
            font-family: 'Poppins', 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
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
            color: #001a4e !important; /* Navy blue for text */
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
            border-left: 3px solid #001a4e; /* Navy blue for security info border */
            margin: 6px 0;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <!-- Document Header - Professional info above ID cards -->
    <div class="document-header">
        <div class="document-title">SENIOR CITIZEN IDENTIFICATION CARD</div>
        <div class="document-subtitle">Republic of the Philippines</div>
        <div class="document-subtitle">Province of Batangas - Barangay Lumanglipa</div>
    </div>
    
    <!-- Document Information -->
    <div class="document-info">
        <span>Document No: SC-{{ $seniorCitizen->senior_id_number }}</span>
        <span>Generated: {{ date('F d, Y H:i:s') }}</span>
        <span>For Barangay Use Only</span>
    </div>
    
    <!-- ID Card Design -->
    <div class="id-card-container">
        <div id="idCardFront" class="id-card">
            <div class="id-card-front-bg">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" alt="Barangay Logo">
            </div>
            <div class="id-card-header">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" alt="Barangay Logo" class="barangay-logo-left">
                <div class="id-card-title">
                    <h6 class="mb-0">Barangay Lumanglipa</h6>
                    <h6 class="small mb-0">Mataasnakahoy, Batangas</h6>
                    <h6 class="mb-0">Senior Citizen Card</h6>
                </div>
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/citylogo.png'))) }}" alt="City Logo" class="barangay-logo-right">
            </div>
            <div class="id-card-body">
                <table style="width: 100%; table-layout: fixed; border: none;">
                    <tr>
                        <td style="width: 65%; vertical-align: top; padding-right: 10px; border: none;">
                            <div class="id-card-details">
                                <div class="mb-2">
                                    <strong>Pangalan/Name</strong><br>
                                    <span class="text-uppercase font-weight-bold">{{ $seniorCitizen->full_name }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Petsa ng Kapanganakan/Date of birth</strong><br>
                                    <span>{{ $seniorCitizen->birthdate ? \Carbon\Carbon::parse($seniorCitizen->birthdate)->format('M d, Y') : 'N/A' }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Telepono/Phone</strong><br>
                                    <span>{{ $seniorCitizen->contact_number ?: 'N/A' }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Tirahan/Address</strong><br>
                                    <span class="address-text" style="font-size: 9px; line-height: 1.1; word-wrap: break-word; word-break: break-all; white-space: normal; overflow-wrap: anywhere; hyphens: auto; max-width: 100%; display: block; word-spacing: -0.5px; letter-spacing: -0.2px; box-sizing: border-box; padding: 0; margin: 0;">{{ $seniorCitizen->current_address ?: 'Sitio Malinggao Bato, Barangay Lumanglipa, Mataasnakahoy, Batangas' }}</span>
                                </div>
                            </div>
                        </td>
                        <td style="width: 35%; vertical-align: top; text-align: center; border: none;">
                            <table style="width: 100%; text-align: center;">
                                <tr>
                                    <td style="text-align: center;">
                                        @if($seniorCitizen->photo)
                                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/' . $seniorCitizen->photo))) }}" alt="{{ $seniorCitizen->full_name }}" style="width: 88px; height: 88px; border: 2px solid #f57f17; border-radius: 5px;">
                                        @else
                                            <table style="width: 88px; height: 88px; background-color: #cccccc; border: 2px solid #999999; margin: 0 auto;">
                                                <tr>
                                                    <td style="text-align: center; vertical-align: middle; font-size: 10px; color: #666666; font-weight: bold;">NO PHOTO</td>
                                                </tr>
                                            </table>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding-top: 8px;">
                                        <div style="border: 1px solid #cccccc; border-radius: 4px; padding: 4px 8px; background-color: #ffffff; display: inline-block; font-size: 10px; white-space: nowrap; overflow: hidden; max-width: 100%;">
                                            <span style="font-weight: bold; color: #001a4e;">
                                                @if($seniorCitizen->senior_id_number)
                                                    {{ $seniorCitizen->senior_id_number }}
                                                @else
                                                    SC-LUM-{{ date('Y') }}-{{ str_pad($seniorCitizen->id ?? 1, 4, '0', STR_PAD_LEFT) }}
                                                @endif
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
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
                                <span>{{ $seniorCitizen->sex }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Katayuang Sibil/Civil Status</strong><br>
                                <span>{{ $seniorCitizen->civil_status }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Place of Birth</strong><br>
                                <span>{{ $seniorCitizen->birthplace ?: 'N/A' }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Emergency Contact</strong><br>
                                <span>{{ $seniorCitizen->emergency_contact_name ?: 'N/A' }} @if($seniorCitizen->emergency_contact_relationship)({{ $seniorCitizen->emergency_contact_relationship }})@endif</span>
                                <span style="display: block; margin-top: 1px;">{{ $seniorCitizen->emergency_contact_number ?: 'N/A' }}</span>
                            </div>
                            
                            <!-- Validation Date -->
                            <div class="row no-gutters">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <strong>Date Issued</strong><br>
                                        <span>{{ $seniorCitizen->senior_id_issued_at ? $seniorCitizen->senior_id_issued_at->format('m/d/Y') : date('m/d/Y') }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <strong>Valid Until</strong><br>
                                        <span>{{ $seniorCitizen->senior_id_expires_at ? $seniorCitizen->senior_id_expires_at->format('m/d/Y') : date('m/d/Y', strtotime('+5 years')) }}</span>
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
                            @if($seniorCitizen->signature)
                                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/' . $seniorCitizen->signature))) }}" alt="Signature" style="max-height: 30px; max-width: 100px;">
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
        <strong>Notice:</strong> This identification card is valid for barangay transactions and services only. Contains QR code for verification purposes.
    </div>
    
    <!-- Document Footer - Professional info below the ID cards -->
    <div class="document-footer">
        <div class="footer-info">
            <span>Issued by: Barangay Lumanglipa Office</span>
            <span>Contact: {{ $seniorCitizen->contact_number ?: 'N/A' }}</span>
        </div>
        <div class="footer-info">
            <span>Address: Mataasnakahoy, Batangas</span>
            <span>Reference: DOC-{{ date('Y') }}-{{ str_pad($seniorCitizen->id, 6, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="footer-note">
            Official barangay document for local transactions only. Handle with care and report if lost or stolen.
            <br>Generated: {{ date('F d, Y') }} | Valid until: {{ $seniorCitizen->senior_id_expires_at ? $seniorCitizen->senior_id_expires_at->format('F d, Y') : date('F d, Y', strtotime('+5 years')) }}
        </div>
    </div>
</body>
</html>
