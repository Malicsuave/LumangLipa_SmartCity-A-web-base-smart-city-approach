<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resident ID Card</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 7pt;
        }
        
        /* ID Card Container */
        .id-card {
            width: 243pt;
            height: 153pt;
            background-color: white;
            position: relative;
            box-sizing: border-box;
        }
        
        /* Front side of the ID card */
        .id-card-front {
            width: 100%;
            height: 100%;
            position: relative;
            padding: 10pt;
        }
        
        /* Header with barangay info */
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 5pt;
            border-bottom: 1pt solid #072d6b;
            padding-bottom: 5pt;
        }
        
        .logo {
            width: 25pt;
            height: 25pt;
            margin-right: 10pt;
        }
        
        .header-text {
            text-align: center;
        }
        
        .header-text h1 {
            font-size: 9pt;
            font-weight: bold;
            margin: 0;
            color: #072d6b;
        }
        
        .header-text h2 {
            font-size: 6pt;
            margin: 0;
            font-weight: normal;
        }
        
        /* Card Title */
        .card-title {
            background-color: #072d6b;
            color: white;
            padding: 3pt;
            text-align: center;
            font-size: 8pt;
            font-weight: bold;
            margin: 5pt 0;
            border-radius: 3pt;
        }
        
        /* Body content */
        .card-body {
            display: flex;
            margin: 5pt 0;
        }
        
        /* Photo section */
        .photo-section {
            width: 50pt;
            height: 50pt;
            overflow: hidden;
            border: 0.5pt solid #ddd;
            margin-right: 8pt;
        }
        
        .photo {
            width: 100%;
            height: 100%;
        }
        
        /* Resident details */
        .details {
            flex: 1;
        }
        
        .field {
            margin-bottom: 2pt;
            font-size: 6pt;
        }
        
        .field-label {
            font-weight: bold;
            display: inline-block;
            width: 40pt;
        }
        
        .field-row {
            display: flex;
            justify-content: space-between;
        }
        
        .field-half {
            width: 48%;
        }
        
        /* Footer with validity dates */
        .card-footer {
            position: absolute;
            bottom: 10pt;
            left: 10pt;
            right: 10pt;
            border-top: 0.5pt solid #ddd;
            padding-top: 3pt;
            display: flex;
            justify-content: space-between;
        }
        
        /* QR Code */
        .qr-code {
            position: absolute;
            bottom: 20pt;
            right: 10pt;
            width: 40pt;
            height: 40pt;
        }
        
        /* Signatures */
        .signature {
            position: absolute;
            bottom: 65pt;
            left: 10pt;
            width: 70pt;
            text-align: center;
        }
        
        .signature img {
            max-width: 100%;
            height: 20pt;
        }
        
        .signature-line {
            width: 100%;
            border-bottom: 0.5pt solid black;
            margin-bottom: 2pt;
        }
        
        .signature-label {
            font-size: 5pt;
            text-align: center;
        }
        
        /* Back side styling */
        .back-header {
            text-align: center;
            margin-bottom: 5pt;
            border-bottom: 1pt solid #072d6b;
            padding-bottom: 5pt;
        }
        
        .back-header h2 {
            font-size: 6pt;
            margin: 0;
            font-weight: normal;
        }
        
        .back-header h1 {
            font-size: 9pt;
            font-weight: bold;
            margin: 2pt 0 0;
            color: #072d6b;
        }
        
        .emergency-contact {
            border: 0.5pt solid #ddd;
            border-radius: 3pt;
            padding: 5pt;
            margin-bottom: 10pt;
        }
        
        .emergency-title {
            text-align: center;
            font-size: 7pt;
            font-weight: bold;
            margin-bottom: 3pt;
        }
        
        .warning {
            font-size: 5pt;
            font-weight: bold;
            margin-bottom: 1pt;
        }
        
        .contact-info {
            font-size: 5pt;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="id-card">
        <div class="id-card-front">
            <!-- Header -->
            <div class="header">
                <!-- Replace with actual logo path -->
                <img src="{{ public_path('images/barangay-logo.png') }}" class="logo">
                <div class="header-text">
                    <h2>Republic of the Philippines</h2>
                    <h1>BARANGAY LUMANGLIPA</h1>
                    <h2>Luzon, Philippines</h2>
                </div>
            </div>
            
            <!-- Card Title -->
            <div class="card-title">
                RESIDENT IDENTIFICATION CARD
            </div>
            
            <!-- Card Body -->
            <div class="card-body">
                <!-- Photo -->
                <div class="photo-section">
                    @if($resident->photo)
                        <img src="{{ public_path('storage/residents/photos/' . $resident->photo) }}" class="photo">
                    @endif
                </div>
                
                <!-- Details -->
                <div class="details">
                    <div class="field">
                        <span class="field-label">ID No:</span>
                        {{ $resident->barangay_id }}
                    </div>
                    
                    <div class="field">
                        <span class="field-label">Name:</span>
                        {{ strtoupper($resident->full_name) }}
                    </div>
                    
                    <div class="field">
                        <span class="field-label">Address:</span>
                        {{ $resident->address }}
                    </div>
                    
                    <div class="field">
                        <span class="field-label">Date of Birth:</span>
                        {{ $resident->birthdate ? $resident->birthdate->format('M d, Y') : 'N/A' }}
                    </div>
                    
                    <div class="field-row">
                        <div class="field field-half">
                            <span class="field-label">Gender:</span>
                            {{ $resident->sex }}
                        </div>
                        
                        <div class="field field-half">
                            <span class="field-label">Civil Status:</span>
                            {{ $resident->civil_status }}
                        </div>
                    </div>
                    
                    <div class="field">
                        <span class="field-label">Contact No:</span>
                        {{ $resident->contact_number ?: 'N/A' }}
                    </div>
                </div>
            </div>
            
            <!-- Signature -->
            <div class="signature">
                @if($resident->signature)
                    <img src="{{ public_path('storage/residents/signatures/' . $resident->signature) }}">
                @else
                    <div class="signature-line"></div>
                @endif
                <div class="signature-label">Resident's Signature</div>
            </div>
            
            <!-- QR Code -->
            <div class="qr-code">
                <img src="data:image/png;base64,{{ $qrCode }}" width="100%">
            </div>
            
            <!-- Footer with validity -->
            <div class="card-footer">
                <div class="field">
                    <span class="field-label">Date Issued:</span>
                    {{ $resident->id_issued_at ? $resident->id_issued_at->format('m/d/Y') : 'N/A' }}
                </div>
                
                <div class="field">
                    <span class="field-label">Valid Until:</span>
                    {{ $resident->id_expires_at ? $resident->id_expires_at->format('m/d/Y') : 'N/A' }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>