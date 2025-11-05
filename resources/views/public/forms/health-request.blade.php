@extends('layouts.public.master')

@section('title', 'Health Service Registration')

@section('content')
<!-- QR Code Scanner Library -->
<script src="https://unpkg.com/html5-qrcode"></script>
<!-- Hero Section with Background -->
<section class="position-relative" style="background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8b 100%); padding-top: 4rem; padding-bottom: 2rem; margin-top: 0;">
    <div class="container">
        <div class="text-center">
            <h1 class="fw-bold mb-1 text-white" style="font-size: 2.5rem; margin-top: 2.5rem; margin-bottom: 0.5rem;">Health Service Registration</h1>
            <p class="text-white opacity-9" style="font-size: 1.1rem;">Register for health services and check-ups at Barangay Lumanglipa</p>
        </div>
    </div>
</section>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0" style="border-radius: 12px; margin-top: -4rem;">                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center" style="border-radius: 12px 12px 0 0; padding: 1.5rem;">
                    <div>
                        <h4 class="mb-0 fw-bold" style="color: #2A7BC4;">
                            <i class="fas fa-user-plus me-2"></i>Health Service Registration
                        </h4>
                        <p class="mb-0 text-muted small">Register to attend health services when available at the barangay</p>
                    </div>
                </div><div class="card-body p-4" style="background: #ffffff;">
                    <!-- Toast Notifications -->
                    <div aria-live="polite" aria-atomic="true" class="position-relative">
                        <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;">
                            <!-- Toasts will be injected here -->
                        </div>
                    </div>

                    <form id="healthRequestForm">
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
                                      
                                        Scan QR Code
                                    </label>
                                    <input type="file"
                                           class="form-control"
                                           id="qr_upload"
                                           accept="image/*"
                                           style="display: none;">
                                    <input type="radio" class="btn-check" name="qr_method" id="upload_qr" value="upload">
                                    <label class="btn btn-outline-primary" for="upload_qr" style="flex: 1; text-align: center;">
                                        
                                        Upload QR Code
                                    </label>
                                </div>
                                <div class="row mt-2">
                                   
                                   
                                </div>
                            </div>
                        </div>                        <!-- Resident Information Display -->
                        <div id="residentInfo" class="card mb-3" style="display: none; border-radius: 12px; border: 2px solid #2A7BC4;">
                            <div class="card-header" style="border-radius: 12px 12px 0 0; background: #2A7BC4; border-bottom: none; padding: 1rem 1.5rem;">
                                <h6 class="mb-0 fw-bold" style="color: #fff; font-size: 1rem; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif !important; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-user-check" style="font-size: 1.1rem; margin-right: 0.5rem; color: #fff;"></i>
                                    Verified Resident Information
                                </h6>
                            </div>
                            <div class="card-body" style="padding: 1.25rem 1.5rem; background: #fff; border-radius: 0 0 12px 12px;">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <p class="mb-1 fw-bold" style="color: #2A7BC4;">Name:</p>
                                        <p id="residentName" class="text-muted mb-2" style="font-size: 1.08rem;"></p>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <p class="mb-1 fw-bold" style="color: #2A7BC4;">Address:</p>
                                        <p id="residentAddress" class="text-muted mb-2" style="font-size: 1.08rem;"></p>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <p class="mb-1 fw-bold" style="color: #2A7BC4;">Age:</p>
                                        <p id="residentAge" class="text-muted mb-2" style="font-size: 1.08rem;"></p>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <p class="mb-1 fw-bold" style="color: #2A7BC4;">Contact Number:</p>
                                        <p id="residentContact" class="text-muted mb-2" style="font-size: 1.08rem;"></p>
                                    </div>
                                </div>
                            </div>
                        </div>                        <!-- OTP Verification Section -->
                        <div id="otpSection" class="card mb-4" style="display: none; border: 2px solid #2A7BC4;">
                            <div class="card-header" style="background: #2A7BC4;">
                                <h6 class="mb-0 text-white" style="font-size: 1rem; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif !important; display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-shield-alt me-2" style="font-size: 1.1rem;"></i>
                                    Email Verification Required / Kailangan ang Pagpapatunay ng Email
                                </h6>
                            </div>
                            <div class="card-body">
                                <div id="otpRequestStep">
                                    <p class="mb-3">
                                        <i class="fas fa-info-circle me-2" style="color: #2A7BC4;"></i>
                                        To proceed with your health service request, we need to verify your identity. 
                                        An OTP (One-Time Password) will be sent to your registered email address.
                                        <br><em class="text-muted">Upang magpatuloy sa inyong kahilingan ng health service, kailangan naming i-verify ang inyong pagkakakilanlan. 
                                        Ang isang OTP (One-Time Password) ay ipapadala sa inyong nakarehistro ng email address.</em>
                                    </p>
                                    <button type="button" class="btn btn-outline-primary" id="sendOtpBtn" style="border-color: #2A7BC4; color: #2A7BC4;">
                                        <i class="fas fa-envelope me-2"></i>
                                        Ipadala ang OTP sa Email
                                    </button>
                                </div>                                  <div id="otpVerifyStep" style="display: none;">
                                    <p class="mb-3">
                                        
                                        A 6-digit OTP has been sent to: <strong id="emailHint"></strong>
                                        <br><em class="text-muted">Ang 6-digit na OTP ay ipinadala na sa:</em>
                                    </p>
                                    <div class="row align-items-end">                                        <div class="col-md-6">
                                            <label for="otp_code" class="form-label">Enter OTP Code / Ilagay ang OTP Code</label>
                                            <input type="text" 
                                                   class="form-control form-control-lg text-center" 
                                                   id="otp_code" 
                                                   placeholder="000000" 
                                                   maxlength="6"
                                                   pattern="[0-9]{6}">
                                        </div>
                                        <div class="col-md-6 d-flex gap-2">
                                            <button type="button" class="btn btn-outline-primary flex-fill" id="verifyOtpBtn" style="border-color: #2A7BC4; color: #2A7BC4;">
                                                Verify OTP
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary flex-fill" id="resendOtpBtn">
                                                Resend 
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-text mt-2">
                                        <i class="fas fa-clock text-muted me-1"></i>
                                        <span id="otpTimer">OTP expires in 10:00 / Mag-eexpire ang OTP sa 10:00</span>
                                    </div>
                                </div>
                                  <div id="otpVerifiedStep" style="display: none;">
                                    <div class="alert alert-success mb-0">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Email verified successfully! You can now proceed with your health service request.
                                        <br><em>Na-verify na ang email! Maaari nang magpatuloy sa inyong kahilingan ng health service.</em>
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
                            </div>                            <!-- Health Service Type Section -->
                            <div class="mb-3">
                                <label for="service_type">Service Type <span class="text-danger">*</span></label>
                                <select class="form-control" id="service_type" name="service_type" required disabled>
                                    <option value="">Select Service Type</option>
                                    <optgroup label="Immunization Services">
                                        <option value="immunization_babies">Immunization for Babies</option>
                                        <option value="immunization_children">Immunization for Children</option>
                                        <option value="flu_vaccine">Flu Vaccine</option>
                                        <option value="pneumococcal_vaccine">Pneumococcal Vaccine</option>
                                        <option value="hpv_vaccine">HPV Vaccine</option>
                                    </optgroup>
                                    <optgroup label="Check-up Services">
                                        <option value="checkup_general">General Check-up</option>
                                        <option value="checkup_senior">Senior Citizen Check-up</option>
                                        <option value="blood_pressure_monitoring">Blood Pressure Monitoring</option>
                                    </optgroup>
                                    <optgroup label="Maternal Health">
                                        <option value="prenatal_checkup">Prenatal Check-up</option>
                                        <option value="family_planning">Family Planning</option>
                                    </optgroup>
                                    <optgroup label="Other Services">
                                        <option value="medicine_distribution">Medicine Distribution</option>
                                        <option value="health_consultation">Health Consultation</option>
                                        <option value="other">Other Health Service</option>
                                    </optgroup>                                </select>
                                <small class="form-text text-muted">Choose the type of health service you need</small>
                            </div>                            <!-- Visit Type Section -->
                            <div class="mb-3">
                                <label for="appointment_type">When will you visit? <span class="text-danger">*</span></label>
                                <select class="form-control" id="appointment_type" name="appointment_type" required disabled>
                                    <option value="">Select Visit Type</option>
                                    <option value="walk-in">Walk-in (When health service is available)</option>
                                    <option value="scheduled">Pre-register for next scheduled service</option>
                                </select>
                                <small class="form-text text-muted">Choose when you plan to come for the health service</small>
                            </div>                            <!-- Health Concern/Purpose Section -->
                            <div class="mb-3">
                                <label for="health_concern">Reason for Registration <span class="text-danger">*</span></label>
                                <textarea class="form-control" 
                                          id="health_concern" 
                                          name="health_concern" 
                                          rows="4" 
                                          placeholder="Describe your health concern or reason for attending the health service..."
                                          required
                                          disabled></textarea>
                                <small class="form-text text-muted">Help us prepare for your visit by describing your health concern</small>
                            </div>

                            <!-- Priority Level -->
                            <div class="mb-3">
                                <label for="priority">Priority Level <span class="text-danger">*</span></label>
                                <select class="form-control" id="priority" name="priority" required disabled>
                                    <option value="">Select Priority</option>
                                    <option value="low">Low - Regular check-up or routine service</option>
                                    <option value="medium">Medium - General health concern</option>
                                    <option value="high">High - Urgent but not life-threatening</option>
                                    <option value="emergency">Emergency - Immediate attention needed</option>
                                </select>
                                <small class="form-text text-muted">Select the urgency level of your health concern</small>
                            </div>

                            <!-- Additional Symptoms -->
                            <div class="mb-3">
                                <label for="symptoms">Additional Symptoms (Optional)</label>
                                <textarea class="form-control" 
                                          id="symptoms" 
                                          name="symptoms" 
                                          rows="3"                                          placeholder="Any additional symptoms or medical history relevant to your visit..."
                                          disabled></textarea>
                                <small class="form-text text-muted">Include any other symptoms or relevant medical information</small>
                            </div>                            <!-- Submit Button -->
                            <div class="d-grid" id="submitButtonSection" style="display: none !important;">
                                <button type="submit" 
                                        class="btn btn-primary btn-lg" 
                                        id="submitBtn"
                                        disabled>
                                    <i class="fas fa-user-check me-2"></i>
                                    Register for Health Service
                                </button>
                            </div>
                        </div>
                    </form>                    <!-- Information Section -->
                    <div class="mt-5 p-4 bg-light rounded">
                        <h5 class="mb-3" style="color: #0d6efd;">
                            <i class="fas fa-info-circle me-2" style="color: #0d6efd;"></i>
                            Important Information
                        </h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Register in advance so we know how many residents will attend
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Municipality nurse visits weekly - check announcements for schedule
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Your registration helps us prepare the right services and supplies
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                You will be notified when health services are available
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-check text-success me-2"></i>
                                Bring your Barangay ID when you visit for faster verification
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

