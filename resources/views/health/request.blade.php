@extends('layouts.public.master')

@section('title', 'Health Service Request - Barangay Lumanglipa')

@section('content')
<div class="container-fluid p-0">
    <!-- Header Banner -->
    <div class="bg-primary text-white py-4 px-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-auto">
                    <img src="{{ asset('images/barangay-logo.png') }}" alt="Barangay Logo" height="70" class="me-3">
                </div>
                <div class="col">
                    <h2 class="mb-0">Barangay Lumanglipa</h2>
                    <div id="current-time" class="text-end text-white">
                        <h3 class="mb-0 display-6 fw-bold" id="clock"></h3>
                        <p class="mb-0" id="date"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-light min-vh-100 py-4">
        <div class="container bg-white shadow rounded-3 p-0">
            <!-- Form Header -->
            <div class="bg-primary text-white p-4 rounded-top">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-heartbeat fa-2x"></i>
                    </div>
                    <div>
                        <h2 class="mb-1">Health Service Appointment</h2>
                        <p class="mb-0">Schedule a health service appointment or request medical assistance from Barangay Lumanglipa</p>
                    </div>
                </div>
            </div>

            <div class="p-4">
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

                <!-- Form Begin -->
                <form id="healthRequestForm" method="POST" class="mt-4">
                    @csrf

                    <!-- Step 1: ID Verification -->
                    <div class="card border shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-id-card text-primary me-2"></i>
                                Barangay ID
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="input-group mb-2">
                                        <input type="text" 
                                               class="form-control form-control-lg" 
                                               id="barangay_id" 
                                               name="barangay_id" 
                                               placeholder="Enter your Barangay ID"
                                               required>
                                        <button type="button" 
                                                class="btn btn-primary" 
                                                id="checkResidentBtn">
                                            <i class="fas fa-search me-2"></i> Verify
                                        </button>
                                    </div>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle text-info"></i>
                                        Enter your registered Barangay ID to verify your information
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Resident Information -->
                    <div id="residentInfo" class="card border shadow-sm mb-4 blurred-section" style="display: none;">
                        <div class="blur-overlay">
                            <div class="overlay-message">
                                <i class="fas fa-lock fa-2x mb-3"></i>
                                <h5>Verify Email to View</h5>
                                <p>Complete email verification to see your information</p>
                            </div>
                        </div>
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-user-check text-success me-2"></i>
                                Verified Resident Information
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="border-start border-primary border-4 ps-3">
                                        <p class="mb-1 text-muted">Full Name</p>
                                        <h5 id="residentName" class="mb-0">-</h5>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border-start border-primary border-4 ps-3">
                                        <p class="mb-1 text-muted">Address</p>
                                        <h5 id="residentAddress" class="mb-0">-</h5>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border-start border-info border-4 ps-3">
                                        <p class="mb-1 text-muted">Age</p>
                                        <h5 id="residentAge" class="mb-0">-</h5>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="border-start border-info border-4 ps-3">
                                        <p class="mb-1 text-muted">Gender</p>
                                        <h5 id="residentGender" class="mb-0">Not specified</h5>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border-start border-info border-4 ps-3">
                                        <p class="mb-1 text-muted">Contact Number</p>
                                        <h5 id="residentContact" class="mb-0">-</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: OTP Verification -->
                    <div id="otpSection" class="card border border-warning mb-4" style="display: none;">
                        <div class="card-header bg-warning bg-opacity-10">
                            <h5 class="mb-0 text-warning">
                                <i class="fas fa-shield-alt me-2"></i>
                                Email Verification Required
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div id="otpRequestStep">
                                <div class="row">
                                    <div class="col-md-8">
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
                                </div>
                            </div>
                            
                            <div id="otpVerifyStep" style="display: none;">
                                <p class="mb-3">
                                    <i class="fas fa-envelope text-success me-2"></i>
                                    A 6-digit OTP has been sent to: <strong id="emailHint"></strong>
                                </p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="otp_code" class="form-label">Enter OTP Code</label>
                                            <input type="text" 
                                                   class="form-control form-control-lg text-center" 
                                                   id="otp_code" 
                                                   placeholder="000000" 
                                                   maxlength="6"
                                                   pattern="[0-9]{6}">
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-success" id="verifyOtpBtn">
                                                <i class="fas fa-check me-2"></i>
                                                Verify OTP
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" id="resendOtpBtn">
                                                <i class="fas fa-redo me-2"></i>
                                                Resend
                                            </button>
                                        </div>
                                        <div class="form-text mt-2">
                                            <i class="fas fa-clock text-muted me-1"></i>
                                            <span id="otpTimer">OTP expires in 10:00</span>
                                        </div>
                                    </div>
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

                    <!-- Service Request Form Section -->
                    <div id="serviceFormSection" class="blurred-section">
                        <div class="blur-overlay">
                            <div class="overlay-message">
                                <i class="fas fa-lock fa-2x mb-3"></i>
                                <h5>Verify Email to Continue</h5>
                                <p>Please complete email verification to access the form fields</p>
                            </div>
                        </div>
                        
                        <!-- Step 4: Appointment Details -->
                        <div class="card border shadow-sm mb-4">
                            <div class="card-header bg-primary bg-opacity-75 text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar-check me-2"></i>
                                    Appointment Details
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <!-- Service Type Selection -->
                                    <div class="col-md-6">
                                        <label for="service_type" class="form-label">
                                            <i class="fas fa-stethoscope text-primary me-2"></i>
                                            Service Type
                                        </label>
                                        <select class="form-select form-select-lg" id="service_type" name="service_type" required disabled>
                                            <option value="">Select Health Service</option>
                                            @foreach($serviceTypes as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle text-info"></i>
                                            Choose the type of health service you need
                                        </div>
                                    </div>
                                    
                                    <!-- Appointment Type -->
                                    <div class="col-md-6">
                                        <label for="appointment_type" class="form-label">
                                            <i class="fas fa-user-clock text-primary me-2"></i>
                                            Appointment Type
                                        </label>
                                        <select class="form-select form-select-lg" id="appointment_type" name="appointment_type" required disabled>
                                            <option value="">Select Appointment Type</option>
                                            <option value="scheduled">Scheduled Appointment</option>
                                            <option value="walk_in">Walk-in Request</option>
                                        </select>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle text-info"></i>
                                            Choose whether you want to schedule a specific time or walk in
                                        </div>
                                    </div>
                                </div>

                                <!-- Date & Time Selection (Conditional) -->
                                <div id="scheduledAppointmentSection" class="row g-4 mt-4" style="display: none;">
                                    <div class="col-md-6">
                                        <label for="preferred_date" class="form-label">
                                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                                            Preferred Date
                                        </label>
                                        <input type="date" class="form-control form-control-lg" id="preferred_date" name="preferred_date" disabled>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle text-info"></i>
                                            Select your preferred appointment date
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="preferred_time" class="form-label">
                                            <i class="fas fa-clock text-primary me-2"></i>
                                            Preferred Time
                                        </label>
                                        <select class="form-select form-select-lg" id="preferred_time" name="preferred_time" disabled>
                                            <option value="">Select Time Slot</option>
                                            <option value="morning_early">8:00 AM - 9:00 AM</option>
                                            <option value="morning_mid">9:00 AM - 10:00 AM</option>
                                            <option value="morning_late">10:00 AM - 11:00 AM</option>
                                            <option value="afternoon_early">1:00 PM - 2:00 PM</option>
                                            <option value="afternoon_mid">2:00 PM - 3:00 PM</option>
                                            <option value="afternoon_late">3:00 PM - 4:00 PM</option>
                                        </select>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle text-info"></i>
                                            Select your preferred time slot
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Step 5: Health Information -->
                        <div class="card border shadow-sm mb-4">
                            <div class="card-header bg-info bg-opacity-75 text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-notes-medical me-2"></i>
                                    Health Information
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <!-- Purpose -->
                                    <div class="col-md-6">
                                        <label for="purpose" class="form-label">
                                            <i class="fas fa-clipboard-list text-primary me-2"></i>
                                            Purpose
                                        </label>
                                        <textarea class="form-control" 
                                                id="purpose" 
                                                name="purpose" 
                                                rows="4" 
                                                placeholder="Please specify the purpose for requesting this health service..."
                                                required
                                                disabled></textarea>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle text-info"></i>
                                            Provide detailed information about why you need this service
                                        </div>
                                    </div>
                                    
                                    <!-- Chief Complaint -->
                                    <div class="col-md-6">
                                        <label for="chief_complaint" class="form-label">
                                            <i class="fas fa-exclamation-circle text-primary me-2"></i>
                                            Chief Complaint
                                        </label>
                                        <textarea class="form-control" 
                                                id="chief_complaint" 
                                                name="chief_complaint" 
                                                rows="4" 
                                                placeholder="Describe your main symptoms or concerns..."
                                                disabled></textarea>
                                        <div class="form-text">
                                            <i class="fas fa-info-circle text-info"></i>
                                            Describe your main health concern or symptom
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Step 6: Medical History -->
                        <div class="card border shadow-sm mb-4">
                            <div class="card-header bg-success bg-opacity-75 text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-history me-2"></i>
                                    Medical History
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="card bg-light h-100">
                                            <div class="card-body">
                                                <h6 class="mb-3">
                                                    <i class="fas fa-check-square text-primary me-2"></i>
                                                    Pre-existing Conditions
                                                </h6>
                                                <p class="text-muted small mb-3">Check all conditions that apply to you</p>
                                                
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-check mb-3">
                                                            <input class="form-check-input" type="checkbox" id="history_hypertension" name="medical_history[]" value="hypertension" disabled>
                                                            <label class="form-check-label" for="history_hypertension">Hypertension (High Blood Pressure)</label>
                                                        </div>
                                                        <div class="form-check mb-3">
                                                            <input class="form-check-input" type="checkbox" id="history_diabetes" name="medical_history[]" value="diabetes" disabled>
                                                            <label class="form-check-label" for="history_diabetes">Diabetes</label>
                                                        </div>
                                                        <div class="form-check mb-3">
                                                            <input class="form-check-input" type="checkbox" id="history_asthma" name="medical_history[]" value="asthma" disabled>
                                                            <label class="form-check-label" for="history_asthma">Asthma</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-check mb-3">
                                                            <input class="form-check-input" type="checkbox" id="history_heart" name="medical_history[]" value="heart_disease" disabled>
                                                            <label class="form-check-label" for="history_heart">Heart Disease</label>
                                                        </div>
                                                        <div class="form-check mb-3">
                                                            <input class="form-check-input" type="checkbox" id="history_allergy" name="medical_history[]" value="allergies" disabled>
                                                            <label class="form-check-label" for="history_allergy">Allergies</label>
                                                        </div>
                                                        <div class="form-check mb-3">
                                                            <input class="form-check-input" type="checkbox" id="history_other" name="medical_history[]" value="other" disabled>
                                                            <label class="form-check-label" for="history_other">Other</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="additional_info" class="form-label">
                                            <i class="fas fa-plus-circle text-primary me-2"></i>
                                            Additional Medical Information
                                        </label>
                                        <textarea class="form-control h-75" 
                                                id="additional_info" 
                                                name="additional_info" 
                                                rows="5" 
                                                placeholder="Please provide any additional health information that may be relevant (e.g., current medications, allergies, previous treatments)..."
                                                disabled></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Step 7: Vital Signs -->
                        <div class="card border shadow-sm mb-4">
                            <div class="card-header bg-secondary bg-opacity-50 text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-heartbeat me-2"></i>
                                    Recent Vital Signs (Optional)
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="blood_pressure" name="blood_pressure" placeholder="e.g., 120/80" disabled>
                                            <label for="blood_pressure">Blood Pressure</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="number" class="form-control" id="heart_rate" name="heart_rate" placeholder="e.g., 75" disabled>
                                            <label for="heart_rate">Heart Rate (BPM)</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="temperature" name="temperature" placeholder="e.g., 36.5" disabled>
                                            <label for="temperature">Temperature (°C)</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="blood_sugar" name="blood_sugar" placeholder="If applicable" disabled>
                                            <label for="blood_sugar">Blood Sugar (mg/dL)</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Step 8: Emergency Contact -->
                        <div class="card border shadow-sm mb-4">
                            <div class="card-header bg-danger bg-opacity-50 text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-phone-alt me-2"></i>
                                    Emergency Contact Information
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" placeholder="Name of person to contact in emergency" disabled>
                                            <label for="emergency_contact_name">Emergency Contact Name</label>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="emergency_contact_number" name="emergency_contact_number" placeholder="Phone number of emergency contact" disabled>
                                            <label for="emergency_contact_number">Emergency Contact Number</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Section -->
                    <div class="card border-0 mb-4">
                        <div class="card-body p-0">
                            <button type="submit" 
                                    class="btn btn-primary btn-lg w-100 py-3" 
                                    id="submitBtn" 
                                    disabled>
                                <i class="fas fa-paper-plane me-2"></i>
                                Submit Health Service Request
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Update the datetime at the top of the page
function updateClock() {
    const now = new Date();
    
    // Update time
    let hours = now.getHours();
    let minutes = now.getMinutes();
    let seconds = now.getSeconds();
    let ampm = hours >= 12 ? 'PM' : 'AM';
    
    hours = hours % 12;
    hours = hours ? hours : 12; // The hour '0' should be '12'
    
    document.getElementById('clock').innerHTML = 
        hours.toString().padStart(2, '0') + ':' + 
        minutes.toString().padStart(2, '0') + ':' + 
        seconds.toString().padStart(2, '0') + ' ' + ampm;
    
    // Update date
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('date').innerHTML = now.toLocaleDateString('en-US', options);
}

document.addEventListener('DOMContentLoaded', function() {
    // Setup clock
    updateClock();
    setInterval(updateClock, 1000);
    
    // Form functionality
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
    const appointmentTypeSelect = document.getElementById('appointment_type');
    const scheduledAppointmentSection = document.getElementById('scheduledAppointmentSection');
    
    // Form fields to enable/disable
    const formFields = [
        'service_type', 'purpose', 'appointment_type', 'preferred_date', 'preferred_time', 
        'chief_complaint', 'additional_info', 'blood_pressure', 'heart_rate', 
        'temperature', 'blood_sugar', 'emergency_contact_name', 'emergency_contact_number'
    ];

    const checkboxFields = document.querySelectorAll('input[name="medical_history[]"]');

    let residentVerified = false;
    let otpVerified = false;
    let otpTimer = null;
    let otpExpiryTime = null;

    // Date input min setup
    const preferredDateInput = document.getElementById('preferred_date');
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);
    preferredDateInput.min = tomorrow.toISOString().split('T')[0]; // Set min date to tomorrow

    // Show/hide appointment fields based on appointment type
    appointmentTypeSelect.addEventListener('change', function() {
        if (this.value === 'scheduled') {
            scheduledAppointmentSection.style.display = 'flex';
            document.getElementById('preferred_date').setAttribute('required', '');
            document.getElementById('preferred_time').setAttribute('required', '');
        } else {
            scheduledAppointmentSection.style.display = 'none';
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
                    const field = document.getElementById(fieldName);
                    if (field) field.disabled = false;
                });
                
                // Enable checkbox fields
                checkboxFields.forEach(checkbox => {
                    checkbox.disabled = false;
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
                showSuccess(`Health appointment request submitted successfully! Reference ID: ${data.data.request_id}`);
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
    }
    
    function resetForm() {
        residentInfo.style.display = 'none';
        otpSection.style.display = 'none';
        scheduledAppointmentSection.style.display = 'none';
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
            const field = document.getElementById(fieldName);
            if (field) field.disabled = true;
        });
        
        // Disable checkbox fields
        checkboxFields.forEach(checkbox => {
            checkbox.disabled = true;
            checkbox.checked = false;
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
        });
    }
    
    // Initialize blur effects on page load
    addBlurEffects();
});
</script>
@endpush

@push('styles')
<style>
.card {
    transition: all 0.3s ease;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
}

.card:hover {
    transform: translateY(-2px);
}

.card-header {
    border-radius: 0.5rem 0.5rem 0 0 !important;
    padding: 1rem 1.5rem;
}

.form-control,
.form-select {
    padding: 0.75rem 1rem;
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
    border-radius: 0.5rem;
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

.border-start {
    transition: all 0.3s ease;
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
    background-color: rgba(255, 255, 255, 0.7);
    border-radius: 0.25rem;
}

.form-floating > .form-control,
.form-floating > .form-select {
    height: calc(3.5rem + 2px);
    padding: 1rem 0.75rem;
}

.form-floating > label {
    padding: 1rem 0.75rem;
}
</style>
@endpush
