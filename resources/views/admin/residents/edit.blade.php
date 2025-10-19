@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
<li class="breadcrumb-item active" aria-current="page">Edit Resident</li>
@endsection

@section('page-title', 'Edit Resident')
@section('page-subtitle', 'Update information for ' . $resident->full_name)

@section('title', 'Edit Resident')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <!-- Page Header Card -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="fe fe-edit-3 fe-16 mr-2 text-primary"></i>View & Update Resident</h4>
                        <p class="text-muted mb-0">{{ $resident->full_name }} - {{ $resident->barangay_id }}</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.residents.index') }}" class="btn btn-outline-secondary mr-2">
                            <i class="fe fe-arrow-left fe-16 mr-2"></i>Back to List
                        </a>
                        <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" type="button">
                            <i class="fe fe-trash fe-16 mr-2"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fe fe-check-circle fe-16 mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif



        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fe fe-alert-circle fe-16 mr-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <form action="{{ route('admin.residents.update', $resident) }}" method="POST" id="residentUpdateForm">
            @csrf
            @method('PUT')
            
            <!-- Resident Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0"><i class="fe fe-user fe-16 mr-2"></i>Resident Information</h6>
                    <small class="form-text text-muted mt-1">Fields marked with <span class="text-danger">*</span> are required</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-4 text-center">
                            <div class="avatar avatar-xl mb-3">
                                @if($resident->photo)
                                    <img src="{{ $resident->photo_url }}" alt="{{ $resident->full_name }}" class="avatar-img rounded-circle">
                                @else
                                    <div class="avatar-letter rounded-circle bg-primary">{{ substr($resident->first_name, 0, 1) }}</div>
                                @endif
                            </div>
                            <div class="mt-1">
                                <p class="small text-muted mb-1">Barangay ID</p>
                                <h5 class="mb-0">{{ $resident->barangay_id }}</h5>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="type_of_resident">Type of Resident <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type_of_resident') is-invalid @enderror" id="type_of_resident" name="type_of_resident">
                                        <option value="Non-Migrant" {{ $resident->type_of_resident == 'Non-Migrant' ? 'selected' : '' }}>Non-Migrant</option>
                                        <option value="Migrant" {{ $resident->type_of_resident == 'Migrant' ? 'selected' : '' }}>Migrant</option>
                                        <option value="Transient" {{ $resident->type_of_resident == 'Transient' ? 'selected' : '' }}>Transient</option>
                                    </select>
                                    @error('type_of_resident')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="first_name">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $resident->first_name) }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name', $resident->middle_name) }}">
                                    @error('middle_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="last_name">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $resident->last_name) }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="suffix">Suffix</label>
                                    <input type="text" class="form-control @error('suffix') is-invalid @enderror" id="suffix" name="suffix" value="{{ old('suffix', $resident->suffix) }}">
                                    @error('suffix')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="birthdate">Birthdate <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('birthdate') is-invalid @enderror" id="birthdate" name="birthdate" value="{{ old('birthdate', $resident->birthdate ? $resident->birthdate->format('Y-m-d') : '') }}" required>
                                    @error('birthdate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="birthplace">Birthplace <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('birthplace') is-invalid @enderror" id="birthplace" name="birthplace" value="{{ old('birthplace', $resident->birthplace) }}" required>
                                    @error('birthplace')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="sex">Gender <span class="text-danger">*</span></label>
                                    <select class="form-control @error('sex') is-invalid @enderror" id="sex" name="sex">
                                        <option value="">-- Select Gender --</option>
                                        <option value="Male" {{ $resident->sex == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ $resident->sex == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Non-binary" {{ $resident->sex == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                                        <option value="Transgender" {{ $resident->sex == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                                        <option value="Other" {{ $resident->sex == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('sex')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="civil_status">Civil Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('civil_status') is-invalid @enderror" id="civil_status" name="civil_status">
                                        <option value="Single" {{ $resident->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                                        <option value="Married" {{ $resident->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                        <option value="Widowed" {{ $resident->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                        <option value="Separated" {{ $resident->civil_status == 'Separated' ? 'selected' : '' }}>Separated</option>
                                        <option value="Divorced" {{ $resident->civil_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                    </select>
                                    @error('civil_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="address">Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $resident->address) }}" required>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="contact_number">Contact Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" 
                                        value="{{ old('contact_number', $resident->contact_number) }}" required
                                        pattern="[0-9]{11}" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                        title="Please enter exactly 11 digits">
                                    @error('contact_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="email_address">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email_address') is-invalid @enderror" id="email_address" name="email_address" value="{{ old('email_address', $resident->email_address) }}" required>
                                    @error('email_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0"><i class="fe fe-info fe-16 mr-2"></i>Additional Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="citizenship_type">Citizenship Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('citizenship_type') is-invalid @enderror" id="citizenship_type" name="citizenship_type">
                                <option value="FILIPINO" {{ $resident->citizenship_type == 'FILIPINO' ? 'selected' : '' }}>Filipino</option>
                                <option value="Dual Citizen" {{ $resident->citizenship_type == 'Dual Citizen' ? 'selected' : '' }}>Dual Citizen</option>
                                <option value="Foreigner" {{ $resident->citizenship_type == 'Foreigner' ? 'selected' : '' }}>Foreigner</option>
                            </select>
                            @error('citizenship_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="citizenship_country">Country</label>
                            <input type="text" class="form-control @error('citizenship_country') is-invalid @enderror" id="citizenship_country" name="citizenship_country" value="{{ old('citizenship_country', $resident->citizenship_country) }}">
                            @error('citizenship_country')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="religion">Religion</label>
                            <input type="text" class="form-control @error('religion') is-invalid @enderror" id="religion" name="religion" value="{{ old('religion', $resident->religion) }}">
                            @error('religion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="philsys_id">PhilSys ID</label>
                            <input type="text" class="form-control @error('philsys_id') is-invalid @enderror" id="philsys_id" name="philsys_id" value="{{ old('philsys_id', $resident->philsys_id) }}">
                            @error('philsys_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="profession_occupation">Occupation/Profession</label>
                            <input type="text" class="form-control @error('profession_occupation') is-invalid @enderror" id="profession_occupation" name="profession_occupation" value="{{ old('profession_occupation', $resident->profession_occupation) }}">
                            @error('profession_occupation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="monthly_income">Monthly Income</label>
                            <input type="number" step="0.01" class="form-control @error('monthly_income') is-invalid @enderror" id="monthly_income" name="monthly_income" value="{{ old('monthly_income', $resident->monthly_income) }}">
                            @error('monthly_income')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="educational_attainment">Highest Educational Attainment</label>
                            <select class="form-control @error('educational_attainment') is-invalid @enderror" id="educational_attainment" name="educational_attainment">
                                <option value="">-- Select Attainment --</option>
                                <option value="Elementary" {{ $resident->educational_attainment == 'Elementary' ? 'selected' : '' }}>Elementary</option>
                                <option value="Highschool" {{ $resident->educational_attainment == 'Highschool' ? 'selected' : '' }}>Highschool</option>
                                <option value="College" {{ $resident->educational_attainment == 'College' ? 'selected' : '' }}>College</option>
                                <option value="Post Graduate" {{ $resident->educational_attainment == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                                <option value="Vocational" {{ $resident->educational_attainment == 'Vocational' ? 'selected' : '' }}>Vocational</option>
                                <option value="not applicable" {{ $resident->educational_attainment == 'not applicable' ? 'selected' : '' }}>Not Applicable</option>
                            </select>
                            @error('educational_attainment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="education_status">Education Status</label>
                            <select class="form-control @error('education_status') is-invalid @enderror" id="education_status" name="education_status">
                                <option value="">-- Select Status --</option>
                                <option value="Graduate" {{ $resident->education_status == 'Graduate' ? 'selected' : '' }}>Graduate</option>
                                <option value="Undergraduate" {{ $resident->education_status == 'Undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                                <option value="not applicable" {{ $resident->education_status == 'not applicable' ? 'selected' : '' }}>Not Applicable</option>
                            </select>
                            @error('education_status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Household Information Card (Collapsible) -->
            <div class="card shadow mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center" style="cursor: pointer;" data-toggle="collapse" data-target="#householdSection" aria-expanded="false">
                    <h6 class="card-title mb-0"><i class="fe fe-home fe-16 mr-2"></i>Household Information</h6>
                    <button type="button" class="btn btn-sm btn-link p-0">
                        <i class="fe fe-chevron-down household-toggle-icon"></i>
                    </button>
                </div>
                <div class="collapse" id="householdSection">
                    <div class="card-body">
                        <!-- Primary and Secondary Person Information -->
                        <div class="row mb-4">
                            <!-- Primary Person Information -->
                            <div class="col-md-6 mb-4">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="text-primary mb-0">Primary Person</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="primary_name">Full Name</label>
                                                <input type="text" class="form-control @error('household.primary_name') is-invalid @enderror" id="primary_name" name="household[primary_name]" 
                                                    value="{{ old('household.primary_name', $resident->household->primary_name ?? '') }}" required>
                                                @error('household.primary_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="primary_birthday">Birthday</label>
                                                <input type="date" class="form-control @error('household.primary_birthday') is-invalid @enderror" id="primary_birthday" name="household[primary_birthday]"
                                                    value="{{ old('household.primary_birthday', $resident->household && $resident->household->primary_birthday ? $resident->household->primary_birthday->format('Y-m-d') : '') }}">
                                                @error('household.primary_birthday')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="primary_gender">Gender</label>
                                                <select class="form-control @error('household.primary_gender') is-invalid @enderror" id="primary_gender" name="household[primary_gender]">
                                                    <option value="">-- Select Gender --</option>
                                                    <option value="Male" {{ $resident->household && $resident->household->primary_gender == 'Male' ? 'selected' : '' }}>Male</option>
                                                    <option value="Female" {{ $resident->household && $resident->household->primary_gender == 'Female' ? 'selected' : '' }}>Female</option>
                                                    <option value="Non-binary" {{ $resident->household && $resident->household->primary_gender == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                                                    <option value="Transgender" {{ $resident->household && $resident->household->primary_gender == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                                                    <option value="Other" {{ $resident->household && $resident->household->primary_gender == 'Other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                                @error('household.primary_gender')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="primary_phone">Contact Number</label>
                                                <input type="text" class="form-control @error('household.primary_phone') is-invalid @enderror" id="primary_phone" name="household[primary_phone]"
                                                    value="{{ old('household.primary_phone', $resident->household->primary_phone ?? '') }}"
                                                    pattern="[0-9]{11}" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                @error('household.primary_phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="primary_work">Occupation</label>
                                                <input type="text" class="form-control @error('household.primary_work') is-invalid @enderror" id="primary_work" name="household[primary_work]"
                                                    value="{{ old('household.primary_work', $resident->household->primary_work ?? '') }}">
                                                @error('household.primary_work')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="primary_allergies">Allergies</label>
                                                <input type="text" class="form-control @error('household.primary_allergies') is-invalid @enderror" id="primary_allergies" name="household[primary_allergies]"
                                                    value="{{ old('household.primary_allergies', $resident->household->primary_allergies ?? '') }}">
                                                @error('household.primary_allergies')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="primary_medical_condition">Medical Conditions</label>
                                                <input type="text" class="form-control @error('household.primary_medical_condition') is-invalid @enderror" id="primary_medical_condition" name="household[primary_medical_condition]"
                                                    value="{{ old('household.primary_medical_condition', $resident->household->primary_medical_condition ?? '') }}">
                                                @error('household.primary_medical_condition')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Secondary Person Information -->
                            <div class="col-md-6 mb-4">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="text-primary mb-0">Secondary Person</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label for="secondary_name">Full Name</label>
                                                <input type="text" class="form-control @error('household.secondary_name') is-invalid @enderror" id="secondary_name" name="household[secondary_name]"
                                                    value="{{ old('household.secondary_name', $resident->household->secondary_name ?? '') }}">
                                                @error('household.secondary_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="secondary_birthday">Birthday</label>
                                                <input type="date" class="form-control @error('household.secondary_birthday') is-invalid @enderror" id="secondary_birthday" name="household[secondary_birthday]"
                                                    value="{{ old('household.secondary_birthday', $resident->household && $resident->household->secondary_birthday ? $resident->household->secondary_birthday->format('Y-m-d') : '') }}">
                                                @error('household.secondary_birthday')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="secondary_gender">Gender</label>
                                                <select class="form-control @error('household.secondary_gender') is-invalid @enderror" id="secondary_gender" name="household[secondary_gender]">
                                                    <option value="">-- Select Gender --</option>
                                                    <option value="Male" {{ $resident->household && $resident->household->secondary_gender == 'Male' ? 'selected' : '' }}>Male</option>
                                                    <option value="Female" {{ $resident->household && $resident->household->secondary_gender == 'Female' ? 'selected' : '' }}>Female</option>
                                                    <option value="Non-binary" {{ $resident->household && $resident->household->secondary_gender == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                                                    <option value="Transgender" {{ $resident->household && $resident->household->secondary_gender == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                                                    <option value="Other" {{ $resident->household && $resident->household->secondary_gender == 'Other' ? 'selected' : '' }}>Other</option>
                                                </select>
                                                @error('household.secondary_gender')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="secondary_phone">Contact Number</label>
                                                <input type="text" class="form-control @error('household.secondary_phone') is-invalid @enderror" id="secondary_phone" name="household[secondary_phone]"
                                                    value="{{ old('household.secondary_phone', $resident->household->secondary_phone ?? '') }}"
                                                    pattern="[0-9]{11}" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                @error('household.secondary_phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="secondary_work">Occupation</label>
                                                <input type="text" class="form-control @error('household.secondary_work') is-invalid @enderror" id="secondary_work" name="household[secondary_work]"
                                                    value="{{ old('household.secondary_work', $resident->household->secondary_work ?? '') }}">
                                                @error('household.secondary_work')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="secondary_allergies">Allergies</label>
                                                <input type="text" class="form-control @error('household.secondary_allergies') is-invalid @enderror" id="secondary_allergies" name="household[secondary_allergies]"
                                                    value="{{ old('household.secondary_allergies', $resident->household->secondary_allergies ?? '') }}">
                                                @error('household.secondary_allergies')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="secondary_medical_condition">Medical Conditions</label>
                                                <input type="text" class="form-control @error('household.secondary_medical_condition') is-invalid @enderror" id="secondary_medical_condition" name="household[secondary_medical_condition]"
                                                    value="{{ old('household.secondary_medical_condition', $resident->household->secondary_medical_condition ?? '') }}">
                                                @error('household.secondary_medical_condition')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Emergency Contact Information -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="text-primary mb-0">Emergency Contact</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="emergency_contact_name">Full Name</label>
                                                <input type="text" class="form-control @error('household.emergency_contact_name') is-invalid @enderror" id="emergency_contact_name" name="household[emergency_contact_name]"
                                                    value="{{ old('household.emergency_contact_name', $resident->household->emergency_contact_name ?? '') }}">
                                                @error('household.emergency_contact_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="emergency_relationship">Relationship</label>
                                                <input type="text" class="form-control @error('household.emergency_relationship') is-invalid @enderror" id="emergency_relationship" name="household[emergency_relationship]"
                                                    value="{{ old('household.emergency_relationship', $resident->household->emergency_relationship ?? '') }}">
                                                @error('household.emergency_relationship')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="emergency_work">Occupation</label>
                                                <input type="text" class="form-control @error('household.emergency_work') is-invalid @enderror" id="emergency_work" name="household[emergency_work]"
                                                    value="{{ old('household.emergency_work', $resident->household->emergency_work ?? '') }}">
                                                @error('household.emergency_work')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-2 mb-3">
                                                <label for="emergency_phone">Contact Number</label>
                                                <input type="text" class="form-control @error('household.emergency_phone') is-invalid @enderror" id="emergency_phone" name="household[emergency_phone]"
                                                    value="{{ old('household.emergency_phone', $resident->household->emergency_phone ?? '') }}"
                                                    pattern="[0-9]{11}" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                @error('household.emergency_phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Family Members Card (Also Collapsible) -->
            <div class="card shadow mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center" style="cursor: pointer;" data-toggle="collapse" data-target="#familyMembersSection" aria-expanded="false">
                    <h6 class="card-title mb-0"><i class="fe fe-users fe-16 mr-2"></i>Family Members</h6>
                    <div>
                        <button type="button" class="btn btn-sm btn-primary mr-2" id="addFamilyMemberBtn" style="display:none;">
                            <i class="fe fe-plus mr-1"></i> Add Family Member
                        </button>
                        <i class="fe fe-chevron-down family-toggle-icon"></i>
                    </div>
                </div>
                <div class="collapse" id="familyMembersSection">
                    <div class="card-body">
                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-primary" id="addFamilyMemberBtnInside">
                                <i class="fe fe-plus mr-1"></i> Add Family Member
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="familyMembersTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Relationship</th>
                                        <th>Related To</th>
                                        <th>Gender</th>
                                        <th>Birthday</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="familyMembersList">
                                    @if(isset($resident->familyMembers) && $resident->familyMembers->count() > 0)
                                        @foreach($resident->familyMembers as $index => $member)
                                            <tr class="family-member-row">
                                                <td>
                                                    <input type="hidden" name="family_members[{{ $index }}][id]" value="{{ $member->id }}">
                                                    <input type="text" class="form-control name-field" name="family_members[{{ $index }}][name]" value="{{ $member->name }}" required>
                                                </td>
                                                <td>
                                                    <select class="form-control" name="family_members[{{ $index }}][relationship]" required>
                                                        <option value="">Select</option>
                                                        <option value="Spouse" {{ $member->relationship == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                                                        <option value="Child" {{ $member->relationship == 'Child' ? 'selected' : '' }}>Child</option>
                                                        <option value="Parent" {{ $member->relationship == 'Parent' ? 'selected' : '' }}>Parent</option>
                                                        <option value="Sibling" {{ $member->relationship == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                                                        <option value="Grandparent" {{ $member->relationship == 'Grandparent' ? 'selected' : '' }}>Grandparent</option>
                                                        <option value="Grandchild" {{ $member->relationship == 'Grandchild' ? 'selected' : '' }}>Grandchild</option>
                                                        <option value="In-Law" {{ $member->relationship == 'In-Law' ? 'selected' : '' }}>In-Law</option>
                                                        <option value="Other" {{ $member->relationship == 'Other' ? 'selected' : '' }}>Other Relative</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control" name="family_members[{{ $index }}][related_to]">
                                                        <option value="">-- Select --</option>
                                                        <option value="primary" {{ $member->related_to == 'primary' ? 'selected' : '' }}>Primary</option>
                                                        <option value="secondary" {{ $member->related_to == 'secondary' ? 'selected' : '' }}>Secondary</option>
                                                        <option value="both" {{ $member->related_to == 'both' ? 'selected' : '' }}>Both</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-control @error('family_members.'.$index.'.gender') is-invalid @enderror" name="family_members[{{ $index }}][gender]">
                                                        <option value="">-- Select Gender --</option>
                                                        <option value="Male" {{ $member->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                                        <option value="Female" {{ $member->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                                        <option value="Non-binary" {{ $member->gender == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                                                        <option value="Transgender" {{ $member->gender == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                                                        <option value="Other" {{ $member->gender == 'Other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="date" class="form-control birth-date-field" name="family_members[{{ $index }}][birthday]" 
                                                        value="{{ $member->birthday ? $member->birthday->format('Y-m-d') : '' }}">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-danger remove-family-member">
                                                        <i class="fe fe-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr class="family-member-row-details">
                                                <td colspan="6">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-2">
                                                            <label>Occupation</label>
                                                            <input type="text" class="form-control" name="family_members[{{ $index }}][work]" 
                                                                value="{{ $member->work }}">
                                                        </div>
                                                        <div class="col-md-3 mb-2">
                                                            <label>Contact Number (optional)</label>
                                                            <input type="text" class="form-control phone-field" name="family_members[{{ $index }}][contact_number]" 
                                                                value="{{ $member->contact_number }}"
                                                                pattern="[0-9]{11}" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                            <div class="invalid-feedback">Phone number must be exactly 11 digits</div>
                                                        </div>
                                                        <div class="col-md-2 mb-2">
                                                            <label>Allergies</label>
                                                            <input type="text" class="form-control" name="family_members[{{ $index }}][allergies]" 
                                                                value="{{ $member->allergies }}">
                                                        </div>
                                                        <div class="col-md-3 mb-2">
                                                            <label>Medical Conditions</label>
                                                            <input type="text" class="form-control" name="family_members[{{ $index }}][medical_condition]" 
                                                                value="{{ $member->medical_condition }}">
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr id="noFamilyMembersRow">
                                            <td colspan="6" class="text-center">No family members added yet</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons Card -->
            <div class="card shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.residents.index') }}" class="btn btn-outline-secondary mr-2">
                            <i class="fe fe-x fe-16 mr-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fe fe-save fe-16 mr-2"></i>Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this resident? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.residents.destroy', $resident) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize family member index
        let familyMemberIndex = {{ isset($resident->familyMembers) && $resident->familyMembers->count() > 0 ? $resident->familyMembers->count() : 0 }};
        
        // Toggle icons for household section
        $('#householdSection').on('show.bs.collapse', function() {
            $('.household-toggle-icon').removeClass('fe-chevron-down').addClass('fe-chevron-up');
        }).on('hide.bs.collapse', function() {
            $('.household-toggle-icon').removeClass('fe-chevron-up').addClass('fe-chevron-down');
        });
        
        // Toggle icons for family members section
        $('#familyMembersSection').on('show.bs.collapse', function() {
            $('.family-toggle-icon').removeClass('fe-chevron-down').addClass('fe-chevron-up');
        }).on('hide.bs.collapse', function() {
            $('.family-toggle-icon').removeClass('fe-chevron-up').addClass('fe-chevron-down');
        });
        
        // Add new family member from the outside button
        $('#addFamilyMemberBtn').click(function() {
            // Open the section if it's collapsed
            $('#familyMembersSection').collapse('show');
            addFamilyMemberRow();
        });
        
        // Add new family member from the inside button
        $('#addFamilyMemberBtnInside').click(function() {
            addFamilyMemberRow();
        });
        
        // Remove family member
        $(document).on('click', '.remove-family-member', function() {
            const row = $(this).closest('.family-member-row');
            const detailsRow = row.next('.family-member-row-details');
            
            row.remove();
            detailsRow.remove();
            
            // Show 'no members' message if no members left
            if ($('#familyMembersList tr.family-member-row').length === 0) {
                $('#familyMembersList').append(`
                    <tr id="noFamilyMembersRow">
                        <td colspan="6" class="text-center">No family members added yet</td>
                    </tr>
                `);
            }
        });
        
        // Add required field indicators
        function addRequiredFieldMarkers() {
            const requiredFields = [
                '#first_name', '#last_name', '#birthdate', '#birthplace', '#address', 
                '#contact_number', '#email_address', '#type_of_resident', 
                '#sex', '#civil_status', '#citizenship_type'
            ];
            
            requiredFields.forEach(field => {
                const label = $(field).prev('label');
                if (label.length && !label.find('.required-marker').length) {
                    // Replace any existing asterisks in the label
                    let labelText = label.html().replace(/<span class="text-danger">\*<\/span>/g, '');
                    labelText = labelText.replace(/\*/g, ''); // Remove any stray asterisks
                    
                    // Add the marker with a single asterisk
                    label.html(labelText + ' <span class="required-marker text-danger">*</span>');
                }
            });
        }
        
        // Name validation - allow only letters, spaces, dots, hyphens, and apostrophes
        function validateNameField($field) {
            const value = $field.val();
            if (value) {
                const nameRegex = /^[a-zA-Z\s\.\-\']+$/;
                if (!nameRegex.test(value)) {
                    $field.addClass('is-invalid');
                    if (!$field.next('.invalid-feedback').length) {
                        $field.after('<div class="invalid-feedback">Name may only contain letters, spaces, dots, hyphens, and apostrophes</div>');
                    }
                    return false;
                }
            }
            $field.removeClass('is-invalid');
            return true;
        }
        
        // Email validation with stricter pattern
        function validateEmailField($field) {
            const value = $field.val();
            if (value) {
                const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (!emailRegex.test(value)) {
                    $field.addClass('is-invalid');
                    if (!$field.next('.invalid-feedback').length) {
                        $field.after('<div class="invalid-feedback">Please enter a valid email format</div>');
                    }
                    return false;
                }
            }
            $field.removeClass('is-invalid');
            return true;
        }
        
        // Phone number validation function
        function validatePhoneNumber(phone) {
            // Only accept exactly 11 numeric digits (e.g., 09123456789)
            const phoneRegex = /^\d{11}$/;
            return phoneRegex.test(phone);
        }
        
        // Validate phone field
        function validatePhoneField($field) {
            const value = $field.val();
            if (value) {
                // If not empty, ensure it's 11 digits
                if (!validatePhoneNumber(value)) {
                    $field.addClass('is-invalid');
                    if (!$field.next('.invalid-feedback').length) {
                        $field.after('<div class="invalid-feedback">Phone number must be exactly 11 digits</div>');
                    }
                    return false;
                } else {
                    $field.removeClass('is-invalid');
                    return true;
                }
            }
            return true; // Empty is fine if not required
        }
        
        // Birthday validation with age range check
        function validateBirthdayField($field) {
            const value = $field.val();
            if (value) {
                const selectedDate = new Date(value);
                const today = new Date();
                
                // Check if date is in the future
                if (selectedDate > today) {
                    $field.addClass('is-invalid');
                    if (!$field.next('.invalid-feedback').length) {
                        $field.after('<div class="invalid-feedback">Birthday cannot be in the future</div>');
                    }
                    return false;
                }
                
                // Check if date is too far in the past (e.g., more than 120 years)
                const minDate = new Date();
                minDate.setFullYear(today.getFullYear() - 120);
                if (selectedDate < minDate) {
                    $field.addClass('is-invalid');
                    if (!$field.next('.invalid-feedback').length) {
                        $field.after('<div class="invalid-feedback">Please enter a valid birthdate (not older than 120 years)</div>');
                    }
                    return false;
                }
            }
            $field.removeClass('is-invalid');
            return true;
        }
        
        // Numeric validation for income fields
        function validateNumericField($field) {
            const value = $field.val();
            if (value) {
                if (isNaN(value) || parseFloat(value) < 0) {
                    $field.addClass('is-invalid');
                    if (!$field.next('.invalid-feedback').length) {
                        $field.after('<div class="invalid-feedback">Please enter a valid positive number</div>');
                    }
                    return false;
                }
            }
            $field.removeClass('is-invalid');
            return true;
        }
        
        // Address validation
        function validateAddressField($field) {
            const value = $field.val();
            if (value) {
                if (value.length < 5) {
                    $field.addClass('is-invalid');
                    if (!$field.next('.invalid-feedback').length) {
                        $field.after('<div class="invalid-feedback">Address should be at least 5 characters</div>');
                    }
                    return false;
                }
                
                // Check for potentially dangerous characters
                const dangerousPattern = /[<>{}]/g;
                if (dangerousPattern.test(value)) {
                    $field.addClass('is-invalid');
                    if (!$field.next('.invalid-feedback').length) {
                        $field.after('<div class="invalid-feedback">Address contains invalid characters</div>');
                    }
                    return false;
                }
            }
            $field.removeClass('is-invalid');
            isValid = validateAddressField($('#address')) && isValid;
            
            if ($('#monthly_income').val()) {
                isValid = validateNumericField($('#monthly_income')) && isValid;
            }
            
            // Validate household info if it exists
            if ($('#primary_name').val()) {
                isValid = validateNameField($('#primary_name')) && isValid;
                
                if ($('#primary_phone').val()) {
                    isValid = validatePhoneField($('#primary_phone')) && isValid;
                }
            }
            
            if ($('#secondary_name').val()) {
                isValid = validateNameField($('#secondary_name')) && isValid;
                
                if ($('#secondary_phone').val()) {
                    isValid = validatePhoneField($('#secondary_phone')) && isValid;
                }
                
                // Cross-field validation - if secondary name exists, gender is required
                if (!$('#secondary_gender').val()) {
                    $('#secondary_gender').addClass('is-invalid');
                    if (!$('#secondary_gender').next('.invalid-feedback').length) {
                        $('#secondary_gender').after('<div class="invalid-feedback">Gender is required when secondary person is specified</div>');
                    }
                    isValid = false;
                }
            }
            
            // Validate cross-field relationships
            isValid = validateRelatedFields() && isValid;
            
            return isValid;
        }
        
        // Text field sanitization to prevent script injection
        function sanitizeInput(input) {
            // Store the current cursor position
            const start = input.selectionStart;
            const end = input.selectionEnd;
            
            // Get the value before sanitization
            const originalValue = input.value;
            
            // Sanitize the value (remove HTML tags and script patterns)
            const sanitizedValue = originalValue.replace(/<\/?[^>]+(>|$)/g, "");
            
            // Only update and highlight if value changed
            if (originalValue !== sanitizedValue) {
                // Update the input value
                input.value = sanitizedValue;
                
                // Add highlighting effect
                $(input).addClass('sanitized-field');
                setTimeout(() => {
                    $(input).removeClass('sanitized-field');
                }, 1000);
                
                // Restore cursor position, accounting for removed characters
                const diff = originalValue.length - sanitizedValue.length;
                input.setSelectionRange(start - diff, end - diff);
            }
        }
        
        // Apply input masking to name fields
        function applyNameInputMasks() {
            const nameFields = $('input[name="first_name"], input[name="last_name"], input[name="middle_name"], input[name*="name"]');
            
            nameFields.on('input', function(e) {
                // Replace any character that isn't a letter, space, dot, hyphen, or apostrophe
                this.value = this.value.replace(/[^a-zA-Z\s\.\-\']/g, '');
            });
        }
        
        // Apply input masking to phone fields
        function applyPhoneInputMasks() {
            $('input[type="text"][name*="phone"], input[type="text"][name*="contact_number"]').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').substring(0, 11);
            });
        }
        
        // Apply validation to existing fields
        function applyValidationToExistingFields() {
            // Add required field markers
            addRequiredFieldMarkers();
            
            // Name fields
            $('input[name="first_name"], input[name="last_name"], input[name="middle_name"], input[name*="name"]').each(function() {
                $(this).on('blur', function() {
                    validateNameField($(this));
                }).on('input', function() {
                    sanitizeInput(this);
                });
            });
            
            // Email field
            $('input[name="email_address"]').on('blur', function() {
                validateEmailField($(this));
            }).on('input', function() {
                sanitizeInput(this);
            });
            
            // Phone number fields
            $('input[type="text"][name*="phone"], input[type="text"][name*="contact_number"]').on('blur', function() {
                validatePhoneField($(this));
            });
            
            // Birthday fields
            $('input[type="date"]').each(function() {
                $(this).on('change', function() {
                    validateBirthdayField($(this));
                });
            });
            
            // Numeric fields
            $('#monthly_income').on('blur', function() {
                validateNumericField($(this));
            });
            
            // Address field
            $('#address').on('blur', function() {
                validateAddressField($(this));
            }).on('input', function() {
                sanitizeInput(this);
            });
            
            // Apply input masks
            applyNameInputMasks();
            applyPhoneInputMasks();
        }
        
        // Function to add a new family member row with enhanced validation
        function addFamilyMemberRow() {
            // Remove 'no members' message if present
            $('#noFamilyMembersRow').remove();
            
            // Create new family member row
            const newRow = `
                <tr class="family-member-row">
                    <td>
                        <input type="text" class="form-control name-field" name="family_members[${familyMemberIndex}][name]" required>
                    </td>
                    <td>
                        <select class="form-control" name="family_members[${familyMemberIndex}][relationship]" required>
                            <option value="">Select</option>
                            <option value="Spouse">Spouse</option>
                            <option value="Child">Child</option>
                            <option value="Parent">Parent</option>
                            <option value="Sibling">Sibling</option>
                            <option value="Grandparent">Grandparent</option>
                            <option value="Grandchild">Grandchild</option>
                            <option value="In-Law">In-Law</option>
                            <option value="Other">Other Relative</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control" name="family_members[${familyMemberIndex}][related_to]">
                            <option value="">-- Select --</option>
                            <option value="primary">Primary</option>
                            <option value="secondary">Secondary</option>
                            <option value="both">Both</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-control" name="family_members[${familyMemberIndex}][gender]" required>
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </td>
                    <td>
                        <input type="date" class="form-control birth-date-field" name="family_members[${familyMemberIndex}][birthday]">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-family-member">
                            <i class="fe fe-trash"></i>
                        </button>
                    </td>
                </tr>
                <tr class="family-member-row-details">
                    <td colspan="6">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label>Occupation</label>
                                <input type="text" class="form-control" name="family_members[${familyMemberIndex}][work]">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label>Contact Number (optional)</label>
                                <input type="text" class="form-control phone-field" name="family_members[${familyMemberIndex}][contact_number]">
                            </div>
                            <div class="col-md-2 mb-2">
                                <label>Allergies</label>
                                <input type="text" class="form-control" name="family_members[${familyMemberIndex}][allergies]">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label>Medical Conditions</label>
                                <input type="text" class="form-control" name="family_members[${familyMemberIndex}][medical_condition]">
                            </div>
                        </div>
                    </td>
                </tr>
            `;
            
            $('#familyMembersList').append(newRow);
            
            // Apply validation to new fields
            const newRow$ = $('.family-member-row').last();
            newRow$.find('.name-field').on('blur', function() {
                validateNameField($(this));
            }).on('input', function() {
                sanitizeInput(this);
                this.value = this.value.replace(/[^a-zA-Z\s\.\-\']/g, '');
            });
            
            newRow$.find('.phone-field').on('blur', function() {
                validatePhoneField($(this));
            }).on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').substring(0, 11);
            });
            
            newRow$.find('.birth-date-field').on('change', function() {
                validateBirthdayField($(this));
            });
            
            familyMemberIndex++;
        }
        
        // Handle form submission with enhanced validation
        $('#residentUpdateForm').on('submit', function(e) {
            if (!validateAllFields()) {
                e.preventDefault();
                e.stopPropagation();
                
                // If there are errors in the collapsed sections, expand them
                if ($('#familyMembersList .is-invalid').length > 0) {
                    $('#familyMembersSection').collapse('show');
                }
                
                if ($('#householdSection .is-invalid').length > 0) {
                    $('#householdSection').collapse('show');
                }
                
                // Scroll to first error
                if ($('.is-invalid').length > 0) {
                    $('html, body').animate({
                        scrollTop: $('.is-invalid:first').offset().top - 100
                    }, 500);
                    
                    // Focus the first invalid field
                    $('.is-invalid:first').focus();
                }
                
                return false;
            }
        });
        
        // Ensure form validation expands sections with errors on page load
        function handleInitialErrors() {
            // Check if there are errors
            if ($('.is-invalid').length > 0 || $('.alert-danger').length > 0) {
                // Expand sections that contain errors
                if ($('#familyMembersList .is-invalid').length > 0) {
                    $('#familyMembersSection').collapse('show');
                }
                
                if ($('#householdSection .is-invalid').length > 0) {
                    $('#householdSection').collapse('show');
                }
                
                // Scroll to the first error after a slight delay to ensure expanded sections are visible
                setTimeout(() => {
                    if ($('.is-invalid').length > 0) {
                        $('html, body').animate({
                            scrollTop: $('.is-invalid:first').offset().top - 100
                        }, 500);
                    } else if ($('.alert-danger').length > 0) {
                        $('html, body').animate({
                            scrollTop: $('.alert-danger:first').offset().top - 100
                        }, 500);
                    }
                }, 300);
            }
        }
        
        // Run the error handler on page load
        handleInitialErrors();
        
        // Initialize form validation
        applyValidationToExistingFields();
    });
</script>
@endpush

@push('styles')
<style>
    .avatar {
        position: relative;
        width: 100px;
        height: 100px;
        margin: 0 auto;
    }
    
    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .avatar-letter {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        color: white;
        font-size: 36px;
        font-weight: bold;
    }
    
    /* Validation indicators */
    .is-invalid {
        border-color: #dc3545 !important;
    }
    
    .invalid-feedback {
        display: block;
    }
    
    .required-marker {
        font-size: 0.875rem;
        vertical-align: super;
    }
    
    .sanitized-field {
        animation: highlightSanitized 1s ease;
    }
    
    @keyframes highlightSanitized {
        0% { background-color: rgba(220, 53, 69, 0.1); }
        100% { background-color: transparent; }
    }
</style>
@endpush