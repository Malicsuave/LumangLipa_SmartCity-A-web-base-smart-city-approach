@extends('layouts.public.resident-registration')

@section('title', 'Resident Pre-Registration - Step 1')

@section('form-title', 'Resident Pre-Registration')
@section('step-indicator', 'Step 1 of 4')

@section('form-content')
<div class="card-header bg-white border-0 pb-0">
  <h5 class="personal-header"><i class="fas fa-user mr-2"></i>Personal Information</h5>
</div>
          <form role="form" id="residentPreRegStep1Form" method="POST" action="{{ route('public.pre-registration.step1.store') }}" autocomplete="off">
            @csrf
            <div class="card-body">
              <!-- Type of Resident -->
              <div class="row mb-4">
                <div class="col-md-12">
                  <div class="input-group input-group-static">
                    <label for="type_of_resident">Type of Resident <span class="text-danger">*</span></label>
                    <select class="form-control selectpicker @error('type_of_resident') is-invalid @enderror" id="type_of_resident" name="type_of_resident" required data-style="btn-white">
                      <option value="">Select type of resident</option>
                      <option value="Non-Migrant" {{ old('type_of_resident', $step1['type_of_resident'] ?? '') == 'Non-Migrant' ? 'selected' : '' }}>Non-Migrant</option>
                      <option value="Migrant" {{ old('type_of_resident', $step1['type_of_resident'] ?? '') == 'Migrant' ? 'selected' : '' }}>Migrant</option>
                      <option value="Transient" {{ old('type_of_resident', $step1['type_of_resident'] ?? '') == 'Transient' ? 'selected' : '' }}>Transient</option>
                    </select>
                    @error('type_of_resident')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                  </div>
                </div>
              </div>
              <!-- Name Fields -->
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="first_name">First Name <span class="text-danger">*</span></label>
                  <input type="text" name="first_name" id="first_name" placeholder="First Name"
                    class="form-control @error('first_name') is-invalid @enderror"
                    value="{{ old('first_name', $step1['first_name'] ?? '') }}" required>
                  @error('first_name')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="middle_name">Middle Name</label>
                  <input type="text" name="middle_name" id="middle_name" placeholder="Middle Name"
                    class="form-control @error('middle_name') is-invalid @enderror"
                    value="{{ old('middle_name', $step1['middle_name'] ?? '') }}">
                  @error('middle_name')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="last_name">Last Name <span class="text-danger">*</span></label>
                  <input type="text" name="last_name" id="last_name" placeholder="Last Name"
                    class="form-control @error('last_name') is-invalid @enderror"
                    value="{{ old('last_name', $step1['last_name'] ?? '') }}" required>
                  @error('last_name')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="suffix">Suffix</label>
                  <input type="text" name="suffix" id="suffix" placeholder="Suffix"
                    class="form-control @error('suffix') is-invalid @enderror"
                    value="{{ old('suffix', $step1['suffix'] ?? '') }}">
                  @error('suffix')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
              </div>
              <!-- Birth Information -->
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="birthdate">Date of Birth <span class="text-danger">*</span></label>
                  <input type="date" name="birthdate" id="birthdate" 
                    class="form-control @error('birthdate') is-invalid @enderror" 
                    value="{{ old('birthdate', $step1['birthdate'] ?? '') }}" required>
                  @error('birthdate')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="birthplace">Place of Birth <span class="text-danger">*</span></label>
                  <input type="text" name="birthplace" id="birthplace" placeholder="Place of Birth"
                    class="form-control @error('birthplace') is-invalid @enderror" 
                    value="{{ old('birthplace', $step1['birthplace'] ?? '') }}" required>
                  @error('birthplace')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
              </div>
              <!-- Gender and Civil Status -->
              <div class="row">
                <div class="col-md-6 mb-3">
                  <div class="input-group input-group-static">
                    <label for="sex">Gender <span class="text-danger">*</span></label>
                    <select class="form-control selectpicker @error('sex') is-invalid @enderror" id="sex" name="sex" required data-style="btn-white">
                      <option value="">Select gender</option>
                      <option value="Male" {{ old('sex', $step1['sex'] ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                      <option value="Female" {{ old('sex', $step1['sex'] ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                      <option value="Non-binary" {{ old('sex', $step1['sex'] ?? '') == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                      <option value="Transgender" {{ old('sex', $step1['sex'] ?? '') == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                      <option value="Other" {{ old('sex', $step1['sex'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('sex')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="input-group input-group-static">
                    <label for="civil_status">Civil Status <span class="text-danger">*</span></label>
                    <select class="form-control selectpicker @error('civil_status') is-invalid @enderror" id="civil_status" name="civil_status" required data-style="btn-white">
                      <option value="">Select civil status</option>
                      <option value="Single" {{ old('civil_status', $step1['civil_status'] ?? '') == 'Single' ? 'selected' : '' }}>Single</option>
                      <option value="Married" {{ old('civil_status', $step1['civil_status'] ?? '') == 'Married' ? 'selected' : '' }}>Married</option>
                      <option value="Widowed" {{ old('civil_status', $step1['civil_status'] ?? '') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                      <option value="Separated" {{ old('civil_status', $step1['civil_status'] ?? '') == 'Separated' ? 'selected' : '' }}>Separated</option>
                      <option value="Divorced" {{ old('civil_status', $step1['civil_status'] ?? '') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                    </select>
                    @error('civil_status')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                  </div>
                </div>
              </div>
              <!-- Citizenship Information -->
              <hr>
              <h5 class="citizenship-header"><i class="fas fa-flag mr-2"></i>Citizenship Information</h5>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <div class="input-group input-group-static">
                    <label for="citizenship_type">Citizenship Type <span class="text-danger">*</span></label>
                    <select class="form-control selectpicker @error('citizenship_type') is-invalid @enderror" id="citizenship_type" name="citizenship_type" required data-style="btn-white">
                      <option value="">Select citizenship type</option>
                      <option value="FILIPINO" {{ old('citizenship_type', $step1['citizenship_type'] ?? '') == 'FILIPINO' ? 'selected' : '' }}>Filipino</option>
                      <option value="DUAL" {{ old('citizenship_type', $step1['citizenship_type'] ?? '') == 'DUAL' ? 'selected' : '' }}>Dual Citizen</option>
                      <option value="NATURALIZED" {{ old('citizenship_type', $step1['citizenship_type'] ?? '') == 'NATURALIZED' ? 'selected' : '' }}>Naturalized Filipino</option>
                      <option value="FOREIGN" {{ old('citizenship_type', $step1['citizenship_type'] ?? '') == 'FOREIGN' ? 'selected' : '' }}>Foreign National</option>
                    </select>
                    @error('citizenship_type')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="citizenship_country">Country <span class="citizenship-country-required text-danger" style="display: none;">*</span></label>
                  <input type="text" name="citizenship_country" id="citizenship_country" placeholder="Country"
                    class="form-control @error('citizenship_country') is-invalid @enderror" 
                    value="{{ old('citizenship_country', $step1['citizenship_country'] ?? '') }}">
                  @error('citizenship_country')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
              </div>
              <!-- Education Information -->
              <hr>
              <h5 class="education-header"><i class="fas fa-graduation-cap mr-2"></i>Education Information</h5>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="educational_attainment">Educational Attainment <span class="text-danger">*</span></label>
                  <select class="form-control selectpicker @error('educational_attainment') is-invalid @enderror" id="educational_attainment" name="educational_attainment" required data-style="btn-white">
                    <option value="">Select educational attainment</option>
                    <option value="No Formal Education" {{ old('educational_attainment', $step1['educational_attainment'] ?? '') == 'No Formal Education' ? 'selected' : '' }}>No Formal Education</option>
                    <option value="Elementary Undergraduate" {{ old('educational_attainment', $step1['educational_attainment'] ?? '') == 'Elementary Undergraduate' ? 'selected' : '' }}>Elementary Undergraduate</option>
                    <option value="Elementary Graduate" {{ old('educational_attainment', $step1['educational_attainment'] ?? '') == 'Elementary Graduate' ? 'selected' : '' }}>Elementary Graduate</option>
                    <option value="High School Undergraduate" {{ old('educational_attainment', $step1['educational_attainment'] ?? '') == 'High School Undergraduate' ? 'selected' : '' }}>High School Undergraduate</option>
                    <option value="High School Graduate" {{ old('educational_attainment', $step1['educational_attainment'] ?? '') == 'High School Graduate' ? 'selected' : '' }}>High School Graduate</option>
                    <option value="Vocational/Technical Graduate" {{ old('educational_attainment', $step1['educational_attainment'] ?? '') == 'Vocational/Technical Graduate' ? 'selected' : '' }}>Vocational/Technical Graduate</option>
                    <option value="College Undergraduate" {{ old('educational_attainment', $step1['educational_attainment'] ?? '') == 'College Undergraduate' ? 'selected' : '' }}>College Undergraduate</option>
                    <option value="College Graduate" {{ old('educational_attainment', $step1['educational_attainment'] ?? '') == 'College Graduate' ? 'selected' : '' }}>College Graduate</option>
                    <option value="Post Graduate" {{ old('educational_attainment', $step1['educational_attainment'] ?? '') == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                  </select>
                  @error('educational_attainment')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="education_status">Education Status <span class="text-danger">*</span></label>
                  <select class="form-control selectpicker @error('education_status') is-invalid @enderror" id="education_status" name="education_status" required data-style="btn-white">
                    <option value="">Select education status</option>
                    <option value="Studying" {{ old('education_status', $step1['education_status'] ?? '') == 'Studying' ? 'selected' : '' }}>Studying</option>
                    <option value="Graduated" {{ old('education_status', $step1['education_status'] ?? '') == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                    <option value="Stopped Schooling" {{ old('education_status', $step1['education_status'] ?? '') == 'Stopped Schooling' ? 'selected' : '' }}>Stopped Schooling</option>
                    <option value="Not Applicable" {{ old('education_status', $step1['education_status'] ?? '') == 'Not Applicable' ? 'selected' : '' }}>Not Applicable</option>
                  </select>
                  @error('education_status')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
              </div>
              <!-- Additional Information -->
              <hr>
              <h5 class="additional-header mb-3"><i class="fas fa-info-circle mr-2"></i>Additional Information</h5>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="religion">Religion</label>
                  <input type="text" name="religion" id="religion" placeholder="Religion"
                    class="form-control @error('religion') is-invalid @enderror" 
                    value="{{ old('religion', $step1['religion'] ?? '') }}">
                  @error('religion')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="profession_occupation">Profession/Occupation</label>
                  <input type="text" name="profession_occupation" id="profession_occupation" placeholder="Profession/Occupation"
                    class="form-control @error('profession_occupation') is-invalid @enderror" 
                    value="{{ old('profession_occupation', $step1['profession_occupation'] ?? '') }}">
                  @error('profession_occupation')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 mt-2">
                  <button type="submit" class="btn bg-gradient-dark w-100">Continue to Step 2 <i class="fas fa-arrow-right ml-2"></i></button>
                </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
