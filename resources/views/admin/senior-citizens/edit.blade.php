@extends('layouts.admin.master')

@section('title', 'Edit Senior Citizen')

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
    
    .card-outline.card-success {
        border-top-color: #28a745;
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
                <h1 class="m-0">Edit Senior Citizen</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.senior-citizens.index') }}">Senior Citizens</a></li>
                    <li class="breadcrumb-item active">Edit Senior Citizen</li>
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
                                <i class="fas fa-user-edit mr-2"></i>View & Update Senior Citizen
                            </h3>
                            <div class="card-tools">
                                <a href="{{ route('admin.senior-citizens.index') }}" class="btn btn-default btn-sm mr-2">
                                    <i class="fas fa-arrow-left mr-2"></i>Back to List
                                </a>
                                <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteModal" type="button">
                                    <i class="fas fa-trash mr-2"></i>Archive
                                </button>
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="mb-0"><strong>Name:</strong> {{ $seniorCitizen->first_name }} {{ $seniorCitizen->middle_name ? $seniorCitizen->middle_name . ' ' : '' }}{{ $seniorCitizen->last_name }}{{ $seniorCitizen->suffix ? ' ' . $seniorCitizen->suffix : '' }}</p>
                            <p class="text-muted mb-0"><strong>Senior ID:</strong> {{ $seniorCitizen->senior_id_number ?: 'ID Not Assigned' }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.senior-citizens.update', $seniorCitizen) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Senior Citizen Information Card -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-user mr-2"></i>Senior Citizen Information</h3>
                            <div class="card-tools">
                                <small class="text-muted">Fields marked with <span class="text-danger">*</span> are required</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 mb-4 text-center">
                                    <div class="mb-3">
                                        @if($seniorCitizen->photo && file_exists(storage_path('app/public/senior_citizens/photos/' . $seniorCitizen->photo)))
                                            <img src="{{ asset('storage/senior_citizens/photos/' . $seniorCitizen->photo) }}" 
                                                 alt="{{ $seniorCitizen->first_name }} {{ $seniorCitizen->last_name }}" 
                                                 class="img-fluid rounded-circle" 
                                                 style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #007bff;">
                                        @else
                                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-primary text-white" 
                                                 style="width: 100px; height: 100px; font-size: 36px; font-weight: bold;">
                                                {{ substr($seniorCitizen->first_name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mt-2">
                                        <p class="small text-muted mb-1">Senior ID</p>
                                        <h5 class="mb-0">{{ $seniorCitizen->senior_id_number ?: 'Not Assigned' }}</h5>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="type_of_resident">Type of Resident <span class="text-danger">*</span></label>
                                            <select class="form-control form-control-sm @error('type_of_resident') is-invalid @enderror" id="type_of_resident" name="type_of_resident" required>
                                                <option value="Non-Migrant" {{ $seniorCitizen->type_of_resident == 'Non-Migrant' ? 'selected' : '' }}>Non-Migrant</option>
                                                <option value="Migrant" {{ $seniorCitizen->type_of_resident == 'Migrant' ? 'selected' : '' }}>Migrant</option>
                                                <option value="Transient" {{ $seniorCitizen->type_of_resident == 'Transient' ? 'selected' : '' }}>Transient</option>
                                            </select>
                                            @error('type_of_resident')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="first_name">First Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $seniorCitizen->first_name) }}" required>
                                            @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="middle_name">Middle Name</label>
                                            <input type="text" class="form-control form-control-sm @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name', $seniorCitizen->middle_name) }}">
                                            @error('middle_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $seniorCitizen->last_name) }}" required>
                                            @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="suffix">Suffix</label>
                                            <input type="text" class="form-control form-control-sm @error('suffix') is-invalid @enderror" id="suffix" name="suffix" value="{{ old('suffix', $seniorCitizen->suffix) }}">
                                            @error('suffix')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="birthdate">Birthdate <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control form-control-sm @error('birthdate') is-invalid @enderror" id="birthdate" name="birthdate" value="{{ old('birthdate', $seniorCitizen->birthdate ? $seniorCitizen->birthdate->format('Y-m-d') : '') }}" required>
                                            @error('birthdate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="birthplace">Birthplace <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm @error('birthplace') is-invalid @enderror" id="birthplace" name="birthplace" value="{{ old('birthplace', $seniorCitizen->birthplace) }}" required>
                                            @error('birthplace')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="sex">Gender <span class="text-danger">*</span></label>
                                            <select class="form-control form-control-sm @error('sex') is-invalid @enderror" id="sex" name="sex" required>
                                                <option value="">-- Select --</option>
                                                <option value="Male" {{ $seniorCitizen->sex == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ $seniorCitizen->sex == 'Female' ? 'selected' : '' }}>Female</option>
                                                <option value="Non-binary" {{ $seniorCitizen->sex == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                                                <option value="Transgender" {{ $seniorCitizen->sex == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                                                <option value="Other" {{ $seniorCitizen->sex == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('sex')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="civil_status">Civil Status <span class="text-danger">*</span></label>
                                            <select class="form-control form-control-sm @error('civil_status') is-invalid @enderror" id="civil_status" name="civil_status" required>
                                                <option value="Single" {{ $seniorCitizen->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                                                <option value="Married" {{ $seniorCitizen->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                                <option value="Widowed" {{ $seniorCitizen->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                                <option value="Separated" {{ $seniorCitizen->civil_status == 'Separated' ? 'selected' : '' }}>Separated</option>
                                                <option value="Divorced" {{ $seniorCitizen->civil_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                            </select>
                                            @error('civil_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="current_address">Address <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm @error('current_address') is-invalid @enderror" id="current_address" name="current_address" value="{{ old('current_address', $seniorCitizen->current_address) }}" required>
                                            @error('current_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label for="purok">Purok</label>
                                            <input type="text" class="form-control form-control-sm @error('purok') is-invalid @enderror" id="purok" name="purok" value="{{ old('purok', $seniorCitizen->purok) }}">
                                            @error('purok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="contact_number">Contact Number <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" value="{{ old('contact_number', $seniorCitizen->contact_number) }}" required pattern="[0-9]{11}" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="email_address">Email Address</label>
                                            <input type="email" class="form-control form-control-sm @error('email_address') is-invalid @enderror" id="email_address" name="email_address" value="{{ old('email_address', $seniorCitizen->email_address) }}">
                                            @error('email_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
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
                                        <option value="FILIPINO" {{ $seniorCitizen->citizenship_type == 'FILIPINO' ? 'selected' : '' }}>Filipino</option>
                                        <option value="DUAL" {{ $seniorCitizen->citizenship_type == 'DUAL' ? 'selected' : '' }}>Dual Citizen</option>
                                        <option value="NATURALIZED" {{ $seniorCitizen->citizenship_type == 'NATURALIZED' ? 'selected' : '' }}>Naturalized Filipino</option>
                                        <option value="FOREIGN" {{ $seniorCitizen->citizenship_type == 'FOREIGN' ? 'selected' : '' }}>Foreign National</option>
                                    </select>
                                    @error('citizenship_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="citizenship_country">Citizenship Country <span id="citizenship_country_required" class="text-danger" style="display: none;">*</span></label>
                                    <input type="text" class="form-control form-control-sm @error('citizenship_country') is-invalid @enderror" id="citizenship_country" name="citizenship_country" value="{{ old('citizenship_country', $seniorCitizen->citizenship_country) }}" placeholder="Enter country (for dual/foreign/naturalized)">
                                    @error('citizenship_country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="religion">Religion</label>
                                    <input type="text" class="form-control form-control-sm @error('religion') is-invalid @enderror" id="religion" name="religion" value="{{ old('religion', $seniorCitizen->religion) }}">
                                    @error('religion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="profession_occupation">Occupation/Profession</label>
                                    <input type="text" class="form-control form-control-sm @error('profession_occupation') is-invalid @enderror" id="profession_occupation" name="profession_occupation" value="{{ old('profession_occupation', $seniorCitizen->profession_occupation) }}">
                                    @error('profession_occupation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="educational_attainment">Educational Attainment</label>
                                    <select class="form-control form-control-sm @error('educational_attainment') is-invalid @enderror" id="educational_attainment" name="educational_attainment">
                                        <option value="">-- Select --</option>
                                        <option value="No Formal Education" {{ $seniorCitizen->educational_attainment == 'No Formal Education' ? 'selected' : '' }}>No Formal Education</option>
                                        <option value="Elementary Undergraduate" {{ $seniorCitizen->educational_attainment == 'Elementary Undergraduate' ? 'selected' : '' }}>Elementary Undergraduate</option>
                                        <option value="Elementary Graduate" {{ $seniorCitizen->educational_attainment == 'Elementary Graduate' ? 'selected' : '' }}>Elementary Graduate</option>
                                        <option value="High School Undergraduate" {{ $seniorCitizen->educational_attainment == 'High School Undergraduate' ? 'selected' : '' }}>High School Undergraduate</option>
                                        <option value="High School Graduate" {{ $seniorCitizen->educational_attainment == 'High School Graduate' ? 'selected' : '' }}>High School Graduate</option>
                                        <option value="Vocational/Technical Graduate" {{ $seniorCitizen->educational_attainment == 'Vocational/Technical Graduate' ? 'selected' : '' }}>Vocational/Technical Graduate</option>
                                        <option value="College Undergraduate" {{ $seniorCitizen->educational_attainment == 'College Undergraduate' ? 'selected' : '' }}>College Undergraduate</option>
                                        <option value="College Graduate" {{ $seniorCitizen->educational_attainment == 'College Graduate' ? 'selected' : '' }}>College Graduate</option>
                                        <option value="Post Graduate" {{ $seniorCitizen->educational_attainment == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                                    </select>
                                    @error('educational_attainment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="education_status">Education Status</label>
                                    <select class="form-control form-control-sm @error('education_status') is-invalid @enderror" id="education_status" name="education_status">
                                        <option value="">-- Select --</option>
                                        <option value="Studying" {{ $seniorCitizen->education_status == 'Studying' ? 'selected' : '' }}>Studying</option>
                                        <option value="Graduated" {{ $seniorCitizen->education_status == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                                        <option value="Stopped Schooling" {{ $seniorCitizen->education_status == 'Stopped Schooling' ? 'selected' : '' }}>Stopped Schooling</option>
                                        <option value="Not Applicable" {{ $seniorCitizen->education_status == 'Not Applicable' ? 'selected' : '' }}>Not Applicable</option>
                                    </select>
                                    @error('education_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Benefits & Pension Information Card -->
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-money-bill-wave mr-2"></i>Benefits & Pension Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" class="form-check-input" id="receiving_pension" name="receiving_pension" value="1" {{ old('receiving_pension', $seniorCitizen->receiving_pension) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="receiving_pension">Receiving Pension</label>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="pension_type">Pension Type</label>
                                    <select class="form-control form-control-sm @error('pension_type') is-invalid @enderror" id="pension_type" name="pension_type">
                                        <option value="">-- Select --</option>
                                        <option value="SSS" {{ $seniorCitizen->pension_type == 'SSS' ? 'selected' : '' }}>SSS</option>
                                        <option value="GSIS" {{ $seniorCitizen->pension_type == 'GSIS' ? 'selected' : '' }}>GSIS</option>
                                        <option value="Government Employee" {{ $seniorCitizen->pension_type == 'Government Employee' ? 'selected' : '' }}>Government Employee</option>
                                        <option value="Private Company" {{ $seniorCitizen->pension_type == 'Private Company' ? 'selected' : '' }}>Private Company</option>
                                        <option value="Social Pension" {{ $seniorCitizen->pension_type == 'Social Pension' ? 'selected' : '' }}>Social Pension</option>
                                        <option value="Other" {{ $seniorCitizen->pension_type == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('pension_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="pension_amount">Pension Amount (₱)</label>
                                    <input type="number" step="0.01" class="form-control form-control-sm @error('pension_amount') is-invalid @enderror" id="pension_amount" name="pension_amount" value="{{ old('pension_amount', $seniorCitizen->pension_amount) }}" placeholder="0.00">
                                    @error('pension_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="form-check mt-4">
                                        <input type="checkbox" class="form-check-input" id="has_senior_discount_card" name="has_senior_discount_card" value="1" {{ old('has_senior_discount_card', $seniorCitizen->has_senior_discount_card) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="has_senior_discount_card">Has Senior Discount Card</label>
                                    </div>
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
                                    <input type="text" class="form-control form-control-sm @error('emergency_contact_name') is-invalid @enderror" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', $seniorCitizen->emergency_contact_name) }}">
                                    @error('emergency_contact_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="emergency_contact_relationship">Relationship</label>
                                    <select class="form-control form-control-sm @error('emergency_contact_relationship') is-invalid @enderror" id="emergency_contact_relationship" name="emergency_contact_relationship">
                                        <option value="">-- Select --</option>
                                        <option value="spouse" {{ $seniorCitizen->emergency_contact_relationship == 'spouse' ? 'selected' : '' }}>Spouse</option>
                                        <option value="child" {{ $seniorCitizen->emergency_contact_relationship == 'child' ? 'selected' : '' }}>Child</option>
                                        <option value="sibling" {{ $seniorCitizen->emergency_contact_relationship == 'sibling' ? 'selected' : '' }}>Sibling</option>
                                        <option value="parent" {{ $seniorCitizen->emergency_contact_relationship == 'parent' ? 'selected' : '' }}>Parent</option>
                                        <option value="relative" {{ $seniorCitizen->emergency_contact_relationship == 'relative' ? 'selected' : '' }}>Relative</option>
                                        <option value="friend" {{ $seniorCitizen->emergency_contact_relationship == 'friend' ? 'selected' : '' }}>Friend</option>
                                        <option value="neighbor" {{ $seniorCitizen->emergency_contact_relationship == 'neighbor' ? 'selected' : '' }}>Neighbor</option>
                                        <option value="caregiver" {{ $seniorCitizen->emergency_contact_relationship == 'caregiver' ? 'selected' : '' }}>Caregiver</option>
                                    </select>
                                    @error('emergency_contact_relationship')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="emergency_contact_number">Contact Number</label>
                                    <input type="text" class="form-control form-control-sm @error('emergency_contact_number') is-invalid @enderror" id="emergency_contact_number" name="emergency_contact_number" value="{{ old('emergency_contact_number', $seniorCitizen->emergency_contact_number) }}" pattern="[0-9]{11}" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    @error('emergency_contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="emergency_contact_address">Emergency Contact Address</label>
                                    <textarea class="form-control form-control-sm @error('emergency_contact_address') is-invalid @enderror" id="emergency_contact_address" name="emergency_contact_address" rows="2" placeholder="Enter emergency contact address">{{ old('emergency_contact_address', $seniorCitizen->emergency_contact_address) }}</textarea>
                                    @error('emergency_contact_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="card">
                        <div class="card-footer">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('admin.senior-citizens.index') }}" class="btn btn-default mr-2">
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
                <p>Are you sure you want to archive this senior citizen?</p>
                <p class="text-muted"><small>This senior citizen will be moved to the archived list and can be restored later if needed.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.senior-citizens.archive', $seniorCitizen) }}" method="POST" style="display: inline-block;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Archive Senior Citizen</button>
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
    
    // Handle citizenship country field requirement
    $('#citizenship_type').on('change', function() {
        var type = $(this).val();
        if (type === 'DUAL' || type === 'NATURALIZED' || type === 'FOREIGN') {
            $('#citizenship_country').prop('required', true);
            $('#citizenship_country_required').show();
        } else {
            $('#citizenship_country').prop('required', false);
            $('#citizenship_country_required').hide();
        }
    });
    
    // Handle pension fields requirement
    $('#receiving_pension').on('change', function() {
        if ($(this).is(':checked')) {
            $('#pension_type').prop('required', true);
        } else {
            $('#pension_type').prop('required', false);
        }
    });
    
    // Set initial state without clearing values
    var initialCitizenshipType = $('#citizenship_type').val();
    if (initialCitizenshipType === 'DUAL' || initialCitizenshipType === 'NATURALIZED' || initialCitizenshipType === 'FOREIGN') {
        $('#citizenship_country').prop('required', true);
        $('#citizenship_country_required').show();
    }
    
    var initialPensionChecked = $('#receiving_pension').is(':checked');
    if (initialPensionChecked) {
        $('#pension_type').prop('required', true);
    }
});
</script>
@endpush
