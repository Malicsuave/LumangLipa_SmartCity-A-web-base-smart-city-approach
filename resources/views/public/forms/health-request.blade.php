@extends('layouts.public.master')

@section('title', 'Appointment Booking')

@section('content')
<!-- QR Code Scanner Library -->
<script src="https://unpkg.com/html5-qrcode"></script>
<!-- Hero Section with Background -->
<section class="position-relative" style="background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8b 100%); padding-top: 4rem; padding-bottom: 2rem; margin-top: 0;">
    <div class="container">
        <div class="text-center">
            <h1 class="fw-bold mb-1 text-white" style="font-size: 2.5rem; margin-top: 2.5rem; margin-bottom: 0.5rem;">Book an Appointment</h1>
            <p class="text-white opacity-9" style="font-size: 1.1rem;">Schedule an appointment for health services at Barangay Lumanglipa</p>
        </div>
    </div>
</section>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0" style="border-radius: 12px; margin-top: -4rem;">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center" style="border-radius: 12px 12px 0 0; padding: 1.5rem;">
                    <div>
                        <h4 class="mb-0 fw-bold" style="color: #2A7BC4;">
                            <i class="fas fa-calendar-alt me-2"></i>Appointment Booking
                        </h4>
                        <p class="mb-0 text-muted small">Fill out the form below to book an appointment for health services</p>
                    </div>
                </div>
                <div class="card-body p-4" style="background: #ffffff;">
                    <!-- Success Alert -->
                    <div id="successAlert" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
                        <i class="fas fa-check-circle me-2"></i>
                        <span id="successMessage"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                    <!-- Error Alert -->
                    <div id="errorAlert" class="alert alert-primary alert-dismissible fade" role="alert" style="display: none;">
                        <i class="fas fa-exclamation-circle text-primary me-2"></i>
                        <span id="errorMessage"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                    <form id="healthRequestForm">
                        @csrf
                        
                        <!-- Identity Verification Section -->
                        <div class="mb-3">
                            <label>Identity Verification <span class="text-primary">*</span></label>

                            <!-- Verification Method Toggle -->
                            <div class="btn-group w-100" role="group" aria-label="Verification method" style="display: flex;">
                                <input type="radio" class="btn-check" name="verification_method" id="manual_input" value="manual" checked>
                                <label class="btn btn-outline-primary" for="manual_input" style="flex: 1; text-align: center;">
                                    <i class="fas fa-keyboard text-primary me-2"></i>Manual Input
                                </label>
                                <input type="radio" class="btn-check" name="verification_method" id="qr_scan" value="qr">
                                <label class="btn btn-outline-primary" for="qr_scan" style="flex: 1; text-align: center;">
                                    <i class="fas fa-qrcode text-primary me-2"></i>QR Code
                                </label>
                            </div>
                        </div>

                        <!-- Barangay ID Section -->
                        <div class="mb-3" style="margin-top: 0;">
                            <label for="barangay_id" style="margin-bottom: 0.25rem; margin-top: -0.5rem;">Barangay ID <span class="text-primary">*</span></label>

                            <!-- Manual Input Section -->
                            <div id="manualInputSection">
                                <div class="input-group" style="display: flex; align-items: stretch;">
                                    <input type="text"
                                           class="form-control"
                                           id="barangay_id"
                                           name="barangay_id"
                                           placeholder="Enter your Barangay ID"
                                           style="height: 38px; border: 1px solid #ced4da !important; padding: 0.375rem 0.75rem !important; flex: 1;"
                                           required>
                                    <button type="button"
                                            class="btn btn-outline-primary"
                                            id="checkResidentBtn"
                                            style="height: 38px; padding: 0.375rem 0.75rem; display: flex; align-items: center; justify-content: center; border: 1px solid #0d6efd !important; white-space: nowrap;">
                                        <i class="fas fa-search"></i> Verify
                                    </button>
                                </div>
                                <small class="form-text text-muted">Enter your registered Barangay ID to verify your information</small>
                            </div>

                            <!-- QR Code Section -->
                            <div id="qrCodeSection" style="display: none;">
                                <div class="btn-group w-100" role="group" style="display: flex;">
                                    <input type="radio" class="btn-check" name="qr_method" id="scan_qr" value="scan">
                                    <label class="btn btn-outline-primary" for="scan_qr" style="flex: 1; text-align: center;">
                                        <i class="fas fa-camera text-primary me-2"></i>
                                        Scan QR Code
                                    </label>
                                    <input type="file"
                                           class="form-control"
                                           id="qr_upload"
                                           accept="image/*"
                                           style="display: none;">
                                    <input type="radio" class="btn-check" name="qr_method" id="upload_qr" value="upload">
                                    <label class="btn btn-outline-primary" for="upload_qr" style="flex: 1; text-align: center;">
                                        <i class="fas fa-upload text-primary me-2"></i>
                                        Upload QR Code
                                    </label>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6">
                                        <small class="form-text text-muted d-block text-center">Use camera to scan QR code</small>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="form-text text-muted d-block text-center">Upload QR code image</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="residentInfo" class="card border-success mb-4" style="display: none;">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-success">
                                    <i class="fas fa-user-check me-2"></i>
                                    Verified Resident Information
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Name:</strong></p>
                                        <p id="residentName" class="text-muted mb-2"></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Address:</strong></p>
                                        <p id="residentAddress" class="text-muted mb-2"></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Age:</strong></p>
                                        <p id="residentAge" class="text-muted mb-2"></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Contact Number:</strong></p>
                                        <p id="residentContact" class="text-muted mb-2"></p>
                                    </div>
                                </div>
                            </div>                        </div>

                        <!-- OTP Verification Section -->
                        <div id="otpSection" class="card border-warning mb-4" style="display: none;">
                            <div class="card-header bg-warning bg-opacity-10">
                                <h6 class="mb-0 text-warning">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    Email Verification Required
                                </h6>
                            </div>
                            <div class="card-body">
                                <div id="otpRequestStep">
                                    <p class="mb-3">
                                        <i class="fas fa-info-circle text-info me-2"></i>
                                        To proceed with your document request, we need to verify your identity. 
                                        An OTP (One-Time Password) will be sent to your registered email address.
                                    </p>
                                    <button type="button" class="btn btn-warning" id="sendOtpBtn">
                                        <i class="fas fa-envelope me-2"></i>
                                        Send OTP to Email
                                    </button>
                                </div>
                                
                                <div id="otpVerifyStep" style="display: none;">
                                    <p class="mb-3">
                                        <i class="fas fa-envelope text-success me-2"></i>
                                        A 6-digit OTP has been sent to: <strong id="emailHint"></strong>
                                    </p>
                                    <div class="row align-items-end">
                                        <div class="col-md-6">
                                            <label for="otp_code" class="form-label">Enter OTP Code</label>
                                            <input type="text" 
                                                   class="form-control form-control-lg text-center" 
                                                   id="otp_code" 
                                                   placeholder="000000" 
                                                   maxlength="6"
                                                   pattern="[0-9]{6}">
                                        </div>
                                        <div class="col-md-6">
                                            <button type="button" class="btn btn-success" id="verifyOtpBtn">
                                                <i class="fas fa-check me-2"></i>
                                                Verify OTP
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary ms-2" id="resendOtpBtn">
                                                <i class="fas fa-redo me-2"></i>
                                                Resend
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-text mt-2">
                                        <i class="fas fa-clock text-muted me-1"></i>
                                        <span id="otpTimer">OTP expires in 10:00</span>
                                    </div>
                                </div>
                                
                                <div id="otpVerifiedStep" style="display: none;">
                                    <div class="alert alert-success mb-0">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Email verified successfully! You can now proceed with your document request.
                                    </div>
                                </div>
                            </div>                        </div>

                        <!-- Form Fields Section (Blurred until OTP verified) -->
                        <div id="formFieldsSection" class="form-fields-blur">
                            <div class="blur-overlay">
                                <div class="blur-message">
                                    <i class="fas fa-shield-alt fa-2x text-warning mb-2"></i>
                                    <h5>Identity Verification Required</h5>
                                    <p class="mb-0">Please verify your identity using Barangay ID or QR Code to access the form</p>
                                </div>
                            </div>

                            <!-- Health Service Type Section -->
                            <div class="mb-4">
                                <label for="service_type" class="form-label fw-bold">
                                    <i class="fas fa-stethoscope text-primary me-2"></i>
                                    Service Type <span class="text-primary">*Required</span>
                                </label>
                                <select class="form-select form-select-lg" id="service_type" name="service_type" required disabled>
                                    <option value="">Select Service Type</option>
                                    <option value="Medical Consultation">Medical Consultation</option>
                                    <option value="Blood Pressure Monitoring">Blood Pressure Monitoring</option>
                                    <option value="Prenatal Check-up">Prenatal Check-up</option>
                                    <option value="Immunization">Immunization</option>
                                    <option value="Health Certificate">Health Certificate</option>
                                    <option value="Family Planning">Family Planning</option>
                                    <option value="Emergency Medical">Emergency Medical</option>
                                    <option value="Senior Citizen Health Check">Senior Citizen Health Check</option>
                                </select>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    Choose the type of health service you need
                                </div>
                            </div>

                            <!-- Appointment Type Section -->
                            <div class="mb-4">
                                <label for="appointment_type" class="form-label fw-bold">
                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                    Appointment Type <span class="text-primary">*Required</span>
                                </label>
                                <select class="form-select form-select-lg" id="appointment_type" name="appointment_type" required disabled>
                                    <option value="">Select Appointment Type</option>
                                    <option value="walk-in">Walk-in (Same Day)</option>
                                    <option value="scheduled">Scheduled Appointment</option>
                                </select>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    Choose whether you want to walk-in or schedule an appointment
                                </div>
                            </div>

                            <!-- Scheduled Appointment Details (Hidden by default) -->
                            <div class="mb-4" id="scheduledAppointmentSection" style="display: none;">
                                <div class="card border-warning">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Appointment Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="preferred_date" class="form-label fw-bold">
                                                    Preferred Date <span class="text-primary">*</span>
                                                </label>
                                                <input type="date" class="form-control" id="preferred_date" name="preferred_date" disabled>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="preferred_time" class="form-label fw-bold">
                                                    Preferred Time <span class="text-primary">*</span>
                                                </label>
                                                <select class="form-select" id="preferred_time" name="preferred_time" disabled>
                                                    <option value="">Select Time</option>
                                                    <option value="08:00 AM">08:00 AM</option>
                                                    <option value="09:00 AM">09:00 AM</option>
                                                    <option value="10:00 AM">10:00 AM</option>
                                                    <option value="11:00 AM">11:00 AM</option>
                                                    <option value="01:00 PM">01:00 PM</option>
                                                    <option value="02:00 PM">02:00 PM</option>
                                                    <option value="03:00 PM">03:00 PM</option>
                                                    <option value="04:00 PM">04:00 PM</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Health Concern/Purpose Section -->
                            <div class="mb-4">
                                <label for="health_concern" class="form-label fw-bold">
                                    <i class="fas fa-clipboard-list text-primary me-2"></i>
                                    Health Concern/Symptoms <span class="text-primary">*Required</span>
                                </label>
                                <textarea class="form-control" 
                                          id="health_concern" 
                                          name="health_concern" 
                                          rows="4" 
                                          placeholder="Please describe your health concern, symptoms, or reason for visit..."
                                          required
                                          disabled></textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    Provide detailed information about your health concern or reason for the service
                                </div>
                            </div>

                            <!-- Priority Level -->
                            <div class="mb-4">
                                <label for="priority" class="form-label fw-bold">
                                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                    Priority Level <span class="text-primary">*Required</span>
                                </label>
                                <select class="form-select form-select-lg" id="priority" name="priority" required disabled>
                                    <option value="">Select Priority</option>
                                    <option value="low">Low - Regular check-up or routine service</option>
                                    <option value="medium">Medium - General health concern</option>
                                    <option value="high">High - Urgent but not life-threatening</option>
                                    <option value="emergency">Emergency - Immediate attention needed</option>
                                </select>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    Select the urgency level of your health concern
                                </div>
                            </div>

                            <!-- Additional Symptoms -->
                            <div class="mb-4">
                                <label for="symptoms" class="form-label fw-bold">
                                    <i class="fas fa-thermometer-half text-primary me-2"></i>
                                    Additional Symptoms (Optional)
                                </label>
                                <textarea class="form-control" 
                                          id="symptoms" 
                                          name="symptoms" 
                                          rows="3" 
                                          placeholder="Any additional symptoms or medical history relevant to your visit..."
                                          disabled></textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    Include any other symptoms or relevant medical information
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid" id="submitButtonSection" style="display: none !important;">
                                <button type="submit" 
                                        class="btn btn-primary btn-lg" 
                                        id="submitBtn"
                                        disabled>
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Submit Appointment Request
                                </button>
                            </form>

                            <!-- Information Section -->
                            <div class="mt-5 p-4 bg-light rounded">
                                <h5 class="mb-3" style="color: #0d6efd;">
                                    <i class="fas fa-info-circle me-2" style="color: #0d6efd;"></i>
                                    Important Information
                                </h5>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Identity verification is required for all appointment requests (QR code verification skips email OTP)
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        All appointment requests will be reviewed by the Barangay Health Office
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Processing time is typically 1-3 business days
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-check text-success me-2"></i>
                                        You will be notified once your appointment is approved
                                    </li>
                                    <li class="mb-0">
                                        <i class="fas fa-check text-success me-2"></i>
                                        Make sure all information provided is accurate and complete
                                    </li>
                                </ul>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Scanner Modal -->
