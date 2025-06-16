@extends('layouts.public.master')

@section('title', 'Pre-Registration - Senior Citizen Information')

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
                        <div class="step active senior">
                            <div class="step-number"><i class="fe fe-award"></i></div>
                            <div class="step-title">Senior Citizen</div>
                        </div>
                        <div class="step">
                            <div class="step-number">4</div>
                            <div class="step-title">Photo & Documents</div>
                        </div>
                        <div class="step">
                            <div class="step-number">5</div>
                            <div class="step-title">Review</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-lg border-warning">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0"><i class="fe fe-award"></i> Senior Citizen Information</h4>
                    <p class="mb-0 mt-2">Additional information for your Senior Citizen ID</p>
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

                    <div class="alert alert-info mb-4">
                        <h5><i class="fe fe-info"></i> Senior Citizen Benefits</h5>
                        <p class="mb-2">As a Senior Citizen, you are entitled to:</p>
                        <ul class="mb-0">
                            <li>20% discount on medicines and medical services</li>
                            <li>Priority lanes in government offices and establishments</li>
                            <li>Discounts on transportation and other services</li>
                            <li>Access to senior citizen programs and benefits</li>
                        </ul>
                    </div>

                    <form action="{{ route('public.pre-registration.step4-senior.store') }}" method="POST">
                        @csrf
                        
                        <!-- Pension Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-warning border-bottom pb-2">
                                    <i class="fe fe-dollar-sign"></i> Pension Information
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pension_type" class="form-label">Pension Type</label>
                                <select class="form-control @error('pension_type') is-invalid @enderror" name="pension_type">
                                    <option value="">Select Pension Type (if applicable)</option>
                                    <option value="SSS Pension" {{ old('pension_type', session('pre_registration.step4_senior.pension_type')) == 'SSS Pension' ? 'selected' : '' }}>SSS Pension</option>
                                    <option value="GSIS Pension" {{ old('pension_type', session('pre_registration.step4_senior.pension_type')) == 'GSIS Pension' ? 'selected' : '' }}>GSIS Pension</option>
                                    <option value="Government Pension" {{ old('pension_type', session('pre_registration.step4_senior.pension_type')) == 'Government Pension' ? 'selected' : '' }}>Government Pension</option>
                                    <option value="Private Pension" {{ old('pension_type', session('pre_registration.step4_senior.pension_type')) == 'Private Pension' ? 'selected' : '' }}>Private Pension</option>
                                    <option value="No Pension" {{ old('pension_type', session('pre_registration.step4_senior.pension_type')) == 'No Pension' ? 'selected' : '' }}>No Pension</option>
                                </select>
                                @error('pension_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="pension_amount" class="form-label">Monthly Pension Amount</label>
                                <input type="number" class="form-control @error('pension_amount') is-invalid @enderror" 
                                       name="pension_amount" value="{{ old('pension_amount', session('pre_registration.step4_senior.pension_amount')) }}" 
                                       min="0" step="0.01" placeholder="Enter amount (optional)">
                                @error('pension_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-warning border-bottom pb-2">
                                    <i class="fe fe-phone"></i> Emergency Contact Information
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="emergency_contact_name" class="form-label">Emergency Contact Name</label>
                                <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                       name="emergency_contact_name" value="{{ old('emergency_contact_name', session('pre_registration.step4_senior.emergency_contact_name')) }}">
                                @error('emergency_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="emergency_contact_relationship" class="form-label">Relationship</label>
                                <input type="text" class="form-control @error('emergency_contact_relationship') is-invalid @enderror" 
                                       name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', session('pre_registration.step4_senior.emergency_contact_relationship')) }}"
                                       placeholder="e.g., Son, Daughter, Spouse">
                                @error('emergency_contact_relationship')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="emergency_contact_number" class="form-label">Contact Number</label>
                                <input type="tel" class="form-control @error('emergency_contact_number') is-invalid @enderror" 
                                       name="emergency_contact_number" value="{{ old('emergency_contact_number', session('pre_registration.step4_senior.emergency_contact_number')) }}"
                                       placeholder="09XXXXXXXXX" maxlength="11">
                                @error('emergency_contact_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Health Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-warning border-bottom pb-2">
                                    <i class="fe fe-heart"></i> Health Information
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="health_conditions" class="form-label">Health Conditions</label>
                                <textarea class="form-control @error('health_conditions') is-invalid @enderror" 
                                          name="health_conditions" rows="3" 
                                          placeholder="List any health conditions, disabilities, or chronic illnesses">{{ old('health_conditions', session('pre_registration.step4_senior.health_conditions')) }}</textarea>
                                @error('health_conditions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="medications" class="form-label">Current Medications</label>
                                <textarea class="form-control @error('medications') is-invalid @enderror" 
                                          name="medications" rows="3" 
                                          placeholder="List current medications and dosages">{{ old('medications', session('pre_registration.step4_senior.medications')) }}</textarea>
                                @error('medications')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Living Situation -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-warning border-bottom pb-2">
                                    <i class="fe fe-home"></i> Living Situation
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="living_arrangement" class="form-label">Living Arrangement</label>
                                <select class="form-control @error('living_arrangement') is-invalid @enderror" name="living_arrangement">
                                    <option value="">Select Living Arrangement</option>
                                    <option value="Alone" {{ old('living_arrangement', session('pre_registration.step4_senior.living_arrangement')) == 'Alone' ? 'selected' : '' }}>Living Alone</option>
                                    <option value="With Family" {{ old('living_arrangement', session('pre_registration.step4_senior.living_arrangement')) == 'With Family' ? 'selected' : '' }}>Living with Family</option>
                                    <option value="With Caregiver" {{ old('living_arrangement', session('pre_registration.step4_senior.living_arrangement')) == 'With Caregiver' ? 'selected' : '' }}>With Caregiver</option>
                                    <option value="Assisted Living" {{ old('living_arrangement', session('pre_registration.step4_senior.living_arrangement')) == 'Assisted Living' ? 'selected' : '' }}>Assisted Living Facility</option>
                                </select>
                                @error('living_arrangement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="mobility_status" class="form-label">Mobility Status</label>
                                <select class="form-control @error('mobility_status') is-invalid @enderror" name="mobility_status">
                                    <option value="">Select Mobility Status</option>
                                    <option value="Independent" {{ old('mobility_status', session('pre_registration.step4_senior.mobility_status')) == 'Independent' ? 'selected' : '' }}>Independent</option>
                                    <option value="Needs Assistance" {{ old('mobility_status', session('pre_registration.step4_senior.mobility_status')) == 'Needs Assistance' ? 'selected' : '' }}>Needs Assistance</option>
                                    <option value="Wheelchair Bound" {{ old('mobility_status', session('pre_registration.step4_senior.mobility_status')) == 'Wheelchair Bound' ? 'selected' : '' }}>Wheelchair Bound</option>
                                    <option value="Bedridden" {{ old('mobility_status', session('pre_registration.step4_senior.mobility_status')) == 'Bedridden' ? 'selected' : '' }}>Bedridden</option>
                                </select>
                                @error('mobility_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('public.pre-registration.step3') }}" class="btn btn-secondary">
                                        <i class="fe fe-arrow-left"></i> Back: Additional Info
                                    </a>
                                    <button type="submit" class="btn btn-warning">
                                        Next: Photo & Documents <i class="fe fe-arrow-right"></i>
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
    background-color: #007bff;
    color: white;
}

.step.completed .step-number {
    background-color: #28a745;
    color: white;
}

.step.senior.active .step-number {
    background-color: #ffc107;
    color: #212529;
}

.step-title {
    font-size: 12px;
    text-align: center;
    color: #6c757d;
}

.step.active .step-title {
    color: #007bff;
    font-weight: 600;
}

.step.completed .step-title {
    color: #28a745;
    font-weight: 600;
}

.step.senior.active .step-title {
    color: #ffc107;
    font-weight: 600;
}
</style>
@endsection