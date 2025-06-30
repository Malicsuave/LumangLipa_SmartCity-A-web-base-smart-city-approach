<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Permit - {{ $fullName }}</title>
    @php
        $isPrintMode = $isPrintMode ?? false;
    @endphp
    <style>
        body {
            font-family: Arial, sans-serif;
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
            font-family: 'Times New Roman', Times, serif;
            margin: 0 auto;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            box-sizing: border-box;
            overflow: hidden;
        }
        .cert-table {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .cert-left {
            width: 34%;
            border-right: 2px solid #000;
            vertical-align: top;
            padding: 18px 10px 10px 18px;
            position: relative;
            padding-bottom: 0 !important;
        }
        .cert-right {
            width: 66%;
            vertical-align: top;
            padding: 10px 30px 10px 30px;
            position: relative;
            height: 100%;
            background: none;
            padding-bottom: 0 !important;
        }
        .cert-right::before {
            content: "";
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 700px;
            height: 700px;
            background: url('{{ asset("request/kahoylogo.png") }}') no-repeat center center;
            background-size: contain;
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
            font-size: 14px;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        .officials-list {
            font-size: 13px;
            line-height: 1.2;
        }
        .official-name {
            font-weight: bold;
            font-size: 13px;
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
        .note-section {
            font-size: 12px;
            font-weight: bold;
            color: #1a4fa3;
            margin-top: 30px;
            border-top: 1px solid #000;
            padding-top: 5px;
            width: 100%;
            position: absolute;
            left: 0;
            right: 0;
            bottom: 220px;
        }
        .logo {
            width: 130px;
            height: 130px;
            margin-bottom: 5px;
        }
        .republic-text {
            font-size: 14px;
            font-family: Arial, sans-serif;
            line-height: 1.1;
        }
        .barangay-title {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .office-text {
            font-size: 14px;
            font-family: Arial, sans-serif;
            color: #1a4fa3;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .certificate-title {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin: 18px 0 18px 0;
            font-style: italic;
            color: #000;
            text-decoration: underline;
            letter-spacing: 1px;
            text-shadow: 1px 1px 0 #1a4fa3;
        }
        .certificate-body {
            font-size: 16px;
            line-height: 1.7;
            text-align: left;
            margin: 20px 0 0 0;
            color: #000;
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
            min-width: 60px;
            border-bottom: 1px solid #000;
            padding: 0 5px;
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
        .signature-line {
            border-bottom: 2px solid #000;
            width: 220px;
            margin: 30px 0 5px 0;
            height: 0;
            display: block;
            margin-bottom: 0 !important;
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
                size: 8.5in 11in;
                margin: 0.25in 0.25in 0.5in 0.7in;
            }
            body, html {
                width: 8.5in !important;
                height: 11in !important;
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
                max-width: none;
            }
            .print-button {
                display: none !important;
            }
            .certificate-container {
                width: 816px !important;
                height: 1056px !important;
                margin: 0 auto !important;
                padding: 0 !important;
                border: 3px solid #000 !important;
                overflow: hidden !important;
                background: white !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body>    <div class="container">
        @if($isPrintMode)
        <div style="text-align: center; margin-bottom: 20px;">
            <button class="print-button" onclick="window.print()">🖨️ Print Document</button>
        </div>
        @endif
        
        <div class="certificate-container" id="certificate">
            <table class="cert-table" style="height:100%;">
                <tr>
                    <!-- LEFT COLUMN -->
                    <td class="cert-left" style="vertical-align: top; position: relative; padding-bottom: 0;">
                        <div>
                            <div class="officials-title">SANGGUNIANG BARANGAY</div>
                            <br><br>
                            <div class="officials-list">
                                <div class="official-name">HON. NOVILITO M. MANALO</div>
                                <div class="official-position">Barangay Captain</div><br><br>
                                
                                <div class="official-name">HON. ROLDAN A. ROSITA</div>
                                <div class="official-position">Councilor</div>
                                <div class="official-committee">Committee on Agriculture, Cooperative, Trade, Commerce and Industry</div><br>
                                
                                <div class="official-name">HON. LEXTER D. MAQUINTO</div>
                                <div class="official-position">Councilor</div>
                                <div class="official-committee">Committee on Family & Human Rights</div><br>
                                
                                <div class="official-name">HON. RICHARD C. CANOSA</div>
                                <div class="official-position">Councilor</div>
                                <div class="official-committee">Committee on Peace & Order, Transportation and Communication</div><br>
                                
                                <div class="official-name">HON. RODOLFO U. MANALO JR</div>
                                <div class="official-position">Councilor</div>
                                <div class="official-committee">Committee on Public Works & Beautification and Environmental Protection</div><br>
                                
                                <div class="official-name">HON. ROSENDO T. BABADILLA</div>
                                <div class="official-position">Councilor</div>
                                <div class="official-committee">Committee on Finance, Ways and Means Laws, Rules and Ordinances</div><br>
                                
                                <div class="official-name">HON. JAIME C. LAQUI</div>
                                <div class="official-position">Councilor</div>
                                <div class="official-committee">Committee on Health and Social Welfare Services</div><br>
                                
                                <div class="official-name">HON. RECHEL R. CIRUELAS</div>
                                <div class="official-position">Councilor</div>
                                <div class="official-committee">Committee on Education and Culture</div><br>
                                
                                <div class="official-name">HON. JOHN MARCO C. ARRIOLA</div>
                                <div class="official-position">SK Chairman</div>
                                <div class="official-committee">Committee on Sports and Youth</div><br>
                                
                                <div class="official-name">APRIL JANE J. SISCAR</div>
                                <div class="official-position">Secretary</div><br>
                                
                                <div class="official-name">JOSEPHINE R. QUISTO</div>
                                <div class="official-position">Treasurer</div>
                            </div>
                        </div>
                        <div class="note-section">
                            Note: Not Valid Without Official Dry Seal
                        </div>
                    </td>
                    <!-- RIGHT COLUMN -->
                    <td class="cert-right" style="vertical-align: top; position: relative; padding-bottom: 0;">
                        <table style="width:100%; border-collapse:collapse;">
                            <tr>
                                <td colspan="2" style="position:relative; padding-bottom:0;">
                                    <!-- LOGO TOP LEFT -->
                                    <img src="{{ asset('request/logo.png') }}" alt="Barangay Logo" class="logo" style="position:absolute; top:0; left:0; margin-top:-20px; margin-left:-45px;">
                                    <div style="text-align:center;">
                                        <div class="republic-text">
                                            Republic of the Philippines<br>
                                            Province of Batangas<br>
                                            Municipality of Mataas na Kahoy<br>
                                            <span class="barangay-title">BARANGAY LUMANGLIPA</span>
                                        </div>

                                        <br><br>

                                        <div class="office-text">
                                            OFFICE OF THE BARANGAY CAPTAIN
                                        </div>

                                        <br>

                                        <div class="certificate-title" style="margin-bottom:10px;">
                                            BUSINESS PERMIT
                                        </div>

                                        <br><br>

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding-top:0;">
                                    <div class="certificate-body" style="margin-top:0;">
                                        <div class="cert-italic" style="margin-bottom: 10px; font-size:16px;"><strong>To Whom It May Concern:</strong></div>
                                        <div style="text-indent: 40px;">
                                            <br>
                                            <span class="cert-bold">THIS IS TO CERTIFY that</span>
                                            <span class="cert-fill">{{ strtoupper($fullName) }}</span>
                                            is hereby permitted to operate a business named
                                            <span class="cert-fill">{{ strtoupper($businessName) }}</span>
                                            located at
                                            <span class="cert-fill">{{ strtoupper($businessAddress) }}</span>
                                            Barangay Lumanglipa, Mataas na Kahoy, Batangas.
                                        </div>
                                        <div style="text-indent: 40px; margin-top: 18px;">
                                            This permit is issued in compliance with the Local Government Code and subject to all applicable laws, ordinances, and regulations.
                                        </div>
                                        <div style="text-indent: 40px; margin-top: 18px;">
                                            Issued this
                                            <span class="cert-fill-day">{{ \Carbon\Carbon::parse($dateIssued)->format('j') }}</span> day of
                                            <span class="cert-fill-month">{{ \Carbon\Carbon::parse($dateIssued)->format('F') }}</span>,
                                            <span class="cert-fill-year">{{ \Carbon\Carbon::parse($dateIssued)->format('Y') }}</span> at Barangay Lumanglipa, Mataas na Kahoy, Batangas for business purposes only.
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding:0; vertical-align:bottom; height:120px;">
                                    <table style="width:100%; border-collapse:collapse;">
                                        <tr>
                                            <td colspan="2" style="text-align:center; padding-bottom:10px;">
                                                <div style="margin-left:300px; display:inline-block; text-align:center;">
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <br>
                                                    <span class="signature-line" style="width:180px;"></span><br>
                                                    <span class="cert-bold">HON. NOVILITO M. MANALO</span><br>
                                                    <span class="cert-italic">Barangay Captain</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <br><br><br>
                        
                        <div style="margin-top: 3px; font-size: 16px;">
                            Applicant Signature: <span class="signature-line" style="width:220px;"></span>
                        </div>
                        <div style="margin-top: 10px; font-size: 16px;">
                            Issued by: <span class="signature-line" style="width:220px;"></span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
