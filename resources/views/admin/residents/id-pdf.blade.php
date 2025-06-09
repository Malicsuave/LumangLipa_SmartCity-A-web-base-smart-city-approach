<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resident ID Card</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 6pt;
            color: #000;
        }
        .id-card {
            width: 243pt;
            height: 153pt;
            border: 1px solid #ccc;
            background-color: #fff;
            overflow: hidden;
        }
        .card-header {
            background-color: #e3f2fd;
            padding: 3pt;
            text-align: center;
            border-bottom: 0.5pt solid #ccc;
        }
        .logo-left {
            display: inline-block;
            vertical-align: middle;
            width: 30px;
            height: 30px;
            margin: 0 3pt;
        }
        .logo-right {
            display: inline-block;
            vertical-align: middle;
            width: 100px;
            height: 100px;
            margin: 0 3pt;
        }
        .header-title {
            display: inline-block;
            vertical-align: middle;
        }
        .bargy-name {
            margin: 0;
            color: #1565c0;
            font-size: 8pt;
            font-weight: bold;
        }
        .bargy-location {
            margin: 0;
            font-size: 5pt;
        }
        .card-type {
            margin: 0;
            color: #1565c0;
            font-size: 6pt;
            font-weight: bold;
        }
        .card-body {
            padding: 3pt 6pt;
        }
        .info-label {
            font-size: 5pt;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 1pt;
        }
        .info-value {
            font-size: 7pt;
            font-weight: bold;
            margin-bottom: 3pt;
        }
        .photo {
            width: 50pt;
            height: 50pt;
            position: absolute;
            top: 38pt;
            right: 6pt;
            border: 0.5pt solid #ccc;
            background-color: #f9f9f9;
            overflow: hidden;
        }
        .photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .id-card-back {
            width: 243pt;
            height: 153pt;
            background-color: #e6f6ee;
            border: 1px solid #ccc;
            margin-top: 2pt;
            overflow: hidden;
        }
        .back-details {
            width: 60%;
            float: left;
            padding: 6pt;
        }
        .back-qr {
            width: 40%;
            float: right;
            text-align: center;
        }
        .qr-code {
            width: 50pt;
            height: 50pt;
            margin-top: 8pt;
        }
        .signature-area {
            margin-top: 6pt;
        }
        .signature-line {
            width: 60pt;
            height: 0.5pt;
            background-color: #000;
            margin: 1pt 0;
        }
        .signature-text {
            font-size: 4pt;
            color: #666;
        }
        .footer {
            position: absolute;
            bottom: 3pt;
            left: 6pt;
            right: 6pt;
            border-top: 0.5pt solid #ddd;
            padding-top: 2pt;
            font-size: 5pt;
        }
        .footer-left {
            float: left;
        }
        .footer-right {
            float: right;
        }
    </style>
</head>
<body>
    <!-- Front side of the ID Card -->
    <div class="id-card">
        <div class="card-header">
            <img src="{{ public_path('images/logo.png') }}" class="logo-left">
            <div class="header-title">
                <div class="bargy-name">Barangay Lumanglipa</div>
                <div class="bargy-location">Matasnakahoy, Lipa City Batangas</div>
                <div class="card-type">Residence Card</div>
            </div>
            <img src="{{ public_path('images/citylogo.png') }}" class="logo-right">
        </div>
        
        <div class="card-body">
            <div style="width: 70%; float: left;">
                <div class="info-label">Name</div>
                <div class="info-value">{{ strtoupper($resident->full_name) }}</div>
                
                <div class="info-label">Date of Birth</div>
                <div class="info-value">{{ $resident->birthdate ? $resident->birthdate->format('m-d-Y') : 'N/A' }}</div>
                
                <div class="info-label">Address</div>
                <div class="info-value" style="font-size:6pt;">{{ $resident->address }}</div>
                
                <div class="info-label">Contact</div>
                <div class="info-value">{{ $resident->contact_number ?: 'N/A' }}</div>
                
                <div class="info-label">ID No.</div>
                <div class="info-value">{{ $resident->barangay_id }}</div>
            </div>
            
            <div class="photo">
                @if($resident->photo)
                    <img src="{{ public_path('storage/residents/photos/' . $resident->photo) }}">
                @endif
            </div>
            
            <div class="footer">
                <div class="footer-left">
                    <strong>Date Issued:</strong> {{ $resident->id_issued_at ? $resident->id_issued_at->format('m/d/Y') : date('m/d/Y') }}
                </div>
                <div class="footer-right">
                    <strong>Valid Until:</strong> {{ $resident->id_expires_at ? $resident->id_expires_at->format('m/d/Y') : date('m/d/Y', strtotime('+5 years')) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Back side of the ID Card -->
    <div class="id-card-back">
        <div class="card-header">
            <img src="{{ public_path('images/logo.png') }}" class="logo-left">
            <div class="header-title">
                <div class="bargy-name">Barangay Lumanglipa</div>
                <div class="bargy-location">Matasnakahoy, Lipa City Batangas</div>
                <div class="card-type">Residence Card</div>
            </div>
            <img src="{{ public_path('images/citylogo.png') }}" class="logo-right">
        </div>
        
        <div class="card-body">
            <div class="back-details">
                <div class="info-label">Gender</div>
                <div class="info-value">{{ $resident->sex }}</div>
                
                <div class="info-label">Civil Status</div>
                <div class="info-value">{{ $resident->civil_status }}</div>
                
                <div class="info-label">Place of Birth</div>
                <div class="info-value" style="font-size:6pt;">{{ $resident->birthplace ?: 'N/A' }}</div>
                
                <div class="info-label">Emergency Contact</div>
                <div class="info-value" style="font-size:6pt;">{{ $resident->household ? $resident->household->emergency_contact_name : 'N/A' }}</div>
                
                <div class="signature-area">
                    @if($resident->signature)
                        <img src="{{ public_path('storage/residents/signatures/' . $resident->signature) }}" style="max-height: 15pt; max-width: 60pt;">
                    @else
                        <div class="signature-line"></div>
                    @endif
                    <div class="signature-text">CARD HOLDER'S SIGNATURE</div>
                </div>
            </div>
            
            <div class="back-qr">
                <img src="data:image/png;base64,{{ $qrCode }}" class="qr-code">
            </div>
        </div>
    </div>
</body>
</html>