/* Ensure placeholder text is visible like document request form */
.form-control::placeholder {
    color: #6c757d;
    opacity: 1;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif !important;
    font-size: 1rem !important;
}

/* Form Control Styling - Make borders visible and fix text alignment */
.form-control,
.form-select {
    border: 1px solid #ced4da !important;
    background-color: #fff !important;
    padding: 0.5rem 0.75rem !important;
    font-weight: 400 !important;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif !important;
    text-align: left !important;
    font-size: 0.875rem !important;
}

/* Select dropdown options - smaller font */
.form-control option,
.form-select option {
    font-size: 0.875rem !important;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif !important;
}

/* Ensure placeholder text is visible like document request form */
.form-control::placeholder {
    color: #6c757d !important;
    opacity: 1 !important;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif !important;
    font-size: 1rem !important;
}

/* Input Group Styling - Match document request exactly */
.input-group {
    display: flex !important;
    flex-wrap: nowrap !important;
    align-items: stretch !important;
}

.input-group .form-control {
    font-size: 1rem !important;
    font-weight: 400 !important;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif !important;
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
    border: 1px solid #0d6efd !important;
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
    border: 1px solid #0d6efd !important;
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

.alert {
    border: none;
    border-radius: 10px;
}

#otp_code:focus {
    border-color: #2A7BC4 !important;
    box-shadow: 0 0 0 0.2rem rgba(42, 123, 196, 0.25) !important;
}

