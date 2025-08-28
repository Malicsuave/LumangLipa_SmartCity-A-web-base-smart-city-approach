@extends('layouts.public.master')

@section('title', 'File a Complaint')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-danger text-white text-center" style="border-radius: 15px 15px 0 0;">
                    <h3 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        File a Complaint
                    </h3>
                    <p class="mb-0 mt-2">Report concerns and issues to the Barangay Officials</p>
                </div>
                
                <div class="card-body p-4">
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

                    <form id="complaintRequestForm">
                        @csrf
                        
                        <!-- Barangay ID Section -->
                        <div class="mb-4">
                            <label for="barangay_id" class="form-label fw-bold">
                                <i class="fas fa-id-card text-danger me-2"></i>
                                Barangay ID
                            </label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="barangay_id" 
                                       name="barangay_id" 
                                       placeholder="Enter your Barangay ID"
                                       required>
                                <button type="button" 
                                        class="btn btn-outline-danger" 
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
                        <div id="residentInfo" class="card border-danger mb-4" style="display: none;">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-danger">
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
                                        To proceed with your complaint, we need to verify your identity. 
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
                                        Email verified successfully! You can now proceed with your complaint.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Fields Section (Blurred until OTP verified) -->
                        <div id="formFieldsSection" class="form-fields-blur">
                            <div class="blur-overlay">
                                <div class="blur-message">
                                    <i class="fas fa-shield-alt fa-2x text-warning mb-2"></i>
                                    <h5>Email Verification Required</h5>
                                    <p class="mb-0">Please verify your email with OTP to access the form</p>
                                </div>
                            </div>

                            <!-- Complaint Type Section -->
                            <div class="mb-4">
                                <label for="complaint_type" class="form-label fw-bold">
                                    <i class="fas fa-list text-danger me-2"></i>
                                    Complaint Type
                                </label>
                                <select class="form-select form-select-lg" id="complaint_type" name="complaint_type" required disabled>
                                    <option value="">Select Complaint Type</option>
                                    <option value="noise_complaint">Noise Complaint</option>
                                    <option value="public_disturbance">Public Disturbance</option>
                                    <option value="illegal_construction">Illegal Construction</option>
                                    <option value="garbage_disposal">Improper Garbage Disposal</option>
                                    <option value="boundary_dispute">Boundary Dispute</option>
                                    <option value="harassment">Harassment</option>
                                    <option value="property_damage">Property Damage</option>
                                    <option value="public_safety">Public Safety Concern</option>
                                    <option value="animal_complaint">Animal-Related Complaint</option>
                                    <option value="other">Other</option>
                                </select>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    Choose the type of complaint you want to file
                                </div>
                            </div>

                            <!-- Subject Section -->
                            <div class="mb-4">
                                <label for="subject" class="form-label fw-bold">
                                    <i class="fas fa-heading text-danger me-2"></i>
                                    Subject
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="subject" 
                                       name="subject" 
                                       placeholder="Brief summary of your complaint..."
                                       required
                                       disabled>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    Provide a clear and concise subject for your complaint
                                </div>
                            </div>

                            <!-- Description Section -->
                            <div class="mb-4">
                                <label for="description" class="form-label fw-bold">
                                    <i class="fas fa-file-alt text-danger me-2"></i>
                                    Description
                                </label>
                                <textarea class="form-control" 
                                          id="description" 
                                          name="description" 
                                          rows="4" 
                                          placeholder="Please provide a detailed description of your complaint..."
                                          required
                                          disabled></textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    Describe your complaint in detail
                                </div>
                            </div>

                            <!-- Incident Details Section -->
                            <div class="mb-4">
                                <label for="incident_details" class="form-label fw-bold">
                                    <i class="fas fa-clipboard-list text-danger me-2"></i>
                                    Incident Details <small class="text-muted">(Optional)</small>
                                </label>
                                <textarea class="form-control" 
                                          id="incident_details" 
                                          name="incident_details" 
                                          rows="3" 
                                          placeholder="Additional details about the incident (optional)..."
                                          disabled></textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    Provide any additional details about the incident
                                </div>
                            </div>

                            <!-- Incident Date Section -->
                            <div class="mb-4">
                                <label for="incident_date" class="form-label fw-bold">
                                    <i class="fas fa-calendar text-danger me-2"></i>
                                    Incident Date <small class="text-muted">(Optional)</small>
                                </label>
                                <input type="date" 
                                       class="form-control form-control-lg" 
                                       id="incident_date" 
                                       name="incident_date" 
                                       disabled>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    When did the incident occur?
                                </div>
                            </div>

                            <!-- Incident Location Section -->
                            <div class="mb-4">
                                <label for="incident_location" class="form-label fw-bold">
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                    Incident Location <small class="text-muted">(Optional)</small>
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="incident_location" 
                                       name="incident_location" 
                                       placeholder="Where did the incident occur? (e.g., Street name, house number, etc.)"
                                       disabled>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    Specify the location where the incident occurred
                                </div>
                            </div>

                            <!-- Involved Parties Section -->
                            <div class="mb-4">
                                <label for="involved_parties" class="form-label fw-bold">
                                    <i class="fas fa-users text-danger me-2"></i>
                                    Involved Parties <small class="text-muted">(Optional)</small>
                                </label>
                                <textarea class="form-control" 
                                          id="involved_parties" 
                                          name="involved_parties" 
                                          rows="3" 
                                          placeholder="Names or descriptions of people involved (if any)..."
                                          disabled></textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    List any individuals involved in the incident
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" 
                                        class="btn btn-danger btn-lg" 
                                        id="submitBtn"
                                        disabled>
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Submit Complaint
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Information Section -->
                    <div class="mt-5 p-4 bg-light rounded">
                        <h5 class="text-danger mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Important Information
                        </h5>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-shield-alt text-danger me-2"></i>
                                Email verification with OTP is required for all complaint submissions
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                All complaints will be reviewed by the appropriate Barangay Officials
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                Response time varies by priority level and complexity of the issue
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                You will be notified once your complaint is reviewed and scheduled for action
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                For urgent matters requiring immediate attention, please contact barangay officials directly
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-check text-success me-2"></i>
                                Please provide accurate and complete information to help resolve your complaint effectively
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.form-control:focus,
.form-select:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.btn-danger {
    background: linear-gradient(45deg, #dc3545, #c82333);
    border: none;
}

.btn-danger:hover {
    background: linear-gradient(45deg, #c82333, #bd2130);
    transform: translateY(-1px);
}

#submitBtn:disabled {
    background: #6c757d;
    cursor: not-allowed;
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
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('complaintRequestForm');
    const barangayIdInput = document.getElementById('barangay_id');
    const checkResidentBtn = document.getElementById('checkResidentBtn');
    const residentInfo = document.getElementById('residentInfo');
    const otpSection = document.getElementById('otpSection');
    const formFieldsSection = document.getElementById('formFieldsSection');
    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const verifyOtpBtn = document.getElementById('verifyOtpBtn');
    const resendOtpBtn = document.getElementById('resendOtpBtn');
    const otpCodeInput = document.getElementById('otp_code');
    const complaintTypeSelect = document.getElementById('complaint_type');
    const subjectInput = document.getElementById('subject');
    const descriptionTextarea = document.getElementById('description');
    const incidentDetailsTextarea = document.getElementById('incident_details');
    const incidentDateInput = document.getElementById('incident_date');
    const incidentLocationInput = document.getElementById('incident_location');
    const involvedPartiesTextarea = document.getElementById('involved_parties');
    const submitBtn = document.getElementById('submitBtn');
    const successAlert = document.getElementById('successAlert');
    const errorAlert = document.getElementById('errorAlert');
    
    let residentVerified = false;
    let otpVerified = false;
    let otpTimer = null;
    let otpExpiryTime = null;

    // Initialize form with blur effect
    formFieldsSection.classList.add('blurred');

    // Check resident function
    checkResidentBtn.addEventListener('click', function() {
        const barangayId = barangayIdInput.value.trim();
        if (!barangayId) {
            showError('Please enter a Barangay ID');
            return;
        }

        checkResidentBtn.disabled = true;
        checkResidentBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking...';

        fetch('{{ route("complaints.check-resident") }}', {
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
                
                residentInfo.style.display = 'block';
                otpSection.style.display = 'block';
                residentVerified = true;
                
                // Change button to show "Found" in red
                checkResidentBtn.innerHTML = '<i class="fas fa-check"></i> Resident Found';
                checkResidentBtn.classList.remove('btn-outline-danger');
                checkResidentBtn.classList.add('btn-danger');
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

        fetch('{{ route("complaints.send-otp") }}', {
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

        fetch('{{ route("complaints.verify-otp") }}', {
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
                
                // Remove blur effect and enable form fields
                formFieldsSection.classList.remove('blurred');
                formFieldsSection.classList.add('form-fields-reveal');
                
                complaintTypeSelect.disabled = false;
                subjectInput.disabled = false;
                descriptionTextarea.disabled = false;
                incidentDetailsTextarea.disabled = false;
                incidentDateInput.disabled = false;
                incidentLocationInput.disabled = false;
                involvedPartiesTextarea.disabled = false;
                
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
        const isValid = residentVerified && 
                       otpVerified &&
                       complaintTypeSelect.value && 
                       subjectInput.value.trim() &&
                       descriptionTextarea.value.trim();
        submitBtn.disabled = !isValid;
    }

    complaintTypeSelect.addEventListener('change', checkFormValidity);
    subjectInput.addEventListener('input', checkFormValidity);
    descriptionTextarea.addEventListener('input', checkFormValidity);

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!residentVerified) {
            showError('Please verify your Barangay ID first');
            return;
        }

        if (!otpVerified) {
            showError('Please verify your email with the OTP first');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Submitting...';

        const formData = new FormData();
        formData.append('barangay_id', barangayIdInput.value);
        formData.append('complaint_type', complaintTypeSelect.value);
        formData.append('subject', subjectInput.value);
        formData.append('description', descriptionTextarea.value);
        formData.append('incident_details', incidentDetailsTextarea.value);
        formData.append('incident_date', incidentDateInput.value);
        formData.append('incident_location', incidentLocationInput.value);
        formData.append('involved_parties', involvedPartiesTextarea.value);

        fetch('{{ route("complaints.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
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
            console.error('Error:', error);
            showError('An error occurred while submitting the complaint');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Submit Complaint';
        });
    });

    function resetForm() {
        residentInfo.style.display = 'none';
        otpSection.style.display = 'none';
        complaintTypeSelect.disabled = true;
        subjectInput.disabled = true;
        descriptionTextarea.disabled = true;
        incidentDetailsTextarea.disabled = true;
        incidentDateInput.disabled = true;
        incidentLocationInput.disabled = true;
        involvedPartiesTextarea.disabled = true;
        submitBtn.disabled = true;
        residentVerified = false;
        otpVerified = false;
        complaintTypeSelect.value = '';
        subjectInput.value = '';
        descriptionTextarea.value = '';
        incidentDetailsTextarea.value = '';
        incidentDateInput.value = '';
        incidentLocationInput.value = '';
        involvedPartiesTextarea.value = '';
        
        // Add blur effect back
        formFieldsSection.classList.add('blurred');
        formFieldsSection.classList.remove('form-fields-reveal');
        
        // Reset OTP section
        document.getElementById('otpRequestStep').style.display = 'block';
        document.getElementById('otpVerifyStep').style.display = 'none';
        document.getElementById('otpVerifiedStep').style.display = 'none';
        otpCodeInput.value = '';
        
        if (otpTimer) {
            clearInterval(otpTimer);
        }
        
        // Reset verification button to original state
        checkResidentBtn.innerHTML = '<i class="fas fa-search"></i> Verify';
        checkResidentBtn.classList.remove('btn-danger');
        checkResidentBtn.classList.add('btn-outline-danger');
        checkResidentBtn.disabled = false;
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
