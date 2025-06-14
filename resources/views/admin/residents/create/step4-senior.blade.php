@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
<li class="breadcrumb-item active" aria-current="page">Register New Resident</li>
@endsection

@section('page-title', 'Register New Resident - Step 4')
@section('page-subtitle', 'Senior Citizen Information')

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Progress Bar -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Registration Progress</h5>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="row mt-2">
                    <div class="col text-center">
                        <small class="text-success">✓ Personal Info</small>
                    </div>
                    <div class="col text-center">
                        <small class="text-success">✓ Citizenship</small>
                    </div>
                    <div class="col text-center">
                        <small class="text-success">✓ Household Info</small>
                    </div>
                    <div class="col text-center">
                        <small class="text-primary font-weight-bold">Step 4: Senior Info</small>
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
                    <i class="fe fe-user fe-16 mr-2"></i>Senior Citizen Information
                </h4>
                <p class="text-muted mb-0">Enter benefits, health, and program participation details for the senior citizen</p>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.residents.create.step4-senior.store') }}" method="POST">
                    @csrf
                    
                    <!-- Health Information -->
                    <div class="card border-primary mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0 text-primary">
                                <i class="fe fe-heart fe-16 mr-2"></i>Health Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="health_conditions" class="form-label">Health Conditions</label>
                                        <textarea class="form-control @error('health_conditions') is-invalid @enderror" 
                                            id="health_conditions" name="health_conditions" rows="3" 
                                            placeholder="List any health conditions">{{ old('health_conditions', session('registration.step4-senior.health_conditions')) }}</textarea>
                                        @error('health_conditions')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="medications" class="form-label">Medications</label>
                                        <textarea class="form-control @error('medications') is-invalid @enderror" 
                                            id="medications" name="medications" rows="3" 
                                            placeholder="List any medications being taken">{{ old('medications', session('registration.step4-senior.medications')) }}</textarea>
                                        @error('medications')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="blood_type" class="form-label">Blood Type</label>
                                        <select class="form-control @error('blood_type') is-invalid @enderror" id="blood_type" name="blood_type">
                                            <option value="">Select Blood Type</option>
                                            <option value="A+" {{ old('blood_type', session('registration.step4-senior.blood_type')) == 'A+' ? 'selected' : '' }}>A+</option>
                                            <option value="A-" {{ old('blood_type', session('registration.step4-senior.blood_type')) == 'A-' ? 'selected' : '' }}>A-</option>
                                            <option value="B+" {{ old('blood_type', session('registration.step4-senior.blood_type')) == 'B+' ? 'selected' : '' }}>B+</option>
                                            <option value="B-" {{ old('blood_type', session('registration.step4-senior.blood_type')) == 'B-' ? 'selected' : '' }}>B-</option>
                                            <option value="AB+" {{ old('blood_type', session('registration.step4-senior.blood_type')) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                            <option value="AB-" {{ old('blood_type', session('registration.step4-senior.blood_type')) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                            <option value="O+" {{ old('blood_type', session('registration.step4-senior.blood_type')) == 'O+' ? 'selected' : '' }}>O+</option>
                                            <option value="O-" {{ old('blood_type', session('registration.step4-senior.blood_type')) == 'O-' ? 'selected' : '' }}>O-</option>
                                            <option value="Unknown" {{ old('blood_type', session('registration.step4-senior.blood_type')) == 'Unknown' ? 'selected' : '' }}>Unknown</option>
                                        </select>
                                        @error('blood_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="allergies" class="form-label">Allergies</label>
                                        <input type="text" class="form-control @error('allergies') is-invalid @enderror" 
                                            id="allergies" name="allergies" value="{{ old('allergies', session('registration.step4-senior.allergies')) }}" 
                                            placeholder="Food, medicine, etc.">
                                        @error('allergies')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="last_medical_checkup" class="form-label">Last Medical Checkup</label>
                                        <input type="date" class="form-control @error('last_medical_checkup') is-invalid @enderror" 
                                            id="last_medical_checkup" name="last_medical_checkup" value="{{ old('last_medical_checkup', session('registration.step4-senior.last_medical_checkup')) }}">
                                        @error('last_medical_checkup')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="special_needs" class="form-label">Special Needs</label>
                                        <input type="text" class="form-control @error('special_needs') is-invalid @enderror" 
                                            id="special_needs" name="special_needs" value="{{ old('special_needs', session('registration.step4-senior.special_needs')) }}" 
                                            placeholder="Any special assistance requirements">
                                        @error('special_needs')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <div class="card border-warning mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0 text-warning">
                                <i class="fe fe-phone fe-16 mr-2"></i>Emergency Contact
                            </h5>
                            <small class="text-muted">Person to contact in case of emergency (will override household emergency contact)</small>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emergency_contact_name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                            id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', session('registration.step4-senior.emergency_contact_name')) }}">
                                        @error('emergency_contact_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emergency_contact_number" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control @error('emergency_contact_number') is-invalid @enderror" 
                                            id="emergency_contact_number" name="emergency_contact_number" value="{{ old('emergency_contact_number', session('registration.step4-senior.emergency_contact_number')) }}" 
                                            placeholder="09123456789">
                                        @error('emergency_contact_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emergency_contact_relationship" class="form-label">Relationship</label>
                                        <input type="text" class="form-control @error('emergency_contact_relationship') is-invalid @enderror" 
                                            id="emergency_contact_relationship" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', session('registration.step4-senior.emergency_contact_relationship')) }}" 
                                            placeholder="e.g., Son, Daughter, Brother">
                                        @error('emergency_contact_relationship')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Benefits & Programs -->
                    <div class="card border-info mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0 text-info">
                                <i class="fe fe-shield fe-16 mr-2"></i>Benefits & Program Participation
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label d-block">Receiving Pension</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="receiving_pension" name="receiving_pension" value="1" 
                                                {{ old('receiving_pension', session('registration.step4-senior.receiving_pension')) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="receiving_pension">Yes</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="pension_type" class="form-label">Pension Type</label>
                                        <select class="form-control @error('pension_type') is-invalid @enderror" id="pension_type" name="pension_type">
                                            <option value="">Select Type</option>
                                            <option value="SSS" {{ old('pension_type', session('registration.step4-senior.pension_type')) == 'SSS' ? 'selected' : '' }}>SSS</option>
                                            <option value="GSIS" {{ old('pension_type', session('registration.step4-senior.pension_type')) == 'GSIS' ? 'selected' : '' }}>GSIS</option>
                                            <option value="PVAO" {{ old('pension_type', session('registration.step4-senior.pension_type')) == 'PVAO' ? 'selected' : '' }}>PVAO</option>
                                            <option value="Other" {{ old('pension_type', session('registration.step4-senior.pension_type')) == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('pension_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="pension_amount" class="form-label">Pension Amount (PHP)</label>
                                        <input type="number" step="0.01" class="form-control @error('pension_amount') is-invalid @enderror" 
                                            id="pension_amount" name="pension_amount" value="{{ old('pension_amount', session('registration.step4-senior.pension_amount')) }}" 
                                            placeholder="0.00">
                                        @error('pension_amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label d-block">Has Senior Discount Card</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="has_senior_discount_card" name="has_senior_discount_card" value="1" 
                                                {{ old('has_senior_discount_card', session('registration.step4-senior.has_senior_discount_card')) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="has_senior_discount_card">Yes</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label d-block">Has PhilHealth</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="has_philhealth" name="has_philhealth" value="1" 
                                                {{ old('has_philhealth', session('registration.step4-senior.has_philhealth')) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="has_philhealth">Yes</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="philhealth_number" class="form-label">PhilHealth Number</label>
                                        <input type="text" class="form-control @error('philhealth_number') is-invalid @enderror" 
                                            id="philhealth_number" name="philhealth_number" value="{{ old('philhealth_number', session('registration.step4-senior.philhealth_number')) }}">
                                        @error('philhealth_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Program Participation -->
                    <div class="card border-success mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0 text-success">
                                <i class="fe fe-check-circle fe-16 mr-2"></i>Program Participation
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Select all programs the senior citizen is enrolled in:</label>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input" id="program_social_pension" name="programs_enrolled[]" value="Social Pension" 
                                                        {{ in_array('Social Pension', (array)old('programs_enrolled', session('registration.step4-senior.programs_enrolled') ?? [])) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="program_social_pension">Social Pension</label>
                                                </div>
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input" id="program_health_checkup" name="programs_enrolled[]" value="Regular Health Checkup" 
                                                        {{ in_array('Regular Health Checkup', (array)old('programs_enrolled', session('registration.step4-senior.programs_enrolled') ?? [])) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="program_health_checkup">Regular Health Checkup</label>
                                                </div>
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input" id="program_medicine" name="programs_enrolled[]" value="Free Medicine Program" 
                                                        {{ in_array('Free Medicine Program', (array)old('programs_enrolled', session('registration.step4-senior.programs_enrolled') ?? [])) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="program_medicine">Free Medicine Program</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input" id="program_centenarian" name="programs_enrolled[]" value="Centenarian Program" 
                                                        {{ in_array('Centenarian Program', (array)old('programs_enrolled', session('registration.step4-senior.programs_enrolled') ?? [])) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="program_centenarian">Centenarian Program</label>
                                                </div>
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input" id="program_recreational" name="programs_enrolled[]" value="Recreational Activities" 
                                                        {{ in_array('Recreational Activities', (array)old('programs_enrolled', session('registration.step4-senior.programs_enrolled') ?? [])) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="program_recreational">Recreational Activities</label>
                                                </div>
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input" id="program_day_center" name="programs_enrolled[]" value="Senior Day Center" 
                                                        {{ in_array('Senior Day Center', (array)old('programs_enrolled', session('registration.step4-senior.programs_enrolled') ?? [])) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="program_day_center">Senior Day Center</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input" id="program_livelihood" name="programs_enrolled[]" value="Livelihood Program" 
                                                        {{ in_array('Livelihood Program', (array)old('programs_enrolled', session('registration.step4-senior.programs_enrolled') ?? [])) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="program_livelihood">Livelihood Program</label>
                                                </div>
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input" id="program_seminar" name="programs_enrolled[]" value="Educational Seminars" 
                                                        {{ in_array('Educational Seminars', (array)old('programs_enrolled', session('registration.step4-senior.programs_enrolled') ?? [])) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="program_seminar">Educational Seminars</label>
                                                </div>
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input" id="program_other" name="programs_enrolled[]" value="Other Programs" 
                                                        {{ in_array('Other Programs', (array)old('programs_enrolled', session('registration.step4-senior.programs_enrolled') ?? [])) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="program_other">Other Programs</label>
                                                </div>
                                            </div>
                                        </div>
                                        @error('programs_enrolled')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="form-group">
                        <label for="notes" class="form-label">Additional Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                            id="notes" name="notes" rows="3" 
                            placeholder="Any additional information about the senior citizen">{{ old('notes', session('registration.step4-senior.notes')) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Form Navigation -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.residents.create.step3') }}" class="btn btn-secondary d-flex align-items-center justify-content-center">
                                    <i class="fe fe-arrow-left fe-16 mr-2"></i>
                                    <span>Back: Household Info</span>
                                </a>
                                <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center">
                                    <span>Next: Family Members</span>
                                    <i class="fe fe-arrow-right fe-16 ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(session('error'))
<script>
    window.addEventListener('DOMContentLoaded', function() {
        alert('Error: {{ session('error') }}');
    });
</script>
@endif
@endsection