<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident ID Card</title>
    <style>
        @page {
            margin: 3mm;
            padding: 0;
            size: 148mm 190mm;
        }
        
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            margin: 0;
            padding: 2mm;
            background-color: #ffffff;
            font-size: 11px;
            line-height: 1.3;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }
        
        * {
            font-family: 'Arial', 'Helvetica', sans-serif !important;
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }
        
        /* Document header - align spacing/theme with Senior Citizen ID */
        .document-header {
            text-align: center;
            margin-bottom: 8px;
            padding: 8px;
            border-bottom: 2px solid #001a4e;
        }
        
        .document-title {
            color: #001a4e;
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
            width: 100%;
            margin-top: 0;
            margin-bottom: 40px;
            font-size: 8px;
            color: #666;
            line-height: 1.1;
        }
        .document-info span {
            font-size: 9.1px;
        }
        .document-info span:nth-child(1) {
            flex: none;
        }
        .document-info span:nth-child(2) {
            flex: none;
            margin-left: 32px;
        }
        .document-info span:nth-child(3) {
            flex: 1;
            text-align: right;
            justify-content: flex-end;
            display: flex;
            margin-left: 32px;
        }
        
        /* ID card styling with blue theme */
        .id-card-container {
            max-width: 240px;
            margin: 0 auto;
            position: relative;
        }
        .id-card {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            background: white;
            min-height: 135px;
            max-height: 135px;
            margin-bottom: 10px;
        }
        /* Extra gap between front and back cards */
        .id-card + .id-card {
            margin-top: 40px; /* Increased from 14px to 40px for more space between front and back */
        }
        
        /* Transparent background logo for front side - optimized for patched qt */
        .id-card-front-bg {
            position: absolute;
            top: 55%;
            left: 50%;
            width: 220px;
            height: 220px;
            margin-left: -110px;
            margin-top: -110px;
            opacity: 0.05;
            z-index: 1;
        }
        
        .id-card-front-bg img {
            width: 100%;
            height: 100%;
        }
        
        .id-card-header {
            background: linear-gradient(to right, #e3f2fd, #1976d2);
            background-color: #e3f2fd;
            padding: 3px;
            border-bottom: 1px solid #1976d2;
            position: relative;
            z-index: 2;
            height: 32px;
            border-radius: 6px 6px 0 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }
        .id-card-header table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
            background: transparent;
        }
        .id-card-header td {
            vertical-align: middle;
            padding: 0;
            background: transparent;
        }
        .barangay-logo-left {
            width: 28px !important;
            height: 28px !important;
            min-width: 28px !important;
            min-height: 28px !important;
            max-width: 28px !important;
            max-height: 28px !important;
            object-fit: cover !important;
            object-position: center !important;
            display: block !important;
            aspect-ratio: 1 / 1 !important;
            flex-shrink: 0 !important;
            margin: auto;
            margin-right: 2px;
        }
        
        .barangay-logo-right {
            width: 20px !important;
            height: 20px !important;
            min-width: 20px !important;
            min-height: 20px !imp4ortant;
            max-width: 20px !important;
            max-height: 20px !important;
            object-fit: cover !important;
            object-position: center !important;
            display: block !important;
            aspect-ratio: 1 / 1 !important;
            flex-shrink: 0 !important;
            margin: auto;
        }
        
        .barangay-logo-right {
            margin-left: 2px;
        }
        
        .header-logo-cell {
            width: 30px;
            min-width: 30px;
            max-width: 30px;
            text-align: center;
            vertical-align: middle;
            padding: 0;
        }
        
        .header-title-cell {
            text-align: center;
            padding: 0 6px;
        }
        
        .id-card-title {
            text-align: center;
            color: #003366 !important;
        }
        .id-card-title h6 {
            margin: 0.3px 0;
            padding: 0;
            font-weight: bold;
            font-size: 7px;
            color: #003366 !important;
            line-height: 1.0;
            font-family: 'Arial', 'Helvetica', sans-serif !important;
        }
        .id-card-title h6.small {
            font-size: 6px;
            color: #003366 !important;
            font-weight: normal;
        }
        .mb-0 {
            margin-bottom: 0;
        }
        .id-card-body {
            padding: 7px 9px;
            text-align: left;
            position: relative;
            z-index: 2;
        }
        
        /* Use table layout instead of flexbox for patched qt */
        .row {
            width: 100%;
            display: table;
            table-layout: fixed;
        }
        .no-gutters {
            margin-right: 0;
            margin-left: 0;
        }
        .col-md-8, .col-7 {
            display: table-cell;
            width: 60%;
            vertical-align: top;
            padding-right: 8px;
        }
        .col-md-4, .col-5 {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            padding-left: 4px;
        }
        .col-6 {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        
        table {
            border-collapse: collapse;
            border-spacing: 0;
        }
        table td {
            border: none;
            padding: 0;
        }
        .id-card-photo-container {
            width: 56px;
            height: 56px;
            overflow: hidden;
            border: 1px solid #003366;
            border-radius: 4px;
            margin: 0 auto 4px auto;
            background: #ffffff;
            display: block;
        }
        .id-card-photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .no-photo {
            width: 56px;
            height: 56px;
            background-color: #f0f0f0;
            text-align: center;
            color: #999;
            font-size: 7px;
            font-weight: bold;
            border: 1px solid #ccc;
            line-height: 56px;
        }
        .id-card-details {
            font-size: 7px;
            padding-left: 0;
            line-height: 1.3;
        }
        .id-card-details strong {
            font-weight: bold;
            color: #000;
            font-size: 6px;
        }
        .id-card-details span {
            font-weight: normal;
            color: #000;
            font-size: 7px;
        }
        .idno {
            font-weight: bold;
            color: #003366 !important;
            font-size: 8px;
        }
        .idno-box {
            background: #fff;
            border: 1px solid #003366;
            border-radius: 3px;
            padding: 3px 6px;
            margin: 4px auto 3px auto;
            text-align: center;
            display: block;
        }
        .text-center {
            text-align: center;
        }
        .mt-2 {
            margin-top: 6px;
        }
        .mb-2 {
            margin-bottom: 4px;
        }
        
        /* Spacing between fields */
        .id-card-details .mb-2 {
            margin-bottom: 3px !important;
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
            font-size: 5.5px !important;
            line-height: 0.95 !important;
            word-wrap: break-word !important;
            word-break: break-all !important;
            white-space: normal !important;
            overflow-wrap: anywhere !important;
            hyphens: auto !important;
            max-width: 100% !important;
            display: block !important;
            max-height: 32px !important;
            overflow: visible !important;
            /* Add word spacing for better readability */
            word-spacing: -0.5px !important;
            letter-spacing: -0.3px !important;
            /* Ensure it fits within the cell */
            box-sizing: border-box !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .text-uppercase {
            text-transform: uppercase;
            font-family: 'Arial', 'Helvetica', sans-serif !important;
        }
        .font-weight-bold {
            font-weight: bold !important;
            color: #000 !important;
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
        
        /* Back side styles */
        .id-card-back {
            background: #fafafa;
            min-height: 135px;
            max-height: 135px;
        }
        .id-card-back-body {
            padding: 7px 9px;
            text-align: left;
        }
        .id-card-back-details {
            font-size: 7px;
            padding-left: 0;
            line-height: 1.3;
        }
        .id-card-back-details strong {
            font-weight: bold;
            color: #000;
            font-size: 6px;
        }
        .id-card-back-details span {
            font-weight: normal;
            color: #000;
            font-size: 7px;
        }
        .qr-code-container {
            text-align: center;
            margin: 4px auto;
            width: 78px;
            height: 78px;
            padding: 0;
        }
        .qr-code-container img {
            width: 78px !important;
            height: 78px !important;
            display: block;
            margin: 0 auto;
            object-fit: contain;
            image-rendering: crisp-edges;
            image-rendering: -webkit-optimize-contrast;
        }
        .id-signature {
            margin-top: 5px;
            text-align: center;
        }
        .signature-line {
            width: 75px;
            height: 1px;
            background: #333;
            margin: 3px auto;
        }
        .id-signature img {
            display: block;
            max-height: 22px;
            max-width: 75px;
            margin: 0 auto;
        }
        .no-signature {
            width: 75px;
            height: 22px;
            border-bottom: 1px dashed #aaa;
            margin: 0 auto;
        }
        .small {
            font-size: 7px;
            margin-top: 1px;
            font-weight: normal;
        }
        strong {
            font-weight: bold;
            color: #000 !important;
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
            flex-direction: row;
            justify-content: space-between;
            margin-bottom: 2px;
        }
        
        .footer-note {
            text-align: center;
            font-style: italic;
            margin-top: 40px;
            font-size: 10px;
            color: #999;
        }
        
        /* Security features info */
        .security-info {
            background: #f8f9fa;
            padding: 5px;
            border-left: 3px solid #001a4e; /* Changed from #007bff to dark navy */
            margin: 70px 0 6px 0; /* Reduced top margin to ensure single-page PDF */
            font-size: 10px;
        }
    </style>
</head>
<body>
    <!-- Document Header - Professional info above ID cards -->
    <div class="document-header">
        <div class="document-title">RESIDENT IDENTIFICATION CARD</div>
        <div class="document-subtitle">Republic of the Philippines</div>
        <div class="document-subtitle">Province of Batangas - Municipality of Mataasnakahoy - Barangay Lumanglipa</div>
    </div>
    
    <!-- Document Information -->
    <div class="document-info">
        <span>Document No: RC-BRG-LUM-{{ date('Y') }}-{{ str_pad($resident->id, 4, '0', STR_PAD_LEFT) }}</span>
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
                <table>
                    <tr>
                        <td class="header-logo-cell">
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" alt="Barangay Logo" class="barangay-logo-left">
                        </td>
                        <td class="header-title-cell">
                            <div class="id-card-title">
                                <h6 class="mb-0">Barangay Lumanglipa</h6>
                                <h6 class="small mb-0">Mataasnakahoy, Batangas</h6>
                                <h6 class="mb-0">Residence Card</h6>
                            </div>
                        </td>
                        <td class="header-logo-cell">
                            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/kahoylogo.png'))) }}" alt="City Logo" class="barangay-logo-right">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="id-card-body">
                <table style="width: 100%; table-layout: fixed;">
                    <tr>
                        <td style="width: 65%; vertical-align: top; padding-right: 10px;">
                            <div class="id-card-details">
                                <div class="mb-2">
                                    <strong>Pangalan/Name</strong><br>
                                    <span class="text-uppercase font-weight-bold">{{ $resident->full_name }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Petsa ng Kapanganakan/Date of birth</strong><br>
                                    <span>{{ $resident->birthdate ? \Carbon\Carbon::parse($resident->birthdate)->format('M d, Y') : 'N/A' }}</span>
                                </div>
                                
                                <div class="mb-2">
                                    <strong>Telepono/Phone</strong><br>
                                    <span>{{ $resident->contact_number ?: 'N/A' }}</span>
                                </div>
                                <div class="mb-2">
                                    <strong>Tirahan/Address</strong><br>
                                    <span class="address-text" style="font-size: 5.5px; line-height: 0.95; word-wrap: break-word; word-break: break-all; white-space: normal; overflow-wrap: anywhere; hyphens: auto; max-width: 100%; display: block; word-spacing: -0.5px; letter-spacing: -0.3px; box-sizing: border-box; padding: 0; margin: 0; max-height: 32px; overflow: visible;">{{ $resident->address ?: ($resident->current_address ?: 'Sitio Malinggao Bato, Barangay Lumanglipa, Mataasnakahoy, Batangas') }}</span>
                                </div>
                            </div>
                        </td>
                        <td style="width: 35%; vertical-align: top; text-align: center;">
                            <table style="width: 100%; text-align: center;">
                                <tr>
                                    <td style="text-align: center;">
                                        @if($resident->photo && $resident->photo_path && file_exists($resident->photo_path))
                                            <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents($resident->photo_path)) }}" alt="{{ $resident->full_name }}" style="width: 52px; height: 52px; border: 1px solid #001a4e; border-radius: 3px;">
                                        @elseif($resident->photo)
                                            @php
                                                $photoPath = storage_path('app/public/residents/photos/' . $resident->photo);
                                            @endphp
                                            @if(file_exists($photoPath))
                                                <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents($photoPath)) }}" alt="{{ $resident->full_name }}" style="width: 52px; height: 52px; border: 1px solid #001a4e; border-radius: 3px;">
                                            @else
                                                <table style="width: 52px; height: 52px; background-color: #cccccc; border: 1px solid #999999; margin: 0 auto;">
                                                    <tr>
                                                        <td style="text-align: center; vertical-align: middle; font-size: 6px; color: #666666; font-weight: bold;">NO PHOTO</td>
                                                    </tr>
                                                </table>
                                            @endif
                                        @else
                                            <table style="width: 52px; height: 52px; background-color: #cccccc; border: 1px solid #999999; margin: 0 auto;">
                                                <tr>
                                                    <td style="text-align: center; vertical-align: middle; font-size: 6px; color: #666666; font-weight: bold;">NO PHOTO</td>
                                                </tr>
                                            </table>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: center; padding-top: 5px;">
                                        <div style="border: 1px solid #cccccc; border-radius: 3px; padding: 2px 5px; background-color: #ffffff; display: inline-block; font-size: 7px; white-space: nowrap; overflow: hidden; max-width: 100%;">
                                            <span style="font-weight: bold; color: #001a4e;">
                                                @if($resident->barangay_id)
                                                    {{ $resident->barangay_id }}
                                                @else
                                                    BRG-LUM-{{ date('Y') }}-{{ str_pad($resident->id ?? 1, 4, '0', STR_PAD_LEFT) }}
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
                                <span>{{ $resident->sex }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Katayuang Sibil/Civil Status</strong><br>
                                <span>{{ $resident->civil_status }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Lugar ng Kapanganakan/Place of birth</strong><br>
                                <span>{{ $resident->birthplace ?: 'N/A' }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Emergency Contact</strong><br>
                                <span>{{ $resident->household ? $resident->household->emergency_contact_name : ($resident->emergency_contact_name ?: 'Maria Santos Dela Cruz') }} @if($resident->household && $resident->household->emergency_contact_relationship)({{ $resident->household->emergency_contact_relationship }})@elseif($resident->emergency_contact_relationship)({{ $resident->emergency_contact_relationship }})@else(Mother)@endif</span>
                                <span style="display: block; margin-top: 1px;">{{ $resident->household ? $resident->household->emergency_phone : ($resident->emergency_contact_number ?: '+63-917-123-4567') }}</span>
                            </div>
                            
                            <!-- Validation Date -->
                            <div class="row no-gutters">
                                <div class="col-6">
                                    <div class="mb-2">
                                        <strong>Date Issued</strong><br>
                                        <span>{{ $resident->id_issued_at ? \Carbon\Carbon::parse($resident->id_issued_at)->format('m/d/Y') : date('m/d/Y') }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="mb-2">
                                        <strong>Valid Until</strong><br>
                                        <span>{{ $resident->id_expires_at ? \Carbon\Carbon::parse($resident->id_expires_at)->format('m/d/Y') : date('m/d/Y', strtotime('+5 years')) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="qr-code-container">
                            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" class="img-fluid">
                        </div>
                        <div class="id-signature mt-2 text-center">
                            @if($resident->signature)
                                @php
                                    $signaturePath = storage_path('app/public/residents/signatures/' . $resident->signature);
                                @endphp
                                @if(file_exists($signaturePath))
                                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents($signaturePath)) }}" alt="Signature" style="max-height: 30px; max-width: 100px; margin: 0 auto; display: block;">
                                @else
                                    <div class="no-signature"></div>
                                @endif
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
    
    
        <div class="footer-note">
            Non-Official Government Document. Handle with care and report if lost or stolen.
            <br>Generated: {{ date('F d, Y') }} | Valid until: {{ $resident->id_expires_at ? \Carbon\Carbon::parse($resident->id_expires_at)->format('F d, Y') : date('F d, Y', strtotime('+3 years')) }}
        </div>
    </div>
</body>
</html>