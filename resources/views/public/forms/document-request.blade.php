@extends('layouts.public.master')

@section('title', 'Request Document')

@section('content')
<!-- QR Code Scanner Library -->
<script src="https://unpkg.com/html5-qrcode"></script>
<!-- Hero Section with Background -->
<section class="position-relative" style="background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8b 100%); padding-top: 4rem; padding-bottom: 2rem; margin-top: 0;">
    <div class="container">
        <div class="text-center">
            <h1 class="fw-bold mb-1 text-white" style="font-size: 2.5rem; margin-top: 2.5rem; margin-bottom: 0.5rem;">Document Request</h1>
            <p class="text-white opacity-9" style="font-size: 1.1rem;">Request official barangay documents online</p>
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
                            <i class="fas fa-file-alt me-2"></i>Document Services
                        </h4>
                        <p class="mb-0 text-muted small">Complete the form below to request official documents</p>
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
                    <div id="errorAlert" class="alert alert-danger alert-dismissible fade" role="alert" style="display: none;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span id="errorMessage"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>

                    <form id="documentRequestForm">
                        @csrf
                        
                        <!-- Identity Verification Section -->
                        <div class="mb-3">
                            <label>Identity Verification <span class="text-danger">*</span></label>
                            
                            <!-- Verification Method Toggle -->
                            <div class="btn-group w-100" role="group" aria-label="Verification method" style="display: flex;">
                                <input type="radio" class="btn-check" name="verification_method" id="manual_input" value="manual" checked>
                                <label class="btn btn-outline-primary" for="manual_input" style="flex: 1; text-align: center;">
                                    <i class="fas fa-keyboard me-2"></i>Manual Input
                                </label>
                                <input type="radio" class="btn-check" name="verification_method" id="qr_scan" value="qr">
                                <label class="btn btn-outline-primary" for="qr_scan" style="flex: 1; text-align: center;">
                                    <i class="fas fa-qrcode me-2"></i>QR Code
                                </label>
                            </div>
                        </div>
                            
                        <!-- Barangay ID Section -->
                        <div class="mb-3" style="margin-top: 0;">
                            <label for="barangay_id" style="margin-bottom: 0.25rem; margin-top: -0.5rem;">Barangay ID <span class="text-danger">*</span></label>

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
                                        <i class="fas fa-camera me-2"></i>
                                        Scan QR Code
                                    </label>
                                    <input type="file" 
                                           class="form-control" 
                                           id="qr_upload" 
                                           accept="image/*"
                                           style="display: none;">
                                    <input type="radio" class="btn-check" name="qr_method" id="upload_qr" value="upload">
                                    <label class="btn btn-outline-primary" for="upload_qr" style="flex: 1; text-align: center;">
                                        <i class="fas fa-upload me-2"></i>
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
                        
                        <!-- Resident Information Display -->
                        <div id="residentInfo" class="card border-success mb-3" style="display: none;">
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
                                <h6 class="mb-0 text-white">
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

                            <!-- Document Type Section -->
                            <div class="mb-3">
                                <label for="document_type">Document Type <span class="text-danger">*</span></label>
                                <select class="form-control" id="document_type" name="document_type" required disabled>
                                    <option value="">Select Document Type</option>
                                    <option value="Barangay Clearance">Barangay Clearance</option>
                                    <option value="Certificate of Residency">Certificate of Residency</option>
                                    <option value="Certificate of Indigency">Certificate of Indigency</option>
                                    <option value="Certificate of Low Income">Certificate of Low Income</option>
                                    <option value="Business Permit">Business Permit</option>
                                </select>
                            </div>

                            <!-- Purpose Section -->
                            <div class="mb-3">
                                <label for="purpose">Purpose <span class="text-danger">*</span></label>
                                <textarea class="form-control" 
                                          id="purpose" 
                                          name="purpose" 
                                          rows="4" 
                                          placeholder="Please specify the purpose for requesting this document..."
                                          required
                                          disabled></textarea>
                                <small class="form-text text-muted">Provide detailed information about why you need this document</small>
                            </div>

                            <!-- GCash Payment Instructions -->
                            <div class="mb-3" id="paymentSection">
                                <label class="fw-bold">Payment Instructions</label>
                                <div class="alert alert-info mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Please pay <strong>₱50</strong> via GCash to <strong>0912345678 (LumangLipaGcash)</strong>.<br>
                                    Upload a screenshot or photo of your payment receipt below to proceed.
                                </div>
                            </div>

                            <!-- Receipt Upload -->
                            <div class="mb-4" id="receiptSection">
                                <label for="receipt">Upload GCash Payment Receipt <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="receipt" name="receipt" accept="image/*,.pdf" required disabled>
                                <small class="form-text text-muted">Accepted formats: JPG, PNG, PDF. Max size: 5MB.</small>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid" id="submitButtonSection" style="display: none !important;">
                                <button type="submit" 
                                        class="btn btn-primary btn-lg" 
                                        id="submitBtn"
                                        disabled>
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Submit Request
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Information Section -->
                    <div class="mt-5 p-4 bg-light rounded">
                        <h5 class="mb-3" style="color: #0d6efd;">
                            <i class="fas fa-info-circle me-2" style="color: #0d6efd;"></i>
                            Important Information
                        </h5>                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Identity verification is required for all document requests (QR code verification skips email OTP)
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                All document requests will be reviewed by the Barangay Office
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Processing time is typically 1-3 business days
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                You will be notified once your request is approved
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-check text-success me-2"></i>
                                Make sure all information provided is accurate and complete
                            </li>
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

