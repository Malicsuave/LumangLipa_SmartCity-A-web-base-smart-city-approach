<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$fullName = 'Juan Dela Cruz';
$purpose = 'MEDICAL ASSISTANCE';
$dateIssued = '2024-01-15';

echo view('documents.templates.certificate-of-indigency-fixed', compact('fullName', 'purpose', 'dateIssued'))->render();
