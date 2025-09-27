@extends('layouts.public.master')

@section('title', 'File a Complaint - Barangay Lumanglipa')

@section('content')
<!-- Hero Section with Background -->
<section class="position-relative" style="background: #eaf4fb; padding-top: 6rem; margin-top: -20px;">
    <div class="container py-4">
        <div class="text-center mb-4">
            <h1 class="fw-bold mb-2" style="color: #2A7BC4; font-size: 2.2rem;">File a Complaint</h1>
            <p class="text-muted" style="font-size: 1rem;">Report issues and concerns to Barangay Lumanglipa</p>
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
                            <i class="fas fa-flag me-3" style="font-size: 2rem;"></i>
                            <h2 class="mb-0 fw-bold">Complaint Services</h2>
                        </div>
                        <p class="mb-0 opacity-9">Complete the form below to file your complaint to Barangay Lumanglipa</p>
                    </div>
                    
                    <div class="card-body p-5" style="background: #ffffff;">
                    <!-- Laravel Validation Errors -->
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Please check the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Session Flash Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

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

                    <form id="complaintForm" method="POST">
                        @csrf
                        
                        <!-- Barangay ID Section -->                        <div class="mb-4">
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
                        </div>                        <!-- Resident Information Display -->
                        <div id="residentInfo" class="card border-success mb-4" style="display: none;">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-success">
                                    <i class="fas fa-user-check me-2"></i>
                                    Verified Resident Information
                                </h6>
                            </div>                            <div class="card-body">
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
                                        To proceed with filing your complaint, we need to verify your identity. 
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
                                        Email verified successfully! You can now proceed with filing your complaint.
                                    </div>
                                </div>
                            </div>
                        </div>                        <!-- Complaint Form Section -->
                        <div id="complaintFormSection" class="blurred-section">
                            <div class="blur-overlay">
                                <div class="overlay-message">
                                    <i class="fas fa-shield-alt fa-2x text-warning mb-2"></i>
                                    <h5>Verify Email to Continue</h5>
                                    <p>Please complete email verification to access the form fields</p>
                                </div>
                            </div>
                            
                            <!-- Complaint Type Section -->
                            <div class="mb-4">
                                <label for="complaint_type" class="form-label fw-bold">
                                    <i class="fas fa-list-alt text-primary me-2"></i>
                                    Complaint Type <span class="text-danger">*Required</span>
                                </label>
                                <select class="form-select form-select-lg" id="complaint_type" name="complaint_type" required disabled>
                                    <option value="">Select Complaint Type</option>
                                    @foreach($complaintTypes as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                <div class="form-text">                                    <i class="fas fa-info-circle text-info"></i>
                                    Choose the category that best describes your complaint
                                </div>
                            </div>

                            <!-- Subject Section -->
                            <div class="mb-4">
                                <label for="subject" class="form-label fw-bold">
                                    <i class="fas fa-tag text-primary me-2"></i>
                                    Subject <span class="text-danger">*Required</span>
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg"
                                       id="subject"
                                       name="subject"
                                       placeholder="Brief summary of your complaint..."
                                       maxlength="255"
                                       required
                                       disabled>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    Provide a clear, concise subject line for your complaint
                                </div>
                            </div>

                            <!-- Description Section -->
                            <div class="mb-4">
                                <label for="description" class="form-label fw-bold">
                                    <i class="fas fa-comment-alt text-primary me-2"></i>
                                    Detailed Description <span class="text-danger">*Required</span>
                                </label>
                                <textarea class="form-control" 
                                          id="description" 
                                          name="description" 
                                          rows="4" 
                                          placeholder="Provide detailed information about your complaint..."
                                          required
                                          disabled></textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    Describe the issue in detail. Include what happened, when, where, and who was involved
                                </div>
                            </div>

                            <!-- Incident Date Section -->
                            <div class="mb-4">
                                <label for="incident_date" class="form-label fw-bold">
                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                    Date of Incident <span class="text-danger">*Required</span>
                                </label>
                                <input type="date" 
                                       class="form-control" 
                                       id="incident_date" 
                                       name="incident_date" 
                                       required
                                       disabled>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    When did the incident occur?
                                </div>
                            </div>

                            <!-- Incident Location Section -->
                            <div class="mb-4">
                                <label for="incident_location" class="form-label fw-bold">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    Location of Incident <span class="text-danger">*Required</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="incident_location" 
                                       name="incident_location" 
                                       placeholder="e.g., Purok 1, Near Barangay Hall, etc."
                                       required
                                       disabled>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    Specify where the incident took place
                                </div>
                            </div>

                            <!-- Involved Parties Section -->
                            <div class="mb-4">
                                <label for="involved_parties" class="form-label fw-bold">
                                    <i class="fas fa-users text-primary me-2"></i>
                                    Involved Parties <span class="text-muted">*Optional</span>
                                </label>
                                <textarea class="form-control" 
                                          id="involved_parties" 
                                          name="involved_parties" 
                                          rows="3" 
                                          placeholder="List the people involved in the incident (names, addresses if known)..."
                                          disabled></textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    List all parties involved in the incident (optional but helpful for investigation)
                                </div>
                            </div>

                            <!-- Additional Incident Details Section -->
                            <div class="mb-4">
                                <label for="incident_details" class="form-label fw-bold">
                                    <i class="fas fa-file-alt text-primary me-2"></i>
                                    Additional Details <span class="text-muted">*Optional</span>
                                </label>
                                <textarea class="form-control" 
                                          id="incident_details" 
                                          name="incident_details" 
                                          rows="3" 
                                          placeholder="Any additional information, witnesses, evidence, etc..."
                                          disabled></textarea>
                                <div class="form-text">
                                    <i class="fas fa-info-circle text-info"></i>
                                    Include any additional details, witness information, or evidence (optional)
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid" id="submitButtonSection" style="display: none !important;">
                            <button type="submit" 
                                    class="btn btn-primary btn-lg" 
                                    id="submitBtn" 
                                    disabled>
                                <i class="fas fa-paper-plane me-2"></i>
                                Submit Complaint
                            </button>
                        </div>

                        <!-- Important Information Section -->
                        <div class="mt-5 p-4 bg-light rounded">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Important Information
                            </h5>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Email verification with OTP is required for all complaint submissions
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    All complaints will be reviewed by the Barangay Officials
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Processing and response time is typically 3-5 business days
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    You will be notified about the status and resolution of your complaint
                                </li>
                                <li class="mb-0">
                                    <i class="fas fa-check text-success me-2"></i>
                                    Ensure all details are accurate for proper investigation and resolution
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if all required elements exist
    const barangayIdInput = document.getElementById('barangay_id');
    const checkResidentBtn = document.getElementById('checkResidentBtn');
    const residentInfo = document.getElementById('residentInfo');
    const otpSection = document.getElementById('otpSection');
    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const verifyOtpBtn = document.getElementById('verifyOtpBtn');
    const resendOtpBtn = document.getElementById('resendOtpBtn');
    const otpCodeInput = document.getElementById('otp_code');
    const complaintForm = document.getElementById('complaintForm');
    const submitBtn = document.getElementById('submitBtn');
    
    // Error check for missing elements
    if (!barangayIdInput || !checkResidentBtn || !complaintForm || !submitBtn) {
        console.error('Required form elements not found');
        return;
    }
    
    // Ensure submit button is hidden initially
    const submitButtonSection = document.getElementById('submitButtonSection');
    if (submitButtonSection) {
        submitButtonSection.style.setProperty('display', 'none', 'important');
    }      
    
    // Form fields to enable/disable
    const formFields = [
        'complaint_type', 'subject', 'description', 'incident_date', 'incident_location', 'involved_parties', 'incident_details'
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

        fetch('/complaints/check-resident', {
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

        fetch('/complaints/send-otp', {
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

        fetch('/complaints/verify-otp', {
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
                
                // Show submit button and enable it
                const submitButtonSection = document.getElementById('submitButtonSection');
                if (submitButtonSection) {
                    submitButtonSection.style.setProperty('display', 'block', 'important');
                }
                submitBtn.disabled = false;
                
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
        const complaintType = document.getElementById('complaint_type').value;
        const subject = document.getElementById('subject').value.trim();
        const description = document.getElementById('description').value.trim();
        const incidentDate = document.getElementById('incident_date').value;
        const incidentLocation = document.getElementById('incident_location').value.trim();
        
        const isValid = residentVerified && otpVerified && complaintType && subject && description && incidentDate && incidentLocation;
        
        // Only enable submit button if OTP is verified AND form is valid
        if (otpVerified) {
            submitBtn.disabled = !isValid;
        } else {
            submitBtn.disabled = true;
            // Ensure submit button section is hidden if OTP not verified
            const submitButtonSection = document.getElementById('submitButtonSection');
            if (submitButtonSection && !otpVerified) {
                submitButtonSection.style.setProperty('display', 'none', 'important');
            }
        }
    }

    // Add event listeners for form validation
    document.getElementById('complaint_type').addEventListener('change', checkFormValidity);
    document.getElementById('subject').addEventListener('input', checkFormValidity);
    document.getElementById('description').addEventListener('input', checkFormValidity);
    document.getElementById('incident_date').addEventListener('change', checkFormValidity);
    document.getElementById('incident_location').addEventListener('input', checkFormValidity);
    document.getElementById('involved_parties').addEventListener('input', checkFormValidity);
    document.getElementById('incident_details').addEventListener('input', checkFormValidity);

    // Form submission
    complaintForm.addEventListener('submit', function(e) {
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
        
        fetch('/complaints/file', {
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
                showSuccess(`Complaint filed successfully! Reference ID: ${data.data.complaint_id}`);
                complaintForm.reset();
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
                    showError(data.message || 'An error occurred while filing the complaint.');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while filing the complaint.');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Submit Complaint';
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
    }

    function resetForm() {
        // Clear barangay ID input
        barangayIdInput.value = '';
        
        residentInfo.style.display = 'none';
        otpSection.style.display = 'none';
        const submitButtonSection = document.getElementById('submitButtonSection');
        if (submitButtonSection) {
            submitButtonSection.style.setProperty('display', 'none', 'important');
        }
        submitBtn.disabled = true;
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

/* Submit Button Width Fix */
.d-grid #submitBtn {
    width: 100% !important;
}
</style>
