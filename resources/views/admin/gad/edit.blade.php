@extends('layouts.admin.master')

@section('title', 'Edit GAD Record')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h3 page-title">Edit GAD Record</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.gad.show', $gad->id) }}" class="btn btn-primary mr-2">
                        <i class="fe fe-arrow-left mr-2"></i> Back to Details
                    </a>
                </div>
            </div>
            
            @if(session('success') || session('gad_update_success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fe fe-check-circle fe-16 mr-2"></i> 
                {{ session('success') ?? 'GAD record updated successfully!' }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fe fe-alert-circle fe-16 mr-2"></i>
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            
            <form action="{{ route('admin.gad.update', $gad->id) }}" method="POST" id="gadEditForm">
                @csrf
                @method('PUT')
                
                <!-- Basic Information Card -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Basic Information</h6>
                        <small class="text-muted mt-1">Fields marked with <span class="text-danger">*</span> are required</small>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="resident_name" class="form-label">Resident</label>
                                <input type="text" class="form-control" id="resident_name" value="{{ $gad->resident->full_name }} (ID: {{ $gad->resident->barangay_id }})" disabled readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="gender_identity" class="form-label">Gender Identity <span class="text-danger">*</span></label>
                                <select name="gender_identity" id="gender_identity" class="form-control @error('gender_identity') is-invalid @enderror" required>
                                    <option value="">Select Gender Identity</option>
                                    <option value="Male" {{ old('gender_identity', $gad->gender_identity) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender_identity', $gad->gender_identity) == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Non-binary" {{ old('gender_identity', $gad->gender_identity) == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                                    <option value="Transgender" {{ old('gender_identity', $gad->gender_identity) == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                                    <option value="Other" {{ old('gender_identity', $gad->gender_identity) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender_identity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3" id="gender-details-row" style="{{ old('gender_identity', $gad->gender_identity) == 'Other' ? '' : 'display: none;' }}">
                            <div class="col-md-6">
                                <label for="gender_details" class="form-label">Gender Details</label>
                                <input type="text" name="gender_details" id="gender_details" class="form-control @error('gender_details') is-invalid @enderror" value="{{ old('gender_details', $gad->gender_details) }}">
                                @error('gender_details')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Program Enrollment Card -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Program Enrollment</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Programs Enrolled</label>
                                <div class="row">
                                    @foreach($programTypes as $program)
                                        <div class="col-md-6 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" name="programs_enrolled[]" value="{{ $program }}" id="program_{{ $loop->index }}" 
                                                    {{ (is_array(old('programs_enrolled')) && in_array($program, old('programs_enrolled'))) || 
                                                       (old('programs_enrolled') === null && is_array($gad->programs_enrolled) && in_array($program, $gad->programs_enrolled)) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="program_{{ $loop->index }}">{{ $program }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('programs_enrolled')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="enrollment_date" class="form-label">Enrollment Date</label>
                                <input type="date" name="enrollment_date" id="enrollment_date" class="form-control @error('enrollment_date') is-invalid @enderror" value="{{ old('enrollment_date', optional($gad->enrollment_date)->format('Y-m-d')) }}">
                                @error('enrollment_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="program_end_date" class="form-label">Program End Date</label>
                                <input type="date" name="program_end_date" id="program_end_date" class="form-control @error('program_end_date') is-invalid @enderror" value="{{ old('program_end_date', optional($gad->program_end_date)->format('Y-m-d')) }}">
                                @error('program_end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="program_status" class="form-label">Program Status</label>
                                <select name="program_status" id="program_status" class="form-control @error('program_status') is-invalid @enderror">
                                    <option value="">Select Status</option>
                                    <option value="Active" {{ old('program_status', $gad->program_status) == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Completed" {{ old('program_status', $gad->program_status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="On Hold" {{ old('program_status', $gad->program_status) == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                                    <option value="Discontinued" {{ old('program_status', $gad->program_status) == 'Discontinued' ? 'selected' : '' }}>Discontinued</option>
                                </select>
                                @error('program_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pregnancy Information Section -->
                <div class="card shadow mb-4">
                    <div class="card-header d-flex align-items-center">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_pregnant_toggle" name="is_pregnant" value="1" {{ old('is_pregnant', $gad->is_pregnant) ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-bold mb-0" for="is_pregnant_toggle">Pregnancy Information</label>
                        </div>
                    </div>
                    <div class="card-body pregnancy-section" {{ old('is_pregnant', $gad->is_pregnant) ? '' : 'style="display: none;"' }}>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date', optional($gad->due_date)->format('Y-m-d')) }}">
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="custom-control custom-checkbox mt-4">
                                    <input type="checkbox" class="custom-control-input" id="is_lactating" name="is_lactating" value="1" {{ old('is_lactating', $gad->is_lactating) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_lactating">Is Lactating</label>
                                </div>
                                <div class="custom-control custom-checkbox mt-2">
                                    <input type="checkbox" class="custom-control-input" id="needs_maternity_support" name="needs_maternity_support" value="1" {{ old('needs_maternity_support', $gad->needs_maternity_support) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="needs_maternity_support">Needs Maternity Support</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Solo Parent Section -->
                <div class="card shadow mb-4">
                    <div class="card-header d-flex align-items-center">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_solo_parent_toggle" name="is_solo_parent" value="1" {{ old('is_solo_parent', $gad->is_solo_parent) ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-bold mb-0" for="is_solo_parent_toggle">Solo Parent Information</label>
                        </div>
                    </div>
                    <div class="card-body solo-parent-section" {{ old('is_solo_parent', $gad->is_solo_parent) ? '' : 'style="display: none;"' }}>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="solo_parent_id" class="form-label">Solo Parent ID Number</label>
                                <input type="text" name="solo_parent_id" id="solo_parent_id" class="form-control @error('solo_parent_id') is-invalid @enderror" value="{{ old('solo_parent_id', $gad->solo_parent_id) }}">
                                @error('solo_parent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="solo_parent_id_issued" class="form-label">ID Issuance Date</label>
                                <input type="date" name="solo_parent_id_issued" id="solo_parent_id_issued" class="form-control @error('solo_parent_id_issued') is-invalid @enderror" value="{{ old('solo_parent_id_issued', optional($gad->solo_parent_id_issued)->format('Y-m-d')) }}">
                                @error('solo_parent_id_issued')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="solo_parent_id_expiry" class="form-label">ID Expiry Date</label>
                                <input type="date" name="solo_parent_id_expiry" id="solo_parent_id_expiry" class="form-control @error('solo_parent_id_expiry') is-invalid @enderror" value="{{ old('solo_parent_id_expiry', optional($gad->solo_parent_id_expiry)->format('Y-m-d')) }}">
                                @error('solo_parent_id_expiry')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="solo_parent_details" class="form-label">Solo Parent Details</label>
                                <textarea name="solo_parent_details" id="solo_parent_details" rows="3" class="form-control @error('solo_parent_details') is-invalid @enderror">{{ old('solo_parent_details', $gad->solo_parent_details) }}</textarea>
                                @error('solo_parent_details')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- VAW Case Section -->
                <div class="card shadow mb-4">
                    <div class="card-header d-flex align-items-center">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_vaw_case_toggle" name="is_vaw_case" value="1" {{ old('is_vaw_case', $gad->is_vaw_case) ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-bold mb-0" for="is_vaw_case_toggle">VAW Case Information</label>
                        </div>
                    </div>
                    <div class="card-body vaw-case-section" {{ old('is_vaw_case', $gad->is_vaw_case) ? '' : 'style="display: none;"' }}>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="vaw_case_number" class="form-label">Case Number</label>
                                <input type="text" name="vaw_case_number" id="vaw_case_number" class="form-control @error('vaw_case_number') is-invalid @enderror" value="{{ old('vaw_case_number', $gad->vaw_case_number) }}">
                                @error('vaw_case_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="vaw_report_date" class="form-label">Report Date</label>
                                <input type="date" name="vaw_report_date" id="vaw_report_date" class="form-control @error('vaw_report_date') is-invalid @enderror" value="{{ old('vaw_report_date', optional($gad->vaw_report_date)->format('Y-m-d')) }}">
                                @error('vaw_report_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="vaw_case_status" class="form-label">Case Status</label>
                                <select name="vaw_case_status" id="vaw_case_status" class="form-control @error('vaw_case_status') is-invalid @enderror">
                                    <option value="">Select Status</option>
                                    <option value="Pending" {{ old('vaw_case_status', $gad->vaw_case_status) == 'Pending' ? 'selected' : '' }}>Pending Investigation</option>
                                    <option value="Ongoing" {{ old('vaw_case_status', $gad->vaw_case_status) == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="Resolved" {{ old('vaw_case_status', $gad->vaw_case_status) == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                    <option value="Closed" {{ old('vaw_case_status', $gad->vaw_case_status) == 'Closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                                @error('vaw_case_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="vaw_case_details" class="form-label">Case Details</label>
                                <textarea name="vaw_case_details" id="vaw_case_details" rows="3" class="form-control @error('vaw_case_details') is-invalid @enderror">{{ old('vaw_case_details', $gad->vaw_case_details) }}</textarea>
                                @error('vaw_case_details')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Notes -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Additional Notes</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $gad->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="row mt-4 mb-4">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-body text-right">
                                <a href="{{ route('admin.gad.show', $gad->id) }}" class="btn btn-secondary mr-2">Cancel</a>
                                <button type="submit" class="btn btn-primary" id="submitButton">Update GAD Record</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Debug form submission
        const form = document.getElementById('gadEditForm');
        
        if (form) {
            console.log('GAD edit form found, attaching submission handler');
            
            form.addEventListener('submit', function() {
                // Don't prevent default form submission - allow the normal process
                
                // Show loading indicator
                const submitBtn = document.getElementById('submitButton');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
                }
                
                // Store in localStorage to check after page reloads
                localStorage.setItem('gadFormSubmitted', 'true');
                
                // Regular form submission continues...
                return true;
            });
            
            // Check if form was just submitted (page was reloaded)
            if (localStorage.getItem('gadFormSubmitted') === 'true') {
                console.log('Form was submitted, showing success message');
                
                // Create success message
                const successAlert = document.createElement('div');
                successAlert.className = 'alert alert-success alert-dismissible fade show';
                successAlert.innerHTML = `
                    <i class="fe fe-check-circle fe-16 mr-2"></i> GAD record updated successfully!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                `;
                
                // Insert at the top of the form
                const formContainer = document.querySelector('.row.justify-content-center .col-12');
                const headerSection = document.querySelector('.row.align-items-center.mb-4');
                formContainer.insertBefore(successAlert, headerSection.nextSibling);
                
                // Scroll to top
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                // Clear the localStorage flag
                localStorage.removeItem('gadFormSubmitted');
                
                // Auto dismiss after 5 seconds
                setTimeout(() => {
                    $(successAlert).alert('close');
                }, 5000);
            }
        }
        
        // Enable submit button
        const submitButton = document.getElementById('submitButton');
        if (submitButton) {
            submitButton.disabled = false;
        }
        
        // Add required field indicators
        function updateRequiredFields() {
            const requiredFields = [
                { field: 'gender_identity', always: true },
                { field: 'gender_details', condition: () => $('#gender_identity').val() === 'Other' },
                { field: 'due_date', condition: () => $('#is_pregnant_toggle').is(':checked') },
                { field: 'solo_parent_id', condition: () => $('#is_solo_parent_toggle').is(':checked') },
                { field: 'solo_parent_id_issued', condition: () => $('#is_solo_parent_toggle').is(':checked') },
                { field: 'solo_parent_id_expiry', condition: () => $('#is_solo_parent_toggle').is(':checked') },
                { field: 'vaw_case_number', condition: () => $('#is_vaw_case_toggle').is(':checked') },
                { field: 'vaw_report_date', condition: () => $('#is_vaw_case_toggle').is(':checked') }
            ];
            
            requiredFields.forEach(item => {
                const label = $(`label[for="${item.field}"]`);
                const asterisk = $('<span class="text-danger">*</span>');
                
                // Remove existing asterisks
                label.find('.text-danger').remove();
                
                // Add asterisk if required
                if (item.always || (item.condition && item.condition())) {
                    label.append(' ').append(asterisk);
                    $(`#${item.field}`).prop('required', true);
                } else {
                    $(`#${item.field}`).prop('required', false);
                }
            });
        }
        
        // Gender details visibility
        const genderIdentity = document.getElementById('gender_identity');
        const genderDetailsRow = document.getElementById('gender-details-row');
        
        genderIdentity.addEventListener('change', function() {
            genderDetailsRow.style.display = this.value === 'Other' ? 'block' : 'none';
            updateRequiredFields();
        });
        
        // Toggle sections
        const pregnancyToggle = document.getElementById('is_pregnant_toggle');
        const pregnancySection = document.querySelector('.pregnancy-section');
        
        pregnancyToggle.addEventListener('change', function() {
            pregnancySection.style.display = this.checked ? 'block' : 'none';
            updateRequiredFields();
        });
        
        const soloParentToggle = document.getElementById('is_solo_parent_toggle');
        const soloParentSection = document.querySelector('.solo-parent-section');
        
        soloParentToggle.addEventListener('change', function() {
            soloParentSection.style.display = this.checked ? 'block' : 'none';
            updateRequiredFields();
            
            // Clear validation messages when toggling off
            if (!this.checked) {
                clearValidationErrors(soloParentSection);
            }
        });
        
        const vawCaseToggle = document.getElementById('is_vaw_case_toggle');
        const vawCaseSection = document.querySelector('.vaw-case-section');
        
        vawCaseToggle.addEventListener('change', function() {
            vawCaseSection.style.display = this.checked ? 'block' : 'none';
            updateRequiredFields();
        });
        
        // Clear all validation errors within a container
        function clearValidationErrors(container) {
            if (!container) return;
            
            // Clear all invalid classes
            container.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            
            // Remove all feedback messages
            container.querySelectorAll('.invalid-feedback, .text-danger').forEach(msg => {
                msg.remove();
            });
            
            // Clear custom validity
            container.querySelectorAll('input, select, textarea').forEach(field => {
                field.setCustomValidity('');
            });
        }
        
        // Input sanitization and validation
        const textInputs = document.querySelectorAll('input[type="text"], textarea');
        textInputs.forEach(input => {
            input.addEventListener('input', function() {
                // Sanitize potentially dangerous HTML/script tags
                let sanitized = this.value.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
                sanitized = sanitized.replace(/</g, '&lt;').replace(/>/g, '&gt;');
                
                // If value was changed by sanitization, update and highlight
                if (sanitized !== this.value) {
                    this.value = sanitized;
                    this.style.backgroundColor = '#ffc';
                    setTimeout(() => {
                        this.style.backgroundColor = '';
                    }, 300);
                }
            });
        });
        
        // Input masking for specific fields
        const nameFields = document.querySelector('#gender_details');
        if (nameFields) {
            nameFields.addEventListener('input', function() {
                // Allow letters, numbers, spaces, dots, hyphens, and apostrophes
                this.value = this.value.replace(/[^a-zA-Z0-9\s.\-']/g, '');
            });
        }
        
        const idFields = document.querySelector('#solo_parent_id');
        if (idFields) {
            idFields.addEventListener('input', function() {
                // Allow only letters, numbers, and hyphens
                this.value = this.value.replace(/[^a-zA-Z0-9\-]/g, '');
            });
        }
        
        const caseNumberField = document.querySelector('#vaw_case_number');
        if (caseNumberField) {
            caseNumberField.addEventListener('input', function() {
                // Allow only letters, numbers, hyphens, and slashes
                this.value = this.value.replace(/[^a-zA-Z0-9\-\/]/g, '');
            });
        }
        
        // Date validation
        const dateInputs = document.querySelectorAll('input[type="date"]');
        
        // Get current date in YYYY-MM-DD format for date field validation
        const today = new Date();
        const currentDateString = today.toISOString().split('T')[0];
        
        // Calculate min date (120 years ago)
        const minDate = new Date();
        minDate.setFullYear(today.getFullYear() - 120);
        const minDateString = minDate.toISOString().split('T')[0];
        
        // Calculate tomorrow's date for due date
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        const tomorrowString = tomorrow.toISOString().split('T')[0];
        
        // Apply min attribute to due date field to prevent past date selection
        const dueDateField = document.getElementById('due_date');
        if (dueDateField) {
            dueDateField.setAttribute('min', tomorrowString);
        }
        
        // Remove browser constraints that might show error messages
        dateInputs.forEach(input => {
            if (input.id !== 'due_date') {
                // Remove min/max constraints that could cause validation messages
                input.removeAttribute('min');
                input.removeAttribute('max');
            }
            
            // Add our own validation on change
            input.addEventListener('change', function() {
                validateDateField(this);
            });
            
            // Validate initial values
            validateDateField(input);
        });
        
        function validateDateField(field) {
            // Clear any existing validation state
            field.classList.remove('is-invalid');
            field.setCustomValidity('');
            
            let errorMsg = null;
            const fieldValue = field.value;
            
            if (!fieldValue) return; // Empty is handled by required validation
            
            if (field.id === 'due_date') {
                // Due date should be in the future
                if (new Date(fieldValue) <= new Date(currentDateString)) {
                    errorMsg = 'Due date must be in the future';
                }
            } else if (field.id === 'solo_parent_id_expiry') {
                // Expiry date validation happens in relation to issued date
                const issuedDate = document.getElementById('solo_parent_id_issued').value;
                if (issuedDate && fieldValue <= issuedDate) {
                    errorMsg = 'Expiry date must be after issuance date';
                }
            } else if (field.id !== 'program_end_date') {
                // Most dates shouldn't be in the future
                if (fieldValue > currentDateString) {
                    errorMsg = 'Date cannot be in the future';
                }
                // And not unreasonably old
                if (fieldValue < minDateString) {
                    errorMsg = 'Date is too far in the past';
                }
            }
            
            // Apply validation state if there's an error
            if (errorMsg) {
                field.classList.add('is-invalid');
                
                // Remove any existing feedback element
                const existingFeedback = field.nextElementSibling;
                if (existingFeedback && existingFeedback.classList.contains('invalid-feedback')) {
                    existingFeedback.remove();
                }
                
                // Create new feedback element
                const feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                feedback.textContent = errorMsg;
                field.parentNode.appendChild(feedback);
                
                // Set the custom validity to prevent form submission
                field.setCustomValidity(errorMsg);
            }
        }
        
        // Cross-field validation
        const enrollmentDate = document.getElementById('enrollment_date');
        const programEndDate = document.getElementById('program_end_date');
        
        if (enrollmentDate && programEndDate) {
            enrollmentDate.addEventListener('change', function() {
                if (programEndDate.value && this.value && programEndDate.value < this.value) {
                    validateDateField(programEndDate);
                }
            });
            
            programEndDate.addEventListener('change', function() {
                if (enrollmentDate.value && this.value && this.value < enrollmentDate.value) {
                    this.classList.add('is-invalid');
                    const feedback = document.createElement('div');
                    feedback.className = 'invalid-feedback';
                    feedback.textContent = 'End date must be after enrollment date';
                    
                    const existingFeedback = this.nextElementSibling;
                    if (existingFeedback && existingFeedback.classList.contains('invalid-feedback')) {
                        existingFeedback.remove();
                    }
                    
                    this.parentNode.appendChild(feedback);
                    this.setCustomValidity('End date must be after enrollment date');
                } else {
                    this.classList.remove('is-invalid');
                    this.setCustomValidity('');
                }
            });
        }
        
        const idIssuedDate = document.getElementById('solo_parent_id_issued');
        const idExpiryDate = document.getElementById('solo_parent_id_expiry');
        
        if (idIssuedDate && idExpiryDate) {
            idIssuedDate.addEventListener('change', function() {
                if (idExpiryDate.value && this.value && idExpiryDate.value <= this.value) {
                    validateDateField(idExpiryDate);
                }
            });
        }
        
        // Clean up any browser validation error messages on form fields
        function removeHtml5ValidationErrors() {
            document.querySelectorAll('input, select, textarea').forEach(field => {
                field.addEventListener('invalid', function(e) {
                    e.preventDefault();
                    field.classList.add('is-invalid');
                });
            });
            
            // Specifically target textarea in the Solo Parent section
            const soloParentDetails = document.getElementById('solo_parent_details');
            if (soloParentDetails) {
                soloParentDetails.setCustomValidity('');
            }
        }
        
        // Form submission handling
        const form = document.getElementById('gadEditForm');
        form.addEventListener('submit', function(e) {
            // Remove any existing error messages
            const existingErrors = document.querySelectorAll('.alert-danger');
            existingErrors.forEach(error => error.remove());
            
            // If VAW case is checked, validate required fields
            const isVawCaseToggle = document.getElementById('is_vaw_case_toggle');
            if (isVawCaseToggle && isVawCaseToggle.checked) {
                const vawCaseSection = document.querySelector('.vaw-case-section');
                const vawCaseFields = vawCaseSection.querySelectorAll('input, select, textarea');
                let isValid = true;
                let firstInvalidField = null;
                
                vawCaseFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                        if (!firstInvalidField) {
                            firstInvalidField = field;
                        }
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    
                    // Scroll to the first invalid field
                    if (firstInvalidField) {
                        firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstInvalidField.focus();
                    }
                    
                    // Show error message
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'alert alert-danger alert-dismissible fade show';
                    errorDiv.innerHTML = `
                        <i class="fe fe-alert-circle fe-16 mr-2"></i> Please fill in all required VAW case fields.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    `;
                    
                    // Insert error message at the top of the form
                    const formContainer = document.querySelector('.row.justify-content-center .col-12');
                    formContainer.insertBefore(errorDiv, form);
                    
                    return false;
                }
            }
            
            // If we get here, the form is valid
            return true;
        });
        
        // Function to scroll to first error
        function scrollToFirstError() {
            // Find the first error element (either backend .is-invalid or error message)
            const firstError = document.querySelector('.is-invalid, .invalid-feedback, .text-danger');
            
            if (firstError) {
                // Find the closest input element if the error message was found
                const errorField = firstError.classList.contains('is-invalid') ? 
                    firstError : firstError.previousElementSibling;
                
                // If error is in a hidden section, expand that section
                const expandSection = (element) => {
                    // Look for parent card-body with display:none
                    const section = element.closest('.card-body[style*="display: none"]');
                    if (section) {
                        section.style.display = 'block';
                        
                        // Identify which section it is and check the corresponding toggle
                        if (section.classList.contains('pregnancy-section')) {
                            document.getElementById('is_pregnant_toggle').checked = true;
                        } else if (section.classList.contains('solo-parent-section')) {
                            document.getElementById('is_solo_parent_toggle').checked = true;
                        } else if (section.classList.contains('vaw-case-section')) {
                            document.getElementById('is_vaw_case_toggle').checked = true;
                        }
                    }
                };
                
                // If error field is found, expand its section if needed
                if (errorField) {
                    expandSection(errorField);
                }
                
                // Also check if the firstError itself is in a hidden section
                expandSection(firstError);
                
                // Scroll to the error with animation
                setTimeout(() => {
                    window.scrollTo({
                        top: firstError.getBoundingClientRect().top + window.pageYOffset - 100,
                        behavior: 'smooth'
                    });
                    
                    // Focus the error field
                    if (errorField && errorField.focus) {
                        setTimeout(() => errorField.focus(), 500);
                    }
                }, 100);
            }
        }
        
        // Handle initial errors (from server validation) when page loads
        function handleInitialErrors() {
            const hasErrors = document.querySelectorAll('.is-invalid, .invalid-feedback, .text-danger:not(:empty)').length > 0;
            
            if (hasErrors) {
                // Ensure all sections with errors are expanded before scrolling
                document.querySelectorAll('.is-invalid, .invalid-feedback:not(:empty), .text-danger:not(:empty)').forEach(error => {
                    const errorSection = error.closest('.pregnancy-section, .solo-parent-section, .vaw-case-section');
                    
                    if (errorSection) {
                        // Expand the section containing the error
                        errorSection.style.display = 'block';
                        
                        // Check the corresponding toggle checkbox
                        if (errorSection.classList.contains('pregnancy-section')) {
                            document.getElementById('is_pregnant_toggle').checked = true;
                        } else if (errorSection.classList.contains('solo-parent-section')) {
                            document.getElementById('is_solo_parent_toggle').checked = true;
                        } else if (errorSection.classList.contains('vaw-case-section')) {
                            document.getElementById('is_vaw_case_toggle').checked = true;
                        }
                    }
                });
                
                // After ensuring sections are expanded, scroll to the first error
                setTimeout(() => {
                    scrollToFirstError();
                }, 200);
            }
        }
        
        // Remove any existing validation errors in the solo parent section specifically
        const soloParentDetailsField = document.getElementById('solo_parent_details');
        if (soloParentDetailsField) {
            soloParentDetailsField.addEventListener('focus', function() {
                // Clear any browser validation message
                this.setCustomValidity('');
            });
            
            // Clear on page load
            soloParentDetailsField.setCustomValidity('');
        }
        
        // Initialize required fields and check for errors on page load
        updateRequiredFields();
        removeHtml5ValidationErrors();
        handleInitialErrors();
    });
</script>
@endpush