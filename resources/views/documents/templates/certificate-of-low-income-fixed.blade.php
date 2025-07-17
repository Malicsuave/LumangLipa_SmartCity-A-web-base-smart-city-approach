<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Low Income - {{ $fullName }}</title>
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
        }
        .cert-right {
            width: 66%;
            vertical-align: top;
            padding: 18px 18px 10px 18px;
            position: relative;
        }
        .officials-title {
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
            color: #1a4fa3;
        }
        .officials-list {
            font-size: 9px;
            line-height: 1.2;
        }
        .official-name {
            font-weight: bold;
            font-size: 8px;
        }
        .official-position {
            font-size: 7px;
            color: #555;
        }
        .official-committee {
            font-size: 6px;
            color: #777;
            font-style: italic;
        }
        .note-section {
            position: absolute;
            bottom: 8px;
            left: 18px;
            right: 10px;
            font-size: 12px;
            font-weight: bold;
            color: #1a4fa3;
            margin-top: 30px;
            border-top: 1px solid #000;
            padding-top: 5px;
            width: calc(100% - 28px);
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
<body>
    <div class="container">
        <div style="text-align: center; margin-bottom: 20px;">
            <button class="print-button" onclick="window.print()">🖨️ Print Document</button>
        </div>
        
        <div class="certificate-container" id="certificate">
            <table class="cert-table">
                <tr>
                    <!-- LEFT COLUMN -->
                    <td class="cert-left">
                        <div class="officials-title">SANGGUNIANG BARANGAY</div>
                        <div class="officials-list">
                            <div class="official-name">HON. {{ $officials->captain_name }}</div>
                            <div class="official-position">Barangay Captain</div><br><br>
                            @for($i = 1; $i <= 7; $i++)
                                <div class="official-name">HON. {{ $officials->{'councilor'.$i.'_name'} }}</div>
                                <div class="official-position">Councilor</div>
                                @php $committee = $officials->{'councilor'.$i.'_committee'} ?? null; @endphp
                                @if($committee)
                                    <div class="official-committee" style="color: #007bff;">{{ $committee }}</div>
                                @endif
                                <br>
                            @endfor
                            <div class="official-name">HON. {{ $officials->sk_chairperson_name }}</div>
                            <div class="official-position">SK Chairman</div>
                            @php $sk_committee = $officials->sk_chairperson_committee ?? null; @endphp
                            @if($sk_committee)
                                <div class="official-committee" style="color: #007bff;">{{ $sk_committee }}</div>
                            @endif
                            <br>
                            <div class="official-name">{{ $officials->secretary_name }}</div>
                            <div class="official-position">Secretary</div><br>
                            <div class="official-name">{{ $officials->treasurer_name }}</div>
                            <div class="official-position">Treasurer</div>
                        </div>
                        <div class="note-section">
                            Note: Not Valid Without Official Dry Seal
                        </div>
                    </td>
                    
                    <!-- RIGHT COLUMN -->
                    <td class="cert-right">
                        <!-- LOGO TOP LEFT -->
                        <img src="/request/logo.png" alt="Barangay Logo" class="logo" style="position:absolute; top:0; left:0; margin-top:-20px; margin-left:-45px;">
                        
                        <div style="text-align:center;">
                            <div class="republic-text">
                                Republic of the Philippines<br>
                                Province of Batangas<br>
                                Municipality of Mataas na Kahoy<br>
                                <span class="barangay-title">BARANGAY LUMANGLIPA</span>
                            </div>

                            <div class="office-text">
                                OFFICE OF THE PUNONG BARANGAY
                            </div>

                            <div class="certificate-title">
                                CERTIFICATE OF LOW INCOME
                            </div>
                        </div>                        <div class="certificate-body">
                            <div class="cert-italic" style="margin-bottom: 10px; font-size:16px;"><strong>To Whom It May Concern:</strong></div>
                            <div style="text-indent: 40px;">
                                <span class="cert-bold">THIS IS TO CERTIFY that</span>
                                <span class="cert-fill">{{ strtoupper($fullName) }}</span>
                                of legal age, is a resident of Purok
                                <span class="cert-fill-short">{{ strtoupper($purok ?? 'N/A') }}</span>
                                Barangay Lumanglipa, Mataas na Kahoy, Batangas.
                            </div>
                            <div style="text-indent: 40px; margin-top: 18px;">
                                This further certifies that the above-named individual has a monthly income of
                                <span class="cert-fill-short">{{ strtoupper($income ?? 'N/A') }}</span>
                                on his nowadays as
                                <span class="cert-fill-short">{{ strtoupper($occupation ?? 'N/A') }}</span>.
                            </div>
                            <div style="text-indent: 40px; margin-top: 18px;">
                                This certification is issued this
                                <span class="cert-fill-day">{{ \Carbon\Carbon::parse($dateIssued)->format('j') }}</span> day of
                                <span class="cert-fill">{{ \Carbon\Carbon::parse($dateIssued)->format('F') }}</span>
                                <span class="cert-fill-year">{{ \Carbon\Carbon::parse($dateIssued)->format('Y') }}</span>
                                at Barangay Lumanglipa, Mataas na Kahoy, Batangas for whatever legal purposes it may serve.
                            </div>
                        </div>                        <div style="margin-top: 50px; font-size: 16px; text-align: center;">
                            <div style="margin-left:300px; display:inline-block; text-align:center;">
                                <br><br><br><br><br>
                                <span class="signature-line" style="width:180px;"></span><br>
                                <span class="cert-bold">{{ $officials->captain_name }}</span><br>
                                <span class="cert-italic">Punong Barangay</span>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