<!-- Rating Feedback Modal -->
<div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-body p-5">
                <!-- Success Icon -->
                <div class="text-center mb-4">
                    <div class="success-checkmark mb-3">
                        <div class="check-icon" style="width: 80px; height: 80px; margin: 0 auto; position: relative;">
                            <span class="icon-line line-tip" style="position: absolute; width: 25px; height: 5px; background-color: #28a745; display: block; border-radius: 2px; left: 14px; top: 46px; transform: rotate(45deg);"></span>
                            <span class="icon-line line-long" style="position: absolute; width: 47px; height: 5px; background-color: #28a745; display: block; border-radius: 2px; right: 8px; top: 38px; transform: rotate(-45deg);"></span>
                            <div class="icon-circle" style="position: absolute; top: 0; left: 0; width: 80px; height: 80px; border-radius: 50%; border: 5px solid #28a745;"></div>
                        </div>
                    </div>
                    <h3 class="fw-bold mb-3">Request Submitted Successfully!</h3>
                </div>

                <!-- Rating Section -->
                <div class="text-center mb-4">
                    <p class="text-muted mb-3">How was your experience with our document request service?</p>
                    <div class="stars-container mb-3" style="font-size: 3rem; cursor: pointer;">
                        <i class="fas fa-star star" data-rating="1"></i>
                        <i class="fas fa-star star" data-rating="2"></i>
                        <i class="fas fa-star star" data-rating="3"></i>
                        <i class="fas fa-star star" data-rating="4"></i>
                        <i class="fas fa-star star" data-rating="5"></i>
                    </div>
                    <p class="rating-text fw-bold" style="color: #ffc107; font-size: 1.2rem; min-height: 30px;"></p>
                </div>

                <!-- Comment Section (Initially Hidden) -->
                <div class="comment-section mb-4" style="display: none;">
                    <label for="ratingComment" class="form-label text-muted">Tell us more (optional)</label>
                    <textarea class="form-control" id="ratingComment" rows="3" placeholder="Share your thoughts..."></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary btn-lg" id="submitRatingBtn" disabled style="border-radius: 8px;">
                        <i class="fas fa-paper-plane me-2"></i>Submit Feedback
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="skipRatingBtn" style="border-radius: 8px;">
                        Skip for now
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Force FontAwesome over Material Symbols */
.fas, .far, .fal, .fab {
    font-family: "Font Awesome 5 Free", "Font Awesome 5 Brands" !important;
    font-weight: 900;
    font-style: normal;
    font-variant: normal;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

/* Form Control Styling - Make borders visible and fix text alignment */
.form-control,
.form-select {
    border: 1px solid #ced4da !important;
    background-color: #fff !important;
    padding: 0.5rem 0.75rem !important;
    text-align: left !important;
}

/* File Input Styling */
.form-control[type="file"] {
    padding: 0.375rem 0.75rem !important;
    line-height: 1.5;
    cursor: pointer !important;
}

.form-control[type="file"]:disabled {
    cursor: not-allowed !important;
    background-color: #e9ecef !important;
}

.form-control[type="file"]::-webkit-file-upload-button {
    padding: 0.375rem 0.75rem;
    margin-right: 0.75rem;
    background-color: #f8f9fa;
    color: #495057;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    cursor: pointer;
}

.form-control[type="file"]::-webkit-file-upload-button:hover {
    background-color: #e9ecef;
}

.form-control[type="file"]:disabled::-webkit-file-upload-button {
    cursor: not-allowed;
    opacity: 0.65;
}

.form-control:focus,
.form-select:focus {
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
    background-color: #fff !important;
}

/* Input Group Styling */
.input-group {
    display: flex !important;
    flex-wrap: nowrap !important;
    align-items: stretch !important;
}

.input-group .form-control {
    border: 1px solid #ced4da !important;
    flex: 1 1 auto !important;
    min-width: 0 !important;
    padding: 0.5rem 0.75rem !important;
}

.input-group .btn {
    border: 2px solid #0d6efd !important;
    padding: 0.5rem 1rem !important;
    flex: 0 0 auto !important;
    white-space: nowrap !important;
    width: auto !important;
    display: flex !important;
    align-items: center !important;
}

/* Primary Buttons - White background with blue border */
.btn-primary {
    background: #ffffff !important;
    border: 2px solid #0d6efd !important;
    color: #0d6efd !important;
    padding: 0.5rem 1rem !important;
    outline: none !important;
    box-shadow: none !important;
}

.btn-primary:hover {
    background: #0d6efd !important;
    border-color: #0d6efd !important;
    color: #ffffff !important;
    transform: translateY(-1px);
    outline: none !important;
    box-shadow: none !important;
}

.btn-primary:focus,
.btn-primary:active {
    background: #0d6efd !important;
    border-color: #0d6efd !important;
    color: #ffffff !important;
    outline: none !important;
    box-shadow: none !important;
}

/* Outline Primary Buttons */
.btn-outline-primary {
    background: #ffffff !important;
    border: 2px solid #0d6efd !important;
    color: #0d6efd !important;
    padding: 0.5rem 1rem !important;
    outline: none !important;
    box-shadow: none !important;
}

.btn-outline-primary:hover {
    background: #0d6efd !important;
    border-color: #0d6efd !important;
    color: #ffffff !important;
    outline: none !important;
    box-shadow: none !important;
}

.btn-outline-primary:focus,
.btn-outline-primary:active {
    background: #0d6efd !important;
    border-color: #0d6efd !important;
    color: #ffffff !important;
    outline: none !important;
    box-shadow: none !important;
}

/* Button icons - force FontAwesome */
.btn i.fas,
.btn i.far {
    font-family: "Font Awesome 5 Free" !important;
    font-weight: 900 !important;
    display: inline-block;
    font-style: normal;
    font-variant: normal;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
}

/* Submit Button Styling */
#submitBtn:disabled {
    background: #e9ecef !important;
    border-color: #ced4da !important;
    color: #6c757d !important;
    cursor: not-allowed;
    opacity: 1;
}

