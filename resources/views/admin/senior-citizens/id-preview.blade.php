@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.senior-citizens.index') }}">Senior Citizens</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.senior-citizens.show', $seniorCitizen) }}">{{ $seniorCitizen->full_name }}</a></li>
<li class="breadcrumb-item active" aria-current="page">ID Preview</li>
@endsection

@section('page-title', 'Senior Citizen ID Preview')
@section('page-subtitle', 'Preview ID card for ' . $seniorCitizen->full_name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Senior Citizen ID Preview</h1>
                    <p class="text-muted mb-0">Preview ID card for {{ $seniorCitizen->full_name }}</p>
                </div>
                <div>
                    @if($seniorCitizen->senior_id_status == 'issued')
                        <a href="{{ route('admin.senior-citizens.id.download', $seniorCitizen) }}" class="btn btn-primary">
                            <i class="fas fa-download mr-1"></i> Download ID Card
                        </a>
                    @endif
                    <a href="{{ route('admin.senior-citizens.show', $seniorCitizen) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Senior Citizen
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Senior Citizen Info Card -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 admin-card-shadow">
                <div class="card-header">
                    <strong class="card-title">
                        <i class="fas fa-user mr-2"></i>Senior Citizen Information
                    </strong>
                </div>
                <div class="card-body box-profile">
                    <div class="text-center">
                        @if($seniorCitizen->photo)
                            <img class="profile-user-img img-fluid img-circle" src="{{ asset('storage/' . $seniorCitizen->photo) }}" alt="{{ $seniorCitizen->full_name }}">
                        @else
                            <img class="profile-user-img img-fluid img-circle" src="{{ asset('images/default-avatar.png') }}" alt="Default Avatar">
                        @endif
                    </div>
                    <h3 class="profile-username text-center">{{ $seniorCitizen->full_name }}</h3>
                    <p class="text-muted text-center">{{ $seniorCitizen->senior_id_number ?: 'No ID Generated' }}</p>
                    
                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Age:</b> <span class="float-right">{{ $seniorCitizen->age ?: 'N/A' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Gender:</b> <span class="float-right">{{ $seniorCitizen->sex ?: 'N/A' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Civil Status:</b> <span class="float-right">{{ $seniorCitizen->civil_status ?: 'N/A' }}</span>
                        </li>
                        <li class="list-group-item">
                            <b>ID Status:</b> 
                            <span class="float-right">
                                @switch($seniorCitizen->senior_id_status)
                                    @case('issued')
                                        <span class="badge badge-success">Issued</span>
                                        @break
                                    @case('pending')
                                        <span class="badge badge-warning">Pending</span>
                                        @break
                                    @case('expired')
                                        <span class="badge badge-danger">Expired</span>
                                        @break
                                    @default
                                        <span class="badge badge-secondary">Not Requested</span>
                                @endswitch
                            </span>
                        </li>
                    </ul>
                    
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('admin.senior-citizens.show', $seniorCitizen) }}" class="btn btn-primary btn-block">
                                <i class="fas fa-user mr-2"></i>
                                View Full Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ID Card Preview -->
        <div class="col-md-8">
            <div class="card shadow-lg border-0 admin-card-shadow">
                <div class="card-header">
                    <strong class="card-title">
                        <i class="fas fa-id-card mr-2"></i>Senior Citizen ID Preview
                    </strong>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="fas fa-info-circle mr-2"></i>
                        This is a preview of how the Senior Citizen ID card will look when printed. The actual card will be formatted to fit standard ID card dimensions.
                    </div>

                        <div class="id-card-container">
                            <!-- Front Side -->
                            <div id="idCardFront" class="id-card">
                                <div class="id-card-front-bg">
                                    <img src="{{ asset('images/logo.png') }}" alt="Barangay Logo">
                                </div>
                                <div class="id-card-header">
                                        <img src="{{ asset('images/logo.png') }}" alt="Barangay Logo" class="barangay-logo-left">
                                        <div class="id-card-title">
                                            <h6 class="mb-0">Barangay Lumanglipa</h6>
                                            <h6 class="small mb-0">Mataasnakahoy, Batangas</h6>
                                            <h6 class="mb-0">Senior Citizen Card</h6>
                                        </div>
                                        <img src="{{ asset('images/citylogo.png') }}" alt="City Logo" class="barangay-logo-right">
                                </div>
                                <div class="id-card-body">
                                    <table style="width: 100%; table-layout: fixed; border: none;">
                                        <tr>
                                            <td style="width: 65%; vertical-align: top; padding-right: 10px; border: none;">
                                                <div class="id-card-details">
                                                    <div class="mb-2">
                                                        <strong>Pangalan/Name</strong><br>
                                                        <span class="text-uppercase font-weight-bold">{{ $seniorCitizen->full_name }}</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong>Petsa ng Kapanganakan/Date of birth</strong><br>
                                                        <span>{{ $seniorCitizen->birthdate ? \Carbon\Carbon::parse($seniorCitizen->birthdate)->format('M d, Y') : 'N/A' }}</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong>Telepono/Phone</strong><br>
                                                        <span>{{ $seniorCitizen->contact_number ?: 'N/A' }}</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong>Tirahan/Address</strong><br>
                                                        <span class="address-text" style="font-size: 9px; line-height: 1.1; word-wrap: break-word; word-break: break-all; white-space: normal; overflow-wrap: anywhere; hyphens: auto; max-width: 100%; display: block; word-spacing: -0.5px; letter-spacing: -0.2px; box-sizing: border-box; padding: 0; margin: 0;">{{ $seniorCitizen->current_address ?: 'Sitio Malinggao Bato, Barangay Lumanglipa, Mataasnakahoy, Batangas' }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="width: 35%; vertical-align: top; text-align: center; border: none;">
                                                <table style="width: 100%; text-align: center;">
                                                    <tr>
                                                        <td style="text-align: center;">
                                                            @if($seniorCitizen->photo)
                                                                <img src="{{ asset('storage/' . $seniorCitizen->photo) }}" alt="{{ $seniorCitizen->full_name }}" style="width: 88px; height: 88px; border: 2px solid #f57f17; border-radius: 5px;">
                                                            @else
                                                                <table style="width: 88px; height: 88px; background-color: #cccccc; border: 2px solid #999999; margin: 0 auto;">
                                                                    <tr>
                                                                        <td style="text-align: center; vertical-align: middle; font-size: 10px; color: #666666; font-weight: bold;">NO PHOTO</td>
                                                                    </tr>
                                                                </table>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: center; padding-top: 8px;">
                                                            <div style="border: 1px solid #cccccc; border-radius: 4px; padding: 4px 8px; background-color: #ffffff; display: inline-block; font-size: 10px; white-space: nowrap; overflow: hidden; max-width: 100%;">
                                                                <span style="font-weight: bold; color: #001a4e;">
                                                                    @if($seniorCitizen->senior_id_number)
                                                                        {{ $seniorCitizen->senior_id_number }}
                                                                    @else
                                                                        SC-LUM-{{ date('Y') }}-{{ str_pad($seniorCitizen->id ?? 1, 4, '0', STR_PAD_LEFT) }}
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Back Side -->
                            <div id="idCardBack" class="id-card mt-4 bg-light">
                                <div class="id-card-back-body">
                                    <div class="row">
                                        <div class="col-7">
                                            <div class="id-card-back-details">
                                                <div class="mb-2">
                                                    <strong>Kasarian/Sex</strong><br>
                                                    <span>{{ $seniorCitizen->sex }}</span>
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Katayuang Sibil/Civil Status</strong><br>
                                                    <span>{{ $seniorCitizen->civil_status }}</span>
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Lugar ng Kapanganakan/Place of birth</strong><br>
                                                    <span>{{ $seniorCitizen->birthplace ?: 'N/A' }}</span>
                                                </div>
                                                <div class="mb-2">
                                                    <strong>Emergency Contact</strong><br>
                                                    <span>{{ $seniorCitizen->emergency_contact_name ?: 'N/A' }} @if($seniorCitizen->emergency_contact_relationship)({{ $seniorCitizen->emergency_contact_relationship }})@endif</span>
                                                    <span style="display: block; margin-top: 1px;">{{ $seniorCitizen->emergency_contact_number ?: 'N/A' }}</span>
                                                </div>
                                                
                                                <!-- Validation Date -->
                                                <div class="row no-gutters">
                                                    <div class="col-6">
                                                        <div class="mb-2">
                                                            <strong>Date Issued</strong><br>
                                                            <span>{{ $seniorCitizen->senior_id_issued_at ? $seniorCitizen->senior_id_issued_at->format('m/d/Y') : date('m/d/Y') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="mb-2">
                                                            <strong>Valid Until</strong><br>
                                                            <span>{{ $seniorCitizen->senior_id_expires_at ? $seniorCitizen->senior_id_expires_at->format('m/d/Y') : date('m/d/Y', strtotime('+5 years')) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-5">
                                            <div class="qr-code-container">
                                                <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code" class="img-fluid" style="width: 150px; height: 150px;">
                                            </div>
                                            <div class="id-signature mt-2 text-center">
                                                @if($seniorCitizen->signature)
                                                    <img src="{{ asset('storage/' . $seniorCitizen->signature) }}" alt="Signature">
                                                @else
                                                    <div class="no-signature"></div>
                                                @endif
                                                <div class="signature-line"></div>
                                                <div class="small">May-ari/Card Holder</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Import Google Fonts to match PDF exactly */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Open+Sans:wght@400;500;600;700&display=swap');
    
    /* ID card styling with orange/yellow theme for senior citizen */
    .id-card-container {
        max-width: 450px;
        margin: 0 auto;
        position: relative;
        font-family: 'Arial', 'Helvetica', sans-serif;
    }
    
    /* Force all elements in ID card to use PDF fonts */
    .id-card * {
        font-family: 'Arial', 'Helvetica', sans-serif !important;
    }
    .id-card {
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        background: white;
        min-height: 250px;
        height: auto;
        margin-bottom: 20px;
    }
    
    /* Transparent background logo for front side - match PDF exactly */
    .id-card-front-bg {
        position: absolute;
        top: 60%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 280px;
        height: 280px;
        opacity: 0.08;
        z-index: 1;
        pointer-events: none;
    }
    
    .id-card-front-bg img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    
    .id-card-header {
        background: linear-gradient(to right, #fff8e1, #ffe082); /* Yellow gradient for senior citizen */
        padding: 5px;
        border-bottom: 1px solid #ffca28; /* Yellow border */
        display: flex;
        align-items: center;
        position: relative;
        z-index: 2;
    }
    .barangay-logo-left {
        width: 45px;
        height: 45px;
        object-fit: cover;
        margin-left: 5px;
        margin-top: 2px;
        margin-bottom: 2px;
    }
    
    .barangay-logo-right {
        width: 45px;
        height: 45px;
        object-fit: cover;
        margin-right: 5px;
        margin-top: 2px;
        margin-bottom: 2px;
        margin-left: auto;
    }
    
    .id-card-title {
        text-align: center;
        flex: 1;
        color: #003366 !important;
    }
    .id-card-title h6 {
        margin: 0;
        font-weight: bold;
        font-size: 12px;
        color: #003366 !important;
    }
    .id-card-title h6.small {
        font-size: 10px;
        color: #003366 !important;
    }
    .id-card-body {
        padding: 15px;
        text-align: left;
        position: relative;
        z-index: 2;
    }
    .id-card-photo-container {
        width: 90px;
        height: 90px;
        overflow: hidden;
        border: 2px solid #f57f17;
        border-radius: 5px;
        margin: 10px auto 5px auto;
        background: white;
    }
    .id-card-photo-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .no-photo {
        width: 88px;
        height: 88px;
        background-color: #e0e0e0;
        text-align: center;
        vertical-align: middle;
        color: #666;
        font-size: 12px;
        font-weight: bold;
        border: 1px solid #999;
        line-height: 88px;
        position: relative;
    }
    .id-card-details {
        font-size: 11px;
        padding-left: 20px;
        font-family: 'Arial', 'Helvetica', sans-serif !important;
    }
    
    /* Reduce spacing between fields to fit more content */
    .id-card-details .mb-2 {
        margin-bottom: 3px !important;
    }
    
    /* Font styling for all text elements - navy blue text */
    .id-card-details strong {
        font-weight: bold !important;
        font-size: 11px !important;
        color: #000 !important;
        font-family: 'Arial', 'Helvetica', sans-serif !important;
    }
    
    .id-card-details span {
        font-weight: normal !important;
        font-size: 11px !important;
        color: #000 !important;
        font-family: 'Arial', 'Helvetica', sans-serif !important;
    }
    .idno {
        font-weight: bold;
        color: #001a4e !important;
        font-size: 12px;
        white-space: nowrap;
        overflow: visible;
        text-overflow: clip;
    }
    .idno-box {
        background: #f8f9fa;
        border: 2px solid #f57f17;
        border-radius: 4px;
        padding: 4px 8px;
        margin: 8px auto 5px auto;
        min-width: 120px;
        text-align: center;
        display: inline-block;
    }
    /* Enhanced address text wrapping fixes - matches PDF exactly */
    .address-text {
        font-size: 9px !important;
        line-height: 1.1 !important;
        word-wrap: break-word !important;
        word-break: break-all !important;
        white-space: normal !important;
        overflow-wrap: anywhere !important;
        hyphens: auto !important;
        max-width: 100% !important;
        display: block !important;
        /* Add word spacing for better readability */
        word-spacing: -0.5px !important;
        letter-spacing: -0.2px !important;
        /* Ensure it fits within the cell */
        box-sizing: border-box !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    .text-uppercase {
        text-transform: uppercase;
        font-family: 'Arial', 'Helvetica', sans-serif !important;
    }
    .font-weight-bold {
        font-weight: bold !important;
        color: #000 !important;
        font-family: 'Arial', 'Helvetica', sans-serif !important;
    }
    
    /* Specific styling for name field to match PDF exactly */
    .id-card-details .text-uppercase.font-weight-bold {
        font-weight: bold !important;
        color: #001a4e !important; /* Navy blue for text */
        font-family: 'Poppins', 'Open Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif !important;
        font-size: 11px !important;
    }
    
    /* Back side styles */
    .id-card-back {
        background: #f5f5f5;
    }
    .id-card-back-body {
        padding: 15px;
        text-align: left;
    }
    .id-card-back-details {
        font-size: 11px;
        padding-left: 10px;
        font-family: 'Arial', 'Helvetica', sans-serif !important;
    }
    
    /* Back side font styling - navy blue text */
    .id-card-back-details strong {
        font-weight: bold !important;
        font-size: 11px !important;
        color: #000 !important;
        font-family: 'Arial', 'Helvetica', sans-serif !important;
    }
    
    .id-card-back-details span {
        font-weight: normal !important;
        font-size: 11px !important;
        color: #000 !important;
        font-family: 'Arial', 'Helvetica', sans-serif !important;
    }
    
    /* Global strong styling - navy blue text */
    .id-card strong {
        font-weight: bold !important;
        color: #000 !important;
        font-family: 'Arial', 'Helvetica', sans-serif !important;
    }
    .qr-code-container {
        text-align: center;
        margin: 10px auto;
    }
    .qr-code-container img {
        width: 150px;
        height: 150px;
    }
    .id-signature {
        margin-top: 5px;
        text-align: center;
    }
    .signature-line {
        width: 100px;
        height: 1px;
        background: #333;
        margin: 5px auto;
    }
    .no-signature {
        width: 100px;
        height: 30px;
        border-bottom: 1px dashed #aaa;
        margin: 0 auto;
    }
    
    /* Fix for signature visibility */
    .id-signature img {
        display: block;
        max-height: 30px;
        max-width: 100px;
        margin: 0 auto;
    }
    
    /* Fix button outline secondary to match census page exactly */
    .btn-outline-secondary {
        color: #6c757d !important;
        border-color: #6c757d !important;
    }
    
    .btn-outline-secondary:hover {
        color: #fff !important;
        background-color: #6c757d !important;
        border-color: #6c757d !important;
    }
    
    .btn-outline-secondary:focus,
    .btn-outline-secondary.focus {
        box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.5) !important;
    }
    
    /* Make card tools buttons white instead of grayish */
    .card-tools .btn-tool {
        color: white !important;
        background-color: transparent !important;
        border: none !important;
    }
    
    .card-tools .btn-tool:hover {
        color: #f8f9fa !important;
        background-color: rgba(255, 255, 255, 0.1) !important;
    }
    
    .card-tools .btn-tool:focus {
        color: white !important;
        box-shadow: none !important;
    }
</style>
@endpush