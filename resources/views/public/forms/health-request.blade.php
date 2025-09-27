@extends('layouts.public.master')

@section('title', 'Health Service Request - Barangay Lumanglipa')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/health-request.css') }}">
@endpush

@section('content')
<!-- Hero Section with Background -->
<section class="position-relative" style="background: #eaf4fb; padding-top: 6rem; margin-top: -20px;">
    <div class="container py-4">
        <div class="text-center mb-4">
            <h1 class="fw-bold mb-2" style="color: #2A7BC4; font-size: 2.2rem;">Health Service Request</h1>
            <p class="text-muted" style="font-size: 1rem;">Schedule a health service appointment or request medical assistance</p>
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
                            <i class="fas fa-heartbeat me-3" style="font-size: 2rem;"></i>
                            <h2 class="mb-0 fw-bold">Health Services</h2>
                        </div>
                        <p class="mb-0 opacity-9">Complete the form below to request health services from Barangay Lumanglipa</p>
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

                <form id="healthRequestForm" method="POST">
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

                        <!-- Service Type Section -->
                        <div class="mb-4">
                            <label for="service_type" class="form-label fw-bold">
                                <i class="fas fa-stethoscope text-primary me-2"></i>
                                Service Type <span class="text-danger">*Required</span>
                            </label>
                            <select class="form-select form-select-lg" id="service_type" name="service_type" required disabled>
                                <option value="">Select Service Type</option>
                                <option value="General Consultation">General Consultation</option>
                                <option value="Blood Pressure Monitoring">Blood Pressure Monitoring</option>
                                <option value="Blood Sugar Check">Blood Sugar Check</option>
                                <option value="Vaccination">Vaccination</option>
                                <option value="Health Education">Health Education</option>
                                <option value="Medical Certificate">Medical Certificate</option>
                                <option value="Other">Other</option>
                            </select>
                            <div class="form-text">
                                <i class="fas fa-info-circle text-info"></i>
                                Choose the type of health service you need
                            </div>
                        </div>

                        <!-- Purpose Section -->
                        <div class="mb-4">
                            <label for="purpose" class="form-label fw-bold">
                                <i class="fas fa-clipboard-list text-primary me-2"></i>
                                Purpose / Additional Details <span class="text-danger">*Required</span>
                            </label>
                            <textarea class="form-control" 
                                      id="purpose" 
                                      name="purpose" 
                                      rows="4" 
                                      placeholder="Please provide details about your health concern or the reason for this service..."
                                      required
                                      disabled></textarea>
                            <div class="form-text">
                                <i class="fas fa-info-circle text-info"></i>
                                Describe your symptoms, concerns, or reason for the health service request
                            </div>
                        </div>

                        <!-- Appointment Type Section -->
                        <div class="mb-4">
                            <label for="appointment_type" class="form-label fw-bold">
                                <i class="fas fa-calendar-alt text-primary me-2"></i>
                                Appointment Type <span class="text-danger">*Required</span>
                            </label>
                            <select class="form-select form-select-lg" id="appointment_type" name="appointment_type" required disabled>
                                <option value="">Select Appointment Type</option>
                                <option value="walk-in">Walk-in (Same day service)</option>
                                <option value="scheduled">Scheduled Appointment</option>
                            </select>
                            <div class="form-text">
                                <i class="fas fa-info-circle text-info"></i>
                                Choose if you need immediate service or want to schedule for later
                            </div>
                        </div>

                        <!-- Scheduled Appointment Details -->
                        <div id="scheduledAppointmentSection" class="mb-4" style="display: none;">
                            <div class="card border-info">
                                <div class="card-header bg-info bg-opacity-10">
                                    <h6 class="mb-0 text-info">
                                        <i class="fas fa-clock me-2"></i>
                                        Appointment Schedule
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="preferred_date" class="form-label">Preferred Date <span class="text-danger">*Required</span></label>
                                            <input type="date" 
                                                   class="form-control" 
                                                   id="preferred_date" 
                                                   name="preferred_date"
                                                   disabled>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="preferred_time" class="form-label">Preferred Time <span class="text-danger">*Required</span></label>
                                            <select class="form-select" id="preferred_time" name="preferred_time" disabled>
                                                <option value="">Select Time</option>
                                                <option value="08:00">8:00 AM</option>
                                                <option value="09:00">9:00 AM</option>
                                                <option value="10:00">10:00 AM</option>
                                                <option value="11:00">11:00 AM</option>
                                                <option value="13:00">1:00 PM</option>
                                                <option value="14:00">2:00 PM</option>
                                                <option value="15:00">3:00 PM</option>
                                                <option value="16:00">4:00 PM</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chief Complaint Section -->
                        <div class="mb-4">
                            <label for="chief_complaint" class="form-label fw-bold">
                                <i class="fas fa-notes-medical text-primary me-2"></i>
                                Chief Complaint / Symptoms <span class="text-muted">*Optional</span>
                            </label>
                            <textarea class="form-control" 
                                      id="chief_complaint" 
                                      name="chief_complaint" 
                                      rows="3" 
                                      placeholder="Describe your main symptoms or health concerns..."
                                      disabled></textarea>
                        </div>

                        <!-- Medical History Section -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-history text-primary me-2"></i>
                                Medical History <span class="text-muted">*Optional</span>
                            </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Hypertension" id="hypertension" name="medical_history[]" disabled>
                                        <label class="form-check-label" for="hypertension">Hypertension</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Diabetes" id="diabetes" name="medical_history[]" disabled>
                                        <label class="form-check-label" for="diabetes">Diabetes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Heart Disease" id="heart_disease" name="medical_history[]" disabled>
                                        <label class="form-check-label" for="heart_disease">Heart Disease</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Asthma" id="asthma" name="medical_history[]" disabled>
                                        <label class="form-check-label" for="asthma">Asthma</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Allergies" id="allergies" name="medical_history[]" disabled>
                                        <label class="form-check-label" for="allergies">Allergies</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="None" id="no_medical_history" name="medical_history[]" disabled>
                                        <label class="form-check-label" for="no_medical_history">None</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact Section -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-phone text-primary me-2"></i>
                                Emergency Contact <span class="text-muted">*Optional</span>
                            </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="emergency_contact_name" class="form-label">Name <span class="text-muted">*Optional</span></label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="emergency_contact_name" 
                                           name="emergency_contact_name" 
                                           placeholder="Emergency contact name"
                                           disabled>
                                </div>
                                <div class="col-md-6">
                                    <label for="emergency_contact_number" class="form-label">Phone Number <span class="text-muted">*Optional</span></label>
                                    <input type="tel" 
                                           class="form-control" 
                                           id="emergency_contact_number" 
                                           name="emergency_contact_number" 
                                           placeholder="Emergency contact number"
                                           disabled>
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
                            <i class="fas fa-check text-success me-2"></i>
                            Email verification with OTP is required for all health service requests
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Walk-in appointments are subject to availability
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Scheduled appointments can be made 1 day in advance
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Health services are provided by qualified barangay health workers
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            You will receive confirmation and updates via email
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

