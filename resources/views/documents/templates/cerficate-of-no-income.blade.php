<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of No/Low Income - {{ $fullName }}</title>
    @php
        $isPrintMode = $isPrintMode ?? false;
        $watermarkUrl = asset('images/kahoylogo.png');
    @endphp
    <style>
        @font-face {
            font-family: 'Liberation Serif';
            src: url('{{ public_path('fonts/LiberationSerif-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Liberation Serif';
            src: url('{{ public_path('fonts/LiberationSerif-Bold.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        @font-face {
            font-family: 'Liberation Serif';
            src: url('{{ public_path('fonts/LiberationSerif-Italic.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: italic;
        }
        @font-face {
            font-family: 'Liberation Serif';
            src: url('{{ public_path('fonts/LiberationSerif-BoldItalic.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: italic;
        }
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 20px;
            background: #f5f5f5;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .certificate-container {
            border: 3px solid #000;
            background: white;
            width: 816px;
            height: 1056px;
            font-family: 'Times New Roman', 'Liberation Serif', Times, serif !important;
            margin: 0 auto;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            box-sizing: border-box;
            overflow: hidden;
        }
        .certificate-container * {
            font-family: 'Times New Roman', 'Liberation Serif', Times, serif !important;
        }
        .cert-table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-family: 'Times New Roman', 'Liberation Serif', Times, serif !important;
        }
        .cert-left {
            width: 34%;
            border-right: 4px solid #000;
            vertical-align: top;
            padding: 18px 15px 10px 18px;
            position: relative;
            padding-bottom: 0 !important;
        }
        .cert-right {
            width: 66%;
            vertical-align: top;
            padding: 10px 25px 10px 25px;
            position: relative;
            height: 100%;
            background: none;
            padding-bottom: 0 !important;
        }
        
        /* PDF-safe watermark centering */
        .cert-right::before {
            content: "";
            position: absolute;
            top: 50%; 
            left: 50%;
            margin-top: -225px;
            margin-left: -225px;
            width: 450px;
            height: 450px;
            background: url('{{ $watermarkUrl }}') no-repeat center center;
            background-size: contain;
            background-position: center center;
            opacity: 0.08;
            z-index: 0;
            pointer-events: none;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .cert-right > * {
            position: relative;
            z-index: 1;
        }

        .officials-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }
        .officials-list {
            font-size: 13px;
            line-height: 1.2;
        }
        .official-name {
            font-weight: bold;
            font-size: 15px;
            margin-top: 14px;
        }
        .official-position {
            font-size: 12px;
            margin-left: 8px;
        }
        .official-committee {
            font-size: 12px;
            color: #1a4fa3;
            margin-left: 8px;
        }
        .logo {
            width: 130px;
            height: 130px;
            margin-bottom: 5px;
        }
        .republic-text {
            font-size: 14px;
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.1;
            margin-bottom: 15px;
        }
        .barangay-title {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .office-text {
            font-size: 14px;
            font-family: Arial, Helvetica, sans-serif;
            color: #1a4fa3;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .certificate-title {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin: 18px 0 10px 0;
            font-style: italic;
            color: #000;
            text-decoration: underline;
            letter-spacing: 1px;
            text-shadow: 1px 1px 0 #1a4fa3;
        }

        .note-section {
            font-size: 12px;
            font-weight: bold;
            color: #1a4fa3;
            margin-top: 30px;
            border-top: 1px solid #000;
            padding-top: 5px;
            width: 100%;
            box-sizing: border-box;
        }
        .certificate-body {
            font-size: 16px;
            line-height: 1.6;
            text-align: left;
            margin: 15px 0 0 0;
            color: #000;
            font-family: 'Times New Roman', 'Liberation Serif', Times, serif !important;
        }
        .cert-bold {
            font-weight: bold;
        }
        .cert-italic {
            font-style: italic;
        }
        .cert-fill {
            display: inline-block;
            min-width: 180px;
            border-bottom: 1px solid #000;
            padding: 0 5px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .cert-fill-short {
            display: inline-block;
            min-width: 80px;
            border-bottom: 1px solid #000;
            padding: 0 5px;
            text-align: center;
        }
        .cert-fill-purok {
            display: inline-block;
            min-width: 80px;
            border-bottom: 1px solid #000;
            padding: 0 5px;
            text-align: center;
        }
        .cert-fill-day {
            display: inline-block;
            min-width: 40px;
            border-bottom: 1px solid #000;
            padding: 0 5px;
            text-align: center;
        }
        .cert-fill-month {
            display: inline-block;
            min-width: 110px;
            border-bottom: 1px solid #000;
            padding: 0 5px;
            text-align: center;
        }
        .cert-fill-year {
            display: inline-block;
            min-width: 60px;
            border-bottom: 1px solid #000;
            padding: 0 5px;
            text-align: center;
        }
        .print-button {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin: 20px 0;
            font-size: 16px;
        }
        .print-button:hover {
            background: #0056b3;
        }
        @media print {
            @page {
                size: letter;
                margin: 0;
            }
            body, html {
                margin: 0;
                padding: 0;
                background: white !important;
            }
            .container {
                box-shadow: none !important;
                border-radius: 0 !important;
                padding: 0 !important;
                margin: 0 !important;
                background: white !important;
                width: 100% !important;
                height: 100% !important;
                box-sizing: border-box !important;
                display: block !important;
            }
            .print-button {
                display: none !important;
            }
            .certificate-container {
                width: 100% !important;
                max-width: 100% !important;
                height: 100% !important;
                margin: 0 auto !important;
                padding: 0 !important;
                border: 3px solid #000 !important;
                overflow: hidden !important;
                background: white !important;
                box-shadow: none !important;
                page-break-inside: avoid !important;
                page-break-after: avoid !important;
                box-sizing: border-box !important;
            }
            .cert-right::before {
                width: 450px !important;
                height: 450px !important;
                top: 50% !important;
                opacity: 0.08 !important;
            }
        }
    </style>
</head>
<body>    
    <div class="container">
        @if($isPrintMode)
        <div style="text-align: center; margin-bottom: 20px;">
            <button class="print-button" onclick="window.print()">🖨️ Print Document</button>
        </div>
        @endif
          <div class="certificate-container" id="certificate">
            <table class="cert-table" style="height:100%;">
                <tr>
                    <td class="cert-left" style="vertical-align: top; position: relative; padding-bottom: 0;">
                        <div style="display: flex; flex-direction: column; height: 100%; justify-content: space-between;">
                            <div>
                                <div class="officials-title">SANGGUNIANG BARANGAY</div>
                                <div class="officials-list">
                                    <div class="official-name">HON. {{ $officials->captain_name }}</div>
                                    <div class="official-position">Barangay Captain</div>
                                    @for($i = 1; $i <= 6; $i++)
                                        <div class="official-name">HON. {{ $officials->{'councilor'.$i.'_name'} }}</div>
                                        <div class="official-position">Councilor</div>
                                        @php $committee = $officials->{'councilor'.$i.'_committee'} ?? null; @endphp
                                        @if($committee)
                                            <div class="official-committee" style="color: #007bff;">{{ $committee }}</div>
                                        @endif
                                    @endfor
                                    <div class="official-name">HON. {{ $officials->sk_chairperson_name }}</div>
                                    <div class="official-position">SK Chairman</div>
                                    @php $sk_committee = $officials->sk_chairperson_committee ?? null; @endphp
                                    @if($sk_committee)
                                        <div class="official-committee" style="color: #007bff;">{{ $sk_committee }}</div>
                                    @endif
                                    <div class="official-name">{{ $officials->secretary_name }}</div>
                                    <div class="official-position">Secretary</div>
                                    <div class="official-name">{{ $officials->treasurer_name }}</div>
                                    <div class="official-position">Treasurer</div>
                                </div>
                            </div>
                            <div style="margin-top: auto; padding-top: 30px; padding-bottom: 20px; margin-left: -18px; margin-right: -15px;">
                                <div class="note-section" style="border-top:2px solid #000; padding-top:4px; font-size:13px; color:#1a4fa3; margin-bottom: 15px; padding-left: 18px; padding-right: 15px; margin-right: 0;">
                                    Note: Not Valid Without Official Dry Seal
                                </div>
                                @if(isset($qrCode))
                                    <div style="text-align:center; margin-top: 20px; margin-bottom: 20px;">
                                        <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" width="170" height="170" style="display:block; margin:0 auto; border:2px solid #1a4fa3; background:#fff; padding:4px; border-radius:8px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>
                      <td class="cert-right" style="vertical-align: top; position: relative; padding-bottom: 0;">
                        <table style="width:100%; border-collapse:collapse; height: 100%;">
                        <tr>
                                <td colspan="2" style="position:relative; padding-bottom:0; vertical-align: top;">
                                    <img src="{{ asset('images/logo.png') }}" alt="Barangay Logo" class="logo" style="position:absolute; top:0; left:-35px; margin-top:-20px; margin-left:0;">
                                    
                                    <div style="text-align:center; margin-top: 20px;">
                                        <div class="republic-text" style="margin-bottom: 15px;">
                                            Republic of the Philippines<br>
                                            Province of Batangas<br>
                                            Municipality of Mataasnakahoy<br>
                                            <span class="barangay-title">BARANGAY LUMANGLIPA</span>
                                        </div>
                                        
                                        <div class="office-text" style="color:#1a4fa3; margin-bottom: 50px;">
                                            OFFICE OF THE PUNONG BARANGAY
                                        </div>

                                        <div class="certificate-title" style="margin-bottom:-30px;">
                                            CERTIFICATE OF NO/LOW INCOME
                                        </div>
                                        
                                        <br> 
                                    </div>
                                </td>
                            </tr>
                          <tr>
                                <td colspan="2" style="padding-top:0; vertical-align: top;">
                                    <div class="certificate-body" style="margin-top:0;">
                                        <div class="cert-italic" style="margin-bottom: 10px; font-size:16px;"><strong>To Whom It May Concern:</strong></div>
                                        
                                        <div style="text-indent: 40px;">
                                            <span class="cert-bold">THIS IS TO CERTIFY that</span>
                                            <span class="cert-fill">{{ strtoupper($fullName) }}</span>
                                            of legal age, is a resident of Barangay Lumanglipa, Mataas na Kahoy, Batangas.
                                        </div>
                                        <div style="text-indent: 40px; margin-top: 18px;">
                                            This further certifies that the above-named individual is one of the <span class="cert-italic">indigents</span> in our barangay and has receive of <span class="cert-italic">No Income</span>.
                                        </div>
                                        <div style="text-indent: 40px; margin-top: 18px;">
                                            This certification is issued this
                                            <span class="cert-fill-day">{{ \Carbon\Carbon::parse($dateIssued)->format('j') }}</span> day of
                                            <span class="cert-fill-month">{{ \Carbon\Carbon::parse($dateIssued)->format('F') }}</span>,
                                            <span class="cert-fill-year">{{ \Carbon\Carbon::parse($dateIssued)->format('Y') }}</span> at Barangay Lumanglipa, Mataas na Kahoy, Batangas for whatever legal purposes it may serve.
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding:0; vertical-align:bottom;">
                                    <table style="width:100%; border-collapse:collapse;">
                                        <tr>
                                            <td colspan="2" style="text-align:center; padding-bottom:10px; padding-top:40px;">
                                                <div style="margin-left:250px; display:inline-block; text-align:center;">
                                                    <span class="cert-bold">{{ $officials->captain_name }}</span><br>
                                                    <span class="cert-italic">Punong Barangay</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="padding: 100px 10px 20px 0;">
                                                <div style="font-size:14px; margin-bottom:20px;">
                                                    Applicant Signature over Printed Name: 
                                                    <span style="display:inline-block; width:200px; border-bottom:1px solid #000; margin-left:5px; vertical-align:baseline;"></span>
                                                </div>
                                               
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
