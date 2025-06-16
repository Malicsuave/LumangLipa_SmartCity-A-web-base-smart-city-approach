@extends('layouts.public.master')

@section('title', 'Pre-Registration - Barangay Lumanglipa')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fe fe-user-plus"></i> Barangay Resident Pre-Registration</h4>
                    <p class="mb-0 mt-2">Register online and receive your digital ID upon approval</p>
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

                    <form action="{{ route('public.pre-registration.store') }}" method="POST" enctype="multipart/form-data" id="preRegistrationForm">
                        @csrf
                        
                        <!-- Personal Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fe fe-user"></i> Personal Information
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type_of_resident" class="form-label">Type of Resident <span class="text-danger">*</span></label>
                                <select class="form-control @error('type_of_resident') is-invalid @enderror" name="type_of_resident" required>
                                    <option value="">Select Type</option>
                                    <option value="Permanent" {{ old('type_of_resident') == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                    <option value="Temporary" {{ old('type_of_resident') == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                                    <option value="Boarder/Transient" {{ old('type_of_resident') == 'Boarder/Transient' ? 'selected' : '' }}>Boarder/Transient</option>
                                </select>
                                @error('type_of_resident')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="civil_status" class="form-label">Civil Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('civil_status') is-invalid @enderror" name="civil_status" required>
                                    <option value="">Select Status</option>
                                    <option value="Single" {{ old('civil_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ old('civil_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Divorced" {{ old('civil_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="Widowed" {{ old('civil_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                    <option value="Separated" {{ old('civil_status') == 'Separated' ? 'selected' : '' }}>Separated</option>
                                </select>
                                @error('civil_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                       name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control @error('middle_name') is-invalid @enderror" 
                                       name="middle_name" value="{{ old('middle_name') }}">
                                @error('middle_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                       name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="suffix" class="form-label">Suffix</label>
                                <input type="text" class="form-control @error('suffix') is-invalid @enderror" 
                                       name="suffix" value="{{ old('suffix') }}" placeholder="Jr., Sr., III">
                                @error('suffix')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="sex" class="form-label">Sex <span class="text-danger">*</span></label>
                                <select class="form-control @error('sex') is-invalid @enderror" name="sex" required>
                                    <option value="">Select</option>
                                    <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('sex')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="birthdate" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('birthdate') is-invalid @enderror" 
                                       name="birthdate" value="{{ old('birthdate') }}" required>
                                @error('birthdate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="birthplace" class="form-label">Place of Birth <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('birthplace') is-invalid @enderror" 
                                       name="birthplace" value="{{ old('birthplace') }}" required>
                                @error('birthplace')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact & Education Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fe fe-phone"></i> Contact & Education Information
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('contact_number') is-invalid @enderror" 
                                       name="contact_number" value="{{ old('contact_number') }}" 
                                       placeholder="09XXXXXXXXX" maxlength="11" required>
                                @error('contact_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email_address" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email_address') is-invalid @enderror" 
                                       name="email_address" value="{{ old('email_address') }}" required>
                                @error('email_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="profession_occupation" class="form-label">Profession/Occupation <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('profession_occupation') is-invalid @enderror" 
                                       name="profession_occupation" value="{{ old('profession_occupation') }}" required>
                                @error('profession_occupation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="monthly_income" class="form-label">Monthly Income (Optional)</label>
                                <input type="number" class="form-control @error('monthly_income') is-invalid @enderror" 
                                       name="monthly_income" value="{{ old('monthly_income') }}" min="0" step="0.01">
                                @error('monthly_income')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="educational_attainment" class="form-label">Educational Attainment <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('educational_attainment') is-invalid @enderror" 
                                       name="educational_attainment" value="{{ old('educational_attainment') }}" 
                                       placeholder="e.g., High School Graduate, College Graduate" required>
                                @error('educational_attainment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="education_status" class="form-label">Education Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('education_status') is-invalid @enderror" name="education_status" required>
                                    <option value="">Select Status</option>
                                    <option value="Studying" {{ old('education_status') == 'Studying' ? 'selected' : '' }}>Studying</option>
                                    <option value="Graduated" {{ old('education_status') == 'Graduated' ? 'selected' : '' }}>Graduated</option>
                                    <option value="Stopped Schooling" {{ old('education_status') == 'Stopped Schooling' ? 'selected' : '' }}>Stopped Schooling</option>
                                    <option value="Not Applicable" {{ old('education_status') == 'Not Applicable' ? 'selected' : '' }}>Not Applicable</option>
                                </select>
                                @error('education_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Citizenship & Address -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fe fe-map-pin"></i> Citizenship & Address Information
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="citizenship_type" class="form-label">Citizenship Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('citizenship_type') is-invalid @enderror" 
                                        name="citizenship_type" id="citizenship_type" required>
                                    <option value="">Select Type</option>
                                    <option value="FILIPINO" {{ old('citizenship_type') == 'FILIPINO' ? 'selected' : '' }}>Filipino</option>
                                    <option value="Dual Citizen" {{ old('citizenship_type') == 'Dual Citizen' ? 'selected' : '' }}>Dual Citizen</option>
                                    <option value="Foreigner" {{ old('citizenship_type') == 'Foreigner' ? 'selected' : '' }}>Foreigner</option>
                                </select>
                                @error('citizenship_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="citizenship_country" class="form-label">Country (if Dual/Foreigner)</label>
                                <input type="text" class="form-control @error('citizenship_country') is-invalid @enderror" 
                                       name="citizenship_country" value="{{ old('citizenship_country') }}" 
                                       id="citizenship_country" disabled>
                                @error('citizenship_country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="address" class="form-label">Complete Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          name="address" rows="3" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Photo and Signature Upload -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fe fe-camera"></i> ID Card Images
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="photo" class="form-label">Photo <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                       name="photo" accept="image/*" required>
                                <small class="form-text text-muted">Upload a clear photo for your ID card (Max: 5MB)</small>
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="signature" class="form-label">Signature (Optional)</label>
                                <input type="file" class="form-control @error('signature') is-invalid @enderror" 
                                       name="signature" accept="image/*">
                                <small class="form-text text-muted">Upload your signature image (Max: 2MB)</small>
                                @error('signature')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fe fe-info"></i> Additional Information
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="religion" class="form-label">Religion</label>
                                <input type="text" class="form-control @error('religion') is-invalid @enderror" 
                                       name="religion" value="{{ old('religion') }}">
                                @error('religion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="philsys_id" class="form-label">PhilSys ID</label>
                                <input type="text" class="form-control @error('philsys_id') is-invalid @enderror" 
                                       name="philsys_id" value="{{ old('philsys_id') }}">
                                @error('philsys_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Population Sectors -->
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Population Sectors (Select all that apply)</label>
                                <div class="row">
                                    @foreach($populationSectors as $sector)
                                        <div class="col-md-6 col-lg-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="population_sectors[]" value="{{ $sector }}" 
                                                       id="sector_{{ $loop->index }}"
                                                       {{ in_array($sector, old('population_sectors', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="sector_{{ $loop->index }}">
                                                    {{ $sector }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Mother's Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fe fe-users"></i> Mother's Information (Optional)
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="mother_first_name" class="form-label">Mother's First Name</label>
                                <input type="text" class="form-control @error('mother_first_name') is-invalid @enderror" 
                                       name="mother_first_name" value="{{ old('mother_first_name') }}">
                                @error('mother_first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="mother_middle_name" class="form-label">Mother's Middle Name</label>
                                <input type="text" class="form-control @error('mother_middle_name') is-invalid @enderror" 
                                       name="mother_middle_name" value="{{ old('mother_middle_name') }}">
                                @error('mother_middle_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="mother_last_name" class="form-label">Mother's Last Name</label>
                                <input type="text" class="form-control @error('mother_last_name') is-invalid @enderror" 
                                       name="mother_last_name" value="{{ old('mother_last_name') }}">
                                @error('mother_last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input @error('terms_accepted') is-invalid @enderror" 
                                           type="checkbox" name="terms_accepted" id="terms_accepted" required>
                                    <label class="form-check-label" for="terms_accepted">
                                        I agree to the <a href="#" data-toggle="modal" data-target="#termsModal">Terms and Conditions</a> 
                                        and acknowledge that the information provided is accurate and complete.
                                        <span class="text-danger">*</span>
                                    </label>
                                    @error('terms_accepted')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    <i class="fe fe-send"></i> Submit Pre-Registration
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Check Status Link -->
            <div class="text-center mt-4">
                <p class="text-muted">Already submitted your registration?</p>
                <a href="{{ route('public.pre-registration.check-status') }}" class="btn btn-outline-primary">
                    <i class="fe fe-search"></i> Check Registration Status
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6>Data Privacy and Information Accuracy</h6>
                <p>By submitting this pre-registration form, you acknowledge and agree to the following:</p>
                <ol>
                    <li>All information provided is accurate, complete, and truthful.</li>
                    <li>Your personal data will be used for barangay registration and ID generation purposes only.</li>
                    <li>Your data will be protected in accordance with the Data Privacy Act of 2012.</li>
                    <li>You understand that providing false information may result in rejection of your application.</li>
                    <li>Your registration is subject to verification and approval by the Barangay Administration.</li>
                    <li>Digital ID cards sent via email are official and can be used for barangay transactions.</li>
                </ol>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="document.getElementById('terms_accepted').checked = true;">
                    I Agree
                </button>
            </div>
        </div>
    </div>
</div>

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
    
    // Form validation
    const form = document.getElementById('preRegistrationForm');
    
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let hasErrors = false;
        
        requiredFields.forEach(function(field) {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                hasErrors = true;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        
        if (hasErrors) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
});
</script>
@endsection