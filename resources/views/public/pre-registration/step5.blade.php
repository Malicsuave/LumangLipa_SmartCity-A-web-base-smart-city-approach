@extends('layouts.public.master')

@section('title', 'Pre-Registration Step 5 - Review & Submit')

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
                    @if(isset($isSenior) && $isSenior)
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
                            <strong>Birthdate:</strong> {{ session('pre_registration.step1.birthdate') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Sex:</strong> {{ session('pre_registration.step1.sex') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Civil Status:</strong> {{ session('pre_registration.step1.civil_status') }}
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
                            <strong>Highest Education:</strong> {{ session('pre_registration.step2.highest_education') }}
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="row mb-4 mt-4">
                        <div class="col-12">
                            <h5 class="text-primary border-bottom pb-2">
                                <i class="fe fe-home"></i> Address Information
                                <a href="{{ route('public.pre-registration.step3') }}" class="btn btn-sm btn-outline-primary float-right">
                                    <i class="fe fe-edit"></i> Edit
                                </a>
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <strong>Complete Address:</strong> {{ session('pre_registration.step3.address_line') }}, 
                            {{ session('pre_registration.step3.barangay') }}, {{ session('pre_registration.step3.city') }}
                        </div>
                    </div>

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

                    <!-- Final Submission Form -->
                    <div class="mt-5">
                        <form method="POST" action="{{ route('public.pre-registration.submit') }}">
                            @csrf
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="final_confirmation" name="final_confirmation" required>
                                    <label class="custom-control-label" for="final_confirmation">
                                        I confirm that all information provided is accurate and complete to the best of my knowledge.
                                    </label>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('public.pre-registration.step4') }}" class="btn btn-outline-primary">
                                    <i class="fe fe-arrow-left"></i> Back
                                </a>
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="fe fe-check-circle"></i> Submit Registration
                                </button>
                            </div>
                        </form>
                    </div>
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
    background-color: #007bff;
    color: white;
}

.step.completed .step-number {
    background-color: #28a745;
    color: white;
}

.step.senior .step-number {
    background-color: #ffc107;
    color: #212529;
}
</style>
@endsection