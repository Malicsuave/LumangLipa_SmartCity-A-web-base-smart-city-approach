@extends('layouts.public.resident-registration')

@section('title', 'Senior Citizen Pre-Registration - Step 1')

@section('form-title', 'Senior Citizen Pre-Registration')
@section('step-indicator', 'Step 1 of 5')

@section('form-content')
<div class="card-header bg-white border-0 pb-0">
  <h5 class="personal-header"><i class="fas fa-user mr-2"></i>Personal Information</h5>
  <div class="mt-2 mb-2 text-muted">
    <i class="fas fa-info-circle mr-2"></i><strong>Age Requirement:</strong> Applicant must be 60 years old or above to register as a senior citizen.
  </div>
  <small class="text-muted">Please provide accurate information. Fields marked with <span class="text-danger">*</span> are required.</small>
</div>
          <form role="form" id="seniorPreRegStep1Form" method="POST" action="{{ route('public.senior-registration.step1.store') }}" autocomplete="off">
            @csrf
            <div class="card-body">
              <!-- Type of Resident -->
              <div class="row mb-4">
                <div class="col-md-12">
                  <label for="type_of_resident">Type of Resident <span class="text-danger">*</span></label>
                  <select class="form-control custom-rounded-input" 
                          id="type_of_resident" 
                          name="type_of_resident" 
                          required>
                    <option value="">Select type of resident</option>
                    <option value="Non-Migrant" {{ old('type_of_resident', $step1['type_of_resident'] ?? '') == 'Non-Migrant' ? 'selected' : '' }}>Non-Migrant</option>
                    <option value="Migrant" {{ old('type_of_resident', $step1['type_of_resident'] ?? '') == 'Migrant' ? 'selected' : '' }}>Migrant</option>
                    <option value="Transient" {{ old('type_of_resident', $step1['type_of_resident'] ?? '') == 'Transient' ? 'selected' : '' }}>Transient</option>
                  </select>
                  <div class="validation-error" data-field="type_of_resident" style="display: none; color: #dc3545; font-size: 0.875em; margin-top: 0.25rem;"></div>
                </div>
              </div>
              <!-- Name Fields -->
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="first_name">First Name <span class="text-danger">*</span></label>
                  <input type="text" 
                         name="first_name" 
                         id="first_name" 
                         class="form-control custom-rounded-input"
                         placeholder="First Name"
                         value="{{ old('first_name', $step1['first_name'] ?? '') }}" 
                         required>
                  <div class="validation-error" data-field="first_name" style="display: none; color: #dc3545; font-size: 0.875em; margin-top: 0.25rem;"></div>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="middle_name">Middle Name</label>
                  <input type="text" 
                         name="middle_name" 
                         id="middle_name" 
                         class="form-control custom-rounded-input optional-field"
                         placeholder="Middle Name"
                         value="{{ old('middle_name', $step1['middle_name'] ?? '') }}">
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="last_name">Last Name <span class="text-danger">*</span></label>
                  <input type="text" 
                         name="last_name" 
                         id="last_name" 
                         class="form-control custom-rounded-input"
                         placeholder="Last Name"
                         value="{{ old('last_name', $step1['last_name'] ?? '') }}" 
                         required>
                  <div class="validation-error" data-field="last_name" style="display: none; color: #dc3545; font-size: 0.875em; margin-top: 0.25rem;"></div>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="suffix">Suffix</label>
                  <input type="text" 
                         name="suffix" 
                         id="suffix" 
                         class="form-control custom-rounded-input optional-field"
                         placeholder="Suffix (Jr., Sr., III, etc.)"
                         value="{{ old('suffix', $step1['suffix'] ?? '') }}">
                </div>
              </div>
              <!-- Birth Information -->
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="birthdate">Date of Birth <span class="text-danger">*</span></label>
                  <input type="date" 
                         name="birthdate" 
                         id="birthdate" 
                         class="form-control custom-rounded-input" 
                         value="{{ old('birthdate', $step1['birthdate'] ?? '') }}" 
                         required>
                  <div class="validation-error" data-field="birthdate" style="display: none; color: #dc3545; font-size: 0.875em; margin-top: 0.25rem;"></div>
                  <small class="form-text text-muted">You must be at least 60 years old</small>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="birthplace">Place of Birth <span class="text-danger">*</span></label>
                  <input type="text" 
                         name="birthplace" 
                         id="birthplace" 
                         class="form-control custom-rounded-input" 
                         placeholder="City/Municipality, Province"
                         value="{{ old('birthplace', $step1['birthplace'] ?? '') }}" 
                         required>
                  <div class="validation-error" data-field="birthplace" style="display: none; color: #dc3545; font-size: 0.875em; margin-top: 0.25rem;"></div>
                </div>
              </div>
              <!-- Gender and Civil Status -->
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="sex">Gender <span class="text-danger">*</span></label>
                  <select class="form-control custom-rounded-input" 
                          id="sex" 
                          name="sex" 
                          required>
                    <option value="">Select gender</option>
                    <option value="Male" {{ old('sex', $step1['sex'] ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('sex', $step1['sex'] ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Non-binary" {{ old('sex', $step1['sex'] ?? '') == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                    <option value="Transgender" {{ old('sex', $step1['sex'] ?? '') == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                    <option value="Other" {{ old('sex', $step1['sex'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                  </select>
                  <div class="validation-error" data-field="sex" style="display: none; color: #dc3545; font-size: 0.875em; margin-top: 0.25rem;"></div>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="civil_status">Civil Status <span class="text-danger">*</span></label>
                                    <select class="form-control custom-rounded-input" 
                          id="civil_status" 
                          name="civil_status" 
                          required>
                    <option value="">Select civil status</option>
                    <option value="Single" {{ old('civil_status', $step1['civil_status'] ?? '') == 'Single' ? 'selected' : '' }}>Single</option>
                    <option value="Married" {{ old('civil_status', $step1['civil_status'] ?? '') == 'Married' ? 'selected' : '' }}>Married</option>
                    <option value="Widowed" {{ old('civil_status', $step1['civil_status'] ?? '') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                    <option value="Separated" {{ old('civil_status', $step1['civil_status'] ?? '') == 'Separated' ? 'selected' : '' }}>Separated</option>
                    <option value="Divorced" {{ old('civil_status', $step1['civil_status'] ?? '') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                  </select>
                  <div class="validation-error" data-field="civil_status" style="display: none; color: #dc3545; font-size: 0.875em; margin-top: 0.25rem;"></div>
                </div>
              </div>
              <!-- Citizenship Information -->
              <hr>
              <h5 class="citizenship-header"><i class="fas fa-flag mr-2"></i>Citizenship Information</h5>
              <small class="text-muted d-block mb-3">Specify if you are a Filipino citizen by birth or naturalization</small>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="citizenship_type">Citizenship Type <span class="text-danger">*</span></label>
                  <select class="form-control custom-rounded-input" 
                          id="citizenship_type" 
                          name="citizenship_type" 
                          required>
                    <option value="">Select citizenship type</option>
                    <option value="FILIPINO" {{ old('citizenship_type', $step1['citizenship_type'] ?? '') == 'FILIPINO' ? 'selected' : '' }}>Filipino</option>
                    <option value="DUAL" {{ old('citizenship_type', $step1['citizenship_type'] ?? '') == 'DUAL' ? 'selected' : '' }}>Dual Citizen</option>
                    <option value="NATURALIZED" {{ old('citizenship_type', $step1['citizenship_type'] ?? '') == 'NATURALIZED' ? 'selected' : '' }}>Naturalized Filipino</option>
                    <option value="FOREIGN" {{ old('citizenship_type', $step1['citizenship_type'] ?? '') == 'FOREIGN' ? 'selected' : '' }}>Foreign National</option>
                  </select>
                  <div class="validation-error" data-field="citizenship_type" style="display: none; color: #dc3545; font-size: 0.875em; margin-top: 0.25rem;"></div>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="citizenship_country">Country <span class="citizenship-country-required text-danger" style="display: none;">*</span></label>
                  <input type="text" 
                         name="citizenship_country" 
                         id="citizenship_country" 
                         class="form-control custom-rounded-input optional-field" 
                         placeholder="Country of Citizenship"
                         value="{{ old('citizenship_country', $step1['citizenship_country'] ?? '') }}">
                  <div class="validation-error" data-field="citizenship_country" style="display: none; color: #dc3545; font-size: 0.875em; margin-top: 0.25rem;"></div>
                </div>
              </div>
              <!-- Education Information -->
              <hr>
              <h5 class="education-header"><i class="fas fa-graduation-cap mr-2"></i>Education Information</h5>
              <small class="text-muted d-block mb-3">Your highest educational attainment and current status</small>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="educational_attainment">Educational Attainment <span class="text-danger">*</span></label>
                  <select class="form-control custom-rounded-input" 
                          id="educational_attainment" 
                          name="educational_attainment" 
                          required>
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
                  <div class="validation-error" data-field="educational_attainment" style="display: none; color: #dc3545; font-size: 0.875em; margin-top: 0.25rem;"></div>
                </div>
                <div class="col-md-6 mb-3">
                  <label for="education_status">Education Status <span class="text-danger">*</span></label>
                  <select class="form-control custom-rounded-input" 
                          id="education_status" 
                          name="education_status" 
                          required>
                    <option value="">Select education status</option>
                    <option value="Studying" {{ old('education_status', $step1['education_status'] ?? '') == 'Studying' ? 'selected' : '' }}>Studying</option>
                    <option value="Graduated" {{ old('education_status', $step1['education_status'] ?? '') == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                    <option value="Stopped Schooling" {{ old('education_status', $step1['education_status'] ?? '') == 'Stopped Schooling' ? 'selected' : '' }}>Stopped Schooling</option>
                    <option value="Not Applicable" {{ old('education_status', $step1['education_status'] ?? '') == 'Not Applicable' ? 'selected' : '' }}>Not Applicable</option>
                  </select>
                  <div class="validation-error" data-field="education_status" style="display: none; color: #dc3545; font-size: 0.875em; margin-top: 0.25rem;"></div>
                </div>
              </div>
              <!-- Additional Information -->
              <hr>
              <h5 class="additional-header mb-3"><i class="fas fa-info-circle mr-2"></i>Additional Information</h5>
              <small class="text-muted d-block mb-3">Optional: Religion and profession/occupation information</small>
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="religion">Religion</label>
                  <input type="text" name="religion" id="religion" placeholder="Religion"
                    class="form-control custom-rounded-input optional-field" 
                    value="{{ old('religion', $step1['religion'] ?? '') }}">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="profession_occupation">Profession/Occupation</label>
                  <input type="text" name="profession_occupation" id="profession_occupation" placeholder="Profession/Occupation"
                    class="form-control custom-rounded-input optional-field" 
                    value="{{ old('profession_occupation', $step1['profession_occupation'] ?? '') }}">
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 mt-2">
                  <button type="submit" class="btn bg-gradient-dark w-100">Next </button>
                </div>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')

@endpush
@endsection


