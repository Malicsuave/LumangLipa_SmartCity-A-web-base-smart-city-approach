@extends('layouts.public.master')

@section('title', 'Blotter Report - Barangay Lumanglipa')

@section('content')
<!-- Hero Section with Background -->
<section class="position-relative" style="background: #eaf4fb; padding-top: 6rem; margin-top: -20px;">
    <div class="container py-4">
        <div class="text-center mb-4">
            <h1 class="fw-bold mb-2" style="color: #2A7BC4; font-size: 2.2rem;">File a Blotter Report</h1>
            <p class="text-muted" style="font-size: 1rem;">Report incidents and maintain peace and order records in Barangay Lumanglipa</p>
        </div>
    </div>
</section>

<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-n4" style="border-radius: 18px;">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg" style="border: 2px solid #2A7BC4 !important; border-radius: 18px; overflow: hidden; background: #ffffff;">
                    <div class="card-header text-center py-4" style="background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8b 100%); color: white; border: none;">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <i class="fas fa-clipboard-check me-3" style="font-size: 2rem;"></i>
                            <h2 class="mb-0 fw-bold">Blotter Report</h2>
                        </div>
                        <p class="mb-0 opacity-9">Complete the form below to file a blotter report in Barangay Lumanglipa</p>
                    </div>
                    
                    <div class="card-body p-5" style="background: #ffffff;">
                <!-- Alerts -->
                <div id="successAlert" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
                    <i class="fas fa-check-circle me-2"></i>
                    <span id="successMessage"></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>

                <div id="errorAlert" class="alert alert-danger alert-dismissible fade" role="alert" style="display: none;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <span id="errorMessage"></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>

                <form id="blotterRequestForm" method="POST">
                    @csrf

                    <!-- Barangay ID Section -->
                    <div class="mb-4">
                        <label for="barangay_id" class="form-label fw-bold">
                            <i class="fas fa-id-card text-primary me-2"></i>
                            Barangay ID <span class="text-danger">*Required</span>
                        </label>
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control form-control-lg" 
                                   id="barangay_id" 
                                   name="barangay_id" 
                                   placeholder="Enter your Barangay ID"
                                   required>
                            <button type="button" 
                                    class="btn btn-outline-primary" 
                                    id="checkResidentBtn">
                                <i class="fas fa-search"></i> Verify
                            </button>
                        </div>
                        <div class="form-text">
                            <i class="fas fa-info-circle text-info"></i>
                            Enter your registered Barangay ID to verify your information
                        </div>
                    </div>

                    <!-- Resident Information Display -->
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
                        </div>
                    </div>

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
                                    To proceed with filing your blotter report, we need to verify your identity. 
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
                                    Email verified successfully! You can now proceed with filing your blotter report.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Fields Section (Blurred until OTP verified) -->
                    <div id="formFieldsSection" class="blurred-section">
                        <div class="blur-overlay">
                            <div class="overlay-message">
                                <i class="fas fa-shield-alt fa-2x"></i>
                                <h5>Email Verification Required</h5>
                                <p>Please verify your email with OTP to access the form</p>
                            </div>
                        </div>

                        <!-- Incident Type Section -->
                        <div class="mb-4">
                            <label for="incident_type" class="form-label fw-bold">
                                <i class="fas fa-exclamation-circle text-primary me-2"></i>
                                Type of Incident <span class="text-danger">*Required</span>
                            </label>
                            <select class="form-select form-select-lg" id="incident_type" name="incident_type" required disabled>
                                <option value="">Select Incident Type</option>
                                <option value="Physical Altercation">Physical Altercation</option>
                                <option value="Verbal Dispute">Verbal Dispute</option>
                                <option value="Domestic Violence">Domestic Violence</option>
                                <option value="Theft/Robbery">Theft/Robbery</option>
                                <option value="Property Damage">Property Damage</option>
                                <option value="Noise Complaint">Noise Complaint</option>
                                <option value="Threats/Harassment">Threats/Harassment</option>
                                <option value="Public Disturbance">Public Disturbance</option>
                                <option value="Drug-related">Drug-related</option>
                                <option value="Other">Other</option>
                            </select>
                            <div class="form-text">
                                <i class="fas fa-info-circle text-info"></i>
                                Choose the type of incident you want to report
                            </div>
                        </div>

                        <!-- Incident Title Section -->
                        <div class="mb-4">
                            <label for="incident_title" class="form-label fw-bold">
                                <i class="fas fa-heading text-primary me-2"></i>
                                Incident Title <span class="text-danger">*Required</span>
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg"
                                   id="incident_title"
                                   name="incident_title"
                                   placeholder="Brief title/summary of the incident..."
                                   maxlength="255"
                                   required
                                   disabled>
                            <div class="form-text">
                                <i class="fas fa-info-circle text-info"></i>
                                Provide a clear, brief title for the incident
                            </div>
                        </div>

                        <!-- Incident Description Section -->
                        <div class="mb-4">
                            <label for="incident_description" class="form-label fw-bold">
                                <i class="fas fa-file-alt text-primary me-2"></i>
                                Detailed Description <span class="text-danger">*Required</span>
                            </label>
                            <textarea class="form-control" 
                                      id="incident_description" 
                                      name="incident_description" 
                                      rows="5" 
                                      placeholder="Provide detailed information about the incident. Include what happened, when, where, and who was involved..."
                                      required
                                      disabled></textarea>
                            <div class="form-text">
                                <i class="fas fa-info-circle text-info"></i>
                                Provide a comprehensive account of the incident
                            </div>
                        </div>

                        <!-- Date and Time Section -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-calendar-clock text-primary me-2"></i>
                                Date and Time of Incident <span class="text-danger">*Required</span>
                            </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="incident_date" class="form-label">Date <span class="text-danger">*Required</span></label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="incident_date" 
                                           name="incident_date" 
                                           required
                                           disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="incident_time" class="form-label">Time <span class="text-muted">*Optional</span></label>
                                    <input type="time" 
                                           class="form-control" 
                                           id="incident_time" 
                                           name="incident_time"
                                           disabled>
                                </div>
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle text-info"></i>
                                Specify when the incident occurred
                            </div>
                        </div>

                        <!-- Location Section -->
                        <div class="mb-4">
                            <label for="incident_location" class="form-label fw-bold">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                Location of Incident <span class="text-danger">*Required</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="incident_location" 
                                   name="incident_location" 
                                   placeholder="e.g., Purok 1, Near Barangay Hall, 123 Main Street, etc."
                                   required
                                   disabled>
                            <div class="form-text">
                                <i class="fas fa-info-circle text-info"></i>
                                Specify the exact location where the incident occurred
                            </div>
                        </div>

                        <!-- Parties Involved Section -->
                        <div class="mb-4">
                            <label for="parties_involved" class="form-label fw-bold">
                                <i class="fas fa-users text-primary me-2"></i>
                                Parties Involved <span class="text-danger">*Required</span>
                            </label>
                            <textarea class="form-control" 
                                      id="parties_involved" 
                                      name="parties_involved" 
                                      rows="4" 
                                      placeholder="List all parties involved in the incident (names, addresses if known, relationship to the incident)..."
                                      required
                                      disabled></textarea>
                            <div class="form-text">
                                <i class="fas fa-info-circle text-info"></i>
                                Provide details of all persons involved in the incident
                            </div>
                        </div>

                        <!-- Witnesses Section -->
                        <div class="mb-4">
                            <label for="witnesses" class="form-label fw-bold">
                                <i class="fas fa-eye text-primary me-2"></i>
                                Witnesses <span class="text-muted">*Optional</span>
                            </label>
                            <textarea class="form-control" 
                                      id="witnesses" 
                                      name="witnesses" 
                                      rows="3" 
                                      placeholder="List any witnesses to the incident (names, contact information if available)..."
                                      disabled></textarea>
                            <div class="form-text">
                                <i class="fas fa-info-circle text-info"></i>
                                Include witness information if available
                            </div>
                        </div>

                        <!-- Desired Resolution Section -->
                        <div class="mb-4">
                            <label for="desired_resolution" class="form-label fw-bold">
                                <i class="fas fa-balance-scale text-primary me-2"></i>
                                Desired Resolution <span class="text-muted">*Optional</span>
                            </label>
                            <textarea class="form-control" 
                                      id="desired_resolution" 
                                      name="desired_resolution" 
                                      rows="3" 
                                      placeholder="What resolution or action would you like the barangay to take regarding this incident?..."
                                      disabled></textarea>
                            <div class="form-text">
                                <i class="fas fa-info-circle text-info"></i>
                                Specify what kind of resolution you are seeking
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid" id="submitButtonSection" style="display: none !important;">
                            <button type="submit" 
                                    class="btn btn-primary btn-lg" 
                                    id="submitBtn"
                                    disabled>
                                <i class="fas fa-paper-plane me-2"></i>
                                Submit Blotter Report
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Information Section -->
                <div class="mt-5 p-4 bg-light rounded">
                    <h5 class="text-primary mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Important Information
                    </h5>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Email verification with OTP is required for all blotter reports
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            All information provided will be kept confidential
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            False reporting is a punishable offense
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Barangay officials will review and act on your report
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            You will receive updates on the status of your report via email
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection

