@extends('layouts.admin.master')

@section('title', 'Edit Resident')

@push('styles')
<style>
    /* Card styling */
    .card-outline {
        border-top: 3px solid;
    }
    
    .card-outline.card-primary {
        border-top-color: #007bff;
    }
    
    .card-outline.card-info {
        border-top-color: #17a2b8;
    }
    
    .card-outline.card-warning {
        border-top-color: #ffc107;
    }
    
    .form-group {
        margin-bottom: 1rem;
    }
    
    .alert {
        border-radius: 0.25rem;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')
<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Resident</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
                    <li class="breadcrumb-item active">Edit Resident</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <!-- Page Header Card -->
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">
                                <i class="fas fa-user-edit mr-2"></i>View & Update Resident
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.residents.index') }}" class="btn btn-default btn-sm mr-2">
                                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                                </a>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" type="button">
                                    <i class="fas fa-trash mr-2"></i>Archive
                                </button>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="mb-0"><strong>Name:</strong> {{ $resident->first_name }} {{ $resident->middle_name ? $resident->middle_name . ' ' : '' }}{{ $resident->last_name }}{{ $resident->suffix ? ' ' . $resident->suffix : '' }}</p>
                            <p class="text-muted mb-0"><strong>Barangay ID:</strong> {{ $resident->barangay_id }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.residents.update', $resident) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Resident Information Card -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user mr-2"></i>Resident Information</h3>
                            <div class="card-tools">
                                <small class="text-muted">Fields marked with <span class="text-danger">*</span> are required</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="type_of_resident">Type of Resident <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm @error('type_of_resident') is-invalid @enderror" id="type_of_resident" name="type_of_resident" required>
                                        <option value="Non-Migrant" {{ $resident->type_of_resident == 'Non-Migrant' ? 'selected' : '' }}>Non-Migrant</option>
                                        <option value="Migrant" {{ $resident->type_of_resident == 'Migrant' ? 'selected' : '' }}>Migrant</option>
                                        <option value="Transient" {{ $resident->type_of_resident == 'Transient' ? 'selected' : '' }}>Transient</option>
                                    </select>
                                    @error('type_of_resident')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                            <label for="first_name">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $resident->first_name) }}" required>
                                            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="middle_name">Middle Name</label>
                                            <input type="text" class="form-control form-control-sm @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name', $resident->middle_name) }}">
                                            @error('middle_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $resident->last_name) }}" required>
                                            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="suffix">Suffix</label>
                                            <input type="text" class="form-control form-control-sm @error('suffix') is-invalid @enderror" id="suffix" name="suffix" value="{{ old('suffix', $resident->suffix) }}">
                                            @error('suffix')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="birthdate">Birthdate <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control form-control-sm @error('birthdate') is-invalid @enderror" id="birthdate" name="birthdate" value="{{ old('birthdate', $resident->birthdate ? $resident->birthdate->format('Y-m-d') : '') }}" required>
                                            @error('birthdate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="birthplace">Birthplace <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm @error('birthplace') is-invalid @enderror" id="birthplace" name="birthplace" value="{{ old('birthplace', $resident->birthplace) }}" required>
                                            @error('birthplace')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="sex">Gender <span class="text-danger">*</span></label>
                                            <select class="form-control form-control-sm @error('sex') is-invalid @enderror" id="sex" name="sex" required>
                                                <option value="">-- Select --</option>
                                                <option value="Male" {{ $resident->sex == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ $resident->sex == 'Female' ? 'selected' : '' }}>Female</option>
                                                <option value="Non-binary" {{ $resident->sex == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                                                <option value="Transgender" {{ $resident->sex == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                                                <option value="Other" {{ $resident->sex == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('sex')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="civil_status">Civil Status <span class="text-danger">*</span></label>
                                            <select class="form-control form-control-sm @error('civil_status') is-invalid @enderror" id="civil_status" name="civil_status" required>
                                                <option value="Single" {{ $resident->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                                                <option value="Married" {{ $resident->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                                <option value="Widowed" {{ $resident->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                                <option value="Separated" {{ $resident->civil_status == 'Separated' ? 'selected' : '' }}>Separated</option>
                                                <option value="Divorced" {{ $resident->civil_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                            </select>
                                            @error('civil_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="current_address">Address <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm @error('current_address') is-invalid @enderror" id="current_address" name="current_address" value="{{ old('current_address', $resident->current_address) }}" required>
                                            @error('current_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="purok">Purok</label>
                                            <input type="text" class="form-control form-control-sm @error('purok') is-invalid @enderror" id="purok" name="purok" value="{{ old('purok', $resident->purok) }}">
                                            @error('purok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="contact_number">Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" value="{{ old('contact_number', $resident->contact_number) }}" required pattern="[0-9]{11}" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="email_address">Email Address</label>
                                            <input type="email" class="form-control form-control-sm @error('email_address') is-invalid @enderror" id="email_address" name="email_address" value="{{ old('email_address', $resident->email_address) }}">
                                            @error('email_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                        </div>
                    </div>
                    
                    <!-- Additional Information Card -->
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>Additional Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="citizenship_type">Citizenship Type</label>
                                    <select class="form-control form-control-sm @error('citizenship_type') is-invalid @enderror" id="citizenship_type" name="citizenship_type">
                                        <option value="">-- Select --</option>
                                        <option value="FILIPINO" {{ $resident->citizenship_type == 'FILIPINO' ? 'selected' : '' }}>Filipino</option>
                                        <option value="DUAL" {{ $resident->citizenship_type == 'DUAL' ? 'selected' : '' }}>Dual Citizen</option>
                                        <option value="NATURALIZED" {{ $resident->citizenship_type == 'NATURALIZED' ? 'selected' : '' }}>Naturalized Filipino</option>
                                        <option value="FOREIGN" {{ $resident->citizenship_type == 'FOREIGN' ? 'selected' : '' }}>Foreign National</option>
                                    </select>
                                    @error('citizenship_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="citizenship_country">Citizenship Country <span class="citizenship-country-required text-danger" style="display: none;">*</span></label>
                                    <input type="text" class="form-control form-control-sm @error('citizenship_country') is-invalid @enderror" id="citizenship_country" name="citizenship_country" value="{{ old('citizenship_country', $resident->citizenship_country) }}" placeholder="Enter country (for dual/foreign/naturalized)">
                                    @error('citizenship_country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="religion">Religion</label>
                                    <input type="text" class="form-control form-control-sm @error('religion') is-invalid @enderror" id="religion" name="religion" value="{{ old('religion', $resident->religion) }}">
                                    @error('religion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="profession_occupation">Occupation/Profession</label>
                                    <input type="text" class="form-control form-control-sm @error('profession_occupation') is-invalid @enderror" id="profession_occupation" name="profession_occupation" value="{{ old('profession_occupation', $resident->profession_occupation) }}">
                                    @error('profession_occupation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="educational_attainment">Educational Attainment</label>
                                    <select class="form-control form-control-sm @error('educational_attainment') is-invalid @enderror" id="educational_attainment" name="educational_attainment">
                                        <option value="">-- Select --</option>
                                        <option value="No Formal Education" {{ $resident->educational_attainment == 'No Formal Education' ? 'selected' : '' }}>No Formal Education</option>
                                        <option value="Elementary Undergraduate" {{ $resident->educational_attainment == 'Elementary Undergraduate' ? 'selected' : '' }}>Elementary Undergraduate</option>
                                        <option value="Elementary Graduate" {{ $resident->educational_attainment == 'Elementary Graduate' ? 'selected' : '' }}>Elementary Graduate</option>
                                        <option value="High School Undergraduate" {{ $resident->educational_attainment == 'High School Undergraduate' ? 'selected' : '' }}>High School Undergraduate</option>
                                        <option value="High School Graduate" {{ $resident->educational_attainment == 'High School Graduate' ? 'selected' : '' }}>High School Graduate</option>
                                        <option value="Vocational/Technical Graduate" {{ $resident->educational_attainment == 'Vocational/Technical Graduate' ? 'selected' : '' }}>Vocational/Technical Graduate</option>
                                        <option value="College Undergraduate" {{ $resident->educational_attainment == 'College Undergraduate' ? 'selected' : '' }}>College Undergraduate</option>
                                        <option value="College Graduate" {{ $resident->educational_attainment == 'College Graduate' ? 'selected' : '' }}>College Graduate</option>
                                        <option value="Post Graduate" {{ $resident->educational_attainment == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                                    </select>
                                    @error('educational_attainment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="education_status">Education Status</label>
                                    <select class="form-control form-control-sm @error('education_status') is-invalid @enderror" id="education_status" name="education_status">
                                        <option value="">-- Select --</option>
                                        <option value="Studying" {{ $resident->education_status == 'Studying' ? 'selected' : '' }}>Studying</option>
                                        <option value="Graduated" {{ $resident->education_status == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                                        <option value="Stopped Schooling" {{ $resident->education_status == 'Stopped Schooling' ? 'selected' : '' }}>Stopped Schooling</option>
                                        <option value="Not Applicable" {{ $resident->education_status == 'Not Applicable' ? 'selected' : '' }}>Not Applicable</option>
                                    </select>
                                    @error('education_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact Card -->
                    <div class="card card-warning card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-phone-alt mr-2"></i>Emergency Contact</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="emergency_contact_name">Contact Person Name</label>
                                    <input type="text" class="form-control form-control-sm @error('emergency_contact_name') is-invalid @enderror" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', $resident->emergency_contact_name) }}">
                                    @error('emergency_contact_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="emergency_contact_relationship">Relationship</label>
                                    <select class="form-control form-control-sm @error('emergency_contact_relationship') is-invalid @enderror" id="emergency_contact_relationship" name="emergency_contact_relationship">
                                        <option value="">-- Select --</option>
                                        <option value="Parent" {{ $resident->emergency_contact_relationship == 'Parent' ? 'selected' : '' }}>Parent</option>
                                        <option value="Spouse" {{ $resident->emergency_contact_relationship == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                                        <option value="Child" {{ $resident->emergency_contact_relationship == 'Child' ? 'selected' : '' }}>Child</option>
                                        <option value="Sibling" {{ $resident->emergency_contact_relationship == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                                        <option value="Relative" {{ $resident->emergency_contact_relationship == 'Relative' ? 'selected' : '' }}>Relative</option>
                                        <option value="Friend" {{ $resident->emergency_contact_relationship == 'Friend' ? 'selected' : '' }}>Friend</option>
                                        <option value="Other" {{ $resident->emergency_contact_relationship == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('emergency_contact_relationship')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="emergency_contact_number">Contact Number</label>
                                    <input type="text" class="form-control form-control-sm @error('emergency_contact_number') is-invalid @enderror" id="emergency_contact_number" name="emergency_contact_number" value="{{ old('emergency_contact_number', $resident->emergency_contact_number) }}" pattern="[0-9]{11}" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    @error('emergency_contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card">
                        <div class="card-footer">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('admin.residents.index') }}" class="btn btn-default mr-2">
                                    <i class="fas fa-times mr-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white">Confirm Archive</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to archive this resident?</p>
                <p class="text-muted"><small>This resident will be moved to the archived list and can be restored later if needed.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.residents.destroy', $resident) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Archive Resident</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Display success message with Toastr
    @if(session('success'))
        showSuccess('{{ session('success') }}');
    @endif
    
    // Display error messages with Toastr
    @if($errors->any())
        @foreach($errors->all() as $error)
            showError('{{ $error }}');
        @endforeach
    @endif
    
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
            countryField.attr('placeholder', 'Enter country (for dual/foreign/naturalized)');
        }
    });
    
    // Trigger on page load to set initial state
    $('#citizenship_type').trigger('change');
});
</script>
@endpush