#submitBtn:not(:disabled) {
    background: #ffffff !important;
    border: 2px solid #0d6efd !important;
    color: #0d6efd !important;
}

#submitBtn:not(:disabled):hover {
    background: #0d6efd !important;
    border-color: #0d6efd !important;
    color: #ffffff !important;
}

/* Button Group for Radio Buttons (Manual Input / QR Code) */
.btn-group .btn-outline-primary {
    background: #ffffff !important;
    border: 2px solid #0d6efd !important;
    color: #0d6efd !important;
    padding: 0.5rem 1rem !important;
    outline: none !important;
    box-shadow: none !important;
}

.btn-group .btn-check:checked + .btn-outline-primary {
    background: #0d6efd !important;
    color: #ffffff !important;
    outline: none !important;
    box-shadow: none !important;
}

.btn-group .btn-outline-primary:hover {
    background: #e7f1ff !important;
    color: #0d6efd !important;
    outline: none !important;
    box-shadow: none !important;
}

.btn-group .btn-outline-primary:focus,
.btn-group .btn-outline-primary:active {
    outline: none !important;
    box-shadow: none !important;
}

/* Button Group icons */
.btn-group .btn i {
    font-family: "Font Awesome 5 Free" !important;
    font-weight: 900 !important;
}

/* Force FontAwesome icon colors */
.btn-outline-primary i,
.btn-primary i {
    color: inherit !important;
    font-family: "Font Awesome 5 Free" !important;
}

