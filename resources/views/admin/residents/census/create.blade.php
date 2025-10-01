@extends('layouts.admin.master')

@section('page-header', 'New Household Census Record')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.residents.census-data') }}">Census Data</a></li>
    <li class="breadcrumb-item active">New Household Census</li>
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
                    <i class="fas fa-home mr-2"></i>
                    Household Census Registration
                </h3>
            </div>
            <form action="{{ route('admin.residents.census.store') }}" method="POST" id="censusForm">
                @csrf
                <div class="card-body registration-form">

                    <!-- Primary Person in Household -->
                    <div class="card border-primary mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-user mr-2"></i>Primary Person in Household
                            </h5>
                            <small class="text-muted">Main person for this household record</small>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="primary_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('primary_name') is-invalid @enderror" 
                                               id="primary_name" name="primary_name" 
                                               value="{{ old('primary_name') }}" 
                                               placeholder="Enter full name" required>
                                        @error('primary_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="primary_birthday" class="form-label">Birthday <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('primary_birthday') is-invalid @enderror" 
                                               id="primary_birthday" name="primary_birthday" 
                                               value="{{ old('primary_birthday') }}" 
                                               max="{{ date('Y-m-d') }}" required>
                                        @error('primary_birthday')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="primary_gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                        <select class="form-control @error('primary_gender') is-invalid @enderror" 
                                                id="primary_gender" name="primary_gender" required>
                                            <option value="">Select gender</option>
                                            <option value="Male" {{ old('primary_gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('primary_gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Non-binary" {{ old('primary_gender') == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                                            <option value="Transgender" {{ old('primary_gender') == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                                            <option value="Other" {{ old('primary_gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('primary_gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="primary_phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control @error('primary_phone') is-invalid @enderror" 
                                               id="primary_phone" name="primary_phone" 
                                               value="{{ old('primary_phone') }}" 
                                               placeholder="09123456789" maxlength="11" pattern="[0-9]{11}">
                                        @error('primary_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="primary_work" class="form-label">Work/Occupation</label>
                                        <input type="text" class="form-control @error('primary_work') is-invalid @enderror" 
                                               id="primary_work" name="primary_work" 
                                               value="{{ old('primary_work') }}" 
                                               placeholder="Enter occupation">
                                        @error('primary_work')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="primary_allergies" class="form-label">Allergies</label>
                                        <input type="text" class="form-control @error('primary_allergies') is-invalid @enderror" 
                                               id="primary_allergies" name="primary_allergies" 
                                               value="{{ old('primary_allergies') }}" 
                                               placeholder="Food, medicine, etc.">
                                        @error('primary_allergies')
                                            <div class="invalid-feedback">{{ $message }}</div>
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
                                                  placeholder="Any ongoing medical conditions">{{ old('primary_medical_condition') }}</textarea>
                                        @error('primary_medical_condition')
                                            <div class="invalid-feedback">{{ $message }}</div>
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
                                <i class="fas fa-users mr-2"></i>Secondary Person in Household
                            </h5>
                            <small class="text-muted">Optional - Spouse, partner, or other significant household member</small>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="secondary_name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control @error('secondary_name') is-invalid @enderror" 
                                               id="secondary_name" name="secondary_name" 
                                               value="{{ old('secondary_name') }}" 
                                               placeholder="Enter full name (optional)">
                                        @error('secondary_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="secondary_birthday" class="form-label">Birthday</label>
                                        <input type="date" class="form-control @error('secondary_birthday') is-invalid @enderror" 
                                               id="secondary_birthday" name="secondary_birthday" 
                                               value="{{ old('secondary_birthday') }}" 
                                               max="{{ date('Y-m-d') }}">
                                        @error('secondary_birthday')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="secondary_gender" class="form-label">Gender</label>
                                        <select class="form-control @error('secondary_gender') is-invalid @enderror" 
                                                id="secondary_gender" name="secondary_gender">
                                            <option value="">Select gender</option>
                                            <option value="Male" {{ old('secondary_gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('secondary_gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Non-binary" {{ old('secondary_gender') == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                                            <option value="Transgender" {{ old('secondary_gender') == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                                            <option value="Other" {{ old('secondary_gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('secondary_gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="secondary_phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control @error('secondary_phone') is-invalid @enderror" 
                                               id="secondary_phone" name="secondary_phone" 
                                               value="{{ old('secondary_phone') }}" 
                                               placeholder="09123456789" maxlength="11" pattern="[0-9]{11}">
                                        @error('secondary_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="secondary_work" class="form-label">Work/Occupation</label>
                                        <input type="text" class="form-control @error('secondary_work') is-invalid @enderror" 
                                               id="secondary_work" name="secondary_work" 
                                               value="{{ old('secondary_work') }}" 
                                               placeholder="Enter occupation">
                                        @error('secondary_work')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="secondary_allergies" class="form-label">Allergies</label>
                                        <input type="text" class="form-control @error('secondary_allergies') is-invalid @enderror" 
                                               id="secondary_allergies" name="secondary_allergies" 
                                               value="{{ old('secondary_allergies') }}" 
                                               placeholder="Food, medicine, etc.">
                                        @error('secondary_allergies')
                                            <div class="invalid-feedback">{{ $message }}</div>
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
                                                  placeholder="Any ongoing medical conditions">{{ old('secondary_medical_condition') }}</textarea>
                                        @error('secondary_medical_condition')
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
                                <i class="fas fa-phone mr-2"></i>Emergency Contact
                            </h5>
                            <small class="text-muted">Person to contact in case of emergency</small>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="emergency_contact_name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                               id="emergency_contact_name" name="emergency_contact_name" 
                                               value="{{ old('emergency_contact_name') }}" 
                                               placeholder="Enter emergency contact name">
                                        @error('emergency_contact_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="emergency_relationship" class="form-label">Relationship</label>
                                        <input type="text" class="form-control @error('emergency_relationship') is-invalid @enderror" 
                                               id="emergency_relationship" name="emergency_relationship" 
                                               value="{{ old('emergency_relationship') }}" 
                                               placeholder="e.g., Son, Daughter, Brother">
                                        @error('emergency_relationship')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="emergency_work" class="form-label">Work/Occupation</label>
                                        <input type="text" class="form-control @error('emergency_work') is-invalid @enderror" 
                                               id="emergency_work" name="emergency_work" 
                                               value="{{ old('emergency_work') }}" 
                                               placeholder="Enter occupation">
                                        @error('emergency_work')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="emergency_phone" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control @error('emergency_phone') is-invalid @enderror" 
                                               id="emergency_phone" name="emergency_phone" 
                                               value="{{ old('emergency_phone') }}" 
                                               placeholder="09123456789" maxlength="11" pattern="[0-9]{11}">
                                        @error('emergency_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('admin.residents.census-data') }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>Save Household Census
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
<script>
$(document).ready(function() {
    // Form validation
    $('#censusForm').on('submit', function(e) {
        let isValid = true;
        let errorMessage = '';
        
        // Validate required fields
        const requiredFields = [
            { field: '#primary_name', name: 'Primary Person Name' },
            { field: '#primary_birthday', name: 'Primary Person Birthday' },
            { field: '#primary_gender', name: 'Primary Person Gender' }
        ];
        
        requiredFields.forEach(function(item) {
            const fieldValue = $(item.field).val().trim();
            if (!fieldValue) {
                isValid = false;
                errorMessage += `${item.name} is required.\n`;
                $(item.field).addClass('is-invalid');
            } else {
                $(item.field).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please correct the following errors:\n\n' + errorMessage);
            return false;
        }
        
        return true;
    });
    
    // Remove validation errors on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
    
    // Auto-format phone numbers
    $('input[type="tel"]').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 11) {
            value = value.substring(0, 11);
        }
        $(this).val(value);
    });
});
</script>
@endpush

@section('scripts')
<script>
$(document).ready(function() {
    // Show success message if redirected with success
    @if(session('success'))
        showSuccess('{{ session('success') }}');
    @endif
    
    // Show error message if redirected with error
    @if(session('error'))
        showError('{{ session('error') }}');
    @endif
});
</script>
@endsection
