@extends('layouts.public.master')

@section('title', 'Pre-Registration Step 3 - Additional Information')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Progress Bar (Updated Design) -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Registration Progress</h5>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 60%" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col text-center">
                            <small class="text-muted">Step 1: Personal Info</small>
                        </div>
                        <div class="col text-center">
                            <small class="text-muted">Step 2: Contact & Education</small>
                        </div>
                        <div class="col text-center">
                            <small class="text-primary font-weight-bold">Step 3: Additional Info</small>
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
                        <i class="fe fe-file-text fe-16 mr-2"></i>Additional Information
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

                    <form action="{{ route('public.pre-registration.step3.store') }}" method="POST">
                        @csrf
                        
                        <!-- Government IDs -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fe fe-credit-card"></i> Government ID
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="philsys_id" class="form-label">PhilSys ID</label>
                                <input type="text" class="form-control @error('philsys_id') is-invalid @enderror" 
                                       name="philsys_id" value="{{ old('philsys_id', session('pre_registration.step3.philsys_id')) }}">
                                <small class="form-text text-muted">Philippine Identification System ID number (optional)</small>
                                @error('philsys_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Population Sectors -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fe fe-users"></i> Population Sectors
                                </h5>
                                <p class="text-muted">Select all categories that apply to you:</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="row">
                                    @foreach($populationSectors as $sector)
                                        <div class="col-md-6 col-lg-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="population_sectors[]" value="{{ $sector }}" 
                                                       id="sector_{{ $loop->index }}"
                                                       {{ in_array($sector, old('population_sectors', session('pre_registration.step3.population_sectors', []))) ? 'checked' : '' }}>
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
                                <p class="text-muted">Provide your mother's information if available:</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="mother_first_name" class="form-label">Mother's First Name</label>
                                <input type="text" class="form-control @error('mother_first_name') is-invalid @enderror" 
                                       name="mother_first_name" value="{{ old('mother_first_name', session('pre_registration.step3.mother_first_name')) }}">
                                @error('mother_first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="mother_middle_name" class="form-label">Mother's Middle Name</label>
                                <input type="text" class="form-control @error('mother_middle_name') is-invalid @enderror" 
                                       name="mother_middle_name" value="{{ old('mother_middle_name', session('pre_registration.step3.mother_middle_name')) }}">
                                @error('mother_middle_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="mother_last_name" class="form-label">Mother's Last Name</label>
                                <input type="text" class="form-control @error('mother_last_name') is-invalid @enderror" 
                                       name="mother_last_name" value="{{ old('mother_last_name', session('pre_registration.step3.mother_last_name')) }}">
                                @error('mother_last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Age Notice for Senior Citizens -->
                        @php
                            $birthdate = old('birthdate', session('pre_registration.step1.birthdate'));
                            $age = $birthdate ? \Carbon\Carbon::parse($birthdate)->age : 0;
                            $isSenior = $age >= 60;
                        @endphp

                        @if($isSenior)
                            <div class="alert alert-info">
                                <h5><i class="fe fe-award"></i> Senior Citizen Detected!</h5>
                                <p class="mb-0">Based on your age ({{ $age }} years), you qualify as a Senior Citizen. 
                                You'll be asked to provide additional information for your Senior Citizen ID in the next step.</p>
                            </div>
                        @endif

                        <!-- Navigation Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('public.pre-registration.step2') }}" class="btn btn-secondary">
                                        <i class="fe fe-arrow-left"></i> Back: Contact & Education
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        @if($isSenior)
                                            Next: Senior Citizen Info <i class="fe fe-award"></i>
                                        @else
                                            Next: Photo & Documents <i class="fe fe-arrow-right"></i>
                                        @endif
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

.form-check {
    padding: 0.5rem;
    border-radius: 0.25rem;
    transition: background-color 0.15s ease-in-out;
}

.form-check:hover {
    background-color: #f8f9fa;
}

.form-check-input:checked ~ .form-check-label {
    font-weight: 600;
    color: #007bff;
}
</style>
@endsection