<div class="modal fade" id="qrScannerModal" tabindex="-1" aria-labelledby="qrScannerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrScannerModalLabel">
                    <i class="fas fa-qrcode me-2"></i>
                    Scan QR Code
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <p class="text-muted">Position your QR code within the camera frame</p>
                    <div id="scannerStatus" class="alert alert-info" style="display: none;">
                        <i class="fas fa-camera me-2"></i>
                        <span id="statusText">Initializing camera...</span>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div id="qr-reader" style="width: 100%; min-height: 400px; border: 2px dashed #ddd; border-radius: 8px; position: relative;"></div>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <small class="text-muted d-block mb-2">Make sure your camera is allowed and QR code is well-lit</small>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

@push('styles')
    <link rel="stylesheet" href="/public/assets/css/material-kit.css">
    <link rel="stylesheet" href="/public/css/material-kit-override.css">
@endpush

<style>
.card {
    transition: all 0.3s ease;
}

.form-control:focus,
.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.btn-primary {
    background: linear-gradient(45deg, #0d6efd, #0a58ca);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #0a58ca, #084298);
    transform: translateY(-1px);
}

#submitBtn:disabled {
    background: #6c757d !important;
    border-color: #6c757d !important;
    cursor: not-allowed;
    opacity: 1;
}

