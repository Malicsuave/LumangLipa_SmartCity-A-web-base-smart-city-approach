@extends('layouts.public.master')

@section('title', 'Pre-Registration Step 2 - Contact & Education')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Progress Steps -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="progress-steps">
                        <div class="step completed">
                            <div class="step-number">1</div>
                            <div class="step-title">Personal Info</div>
                        </div>
                        <div class="step active">
                            <div class="step-number">2</div>
                            <div class="step-title">Contact & Education</div>
                        </div>
                        <div class="step">
                            <div class="step-number">3</div>
                            <div class="step-title">Additional Info</div>
                        </div>
                        <div class="step">
                            <div class="step-number">4</div>
                            <div class="step-title">Photo & Documents</div>
                        </div>
                        <div class="step">
                            <div class="step-number">5</div>
                            <div class="step-title">Review</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fe fe-phone"></i> Step 2: Contact & Education Information</h4>
                    <p class="mb-0 mt-2">Provide your contact details and educational background</p>
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
                                        name="citizenship_type" id="citizenship_type" required>
                                    <option value="">Select Type</option>
                                    <option value="FILIPINO" {{ old('citizenship_type', session('pre_registration.step2.citizenship_type')) == 'FILIPINO' ? 'selected' : '' }}>Filipino</option>
                                    <option value="Dual Citizen" {{ old('citizenship_type', session('pre_registration.step2.citizenship_type')) == 'Dual Citizen' ? 'selected' : '' }}>Dual Citizen</option>
                                    <option value="Foreigner" {{ old('citizenship_type', session('pre_registration.step2.citizenship_type')) == 'Foreigner' ? 'selected' : '' }}>Foreigner</option>
                                </select>
                                @error('citizenship_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="citizenship_country" class="form-label">Country (if Dual/Foreigner)</label>
                                <input type="text" class="form-control @error('citizenship_country') is-invalid @enderror" 
                                       name="citizenship_country" value="{{ old('citizenship_country', session('pre_registration.step2.citizenship_country')) }}" 
                                       id="citizenship_country" disabled>
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
                                <input type="text" class="form-control @error('educational_attainment') is-invalid @enderror" 
                                       name="educational_attainment" value="{{ old('educational_attainment', session('pre_registration.step2.educational_attainment')) }}" 
                                       placeholder="e.g., High School Graduate, College Graduate" required>
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
<link rel="stylesheet" href="{{ asset('css/pre-registration-form.css') }}">
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle citizenship type change
    const citizenshipType = document.getElementById('citizenship_type');
    const citizenshipCountry = document.getElementById('citizenship_country');
    
    citizenshipType.addEventListener('change', function() {
        if (this.value === 'Dual Citizen' || this.value === 'Foreigner') {
            citizenshipCountry.disabled = false;
            citizenshipCountry.required = true;
        } else {
            citizenshipCountry.disabled = true;
            citizenshipCountry.required = false;
            citizenshipCountry.value = '';
        }
    });
    
    // Initialize the field state
    if (citizenshipType.value === 'Dual Citizen' || citizenshipType.value === 'Foreigner') {
        citizenshipCountry.disabled = false;
        citizenshipCountry.required = true;
    }
});
</script>
@endsection