#otpSection .row.align-items-end {
    margin-top: 1rem !important;
    gap: 0.5rem;
}

#otpTimer {
    font-weight: 600 !important;
    color: #2A7BC4 !important;
    font-size: 1.08rem !important;
    margin-top: 0.5rem !important;
}

.alert {
    border: none;
    border-radius: 10px;
}

.card-header.bg-warning {
    border-bottom: 2px solid #ffc107;
}

.btn-warning:hover {
    background-color: #e0a800;
    border-color: #d39e00;
}

/* OTP Send button - outlined blue with solid blue on hover */
#sendOtpBtn {
    border-color: #2A7BC4 !important;
    color: #2A7BC4 !important;
    background-color: transparent !important;
    transition: all 0.3s ease;
}

#sendOtpBtn:hover {
    background-color: #2A7BC4 !important;
    color: white !important;
    border-color: #2A7BC4 !important;
}

#sendOtpBtn:focus {
    box-shadow: 0 0 0 0.2rem rgba(42, 123, 196, 0.25) !important;
}

/* OTP Verify button - outlined blue with solid blue on hover */
#verifyOtpBtn {
    border-color: #2A7BC4 !important;
    color: #2A7BC4 !important;
    background-color: transparent !important;
    transition: all 0.3s ease;
}

