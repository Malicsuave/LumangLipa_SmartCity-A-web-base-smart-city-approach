@extends('layouts.public.master')

@section('title', 'Pre-Registration Step 1 - Personal Information')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Progress Bar (Admin Style) -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Registration Progress</h5>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col text-center">
                            <small class="text-primary font-weight-bold">Step 1: Personal Info</small>
                        </div>
                        <div class="col text-center">
                            <small class="text-muted">Step 2: Contact & Education</small>
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
                        <i class="fe fe-user fe-16 mr-2"></i>Personal Information
                    </h4>
                    <p class="text-muted mb-0">Basic personal details of the resident</p>
                </div>
                
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('public.pre-registration.step1.store') }}" method="POST">
                        @csrf
                        
                        <!-- Type of Resident -->
                        <div class="form-group">
                            <label for="type_of_resident" class="form-label">Type of Resident <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="migrant" name="type_of_resident" value="Migrant" 
                                               class="custom-control-input" {{ old('type_of_resident', session('pre_registration.step1.type_of_resident')) == 'Migrant' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="migrant">Migrant</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="nonmigrant" name="type_of_resident" value="Non-Migrant" 
                                               class="custom-control-input" {{ old('type_of_resident', session('pre_registration.step1.type_of_resident')) == 'Non-Migrant' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="nonmigrant">Non-Migrant</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="transient" name="type_of_resident" value="Transient" 
                                               class="custom-control-input" {{ old('type_of_resident', session('pre_registration.step1.type_of_resident')) == 'Transient' ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="transient">Transient</label>
                                    </div>
                                </div>
                            </div>
                            @error('type_of_resident')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Name Fields -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" name="last_name" value="{{ old('last_name', session('pre_registration.step1.last_name')) }}" required>
                                    @error('last_name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" name="first_name" value="{{ old('first_name', session('pre_registration.step1.first_name')) }}" required>
                                    @error('first_name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="middle_name" class="form-label">Middle Name</label>
                                    <input type="text" class="form-control @error('middle_name') is-invalid @enderror" 
                                           id="middle_name" name="middle_name" value="{{ old('middle_name', session('pre_registration.step1.middle_name')) }}">
                                    @error('middle_name')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="suffix" class="form-label">Suffix</label>
                                    <input type="text" class="form-control @error('suffix') is-invalid @enderror" 
                                           id="suffix" name="suffix" value="{{ old('suffix', session('pre_registration.step1.suffix')) }}" placeholder="Jr., Sr.">
                                    @error('suffix')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Birth Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="birthplace" class="form-label">Birthplace <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('birthplace') is-invalid @enderror" 
                                          id="birthplace" name="birthplace" value="{{ old('birthplace', session('pre_registration.step1.birthplace')) }}" required>
                                    @error('birthplace')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="birthdate" class="form-label">Birthdate <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('birthdate') is-invalid @enderror" 
                                          id="birthdate" name="birthdate" value="{{ old('birthdate', session('pre_registration.step1.birthdate')) }}" required>
                                    @error('birthdate')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sex and Civil Status -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Sex <span class="text-danger">*</span></label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="male" name="sex" value="Male" 
                                                      class="custom-control-input" {{ old('sex', session('pre_registration.step1.sex')) == 'Male' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="male">Male</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="female" name="sex" value="Female" 
                                                      class="custom-control-input" {{ old('sex', session('pre_registration.step1.sex')) == 'Female' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="female">Female</label>
                                            </div>
                                        </div>
                                    </div>
                                    @error('sex')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="civil_status" class="form-label">Civil Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('civil_status') is-invalid @enderror" 
                                           id="civil_status" name="civil_status" required>
                                        <option value="">Select Civil Status</option>
                                        <option value="Single" {{ old('civil_status', session('pre_registration.step1.civil_status')) == 'Single' ? 'selected' : '' }}>Single</option>
                                        <option value="Married" {{ old('civil_status', session('pre_registration.step1.civil_status')) == 'Married' ? 'selected' : '' }}>Married</option>
                                        <option value="Widowed" {{ old('civil_status', session('pre_registration.step1.civil_status')) == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                        <option value="Separated" {{ old('civil_status', session('pre_registration.step1.civil_status')) == 'Separated' ? 'selected' : '' }}>Separated</option>
                                        <option value="Divorced" {{ old('civil_status', session('pre_registration.step1.civil_status')) == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                    </select>
                                    @error('civil_status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Navigation -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('public.home') }}" class="btn btn-secondary d-flex align-items-center justify-content-center">
                                        <i class="fe fe-arrow-left fe-16 mr-2"></i>
                                        <span>Back to Home</span>
                                    </a>
                                    <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center">
                                        <span>Next: Contact & Education</span>
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
</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('css/pre-registration-form.css') }}">
@endsection