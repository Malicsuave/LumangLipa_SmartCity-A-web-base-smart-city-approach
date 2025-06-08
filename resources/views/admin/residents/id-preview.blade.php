@extends('layouts.admin.master')

@section('title', 'ID Preview: ' . $resident->full_name)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">ID Card Preview: {{ $resident->full_name }}</h6>
                        <div>
                            @if($resident->id_status == 'issued')
                                <a href="{{ route('admin.residents.id.download', $resident) }}" class="btn btn-sm btn-primary mr-2">
                                    <i class="fe fe-download"></i> Download ID
                                </a>
                            @endif
                            <a href="{{ route('admin.residents.id.show', $resident) }}" class="btn btn-sm btn-secondary">
                                <i class="fe fe-arrow-left"></i> Back to ID Management
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="alert alert-info">
                                <i class="fe fe-info"></i> This is a preview of how the ID card will look when printed. The actual card will be formatted to fit standard ID card size.
                            </div>
                            
                            <div class="id-card-preview">
                                <!-- ID Card Front -->
                                <div class="id-card-front">
                                    <div class="id-card-header">
                                        <img src="{{ asset('images/barangay-logo.png') }}" alt="Barangay Logo" class="logo">
                                        <div class="header-text">
                                            <h6>Republic of the Philippines</h6>
                                            <h5>BARANGAY LUMANGLIPA</h5>
                                            <h6>Luzon, Philippines</h6>
                                        </div>
                                    </div>
                                    
                                    <div class="id-card-title">
                                        <h4>RESIDENT IDENTIFICATION CARD</h4>
                                    </div>
                                    
                                    <div class="id-card-body">
                                        <div class="id-photo">
                                            @if($resident->photo)
                                                <img src="{{ $resident->photo_url }}" alt="Resident Photo">
                                            @else
                                                <div class="no-photo">
                                                    <i class="fe fe-user"></i>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="id-details">
                                            <div class="id-field">
                                                <span class="label">ID No:</span>
                                                <span class="value">{{ $resident->barangay_id }}</span>
                                            </div>
                                            
                                            <div class="id-field">
                                                <span class="label">Name:</span>
                                                <span class="value">{{ strtoupper($resident->full_name) }}</span>
                                            </div>
                                            
                                            <div class="id-field">
                                                <span class="label">Address:</span>
                                                <span class="value">{{ $resident->address }}</span>
                                            </div>
                                            
                                            <div class="id-field">
                                                <span class="label">Date of Birth:</span>
                                                <span class="value">{{ $resident->birthdate ? $resident->birthdate->format('M d, Y') : 'N/A' }}</span>
                                            </div>
                                            
                                            <div class="id-row">
                                                <div class="id-field half">
                                                    <span class="label">Gender:</span>
                                                    <span class="value">{{ $resident->sex }}</span>
                                                </div>
                                                
                                                <div class="id-field half">
                                                    <span class="label">Civil Status:</span>
                                                    <span class="value">{{ $resident->civil_status }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="id-field">
                                                <span class="label">Contact No:</span>
                                                <span class="value">{{ $resident->contact_number ?: 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="id-card-footer">
                                        <div class="id-validity">
                                            <div class="id-field">
                                                <span class="label">Date Issued:</span>
                                                <span class="value">{{ $resident->id_issued_at ? $resident->id_issued_at->format('m/d/Y') : 'N/A' }}</span>
                                            </div>
                                            <div class="id-field">
                                                <span class="label">Valid Until:</span>
                                                <span class="value">{{ $resident->id_expires_at ? $resident->id_expires_at->format('m/d/Y') : 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- ID Card Back -->
                                <div class="id-card-back">
                                    <div class="id-card-header-back">
                                        <h5>THIS CARD IDENTIFIES THE BEARER AS A RESIDENT OF</h5>
                                        <h4>BARANGAY LUMANGLIPA</h4>
                                    </div>
                                    
                                    <div class="id-emergency-contact">
                                        <h6>IN CASE OF EMERGENCY, PLEASE NOTIFY:</h6>
                                        @if($resident->household && $resident->household->emergency_contact_name)
                                            <div class="emergency-details">
                                                <div class="id-field">
                                                    <span class="label">Name:</span>
                                                    <span class="value">{{ $resident->household->emergency_contact_name }}</span>
                                                </div>
                                                
                                                <div class="id-field">
                                                    <span class="label">Contact No:</span>
                                                    <span class="value">{{ $resident->household->emergency_phone ?: 'N/A' }}</span>
                                                </div>
                                                
                                                <div class="id-field">
                                                    <span class="label">Relationship:</span>
                                                    <span class="value">{{ $resident->household->emergency_relationship ?: 'N/A' }}</span>
                                                </div>
                                            </div>
                                        @else
                                            <div class="emergency-details">
                                                <p class="text-muted">No emergency contact provided</p>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="id-signatures">
                                        <div class="resident-signature">
                                            <div class="signature-box">
                                                @if($resident->signature)
                                                    <img src="{{ $resident->signature_url }}" alt="Resident's Signature">
                                                @else
                                                    <div class="no-signature"></div>
                                                @endif
                                            </div>
                                            <div class="signature-label">Resident's Signature</div>
                                        </div>
                                        
                                        <div class="official-signature">
                                            <div class="signature-box">
                                                <img src="{{ asset('images/chairman-signature.png') }}" alt="Barangay Chairman's Signature">
                                            </div>
                                            <div class="signature-label">Barangay Chairman</div>
                                        </div>
                                    </div>
                                    
                                    <div class="id-qr-section">
                                        <div class="qr-code">
                                            <img src="data:image/png;base64,{{ $qrCode }}" alt="QR Code">
                                        </div>
                                        <div class="id-info">
                                            <p class="warning">This ID is non-transferable. Finder of lost ID please return to Barangay Lumanglipa Office.</p>
                                            <p class="contact-info">Tel: (123) 456-7890 | Email: barangay.lumanglipa@example.com</p>
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
    .id-card-preview {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin: 20px 0;
    }
    
    .id-card-front,
    .id-card-back {
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 10px;
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        position: relative;
    }
    
    .id-card-header {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
        border-bottom: 2px solid #072d6b;
        padding-bottom: 10px;
    }
    
    .id-card-header .logo {
        width: 60px;
        height: 60px;
        object-fit: contain;
        margin-right: 15px;
    }
    
    .header-text h6 {
        margin: 0;
        font-size: 12px;
    }
    
    .header-text h5 {
        margin: 3px 0;
        font-size: 16px;
        font-weight: 700;
        color: #072d6b;
    }
    
    .id-card-title {
        text-align: center;
        margin: 10px 0;
        background-color: #072d6b;
        color: white;
        padding: 5px;
        border-radius: 5px;
    }
    
    .id-card-title h4 {
        margin: 0;
        font-size: 14px;
        font-weight: 700;
    }
    
    .id-card-body {
        display: flex;
        gap: 15px;
        padding: 10px 0;
    }
    
    .id-photo {
        width: 120px;
        height: 120px;
        border: 1px solid #ddd;
        overflow: hidden;
    }
    
    .id-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .no-photo {
        width: 100%;
        height: 100%;
        background-color: #f9f9f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #aaa;
        font-size: 48px;
    }
    
    .id-details {
        flex: 1;
    }
    
    .id-field {
        margin-bottom: 5px;
        font-size: 12px;
    }
    
    .id-field .label {
        font-weight: bold;
        color: #444;
        width: 80px;
        display: inline-block;
    }
    
    .id-field .value {
        font-weight: normal;
    }
    
    .id-row {
        display: flex;
        gap: 10px;
    }
    
    .id-field.half {
        flex: 1;
    }
    
    .id-card-footer {
        border-top: 1px solid #ddd;
        padding-top: 10px;
        margin-top: 10px;
    }
    
    .id-validity {
        display: flex;
        justify-content: space-between;
    }
    
    /* Back of the card */
    .id-card-header-back {
        text-align: center;
        border-bottom: 2px solid #072d6b;
        padding-bottom: 10px;
        margin-bottom: 15px;
    }
    
    .id-card-header-back h5 {
        margin: 0;
        font-size: 12px;
        font-weight: normal;
    }
    
    .id-card-header-back h4 {
        margin: 5px 0 0;
        font-size: 16px;
        font-weight: 700;
        color: #072d6b;
    }
    
    .id-emergency-contact {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 15px;
    }
    
    .id-emergency-contact h6 {
        margin: 0 0 10px;
        font-size: 12px;
        text-align: center;
        color: #444;
    }
    
    .emergency-details {
        padding: 0 10px;
    }
    
    .id-signatures {
        display: flex;
        justify-content: space-around;
        margin: 15px 0;
    }
    
    .resident-signature, 
    .official-signature {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 45%;
    }
    
    .signature-box {
        height: 50px;
        width: 100%;
        border-bottom: 1px solid #000;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .signature-box img {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
    }
    
    .no-signature {
        width: 80%;
        border-bottom: 1px dashed #aaa;
    }
    
    .signature-label {
        margin-top: 5px;
        font-size: 10px;
        text-align: center;
    }
    
    .id-qr-section {
        display: flex;
        align-items: center;
        margin-top: 15px;
        border-top: 1px solid #ddd;
        padding-top: 15px;
    }
    
    .qr-code {
        width: 80px;
        height: 80px;
        margin-right: 10px;
    }
    
    .qr-code img {
        width: 100%;
        height: 100%;
    }
    
    .id-info {
        flex: 1;
    }
    
    .id-info .warning {
        margin: 0 0 5px;
        font-size: 10px;
        font-weight: bold;
    }
    
    .id-info .contact-info {
        margin: 0;
        font-size: 9px;
        color: #666;
    }
    
    @media (min-width: 768px) {
        .id-card-preview {
            flex-direction: row;
        }
        
        .id-card-front,
        .id-card-back {
            width: 48%;
        }
    }
</style>
@endpush