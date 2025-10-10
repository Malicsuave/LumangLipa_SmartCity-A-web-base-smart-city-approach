@extends('layouts.admin.master')

@section('page-header', 'New Resident Registration')

@section('page-header-extra')
    <p class="text-muted mb-0">Complete the registration process by reviewing all information before final submission</p>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
    <li class="breadcrumb-item active">New Registration</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/registration-form.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endpush

@section('content')
@php
    // Ensure step3 session exists with minimal data for the store method
    if (!session('registration.step3')) {
        session(['registration.step3' => [
            'review_completed' => true,
            'timestamp' => now()
        ]]);
    }
@endphp

<!-- Debug Information (only show if session data is missing) -->
@if(!session('registration.step1') || !session('registration.step2'))
<div class="row mb-3">
    <div class="col-md-12">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <strong>Registration Data Missing:</strong>
            <ul class="mb-0 mt-2">
                @if(!session('registration.step1'))
                    <li>Step 1 data is missing. Please <a href="{{ route('admin.residents.create.step1') }}">start from Step 1</a>.</li>
                @endif
                @if(!session('registration.step2'))
                    <li>Step 2 data is missing. Please complete <a href="{{ route('admin.residents.create.step2') }}">Step 2</a>.</li>
                @endif
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title text-white">
                    <i class="fas fa-check-circle mr-2"></i>
                    Review Registration Information
                </h3>
                <div class="card-tools">
                    <span class="badge badge-light">Step 4 of 4</span>
                </div>
            </div>
            <div class="card-body">
                <p class="card-text text-muted mb-4">Please review all information before submitting. You can go back to edit any section if needed.</p>
                
                <!-- Personal Information Section -->
                <div class="mb-4">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h5 class="text-primary border-bottom pb-2">
                                <i class="fas fa-user mr-2"></i>Personal Information
                                <a href="{{ route('admin.residents.create.step1') }}" class="btn btn-sm btn-outline-secondary float-right">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Type of Resident:</strong>
                                <div class="info-value text-muted">{{ session('registration.step1.type_of_resident') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Full Name:</strong>
                                <div class="info-value text-muted">
                                    {{ session('registration.step1.first_name') }} 
                                    {{ session('registration.step1.middle_name') }} 
                                    {{ session('registration.step1.last_name') }}
                                    {{ session('registration.step1.suffix') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Date of Birth:</strong>
                                <div class="info-value text-muted">
                                    @php
                                        $birthdate = session('registration.step1.birthdate');
                                        if ($birthdate) {
                                            $date = \Carbon\Carbon::parse($birthdate);
                                            echo $date->format('F d, Y') . ' (' . $date->age . ' years old)';
                                        }
                                    @endphp
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Place of Birth:</strong>
                                <div class="info-value text-muted">{{ session('registration.step1.birthplace') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Gender:</strong>
                                <div class="info-value text-muted">{{ session('registration.step1.sex') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Civil Status:</strong>
                                <div class="info-value text-muted">{{ session('registration.step1.civil_status') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Citizenship:</strong>
                                <div class="info-value text-muted">
                                    {{ session('registration.step1.citizenship_type') }}
                                    @if(session('registration.step1.citizenship_country'))
                                        ({{ session('registration.step1.citizenship_country') }})
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Educational Attainment:</strong>
                                <div class="info-value text-muted">{{ session('registration.step1.educational_attainment') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Education Status:</strong>
                                <div class="info-value text-muted">{{ session('registration.step1.education_status') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Religion:</strong>
                                <div class="info-value text-muted">{{ session('registration.step1.religion') ?: 'Not specified' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Profession/Occupation:</strong>
                                <div class="info-value text-muted">{{ session('registration.step1.profession_occupation') ?: 'Not specified' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="mb-4">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h5 class="text-primary border-bottom pb-2">
                                <i class="fas fa-address-book mr-2"></i>Contact Information
                                <a href="{{ route('admin.residents.create.step2') }}" class="btn btn-sm btn-outline-secondary float-right">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Contact Number:</strong>
                                <div class="info-value text-muted">{{ session('registration.step2.contact_number') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Email Address:</strong>
                                <div class="info-value text-muted">{{ session('registration.step2.email_address') ?: 'Not provided' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Current Address:</strong>
                                <div class="info-value text-muted">{{ session('registration.step2.current_address') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Purok:</strong>
                                <div class="info-value text-muted">{{ session('registration.step2.purok') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact Section -->
                <div class="mb-4">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h5 class="text-primary border-bottom pb-2">
                                <i class="fas fa-phone mr-2"></i>Emergency Contact
                                <a href="{{ route('admin.residents.create.step2') }}" class="btn btn-sm btn-outline-secondary float-right">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Contact Person:</strong>
                                <div class="info-value text-muted">{{ session('registration.step2.emergency_contact_name') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Relationship:</strong>
                                <div class="info-value text-muted">{{ session('registration.step2.emergency_contact_relationship') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Contact Number:</strong>
                                <div class="info-value text-muted">{{ session('registration.step2.emergency_contact_number') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Address:</strong>
                                <div class="info-value text-muted">{{ session('registration.step2.emergency_contact_address') ?: 'Not provided' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Photo and Signature Section -->
                <div class="mb-4">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h5 class="text-primary border-bottom pb-2">
                                <i class="fas fa-camera mr-2"></i>Photo and Signature
                                <a href="{{ route('admin.residents.create.step3') }}" class="btn btn-sm btn-outline-secondary float-right">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Profile Photo:</strong>
                                <div class="info-value">
                                    @if(session('registration.step3.photo'))
                                        <img src="{{ asset('storage/' . session('registration.step3.photo')) }}" 
                                             alt="Profile Photo" 
                                             style="max-width: 150px; max-height: 150px; border-radius: 8px; border: 2px solid #007bff; margin-top: 10px;">
                                        <br><small class="text-success mt-2"><i class="fas fa-check mr-1"></i>Photo uploaded</small>
                                    @else
                                        <span class="text-muted">No photo uploaded</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Digital Signature:</strong>
                                <div class="info-value">
                                    @if(session('registration.step3.signature'))
                                        <img src="{{ asset('storage/' . session('registration.step3.signature')) }}" 
                                             alt="Digital Signature" 
                                             style="max-width: 250px; max-height: 100px; border-radius: 8px; border: 2px solid #007bff; margin-top: 10px;">
                                        <br><small class="text-success mt-2"><i class="fas fa-check mr-1"></i>Signature uploaded</small>
                                    @else
                                        <span class="text-muted">No signature uploaded</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Confirmation and Submit -->
            <form action="{{ route('admin.residents.create.store') }}" method="POST" id="finalSubmitForm">
                @csrf
                
                <!-- Debug Information (hidden) -->
                <input type="hidden" name="debug_step1" value="{{ Session::has('registration.step1') ? 'exists' : 'missing' }}">
                <input type="hidden" name="debug_step2" value="{{ Session::has('registration.step2') ? 'exists' : 'missing' }}">
                <input type="hidden" name="debug_step3" value="{{ Session::has('registration.step3') ? 'exists' : 'missing' }}">
                
                <div class="card-footer">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h5 class="text-success mb-3">
                                <i class="fas fa-check-circle mr-2"></i>Final Confirmation
                            </h5>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="confirmation" name="confirmation" required>
                                <label class="form-check-label" for="confirmation">
                                    I confirm that all the information provided above is accurate and complete. 
                                    I understand that providing false information may result in the rejection of this registration.
                                </label>
                            </div>
                            @error('confirmation')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>What happens next:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Your registration will be processed and a resident record will be created</li>
                            <li>A unique Barangay ID will be automatically generated</li>
                            @if(session('registration.step2.email_address'))
                                <li>An email with the resident ID will be sent to <strong>{{ session('registration.step2.email_address') }}</strong></li>
                            @endif
                            <li>The resident will be eligible for ID card generation and barangay services</li>
                        </ul>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <a href="{{ route('admin.residents.create.step3') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>Previous Step
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                <i class="fas fa-check mr-2"></i>Complete Registration
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Debug: Log session data availability
    console.log('Step 1 data:', {{ session('registration.step1') ? 'true' : 'false' }});
    console.log('Step 2 data:', {{ session('registration.step2') ? 'true' : 'false' }});
    console.log('Step 3 data:', {{ session('registration.step3') ? 'true' : 'false' }});
    
    // Flash messages are now handled automatically by the master layout with toastr
    
    // Show validation errors with toastr
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            showError('{{ $error }}', 'Validation Error');
        @endforeach
        
        // Auto-scroll to top for easy error viewing
        $('html, body').animate({
            scrollTop: 0
        }, 500);
    @endif
    
    // Form submission handling
    $('#finalSubmitForm').on('submit', function(e) {
        if (!$('#confirmation').is(':checked')) {
            e.preventDefault();
            showWarning('Please confirm that all information is accurate before submitting.', 'Confirmation Required');
            return false;
        }
        
        console.log('Form submitted, processing registration...');
        
        // Show loading toastr notification
        showInfo('Please wait while we create the resident record...', 'Registering Resident');
        
        // Disable form inputs to prevent changes during submission
        $(this).find('input, button, a').prop('disabled', true);
        
        // Add a timeout to handle potential server issues
        setTimeout(function() {
            if ($('#finalSubmitForm').find('input, button, a').first().prop('disabled')) {
                showError('Registration is taking longer than expected. Please check your connection and try again.', 'Request Timeout');
                $('#finalSubmitForm').find('input, button, a').prop('disabled', false);
                console.error('Registration timeout - check server logs');
            }
        }, 30000); // 30 seconds timeout
        
        // Allow form to submit
        return true;
    });
    
    // Confirmation checkbox styling
    $('#confirmation').on('change', function() {
        if ($(this).is(':checked')) {
            $('#submitBtn').removeClass('btn-secondary').addClass('btn-success').prop('disabled', false);
        } else {
            $('#submitBtn').removeClass('btn-success').addClass('btn-secondary').prop('disabled', true);
        }
    });
    
    // Initialize submit button state
    $('#submitBtn').prop('disabled', true);
});
</script>
@endsection

@push('scripts')
<script src="{{ asset('js/form-validation.js') }}"></script>
@endpush