#verifyOtpBtn:hover {
    background-color: #2A7BC4 !important;
    color: white !important;
    border-color: #2A7BC4 !important;
}

#verifyOtpBtn:focus {
    box-shadow: 0 0 0 0.2rem rgba(42, 123, 196, 0.25) !important;
}

/* Modern OTP Card Design */
#otpSection.card {
    border-radius: 14px !important;
    border: 2px solid #2A7BC4 !important;
    box-shadow: 0 4px 18px rgba(0,0,0,0.08);
    overflow: hidden;
}

#otpSection .card-header {
    background: #2A7BC4 !important;
    color: #fff !important;
    border-radius: 12px 12px 0 0 !important;
    border-bottom: none !important;
    padding: 1rem 1.5rem !important;
    font-size: 1.15rem !important;
    font-weight: 600 !important;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

#otpSection .card-header i {
    font-size: 1.3rem;
    margin-right: 0.5rem;
}

#otpSection .card-body {
    padding: 1.5rem !important;
    background: #fff !important;
    border-radius: 0 0 12px 12px !important;
}

#otpSection .alert {
    background: none !important;
    color: #333 !important;
    border: none !important;
    font-size: 1.08rem !important;
    margin-bottom: 1rem !important;
}

#otpSection label.form-label {
    font-weight: 500 !important;
    color: #666 !important;
    margin-bottom: 0.5rem !important;
}

#otp_code {
    font-size: 1.6rem !important;
    letter-spacing: 0.5rem !important;
    font-weight: 600 !important;
    border-radius: 8px !important;
    padding: 0.5rem 1rem !important;
    border: 1.5px solid #2A7BC4 !important;
    background: #fff !important;
    margin-bottom: 1rem !important;
    text-align: center !important;
}

#otp_code::placeholder {
    font-size: 1.6rem !important;
    letter-spacing: 0.5rem !important;
    font-weight: 600 !important;
    text-align: center !important;
    opacity: 0.5 !important;
}

#otp_code:focus {
    border-color: #2A7BC4 !important;
    box-shadow: 0 0 0 0.2rem rgba(42, 123, 196, 0.25) !important;
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
    background-color: #0d6efd !important;
    border-color: #0d6efd !important;
    color: white !important;
}

/* Force FontAwesome for all icons, prevent Material Symbols override */
.btn i,
.btn-outline-primary i,
.btn-primary i,
label i,
button i,
.fas, .far, .fal, .fab {
    font-family: 'Font Awesome 5 Free', 'Font Awesome 5 Brands' !important;
    font-weight: 900 !important;
    font-style: normal;
    font-variant: normal;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
}

/* Ensure button text and labels use system font */
.btn,
.btn-outline-primary,
label,
h1, h2, h3, h4, h5, h6 {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif !important;
}

/* Enforce blue color scheme for all buttons */
.btn-outline-primary {
    color: #0d6efd !important;
    border-color: #0d6efd !important;
}

