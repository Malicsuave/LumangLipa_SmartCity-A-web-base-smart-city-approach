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
                    
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('admin.gad.index') }}" class="btn btn-outline-secondary mr-2">
                            <i class="fe fe-x fe-16 mr-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fe fe-save fe-16 mr-2"></i>Create GAD Record
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
        // Initialize select2 for dropdowns
        $('.custom-select').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
        
        // Gender details visibility
        const genderIdentity = document.getElementById('gender_identity');
        const genderDetailsRow = document.getElementById('gender-details-row');
        
        genderIdentity.addEventListener('change', function() {
            if (this.value === 'Other') {
                genderDetailsRow.style.display = 'flex';
            } else {
                genderDetailsRow.style.display = 'none';
            }
        });
        
        // Initialize on page load
        if (genderIdentity.value === 'Other') {
            genderDetailsRow.style.display = 'flex';
        } else {
            genderDetailsRow.style.display = 'none';
        }
        
        // Toggle sections
        const pregnancyToggle = document.getElementById('is_pregnant_toggle');
        const pregnancySection = document.querySelector('.pregnancy-section');
        
        pregnancyToggle.addEventListener('change', function() {
            pregnancySection.style.display = this.checked ? 'block' : 'none';
        });
        
        const soloParentToggle = document.getElementById('is_solo_parent_toggle');
        const soloParentSection = document.querySelector('.solo-parent-section');
        
        soloParentToggle.addEventListener('change', function() {
            soloParentSection.style.display = this.checked ? 'block' : 'none';
        });
        
        const vawCaseToggle = document.getElementById('is_vaw_case_toggle');
        const vawCaseSection = document.querySelector('.vaw-case-section');
        
        vawCaseToggle.addEventListener('change', function() {
            vawCaseSection.style.display = this.checked ? 'block' : 'none';
        });
    });
</script>
@endsection