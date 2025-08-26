@extends('layouts.public.master')

@section('title', 'Request Document')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-header bg-primary text-white text-center" style="border-radius: 15px 15px 0 0;">
                    <h3 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        Document Request
                    </h3>
                    <p class="mb-0 mt-2">Request official barangay documents online</p>
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

                    <form id="documentRequestForm">
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
                                    <h5>Email Verification Required</h5>
                                    <p class="mb-0">Please verify your email with OTP to access the form</p>
                                </div>
                            </div>

                            <!-- Document Type Section -->
                            <div class="mb-4">
                                <label for="document_type" class="form-label fw-bold">
                                    <i class="fas fa-file-text text-primary me-2"></i>
                                    Document Type
                                </label>
                                <select class="form-select form-select-lg" id="document_type" name="document_type" required disabled>
                                    <option value="">Select Document Type</option>
                                    <option value="Barangay Clearance">Barangay Clearance</option>
                                    <option value="Certificate of Residency">Certificate of Residency</option>
                                    <option value="Certificate of Indigency">Certificate of Indigency</option>
                                    <option value="Certificate of Low Income">Certificate of Low Income</option>
                                    <option value="Business Permit">Business Permit</option>
                                </select>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    Choose the type of document you want to request
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
                                          rows="4" 
                                          placeholder="Please specify the purpose for requesting this document..."
                                          required
                                          disabled></textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    Provide detailed information about why you need this document
                                </div>
                            </div>

                            <!-- GCash Payment Instructions -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-money-bill-wave text-success me-2"></i>
                                    Payment Instructions
                                </label>
                                <div class="alert alert-info">
                                    Please pay <strong>₱50</strong> via GCash to <strong>0912345678 (LumangLipaGcash)</strong>.<br>
                                    <strong>Upload a screenshot or photo of your payment receipt below to proceed.</strong>
                                </div>
                            </div>

                            <!-- Receipt Upload -->
                            <div class="mb-4">
                                <label for="receipt" class="form-label fw-bold">
                                    <i class="fas fa-receipt text-primary me-2"></i>
                                    Upload GCash Payment Receipt <span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control" id="receipt" name="receipt" accept="image/*,.pdf" required>
                                <div class="form-text">
                                    Accepted formats: JPG, PNG, PDF. Max size: 5MB.
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
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
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Important Information
                        </h5>                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-shield-alt text-primary me-2"></i>
                                Email verification with OTP is required for all document requests
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
                
                residentInfo.style.display = 'block';
                otpSection.style.display = 'block';
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
        .then(response => response.json())        .then(data => {
            if (data.success) {
                otpVerified = true;
                document.getElementById('otpVerifyStep').style.display = 'none';
                document.getElementById('otpVerifiedStep').style.display = 'block';
                
                // Remove blur effect and enable form fields
                formFieldsSection.classList.remove('blurred');
                formFieldsSection.classList.add('form-fields-reveal');
                
                documentTypeSelect.disabled = false;
                purposeTextarea.disabled = false;
                
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
        const receiptInput = document.getElementById('receipt');
        const isValid = residentVerified && 
                       otpVerified &&
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
            showError('Please verify your Barangay ID first');
            return;
        }

        if (!otpVerified) {
            showError('Please verify your email with the OTP first');
            return;
        }

        const receiptInput = document.getElementById('receipt');
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

        fetch('{{ route("documents.store") }}', {
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
            showError('An error occurred while submitting the request');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Submit Request';
        });
    });

    function resetForm() {
        residentInfo.style.display = 'none';
        otpSection.style.display = 'none';
        documentTypeSelect.disabled = true;
        purposeTextarea.disabled = true;
        submitBtn.disabled = true;
        residentVerified = false;
        otpVerified = false;
        documentTypeSelect.value = '';
        purposeTextarea.value = '';
        
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
        checkResidentBtn.classList.remove('btn-success');
        checkResidentBtn.classList.add('btn-outline-primary');
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
