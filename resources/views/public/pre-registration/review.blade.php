@extends('layouts.public.master')

@section('title', 'Pre-Registration Review - Final Step')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Progress Steps -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="progress-steps">
                        <div class="step completed">
                            <div class="step-number">1</div>
                            <div class="step-title">Personal Info</div>
                        </div>
                        <div class="step completed">
                            <div class="step-number">2</div>
                            <div class="step-title">Contact & Education</div>
                        </div>
                        <div class="step completed">
                            <div class="step-number">3</div>
                            <div class="step-title">Additional Info</div>
                        </div>
                        @if($isSenior)
                        <div class="step completed senior">
                            <div class="step-number"><i class="fe fe-award"></i></div>
                            <div class="step-title">Senior Citizen</div>
                        </div>
                        @endif
                        <div class="step completed">
                            <div class="step-number">4</div>
                            <div class="step-title">Photo & Documents</div>
                        </div>
                        <div class="step active">
                            <div class="step-number">5</div>
                            <div class="step-title">Review</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-lg">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fe fe-check-circle"></i> Review Your Information</h4>
                    <p class="mb-0 mt-2">Please review all information before final submission</p>
                </div>
                
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6><i class="fe fe-alert-circle"></i> Please correct the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            <h6><i class="fe fe-alert-circle"></i> Error</h6>
                            <p class="mb-0">{{ session('error') }}</p>
                        </div>
                    @endif

                    @if($isSenior)
                        <div class="alert alert-warning mb-4">
                            <h5><i class="fe fe-award"></i> Senior Citizen Registration</h5>
                            <p class="mb-0">You're registering as a Senior Citizen. Upon approval, you'll receive a <strong>Senior Citizen ID</strong> via email with access to benefits and discounts.</p>
                        </div>
                    @endif

                    <!-- Personal Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="text-primary border-bottom pb-2">
                                <i class="fe fe-user"></i> Personal Information
                                <a href="{{ route('public.pre-registration.step1') }}" class="btn btn-sm btn-outline-primary float-right">
                                    <i class="fe fe-edit"></i> Edit
                                </a>
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <strong>Name:</strong> {{ session('pre_registration.step1.first_name') }} 
                            {{ session('pre_registration.step1.middle_name') }} 
                            {{ session('pre_registration.step1.last_name') }}
                            {{ session('pre_registration.step1.suffix') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Type of Resident:</strong> {{ session('pre_registration.step1.type_of_resident') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Date of Birth:</strong> {{ \Carbon\Carbon::parse(session('pre_registration.step1.birthdate'))->format('F d, Y') }}
                            @php $age = \Carbon\Carbon::parse(session('pre_registration.step1.birthdate'))->age; @endphp
                            ({{ $age }} years old)
                        </div>
                        <div class="col-md-6">
                            <strong>Sex:</strong> {{ session('pre_registration.step1.sex') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Civil Status:</strong> {{ session('pre_registration.step1.civil_status') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Place of Birth:</strong> {{ session('pre_registration.step1.birthplace') }}
                        </div>
                    </div>

                    <!-- Contact & Education Information -->
                    <div class="row mb-4 mt-4">
                        <div class="col-12">
                            <h5 class="text-primary border-bottom pb-2">
                                <i class="fe fe-phone"></i> Contact & Education Information
                                <a href="{{ route('public.pre-registration.step2') }}" class="btn btn-sm btn-outline-primary float-right">
                                    <i class="fe fe-edit"></i> Edit
                                </a>
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <strong>Contact Number:</strong> {{ session('pre_registration.step2.contact_number') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Email Address:</strong> {{ session('pre_registration.step2.email_address') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Citizenship:</strong> {{ session('pre_registration.step2.citizenship_type') }}
                            @if(session('pre_registration.step2.citizenship_country'))
                                ({{ session('pre_registration.step2.citizenship_country') }})
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>Profession/Occupation:</strong> {{ session('pre_registration.step2.profession_occupation') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Educational Attainment:</strong> {{ session('pre_registration.step2.educational_attainment') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Education Status:</strong> {{ session('pre_registration.step2.education_status') }}
                        </div>
                        <div class="col-md-12">
                            <strong>Address:</strong> {{ session('pre_registration.step2.address') }}
                        </div>
                        @if(session('pre_registration.step2.religion'))
                        <div class="col-md-6">
                            <strong>Religion:</strong> {{ session('pre_registration.step2.religion') }}
                        </div>
                        @endif
                        @if(session('pre_registration.step2.monthly_income'))
                        <div class="col-md-6">
                            <strong>Monthly Income:</strong> ₱{{ number_format(session('pre_registration.step2.monthly_income'), 2) }}
                        </div>
                        @endif
                    </div>

                    <!-- Additional Information -->
                    <div class="row mb-4 mt-4">
                        <div class="col-12">
                            <h5 class="text-primary border-bottom pb-2">
                                <i class="fe fe-info"></i> Additional Information
                                <a href="{{ route('public.pre-registration.step3') }}" class="btn btn-sm btn-outline-primary float-right">
                                    <i class="fe fe-edit"></i> Edit
                                </a>
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        @if(session('pre_registration.step3.philsys_id'))
                        <div class="col-md-6">
                            <strong>PhilSys ID:</strong> {{ session('pre_registration.step3.philsys_id') }}
                        </div>
                        @endif
                        
                        @if(session('pre_registration.step3.population_sectors'))
                        <div class="col-md-12">
                            <strong>Population Sectors:</strong> 
                            {{ implode(', ', session('pre_registration.step3.population_sectors')) }}
                        </div>
                        @endif

                        @if(session('pre_registration.step3.mother_first_name') || session('pre_registration.step3.mother_middle_name') || session('pre_registration.step3.mother_last_name'))
                        <div class="col-md-12">
                            <strong>Mother's Name:</strong> 
                            {{ session('pre_registration.step3.mother_first_name') }} 
                            {{ session('pre_registration.step3.mother_middle_name') }} 
                            {{ session('pre_registration.step3.mother_last_name') }}
                        </div>
                        @endif
                    </div>

                    <!-- Senior Citizen Information -->
                    @if($isSenior && session('pre_registration.step4_senior'))
                    <div class="row mb-4 mt-4">
                        <div class="col-12">
                            <h5 class="text-warning border-bottom pb-2">
                                <i class="fe fe-award"></i> Senior Citizen Information
                                <a href="{{ route('public.pre-registration.step4-senior') }}" class="btn btn-sm btn-outline-warning float-right">
                                    <i class="fe fe-edit"></i> Edit
                                </a>
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        @if(session('pre_registration.step4_senior.pension_type'))
                        <div class="col-md-6">
                            <strong>Pension Type:</strong> {{ session('pre_registration.step4_senior.pension_type') }}
                        </div>
                        @endif
                        
                        @if(session('pre_registration.step4_senior.pension_amount'))
                        <div class="col-md-6">
                            <strong>Pension Amount:</strong> ₱{{ number_format(session('pre_registration.step4_senior.pension_amount'), 2) }}
                        </div>
                        @endif

                        @if(session('pre_registration.step4_senior.emergency_contact_name'))
                        <div class="col-md-4">
                            <strong>Emergency Contact:</strong> {{ session('pre_registration.step4_senior.emergency_contact_name') }}
                        </div>
                        @endif

                        @if(session('pre_registration.step4_senior.emergency_contact_relationship'))
                        <div class="col-md-4">
                            <strong>Relationship:</strong> {{ session('pre_registration.step4_senior.emergency_contact_relationship') }}
                        </div>
                        @endif

                        @if(session('pre_registration.step4_senior.emergency_contact_number'))
                        <div class="col-md-4">
                            <strong>Contact Number:</strong> {{ session('pre_registration.step4_senior.emergency_contact_number') }}
                        </div>
                        @endif

                        @if(session('pre_registration.step4_senior.living_arrangement'))
                        <div class="col-md-6">
                            <strong>Living Arrangement:</strong> {{ session('pre_registration.step4_senior.living_arrangement') }}
                        </div>
                        @endif

                        @if(session('pre_registration.step4_senior.mobility_status'))
                        <div class="col-md-6">
                            <strong>Mobility Status:</strong> {{ session('pre_registration.step4_senior.mobility_status') }}
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Documents -->
                    <div class="row mb-4 mt-4">
                        <div class="col-12">
                            <h5 class="text-primary border-bottom pb-2">
                                <i class="fe fe-camera"></i> Documents
                                <a href="{{ route('public.pre-registration.step4') }}" class="btn btn-sm btn-outline-primary float-right">
                                    <i class="fe fe-edit"></i> Edit
                                </a>
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <strong>Photo:</strong> 
                            <span class="text-success"><i class="fe fe-check"></i> Uploaded</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Signature:</strong> 
                            @if(session('pre_registration.step4.signature'))
                                <span class="text-success"><i class="fe fe-check"></i> Uploaded</span>
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </div>
                    </div>

                    <!-- Final Confirmation -->
                    <div class="row mb-4 mt-4">
                        <div class="col-12">
                            <h5 class="text-success border-bottom pb-2">
                                <i class="fe fe-check-circle"></i> Final Confirmation
                            </h5>
                        </div>
                    </div>

                    <form action="{{ route('public.pre-registration.store') }}" method="POST">
                        @csrf
                        
                        <div class="alert alert-info">
                            <h6><i class="fe fe-info"></i> Important Notice</h6>
                            <ul class="mb-0">
                                <li>Please review all information carefully before submitting</li>
                                <li>Your registration will be reviewed by Barangay Administration</li>
                                <li>{{ $isSenior ? 'Senior Citizen ID' : 'Digital ID' }} will be sent to your email upon approval</li>
                                <li>You can check your registration status using your email address</li>
                            </ul>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input @error('final_confirmation') is-invalid @enderror" 
                                   type="checkbox" name="final_confirmation" id="final_confirmation" required>
                            <label class="form-check-label" for="final_confirmation">
                                I confirm that all information provided is accurate and complete. I understand that providing false information may result in rejection of my application.
                                <span class="text-danger">*</span>
                            </label>
                            @error('final_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('public.pre-registration.step4') }}" class="btn btn-secondary">
                                        <i class="fe fe-arrow-left"></i> Back: Photo & Documents
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fe fe-send"></i> Submit @if($isSenior)Senior Citizen @endif Registration
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.progress-steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

.progress-steps::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    height: 2px;
    background-color: #e9ecef;
    z-index: 1;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    background: white;
    padding: 0 10px;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-bottom: 8px;
}

.step.active .step-number {
    background-color: #28a745;
    color: white;
}

.step.completed .step-number {
    background-color: #28a745;
    color: white;
}

.step.senior.completed .step-number {
    background-color: #ffc107;
    color: #212529;
}

.step-title {
    font-size: 12px;
    text-align: center;
    color: #6c757d;
}

.step.active .step-title {
    color: #28a745;
    font-weight: 600;
}

.step.completed .step-title {
    color: #28a745;
    font-weight: 600;
}

.step.senior.completed .step-title {
    color: #ffc107;
    font-weight: 600;
}

.row > div {
    margin-bottom: 10px;
}
</style>
@endsection