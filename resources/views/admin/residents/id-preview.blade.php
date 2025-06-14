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
                            <a href="{{ route('admin.residents.id.pending') }}" class="btn btn-sm btn-secondary">
                                <i class="fe fe-arrow-left"></i> Back to Pending IDs
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

                            <div class="id-card-container">
                                <!-- Front Side -->
                                <div id="idCardFront" class="id-card">
                                    <div class="id-card-front-bg">
                                        <img src="{{ asset('images/logo.png') }}" alt="Barangay Logo">
                                    </div>
                                    <div class="id-card-header">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('images/logo.png') }}" alt="Barangay Logo" class="barangay-logo-left">
                                            <div class="id-card-title text-primary">
                                                <h6 class="mb-0">Barangay Lumanglipa</h6>
                                                <h6 class="small mb-0">Matasnakahoy, Lipa City Batangas</h6>
                                                <h6 class="mb-0">Residence Card</h6>
                                            </div>
                                            <img src="{{ asset('images/citylogo.png') }}" alt="City Logo" class="barangay-logo-right ml-auto">
                                        </div>
                                    </div>
                                    <div class="id-card-body">
                                        <div class="row no-gutters">
                                            <div class="col-md-8">
                                                <div class="id-card-details">
                                                    <div class="mb-2">
                                                        <strong>Pangalan/Name</strong><br>
                                                        <span class="text-uppercase font-weight-bold">{{ $resident->full_name }}</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong>Petsa ng Kapanganakan/Date of birth</strong><br>
                                                        <span>{{ $resident->birthdate ? $resident->birthdate->format('M d, Y') : 'N/A' }}</span>
                                                    </div>
                                                    
                                                    <!-- Changed to vertical layout - telephone first, then address below it -->
                                                    <div class="mb-2">
                                                        <strong>Telepono/Phone</strong><br>
                                                        <span>{{ $resident->contact_number ?: 'N/A' }}</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong>Tirahan/Address</strong><br>
                                                        <span>{{ $resident->address }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="id-card-photo-container">
                                                    @if($resident->photo)
                                                        <img src="{{ $resident->photo_url }}" alt="{{ $resident->full_name }}">
                                                    @else
                                                        <div class="no-photo">
                                                            <i class="fe fe-user"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="text-center mt-2">
                                                    <span class="idno">{{ $resident->barangay_id }}</span>
                                                </div>
                                            </div>
                                        </div>
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
                                                        <span>{{ $resident->sex }}</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong>Katayuang Sibil/Civil Status</strong><br>
                                                        <span>{{ $resident->civil_status }}</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong>Lugar ng Kapanganakan/Place of birth</strong><br>
                                                        <span>{{ $resident->birthplace ?: 'N/A' }}</span>
                                                    </div>
                                                    <div class="mb-2">
                                                        <strong>Emergency Contact</strong><br>
                                                        <span>{{ $resident->household ? $resident->household->emergency_contact_name : 'N/A' }}</span><br>
                                                        <span>{{ $resident->household ? $resident->household->emergency_phone : '' }}</span>
                                                    </div>
                                                    
                                                    <!-- Validation Date -->
                                                    <div class="row no-gutters">
                                                        <div class="col-6">
                                                            <div class="mb-2">
                                                                <strong>Date Issued</strong><br>
                                                                <span>{{ $resident->id_issued_at ? $resident->id_issued_at->format('m/d/Y') : date('m/d/Y') }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="mb-2">
                                                                <strong>Valid Until</strong><br>
                                                                <span>{{ $resident->id_expires_at ? $resident->id_expires_at->format('m/d/Y') : date('m/d/Y', strtotime('+5 years')) }}</span>
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
                                                    @if($resident->signature)
                                                        <img src="{{ $resident->signature_url }}" alt="Signature">
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
    </div>
</div>
@endsection

@push('styles')
<style>
    .id-card-container {
        max-width: 450px;
        margin: 0 auto;
        position: relative;
    }
    .id-card {
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        background: white;
        height: 250px;
    }
    .id-card-front-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        overflow: hidden;
    }
    .id-card-front-bg img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.1;
    }
    .id-card-header {
        background: linear-gradient(to right, #e3f2fd, #bbdefb);
        padding: 5px;
        border-bottom: 1px solid #ccc;
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
    }
    
    .id-card-title {
        text-align: center;
        flex: 1;
        color: #1565c0;
    }
    .id-card-title h6 {
        margin: 0;
        font-weight: bold;
        font-size: 12px;
    }
    .id-card-title h6.small {
        font-size: 10px;
    }
    .id-card-body {
        padding: 15px;
        text-align: left;
    }
    .id-card-photo-container {
        width: 100px;
        height: 100px;
        overflow: hidden;
        border: 1px solid #ddd;
        border-radius: 10%;
        margin: 0 auto;
    }
    .id-card-photo-container img {
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
    .id-card-details {
        font-size: 11px;
        padding-left: 20px;
    }
    .idno {
        font-weight: bold;
        color: #333;
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
    }
    .qr-code-container {
        text-align: center;
        margin: 10px auto;
    }
    .qr-code-container img {
        width: 150px;
        height: 150px;
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
</style>
@endpush