#scheduledAppointmentSection {
    display: none;
    transition: all 0.3s ease;
}

#scheduledAppointmentSection.show {
    display: block;
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
    const purposeTextarea = document.getElementById('purpose');
    const appointmentTypeSelect = document.getElementById('appointment_type');
    const scheduledAppointmentSection = document.getElementById('scheduledAppointmentSection');
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

    // Initialize form with blur effect
    formFieldsSection.classList.add('blurred');

    // Form fields to enable/disable
    const formFields = [
        'service_type', 'purpose', 'appointment_type', 'preferred_date', 'preferred_time', 
        'chief_complaint', 'emergency_contact_name', 'emergency_contact_number'
    ];

    const checkboxFields = document.querySelectorAll('input[name="medical_history[]"]');

    // Date input min setup
    const preferredDateInput = document.getElementById('preferred_date');
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    preferredDateInput.min = tomorrow.toISOString().split('T')[0]; // Set min date to tomorrow

    // Show/hide appointment fields based on appointment type
    appointmentTypeSelect.addEventListener('change', function() {
        if (this.value === 'scheduled') {
            scheduledAppointmentSection.classList.add('show');
            document.getElementById('preferred_date').setAttribute('required', '');
            document.getElementById('preferred_time').setAttribute('required', '');
        } else {
            scheduledAppointmentSection.classList.remove('show');
            document.getElementById('preferred_date').removeAttribute('required');
            document.getElementById('preferred_time').removeAttribute('required');
        }
        checkFormValidity();
    });

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
                // Show resident information
                document.getElementById('residentName').textContent = data.resident.name;
                document.getElementById('residentAddress').textContent = data.resident.address;
                document.getElementById('residentAge').textContent = data.resident.age;
                document.getElementById('residentContact').textContent = data.resident.contact_number || 'N/A';
                
                // Display gender if available
                if (data.resident.gender) {
                    document.getElementById('residentGender').textContent = data.resident.gender;
                }
                
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
                
                // Enable checkbox fields
                checkboxFields.forEach(checkbox => {
                    checkbox.disabled = false;
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
        const serviceType = document.getElementById('service_type').value;
        const purpose = document.getElementById('purpose').value.trim();
        const appointmentType = document.getElementById('appointment_type').value;
        
        // Check if scheduled appointment fields are required and filled
        let appointmentFieldsValid = true;
        if (appointmentType === 'scheduled') {
            const preferredDate = document.getElementById('preferred_date').value;
            const preferredTime = document.getElementById('preferred_time').value;
            appointmentFieldsValid = preferredDate && preferredTime;
        }
        
        const isValid = residentVerified && otpVerified && serviceType && purpose && appointmentType && appointmentFieldsValid;
        submitBtn.disabled = !isValid;
    }

    // Add event listeners for form validation
    document.getElementById('service_type').addEventListener('change', checkFormValidity);
    document.getElementById('purpose').addEventListener('input', checkFormValidity);
    document.getElementById('appointment_type').addEventListener('change', checkFormValidity);
    document.getElementById('preferred_date').addEventListener('input', checkFormValidity);
    document.getElementById('preferred_time').addEventListener('change', checkFormValidity);

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
        
        // Collect medical history checkboxes
        const selectedMedicalHistory = [];
        checkboxFields.forEach(checkbox => {
            if (checkbox.checked) {
                selectedMedicalHistory.push(checkbox.value);
            }
        });
        
        // Create FormData
        const formData = new FormData(this);
        
        // Convert FormData to JSON object
        const submitData = Object.fromEntries(formData.entries());
        
        // Add medical history array
        submitData.medical_history = selectedMedicalHistory;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
        
        fetch('{{ route("health.request") }}', {
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
        
        scheduledAppointmentSection.classList.remove('show');
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
        
        // Disable and clear checkbox fields
        checkboxFields.forEach(checkbox => {
            checkbox.disabled = true;
            checkbox.checked = false;
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