/* Hide any emoji/unicode fallback content */
.btn::before,
.btn::after {
    content: none !important;
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

/* Rating Modal Styles */
.star {
    color: #ddd;
    transition: all 0.2s ease;
    cursor: pointer;
}

.star:hover {
    transform: scale(1.1);
}

.star.active {
    color: #ffc107;
}

.star.hover {
    color: #ffc107;
}

.comment-section {
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

#ratingModal .modal-content {
    animation: modalSlideUp 0.3s ease;
}

@keyframes modalSlideUp {
    from {
        opacity: 0;
        transform: translateY(50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('documentRequestForm');
    const barangayIdInput = document.getElementById('barangay_id');
    const checkResidentBtn = document.getElementById('checkResidentBtn');
    const residentInfo = document.getElementById('residentInfo');
    const otpSection = document.getElementById('otpSection');
    const formFieldsSection = document.getElementById('formFieldsSection');
    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const verifyOtpBtn = document.getElementById('verifyOtpBtn');
    const resendOtpBtn = document.getElementById('resendOtpBtn');
    const otpCodeInput = document.getElementById('otp_code');
    const documentTypeSelect = document.getElementById('document_type');
    const purposeTextarea = document.getElementById('purpose');
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
    
    // Save original OTP section HTML before it can be replaced by QR verification
    const originalOtpSectionHTML = otpSection ? otpSection.innerHTML : '';

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

    // QR Code method selection
    scanQrRadio.addEventListener('change', function() {
        if (this.checked) {
            qrScannerModal.show();
            // Wait for modal to be fully shown before starting scanner
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
                if (uploadQrRadio) uploadQrRadio.checked = false; // Uncheck radio
                return;
            }
            
            // Validate file type
            if (!file.type.startsWith('image/')) {
                showError('Please select a valid image file.');
                qrUploadInput.value = '';
                if (uploadQrRadio) uploadQrRadio.checked = false; // Uncheck radio
                return;
            }
            
            const uploadLabel = document.querySelector('label[for="upload_qr"]');
            const originalHtml = uploadLabel.innerHTML;
            uploadLabel.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
            
            // Create FormData to send file to server
            const formData = new FormData();
            formData.append('qr_image', file);
            
            // Send to server API for QR code decoding
            fetch('{{ route("documents.decode-qr") }}', {
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
                console.log('QR decode response:', data); // Debug log
                
                if (data.success) {
                    console.log('QR data received:', data.qr_data); // Debug log
                    console.log('Debug info:', data.debug_info); // Debug log
                    
                    handleQrCodeData(data.qr_data);
                    showSuccess('QR Code uploaded and processed successfully!');
                } else {
                    console.error('QR decode failed:', data.message); // Debug log
                    showError(data.message || 'Failed to decode QR code. Please ensure the image contains a valid QR code.');
                }
            })
            .catch(error => {
                console.error('QR Code decode failed:', error);
                showError('Failed to process QR code. Please check your internet connection and try again.');
            })
            .finally(() => {
                const uploadLabel = document.querySelector('label[for="upload_qr"]');
                uploadLabel.innerHTML = '<i class="fas fa-upload me-2"></i>Upload QR Code';
                qrUploadInput.value = '';
                // Uncheck the upload radio button after processing
                if (uploadQrRadio) uploadQrRadio.checked = false;
            });
        }
    });

    // Handle QR scanner modal close
    document.getElementById('qrScannerModal').addEventListener('hidden.bs.modal', function() {
        stopQrScanner();
        // Uncheck the scan radio button when modal is closed
        if (scanQrRadio) scanQrRadio.checked = false;
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
        fetch('{{ route("documents.check-resident") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ 
                barangay_id: barangayId,
                qr_verified: true // Flag to indicate QR verification
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('residentName').textContent = data.resident.name;
                document.getElementById('residentAddress').textContent = data.resident.address;
                document.getElementById('residentAge').textContent = data.resident.age;
                document.getElementById('residentContact').textContent = data.resident.contact_number || 'N/A';
                
                residentInfo.style.display = 'block';
                residentVerified = true;
                
                // Skip OTP verification for QR code users
                otpVerified = true;
                qrVerified = true; // Set QR verification flag
                
                // Show QR verification success instead of OTP section
                showQrVerificationSuccess();
                
                // Enable form fields immediately
                enableFormFields();
                
                showSuccess('QR Code verified successfully! You can now proceed with your document request.');
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
        
        documentTypeSelect.disabled = false;
        purposeTextarea.disabled = false;
        
        // Enable receipt file input
        const receiptInput = document.getElementById('receipt');
        if (receiptInput) {
            receiptInput.disabled = false;
        }
        
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

        fetch('{{ route("documents.check-resident") }}', {
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

    // OTP Event Handlers - Using event delegation to work after HTML restoration
    document.addEventListener('click', function(e) {
        // Send OTP
        if (e.target && e.target.id === 'sendOtpBtn') {
            const sendBtn = e.target;
            const barangayId = barangayIdInput.value.trim();
            
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

            fetch('{{ route("documents.send-otp") }}', {
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
                sendBtn.disabled = false;
                sendBtn.innerHTML = '<i class="fas fa-envelope me-2"></i>Send OTP to Email';
            });
        }
        
        // Verify OTP
        if (e.target && e.target.id === 'verifyOtpBtn') {
            const verifyBtn = e.target;
            const barangayId = barangayIdInput.value.trim();
            const otpCodeInput = document.getElementById('otp_code');
            const otpCode = otpCodeInput ? otpCodeInput.value.trim() : '';
            
            if (!otpCode || otpCode.length !== 6) {
                showError('Please enter a valid 6-digit OTP code');
                return;
            }

            verifyBtn.disabled = true;
            verifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';

            fetch('{{ route("documents.verify-otp") }}', {
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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    otpVerified = true;
                    residentVerified = true;
                    const otpVerifyStep = document.getElementById('otpVerifyStep');
                    const otpVerifiedStep = document.getElementById('otpVerifiedStep');
                    if (otpVerifyStep) otpVerifyStep.style.display = 'none';
                    if (otpVerifiedStep) otpVerifiedStep.style.display = 'block';
                    
                    // Remove blur effect and enable form fields
                    formFieldsSection.classList.remove('blurred');
                    formFieldsSection.classList.add('form-fields-reveal');
                    
                    documentTypeSelect.disabled = false;
                    purposeTextarea.disabled = false;
                    
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
                verifyBtn.disabled = false;
                verifyBtn.innerHTML = '<i class="fas fa-check me-2"></i>Verify OTP';
            });
        }
        
        // Resend OTP
        if (e.target && e.target.id === 'resendOtpBtn') {
            const otpVerifyStep = document.getElementById('otpVerifyStep');
            const otpRequestStep = document.getElementById('otpRequestStep');
            if (otpVerifyStep) otpVerifyStep.style.display = 'none';
            if (otpRequestStep) otpRequestStep.style.display = 'block';
            
            const otpCodeInput = document.getElementById('otp_code');
            if (otpCodeInput) otpCodeInput.value = '';
            
            if (otpTimer) {
                clearInterval(otpTimer);
            }
        }
    });

    // OTP input formatting - Using event delegation
    document.addEventListener('input', function(e) {
        if (e.target && e.target.id === 'otp_code') {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
            if (e.target.value.length === 6) {
                const verifyBtn = document.getElementById('verifyOtpBtn');
                if (verifyBtn) verifyBtn.focus();
            }
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

    // Form validation
    function checkFormValidity() {
        const receiptInput = document.getElementById('receipt');
        // All users need receipt upload, QR only skips OTP verification
        const isValid = residentVerified && 
                       (otpVerified || qrVerified) &&
                       documentTypeSelect.value && 
                       purposeTextarea.value.trim() &&
                       receiptInput.files.length > 0;
        submitBtn.disabled = !isValid;
    }

    documentTypeSelect.addEventListener('change', checkFormValidity);
    purposeTextarea.addEventListener('input', checkFormValidity);
    document.getElementById('receipt').addEventListener('change', checkFormValidity);

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

        const receiptInput = document.getElementById('receipt');
        // All users need to upload receipt regardless of verification method
        if (!receiptInput.files.length) {
            showError('Please upload your GCash payment receipt.');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';

        const formData = new FormData();
        formData.append('barangay_id', barangayIdInput.value);
        formData.append('document_type', documentTypeSelect.value);
        formData.append('purpose', purposeTextarea.value);
        formData.append('receipt', receiptInput.files[0]);
        formData.append('verification_method', qrVerified ? 'qr' : 'manual');

        fetch('{{ route("documents.store") }}', {
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
                // Show rating modal instead of just success message
                // Access request_id from data.data object
                const requestId = data.data?.request_id || data.request_id;
                showRatingModal(requestId);
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
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Submit Request';
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
        
        if (documentTypeSelect) {
            documentTypeSelect.disabled = true;
            documentTypeSelect.value = '';
        }
        if (purposeTextarea) {
            purposeTextarea.disabled = true;
            purposeTextarea.value = '';
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
        if (otpSection) {
            otpSection.style.display = 'none';
            // Restore original OTP section HTML (in case it was replaced by QR verification message)
            otpSection.innerHTML = originalOtpSectionHTML;
        }
        
        // Hide submit button section
        const submitButtonSection = document.getElementById('submitButtonSection');
        if (submitButtonSection) {
            submitButtonSection.style.setProperty('display', 'none', 'important');
        }
        
        if (documentTypeSelect) {
            documentTypeSelect.disabled = true;
            documentTypeSelect.value = '';
        }
        if (purposeTextarea) {
            purposeTextarea.disabled = true;
            purposeTextarea.value = '';
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
        
        // Reset OTP section steps
        const otpRequestStep = document.getElementById('otpRequestStep');
        const otpVerifyStep = document.getElementById('otpVerifyStep');
        const otpVerifiedStep = document.getElementById('otpVerifiedStep');
        
        if (otpRequestStep) otpRequestStep.style.display = 'block';
        if (otpVerifyStep) otpVerifyStep.style.display = 'none';
        if (otpVerifiedStep) otpVerifiedStep.style.display = 'none';
        
        // Clear OTP input
        const otpCodeInput = document.getElementById('otp_code');
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

    // ============ RATING SYSTEM ============
    const ratingModal = new bootstrap.Modal(document.getElementById('ratingModal'));
    const stars = document.querySelectorAll('.star');
    const ratingText = document.querySelector('.rating-text');
    const commentSection = document.querySelector('.comment-section');
    const ratingComment = document.getElementById('ratingComment');
    const submitRatingBtn = document.getElementById('submitRatingBtn');
    const skipRatingBtn = document.getElementById('skipRatingBtn');
    let selectedRating = 0;
    let currentRequestId = null;

    const ratingMessages = {
        1: "😞 We're sorry to hear that",
        2: "😕 We can do better",
        3: "😊 Good!",
        4: "😃 Great!",
        5: "🌟 Excellent!"
    };

    // Star hover and click effects
    stars.forEach(star => {
        star.addEventListener('mouseenter', function() {
            const rating = parseInt(this.dataset.rating);
            highlightStars(rating);
        });

        star.addEventListener('click', function() {
            selectedRating = parseInt(this.dataset.rating);
            highlightStars(selectedRating, true);
            ratingText.textContent = ratingMessages[selectedRating];
            ratingText.style.color = '#ffc107';
            commentSection.style.display = 'block';
            submitRatingBtn.disabled = false;
        });
    });

    // Reset stars on mouse leave
    document.querySelector('.stars-container').addEventListener('mouseleave', function() {
        if (selectedRating > 0) {
            highlightStars(selectedRating, true);
        } else {
            resetStars();
        }
    });

    function highlightStars(rating, permanent = false) {
        stars.forEach(star => {
            const starRating = parseInt(star.dataset.rating);
            if (starRating <= rating) {
                if (permanent) {
                    star.classList.add('active');
                    star.classList.remove('hovered');
                } else {
                    star.classList.add('hovered');
                }
            } else {
                star.classList.remove('active', 'hovered');
            }
        });
    }

    function resetStars() {
        stars.forEach(star => {
            star.classList.remove('active', 'hovered');
        });
        ratingText.textContent = '';
    }

    // Submit rating
    submitRatingBtn.addEventListener('click', function() {
        if (selectedRating === 0) return;

        submitRatingBtn.disabled = true;
        submitRatingBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';

        const feedbackData = {
            request_id: currentRequestId,
            rating: selectedRating,
            comment: ratingComment.value.trim(),
            service_type: 'document_request'
        };

        fetch('{{ route("feedback.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(feedbackData)
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Feedback response:', data);
            if (data.success) {
                // Show thank you message
                document.querySelector('#ratingModal .modal-body').innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-heart text-danger mb-3" style="font-size: 4rem;"></i>
                        <h4 class="fw-bold mb-3">Thank You for Your Feedback!</h4>
                        <p class="text-muted mb-4">Your feedback helps us improve our services.</p>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    </div>
                `;
                setTimeout(() => {
                    ratingModal.hide();
                    resetRatingModal();
                }, 3000);
            } else {
                // Handle error response
                throw new Error(data.message || 'Failed to submit feedback');
            }
        })
        .catch(error => {
            console.error('Error submitting feedback:', error);
            alert('Failed to submit feedback. Please try again. Error: ' + error.message);
            submitRatingBtn.disabled = false;
            submitRatingBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Submit Feedback';
        });
    });

    // Skip rating
    skipRatingBtn.addEventListener('click', function() {
        ratingModal.hide();
        resetRatingModal();
    });

    function resetRatingModal() {
        selectedRating = 0;
        resetStars();
        ratingComment.value = '';
        commentSection.style.display = 'none';
        submitRatingBtn.disabled = true;
        submitRatingBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Submit Feedback';
        ratingText.textContent = '';
    }

    function showRatingModal(requestId) {
        currentRequestId = requestId;
        ratingModal.show();
    }
    // ======================================
});
</script>
@endsection
