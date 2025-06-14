@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
<li class="breadcrumb-item active" aria-current="page">Register New Resident</li>
@endsection

@section('page-title', 'Register New Resident - Step 2')
@section('page-subtitle', 'Citizenship & Education')

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Progress Bar -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Registration Progress</h5>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 40%" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="row mt-2">
                    <div class="col text-center">
                        <small class="text-success">✓ Personal Info</small>
                    </div>
                    <div class="col text-center">
                        <small class="text-primary font-weight-bold">Step 2: Citizenship & Education</small>
                    </div>
                    <div class="col text-center">
                        <small class="text-muted">Step 3: Household Info</small>
                    </div>
                    <div class="col text-center">
                        <small class="text-muted">Step 4: Family Members</small>
                    </div>
                    <div class="col text-center">
                        <small class="text-muted">Step 5: Review</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="card shadow">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="fe fe-flag fe-16 mr-2"></i>Citizenship & Education Information
                </h4>
                <p class="text-muted mb-0">Citizenship status, work, education, and contact details</p>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.residents.create.step2.store') }}" method="POST">
                    @csrf
                    
                    <!-- Citizenship -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Citizenship <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="filipino" name="citizenship_type" value="FILIPINO" 
                                                   class="custom-control-input" {{ old('citizenship_type', session('registration.step2.citizenship_type') ?? 'FILIPINO') == 'FILIPINO' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="filipino">Filipino</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="dual" name="citizenship_type" value="Dual Citizen" 
                                                   class="custom-control-input" {{ old('citizenship_type', session('registration.step2.citizenship_type')) == 'Dual Citizen' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="dual">Dual Citizen</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" id="foreigner" name="citizenship_type" value="Foreigner" 
                                                   class="custom-control-input" {{ old('citizenship_type', session('registration.step2.citizenship_type')) == 'Foreigner' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="foreigner">Foreigner</label>
                                        </div>
                                    </div>
                                </div>
                                @error('citizenship')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row" id="citizenship_country_row" style="display: none;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="citizenship_country" class="form-label">Other Country (for Dual Citizens/Foreigners)</label>
                                <input type="text" class="form-control @error('citizenship_country') is-invalid @enderror" 
                                       id="citizenship_country" name="citizenship_country" 
                                       value="{{ old('citizenship_country', session('registration.step2.citizenship_country')) }}" 
                                       placeholder="e.g., United States, Japan">
                                @error('citizenship_country')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Work and Contact -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="profession_occupation" class="form-label">Profession/Occupation <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('profession_occupation') is-invalid @enderror" 
                                       id="profession_occupation" name="profession_occupation" 
                                       value="{{ old('profession_occupation', session('registration.step2.profession_occupation')) }}" 
                                       placeholder="e.g., government employee, nurse, engineer, farmer" required>
                                @error('profession_occupation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="monthly_income" class="form-label">Monthly Income</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">₱</span>
                                    </div>
                                    <input type="number" class="form-control @error('monthly_income') is-invalid @enderror" 
                                           id="monthly_income" name="monthly_income" 
                                           value="{{ old('monthly_income', session('registration.step2.monthly_income')) }}" 
                                           placeholder="e.g., 25000" step="0.01" min="0">
                                </div>
                                <small class="form-text text-muted">Enter your approximate monthly income in Philippine Peso</small>
                                @error('monthly_income')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('contact_number') is-invalid @enderror" 
                                       id="contact_number" name="contact_number" value="{{ old('contact_number', session('registration.step2.contact_number')) }}" 
                                       placeholder="e.g., 09123456789" required maxlength="11" pattern="[0-9]{11}"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)">
                                <small class="form-text text-muted">Enter a valid Philippine mobile number (11 digits, e.g., 09XXXXXXXXX)</small>
                                @error('contact_number')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email_address" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email_address') is-invalid @enderror" 
                                       id="email_address" name="email_address" value="{{ old('email_address', session('registration.step2.email_address')) }}" 
                                       placeholder="example@email.com" required>
                                @error('email_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="religion" class="form-label">Religion</label>
                                <input type="text" class="form-control @error('religion') is-invalid @enderror" 
                                       id="religion" name="religion" value="{{ old('religion', session('registration.step2.religion')) }}" 
                                       placeholder="e.g., Christian, Islam, other religion">
                                @error('religion')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Education -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="educational_attainment" class="form-label">Highest Educational Attainment <span class="text-danger">*</span></label>
                                <select class="form-control @error('education_attainment') is-invalid @enderror" 
                                        id="educational_attainment" name="educational_attainment" required>
                                    <option value="">Select Educational Attainment</option>
                                    <option value="Elementary" {{ old('educational_attainment', session('registration.step2.educational_attainment')) == 'Elementary' ? 'selected' : '' }}>Elementary</option>
                                    <option value="Highschool" {{ old('educational_attainment', session('registration.step2.educational_attainment')) == 'Highschool' ? 'selected' : '' }}>High School</option>
                                    <option value="College" {{ old('educational_attainment', session('registration.step2.educational_attainment')) == 'College' ? 'selected' : '' }}>College</option>
                                    <option value="Post Graduate" {{ old('educational_attainment', session('registration.step2.educational_attainment')) == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                                    <option value="Vocational" {{ old('educational_attainment', session('registration.step2.educational_attainment')) == 'Vocational' ? 'selected' : '' }}>Vocational</option>
                                    <option value="not applicable" {{ old('educational_attainment', session('registration.step2.educational_attainment')) == 'not applicable' ? 'selected' : '' }}>Not Applicable</option>
                                </select>
                                @error('education_attainment')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="education_status" class="form-label">Education Status</label>
                                <select class="form-control @error('education_status') is-invalid @enderror" 
                                        id="education_status" name="education_status">
                                    <option value="">Select Status</option>
                                    <option value="Graduate" {{ old('education_status', session('registration.step2.education_status')) == 'Graduate' ? 'selected' : '' }}>Graduate</option>
                                    <option value="Undergraduate" {{ old('education_status', session('registration.step2.education_status')) == 'Undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                                    <option value="not applicable" {{ old('education_status', session('registration.step2.education_status')) == 'not applicable' ? 'selected' : '' }}>Not Applicable</option>
                                </select>
                                @error('education_status')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Mother's Information -->
                    <hr class="my-4">
                    <h5 class="mb-3">Mother's Information</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="mother_first_name" class="form-label">Mother's First Name</label>
                                <input type="text" class="form-control @error('mother_first_name') is-invalid @enderror" 
                                       id="mother_first_name" name="mother_first_name" value="{{ old('mother_first_name', session('registration.step2.mother_first_name')) }}">
                                @error('mother_first_name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="mother_middle_name" class="form-label">Mother's Middle Name</label>
                                <input type="text" class="form-control @error('mother_middle_name') is-invalid @enderror" 
                                       id="mother_middle_name" name="mother_middle_name" value="{{ old('mother_middle_name', session('registration.step2.mother_middle_name')) }}">
                                @error('mother_middle_name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="mother_last_name" class="form-label">Mother's Last Name</label>
                                <input type="text" class="form-control @error('mother_last_name') is-invalid @enderror" 
                                       id="mother_last_name" name="mother_last_name" value="{{ old('mother_last_name', session('registration.step2.mother_last_name')) }}">
                                @error('mother_last_name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Address and IDs -->
                    <hr class="my-4">
                    <h5 class="mb-3">Address & IDs</h5>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3" required>{{ old('address', session('registration.step2.address')) }}</textarea>
                                @error('address')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="philsys_id" class="form-label">PhilSys ID</label>
                                <input type="text" class="form-control @error('philsys_id') is-invalid @enderror" 
                                       id="philsys_id" name="philsys_id" value="{{ old('philsys_id', session('registration.step2.philsys_id')) }}" 
                                       placeholder="1234-5678-9012">
                                @error('philsys_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Population Sectors -->
                    <hr class="my-4">
                    <h5 class="mb-3">Population by Sector</h5>
                    <p class="text-muted">Select all that applies. Select "Not applicable" if none of the options match.</p>
                    <div class="row">
                        @php
                            $sectors = [
                                'Labor Force', 'Overseas Filipino Worker', 'Solo Parent',
                                'Person with Disability', 'Indigenous People', 'Employed',
                                'Self-employed (including businessman/women)', 'Unemployed',
                                'Student', 'Out of school children (6-14 years old)',
                                'Out of School Youth (15-24 years old)', 'Not applicable'
                            ];
                            $oldSectors = old('population_sectors', []);
                        @endphp
                        @foreach($sectors as $index => $sector)
                            @if($index % 3 == 0 && $index > 0)
                                </div><div class="row">
                            @endif
                            <div class="col-md-4">
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input" 
                                           id="sector_{{ $index }}" name="population_sectors[]" value="{{ $sector }}"
                                           {{ in_array($sector, $oldSectors) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="sector_{{ $index }}">{{ $sector }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Form Navigation -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.residents.create') }}" class="btn btn-secondary d-flex align-items-center justify-content-center">
                                    <i class="fe fe-arrow-left fe-16 mr-2"></i>
                                    <span>Back: Personal Info</span>
                                </a>
                                <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center">
                                    <span>Next: Household Information</span>
                                    <i class="fe fe-arrow-right fe-16 ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show/hide citizenship country field
    const citizenshipRadios = document.querySelectorAll('input[name="citizenship_type"]');
    const countryRow = document.getElementById('citizenship_country_row');
    
    function toggleCitizenshipCountry() {
        const selectedValue = document.querySelector('input[name="citizenship_type"]:checked')?.value;
        if (selectedValue === 'Dual Citizen' || selectedValue === 'Foreigner') {
            countryRow.style.display = 'block';
        } else {
            countryRow.style.display = 'none';
            document.getElementById('citizenship_country').value = '';
        }
    }
    
    citizenshipRadios.forEach(radio => {
        radio.addEventListener('change', toggleCitizenshipCountry);
    });
    
    // Initialize on page load
    toggleCitizenshipCountry();
});
</script>

@if(session('error'))
<script>
    window.addEventListener('DOMContentLoaded', function() {
        alert('Error: {{ session('error') }}');
    });
</script>
@endif
@endsection