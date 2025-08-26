<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1000, initial-scale=1.0">
    <title>Certificate of Low Income - {{ $fullName }}</title>
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
            /* Short bond paper: 8.5 x 11 inches = 816 x 1056px at 96dpi */
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
        }        .cert-right::before {
            content: "";
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 500px;
            height: 500px;
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
        .certificate-body p {
            margin-bottom: 18px;
        }
        .cert-bold {
            font-weight: bold;
        }
        .cert-italic {
            font-style: italic;
        }
        .cert-underline {
            text-decoration: underline;
        }        .cert-fill {
            display: inline-block;
            min-width: 180px;
            border-bottom: 1px solid #000;
            padding: 0 5px;
        }
        .cert-fill-short {
            display: inline-block;
            min-width: 80px;
            border-bottom: 1px solid #000;
            padding: 0 5px;
        }
        .cert-fill-day {
            display: inline-block;
            min-width: 40px;
            border-bottom: 1px solid #000;
            padding: 0 5px;
        }
        .cert-fill-year {
            display: inline-block;
            min-width: 60px;
            border-bottom: 1px solid #000;
            padding: 0 5px;
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
                    <td class="cert-left" style="vertical-align: top; position: relative; padding-bottom: 0;">                        <div>
                            <div class="officials-title">SANGGUNIANG BARANGAY</div>
                            <br><br>
                            <div class="officials-list">
                                <div class="official-name">HON. {{ $officials->captain_name }}</div>
                                <div class="official-position">Barangay Captain</div><br><br>
                                @for($i = 1; $i <= 7; $i++)
                                    <div class="official-name">HON. {{ $officials->{'councilor'.$i.'_name'} }}</div>
                                    <div class="official-position">Councilor</div>
                                    @php $committee = $officials->{'councilor'.$i.'_committee'} ?? null; @endphp
                                    @if($committee)
                                        <div class="official-committee">{{ $committee }}</div>
                                    @endif
                                    <br>
                                @endfor
                                <div class="official-name">HON. {{ $officials->sk_chairperson_name }}</div>
                                <div class="official-position">SK Chairman</div>
                                @php $sk_committee = $officials->sk_chairperson_committee ?? null; @endphp
                                @if($sk_committee)
                                    <div class="official-committee">{{ $sk_committee }}</div>
                                @endif
                                <br>
                                <div class="official-name">{{ $officials->secretary_name }}</div>
                                <div class="official-position">Secretary</div><br>
                                <div class="official-name">{{ $officials->treasurer_name }}</div>
                                <div class="official-position">Treasurer</div>
                            </div>
                        </div>
                        <div style="position: absolute; left: 0; right: 0; bottom: 0; padding: 12px 0 10px 0;">
                            <div class="note-section" style="border-top:1px solid #000; padding-top:4px; font-size:13px; color:#1a4fa3; margin-bottom: 8px;">
                                Note: Not Valid Without Official Dry Seal
                            </div>
                            @if(isset($qrCode))
                                <div style="text-align:center;">
                                    <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" width="170" height="170" style="display:block; margin:0 auto; border:2px solid #1a4fa3; background:#fff; padding:4px; border-radius:8px;">
                                </div>
                            @endif
                        </div>
                    </td>
                    <!-- RIGHT COLUMN -->
                    <td class="cert-right" style="vertical-align: top; position: relative; padding-bottom: 0;">
                        <table style="width:100%; border-collapse:collapse;">
                            <tr>
                                <td colspan="2" style="position:relative; padding-bottom:0;">                                    <!-- LOGO TOP LEFT -->
                                    <img src="{{ asset('request/kahoylogo.png') }}" alt="Barangay Logo" style="width:90px; position:absolute; margin-left:-25px; margin-top:-5px;">
                                    <div style="text-align:center;">
                                        <div class="republic-text">
                                            Republic of the Philippines<br>
                                            Province of Batangas<br>
                                            Municipality of Mataas na Kahoy<br>
                                            <span class="barangay-title">BARANGAY LUMANGLIPA</span>
                                        </div>

                                        <br>
                                        <br>
                                        
                                        <div class="office-text" style="color:#1a4fa3;">
                                            OFFICE OF THE BARANGAY CAPTAIN
                                        </div>

                                        <br>
                                        
                                        <div class="certificate-title" style="margin-bottom:10px;">
                                            CERTIFICATE OF LOW INCOME
                                        </div>

                                        <br>
                                        <br>

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="padding-top:0;">
                                    <div class="certificate-body" style="margin-top:0;">
                                        <div class="cert-italic" style="margin-bottom: 10px; font-size:16px;"><strong>To Whom It May Concern:</strong></div>
                                        <div style="text-indent: 40px;">
                                            <br>
                                            <span class="cert-bold">THIS IS TO CERTIFY that</span>                                            <span class="cert-fill">{{ strtoupper($fullName) }}</span>
                                            of legal age, is a resident of Purok
                                            <span class="cert-fill-short">{{ $purok ?? 'N/A' }}</span>
                                            Barangay Lumanglipa, Mataas na Kahoy, Batangas.
                                        </div>                                        <div style="text-indent: 40px; margin-top: 18px;">
                                            This further certifies that the above-named individual has a monthly income of
                                            <span class="cert-fill-short">{{ strtoupper($income ?? 'N/A') }}</span>
                                            on his nowadays as
                                            <span class="cert-fill-short">{{ $occupation ?? 'N/A' }}</span>.
                                        </div>
                                        <div style="text-indent: 40px; margin-top: 18px;">
                                            This certification is issued this
                                            <span class="cert-fill-day">{{ \Carbon\Carbon::parse($dateIssued)->format('j') }}</span> day of
                                            <span class="cert-fill">{{ \Carbon\Carbon::parse($dateIssued)->format('F') }}</span>
                                            <span class="cert-fill-year">{{ \Carbon\Carbon::parse($dateIssued)->format('Y') }}</span>
                                            at Barangay Lumanglipa, Mataas na Kahoy, Batangas for whatever legal purposes it may serve.
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
                                                    <span class="signature-line" style="width:180px;"></span><br>
                                                    <span class="cert-bold">HON. NOVILITO M. MANALO</span><br>
                                                    <span class="cert-italic">Barangay Captain</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>                        </table>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        
                        <div style="margin-top: 3px; font-size: 16px;">
                            Applicant Signature over printed name: <span class="signature-line" style="width:220px;"></span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
