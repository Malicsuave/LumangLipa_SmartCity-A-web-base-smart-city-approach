@extends('layouts.admin.master')

@section('title', 'Edit GAD Record')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit GAD Record</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.gad.index') }}">GAD Records</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.gad.show', $gad->id) }}">Record Details</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Edit GAD Record for {{ $gad->resident->full_name }}
        </div>
        <div class="card-body">
            <form action="{{ route('admin.gad.update', $gad->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="gender_identity" class="form-label">Gender Identity <span class="text-danger">*</span></label>
                            <select name="gender_identity" id="gender_identity" class="form-select @error('gender_identity') is-invalid @enderror" required>
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
                    <div class="col-md-6" id="gender-details-row" style="{{ old('gender_identity', $gad->gender_identity) == 'Other' ? '' : 'display: none;' }}">
                        <div class="form-group">
                            <label for="gender_details" class="form-label">Gender Details (Optional)</label>
                            <input type="text" name="gender_details" id="gender_details" class="form-control @error('gender_details') is-invalid @enderror" value="{{ old('gender_details', $gad->gender_details) }}">
                            @error('gender_details')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="card mb-3">
                    <div class="card-header bg-light">Program Enrollment</div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Programs Enrolled</label>
                                <div class="row">
                                    @foreach($programTypes as $program)
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="programs_enrolled[]" value="{{ $program }}" id="program_{{ $loop->index }}" 
                                                    {{ (is_array(old('programs_enrolled')) && in_array($program, old('programs_enrolled'))) || 
                                                       (old('programs_enrolled') === null && is_array($gad->programs_enrolled) && in_array($program, $gad->programs_enrolled)) ? 'checked' : '' }}>
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
                                    <input type="date" name="enrollment_date" id="enrollment_date" class="form-control @error('enrollment_date') is-invalid @enderror" value="{{ old('enrollment_date', optional($gad->enrollment_date)->format('Y-m-d')) }}">
                                    @error('enrollment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="program_end_date" class="form-label">Program End Date</label>
                                    <input type="date" name="program_end_date" id="program_end_date" class="form-control @error('program_end_date') is-invalid @enderror" value="{{ old('program_end_date', optional($gad->program_end_date)->format('Y-m-d')) }}">
                                    @error('program_end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="program_status" class="form-label">Program Status</label>
                                    <select name="program_status" id="program_status" class="form-select @error('program_status') is-invalid @enderror">
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
                </div>
                
                <!-- Pregnancy Information Section -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="is_pregnant_toggle" name="is_pregnant" {{ old('is_pregnant', $gad->is_pregnant) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_pregnant_toggle">
                                Pregnancy Information
                            </label>
                        </div>
                    </div>
                    <div class="card-body pregnancy-section" {{ old('is_pregnant', $gad->is_pregnant) ? '' : 'style="display: none;"' }}>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="due_date" class="form-label">Due Date</label>
                                    <input type="date" name="due_date" id="due_date" class="form-control @error('due_date') is-invalid @enderror" value="{{ old('due_date', optional($gad->due_date)->format('Y-m-d')) }}">
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" value="1" id="is_lactating" name="is_lactating" {{ old('is_lactating', $gad->is_lactating) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_lactating">
                                        Is Lactating
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="needs_maternity_support" name="needs_maternity_support" {{ old('needs_maternity_support', $gad->needs_maternity_support) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="needs_maternity_support">
                                        Needs Maternity Support
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Solo Parent Section -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="is_solo_parent_toggle" name="is_solo_parent" {{ old('is_solo_parent', $gad->is_solo_parent) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_solo_parent_toggle">
                                Solo Parent Information
                            </label>
                        </div>
                    </div>
                    <div class="card-body solo-parent-section" {{ old('is_solo_parent', $gad->is_solo_parent) ? '' : 'style="display: none;"' }}>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="solo_parent_id" class="form-label">Solo Parent ID Number</label>
                                    <input type="text" name="solo_parent_id" id="solo_parent_id" class="form-control @error('solo_parent_id') is-invalid @enderror" value="{{ old('solo_parent_id', $gad->solo_parent_id) }}">
                                    @error('solo_parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="solo_parent_id_issued" class="form-label">ID Issuance Date</label>
                                    <input type="date" name="solo_parent_id_issued" id="solo_parent_id_issued" class="form-control @error('solo_parent_id_issued') is-invalid @enderror" value="{{ old('solo_parent_id_issued', optional($gad->solo_parent_id_issued)->format('Y-m-d')) }}">
                                    @error('solo_parent_id_issued')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="solo_parent_id_expiry" class="form-label">ID Expiry Date</label>
                                    <input type="date" name="solo_parent_id_expiry" id="solo_parent_id_expiry" class="form-control @error('solo_parent_id_expiry') is-invalid @enderror" value="{{ old('solo_parent_id_expiry', optional($gad->solo_parent_id_expiry)->format('Y-m-d')) }}">
                                    @error('solo_parent_id_expiry')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="solo_parent_details" class="form-label">Solo Parent Details</label>
                                    <textarea name="solo_parent_details" id="solo_parent_details" rows="3" class="form-control @error('solo_parent_details') is-invalid @enderror">{{ old('solo_parent_details', $gad->solo_parent_details) }}</textarea>
                                    @error('solo_parent_details')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- VAW Case Section -->
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="is_vaw_case_toggle" name="is_vaw_case" {{ old('is_vaw_case', $gad->is_vaw_case) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="is_vaw_case_toggle">
                                Violence Against Women (VAW) Case
                            </label>
                        </div>
                    </div>
                    <div class="card-body vaw-case-section" {{ old('is_vaw_case', $gad->is_vaw_case) ? '' : 'style="display: none;"' }}>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vaw_case_number" class="form-label">Case Number</label>
                                    <input type="text" name="vaw_case_number" id="vaw_case_number" class="form-control @error('vaw_case_number') is-invalid @enderror" value="{{ old('vaw_case_number', $gad->vaw_case_number) }}">
                                    @error('vaw_case_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vaw_report_date" class="form-label">Report Date</label>
                                    <input type="date" name="vaw_report_date" id="vaw_report_date" class="form-control @error('vaw_report_date') is-invalid @enderror" value="{{ old('vaw_report_date', optional($gad->vaw_report_date)->format('Y-m-d')) }}">
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
                                    <select name="vaw_case_status" id="vaw_case_status" class="form-select @error('vaw_case_status') is-invalid @enderror">
                                        <option value="">Select Status</option>
                                        <option value="Pending" {{ old('vaw_case_status', $gad->vaw_case_status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Ongoing" {{ old('vaw_case_status', $gad->vaw_case_status) == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                        <option value="Resolved" {{ old('vaw_case_status', $gad->vaw_case_status) == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="Closed" {{ old('vaw_case_status', $gad->vaw_case_status) == 'Closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                    @error('vaw_case_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="vaw_case_details" class="form-label">Case Details</label>
                                    <textarea name="vaw_case_details" id="vaw_case_details" rows="3" class="form-control @error('vaw_case_details') is-invalid @enderror">{{ old('vaw_case_details', $gad->vaw_case_details) }}</textarea>
                                    @error('vaw_case_details')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Notes -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $gad->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Update GAD Record</button>
                        <a href="{{ route('admin.gad.show', $gad->id) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gender details visibility
        const genderIdentity = document.getElementById('gender_identity');
        const genderDetailsRow = document.getElementById('gender-details-row');
        
        genderIdentity.addEventListener('change', function() {
            if (this.value === 'Other') {
                genderDetailsRow.style.display = 'block';
            } else {
                genderDetailsRow.style.display = 'none';
            }
        });
        
        // Initialize on page load
        if (genderIdentity.value === 'Other') {
            genderDetailsRow.style.display = 'block';
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