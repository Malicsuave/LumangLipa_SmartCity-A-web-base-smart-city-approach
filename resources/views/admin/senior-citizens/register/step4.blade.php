@extends('layouts.admin.master')

@section('page-header', 'New Senior Citizen Registration')

@section('page-header-extra')
    <p class="text-muted mb-0">Complete the pension and benefits information for the senior citizen</p>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.senior-citizens.index') }}">Senior Citizens</a></li>
    <li class="breadcrumb-item active">New Registration</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/registration-form.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">
                    <i class="fas fa-money-check-alt mr-2"></i>
                    Pension & Benefits Information
                </strong>
                <div class="card-tools">
                    <span class="badge badge-light">Step 4 of 5</span>
                </div>
            </div>
            <form action="{{ route('admin.senior-citizens.register.step4.store') }}" method="POST" id="step4Form">
                @csrf
                <div class="card-body registration-form">
                    <!-- Pension and Benefits Information -->
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-money-check-alt mr-2"></i>Pension and Benefits Information
                            </h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="receiving_pension" class="form-label">Are you currently receiving a pension?</label>
                                <select class="form-control @error('receiving_pension') is-invalid @enderror" 
                                        id="receiving_pension" name="receiving_pension">
                                    <option value="">Select option</option>
                                    <option value="1" {{ old('receiving_pension', session('senior_registration.step4.receiving_pension')) == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('receiving_pension', session('senior_registration.step4.receiving_pension')) == '0' ? 'selected' : '' }}>No</option>
                                </select>
                                @error('receiving_pension')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pension_type" class="form-label text-muted">Type of Pension</label>
                                <select class="form-control @error('pension_type') is-invalid @enderror bg-light" 
                                        id="pension_type" name="pension_type" disabled>
                                    <option value="">Select pension type</option>
                                    <option value="SSS" {{ old('pension_type', session('senior_registration.step4.pension_type')) == 'SSS' ? 'selected' : '' }}>SSS Pension</option>
                                    <option value="GSIS" {{ old('pension_type', session('senior_registration.step4.pension_type')) == 'GSIS' ? 'selected' : '' }}>GSIS Pension</option>
                                    <option value="Government Employee" {{ old('pension_type', session('senior_registration.step4.pension_type')) == 'Government Employee' ? 'selected' : '' }}>Government Employee Pension</option>
                                    <option value="Private Company" {{ old('pension_type', session('senior_registration.step4.pension_type')) == 'Private Company' ? 'selected' : '' }}>Private Company Pension</option>
                                    <option value="Social Pension" {{ old('pension_type', session('senior_registration.step4.pension_type')) == 'Social Pension' ? 'selected' : '' }}>Social Pension</option>
                                    <option value="Other" {{ old('pension_type', session('senior_registration.step4.pension_type')) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('pension_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="pension_amount" class="form-label text-muted">Monthly Pension Amount</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">₱</span>
                                    </div>
                                    <input type="number" class="form-control @error('pension_amount') is-invalid @enderror bg-light" 
                                           id="pension_amount" name="pension_amount" 
                                           value="{{ old('pension_amount', session('senior_registration.step4.pension_amount')) }}" 
                                           placeholder="0.00" step="0.01" min="0" disabled>
                                    @error('pension_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">Enter the approximate monthly pension amount (optional)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="has_philhealth" class="form-label">Do you have PhilHealth?</label>
                                <select class="form-control @error('has_philhealth') is-invalid @enderror" 
                                        id="has_philhealth" name="has_philhealth">
                                    <option value="">Select option</option>
                                    <option value="1" {{ old('has_philhealth', session('senior_registration.step4.has_philhealth')) == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('has_philhealth', session('senior_registration.step4.has_philhealth')) == '0' ? 'selected' : '' }}>No</option>
                                </select>
                                @error('has_philhealth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="philhealth_number" class="form-label text-muted">PhilHealth Number</label>
                                <input type="text" class="form-control @error('philhealth_number') is-invalid @enderror bg-light" 
                                       id="philhealth_number" name="philhealth_number" 
                                       value="{{ old('philhealth_number', session('senior_registration.step4.philhealth_number')) }}" 
                                       placeholder="XX-XXXXXXXXX-X" maxlength="14" disabled>
                                @error('philhealth_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Format: XX-XXXXXXXXX-X (if available)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="has_senior_discount_card" class="form-label">Do you have a Senior Citizen Discount Card?</label>
                                <select class="form-control @error('has_senior_discount_card') is-invalid @enderror" 
                                        id="has_senior_discount_card" name="has_senior_discount_card">
                                    <option value="">Select option</option>
                                    <option value="1" {{ old('has_senior_discount_card', session('senior_registration.step4.has_senior_discount_card')) == '1' ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ old('has_senior_discount_card', session('senior_registration.step4.has_senior_discount_card')) == '0' ? 'selected' : '' }}>No</option>
                                </select>
                                @error('has_senior_discount_card')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('admin.senior-citizens.register.step3') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>Previous: Photo & Signature
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-primary">
                                Continue to Step 5 <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/form-validation.js') }}"></script>
<script>
$(document).ready(function() {
    // Handle pension fields enable/disable
    function togglePensionFields() {
        const receivingPension = $('#receiving_pension').val();
        if (receivingPension === '1') {
            $('#pension_type, #pension_amount').prop('disabled', false).removeClass('bg-light');
            $('#pension_type, #pension_amount').closest('.form-group').find('label').removeClass('text-muted');
        } else {
            $('#pension_type, #pension_amount').prop('disabled', true).addClass('bg-light');
            $('#pension_type, #pension_amount').closest('.form-group').find('label').addClass('text-muted');
            if (receivingPension === '0') {
                $('#pension_type').val('');
                $('#pension_amount').val('');
            }
        }
    }
    
    // Handle PhilHealth number field enable/disable
    function togglePhilhealthFields() {
        const hasPhilhealth = $('#has_philhealth').val();
        if (hasPhilhealth === '1') {
            $('#philhealth_number').prop('disabled', false).removeClass('bg-light');
            $('#philhealth_number').closest('.form-group').find('label').removeClass('text-muted');
        } else {
            $('#philhealth_number').prop('disabled', true).addClass('bg-light');
            $('#philhealth_number').closest('.form-group').find('label').addClass('text-muted');
            if (hasPhilhealth === '0') {
                $('#philhealth_number').val('');
            }
        }
    }
    
    // Initial toggle - check current values and enable fields if needed
    togglePensionFields();
    togglePhilhealthFields();
    
    // If there are existing values, make sure fields are enabled
    @if(old('receiving_pension', session('senior_registration.step4.receiving_pension')) == '1')
        $('#pension_type, #pension_amount').prop('disabled', false).removeClass('bg-light');
        $('#pension_type, #pension_amount').closest('.form-group').find('label').removeClass('text-muted');
    @endif
    
    @if(old('has_philhealth', session('senior_registration.step4.has_philhealth')) == '1')
        $('#philhealth_number').prop('disabled', false).removeClass('bg-light');
        $('#philhealth_number').closest('.form-group').find('label').removeClass('text-muted');
    @endif
    
    // Event listeners
    $('#receiving_pension').change(togglePensionFields);
    $('#has_philhealth').change(togglePhilhealthFields);
    
    // PhilHealth number formatting
    $('#philhealth_number').on('input', function() {
        let value = this.value.replace(/\D/g, ''); // Remove non-digits
        if (value.length >= 2) {
            value = value.substring(0, 2) + '-' + value.substring(2);
        }
        if (value.length >= 12) {
            value = value.substring(0, 12) + '-' + value.substring(12);
        }
        this.value = value.substring(0, 14); // Limit to XX-XXXXXXXXX-X format
    });
});
</script>
@endpush
