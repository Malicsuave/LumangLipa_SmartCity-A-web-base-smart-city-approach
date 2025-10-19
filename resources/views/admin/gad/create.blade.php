@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.gad.index') }}">Gender & Development</a></li>
<li class="breadcrumb-item active" aria-current="page">Create New</li>
@endsection

@section('page-title', 'Create GAD Record')
@section('page-subtitle', 'Add a new Gender and Development record')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="fe fe-plus-circle fe-16 mr-2"></i>New GAD Record</h4>
                        <p class="text-muted mb-0">Enter details for the new GAD record</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.gad.index') }}" class="btn btn-outline-secondary">
                            <i class="fe fe-x fe-16 mr-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.gad.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="resident_id" class="form-label">Resident <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fe fe-user"></i></span>
                                    </div>
                                    <select name="resident_id" id="resident_id" class="form-select custom-select @error('resident_id') is-invalid @enderror" required>
                                        <option value="">Select Resident</option>
                                        @foreach($residents as $id => $name)
                                            <option value="{{ $id }}" {{ old('resident_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('resident_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="gender_identity" class="form-label">Gender Identity <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fe fe-users"></i></span>
                                    </div>
                                    <select name="gender_identity" id="gender_identity" class="form-select custom-select @error('gender_identity') is-invalid @enderror" required>
                                        <option value="">Select Gender Identity</option>
                                        <option value="Male" {{ old('gender_identity') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender_identity') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Non-binary" {{ old('gender_identity') == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                                        <option value="Transgender" {{ old('gender_identity') == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                                        <option value="Other" {{ old('gender_identity') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender_identity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3" id="gender-details-row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="gender_details" class="form-label">Gender Details (Optional)</label>
                                <input type="text" name="gender_details" id="gender_details" class="form-control @error('gender_details') is-invalid @enderror" value="{{ old('gender_details') }}">
                                @error('gender_details')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Please provide additional details about gender identity if needed.</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card mb-3 border-left-primary">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 text-primary">
                                <i class="fe fe-check-square fe-16 mr-2"></i>Program Enrollment
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="form-label">Programs Enrolled</label>
                                    <div class="row">
                                        @foreach($programTypes as $program)
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="programs_enrolled[]" value="{{ $program }}" id="program_{{ $loop->index }}" 
                                                        {{ is_array(old('programs_enrolled')) && in_array($program, old('programs_enrolled')) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="program_{{ $loop->index }}">{{ $program }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('programs_enrolled')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="enrollment_date" class="form-label">Enrollment Date</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fe fe-calendar"></i></span>
                                            </div>
                                            <input type="date" name="enrollment_date" id="enrollment_date" class="form-control @error('enrollment_date') is-invalid @enderror" value="{{ old('enrollment_date') }}">
                                            @error('enrollment_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="program_end_date" class="form-label">Program End Date</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fe fe-calendar"></i></span>
                                            </div>
                                            <input type="date" name="program_end_date" id="program_end_date" class="form-control @error('program_end_date') is-invalid @enderror" value="{{ old('program_end_date') }}">
                                            @error('program_end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="program_status" class="form-label">Program Status</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fe fe-activity"></i></span>
                                            </div>
                                            <select name="program_status" id="program_status" class="form-select custom-select @error('program_status') is-invalid @enderror">
                                                <option value="">Select Status</option>
                                                <option value="Active" {{ old('program_status') == 'Active' ? 'selected' : '' }}>Active</option>
                                                <option value="Completed" {{ old('program_status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="On Hold" {{ old('program_status') == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                                                <option value="Discontinued" {{ old('program_status') == 'Discontinued' ? 'selected' : '' }}>Discontinued</option>
                                            </select>
                                            @error('program_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pregnancy Information Section -->
                    <div class="card mb-3 border-left-warning">
                        <div class="card-header bg-light">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input mr-2" type="checkbox" value="1" id="is_pregnant_toggle" name="is_pregnant" {{ old('is_pregnant') ? 'checked' : '' }}>
                                <label class="form-check-label mb-0 fw-bold text-warning" for="is_pregnant_toggle">
                                    <i class="fe fe-heart fe-16 mr-2"></i>Pregnancy Information
                                </label>
                            </div>
                        </div>
                        <div class="card-body pregnancy-section" {{ old('is_pregnant') ? '' : 'style="display: none;"' }}>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="due_date" class="form-label">Due Date</label>
                                        <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date') }}">
                                        @error('due_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" value="1" id="is_lactating" name="is_lactating" {{ old('is_lactating') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_lactating">
                                            Is Lactating
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="needs_maternity_support" name="needs_maternity_support" {{ old('needs_maternity_support') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="needs_maternity_support">
                                            Needs Maternity Support
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Solo Parent Section -->
                    <div class="card mb-3 border-left-success">
                        <div class="card-header bg-light">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input mr-2" type="checkbox" value="1" id="is_solo_parent_toggle" name="is_solo_parent" {{ old('is_solo_parent') ? 'checked' : '' }}>
                                <label class="form-check-label mb-0 fw-bold text-success" for="is_solo_parent_toggle">
                                    <i class="fe fe-user fe-16 mr-2"></i>Solo Parent Information
                                </label>
                            </div>
                        </div>
                        <div class="card-body solo-parent-section" {{ old('is_solo_parent') ? '' : 'style="display: none;"' }}>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="solo_parent_id" class="form-label">Solo Parent ID Number</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fe fe-credit-card"></i></span>
                                            </div>
                                            <input type="text" name="solo_parent_id" id="solo_parent_id" class="form-control @error('solo_parent_id') is-invalid @enderror" value="{{ old('solo_parent_id') }}">
                                            @error('solo_parent_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="solo_parent_id_issued" class="form-label">ID Issuance Date</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fe fe-calendar"></i></span>
                                            </div>
                                            <input type="date" name="solo_parent_id_issued" id="solo_parent_id_issued" class="form-control @error('solo_parent_id_issued') is-invalid @enderror" value="{{ old('solo_parent_id_issued') }}">
                                            @error('solo_parent_id_issued')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="solo_parent_id_expiry" class="form-label">ID Expiry Date</label>
                                        <input type="date" name="solo_parent_id_expiry" id="solo_parent_id_expiry" class="form-control @error('solo_parent_id_expiry') is-invalid @enderror" value="{{ old('solo_parent_id_expiry') }}">
                                        @error('solo_parent_id_expiry')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="solo_parent_details" class="form-label">Solo Parent Details</label>
                                        <textarea name="solo_parent_details" id="solo_parent_details" rows="3" class="form-control @error('solo_parent_details') is-invalid @enderror">{{ old('solo_parent_details') }}</textarea>
                                        @error('solo_parent_details')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- VAW Case Section -->
                    <div class="card mb-3 border-left-danger">
                        <div class="card-header bg-light">
                            <div class="form-check d-flex align-items-center">
                                <input class="form-check-input mr-2" type="checkbox" value="1" id="is_vaw_case_toggle" name="is_vaw_case" {{ old('is_vaw_case') ? 'checked' : '' }}>
                                <label class="form-check-label mb-0 fw-bold text-danger" for="is_vaw_case_toggle">
                                    <i class="fe fe-alert-triangle fe-16 mr-2"></i>Violence Against Women (VAW) Case
                                </label>
                            </div>
                        </div>
                        <div class="card-body vaw-case-section" {{ old('is_vaw_case') ? '' : 'style="display: none;"' }}>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vaw_case_number" class="form-label">Case Number</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fe fe-hash"></i></span>
                                            </div>
                                            <input type="text" name="vaw_case_number" id="vaw_case_number" class="form-control @error('vaw_case_number') is-invalid @enderror" value="{{ old('vaw_case_number') }}">
                                            @error('vaw_case_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vaw_report_date" class="form-label">Report Date</label>
                                        <input type="date" name="vaw_report_date" id="vaw_report_date" class="form-control @error('vaw_report_date') is-invalid @enderror" value="{{ old('vaw_report_date') }}">
                                        @error('vaw_report_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vaw_case_status" class="form-label">Case Status</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fe fe-activity"></i></span>
                                            </div>
                                            <select name="vaw_case_status" id="vaw_case_status" class="form-select custom-select @error('vaw_case_status') is-invalid @enderror">
                                                <option value="">Select Status</option>
                                                <option value="Pending" {{ old('vaw_case_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="Ongoing" {{ old('vaw_case_status') == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                                <option value="Resolved" {{ old('vaw_case_status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                                <option value="Closed" {{ old('vaw_case_status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                                            </select>
                                            @error('vaw_case_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="vaw_case_details" class="form-label">Case Details</label>
                                        <textarea name="vaw_case_details" id="vaw_case_details" rows="3" class="form-control @error('vaw_case_details') is-invalid @enderror">{{ old('vaw_case_details') }}</textarea>
                                        @error('vaw_case_details')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Notes -->
                    <div class="card mb-3 border-left-info">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 text-info">
                                <i class="fe fe-file-text fe-16 mr-2"></i>Additional Notes
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror" placeholder="Enter any additional information about this GAD record...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="text-right mt-4">
                        <a href="{{ route('admin.gad.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            <i class="fe fe-save fe-16 mr-2"></i>Save GAD Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form and field variables
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submit-btn');
    const residentField = document.getElementById('resident_id');
    const genderIdentityField = document.getElementById('gender_identity');
    const genderDetailsField = document.getElementById('gender_details');
    const genderDetailsRow = document.getElementById('gender-details-row');
    const enrollmentDateField = document.getElementById('enrollment_date');
    const programEndDateField = document.getElementById('program_end_date');
    const isPregnantToggle = document.getElementById('is_pregnant_toggle');
    const dueDateField = document.getElementById('due_date');
    const pregnancySection = document.querySelector('.pregnancy-section');
    const isSoloParentToggle = document.getElementById('is_solo_parent_toggle');
    const soloParentSection = document.querySelector('.solo-parent-section');
    const soloParentIdField = document.getElementById('solo_parent_id');
    const soloParentIdIssuedField = document.getElementById('solo_parent_id_issued');
    const soloParentIdExpiryField = document.getElementById('solo_parent_id_expiry');

    // Validation error messages container
    const createErrorContainer = (field) => {
        // Remove existing error message if any
        const existingError = field.parentNode.querySelector('.validation-error');
        if (existingError) {
            existingError.remove();
        }
        
        // Create and insert error message element
        const errorDiv = document.createElement('div');
        errorDiv.className = 'validation-error text-danger small mt-1';
        
        // If field is inside input group, append after the input group
        const parentNode = field.closest('.input-group') || field.parentNode;
        parentNode.parentNode.insertBefore(errorDiv, parentNode.nextSibling);
        
        return errorDiv;
    };
    
    // Show validation error
    const showError = (field, message) => {
        field.classList.add('is-invalid');
        const errorDiv = createErrorContainer(field);
        errorDiv.textContent = message;
    };
    
    // Clear validation error
    const clearError = (field) => {
        field.classList.remove('is-invalid');
        const errorDiv = field.parentNode.querySelector('.validation-error') || 
            field.closest('.input-group')?.parentNode.querySelector('.validation-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    };
    
    // Validate form fields in real time
    
    // Resident validation
    if (residentField) {
        residentField.addEventListener('change', () => {
            if (!residentField.value) {
                showError(residentField, 'Please select a resident.');
            } else {
                clearError(residentField);
            }
        });
    }
    
    // Gender Identity validation
    genderIdentityField.addEventListener('change', () => {
        if (!genderIdentityField.value) {
            showError(genderIdentityField, 'Please select a gender identity.');
        } else {
            clearError(genderIdentityField);
            
            // Show/hide gender details field based on selection
            if (genderIdentityField.value === 'Other') {
                genderDetailsRow.style.display = 'flex';
                genderDetailsField.setAttribute('required', 'required');
            } else {
                genderDetailsRow.style.display = 'none';
                genderDetailsField.removeAttribute('required');
                clearError(genderDetailsField);
            }
        }
    });
    
    // Gender Details validation
    genderDetailsField.addEventListener('input', () => {
        if (genderIdentityField.value === 'Other') {
            // Only validate when the field is required
            if (!genderDetailsField.value.trim()) {
                showError(genderDetailsField, 'Please provide gender details.');
            } else if (!/^[a-zA-Z\s\.\-\']+$/.test(genderDetailsField.value)) {
                showError(genderDetailsField, 'Gender details can only contain letters, spaces, dots, hyphens, and apostrophes.');
            } else {
                clearError(genderDetailsField);
            }
        }
    });
    
    // Date validations
    const validateDates = () => {
        const currentDate = new Date();
        const maxPastDate = new Date();
        maxPastDate.setFullYear(currentDate.getFullYear() - 120);
        
        // Enrollment date validation
        if (enrollmentDateField.value) {
            const enrollmentDate = new Date(enrollmentDateField.value);
            
            if (enrollmentDate > currentDate) {
                showError(enrollmentDateField, 'Enrollment date cannot be in the future.');
            } else if (enrollmentDate < maxPastDate) {
                showError(enrollmentDateField, 'Enrollment date cannot be more than 120 years ago.');
            } else {
                clearError(enrollmentDateField);
            }
            
            // Program end date validation
            if (programEndDateField.value) {
                const endDate = new Date(programEndDateField.value);
                
                if (endDate < enrollmentDate) {
                    showError(programEndDateField, 'Program end date must be after enrollment date.');
                } else {
                    clearError(programEndDateField);
                }
            }
        } else {
            clearError(enrollmentDateField);
        }
    };
    
    enrollmentDateField.addEventListener('change', validateDates);
    programEndDateField.addEventListener('change', validateDates);
    
    // Pregnancy section validations
    isPregnantToggle.addEventListener('change', () => {
        pregnancySection.style.display = isPregnantToggle.checked ? 'block' : 'none';
        
        if (isPregnantToggle.checked) {
            dueDateField.setAttribute('required', 'required');
        } else {
            dueDateField.removeAttribute('required');
            clearError(dueDateField);
        }
    });
    
    dueDateField.addEventListener('change', () => {
        if (isPregnantToggle.checked) {
            const currentDate = new Date();
            currentDate.setHours(0, 0, 0, 0);
            const dueDate = new Date(dueDateField.value);
            
            if (!dueDateField.value) {
                showError(dueDateField, 'Due date is required when pregnancy is indicated.');
            } else if (dueDate < currentDate) {
                showError(dueDateField, 'Due date must be today or a future date.');
            } else {
                clearError(dueDateField);
            }
        }
    });
    
    // Solo parent section validations
    isSoloParentToggle.addEventListener('change', () => {
        soloParentSection.style.display = isSoloParentToggle.checked ? 'block' : 'none';
        
        if (isSoloParentToggle.checked) {
            soloParentIdField.setAttribute('required', 'required');
            soloParentIdIssuedField.setAttribute('required', 'required');
            if (soloParentIdExpiryField) {
                soloParentIdExpiryField.setAttribute('required', 'required');
            }
        } else {
            soloParentIdField.removeAttribute('required');
            soloParentIdIssuedField.removeAttribute('required');
            if (soloParentIdExpiryField) {
                soloParentIdExpiryField.removeAttribute('required');
            }
            clearError(soloParentIdField);
            clearError(soloParentIdIssuedField);
            if (soloParentIdExpiryField) {
                clearError(soloParentIdExpiryField);
            }
        }
    });
    
    soloParentIdField.addEventListener('input', () => {
        if (isSoloParentToggle.checked) {
            if (!soloParentIdField.value.trim()) {
                showError(soloParentIdField, 'Solo Parent ID is required when indicating solo parent status.');
            } else if (!/^[a-zA-Z0-9\-]+$/.test(soloParentIdField.value)) {
                showError(soloParentIdField, 'Solo Parent ID can only contain letters, numbers, and hyphens.');
            } else {
                clearError(soloParentIdField);
            }
        }
    });
    
    soloParentIdIssuedField.addEventListener('change', () => {
        if (isSoloParentToggle.checked) {
            const currentDate = new Date();
            currentDate.setHours(0, 0, 0, 0);
            const issuedDate = new Date(soloParentIdIssuedField.value);
            
            if (!soloParentIdIssuedField.value) {
                showError(soloParentIdIssuedField, 'ID issuance date is required for solo parents.');
            } else if (issuedDate > currentDate) {
                showError(soloParentIdIssuedField, 'ID issuance date cannot be in the future.');
            } else {
                clearError(soloParentIdIssuedField);
            }
        }
    });
    
    if (soloParentIdExpiryField) {
        soloParentIdExpiryField.addEventListener('change', () => {
            if (isSoloParentToggle.checked && soloParentIdIssuedField.value) {
                const issuedDate = new Date(soloParentIdIssuedField.value);
                const expiryDate = new Date(soloParentIdExpiryField.value);
                
                if (!soloParentIdExpiryField.value) {
                    showError(soloParentIdExpiryField, 'ID expiry date is required for solo parents.');
                } else if (expiryDate <= issuedDate) {
                    showError(soloParentIdExpiryField, 'ID expiry date must be after the issuance date.');
                } else {
                    clearError(soloParentIdExpiryField);
                }
            }
        });
    }

    // Form submission validation
    form.addEventListener('submit', function(e) {
        let hasErrors = false;
        
        // Basic required field validations
        if (!residentField.value) {
            showError(residentField, 'Please select a resident.');
            hasErrors = true;
        }
        
        if (!genderIdentityField.value) {
            showError(genderIdentityField, 'Please select a gender identity.');
            hasErrors = true;
        }
        
        if (genderIdentityField.value === 'Other' && !genderDetailsField.value.trim()) {
            showError(genderDetailsField, 'Please provide gender details.');
            hasErrors = true;
        }
        
        // Validate dates
        validateDates();
        
        // Pregnancy validation
        if (isPregnantToggle.checked && !dueDateField.value) {
            showError(dueDateField, 'Due date is required when pregnancy is indicated.');
            hasErrors = true;
        }
        
        // Solo parent validation
        if (isSoloParentToggle.checked) {
            if (!soloParentIdField.value.trim()) {
                showError(soloParentIdField, 'Solo Parent ID is required.');
                hasErrors = true;
            }
            
            if (!soloParentIdIssuedField.value) {
                showError(soloParentIdIssuedField, 'ID issuance date is required.');
                hasErrors = true;
            }
            
            if (soloParentIdExpiryField && !soloParentIdExpiryField.value) {
                showError(soloParentIdExpiryField, 'ID expiry date is required.');
                hasErrors = true;
            }
        }
        
        if (hasErrors) {
            e.preventDefault();
            // Scroll to the first error
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            // Show alert
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show';
            alertDiv.innerHTML = `
                <strong>Error!</strong> Please correct the errors in the form before submitting.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            `;
            
            const cardHeader = document.querySelector('.card-header');
            const existingAlert = document.querySelector('.alert');
            
            if (existingAlert) {
                existingAlert.remove();
            }
            
            cardHeader.insertAdjacentElement('afterend', alertDiv);
        }
    });
    
    // Initialize toggle states
    if (genderIdentityField.value === 'Other') {
        genderDetailsRow.style.display = 'flex';
    } else {
        genderDetailsRow.style.display = 'none';
    }
    
    pregnancySection.style.display = isPregnantToggle.checked ? 'block' : 'none';
    soloParentSection.style.display = isSoloParentToggle.checked ? 'block' : 'none';
});
</script>
@endsection