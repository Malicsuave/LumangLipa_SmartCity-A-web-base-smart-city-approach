@extends('layouts.admin.master')

@section('page-header', 'New Senior Citizen Registration')

@section('page-header-extra')
    <p class="text-muted mb-0">Complete the registration process by reviewing a                                <div class="info-value text-muted">{{ session('senior_registration.step2.emergency_contact_number') }}</div>l information before final submission</p>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.senior-citizens.index') }}">Senior Citizens</a></li>
    <li class="breadcrumb-item active">New Registration</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/registration-form.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card bg-white border-0 shadow-lg">
            <div class="card-header bg-primary">
                <h3 class="card-title text-white">
                    <i class="fas fa-check-circle mr-2"></i>
                    Review Senior Citizen Registration
                </h3>
                <div class="card-tools">
                    <span class="badge badge-light">Step 5 of 5</span>
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
                                <a href="{{ route('admin.senior-citizens.register.step1') }}" class="btn btn-sm btn-outline-secondary float-right">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Last Name:</strong>
                                <div class="info-value text-muted">{{ session('senior_registration.step1.last_name') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">First Name:</strong>
                                <div class="info-value text-muted">{{ session('senior_registration.step1.first_name') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Middle Name:</strong>
                                <div class="info-value text-muted">{{ session('senior_registration.step1.middle_name') ?: 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Suffix:</strong>
                                <div class="info-value text-muted">{{ session('senior_registration.step1.suffix') ?: 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Date of Birth:</strong>
                                <div class="info-value text-muted">
                                    @php
                                        $birthdate = session('senior_registration.step1.birthdate');
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
                                <div class="info-value text-muted">{{ session('senior_registration.step1.birthplace') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Gender:</strong>
                                <div class="info-value text-muted">{{ session('senior_registration.step1.sex') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Civil Status:</strong>
                                <div class="info-value text-muted">{{ session('senior_registration.step1.civil_status') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Citizenship:</strong>
                                <div class="info-value text-muted">
                                    {{ session('senior_registration.step1.citizenship_type') }}
                                    @if(session('senior_registration.step1.citizenship_country'))
                                        ({{ session('senior_registration.step1.citizenship_country') }})
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Educational Attainment:</strong>
                                <div class="info-value text-muted">{{ session('senior_registration.step1.educational_attainment') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Education Status:</strong>
                                <div class="info-value text-muted">{{ session('senior_registration.step1.education_status') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Religion:</strong>
                                <div class="info-value text-muted">{{ session('senior_registration.step1.religion') ?: 'Not specified' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Profession/Occupation:</strong>
                                <div class="info-value text-muted">{{ session('senior_registration.step1.profession_occupation') ?: 'Not specified' }}</div>
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
                                <a href="{{ route('admin.senior-citizens.register.step2') }}" class="btn btn-sm btn-outline-secondary float-right">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Contact Number:</strong>
                                <div class="info-value text-muted">{{ session('senior_registration.step2.contact_number') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Email Address:</strong>
                                <div class="info-value text-muted">{{ session('senior_registration.step2.email_address') ?: 'Not provided' }}</div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Current Address:</strong>
                                <div class="info-value text-muted">{{ session('senior_registration.step2.current_address') }}</div>
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
                                <a href="{{ route('admin.senior-citizens.register.step4') }}" class="btn btn-sm btn-outline-secondary float-right">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Contact Person:</strong>
                                <div class="info-value text-muted">{{ session('senior_registration.step2.emergency_contact_name') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Relationship:</strong>
                                <div class="info-value text-muted">{{ session('senior_registration.step2.emergency_contact_relationship') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Contact Number:</strong>
                                <div class="info-value text-muted">{{ session('senior_registration.step4.emergency_contact_number') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Address:</strong>
                                <div class="info-value text-muted">Not collected</div>
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
                                <a href="{{ route('admin.senior-citizens.register.step3') }}" class="btn btn-sm btn-outline-secondary float-right">
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
                                    @if(session('senior_registration.step3.photo'))
                                        <img src="{{ asset('storage/' . session('senior_registration.step3.photo')) }}" 
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
                                    @if(session('senior_registration.step3.signature'))
                                        <img src="{{ asset('storage/' . session('senior_registration.step3.signature')) }}" 
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

                <!-- Pension and Benefits Section -->
                <div class="mb-4">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h5 class="text-primary border-bottom pb-2">
                                <i class="fas fa-money-check-alt mr-2"></i>Pension & Benefits Information
                                <a href="{{ route('admin.senior-citizens.register.step4') }}" class="btn btn-sm btn-outline-secondary float-right">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Receiving Pension:</strong>
                                <div class="info-value text-muted">
                                    @if(session('senior_registration.step4.receiving_pension') == '1')
                                        <span class="text-success"><i class="fas fa-check mr-1"></i>Yes</span>
                                    @else
                                        <span class="text-muted"><i class="fas fa-times mr-1"></i>No</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Pension Type:</strong>
                                <div class="info-value text-muted">{{ session('senior_registration.step4.pension_type') ?: 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Monthly Pension Amount:</strong>
                                <div class="info-value text-muted">
                                    @if(session('senior_registration.step4.pension_amount'))
                                        ₱{{ number_format(session('senior_registration.step4.pension_amount'), 2) }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">PhilHealth:</strong>
                                <div class="info-value text-muted">
                                    @if(session('senior_registration.step4.has_philhealth') == '1')
                                        <span class="text-success"><i class="fas fa-check mr-1"></i>Yes</span>
                                        @if(session('senior_registration.step4.philhealth_number'))
                                            <br><small class="text-muted">{{ session('senior_registration.step4.philhealth_number') }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted"><i class="fas fa-times mr-1"></i>No</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Senior Citizen Discount Card:</strong>
                                <div class="info-value text-muted">
                                    @if(session('senior_registration.step4.has_senior_discount_card') == '1')
                                        <span class="text-success"><i class="fas fa-check mr-1"></i>Yes</span>
                                    @else
                                        <span class="text-muted"><i class="fas fa-times mr-1"></i>No</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Health, Services, and Notes Section -->
                <div class="mb-4">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h5 class="text-primary border-bottom pb-2">
                                <i class="fas fa-heartbeat mr-2"></i>Health, Services & Notes
                                <a href="{{ route('admin.senior-citizens.register.step4') }}" class="btn btn-sm btn-outline-secondary float-right">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Health Condition:</strong>
                                <div class="info-value text-muted">{{ ucfirst(session('senior_registration.step4.health_condition')) ?: 'Not specified' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Mobility Status:</strong>
                                <div class="info-value text-muted">{{ ucfirst(session('senior_registration.step4.mobility_status')) ?: 'Not specified' }}</div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Medical Conditions & Health Details:</strong>
                                <div class="info-value text-muted">{{ session('senior_registration.step4.medical_conditions') ?: 'None' }}</div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Requested Services:</strong>
                                <div class="info-value text-muted">
                                    @php
                                        $services = session('senior_registration.step4.services', []);
                                        $serviceLabels = [
                                            'healthcare' => 'Healthcare Services',
                                            'financial_assistance' => 'Financial Assistance',
                                            'education' => 'Educational Programs',
                                            'legal_assistance' => 'Legal Assistance',
                                            'transportation' => 'Transportation Services',
                                            'discount_privileges' => 'Discount Privileges',
                                            'emergency_response' => 'Emergency Response',
                                        ];
                                    @endphp
                                    @if(!empty($services))
                                        <ul class="list-unstyled mb-0">
                                            @foreach($services as $service)
                                                <li><i class="fas fa-check text-success mr-1"></i>{{ $serviceLabels[$service] ?? $service }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">No services selected</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="info-item">
                                <strong class="info-label">Additional Notes:</strong>
                                <div class="info-value text-muted">{{ session('senior_registration.step4.notes') ?: 'None' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Confirmation and Submit -->
            <form action="{{ route('admin.senior-citizens.register.store') }}" method="POST" id="finalSubmitForm">
                @csrf
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
                            <li>Your registration will be processed and a senior citizen record will be created</li>
                            <li>A unique Senior Citizen ID will be automatically generated</li>
                            @if(session('senior_registration.step2.email_address'))
                                <li>An email with the senior citizen ID will be sent to <strong>{{ session('senior_registration.step2.email_address') }}</strong></li>
                            @endif
                            <li>The senior citizen will be eligible for ID card generation and barangay services</li>
                        </ul>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <a href="{{ route('admin.senior-citizens.register.step4') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>Previous Step
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
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