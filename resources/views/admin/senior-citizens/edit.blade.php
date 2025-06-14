@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.senior-citizens.index') }}">Senior Citizens</a></li>
<li class="breadcrumb-item active" aria-current="page">Update</li>
@endsection

@section('page-title', 'Update Senior Citizen')
@section('page-subtitle', 'Update information for ' . $seniorCitizen->resident->full_name)

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="fe fe-edit-3 fe-16 mr-2"></i>Update Senior Citizen Information</h4>
                        <p class="text-muted mb-0">{{ $seniorCitizen->resident->full_name }} - {{ $seniorCitizen->resident->barangay_id }}</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.senior-citizens.index') }}" class="btn btn-outline-secondary">
                            <i class="fe fe-arrow-left fe-16 mr-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fe fe-check-circle fe-16 mr-2"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fe fe-alert-circle fe-16 mr-2"></i> {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif

                <form action="{{ route('admin.senior-citizens.update', $seniorCitizen) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Senior ID Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="senior_id_number" class="form-label">Senior ID Number</label>
                                <input type="text" class="form-control @error('senior_id_number') is-invalid @enderror" 
                                       id="senior_id_number" name="senior_id_number" 
                                       value="{{ old('senior_id_number', $seniorCitizen->senior_id_number) }}" 
                                       placeholder="Enter senior ID number">
                                @error('senior_id_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="senior_id_expires_at" class="form-label">ID Expiration Date</label>
                                <input type="date" class="form-control @error('senior_id_expires_at') is-invalid @enderror" 
                                       id="senior_id_expires_at" name="senior_id_expires_at" 
                                       value="{{ old('senior_id_expires_at', $seniorCitizen->senior_id_expires_at ? $seniorCitizen->senior_id_expires_at->format('Y-m-d') : '') }}">
                                @error('senior_id_expires_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Health Information -->
                    <h5 class="mb-3"><i class="fe fe-heart fe-16 mr-2"></i>Health Information</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="blood_type" class="form-label">Blood Type</label>
                                <select class="form-control @error('blood_type') is-invalid @enderror" id="blood_type" name="blood_type">
                                    <option value="">Select Blood Type</option>
                                    <option value="A+" {{ old('blood_type', $seniorCitizen->blood_type) == 'A+' ? 'selected' : '' }}>A+</option>
                                    <option value="A-" {{ old('blood_type', $seniorCitizen->blood_type) == 'A-' ? 'selected' : '' }}>A-</option>
                                    <option value="B+" {{ old('blood_type', $seniorCitizen->blood_type) == 'B+' ? 'selected' : '' }}>B+</option>
                                    <option value="B-" {{ old('blood_type', $seniorCitizen->blood_type) == 'B-' ? 'selected' : '' }}>B-</option>
                                    <option value="AB+" {{ old('blood_type', $seniorCitizen->blood_type) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                    <option value="AB-" {{ old('blood_type', $seniorCitizen->blood_type) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                    <option value="O+" {{ old('blood_type', $seniorCitizen->blood_type) == 'O+' ? 'selected' : '' }}>O+</option>
                                    <option value="O-" {{ old('blood_type', $seniorCitizen->blood_type) == 'O-' ? 'selected' : '' }}>O-</option>
                                </select>
                                @error('blood_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="living_arrangement" class="form-label">Living Arrangement</label>
                                <input type="text" class="form-control @error('living_arrangement') is-invalid @enderror" 
                                       id="living_arrangement" name="living_arrangement" 
                                       value="{{ old('living_arrangement', $seniorCitizen->living_arrangement) }}" 
                                       placeholder="e.g., With family, Alone, Nursing home">
                                @error('living_arrangement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="health_conditions" class="form-label">Health Conditions</label>
                                <textarea class="form-control @error('health_conditions') is-invalid @enderror" 
                                          id="health_conditions" name="health_conditions" rows="3" 
                                          placeholder="List any chronic conditions, illnesses, or health concerns">{{ old('health_conditions', $seniorCitizen->health_conditions) }}</textarea>
                                @error('health_conditions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="medications" class="form-label">Current Medications</label>
                                <textarea class="form-control @error('medications') is-invalid @enderror" 
                                          id="medications" name="medications" rows="3" 
                                          placeholder="List current medications and dosages">{{ old('medications', $seniorCitizen->medications) }}</textarea>
                                @error('medications')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="allergies" class="form-label">Allergies</label>
                                <textarea class="form-control @error('allergies') is-invalid @enderror" 
                                          id="allergies" name="allergies" rows="3" 
                                          placeholder="List any known allergies">{{ old('allergies', $seniorCitizen->allergies) }}</textarea>
                                @error('allergies')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <h5 class="mb-3"><i class="fe fe-phone fe-16 mr-2"></i>Emergency Contact</h5>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="emergency_contact_name" class="form-label">Contact Name</label>
                                <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                                       id="emergency_contact_name" name="emergency_contact_name" 
                                       value="{{ old('emergency_contact_name', $seniorCitizen->emergency_contact_name) }}" 
                                       placeholder="Full name of emergency contact">
                                @error('emergency_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="emergency_contact_number" class="form-label">Contact Number</label>
                                <input type="text" class="form-control @error('emergency_contact_number') is-invalid @enderror" 
                                       id="emergency_contact_number" name="emergency_contact_number" 
                                       value="{{ old('emergency_contact_number', $seniorCitizen->emergency_contact_number) }}" 
                                       placeholder="Phone number">
                                @error('emergency_contact_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="emergency_contact_relationship" class="form-label">Relationship</label>
                                <input type="text" class="form-control @error('emergency_contact_relationship') is-invalid @enderror" 
                                       id="emergency_contact_relationship" name="emergency_contact_relationship" 
                                       value="{{ old('emergency_contact_relationship', $seniorCitizen->emergency_contact_relationship) }}" 
                                       placeholder="e.g., Son, Daughter, Spouse">
                                @error('emergency_contact_relationship')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Benefits Information -->
                    <h5 class="mb-3"><i class="fe fe-gift fe-16 mr-2"></i>Benefits & Support</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="receiving_pension" name="receiving_pension" value="1" 
                                           {{ old('receiving_pension', $seniorCitizen->receiving_pension) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="receiving_pension">
                                        Receiving Pension
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="pension_type" class="form-label">Pension Type</label>
                                <input type="text" class="form-control @error('pension_type') is-invalid @enderror" 
                                       id="pension_type" name="pension_type" 
                                       value="{{ old('pension_type', $seniorCitizen->pension_type) }}" 
                                       placeholder="e.g., SSS, GSIS, Private">
                                @error('pension_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="pension_amount" class="form-label">Monthly Pension Amount</label>
                                <input type="number" class="form-control @error('pension_amount') is-invalid @enderror" 
                                       id="pension_amount" name="pension_amount" 
                                       value="{{ old('pension_amount', $seniorCitizen->pension_amount) }}" 
                                       placeholder="0.00" step="0.01">
                                @error('pension_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="has_philhealth" name="has_philhealth" value="1" 
                                           {{ old('has_philhealth', $seniorCitizen->has_philhealth) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="has_philhealth">
                                        Has PhilHealth
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="philhealth_number" class="form-label">PhilHealth Number</label>
                                <input type="text" class="form-control @error('philhealth_number') is-invalid @enderror" 
                                       id="philhealth_number" name="philhealth_number" 
                                       value="{{ old('philhealth_number', $seniorCitizen->philhealth_number) }}" 
                                       placeholder="PhilHealth ID number">
                                @error('philhealth_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="has_senior_discount_card" name="has_senior_discount_card" value="1" 
                                           {{ old('has_senior_discount_card', $seniorCitizen->has_senior_discount_card) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="has_senior_discount_card">
                                        Has Senior Citizen Discount Card
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fe fe-save fe-16 mr-2"></i>Update Senior Citizen Information
                        </button>
                        <a href="{{ route('admin.senior-citizens.index') }}" class="btn btn-outline-secondary btn-lg ml-2">
                            <i class="fe fe-x fe-16 mr-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection