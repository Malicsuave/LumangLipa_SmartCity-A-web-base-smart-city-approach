@extends('layouts.public.master')

@section('title', 'Pre-Registration Step 2 - Contact & Education Information')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Progress Bar (Updated Design) -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Registration Progress</h5>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col text-center">
                            <small class="text-muted">Step 1: Personal Info</small>
                        </div>
                        <div class="col text-center">
                            <small class="text-primary font-weight-bold">Step 2: Contact & Education</small>
                        </div>
                        <div class="col text-center">
                            <small class="text-muted">Step 3: Additional Info</small>
                        </div>
                        <div class="col text-center">
                            <small class="text-muted">Step 4: Photo & Documents</small>
                        </div>
                        <div class="col text-center">
                            <small class="text-muted">Step 5: Review</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fe fe-phone fe-16 mr-2"></i>Contact & Education Information
                    </h4>
                </div>
                
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6><i class="fe fe-alert-circle"></i> Please correct the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('public.pre-registration.step2.store') }}" method="POST">
                        @csrf
                        
                        <!-- Citizenship Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fe fe-flag"></i> Citizenship Information
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="citizenship_type" class="form-label">Citizenship Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('citizenship_type') is-invalid @enderror" 
                                        name="citizenship_type" id="citizenship_type" required onchange="toggleCountryField()">
                                    <option value="">Select Type</option>
                                    <option value="FILIPINO" {{ old('citizenship_type', session('pre_registration.step2.citizenship_type')) == 'FILIPINO' ? 'selected' : '' }}>Filipino</option>
                                    <option value="Dual Citizen" {{ old('citizenship_type', session('pre_registration.step2.citizenship_type')) == 'Dual Citizen' ? 'selected' : '' }}>Dual Citizen</option>
                                    <option value="Foreigner" {{ old('citizenship_type', session('pre_registration.step2.citizenship_type')) == 'Foreigner' ? 'selected' : '' }}>Foreigner</option>
                                </select>
                                @error('citizenship_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3" id="citizenship_country_div" style="{{ (old('citizenship_type', session('pre_registration.step2.citizenship_type')) == 'Dual Citizen' || old('citizenship_type', session('pre_registration.step2.citizenship_type')) == 'Foreigner') ? '' : 'display: none;' }}">
                                <label for="citizenship_country" class="form-label">Country (if Dual/Foreigner)</label>
                                <input type="text" class="form-control @error('citizenship_country') is-invalid @enderror" 
                                       name="citizenship_country" value="{{ old('citizenship_country', session('pre_registration.step2.citizenship_country')) }}" 
                                       id="citizenship_country" placeholder="e.g., United States, Japan">
                                @error('citizenship_country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Contact Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fe fe-phone"></i> Contact Information
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('contact_number') is-invalid @enderror" 
                                       name="contact_number" value="{{ old('contact_number', session('pre_registration.step2.contact_number')) }}" 
                                       placeholder="09XXXXXXXXX" maxlength="11" required>
                                @error('contact_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email_address" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email_address') is-invalid @enderror" 
                                       name="email_address" value="{{ old('email_address', session('pre_registration.step2.email_address')) }}" required>
                                @error('email_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fe fe-briefcase"></i> Professional Information
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="profession_occupation" class="form-label">Profession/Occupation <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('profession_occupation') is-invalid @enderror" 
                                       name="profession_occupation" value="{{ old('profession_occupation', session('pre_registration.step2.profession_occupation')) }}" required>
                                @error('profession_occupation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="monthly_income" class="form-label">Monthly Income (Optional)</label>
                                <input type="number" class="form-control @error('monthly_income') is-invalid @enderror" 
                                       name="monthly_income" value="{{ old('monthly_income', session('pre_registration.step2.monthly_income')) }}" min="0" step="0.01">
                                @error('monthly_income')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Educational Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fe fe-book-open"></i> Educational Information
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="educational_attainment" class="form-label">Educational Attainment <span class="text-danger">*</span></label>
                                <select class="form-control @error('educational_attainment') is-invalid @enderror" 
                                       name="educational_attainment" id="educational_attainment" required>
                                    <option value="">Select Educational Attainment</option>
                                    <option value="No Formal Education" {{ old('educational_attainment', session('pre_registration.step2.educational_attainment')) == 'No Formal Education' ? 'selected' : '' }}>No Formal Education</option>
                                    <option value="Elementary Level" {{ old('educational_attainment', session('pre_registration.step2.educational_attainment')) == 'Elementary Level' ? 'selected' : '' }}>Elementary Level</option>
                                    <option value="Elementary Graduate" {{ old('educational_attainment', session('pre_registration.step2.educational_attainment')) == 'Elementary Graduate' ? 'selected' : '' }}>Elementary Graduate</option>
                                    <option value="High School Level" {{ old('educational_attainment', session('pre_registration.step2.educational_attainment')) == 'High School Level' ? 'selected' : '' }}>High School Level</option>
                                    <option value="High School Graduate" {{ old('educational_attainment', session('pre_registration.step2.educational_attainment')) == 'High School Graduate' ? 'selected' : '' }}>High School Graduate</option>
                                    <option value="Vocational/Technical" {{ old('educational_attainment', session('pre_registration.step2.educational_attainment')) == 'Vocational/Technical' ? 'selected' : '' }}>Vocational/Technical</option>
                                    <option value="College Level" {{ old('educational_attainment', session('pre_registration.step2.educational_attainment')) == 'College Level' ? 'selected' : '' }}>College Level</option>
                                    <option value="College Graduate" {{ old('educational_attainment', session('pre_registration.step2.educational_attainment')) == 'College Graduate' ? 'selected' : '' }}>College Graduate</option>
                                    <option value="Post Graduate" {{ old('educational_attainment', session('pre_registration.step2.educational_attainment')) == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                                </select>
                                @error('educational_attainment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="education_status" class="form-label">Education Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('education_status') is-invalid @enderror" name="education_status" required>
                                    <option value="">Select Status</option>
                                    <option value="Studying" {{ old('education_status', session('pre_registration.step2.education_status')) == 'Studying' ? 'selected' : '' }}>Studying</option>
                                    <option value="Graduated" {{ old('education_status', session('pre_registration.step2.education_status')) == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                                    <option value="Stopped Schooling" {{ old('education_status', session('pre_registration.step2.education_status')) == 'Stopped Schooling' ? 'selected' : '' }}>Stopped Schooling</option>
                                    <option value="Not Applicable" {{ old('education_status', session('pre_registration.step2.education_status')) == 'Not Applicable' ? 'selected' : '' }}>Not Applicable</option>
                                </select>
                                @error('education_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address & Religion -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fe fe-map-pin"></i> Address & Personal Details
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="address" class="form-label">Complete Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          name="address" rows="3" required>{{ old('address', session('pre_registration.step2.address')) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="religion" class="form-label">Religion</label>
                                <input type="text" class="form-control @error('religion') is-invalid @enderror" 
                                       name="religion" value="{{ old('religion', session('pre_registration.step2.religion')) }}">
                                @error('religion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('public.pre-registration.step1') }}" class="btn btn-secondary">
                                        <i class="fe fe-arrow-left"></i> Back: Personal Info
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        Next: Additional Info <i class="fe fe-arrow-right"></i>
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

@section('styles')
<style>
.progress-steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

.progress-steps::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    height: 2px;
    background-color: #e9ecef;
    z-index: 1;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    background: white;
    padding: 0 10px;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-bottom: 8px;
}

.step.active .step-number {
    background-color: #007bff;
    color: white;
}

.step.completed .step-number {
    background-color: #28a745;
    color: white;
}

.step-title {
    font-size: 12px;
    text-align: center;
    color: #6c757d;
}

.step.active .step-title {
    color: #007bff;
    font-weight: 600;
}

.step.completed .step-title {
    color: #28a745;
    font-weight: 600;
}
</style>
@endsection

@section('scripts')
<script>
function toggleCountryField() {
    var citizenshipType = document.getElementById('citizenship_type');
    var citizenshipCountryDiv = document.getElementById('citizenship_country_div');
    
    if (citizenshipType.value === 'Dual Citizen' || citizenshipType.value === 'Foreigner') {
        citizenshipCountryDiv.style.display = '';
    } else {
        citizenshipCountryDiv.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleCountryField();
});
</script>
@endsection