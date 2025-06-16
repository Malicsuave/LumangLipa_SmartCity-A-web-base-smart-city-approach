<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of No/Low Income Request</title>
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
            padding: 10px 30px 10px 30px;
            position: relative;
            height: 100%;
            background: none;
        }
        .cert-right::before {
            content: "";
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: 550px ;
            height: 550px;
            background: url('kahoylogo.png') no-repeat center center;
            background-size: contain;
            opacity: 0.08;
            z-index: 0;
            pointer-events: none;
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
        .cert-bold {
            font-weight: bold;
        }
        .cert-italic {
            font-style: italic;
        }
        .cert-fill {
            display: inline-block;
            min-width: 220px;
            border-bottom: 1px solid #000;
            padding: 0 5px;
        }
        .cert-fill-day {
            display: inline-block;
            min-width: 40px;
            border-bottom: 1px solid #000;
            padding: 0 5px;
        }
        .cert-fill-month {
            display: inline-block;
            min-width: 110px;
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
        }
        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-header h2 {
            color: #333;
            margin-bottom: 10px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .form-row {
            display: flex;
            gap: 15px;
        }
        .form-row .form-group {
            flex: 1;
        }
        .btn {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background: #0056b3;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #545b62;
        }
        .button-group {
            text-align: center;
            margin-top: 20px;
        }
        .button-group .btn {
            margin: 0 10px;
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
            .form-header, .button-group, .form-group {
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
        <div class="form-header">
            <h2>Certificate of No/Low Income Request</h2>
            <p>Fill out the form below to generate the certificate</p>
        </div>
        <form id="lowincomeForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="fullName">Full Name of Applicant:</label>
                    <input type="text" id="fullName" name="fullName" required>
                </div>
                <div class="form-group">
                    <label for="dateIssued">Date to be Issued:</label>
                    <input type="date" id="dateIssued" name="dateIssued" required>
                </div>
            </div>
            <div class="button-group">
                <button type="button" class="btn" onclick="generateCertificate()">Generate Certificate</button>
                <button type="button" class="btn btn-secondary" onclick="printCertificate()">Print Certificate</button>
                <button type="reset" class="btn btn-secondary">Clear Form</button>
            </div>
            <br><br>
        </form>
        <div class="certificate-container" id="certificate" style="display:none;">
            <table class="cert-table" style="height:100%;">
                <tr>
                    <!-- LEFT COLUMN -->
                    <td class="cert-left" style="vertical-align: top; position: relative; padding-bottom: 0;">
                        <div>
                            <div class="officials-title">SANGGUNIANG BARANGAY</div>
                            <br>
                            <br>
                            <div class="officials-list">
                                <div class="official-name">HON. NOVILITO M. MANALO</div>
                                <div class="official-position">Barangay Captain</div><br>
                                <br>
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
                        <div style="position: absolute; left: 0; right: 0; bottom: 220px;">
                            <div class="note-section" style="border-top:1px solid #000; padding-top:4px; font-size:13px; color:#1a4fa3;">
                                Note: Not Valid Without Official Dry Seal
                            </div>
                        </div>
                    </td>
                    <!-- RIGHT COLUMN -->
                    <td class="cert-right" style="vertical-align: top; position: relative; padding-bottom: 0;">
    <table style="width:100%; border-collapse:collapse;">
        <tr>
            <td colspan="2" style="position:relative; padding-bottom:0;">
                <!-- LOGO TOP LEFT -->
                <img src="kahoylogo.png" alt="Barangay Logo" style="width:90px; position:absolute; margin-left:-25px; margin-top:-5px;">
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
                        CERTIFICATE OF NO/LOW INCOME
                    </div>

                    <br>
                    <br>

                </div>
            </td>
        </tr>
                                <td colspan="2" style="padding-top:0;">
                                    <div class="certificate-body" style="margin-top:0;">
                                        <div class="cert-italic" style="margin-bottom: 10px; font-size:16px;"><strong>To Whom It May Concern:</strong></div>
                                        <div style="text-indent: 40px;">
                                            <br>
                                            <span class="cert-bold">THIS IS TO CERTIFY that</span>
                                            <span class="cert-fill" id="certName">_________________________</span>
                                            of legal age, is a resident of Barangay Lumanglipa, Mataas na Kahoy, Batangas.
                                        </div>
                                        <div style="text-indent: 40px; margin-top: 18px;">
                                            This further certifies that the above-named individual is one of the <span class="cert-bold cert-italic">indigents</span> in our barangay and has receive of <span class="cert-bold cert-italic">No-Income</span>.
                                        </div>
                                        <div style="text-indent: 40px; margin-top: 18px;">
                                            This certification is issued this
                                            <span class="cert-fill-day" id="certDay">_____</span> day of
                                            <span class="cert-fill-month" id="certMonth">_____________________</span>,
                                            <span class="cert-fill-year" id="certYear">______</span> at Barangay Lumanglipa, Mataas na Kahoy, Batangas.
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
                            </tr>
                        </table>
                        <br>
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
    <script>
        function generateCertificate() {
            const fullName = document.getElementById('fullName').value;
            const dateIssued = document.getElementById('dateIssued').value;
            if (!fullName || !dateIssued) {
                alert('Please fill out all required fields.');
                return;
            }
            const date = new Date(dateIssued);
            const day = date.getDate();
            const monthNames = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];
            const month = monthNames[date.getMonth()];
            const year = date.getFullYear();
            document.getElementById('certName').textContent = fullName.toUpperCase();
            document.getElementById('certDay').textContent = day;
            document.getElementById('certMonth').textContent = month;
            document.getElementById('certYear').textContent = year;
            document.getElementById('certificate').style.display = 'block';
            document.getElementById('certificate').scrollIntoView({ behavior: 'smooth' });
        }
        function printCertificate() {
            const certificate = document.getElementById('certificate');
            if (certificate.style.display === 'none') {
                alert('Please generate the certificate first.');
                return;
            }
            window.print();
        }
        document.getElementById('dateIssued').value = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>