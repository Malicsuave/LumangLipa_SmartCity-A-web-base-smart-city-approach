@extends('layouts.admin.master')

@section('page-header', 'New Resident Registration')

@section('page-header-extra')
    <p class="text-muted mb-0">Enter the resident's personal information and household details</p>
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
                    <i class="fas fa-user mr-2"></i>
                    Personal Information
                </strong>
                <div class="card-tools">
                    <span class="badge badge-light">Step 1 of 4</span>
                </div>
            </div>
            <form action="{{ route('admin.residents.create.step1.store') }}" method="POST" id="step1Form">
                @csrf
                <div class="card-body registration-form">

                    <div class="card-body">
                        <!-- Type of Resident -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="type_of_resident" class="form-label">Type of Resident <span class="text-danger">*</span></label>
                                <select class="form-control @error('type_of_resident') is-invalid @enderror" 
                                        id="type_of_resident" name="type_of_resident" required>
                                    <option value="">Select type of resident</option>
                                    <option value="Non-Migrant" {{ old('type_of_resident', session('registration.step1.type_of_resident')) == 'Non-Migrant' ? 'selected' : '' }}>Non-Migrant</option>
                                    <option value="Migrant" {{ old('type_of_resident', session('registration.step1.type_of_resident')) == 'Migrant' ? 'selected' : '' }}>Migrant</option>
                                    <option value="Transient" {{ old('type_of_resident', session('registration.step1.type_of_resident')) == 'Transient' ? 'selected' : '' }}>Transient</option>
                                </select>
                                @error('type_of_resident')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Name Fields -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" name="first_name" 
                                       value="{{ old('first_name', session('registration.step1.first_name')) }}" 
                                       placeholder="Enter first name" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control @error('middle_name') is-invalid @enderror" 
                                       id="middle_name" name="middle_name" 
                                       value="{{ old('middle_name', session('registration.step1.middle_name')) }}" 
                                       placeholder="Enter middle name (optional)">
                                @error('middle_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                       id="last_name" name="last_name" 
                                       value="{{ old('last_name', session('registration.step1.last_name')) }}" 
                                       placeholder="Enter last name" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="suffix" class="form-label">Suffix</label>
                                <input type="text" class="form-control @error('suffix') is-invalid @enderror" 
                                       id="suffix" name="suffix" 
                                       value="{{ old('suffix', session('registration.step1.suffix')) }}" 
                                       placeholder="Jr., Sr., III, etc. (optional)">
                                @error('suffix')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Birth Information -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="birthdate" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('birthdate') is-invalid @enderror" 
                                       id="birthdate" name="birthdate" 
                                       value="{{ old('birthdate', session('registration.step1.birthdate')) }}" 
                                       required>
                                @error('birthdate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="birthplace" class="form-label">Place of Birth <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('birthplace') is-invalid @enderror" 
                                       id="birthplace" name="birthplace" 
                                       value="{{ old('birthplace', session('registration.step1.birthplace')) }}" 
                                       placeholder="City, Province" required>
                                @error('birthplace')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Gender and Civil Status -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="sex" class="form-label">Gender <span class="text-danger">*</span></label>
                                <select class="form-control @error('sex') is-invalid @enderror" 
                                        id="sex" name="sex" required>
                                    <option value="">Select gender</option>
                                    <option value="Male" {{ old('sex', session('registration.step1.sex')) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sex', session('registration.step1.sex')) == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Non-binary" {{ old('sex', session('registration.step1.sex')) == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                                    <option value="Transgender" {{ old('sex', session('registration.step1.sex')) == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                                    <option value="Other" {{ old('sex', session('registration.step1.sex')) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('sex')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="civil_status" class="form-label">Civil Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('civil_status') is-invalid @enderror" 
                                        id="civil_status" name="civil_status" required>
                                    <option value="">Select civil status</option>
                                    <option value="Single" {{ old('civil_status', session('registration.step1.civil_status')) == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ old('civil_status', session('registration.step1.civil_status')) == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Widowed" {{ old('civil_status', session('registration.step1.civil_status')) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                    <option value="Separated" {{ old('civil_status', session('registration.step1.civil_status')) == 'Separated' ? 'selected' : '' }}>Separated</option>
                                    <option value="Divorced" {{ old('civil_status', session('registration.step1.civil_status')) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                </select>
                                @error('civil_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Citizenship Information -->
                        <hr>
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-flag mr-2"></i>Citizenship Information
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="citizenship_type" class="form-label">Citizenship Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('citizenship_type') is-invalid @enderror" 
                                        id="citizenship_type" name="citizenship_type" required>
                                    <option value="">Select citizenship type</option>
                                    <option value="FILIPINO" {{ old('citizenship_type', session('registration.step1.citizenship_type')) == 'FILIPINO' ? 'selected' : '' }}>Filipino</option>
                                    <option value="DUAL" {{ old('citizenship_type', session('registration.step1.citizenship_type')) == 'DUAL' ? 'selected' : '' }}>Dual Citizen</option>
                                    <option value="NATURALIZED" {{ old('citizenship_type', session('registration.step1.citizenship_type')) == 'NATURALIZED' ? 'selected' : '' }}>Naturalized Filipino</option>
                                    <option value="FOREIGN" {{ old('citizenship_type', session('registration.step1.citizenship_type')) == 'FOREIGN' ? 'selected' : '' }}>Foreign National</option>
                                </select>
                                @error('citizenship_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="citizenship_country" class="form-label">Country <span class="citizenship-country-required text-danger" style="display: none;">*</span></label>
                                <input type="text" class="form-control @error('citizenship_country') is-invalid @enderror" 
                                       id="citizenship_country" name="citizenship_country" 
                                       value="{{ old('citizenship_country', session('registration.step1.citizenship_country')) }}" 
                                       placeholder="Enter country (for dual/foreign citizens)">
                                @error('citizenship_country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Education Information -->
                        <hr>
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-graduation-cap mr-2"></i>Education Information
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="educational_attainment" class="form-label">Educational Attainment <span class="text-danger">*</span></label>
                                <select class="form-control @error('educational_attainment') is-invalid @enderror" 
                                        id="educational_attainment" name="educational_attainment" required>
                                    <option value="">Select educational attainment</option>
                                    <option value="No Formal Education" {{ old('educational_attainment', session('registration.step1.educational_attainment')) == 'No Formal Education' ? 'selected' : '' }}>No Formal Education</option>
                                    <option value="Elementary Undergraduate" {{ old('educational_attainment', session('registration.step1.educational_attainment')) == 'Elementary Undergraduate' ? 'selected' : '' }}>Elementary Undergraduate</option>
                                    <option value="Elementary Graduate" {{ old('educational_attainment', session('registration.step1.educational_attainment')) == 'Elementary Graduate' ? 'selected' : '' }}>Elementary Graduate</option>
                                    <option value="High School Undergraduate" {{ old('educational_attainment', session('registration.step1.educational_attainment')) == 'High School Undergraduate' ? 'selected' : '' }}>High School Undergraduate</option>
                                    <option value="High School Graduate" {{ old('educational_attainment', session('registration.step1.educational_attainment')) == 'High School Graduate' ? 'selected' : '' }}>High School Graduate</option>
                                    <option value="Vocational/Technical Graduate" {{ old('educational_attainment', session('registration.step1.educational_attainment')) == 'Vocational/Technical Graduate' ? 'selected' : '' }}>Vocational/Technical Graduate</option>
                                    <option value="College Undergraduate" {{ old('educational_attainment', session('registration.step1.educational_attainment')) == 'College Undergraduate' ? 'selected' : '' }}>College Undergraduate</option>
                                    <option value="College Graduate" {{ old('educational_attainment', session('registration.step1.educational_attainment')) == 'College Graduate' ? 'selected' : '' }}>College Graduate</option>
                                    <option value="Post Graduate" {{ old('educational_attainment', session('registration.step1.educational_attainment')) == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                                </select>
                                @error('educational_attainment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="education_status" class="form-label">Education Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('education_status') is-invalid @enderror" 
                                        id="education_status" name="education_status" required>
                                    <option value="">Select education status</option>
                                    <option value="Studying" {{ old('education_status', session('registration.step1.education_status')) == 'Studying' ? 'selected' : '' }}>Studying</option>
                                    <option value="Graduated" {{ old('education_status', session('registration.step1.education_status')) == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                                    <option value="Stopped Schooling" {{ old('education_status', session('registration.step1.education_status')) == 'Stopped Schooling' ? 'selected' : '' }}>Stopped Schooling</option>
                                    <option value="Not Applicable" {{ old('education_status', session('registration.step1.education_status')) == 'Not Applicable' ? 'selected' : '' }}>Not Applicable</option>
                                </select>
                                @error('education_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <hr>
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-info-circle mr-2"></i>Additional Information
                        </h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="religion" class="form-label">Religion</label>
                                <input type="text" class="form-control @error('religion') is-invalid @enderror" 
                                       id="religion" name="religion" 
                                       value="{{ old('religion', session('registration.step1.religion')) }}" 
                                       placeholder="Enter religion (optional)">
                                @error('religion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="profession_occupation" class="form-label">Profession/Occupation</label>
                                <input type="text" class="form-control @error('profession_occupation') is-invalid @enderror" 
                                       id="profession_occupation" name="profession_occupation" 
                                       value="{{ old('profession_occupation', session('registration.step1.profession_occupation')) }}" 
                                       placeholder="Enter profession/occupation (optional)">
                                @error('profession_occupation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('admin.residents.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-primary">
                                Continue to Step 2 <i class="fas fa-arrow-right ml-2"></i>
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
    console.log('Step 1 inline script loaded');
    
    // Handle citizenship type change
    $('#citizenship_type').on('change', function() {
        const citizenshipType = $(this).val();
        const countryField = $('#citizenship_country');
        const requiredIndicator = $('.citizenship-country-required');
        
        if (citizenshipType === 'DUAL' || citizenshipType === 'FOREIGN' || citizenshipType === 'NATURALIZED') {
            // Show required indicator and make field required
            requiredIndicator.show();
            countryField.prop('required', true);
            
            // Update placeholder based on type
            if (citizenshipType === 'DUAL') {
                countryField.attr('placeholder', 'Enter other country (for dual citizens)');
            } else if (citizenshipType === 'FOREIGN') {
                countryField.attr('placeholder', 'Enter country of citizenship');
            } else if (citizenshipType === 'NATURALIZED') {
                countryField.attr('placeholder', 'Enter country of origin');
            }
        } else {
            // Hide required indicator and remove required attribute
            requiredIndicator.hide();
            countryField.prop('required', false);
            countryField.val(''); // Clear the field
            countryField.attr('placeholder', 'Enter country (for dual/foreign citizens)');
        }
    });
    
    // Trigger change event on page load to handle pre-selected values
    $('#citizenship_type').trigger('change');
    
    // Form validation enhancement
    $('#step1Form').on('submit', function(e) {
        const citizenshipType = $('#citizenship_type').val();
        const countryField = $('#citizenship_country').val().trim();
        
        // Validate citizenship country field
        if ((citizenshipType === 'DUAL' || citizenshipType === 'FOREIGN' || citizenshipType === 'NATURALIZED') && !countryField) {
            e.preventDefault();
            alert('Please enter the country for the selected citizenship type.');
            $('#citizenship_country').focus();
            return false;
        }
        
        return true;
    });

    // Calculate age when birthdate is entered
    $('#birthdate').on('change', function() {
        var birthdate = new Date($(this).val());
        var today = new Date();
        var age = today.getFullYear() - birthdate.getFullYear();
        var monthDiff = today.getMonth() - birthdate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
            age--;
        }
        
        // Show age info
        if (age >= 0) {
            var ageText = age + ' years old';
            if (age >= 60) {
                ageText += ' <span class="badge badge-warning">Senior Citizen</span>';
            }
            
            // Add age display if it doesn't exist
            if ($('#age-display').length === 0) {
                $('#birthdate').after('<small id="age-display" class="form-text text-muted mt-1"></small>');
            }
            $('#age-display').html('Age: ' + ageText);
        }
    });
    
    // Trigger age calculation on page load if birthdate is already filled
    if ($('#birthdate').val()) {
        $('#birthdate').trigger('change');
    }
});
</script>
@endsection
