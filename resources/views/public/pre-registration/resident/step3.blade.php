@extends('layouts.public.resident-registration')

@section('title', 'Resident Pre-Registration - Step 3')

@section('form-title', 'Resident Pre-Registration')
@section('step-indicator', 'Step 3 of 4')

@section('form-content')
<div class="card-header bg-white border-0 pb-0">
  <h5 class="personal-header"><i class="fas fa-graduation-cap mr-2"></i>Education & Work Information</h5>
</div>
          <form role="form" id="residentPreRegStep3Form" method="POST" action="{{ route('public.pre-registration.step3.store') }}" autocomplete="off">
            @csrf
            <div class="card-body">
              <!-- Education Information -->
              <div class="row mb-4">
                <div class="col-md-6 mb-3">
                  <label for="educational_attainment">Educational Attainment <span class="text-danger">*</span></label>
                  <select class="form-control @error('educational_attainment') is-invalid @enderror" 
                    id="educational_attainment" name="educational_attainment" required>
                    <option value="">Select educational attainment</option>
                    <option value="No Formal Education" {{ old('educational_attainment', $step3['educational_attainment'] ?? '') == 'No Formal Education' ? 'selected' : '' }}>No Formal Education</option>
                    <option value="Elementary Undergraduate" {{ old('educational_attainment', $step3['educational_attainment'] ?? '') == 'Elementary Undergraduate' ? 'selected' : '' }}>Elementary Undergraduate</option>
                    <option value="Elementary Graduate" {{ old('educational_attainment', $step3['educational_attainment'] ?? '') == 'Elementary Graduate' ? 'selected' : '' }}>Elementary Graduate</option>
                    <option value="High School Undergraduate" {{ old('educational_attainment', $step3['educational_attainment'] ?? '') == 'High School Undergraduate' ? 'selected' : '' }}>High School Undergraduate</option>
                    <option value="High School Graduate" {{ old('educational_attainment', $step3['educational_attainment'] ?? '') == 'High School Graduate' ? 'selected' : '' }}>High School Graduate</option>
                    <option value="Vocational/Technical Graduate" {{ old('educational_attainment', $step3['educational_attainment'] ?? '') == 'Vocational/Technical Graduate' ? 'selected' : '' }}>Vocational/Technical Graduate</option>
                    <option value="College Undergraduate" {{ old('educational_attainment', $step3['educational_attainment'] ?? '') == 'College Undergraduate' ? 'selected' : '' }}>College Undergraduate</option>
                    <option value="College Graduate" {{ old('educational_attainment', $step3['educational_attainment'] ?? '') == 'College Graduate' ? 'selected' : '' }}>College Graduate</option>
                    <option value="Post Graduate" {{ old('educational_attainment', $step3['educational_attainment'] ?? '') == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                  </select>
                  @error('educational_attainment')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-6 mb-3">
                  <label for="education_status">Education Status <span class="text-danger">*</span></label>
                  <select class="form-control @error('education_status') is-invalid @enderror" 
                    id="education_status" name="education_status" required>
                    <option value="">Select education status</option>
                    <option value="Studying" {{ old('education_status', $step3['education_status'] ?? '') == 'Studying' ? 'selected' : '' }}>Studying</option>
                    <option value="Graduated" {{ old('education_status', $step3['education_status'] ?? '') == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                    <option value="Stopped Schooling" {{ old('education_status', $step3['education_status'] ?? '') == 'Stopped Schooling' ? 'selected' : '' }}>Stopped Schooling</option>
                    <option value="Not Applicable" {{ old('education_status', $step3['education_status'] ?? '') == 'Not Applicable' ? 'selected' : '' }}>Not Applicable</option>
                  </select>
                  @error('education_status')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
              </div>

              <!-- Employment Information -->
              <hr>
              <h5 class="work-header mb-3"><i class="fas fa-briefcase mr-2"></i>Employment Information</h5>
              
              <div class="row mb-4">
                <div class="col-md-6 mb-3">
                  <label for="occupation">Occupation <span class="text-danger">*</span></label>
                  <input type="text" name="occupation" id="occupation" placeholder="e.g., Teacher, Engineer, Unemployed"
                    class="form-control @error('occupation') is-invalid @enderror" 
                    value="{{ old('occupation', $step3['occupation'] ?? '') }}" required>
                  @error('occupation')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-6 mb-3">
                  <label for="employment_status">Employment Status <span class="text-danger">*</span></label>
                  <select class="form-control @error('employment_status') is-invalid @enderror" 
                    id="employment_status" name="employment_status" required>
                    <option value="">Select employment status</option>
                    <option value="Employed" {{ old('employment_status', $step3['employment_status'] ?? '') == 'Employed' ? 'selected' : '' }}>Employed</option>
                    <option value="Self-Employed" {{ old('employment_status', $step3['employment_status'] ?? '') == 'Self-Employed' ? 'selected' : '' }}>Self-Employed</option>
                    <option value="Unemployed" {{ old('employment_status', $step3['employment_status'] ?? '') == 'Unemployed' ? 'selected' : '' }}>Unemployed</option>
                    <option value="Student" {{ old('employment_status', $step3['employment_status'] ?? '') == 'Student' ? 'selected' : '' }}>Student</option>
                    <option value="Retired" {{ old('employment_status', $step3['employment_status'] ?? '') == 'Retired' ? 'selected' : '' }}>Retired</option>
                    <option value="OFW" {{ old('employment_status', $step3['employment_status'] ?? '') == 'OFW' ? 'selected' : '' }}>OFW (Overseas Filipino Worker)</option>
                  </select>
                  @error('employment_status')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
              </div>
              
              <div class="row mb-4">
                <div class="col-md-12 mb-3">
                  <label for="workplace_name">Workplace/Company Name</label>
                  <input type="text" name="workplace_name" id="workplace_name" placeholder="Name of company or business (if applicable)"
                    class="form-control @error('workplace_name') is-invalid @enderror" 
                    value="{{ old('workplace_name', $step3['workplace_name'] ?? '') }}">
                  @error('workplace_name')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
              </div>
              
              <div class="row mb-4">
                <div class="col-md-12 mb-3">
                  <label for="workplace_address">Workplace Address</label>
                  <textarea name="workplace_address" id="workplace_address" rows="2" placeholder="Complete address of workplace (if applicable)"
                    class="form-control @error('workplace_address') is-invalid @enderror">{{ old('workplace_address', $step3['workplace_address'] ?? '') }}</textarea>
                  @error('workplace_address')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
              </div>

              <!-- Additional Information -->
              <hr>
              <h5 class="additional-header mb-3"><i class="fas fa-info-circle mr-2"></i>Additional Information</h5>
              
              <div class="row mb-4">
                <div class="col-md-6 mb-3">
                  <label for="monthly_income">Monthly Income Range <span class="text-danger">*</span></label>
                  <select class="form-control @error('monthly_income') is-invalid @enderror" 
                    id="monthly_income" name="monthly_income" required>
                    <option value="">Select income range</option>
                    <option value="Below 10,000" {{ old('monthly_income', $step3['monthly_income'] ?? '') == 'Below 10,000' ? 'selected' : '' }}>Below ₱10,000</option>
                    <option value="10,000 - 20,000" {{ old('monthly_income', $step3['monthly_income'] ?? '') == '10,000 - 20,000' ? 'selected' : '' }}>₱10,000 - ₱20,000</option>
                    <option value="20,001 - 30,000" {{ old('monthly_income', $step3['monthly_income'] ?? '') == '20,001 - 30,000' ? 'selected' : '' }}>₱20,001 - ₱30,000</option>
                    <option value="30,001 - 50,000" {{ old('monthly_income', $step3['monthly_income'] ?? '') == '30,001 - 50,000' ? 'selected' : '' }}>₱30,001 - ₱50,000</option>
                    <option value="Above 50,000" {{ old('monthly_income', $step3['monthly_income'] ?? '') == 'Above 50,000' ? 'selected' : '' }}>Above ₱50,000</option>
                    <option value="No Income" {{ old('monthly_income', $step3['monthly_income'] ?? '') == 'No Income' ? 'selected' : '' }}>No Income</option>
                  </select>
                  @error('monthly_income')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
                
                <div class="col-md-6 mb-3">
                  <label for="health_insurance">Health Insurance</label>
                  <select class="form-control @error('health_insurance') is-invalid @enderror" 
                    id="health_insurance" name="health_insurance">
                    <option value="">Select health insurance</option>
                    <option value="PhilHealth" {{ old('health_insurance', $step3['health_insurance'] ?? '') == 'PhilHealth' ? 'selected' : '' }}>PhilHealth</option>
                    <option value="Private Insurance" {{ old('health_insurance', $step3['health_insurance'] ?? '') == 'Private Insurance' ? 'selected' : '' }}>Private Insurance</option>
                    <option value="None" {{ old('health_insurance', $step3['health_insurance'] ?? '') == 'None' ? 'selected' : '' }}>None</option>
                  </select>
                  @error('health_insurance')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6 mt-2">
                  <a href="{{ route('public.pre-registration.step2') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-arrow-left"></i> Previous
                  </a>
                </div>
                <div class="col-md-6 mt-2">
                  <button type="submit" class="btn bg-gradient-dark w-100">
                    Continue to Step 4 <i class="fas fa-arrow-right ml-2"></i>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