#submitBtn:not(:disabled) {
    background: linear-gradient(45deg, #0d6efd, #0a58ca) !important;
    border-color: #0d6efd !important;
}

.alert {
    border: none;
    border-radius: 10px;
}

#otp_code {
    font-size: 1.5rem;
    letter-spacing: 0.5rem;
    font-weight: bold;
}

#otp_code:focus {
    border-color: #198754;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
}

.card-header.bg-warning {
    border-bottom: 2px solid #ffc107;
}

.btn-warning:hover {
    background-color: #e0a800;
    border-color: #d39e00;
}

#otpTimer {
    font-weight: 600;
}

/* Blur effect for form fields before OTP verification */
.form-fields-blur {
    position: relative;
    transition: all 0.3s ease;
}

.form-fields-blur.blurred {
    filter: blur(5px);
    pointer-events: none;
    user-select: none;
}

.blur-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(5px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.form-fields-blur:not(.blurred) .blur-overlay {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}

.blur-message {
    text-align: center;
    padding: 2rem;
    color: #6c757d;
}

.blur-message h5 {
    color: #495057;
    margin-bottom: 0.5rem;
}

.blur-message p {
    font-size: 0.9rem;
}

/* Animation for revealing form */
.form-fields-reveal {
    animation: formReveal 0.6s ease-out;
}

@keyframes formReveal {
    from {
        filter: blur(5px);
        opacity: 0.7;
        transform: translateY(10px);
    }
    to {
        filter: blur(0);
        opacity: 1;
        transform: translateY(0);
    }
}

/* QR Code Scanner Styles */
.btn-check:checked + .btn-outline-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

#qr-reader {
    border: 2px dashed #0d6efd;
    border-radius: 8px;
    background-color: #f8f9fa;
}

#qr-reader video {
    border-radius: 8px;
    width: 100% !important;
    height: auto !important;
}

.qr-success {
    background-color: #d1e7dd;
    border: 2px solid #198754;
    border-radius: 8px;
    padding: 1rem;
    text-align: center;
}

.verification-method-toggle {
    transition: all 0.3s ease;
}

#qrCodeSection, #manualInputSection {
    transition: all 0.3s ease;
}

/* Scanner status styles */
#scannerStatus {
    border-radius: 8px;
    font-size: 0.9rem;
}

#scannerStatus i {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

/* File upload hover effect */
#uploadQrBtn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

#scanQrBtn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* QR scanner modal styles */
#qrScannerModal .modal-content {
    border-radius: 12px;
    border: none;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
}

#qrScannerModal .modal-header {
    background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8b 100%);
    color: white;
    border-radius: 12px 12px 0 0;
    border: none;
}

#qrScannerModal .btn-close {
    filter: invert(1);
}

/* QR Reader styling */
#qr-reader {
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

#qr-reader video {
    border-radius: 8px;
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
}

#qr-reader canvas {
    border-radius: 8px;
}

