<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Clearance - <?php echo e($fullName); ?></title>
    <?php
        $isPrintMode = $isPrintMode ?? false;
    ?>
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
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .certificate-container {
            border: 2px solid #000;
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
            position: relative;
            transform-origin: center center;
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
            padding: 18px 15px 25px 18px;
            position: relative;
            padding-bottom: 25px !important;
        }
        .cert-right {
            width: 66%;
            vertical-align: top;
            padding: 10px 25px 25px 25px;
            position: relative;
            height: 100%;
            background: none;
            padding-bottom: 25px !important;
        }
        .cert-right::before {
            content: "";
            position: absolute;
            top: 50%; 
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            height: 300px;
            background: url('<?php echo e('file://' . public_path('images/kahoylogo.png')); ?>') no-repeat center center;
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
            font-size: 14px;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        .officials-list {
            font-size: 13px;
            line-height: 1.2;
            margin-bottom: 20px;
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
            margin-top: 15px;
            border-top: 1px solid #000;
            padding-top: 5px;
            width: 100%;
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
            margin: 0 0 5px 0;
            height: 0;
            display: inline-block;
            vertical-align: baseline;
        }
        .signature-section {
            margin-top: auto;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .signature-row {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        .signature-label {
            display: inline-block;
            width: 180px;
            margin-right: 5px;
            white-space: nowrap;
            vertical-align: baseline;
        }
        .signature-item:last-child .signature-label {
            margin-right: 2px;
        }
        .signature-container {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
            font-size: 16px;
        }
        .signature-item {
            display: flex;
            align-items: baseline;
            margin-bottom: 15px;
        }
        .signature-item:last-child {
            margin-bottom: 0;
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
        .print-button.hidden {
            display: none !important;
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
                padding: 0.05in !important; /* reduced from 0.2in */
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
                margin: 0 !important;
                padding: 0 !important;
                border: 2px solid #000 !important;
                overflow: hidden !important;
                background: white !important;
                box-shadow: none !important;
                page-break-inside: avoid !important;
                page-break-after: avoid !important;
                box-sizing: border-box !important;
            }
            .cert-right::before {
                width: 250px !important;
                height: 250px !important;
                top: 45% !important;
                opacity: 0.06 !important;
            }
        }
    </style>
</head>
<body>    <div class="container">        <?php if($isPrintMode): ?>
        <div style="text-align: center; margin-bottom: 20px;">
            <button class="print-button" onclick="window.print()">🖨️ Print Document</button>
        </div>
        <?php endif; ?>
        
        <div class="certificate-container" id="certificate">
            <table class="cert-table" style="height:100%;">
                <tr>
                    <!-- LEFT COLUMN -->
                    <td class="cert-left" style="vertical-align: top; position: relative; padding-bottom: 0;">
                        <div style="height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <div class="officials-title">SANGGUNIANG BARANGAY</div>
                                <br><br>
                                <div class="officials-list">
                                    <div class="official-name">HON. <?php echo e($officials->captain_name); ?></div>
                                    <div class="official-position">Barangay Captain</div><br><br>
                                    <?php for($i = 1; $i <= 7; $i++): ?>
                                        <div class="official-name">HON. <?php echo e($officials->{'councilor'.$i.'_name'}); ?></div>
                                        <div class="official-position">Councilor</div>
                                        <?php $committee = $officials->{'councilor'.$i.'_committee'} ?? null; ?>
                                        <?php if($committee): ?>
                                            <div class="official-committee" style="color: #007bff;"><?php echo e($committee); ?></div>
                                        <?php endif; ?>
                                        <br>
                                    <?php endfor; ?>
                                    <div class="official-name">HON. <?php echo e($officials->sk_chairperson_name); ?></div>
                                    <div class="official-position">SK Chairman</div>
                                    <?php $sk_committee = $officials->sk_chairperson_committee ?? null; ?>
                                    <?php if($sk_committee): ?>
                                        <div class="official-committee" style="color: #007bff;"><?php echo e($sk_committee); ?></div>
                                    <?php endif; ?>
                                    <br>
                                    <div class="official-name"><?php echo e($officials->secretary_name); ?></div>
                                    <div class="official-position">Secretary</div><br>
                                    <div class="official-name"><?php echo e($officials->treasurer_name); ?></div>
                                    <div class="official-position">Treasurer</div>
                                </div>
                            </div>
                            <div style="margin-top: auto; padding-top: 10px;">
                                <div class="note-section" style="border-top:1px solid #000; padding-top:4px; font-size:13px; color:#1a4fa3; margin-bottom: 8px;">
                                    Note: Not Valid Without Official Dry Seal
                                </div>
                                <?php if(isset($qrCode)): ?>
                                    <div style="text-align:center;">
                                        <img src="data:image/png;base64,<?php echo e($qrCode); ?>" alt="QR Code" width="170" height="170" style="display:block; margin:0 auto; border:4px solid #1a4fa3; background:#fff; padding:4px; border-radius:8px;">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <!-- RIGHT COLUMN -->
                    <td class="cert-right" style="vertical-align: top; position: relative; padding-bottom: 0;">
                        <!-- Background watermark for better PDF compatibility -->
                        <img src="<?php echo e('file://' . public_path('images/kahoylogo.png')); ?>" alt="Watermark" style="position: absolute; top: 55%; left: 50%; transform: translate(-50%, -50%); width: 520px; height: 520px; opacity: 0.08; z-index: 0; pointer-events: none;">
                        <div style="height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <table style="width:100%; border-collapse:collapse; position: relative; z-index: 1;">
                                    <tr>
                                        <td colspan="2" style="position:relative; padding-bottom:0;">
                                            <!-- LOGO TOP LEFT -->
                                            <img src="<?php echo e('file://' . public_path('images/logo.png')); ?>" alt="Barangay Logo" class="logo" style="position:absolute; top:0; left:8px; margin-top:-20px; margin-left:0;"> <!-- moved left to 8px for better balance -->
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
                                                    BARANGAY CLEARANCE
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
                                                    <span class="cert-fill"><?php echo e(strtoupper($fullName)); ?></span>
                                                    is a bona fide resident of Barangay Lumanglipa, Mataas na Kahoy, Batangas. He/She is known to me to be a person of good moral character and law-abiding citizen.
                                                </div>
                                                <div style="text-indent: 40px; margin-top: 18px;">
                                                    This further certifies that he/she has never been connected nor committed in any subversive organization that seeks to overthrow our government.
                                                </div>
                                                <div style="text-indent: 40px; margin-top: 18px;">
                                                    Issued upon request of
                                                    <span class="cert-fill-short"><?php echo e(strtoupper($purpose)); ?></span>
                                                    this
                                                    <span class="cert-fill-day"><?php echo e(\Carbon\Carbon::parse($dateIssued)->format('j')); ?></span> day of
                                                    <span class="cert-fill-month"><?php echo e(\Carbon\Carbon::parse($dateIssued)->format('F')); ?></span>,
                                                    <span class="cert-fill-year"><?php echo e(\Carbon\Carbon::parse($dateIssued)->format('Y')); ?></span> at Barangay Lumanglipa, Mataas na Kahoy, Batangas for any legal purposes it may serve.
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
                                                            <br><br><br><br><br>
                                                            <div style="font-family:Arial, sans-serif; font-weight:bold; font-size:16px; text-transform:uppercase; margin-bottom:5px;">HON. <?php echo e($officials->captain_name); ?></div>
                                                            <div style="font-family:Arial, sans-serif; font-style:italic; font-size:14px;">Barangay Captain</div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            
                            <div class="signature-section">
                                <div class="signature-container">
                                    <div class="signature-item">
                                        <span class="signature-label">Applicant Signature:</span>
                                        <span class="signature-line"></span>
                                    </div>
                                    <div class="signature-item">
                                        <span class="signature-label">Issued by:</span>
                                        <span class="signature-line"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
<?php /**PATH /var/www/html/lumanglipa/resources/views/documents/templates/barangay-clearance.blade.php ENDPATH**/ ?>