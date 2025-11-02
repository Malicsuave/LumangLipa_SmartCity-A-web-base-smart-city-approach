<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document Verification - Barangay Lumanglipa</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Feather Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.css">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 10px;
        }
        .fe {
            font-family: 'feather' !important;
        }
        .verification-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 0;
        }
        .card-header-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .card-body-custom {
            padding: 20px;
        }
        .info-card {
            padding: 15px;
            background: white;
            border-radius: 8px;
            margin-bottom: 12px;
            border-left: 4px solid;
        }
        .section-title {
            color: #495057;
            margin-bottom: 15px;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 8px;
            font-size: 16px;
        }
        
        @media (min-width: 768px) {
            body {
                padding: 20px;
            }
            .verification-container {
                margin: 60px auto;
            }
            .card-header-custom {
                padding: 30px;
            }
            .card-body-custom {
                padding: 40px;
            }
            .info-card {
                padding: 20px;
            }
            .section-title {
                font-size: 18px;
                margin-bottom: 20px;
                padding-bottom: 10px;
            }
        }
    </style>
</head>
<body>
<div class="container verification-container">
    <div class="card shadow-lg border-0">
                <div class="card-header card-header-custom">
            <div style="margin-bottom: 10px;">
                <img src="{{ asset('images/logo.png') }}" alt="Barangay Logo" style="width: 60px; height: 60px; border-radius: 50%; border: 3px solid white; background: white; padding: 5px;">
            </div>
            <h2 class="mb-0" style="font-weight: 600; letter-spacing: 0.5px; font-size: 1.5rem;">Document Verification</h2>
            <p class="mb-0" style="font-size: 13px; opacity: 0.9; margin-top: 5px;">Barangay Lumanglipa - Mataasnakahoy, Batangas</p>
        </div>
        <div class="card-body card-body-custom">
            @if(!$valid && !isset($document))
                <div class="text-center" style="padding: 30px 15px;">
                    <div style="font-size: 48px; color: #dc3545; margin-bottom: 15px;">
                        <i class="fe fe-x-circle"></i>
                    </div>
                    <h4 style="color: #dc3545; margin-bottom: 12px; font-weight: 600; font-size: 1.25rem;">Invalid Document</h4>
                    <p style="color: #6c757d; font-size: 15px;">{{ $message ?? 'This document code is invalid or does not exist in our records.' }}</p>
                    <div style="margin-top: 20px; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                        <p style="margin: 0; color: #495057; font-size: 14px;"><strong>Note:</strong> Please ensure you scanned the correct QR code from an official barangay document.</p>
                    </div>
                </div>
            @elseif(!$valid && isset($document))
                <div class="text-center" style="padding: 30px 15px;">
                    <div style="font-size: 48px; color: #ffc107; margin-bottom: 15px;">
                        <i class="fe fe-alert-triangle"></i>
                    </div>
                    <h4 style="color: #ffc107; margin-bottom: 12px; font-weight: 600; font-size: 1.25rem;">Document Expired</h4>
                    <p style="color: #6c757d; font-size: 15px;">This document has expired and is no longer valid.</p>
                    
                    <div style="margin-top: 20px;">
                        <div class="info-card" style="border-left-color: #007bff;">
                            <div style="font-size: 11px; color: #6c757d; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px;">Document Type</div>
                            <div style="font-weight: 600; color: #212529; font-size: 15px;">{{ $document->document_type }}</div>
                        </div>
                        <div class="info-card" style="border-left-color: #28a745;">
                            <div style="font-size: 11px; color: #6c757d; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px;">Resident Name</div>
                            <div style="font-weight: 600; color: #212529; font-size: 15px;">{{ $resident->first_name }} {{ $resident->middle_name }} {{ $resident->last_name }}</div>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="info-card" style="border-left-color: #17a2b8;">
                                    <div style="font-size: 11px; color: #6c757d; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px;">Date Issued</div>
                                    <div style="font-weight: 600; color: #212529; font-size: 14px;">{{ $document->approved_at ? \Carbon\Carbon::parse($document->approved_at)->format('M j, Y') : 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-card" style="border-left-color: #dc3545;">
                                    <div style="font-size: 11px; color: #6c757d; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px;">Expired On</div>
                                    <div style="font-weight: 600; color: #dc3545; font-size: 14px;">{{ $expiration ? \Carbon\Carbon::parse($expiration)->format('M j, Y') : 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border-radius: 8px; border: 1px solid #ffc107;">
                        <p style="margin: 0; color: #856404; font-size: 14px;"><strong>⚠️ Action Required:</strong> Please visit the Barangay Hall to request a new document.</p>
                    </div>
                </div>
            @else
                <div class="text-center" style="padding: 20px 15px 15px 15px;">
                    <div style="font-size: 48px; color: #28a745; margin-bottom: 15px;">
                        <i class="fe fe-check-circle"></i>
                    </div>
                    <h4 style="color: #28a745; margin-bottom: 10px; font-weight: 600; font-size: 1.25rem;">✓ Verified Document</h4>
                    <p style="color: #6c757d; font-size: 15px; margin-bottom: 20px;">This is an authentic and valid barangay document.</p>
                </div>

                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
                    <h5 class="section-title">
                        <i class="fe fe-file-text" style="margin-right: 5px;"></i>Document Information
                    </h5>
                    
                    <div class="row g-2">
                        <div class="col-12 col-md-6">
                            <div class="info-card" style="border-left-color: #007bff; height: 100%;">
                                <div style="font-size: 11px; color: #6c757d; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Document Type</div>
                                <div style="font-weight: 600; color: #212529; font-size: 15px;">{{ $document->document_type }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="info-card" style="border-left-color: #007bff; height: 100%;">
                                <div style="font-size: 11px; color: #6c757d; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Document ID</div>
                                <div style="font-weight: 600; color: #212529; font-size: 13px; font-family: monospace;">{{ substr($document->uuid, 0, 8) }}...</div>
                            </div>
                        </div>
                    </div>

                    <h5 class="section-title" style="margin-top: 20px;">
                        <i class="fe fe-user" style="margin-right: 5px;"></i>Resident Information
                    </h5>
                    
                    <div class="info-card" style="border-left-color: #007bff; margin-bottom: 12px;">
                        <div style="font-size: 11px; color: #6c757d; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Full Name</div>
                        <div style="font-weight: 600; color: #212529; font-size: 16px;">{{ $resident->first_name }} {{ $resident->middle_name }} {{ $resident->last_name }}</div>
                    </div>
                    
                    
                        <div class="col-12 col-md-6">
                            <div class="info-card" style="border-left-color: #007bff;">
                                <div style="font-size: 11px; color: #6c757d; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Purok</div>
                                <div style="font-weight: 600; color: #212529; font-size: 15px;">{{ $resident->purok ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="info-card" style="border-left-color: #007bff;">
                                <div style="font-size: 11px; color: #6c757d; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Address</div>
                                <div style="font-weight: 600; color: #212529; font-size: 14px;">{{ $resident->current_address ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <h5 class="section-title" style="margin-top: 20px;">
                        <i class="fe fe-calendar" style="margin-right: 5px;"></i>Validity Information
                    </h5>
                    
                    <div class="row g-2">
                        <div class="col-6 col-md-4">
                            <div class="info-card" style="border-left-color: #007bff text-align: center;">
                                <div style="font-size: 11px; color: #6c757d; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Issued On</div>
                                <div style="font-weight: 600; color: #212529; font-size: 13px;">{{ $document->approved_at ? \Carbon\Carbon::parse($document->approved_at)->format('M j, Y') : 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4">
                            <div class="info-card" style="border-left-color: #007bff; text-align: center;">
                                <div style="font-size: 11px; color: #6c757d; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Valid Until</div>
                                <div style="font-weight: 600; color: #212529; font-size: 13px;">{{ $expiration ? \Carbon\Carbon::parse($expiration)->format('M j, Y') : 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="info-card" style="border-left-color: #007bff; text-align: center;">
                                <div style="font-size: 11px; color: #6c757d; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px;">Status</div>
                                <div style="font-weight: 600; color: #28a745; font-size: 13px;">
                                    <i class="fe fe-check-circle"></i> ACTIVE
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($document->purpose)
                    <h5 class="section-title" style="margin-top: 20px;">
                        <i class="fe fe-info" style="margin-right: 5px;"></i>Purpose
                    </h5>
                    <div class="info-card" style="border-left-color: #007bff;">
                        <p style="margin: 0; color: #495057; font-size: 14px;">{{ $document->purpose }}</p>
                    </div>
                    @endif
                </div>

                <div style="background: #d4edda; padding: 15px; border-radius: 8px; border: 1px solid #c3e6cb; text-align: center;">
                    <p style="margin: 0; color: #155724; font-weight: 500; font-size: 14px;">
                        <i class="fe fe-shield" style="margin-right: 5px;"></i>
                        This document has been verified as authentic and is currently valid.
                    </p>
                </div>
                
                <div style="margin-top: 15px; padding: 12px; background: #e7f3ff; border-radius: 8px; border-left: 4px solid #007bff;">
                    <p style="margin: 0; color: #004085; font-size: 12px;">
                        <strong>ℹ️ Note:</strong> This verification page confirms the authenticity of the document based on our records as of {{ now()->format('M j, Y g:i A') }}.
                    </p>
                </div>
            @endif
        </div>
        
        <div class="card-footer text-center" style="background: #f8f9fa; padding: 15px; border-top: 1px solid #dee2e6;">
            <p style="margin: 0; color: #6c757d; font-size: 13px;">
                <i class="fe fe-globe" style="margin-right: 5px;"></i>
                Barangay Lumanglipa Smarcity System
            </p>
            <p style="margin: 5px 0 0 0; color: #adb5bd; font-size: 11px;">
                © {{ date('Y') }} Barangay Lumanglipa. All rights reserved.
            </p>
        </div>
    </div>
    
    <div class="text-center" style="margin-top: 20px;">
        <a href="{{ url('/') }}" class="btn btn-outline-light" style="padding: 10px 25px; border-radius: 25px; font-weight: 500; border-width: 2px;">
            <i class="fe fe-arrow-left" style="margin-right: 5px;"></i>Back to Home
        </a>
    </div>
</div>

<style>
    @media print {
        .btn, .card-footer {
            display: none !important;
        }
    }
</style>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 