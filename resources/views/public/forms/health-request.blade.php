@extends('layouts.public.master')

@section('title', 'Health Service Request - Barangay Lumanglipa')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-primary text-white text-center" style="border-radius: 15px 15px 0 0;">
                    <h3 class="mb-0">
                        <i class="fas fa-heartbeat me-2"></i>
                        Health Service Request
                    </h3>
                    <p class="mb-0 mt-2">Request health services from Barangay Lumanglipa</p>
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

                    <form id="healthRequestForm" method="POST">
                        @csrf
                        
                        <!-- Barangay ID Section -->
                        <div class="mb-4">
                            <label for="barangay_id" class="form-label fw-bold">
                                <i class="fas fa-id-card text-primary me-2"></i>
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
                                        class="btn btn-outline-primary" 
                                        id="checkResidentBtn">
                                    <i class="fas fa-search"></i> Verify
                                </button>
                            </div>
                            <div class="form-text">
                                <i class="fas fa-info-circle text-info"></i>
                                Enter your registered Barangay ID to verify your information
                            </div>
                        </div>                        <!-- Resident Information Display -->
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
                                        To proceed with your health service request, we need to verify your identity. 
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
                                        Email verified successfully! You can now proceed with your health service request.
                                    </div>
                                </div>
                            </div>
                        </div>                        <!-- Service Request Form Section -->
                        <div id="serviceFormSection" class="blurred-section">
                            <div class="blur-overlay">
                                <div class="overlay-message">
                                    <i class="fas fa-lock fa-2x mb-3"></i>
                                    <h5>Verify Email to Continue</h5>
                                    <p>Please complete email verification to access the form fields</p>
                                </div>
                            </div>
                            
                            <!-- Service Type Section -->
                            <div class="mb-4">
                                <label for="service_type" class="form-label fw-bold">
                                    <i class="fas fa-stethoscope text-primary me-2"></i>
                                    Service Type
                                </label>
                                <select class="form-select form-select-lg" id="service_type" name="service_type" required disabled>
                                    <option value="">Select Health Service</option>
                                    @foreach($serviceTypes as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach                                </select>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    Choose the type of health service you need
                                </div>
                            </div>

                            <!-- Purpose Section -->
                            <div class="mb-4">
                                <label for="purpose" class="form-label fw-bold">
                                    <i class="fas fa-clipboard-list text-primary me-2"></i>
                                    Purpose
                                </label>
                                <textarea class="form-control" 
                                          id="purpose" 
                                          name="purpose" 
                                          rows="3" 
                                          placeholder="Please specify the purpose for requesting this health service..."
                                          required
                                          disabled></textarea>                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    Provide detailed information about why you need this service
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2">
                                <button type="submit" 
                                        class="btn btn-primary btn-lg" 
                                        id="submitBtn" 
                                        disabled>
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Submit Health Service Request
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
                                <i class="fas fa-shield-alt text-primary me-2"></i>
                                Email verification with OTP is required for all health service requests
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                All health service requests will be reviewed by the Barangay Health Office
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const barangayIdInput = document.getElementById('barangay_id');
    const checkResidentBtn = document.getElementById('checkResidentBtn');
    const residentInfo = document.getElementById('residentInfo');
    const otpSection = document.getElementById('otpSection');
    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const verifyOtpBtn = document.getElementById('verifyOtpBtn');
    const resendOtpBtn = document.getElementById('resendOtpBtn');
    const otpCodeInput = document.getElementById('otp_code');
    const healthRequestForm = document.getElementById('healthRequestForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Form fields to enable/disable
    const formFields = [
        'service_type', 'purpose'
    ];

    let residentVerified = false;
    let otpVerified = false;
    let otpTimer = null;
    let otpExpiryTime = null;

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

        fetch('/health/check-resident', {
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

        fetch('/health/send-otp', {
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

        fetch('/health/verify-otp', {
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
                  // Enable form fields
                formFields.forEach(fieldName => {
                    document.getElementById(fieldName).disabled = false;
                });
                
                // Remove blur effects
                removeBlurEffects();
                
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
        const serviceType = document.getElementById('service_type').value;
        const purpose = document.getElementById('purpose').value.trim();
        const isValid = residentVerified && otpVerified && serviceType && purpose;
        submitBtn.disabled = !isValid;
    }

    // Add event listeners for form validation
    document.getElementById('service_type').addEventListener('change', checkFormValidity);
    document.getElementById('purpose').addEventListener('input', checkFormValidity);

    // Form submission
    healthRequestForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!residentVerified) {
            showError('Please verify your Barangay ID first');
            return;
        }

        if (!otpVerified) {
            showError('Please verify your email with the OTP first');
            return;
        }
        
        const formData = new FormData(this);
        const submitData = Object.fromEntries(formData.entries());
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        
        fetch('/health/request', {
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
                showSuccess(`Health service request submitted successfully! Reference ID: ${data.data.request_id}`);
                healthRequestForm.reset();
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
                    showError(data.message || 'An error occurred while submitting the request.');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while submitting the request.');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Submit Health Service Request';
        });
    });

    function showSuccess(message) {
        const successAlert = document.getElementById('successAlert');
        const successMessage = document.getElementById('successMessage');
        successMessage.textContent = message;
        successAlert.style.display = 'block';
        successAlert.classList.add('show');
        
        // Hide error if visible
        hideError();
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function showError(message) {
        const errorAlert = document.getElementById('errorAlert');
        const errorMessage = document.getElementById('errorMessage');
        errorMessage.textContent = message;
        errorAlert.style.display = 'block';
        errorAlert.classList.add('show');
        
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
    }    function resetForm() {
        residentInfo.style.display = 'none';
        otpSection.style.display = 'none';
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
        
        // Disable form fields
        formFields.forEach(fieldName => {
            document.getElementById(fieldName).disabled = true;
        });
        
        // Add blur effects back
        addBlurEffects();
        
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
        });
    }
    
    function addBlurEffects() {
        const blurredSections = document.querySelectorAll('.blurred-section');
        blurredSections.forEach(section => {
            section.classList.add('blurred');
        });
    }
    
    // Initialize blur effects on page load
    addBlurEffects();
});
</script>
@endpush

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