/* Loading animation for QR reader */
#qr-reader:empty::before {
    content: 'Preparing camera...';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #6c757d;
    font-size: 16px;
    text-align: center;
}

/* Scanner status improvements */
#scannerStatus {
    margin-bottom: 15px;
    border: none;
    border-radius: 8px;
    transition: all 0.3s ease;
}

#scannerStatus.alert-info {
    background-color: #cff4fc;
    color: #055160;
    border: 1px solid #b6effb;
}

#scannerStatus.alert-success {
    background-color: #d1e7dd;
    color: #0f5132;
    border: 1px solid #badbcc;
}

#scannerStatus.alert-danger {
    background-color: #f8d7da;
    color: #842029;
    border: 1px solid #f5c2c7;
}

#scannerStatus.alert-warning {
    background-color: #fff3cd;
    color: #664d03;
    border: 1px solid #ffecb5;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('healthRequestForm');
    const barangayIdInput = document.getElementById('barangay_id');
    const checkResidentBtn = document.getElementById('checkResidentBtn');
    const residentInfo = document.getElementById('residentInfo');
    const otpSection = document.getElementById('otpSection');
    const formFieldsSection = document.getElementById('formFieldsSection');
    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const verifyOtpBtn = document.getElementById('verifyOtpBtn');
    const resendOtpBtn = document.getElementById('resendOtpBtn');
    const otpCodeInput = document.getElementById('otp_code');
    const serviceTypeSelect = document.getElementById('service_type');
    const appointmentTypeSelect = document.getElementById('appointment_type');
    const healthConcernTextarea = document.getElementById('health_concern');
    const prioritySelect = document.getElementById('priority');
    const symptomsTextarea = document.getElementById('symptoms');
    const submitBtn = document.getElementById('submitBtn');
    const successAlert = document.getElementById('successAlert');
    const errorAlert = document.getElementById('errorAlert');
    
    // QR Code elements
    const manualInputRadio = document.getElementById('manual_input');
    const qrScanRadio = document.getElementById('qr_scan');
    const manualInputSection = document.getElementById('manualInputSection');
    const qrCodeSection = document.getElementById('qrCodeSection');
    const scanQrRadio = document.getElementById('scan_qr');
    const uploadQrRadio = document.getElementById('upload_qr');
    const qrUploadInput = document.getElementById('qr_upload');
    const qrScannerModal = new bootstrap.Modal(document.getElementById('qrScannerModal'));
    
    let residentVerified = false;
    let otpVerified = false;
    let qrVerified = false; // QR verification bypasses OTP
    let otpTimer = null;
    let otpExpiryTime = null;
    let html5QrCode = null;

    // Initialize form with blur effect and hidden submit button
    formFieldsSection.classList.add('blurred');
    
    // Ensure submit button is hidden initially
    const submitButtonSection = document.getElementById('submitButtonSection');
    if (submitButtonSection) {
        submitButtonSection.style.setProperty('display', 'none', 'important');
    }

    // Verification method toggle
    manualInputRadio.addEventListener('change', function() {
        if (this.checked) {
            if (manualInputSection) manualInputSection.style.display = 'block';
            if (qrCodeSection) qrCodeSection.style.display = 'none';
            resetFormFields(); // Reset form fields but keep radio button selection
        }
    });

    qrScanRadio.addEventListener('change', function() {
        if (this.checked) {
            if (manualInputSection) manualInputSection.style.display = 'none';
            if (qrCodeSection) qrCodeSection.style.display = 'block';
            resetFormFields(); // Reset form fields but keep radio button selection
        }
    });


    // QR Code method selection (match document request form)
    scanQrRadio.addEventListener('change', function() {
        if (this.checked) {
            qrScannerModal.show();
            setTimeout(() => {
                startQrScanner();
            }, 500);
        }
    });

    uploadQrRadio.addEventListener('change', function() {
        if (this.checked) {
            qrUploadInput.click();
        }
    });

    qrUploadInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (10MB max)
            if (file.size > 10 * 1024 * 1024) {
                showError('File size too large. Please select an image under 10MB.');
                qrUploadInput.value = '';
                if (uploadQrRadio) uploadQrRadio.checked = false;
                return;
            }
            // Validate file type
            if (!file.type.startsWith('image/')) {
                showError('Please select a valid image file.');
                qrUploadInput.value = '';
                if (uploadQrRadio) uploadQrRadio.checked = false;
                return;
            }
            const uploadLabel = document.querySelector('label[for="upload_qr"]');
            const originalHtml = uploadLabel.innerHTML;
            uploadLabel.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
            // Create FormData to send file to server
            const formData = new FormData();
            formData.append('qr_image', file);
            // Send to server API for QR code decoding
            fetch('{{ route("health.decode-qr") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    handleQrCodeData(data.qr_data);
                    showSuccess('QR Code uploaded and processed successfully!');
                } else {
                    showError(data.message || 'Failed to decode QR code. Please ensure the image contains a valid QR code.');
                }
            })
            .catch(error => {
                showError('Failed to process QR code. Please check your internet connection and try again.');
            })
            .finally(() => {
                uploadLabel.innerHTML = originalHtml;
                qrUploadInput.value = '';
                if (uploadQrRadio) uploadQrRadio.checked = false;
            });
        }
    });

    // Handle QR scanner modal close
    document.getElementById('qrScannerModal').addEventListener('hidden.bs.modal', function() {
        stopQrScanner();
    });

        function startQrScanner() {
        // Check if HTML5-QRCode library is loaded
        if (typeof Html5Qrcode === 'undefined') {
            console.error('HTML5-QRCode library not loaded');
            showError('QR Scanner library not loaded. Please refresh the page and try again.');
            qrScannerModal.hide();
            return;
        }

        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                initializeScanner();
            }).catch(err => {
                console.log('Error stopping previous scanner:', err);
                initializeScanner();
            });
        } else {
            initializeScanner();
        }
    }

    function initializeScanner() {
        const scannerStatus = document.getElementById('scannerStatus');
        const statusText = document.getElementById('statusText');
        const qrReaderDiv = document.getElementById('qr-reader');
        
        // Clear any existing content
        qrReaderDiv.innerHTML = '';
        
        if (scannerStatus) {
            scannerStatus.style.display = 'block';
            statusText.textContent = 'Requesting camera access...';
            scannerStatus.className = 'alert alert-info';
        }
        
        try {
            html5QrCode = new Html5Qrcode("qr-reader");
            
            // Use facingMode for better mobile compatibility
            statusText.textContent = 'Starting camera...';
            
            const config = {
                fps: 10,
                qrbox: function(viewfinderWidth, viewfinderHeight) {
                    let minEdgePercentage = 0.7; // 70% of the smaller edge
                    let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
                    let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
                    return {
                        width: qrboxSize,
                        height: qrboxSize
                    };
                },
                aspectRatio: 1.0,
                disableFlip: false
            };
            
            // Try to use back camera on mobile devices
            const cameraId = { facingMode: "environment" };
            
            html5QrCode.start(
                cameraId,
                        config,
                        (qrCodeMessage) => {
                            console.log('QR Code detected:', qrCodeMessage);
                            statusText.textContent = 'QR Code detected! Processing...';
                            handleQrCodeData(qrCodeMessage);
                            qrScannerModal.hide();
                            showSuccess('QR Code scanned successfully!');
                        },
                        (errorMessage) => {
                            // This is called continuously during scanning, so we only log serious errors
                            if (errorMessage.includes('NotAllowedError') || errorMessage.includes('Permission denied')) {
                                console.error('Camera permission error:', errorMessage);
                                statusText.textContent = 'Camera permission denied';
                                scannerStatus.className = 'alert alert-danger';
                                showError('Camera permission denied. Please allow camera access and try again.');
                                setTimeout(() => qrScannerModal.hide(), 3000);
                            }
                        }
                    ).then(() => {
                        statusText.textContent = 'Camera ready! Position QR code in view...';
                        scannerStatus.className = 'alert alert-success';
                        console.log('QR Scanner started successfully');
                    }).catch(err => {
                        console.error('Unable to start scanner:', err);
                        statusText.textContent = 'Camera start failed';
                        scannerStatus.className = 'alert alert-danger';
                        
                        let errorMsg = 'Unable to access camera. Please check your camera permissions.';
                        if (err.name === 'NotAllowedError') {
                            errorMsg = 'Camera access denied. Please allow camera permissions and try again.';
                        } else if (err.name === 'NotFoundError') {
                            errorMsg = 'No camera found. Please use the upload option instead.';
                        } else if (err.name === 'NotSupportedError') {
                            errorMsg = 'Camera not supported on this device. Please use the upload option instead.';
                        }
                        showError(errorMsg);
                        setTimeout(() => qrScannerModal.hide(), 3000);
                    });
        } catch (err) {
            console.error('Scanner initialization failed:', err);
            statusText.textContent = 'Scanner initialization failed';
            scannerStatus.className = 'alert alert-danger';
            showError('QR Scanner initialization failed. Please use the upload option instead.');
            setTimeout(() => qrScannerModal.hide(), 3000);
        }
    }

    function stopQrScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode = null;
            }).catch(err => {
                console.error('Error stopping scanner:', err);
                html5QrCode = null;
            });
        }
        
        // Reset scanner status
        const scannerStatus = document.getElementById('scannerStatus');
        const statusText = document.getElementById('statusText');
        if (scannerStatus) {
            scannerStatus.style.display = 'none';
            scannerStatus.className = 'alert alert-info';
            statusText.textContent = 'Initializing camera...';
        }
    }

    function handleQrCodeData(qrData) {
        console.log('Processing QR data:', qrData); // Debug log
        console.log('QR data type:', typeof qrData); // Debug log
        console.log('QR data length:', qrData ? qrData.length : 'null'); // Debug log
        
        try {
            // Assuming QR code contains the barangay ID
            // You can modify this logic based on your QR code format
            let barangayId = qrData.trim();
            
            console.log('Extracted Barangay ID:', barangayId); // Debug log
            
            // If QR contains JSON or other format, parse it here
            // Example: const data = JSON.parse(qrData); barangayId = data.barangay_id;
            
            // Try to parse as JSON first
            try {
                const parsedData = JSON.parse(qrData);
                console.log('Parsed JSON data:', parsedData); // Debug log
                
                if (parsedData.barangay_id) {
                    barangayId = parsedData.barangay_id;
                    console.log('Found barangay_id in JSON:', barangayId); // Debug log
                } else if (parsedData.id) {
                    barangayId = parsedData.id;
                    console.log('Found id in JSON:', barangayId); // Debug log
                }
            } catch (jsonError) {
                console.log('QR data is not JSON, using as plain text:', barangayId); // Debug log
            }
            
            barangayIdInput.value = barangayId;
            qrVerified = true;
            
            // Automatically verify resident with QR data
            verifyResidentWithQr(barangayId);
            
        } catch (error) {
            console.error('Error processing QR data:', error);
            showError('Invalid QR code format. Please try again.');
        }
    }

    function verifyResidentWithQr(barangayId) {
    fetch('{{ route("health.check-resident") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
                barangay_id: barangayId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('residentName').textContent = data.resident.name;
                document.getElementById('residentAddress').textContent = data.resident.address;
                document.getElementById('residentAge').textContent = data.resident.age;
                document.getElementById('residentContact').textContent = data.resident.contact || 'N/A';
                
                residentInfo.style.display = 'block';
                residentVerified = true;
                
                // Skip OTP verification for QR code users
                otpVerified = true;
                qrVerified = true; // Set QR verification flag
                
                // Show QR verification success instead of OTP section
                showQrVerificationSuccess();
                
                // Enable form fields immediately
                enableFormFields();
                
                showSuccess('QR Code verified successfully! You can now proceed with your health service request.');
                hideError();
            } else {
                showError(data.message || 'Invalid QR code or resident not found');
                resetForm();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while verifying the QR code');
            resetForm();
        });
    }

    function showQrVerificationSuccess() {
        otpSection.style.display = 'block';
        otpSection.innerHTML = `
            <div class="card border-success mb-4">
                <div class="card-header bg-success bg-opacity-10">
                    <h6 class="mb-0 text-success">
                        <i class="fas fa-qrcode me-2"></i>
                        QR Code Verified Successfully
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-success mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        Your identity has been verified using QR code. No additional email verification required.
                    </div>
                </div>
            </div>
        `;
    }

    function enableFormFields() {
        // Remove blur effect and enable form fields
        formFieldsSection.classList.remove('blurred');
        formFieldsSection.classList.add('form-fields-reveal');
        
        serviceTypeSelect.disabled = false;
        appointmentTypeSelect.disabled = false;
        healthConcernTextarea.disabled = false;
        prioritySelect.disabled = false;
        symptomsTextarea.disabled = false;
        document.getElementById('preferred_date').disabled = false;
        document.getElementById('preferred_time').disabled = false;
        
        // Show submit button section
        const submitButtonSection = document.getElementById('submitButtonSection');
        if (submitButtonSection) {
            submitButtonSection.style.setProperty('display', 'block', 'important');
        }
        
        checkFormValidity();
    }

    // Check resident function
    checkResidentBtn.addEventListener('click', function() {
        const barangayId = barangayIdInput.value.trim();
        if (!barangayId) {
            showError('Please enter a Barangay ID');
            return;
        }

        checkResidentBtn.disabled = true;
        checkResidentBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking...';

    fetch('{{ route("health.check-resident") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ barangay_id: barangayId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('residentName').textContent = data.resident.name;
                document.getElementById('residentAddress').textContent = data.resident.address;
                document.getElementById('residentAge').textContent = data.resident.age;
                document.getElementById('residentContact').textContent = data.resident.contact_number || 'N/A';
                
                if (residentInfo) residentInfo.style.display = 'block';
                if (otpSection) otpSection.style.display = 'block';
                residentVerified = true;
                
                // Change button to show "Found" in green
                checkResidentBtn.innerHTML = '<i class="fas fa-check"></i> Resident Found';
                checkResidentBtn.classList.remove('btn-outline-primary');
                checkResidentBtn.classList.add('btn-success');
                checkResidentBtn.disabled = true;
                
                hideError();
            } else {
                showError(data.message || 'Resident not found');
                resetForm();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while verifying the resident');
            resetForm();
        })
        .finally(() => {
            if (!residentVerified) {
                checkResidentBtn.disabled = false;
                checkResidentBtn.innerHTML = '<i class="fas fa-search"></i> Verify';
            }
        });
    });

    // Send OTP
    sendOtpBtn.addEventListener('click', function() {
        const barangayId = barangayIdInput.value.trim();
        
        sendOtpBtn.disabled = true;
        sendOtpBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

        fetch('{{ route("health.send-otp") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ barangay_id: barangayId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('emailHint').textContent = data.email_hint;
                const otpRequestStep = document.getElementById('otpRequestStep');
                const otpVerifyStep = document.getElementById('otpVerifyStep');
                if (otpRequestStep) otpRequestStep.style.display = 'none';
                if (otpVerifyStep) otpVerifyStep.style.display = 'block';
                
                // Set expiry time and start countdown
                otpExpiryTime = new Date(data.expires_at);
                startOtpTimer();
                
                showSuccess(data.message);
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while sending OTP');
        })
        .finally(() => {
            sendOtpBtn.disabled = false;
            sendOtpBtn.innerHTML = '<i class="fas fa-envelope me-2"></i>Send OTP to Email';
        });
    });

    // Verify OTP
    verifyOtpBtn.addEventListener('click', function() {
        const barangayId = barangayIdInput.value.trim();
        const otpCode = otpCodeInput.value.trim();
        
        if (!otpCode || otpCode.length !== 6) {
            showError('Please enter a valid 6-digit OTP code');
            return;
        }

        verifyOtpBtn.disabled = true;
        verifyOtpBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';

        fetch('{{ route("health.verify-otp") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
                barangay_id: barangayId,
                otp_code: otpCode
            })
        })
        .then(response => response.json())        .then(data => {
            if (data.success) {
                otpVerified = true;
                const otpVerifyStep = document.getElementById('otpVerifyStep');
                const otpVerifiedStep = document.getElementById('otpVerifiedStep');
                if (otpVerifyStep) otpVerifyStep.style.display = 'none';
                if (otpVerifiedStep) otpVerifiedStep.style.display = 'block';
                
                // Remove blur effect and enable form fields
                formFieldsSection.classList.remove('blurred');
                formFieldsSection.classList.add('form-fields-reveal');
                
                serviceTypeSelect.disabled = false;
                appointmentTypeSelect.disabled = false;
                healthConcernTextarea.disabled = false;
                prioritySelect.disabled = false;
                symptomsTextarea.disabled = false;
                document.getElementById('preferred_date').disabled = false;
                document.getElementById('preferred_time').disabled = false;
                
                // Show submit button section
                const submitButtonSection = document.getElementById('submitButtonSection');
                if (submitButtonSection) {
                    submitButtonSection.style.setProperty('display', 'block', 'important');
                }
                
                // Stop timer
                if (otpTimer) {
                    clearInterval(otpTimer);
                }
                
                checkFormValidity();
                showSuccess(data.message);
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while verifying OTP');
        })
        .finally(() => {
            verifyOtpBtn.disabled = false;
            verifyOtpBtn.innerHTML = '<i class="fas fa-check me-2"></i>Verify OTP';
        });
    });

    // Resend OTP
    resendOtpBtn.addEventListener('click', function() {
        const otpVerifyStep = document.getElementById('otpVerifyStep');
        const otpRequestStep = document.getElementById('otpRequestStep');
        if (otpVerifyStep) otpVerifyStep.style.display = 'none';
        if (otpRequestStep) otpRequestStep.style.display = 'block';
        otpCodeInput.value = '';
        
        if (otpTimer) {
            clearInterval(otpTimer);
        }
    });

    // OTP input formatting
    otpCodeInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length === 6) {
            verifyOtpBtn.focus();
        }
    });

    // Start OTP countdown timer
    function startOtpTimer() {
        otpTimer = setInterval(function() {
            const now = new Date();
            const timeLeft = Math.max(0, otpExpiryTime - now);
            
            if (timeLeft <= 0) {
                clearInterval(otpTimer);
                document.getElementById('otpTimer').textContent = 'OTP has expired';
                document.getElementById('otpTimer').className = 'text-danger';
                verifyOtpBtn.disabled = true;
                return;
            }
            
            const minutes = Math.floor(timeLeft / 60000);
            const seconds = Math.floor((timeLeft % 60000) / 1000);
            document.getElementById('otpTimer').textContent = 
                `OTP expires in ${minutes}:${seconds.toString().padStart(2, '0')}`;
        }, 1000);
    }

    // Appointment type functionality
    appointmentTypeSelect.addEventListener('change', function() {
        const scheduledSection = document.getElementById('scheduledAppointmentSection');
        const preferredDate = document.getElementById('preferred_date');
        const preferredTime = document.getElementById('preferred_time');
        
        if (this.value === 'scheduled') {
            scheduledSection.style.display = 'block';
            preferredDate.setAttribute('required', '');
            preferredTime.setAttribute('required', '');
        } else {
            scheduledSection.style.display = 'none';
            preferredDate.removeAttribute('required');
            preferredTime.removeAttribute('required');
            preferredDate.value = '';
            preferredTime.value = '';
        }
        checkFormValidity();
    });

    // Set minimum date for preferred_date to tomorrow
    const preferredDateInput = document.getElementById('preferred_date');
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    preferredDateInput.min = tomorrow.toISOString().split('T')[0];

    // Form validation
    function checkFormValidity() {
        let isValid = residentVerified && 
                     (otpVerified || qrVerified) &&
                     serviceTypeSelect.value && 
                     appointmentTypeSelect.value &&
                     healthConcernTextarea.value.trim() &&
                     prioritySelect.value;

        // Check scheduled appointment fields if needed
        if (appointmentTypeSelect.value === 'scheduled') {
            const preferredDate = document.getElementById('preferred_date');
            const preferredTime = document.getElementById('preferred_time');
            isValid = isValid && preferredDate.value && preferredTime.value;
        }

        submitBtn.disabled = !isValid;
    }

    serviceTypeSelect.addEventListener('change', checkFormValidity);
    appointmentTypeSelect.addEventListener('change', checkFormValidity);
    healthConcernTextarea.addEventListener('input', checkFormValidity);
    prioritySelect.addEventListener('change', checkFormValidity);
    document.getElementById('preferred_date').addEventListener('change', checkFormValidity);
    document.getElementById('preferred_time').addEventListener('change', checkFormValidity);

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!residentVerified) {
            showError('Please verify your identity first');
            return;
        }

        if (!otpVerified && !qrVerified) {
            showError('Please complete the verification process first');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';

        const formData = new FormData();
        formData.append('barangay_id', barangayIdInput.value);
        formData.append('service_type', serviceTypeSelect.value);
        formData.append('appointment_type', appointmentTypeSelect.value);
        formData.append('health_concern', healthConcernTextarea.value);
        formData.append('priority', prioritySelect.value);
        
        // Add symptoms if provided
        if (symptomsTextarea.value.trim()) {
            formData.append('symptoms', symptomsTextarea.value);
        }
        
        // Add scheduled appointment details if applicable
        if (appointmentTypeSelect.value === 'scheduled') {
            formData.append('preferred_date', document.getElementById('preferred_date').value);
            formData.append('preferred_time', document.getElementById('preferred_time').value);
        }
        
        formData.append('verification_method', qrVerified ? 'qr' : 'manual');

        fetch('{{ route("health.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                // Try to get the error response body
                return response.text().then(text => {
                    console.error('Response text:', text);
                    let errorData;
                    try {
                        errorData = JSON.parse(text);
                        console.error('Parsed error data:', errorData);
                    } catch (parseError) {
                        console.error('Could not parse response as JSON:', parseError);
                        errorData = { message: text || `HTTP error! status: ${response.status}` };
                    }
                    throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                });
            }
            
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                showSuccess(data.message);
                form.reset();
                resetForm();
            } else {
                if (data.errors) {
                    const errorMessages = Object.values(data.errors).flat().join(', ');
                    showError(errorMessages);
                } else {
                    showError(data.message || 'An error occurred');
                }
            }
        })
        .catch(error => {
            console.error('Detailed error:', error);
            console.error('Error type:', error.constructor.name);
            console.error('Error message:', error.message);
            showError('An error occurred while submitting the request: ' + error.message);
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Submit Health Request';
        });
    });

    function resetFormFields() {
        // Reset form fields but keep radio button selection
        
        // Clear input fields
        if (barangayIdInput) barangayIdInput.value = '';
        if (otpCodeInput) otpCodeInput.value = '';
        if (nameInput) nameInput.value = '';
        if (birthdateInput) birthdateInput.value = '';
        if (ageInput) ageInput.value = '';
        if (sexSelect) sexSelect.value = '';
        if (civilStatusSelect) civilStatusSelect.value = '';
        if (addressInput) addressInput.value = '';
        if (contactNumberInput) contactNumberInput.value = '';
        
        // Clear and disable form fields 
        if (nameInput) {
            nameInput.disabled = true;
            nameInput.value = '';
        }
        if (birthdateInput) {
            birthdateInput.disabled = true;
            birthdateInput.value = '';
        }
        if (ageInput) {
            ageInput.disabled = true;
            ageInput.value = '';
        }
        if (sexSelect) {
            sexSelect.disabled = true;
            sexSelect.value = '';
        }
        if (civilStatusSelect) {
            civilStatusSelect.disabled = true;
            civilStatusSelect.value = '';
        }
        if (addressInput) {
            addressInput.disabled = true;
            addressInput.value = '';
        }
        if (contactNumberInput) {
            contactNumberInput.disabled = true;
            contactNumberInput.value = '';
        }
        
        if (serviceTypeSelect) {
            serviceTypeSelect.disabled = true;
            serviceTypeSelect.value = '';
        }
        if (appointmentTypeSelect) {
            appointmentTypeSelect.disabled = true;
            appointmentTypeSelect.value = '';
        }
        if (healthConcernTextarea) {
            healthConcernTextarea.disabled = true;
            healthConcernTextarea.value = '';
        }
        if (prioritySelect) {
            prioritySelect.disabled = true;
            prioritySelect.value = '';
        }
        if (symptomsTextarea) {
            symptomsTextarea.disabled = true;
            symptomsTextarea.value = '';
        }
        if (submitBtn) submitBtn.disabled = true;
        residentVerified = false;
        otpVerified = false;
        qrVerified = false;
        
        // Clear file input
        const receiptInput = document.getElementById('receipt');
        if (receiptInput) receiptInput.value = '';
        
        // Reset blur effects
        if (formFieldsSection) {
            formFieldsSection.classList.add('blurred');
            formFieldsSection.classList.remove('form-fields-reveal');
        }
        
        // Reset OTP section
        const otpRequestStep = document.getElementById('otpRequestStep');
        const otpVerifyStep = document.getElementById('otpVerifyStep');
        const otpVerifiedStep = document.getElementById('otpVerifiedStep');
        
        if (otpRequestStep) otpRequestStep.style.display = 'block';
        if (otpVerifyStep) otpVerifyStep.style.display = 'none';
        if (otpVerifiedStep) otpVerifiedStep.style.display = 'none';
        if (otpCodeInput) otpCodeInput.value = '';
        
        if (otpTimer) {
            clearInterval(otpTimer);
        }
        
        // Reset verification button to original state
        if (checkResidentBtn) {
            checkResidentBtn.innerHTML = '<i class="fas fa-search"></i> Verify';
            checkResidentBtn.classList.remove('btn-success');
            checkResidentBtn.classList.add('btn-outline-primary');
            checkResidentBtn.disabled = false;
        }
        
        // Reset QR upload input
        if (qrUploadInput) qrUploadInput.value = '';
        
        // Stop QR scanner if running
        stopQrScanner();
    }

    function resetForm() {
        if (residentInfo) residentInfo.style.display = 'none';
        if (otpSection) otpSection.style.display = 'none';
        
        // Hide submit button section
        const submitButtonSection = document.getElementById('submitButtonSection');
        if (submitButtonSection) {
            submitButtonSection.style.setProperty('display', 'none', 'important');
        }
        
        if (serviceTypeSelect) {
            serviceTypeSelect.disabled = true;
            serviceTypeSelect.value = '';
        }
        if (appointmentTypeSelect) {
            appointmentTypeSelect.disabled = true;
            appointmentTypeSelect.value = '';
        }
        if (healthConcernTextarea) {
            healthConcernTextarea.disabled = true;
            healthConcernTextarea.value = '';
        }
        if (prioritySelect) {
            prioritySelect.disabled = true;
            prioritySelect.value = '';
        }
        if (symptomsTextarea) {
            symptomsTextarea.disabled = true;
            symptomsTextarea.value = '';
        }
        if (submitBtn) submitBtn.disabled = true;
        residentVerified = false;
        otpVerified = false;
        qrVerified = false;
        
        // Clear file input
        const receiptInput = document.getElementById('receipt');
        if (receiptInput) receiptInput.value = '';
        
        // Reset blur effects
        if (formFieldsSection) {
            formFieldsSection.classList.add('blurred');
            formFieldsSection.classList.remove('form-fields-reveal');
        }
        
        // Reset OTP section
        const otpRequestStep = document.getElementById('otpRequestStep');
        const otpVerifyStep = document.getElementById('otpVerifyStep');
        const otpVerifiedStep = document.getElementById('otpVerifiedStep');
        
        if (otpRequestStep) otpRequestStep.style.display = 'block';
        if (otpVerifyStep) otpVerifyStep.style.display = 'none';
        if (otpVerifiedStep) otpVerifiedStep.style.display = 'none';
        if (otpCodeInput) otpCodeInput.value = '';
        
        if (otpTimer) {
            clearInterval(otpTimer);
        }
        
        // Reset verification button to original state
        if (checkResidentBtn) {
            checkResidentBtn.innerHTML = '<i class="fas fa-search"></i> Verify';
            checkResidentBtn.classList.remove('btn-success');
            checkResidentBtn.classList.add('btn-outline-primary');
            checkResidentBtn.disabled = false;
        }
        
        // Reset QR upload input
        if (qrUploadInput) qrUploadInput.value = '';
        
        // Reset verification method to Manual Input
        if (manualInputRadio) {
            manualInputRadio.checked = true;
        }
        if (qrScanRadio) {
            qrScanRadio.checked = false;
        }
        
        // Show Manual Input section and hide QR Code section
        if (manualInputSection) manualInputSection.style.display = 'block';
        if (qrCodeSection) qrCodeSection.style.display = 'none';
        
        // Clear barangay ID input
        if (barangayIdInput) barangayIdInput.value = '';
        
        // Stop QR scanner if running
        stopQrScanner();
    }

    function showSuccess(message) {
        document.getElementById('successMessage').textContent = message;
        successAlert.style.display = 'block';
        successAlert.classList.add('show');
        errorAlert.style.display = 'none';
        errorAlert.classList.remove('show');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function showError(message) {
        document.getElementById('errorMessage').textContent = message;
        errorAlert.style.display = 'block';
        errorAlert.classList.add('show');
        successAlert.style.display = 'none';
        successAlert.classList.remove('show');
    }

    function hideError() {
        errorAlert.style.display = 'none';
        errorAlert.classList.remove('show');
    }
});
</script>
@endsection