.btn-outline-primary:hover {
    background-color: #0d6efd !important;
    border-color: #0d6efd !important;
    color: white !important;
}

.btn-check:checked + .btn-outline-primary {
    background-color: #0d6efd !important;
    border-color: #0d6efd !important;
    color: white !important;
}

/* Button Group for Radio Buttons (Manual Input / QR Code) */
.btn-group .btn-outline-primary {
    background: #ffffff !important;
    border: 1px solid #0d6efd !important;
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

.btn-check:focus + .btn-outline-primary {
    border-color: #0d6efd !important;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25) !important;
}

/* Force icon colors to match button text color */
.btn-outline-primary i {
    color: inherit !important;
}

.btn-check:checked + .btn-outline-primary i,
.btn-outline-primary:hover i,
.btn-outline-primary:focus i,
.btn-outline-primary:active i,
.btn-outline-primary.active i {
    color: inherit !important;
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
    const serviceTypeSelect = document.getElementById('service_type');    const appointmentTypeSelect = document.getElementById('appointment_type');
    const healthConcernTextarea = document.getElementById('health_concern');
    const prioritySelect = document.getElementById('priority');
    const symptomsTextarea = document.getElementById('symptoms');
    const submitBtn = document.getElementById('submitBtn');
    
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
    let isScanning = false; // Flag to prevent multiple scans

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
    });    qrUploadInput.addEventListener('change', function(e) {
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
                console.log('QR Upload Response:', data); // Debug log
                
                if (data.success && data.qr_data) {
                    // Process the QR data
                    handleQrCodeData(data.qr_data);
                } else {
                    showError(data.message || 'Failed to decode QR code. Please ensure the image contains a valid QR code.');
                    if (uploadQrRadio) uploadQrRadio.checked = false;
                }
            })
            .catch(error => {
                console.error('QR Upload Error:', error);
                showError('Failed to process QR code. Please check your internet connection and try again.');
                if (uploadQrRadio) uploadQrRadio.checked = false;
            })
            .finally(() => {
                uploadLabel.innerHTML = originalHtml;
                qrUploadInput.value = '';
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
    }    function initializeScanner() {
        const scannerStatus = document.getElementById('scannerStatus');
        const statusText = document.getElementById('statusText');
        const qrReaderDiv = document.getElementById('qr-reader');
        
        // Clear any existing content
        qrReaderDiv.innerHTML = '';
        
        // Reset scanning flag
        isScanning = false;
        
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
                            // Prevent multiple scans
                            if (isScanning) {
                                return;
                            }
                            isScanning = true;
                            
                            console.log('QR Code detected:', qrCodeMessage);
                            statusText.textContent = 'QR Code detected! Processing...';
                            
                            // Stop scanner immediately
                            html5QrCode.stop().then(() => {
                                handleQrCodeData(qrCodeMessage);
                                qrScannerModal.hide();
                                showSuccess('QR Code scanned successfully!');
                            }).catch(err => {
                                console.error('Error stopping scanner:', err);
                                handleQrCodeData(qrCodeMessage);
                                qrScannerModal.hide();
                                showSuccess('QR Code scanned successfully!');
                            });
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
    }    function stopQrScanner() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode = null;
                isScanning = false; // Reset scanning flag
            }).catch(err => {
                console.error('Error stopping scanner:', err);
                html5QrCode = null;
                isScanning = false; // Reset scanning flag
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
    }    function handleQrCodeData(qrData) {
        console.log('Processing QR data:', qrData); // Debug log
        console.log('QR data type:', typeof qrData); // Debug log
        console.log('QR data length:', qrData ? qrData.length : 'null'); // Debug log
        
        try {
            // Assuming QR code contains the barangay ID
            // You can modify this logic based on your QR code format
            let barangayId = qrData.trim();
            
            console.log('Extracted Barangay ID (initial):', barangayId); // Debug log
            
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
            
            console.log('Final barangayId to verify:', barangayId); // Debug log
            
            barangayIdInput.value = barangayId;
            qrVerified = true;
            
            // Automatically verify resident with QR data
            verifyResidentWithQr(barangayId);
            
        } catch (error) {
            console.error('Error processing QR data:', error);
            showError('Invalid QR code format. Please try again.');
        }
    }    function verifyResidentWithQr(barangayId) {
        console.log('Verifying resident with barangay ID:', barangayId); // Debug log
        
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
        .then(response => {
            console.log('Resident verification response status:', response.status); // Debug log
            return response.json();
        })
        .then(data => {
            console.log('Resident verification response data:', data); // Debug log
            
            if (data.success) {
                console.log('Resident verified successfully!'); // Debug log
                
                document.getElementById('residentName').textContent = data.resident.name;
                document.getElementById('residentAddress').textContent = data.resident.address;
                document.getElementById('residentAge').textContent = data.resident.age;
                document.getElementById('residentContact').textContent = data.resident.contact || data.resident.contact_number || 'N/A';
                
                residentInfo.style.display = 'block';
                residentVerified = true;
                
                // Skip OTP verification for QR code users
                otpVerified = true;
                qrVerified = true; // Set QR verification flag
                
                // Show QR verification success instead of OTP section
                showQrVerificationSuccess();
                
                // Enable form fields immediately
                enableFormFields();
                
                showSuccess('QR Code verified successfully! You can now proceed with your health service registration.');
                hideError();
            } else {
                console.error('Resident verification failed:', data.message); // Debug log
                showError(data.message || 'Invalid QR code or resident not found');
                resetForm();
            }
        })
        .catch(error => {
            console.error('Error verifying resident:', error);
            showError('An error occurred while verifying the QR code. Please try again.');
            resetForm();
        });
    }function showQrVerificationSuccess() {
        // QR verification success is shown via toastr notification
        // No need to display additional success card
    }    function enableFormFields() {
        // Remove blur effect and enable form fields
        formFieldsSection.classList.remove('blurred');
        formFieldsSection.classList.add('form-fields-reveal');
        
        serviceTypeSelect.disabled = false;
        appointmentTypeSelect.disabled = false;
        healthConcernTextarea.disabled = false;
        prioritySelect.disabled = false;
        symptomsTextarea.disabled = false;
        
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
        })        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('residentName').textContent = data.resident.name;
                document.getElementById('residentAddress').textContent = data.resident.address;
                document.getElementById('residentAge').textContent = data.resident.age;
                document.getElementById('residentContact').textContent = data.resident.contact || data.resident.contact_number || 'N/A';
                
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
    });    function resetFormFields() {
        // Reset form fields but keep radio button selection
        
        // Clear input fields
        if (barangayIdInput) barangayIdInput.value = '';
        if (otpCodeInput) otpCodeInput.value = '';
        
        // Clear and disable service form fields 
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
        
        // Hide resident info
        if (residentInfo) residentInfo.style.display = 'none';
        
        // Hide OTP section
        if (otpSection) otpSection.style.display = 'none';
        
        // Re-enable blur effect
        if (formFieldsSection) formFieldsSection.classList.add('blurred');
        
        // Hide submit button
        const submitButtonSection = document.getElementById('submitButtonSection');
        if (submitButtonSection) {
            submitButtonSection.style.setProperty('display', 'none', 'important');
        }
          // Reset verification flags
        residentVerified = false;
        otpVerified = false;
        qrVerified = false;
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
        stopQrScanner();    }

    // Toast notification helpers (matching document request form)
    function showToast(message, type = 'success') {
        const toastContainer = document.getElementById('toastContainer');
        const toastId = 'toast-' + Date.now();
        const icon = type === 'success'
            ? '<i class="fas fa-check-circle me-2"></i>'
            : '<i class="fas fa-exclamation-triangle me-2"></i>';
        const bgClass = type === 'success' ? 'bg-success text-white' : 'bg-danger text-white';
        
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center ${bgClass} border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
                <div class="d-flex">
                    <div class="toast-body">
                        ${icon}${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        const toastEl = document.getElementById(toastId);
        const bsToast = new bootstrap.Toast(toastEl);
        bsToast.show();
        
        toastEl.addEventListener('hidden.bs.toast', function() {
            toastEl.remove();
        });
    }

    function showSuccess(message) {
        showToast(message, 'success');
    }

    function showError(message) {
        showToast(message, 'error');
    }

    function hideError() {
        // Not needed with toast notifications as they auto-dismiss
    }
});
</script>
@endsection
