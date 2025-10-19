@extends('layouts.admin.master')

@section('page-header', 'New Resident Registration')

@section('page-header-extra')
    <p class="text-muted mb-0">Provide contact information and additional details for the resident</p>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
    <li class="breadcrumb-item active">New Registration</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/registration-form.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">
                    <i class="fas fa-phone mr-2"></i>
                    Contact Information
                </strong>
                <div class="card-tools">
                    <span class="badge badge-light">Step 2 of 4</span>
                </div>
            </div>
            <form action="{{ route('admin.residents.create.step2.store') }}" method="POST" id="step2Form">
                @csrf
                <div class="card-body registration-form">

                    <div class="card-body">
                        <!-- Contact Information -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('contact_number') is-invalid @enderror" 
                                       id="contact_number" name="contact_number" 
                                       value="{{ old('contact_number', session('registration.step2.contact_number')) }}" 
                                       placeholder="09123456789" required>
                                @error('contact_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email_address" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email_address') is-invalid @enderror" 
                                       id="email_address" name="email_address" 
                                       value="{{ old('email_address', session('registration.step2.email_address')) }}" 
                                       placeholder="resident@example.com">
                                @error('email_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Current Address -->
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="current_address" class="form-label">Current Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('current_address') is-invalid @enderror" 
                                          id="current_address" name="current_address" 
                                          rows="3" placeholder="House No., Street" required>{{ old('current_address', session('registration.step2.current_address')) }}</textarea>
                                @error('current_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Purok Field -->
                            <div class="col-md-4 mb-3">
                                <label for="purok" class="form-label">Purok <span class="text-danger">*</span></label>
                                <select class="form-control @error('purok') is-invalid @enderror" 
                                        id="purok" name="purok" required>
                                    <option value="">Select Purok</option>
                                    <option value="Purok 1" {{ old('purok', session('registration.step2.purok')) == 'Purok 1' ? 'selected' : '' }}>Purok 1</option>
                                    <option value="Purok 2" {{ old('purok', session('registration.step2.purok')) == 'Purok 2' ? 'selected' : '' }}>Purok 2</option>
                                    <option value="Purok 3" {{ old('purok', session('registration.step2.purok')) == 'Purok 3' ? 'selected' : '' }}>Purok 3</option>
                                    <option value="Purok 4" {{ old('purok', session('registration.step2.purok')) == 'Purok 4' ? 'selected' : '' }}>Purok 4</option>
                                    <option value="Purok 5" {{ old('purok', session('registration.step2.purok')) == 'Purok 5' ? 'selected' : '' }}>Purok 5</option>
                                    <option value="Purok 6" {{ old('purok', session('registration.step2.purok')) == 'Purok 6' ? 'selected' : '' }}>Purok 6</option>
                                    <option value="Purok 7" {{ old('purok', session('registration.step2.purok')) == 'Purok 7' ? 'selected' : '' }}>Purok 7</option>
                                    <option value="Purok 8" {{ old('purok', session('registration.step2.purok')) == 'Purok 8' ? 'selected' : '' }}>Purok 8</option>
                                    <option value="Purok 9" {{ old('purok', session('registration.step2.purok')) == 'Purok 9' ? 'selected' : '' }}>Purok 9</option>
                                    <option value="Purok 10" {{ old('purok', session('registration.step2.purok')) == 'Purok 10' ? 'selected' : '' }}>Purok 10</option>
                                    <option value="Other" {{ old('purok', session('registration.step2.purok')) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('purok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Custom Purok Input (shown when "Other" is selected) -->
                        <div class="row" id="custom-purok-row" style="display: none;">
                            <div class="col-md-12 mb-3">
                                <label for="custom_purok" class="form-label">Specify Purok/Sitio <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('custom_purok') is-invalid @enderror" 
                                       id="custom_purok" name="custom_purok" 
                                       value="{{ old('custom_purok', session('registration.step2.custom_purok')) }}" 
                                       placeholder="Enter purok or sitio name">
                                @error('custom_purok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Emergency Contact -->
                        <hr>
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-phone mr-2"></i>Emergency Contact
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="emergency_contact_name" class="form-label">Contact Person <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                       id="emergency_contact_name" name="emergency_contact_name" 
                                       value="{{ old('emergency_contact_name', session('registration.step2.emergency_contact_name')) }}" 
                                       placeholder="Full name of emergency contact" required>
                                @error('emergency_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="emergency_contact_relationship" class="form-label">Relationship <span class="text-danger">*</span></label>
                                <select class="form-control @error('emergency_contact_relationship') is-invalid @enderror" 
                                        id="emergency_contact_relationship" name="emergency_contact_relationship" required>
                                    <option value="">Select relationship</option>
                                    <option value="Parent" {{ old('emergency_contact_relationship', session('registration.step2.emergency_contact_relationship')) == 'Parent' ? 'selected' : '' }}>Parent</option>
                                    <option value="Spouse" {{ old('emergency_contact_relationship', session('registration.step2.emergency_contact_relationship')) == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                                    <option value="Child" {{ old('emergency_contact_relationship', session('registration.step2.emergency_contact_relationship')) == 'Child' ? 'selected' : '' }}>Child</option>
                                    <option value="Sibling" {{ old('emergency_contact_relationship', session('registration.step2.emergency_contact_relationship')) == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                                    <option value="Relative" {{ old('emergency_contact_relationship', session('registration.step2.emergency_contact_relationship')) == 'Relative' ? 'selected' : '' }}>Relative</option>
                                    <option value="Friend" {{ old('emergency_contact_relationship', session('registration.step2.emergency_contact_relationship')) == 'Friend' ? 'selected' : '' }}>Friend</option>
                                    <option value="Other" {{ old('emergency_contact_relationship', session('registration.step2.emergency_contact_relationship')) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('emergency_contact_relationship')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="emergency_contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('emergency_contact_number') is-invalid @enderror" 
                                       id="emergency_contact_number" name="emergency_contact_number" 
                                       value="{{ old('emergency_contact_number', session('registration.step2.emergency_contact_number')) }}" 
                                       placeholder="09123456789" required>
                                @error('emergency_contact_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="emergency_contact_address" class="form-label">Emergency Contact Address</label>
                                <textarea class="form-control @error('emergency_contact_address') is-invalid @enderror" 
                                          id="emergency_contact_address" name="emergency_contact_address" 
                                          rows="2" placeholder="Address of emergency contact (optional)">{{ old('emergency_contact_address', session('registration.step2.emergency_contact_address')) }}</textarea>
                                @error('emergency_contact_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('admin.residents.create.step1') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>Previous Step
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-primary" id="nextBtn">
                                Continue to Step 3 <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/form-validation.js') }}"></script>
@endpush

@section('scripts')
<script>
$(document).ready(function() {
    console.log('Step 2 inline script loaded');
    
    // Test if the submit button works at all
    $('#nextBtn').on('click', function(e) {
        console.log('Submit button clicked');
        console.log('Form action:', $('#step2Form').attr('action'));
        console.log('Form method:', $('#step2Form').attr('method'));
    });
    
    // Contact number formatting (additional to form-validation.js)
    $('#contact_number, #emergency_contact_number').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 11) {
            value = value.substring(0, 11);
        }
        $(this).val(value);
    });
    
    // Handle purok selection
    $('#purok').on('change', function() {
        if ($(this).val() === 'Other') {
            $('#custom-purok-row').show();
            $('#custom_purok').prop('required', true);
        } else {
            $('#custom-purok-row').hide();
            $('#custom_purok').prop('required', false).val('');
        }
    });
    
    // Initialize on page load
    if ($('#purok').val() === 'Other') {
        $('#custom-purok-row').show();
        $('#custom_purok').prop('required', true);
    }
});
</script>
@endsection