@push('scripts')
<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
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

.d-grid #submitBtn {
    width: 100% !important;
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

/* Blur effect styles */
.blurred-section {
    position: relative;
    transition: filter 0.3s ease;
}

.blurred-section.blurred {
    filter: blur(3px);
    pointer-events: none;
    user-select: none;
}

.blurred-section .blur-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    border-radius: 0.375rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.blurred-section.blurred .blur-overlay {
    opacity: 1;
}

.overlay-message {
    text-align: center;
    color: #6c757d;
}

.overlay-message i {
    color: #ffc107;
}

.overlay-message h5 {
    color: #495057;
    font-weight: 600;
}

.overlay-message p {
    color: #6c757d;
    margin: 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('blotterRequestForm');
    const barangayIdInput = document.getElementById('barangay_id');
    const checkResidentBtn = document.getElementById('checkResidentBtn');
    const residentInfo = document.getElementById('residentInfo');
    const otpSection = document.getElementById('otpSection');
    const formFieldsSection = document.getElementById('formFieldsSection');
    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const verifyOtpBtn = document.getElementById('verifyOtpBtn');
    const resendOtpBtn = document.getElementById('resendOtpBtn');
    const otpCodeInput = document.getElementById('otp_code');
    const submitBtn = document.getElementById('submitBtn');
    const successAlert = document.getElementById('successAlert');
    const errorAlert = document.getElementById('errorAlert');
    
    let residentVerified = false;
    let otpVerified = false;
    let otpTimer = null;
    let otpExpiryTime = null;

    // Initialize form - show form fields section with blur effect (original state)
    formFieldsSection.classList.add('blurred');
    addBlurEffects();

    // Ensure submit button is hidden initially
    const submitButtonSection = document.getElementById('submitButtonSection');
    if (submitButtonSection) {
        submitButtonSection.style.setProperty('display', 'none', 'important');
    }

    // Form fields to enable/disable
    const formFields = [
        'incident_type', 'incident_title', 'incident_description', 'incident_date', 'incident_time',
        'incident_location', 'parties_involved', 'witnesses', 'desired_resolution'
    ];

    // Check resident functionality
    checkResidentBtn.addEventListener('click', function() {
        const barangayId = barangayIdInput.value.trim();
        
        if (!barangayId) {
            showError('Please enter a Barangay ID');
            return;
        }

        // Disable button and show loading
        checkResidentBtn.disabled = true;
        checkResidentBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking...';

        fetch('{{ route("blotter.check-resident") }}', {
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
                // Show resident information
                document.getElementById('residentName').textContent = data.resident.name;
                document.getElementById('residentAddress').textContent = data.resident.address;
                document.getElementById('residentAge').textContent = data.resident.age;
                document.getElementById('residentContact').textContent = data.resident.contact_number || 'N/A';
                
                residentInfo.style.display = 'block';
                otpSection.style.display = 'block';
                residentVerified = true;
                
                // Change button text
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

        fetch('{{ route("blotter.send-otp") }}', {
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
                document.getElementById('otpRequestStep').style.display = 'none';
                document.getElementById('otpVerifyStep').style.display = 'block';
                
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

        fetch('{{ route("blotter.verify-otp") }}', {
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
                document.getElementById('otpVerifyStep').style.display = 'none';
                document.getElementById('otpVerifiedStep').style.display = 'block';
                  
                // Show and enable form fields section
                formFieldsSection.style.display = 'block';
                
                // Remove blur effect and enable form fields
                formFieldsSection.classList.remove('blurred');
                
                // Enable form fields
                formFields.forEach(fieldName => {
                    const field = document.getElementById(fieldName);
                    if (field) field.disabled = false;
                });
                
                // Remove blur effects
                removeBlurEffects();
                
                // Show submit button section
                const submitButtonSection = document.getElementById('submitButtonSection');
                if (submitButtonSection) {
                    submitButtonSection.style.setProperty('display', 'block', 'important');
                }
                
                checkFormValidity();
                
                // Stop timer
                if (otpTimer) {
                    clearInterval(otpTimer);
                }
                
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
        document.getElementById('otpVerifyStep').style.display = 'none';
        document.getElementById('otpRequestStep').style.display = 'block';
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

    // Form validation
    function checkFormValidity() {
        const incidentType = document.getElementById('incident_type').value;
        const incidentTitle = document.getElementById('incident_title').value.trim();
        const incidentDescription = document.getElementById('incident_description').value.trim();
        const incidentDate = document.getElementById('incident_date').value;
        const incidentLocation = document.getElementById('incident_location').value.trim();
        const partiesInvolved = document.getElementById('parties_involved').value.trim();
        
        const isValid = residentVerified && otpVerified && incidentType && incidentTitle && 
                       incidentDescription && incidentDate && incidentLocation && partiesInvolved;
        submitBtn.disabled = !isValid;
    }

    // Add event listeners for form validation
    document.getElementById('incident_type').addEventListener('change', checkFormValidity);
    document.getElementById('incident_title').addEventListener('input', checkFormValidity);
    document.getElementById('incident_description').addEventListener('input', checkFormValidity);
    document.getElementById('incident_date').addEventListener('input', checkFormValidity);
    document.getElementById('incident_location').addEventListener('input', checkFormValidity);
    document.getElementById('parties_involved').addEventListener('input', checkFormValidity);

    // Form submission
    blotterRequestForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!residentVerified) {
            showError('Please verify your Barangay ID first');
            return;
        }

        if (!otpVerified) {
            showError('Please verify your email with the OTP first');
            return;
        }
        
        // Create FormData
        const formData = new FormData(this);
        
        // Convert FormData to JSON object
        const submitData = Object.fromEntries(formData.entries());
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        
        fetch('{{ route("blotter.request") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(submitData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(`Blotter report submitted successfully! Reference ID: ${data.data.blotter_id}`);
                // Clear form fields first
                form.reset();
                // Then reset to initial state with blur
                resetForm();
            } else {
                let errorMessage = 'Please check the following errors:\n';
                if (data.errors) {
                    Object.values(data.errors).forEach(errors => {
                        errors.forEach(error => {
                            errorMessage += '• ' + error + '\n';
                        });
                    });
                    showError(errorMessage);
                } else {
                    showError(data.message || 'An error occurred while submitting the report.');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while submitting the report.');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Submit Blotter Report';
        });
    });

    function showSuccess(message) {
        const successAlert = document.getElementById('successAlert');
        const successMessage = document.getElementById('successMessage');
        successMessage.textContent = message;
        successAlert.classList.add('show');
        successAlert.style.display = 'block';
        
        // Hide error if visible
        hideError();
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function showError(message) {
        const errorAlert = document.getElementById('errorAlert');
        const errorMessage = document.getElementById('errorMessage');
        errorMessage.textContent = message;
        errorAlert.classList.add('show');
        errorAlert.style.display = 'block';
        
        // Hide success if visible
        const successAlert = document.getElementById('successAlert');
        successAlert.style.display = 'none';
        successAlert.classList.remove('show');
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function hideError() {
        const errorAlert = document.getElementById('errorAlert');
        errorAlert.style.display = 'none';
        errorAlert.classList.remove('show');
    }
    
    function resetForm() {
        // Clear barangay ID input
        barangayIdInput.value = '';
        
        residentInfo.style.display = 'none';
        otpSection.style.display = 'none';
        
        // Reset form fields section to blurred state (original state)
        formFieldsSection.style.display = 'block';
        formFieldsSection.classList.add('blurred');
        addBlurEffects();
        
        // Hide submit button section
        const submitButtonSection = document.getElementById('submitButtonSection');
        if (submitButtonSection) {
            submitButtonSection.style.setProperty('display', 'none', 'important');
        }
        
        residentVerified = false;
        otpVerified = false;
        
        // Reset OTP section
        document.getElementById('otpRequestStep').style.display = 'block';
        document.getElementById('otpVerifyStep').style.display = 'none';
        document.getElementById('otpVerifiedStep').style.display = 'none';
        otpCodeInput.value = '';
        
        if (otpTimer) {
            clearInterval(otpTimer);
        }
        
        // Clear and disable form fields
        formFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field) {
                field.disabled = true;
                if (field.tagName === 'SELECT') {
                    field.selectedIndex = 0;
                } else {
                    field.value = '';
                }
            }
        });
        
        submitBtn.disabled = true;
        
        // Reset button
        checkResidentBtn.innerHTML = '<i class="fas fa-search"></i> Verify';
        checkResidentBtn.classList.remove('btn-success');
        checkResidentBtn.classList.add('btn-outline-primary');
        checkResidentBtn.disabled = false;
    }
    
    function removeBlurEffects() {
        const blurredSections = document.querySelectorAll('.blurred-section');
        blurredSections.forEach(section => {
            section.classList.remove('blurred');
            // Remove the blur overlay completely
            const blurOverlay = section.querySelector('.blur-overlay');
            if (blurOverlay) {
                blurOverlay.style.opacity = '0';
                blurOverlay.style.pointerEvents = 'none';
            }
            // Ensure the section is interactive
            section.style.pointerEvents = 'auto';
            section.style.userSelect = 'auto';
            section.style.filter = 'none';
        });
    }
    
    function addBlurEffects() {
        const blurredSections = document.querySelectorAll('.blurred-section');
        blurredSections.forEach(section => {
            section.classList.add('blurred');
            // Restore the blur overlay visibility
            const blurOverlay = section.querySelector('.blur-overlay');
            if (blurOverlay) {
                blurOverlay.style.opacity = '1';
                blurOverlay.style.pointerEvents = 'auto';
            }
            // Disable interactions with the section
            section.style.pointerEvents = 'none';
            section.style.userSelect = 'none';
            section.style.filter = 'blur(3px)';
        });
    }
    
    // Initialize blur effects on page load
    addBlurEffects();
});
</script>
@endpush