@extends('layouts.public.master')

@section('title', 'Pre-Registration Step 3 - Household Information')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Progress Bar (Updated Design) -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Registration Progress</h5>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col text-center">
                            <small class="text-success">✓ Personal Info</small>
                        </div>
                        <div class="col text-center">
                            <small class="text-success">✓ Contact & Education</small>
                        </div>
                        <div class="col text-center">
                            <small class="text-primary font-weight-bold">Step 3: Household Info</small>
                        </div>
                        <div class="col text-center">
                            <small class="text-muted">Step 4: Senior Info</small>
                        </div>
                        <div class="col text-center">
                            <small class="text-muted">Step 5: Family</small>
                        </div>
                        <div class="col text-center">
                            <small class="text-muted">Step 6: Review</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fe fe-home fe-16 mr-2"></i>Household Information
                    </h4>
                    <p class="text-muted mb-0">Household members and emergency contact information</p>
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
                    <form action="{{ route('public.pre-registration.step3.store') }}" method="POST">
                        @csrf
                        <!-- Primary Person in Household -->
                        <div class="card border-primary mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0 text-primary">
                                    <i class="fe fe-user fe-16 mr-2"></i>Primary Person in Household <span class="text-danger">*</span>
                                </h5>
                                <small class="text-muted">Main person responsible for the household</small>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="primary_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('primary_name') is-invalid @enderror" 
                                                   id="primary_name" name="primary_name" value="{{ old('primary_name', session('pre_registration.step3.primary_name')) }}" required>
                                            @error('primary_name')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="primary_birthday" class="form-label">Birthday <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('primary_birthday') is-invalid @enderror" 
                                                   id="primary_birthday" name="primary_birthday" value="{{ old('primary_birthday', session('pre_registration.step3.primary_birthday')) }}" required>
                                            @error('primary_birthday')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Gender <span class="text-danger">*</span></label>
                                            <select name="primary_gender" id="primary_gender" class="form-control @error('primary_gender') is-invalid @enderror" required>
                                                <option value="">Select Gender</option>
                                                <option value="Male" {{ old('primary_gender', session('pre_registration.step3.primary_gender')) == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ old('primary_gender', session('pre_registration.step3.primary_gender')) == 'Female' ? 'selected' : '' }}>Female</option>
                                                <option value="Non-binary" {{ old('primary_gender', session('pre_registration.step3.primary_gender')) == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                                                <option value="Transgender" {{ old('primary_gender', session('pre_registration.step3.primary_gender')) == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                                                <option value="Other" {{ old('primary_gender', session('pre_registration.step3.primary_gender')) == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('primary_gender')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="primary_phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                            <input type="tel" class="form-control @error('primary_phone') is-invalid @enderror" 
                                                   id="primary_phone" name="primary_phone" value="{{ old('primary_phone', session('pre_registration.step3.primary_phone')) }}" 
                                                   placeholder="09123456789" maxlength="11" pattern="[0-9]{11}" 
                                                   title="Please enter exactly 11 digits (e.g., 09123456789)" required>
                                            @error('primary_phone')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="primary_work" class="form-label">Work/Occupation</label>
                                            <input type="text" class="form-control @error('primary_work') is-invalid @enderror" 
                                                   id="primary_work" name="primary_work" value="{{ old('primary_work', session('pre_registration.step3.primary_work')) }}">
                                            @error('primary_work')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="primary_allergies" class="form-label">Allergies</label>
                                            <input type="text" class="form-control @error('primary_allergies') is-invalid @enderror" 
                                                   id="primary_allergies" name="primary_allergies" value="{{ old('primary_allergies', session('pre_registration.step3.primary_allergies')) }}" 
                                                   placeholder="Food, medicine, etc.">
                                            @error('primary_allergies')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                            </div>
                        </div>
                        <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="primary_medical_condition" class="form-label">Medical Condition</label>
                                            <textarea class="form-control @error('primary_medical_condition') is-invalid @enderror" 
                                                      id="primary_medical_condition" name="primary_medical_condition" rows="2" 
                                                      placeholder="Any ongoing medical conditions">{{ old('primary_medical_condition', session('pre_registration.step3.primary_medical_condition')) }}</textarea>
                                            @error('primary_medical_condition')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Secondary Person in Household -->
                        <div class="card border-info mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0 text-info">
                                    <i class="fe fe-users fe-16 mr-2"></i>Secondary Person in Household
                                </h5>
                                <small class="text-muted">Optional - Spouse, partner, or other significant household member</small>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="secondary_name" class="form-label">Full Name</label>
                                            <input type="text" class="form-control @error('secondary_name') is-invalid @enderror" 
                                                   id="secondary_name" name="secondary_name" value="{{ old('secondary_name', session('pre_registration.step3.secondary_name')) }}">
                                            @error('secondary_name')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="secondary_birthday" class="form-label">Birthday</label>
                                            <input type="date" class="form-control @error('secondary_birthday') is-invalid @enderror" 
                                                   id="secondary_birthday" name="secondary_birthday" value="{{ old('secondary_birthday', session('pre_registration.step3.secondary_birthday')) }}">
                                            @error('secondary_birthday')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Gender</label>
                                            <select name="secondary_gender" id="secondary_gender" class="form-control @error('secondary_gender') is-invalid @enderror">
                                                <option value="">Select Gender</option>
                                                <option value="Male" {{ old('secondary_gender', session('pre_registration.step3.secondary_gender')) == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ old('secondary_gender', session('pre_registration.step3.secondary_gender')) == 'Female' ? 'selected' : '' }}>Female</option>
                                                <option value="Non-binary" {{ old('secondary_gender', session('pre_registration.step3.secondary_gender')) == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                                                <option value="Transgender" {{ old('secondary_gender', session('pre_registration.step3.secondary_gender')) == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                                                <option value="Other" {{ old('secondary_gender', session('pre_registration.step3.secondary_gender')) == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('secondary_gender')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                            </div>
                        </div>
                        <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="secondary_phone" class="form-label">Phone Number</label>
                                            <input type="tel" class="form-control @error('secondary_phone') is-invalid @enderror" 
                                                   id="secondary_phone" name="secondary_phone" value="{{ old('secondary_phone', session('pre_registration.step3.secondary_phone')) }}" 
                                                   placeholder="09123456789" maxlength="11" pattern="[0-9]{11}" 
                                                   title="Please enter exactly 11 digits (e.g., 09123456789)">
                                            @error('secondary_phone')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="secondary_work" class="form-label">Work/Occupation</label>
                                            <input type="text" class="form-control @error('secondary_work') is-invalid @enderror" 
                                                   id="secondary_work" name="secondary_work" value="{{ old('secondary_work', session('pre_registration.step3.secondary_work')) }}">
                                            @error('secondary_work')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="secondary_allergies" class="form-label">Allergies</label>
                                            <input type="text" class="form-control @error('secondary_allergies') is-invalid @enderror" 
                                                   id="secondary_allergies" name="secondary_allergies" value="{{ old('secondary_allergies', session('pre_registration.step3.secondary_allergies')) }}" 
                                                   placeholder="Food, medicine, etc.">
                                            @error('secondary_allergies')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="secondary_medical_condition" class="form-label">Medical Condition</label>
                                            <textarea class="form-control @error('secondary_medical_condition') is-invalid @enderror" 
                                                      id="secondary_medical_condition" name="secondary_medical_condition" rows="2" 
                                                      placeholder="Any ongoing medical conditions">{{ old('secondary_medical_condition', session('pre_registration.step3.secondary_medical_condition')) }}</textarea>
                                            @error('secondary_medical_condition')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Emergency Contact -->
                        <div class="card border-danger mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0 text-danger">
                                    <i class="fe fe-phone fe-16 mr-2"></i>Emergency Contact
                                </h5>
                                <small class="text-muted">Person to contact in case of emergency</small>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emergency_contact_name" class="form-label">Full Name</label>
                                            <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                                   id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', session('pre_registration.step3.emergency_contact_name')) }}">
                                            @error('emergency_contact_name')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                            </div>
                        </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emergency_relationship" class="form-label">Relationship</label>
                                            <input type="text" class="form-control @error('emergency_relationship') is-invalid @enderror" 
                                                   id="emergency_relationship" name="emergency_relationship" value="{{ old('emergency_relationship', session('pre_registration.step3.emergency_relationship')) }}">
                                            @error('emergency_relationship')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emergency_work" class="form-label">Work/Occupation</label>
                                            <input type="text" class="form-control @error('emergency_work') is-invalid @enderror" 
                                                   id="emergency_work" name="emergency_work" value="{{ old('emergency_work', session('pre_registration.step3.emergency_work')) }}">
                                            @error('emergency_work')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="emergency_phone" class="form-label">Phone Number</label>
                                            <input type="tel" class="form-control @error('emergency_phone') is-invalid @enderror" 
                                                   id="emergency_phone" name="emergency_phone" value="{{ old('emergency_phone', session('pre_registration.step3.emergency_phone')) }}" 
                                                   placeholder="09123456789" maxlength="11" pattern="[0-9]{11}" 
                                                   title="Please enter exactly 11 digits (e.g., 09123456789)">
                                            @error('emergency_phone')
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('public.pre-registration.step2') }}" class="btn btn-secondary">
                                        <i class="fe fe-arrow-left"></i> Back: Contact & Education
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                Next: @if(isset($isSenior) && $isSenior) Senior Citizen Info <i class="fe fe-award"></i> @else Family Members <i class="fe fe-arrow-right"></i> @endif
                                    </button>
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

.form-check {
    padding: 0.5rem;
    border-radius: 0.25rem;
    transition: background-color 0.15s ease-in-out;
}

.form-check:hover {
    background-color: #f8f9fa;
}

.form-check-input:checked ~ .form-check-label {
    font-weight: 600;
    color: #007bff;
}
</style>
@endsection