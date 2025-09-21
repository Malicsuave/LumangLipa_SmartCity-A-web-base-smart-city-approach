@extends('layouts.admin.master')

@section('page-header', 'New Resident Registration')

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
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title text-white">
                    <i class="fas fa-user mr-2"></i>
                    Personal Information
                </h3>
                <div class="card-tools">
                    <span class="badge badge-light">Step 1 of 3</span>
                </div>
            </div>
            <form action="{{ route('admin.residents.create.step1.store') }}" method="POST" id="step1Form">
                @csrf
                <div class="card-body registration-form">

                    @csrf
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
