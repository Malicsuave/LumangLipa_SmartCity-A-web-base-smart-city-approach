@extends('layouts.public.resident-registration')

@section('title', 'Resident Pre-Registration - Step 4')

@section('form-title', 'Resident Pre-Registration')
@section('step-indicator', 'Step 4 of 4')

@section('form-content')
<div class="card-header bg-white border-0 pb-0">
  <h5 class="personal-header"><i class="fas fa-check-circle mr-2"></i>Review & Submit</h5>
  <p class="text-muted mb-0">Please review all information before submitting. You can go back to edit any section.</p>
</div>

<div class="card-body">
  <!-- Personal Information -->
  <div class="mb-4">
    <div class="row mb-3">
      <div class="col-md-12">
        <h5 class="text-primary border-bottom pb-2">
          <i class="fas fa-user mr-2"></i>Personal Information
          <a href="{{ route('public.pre-registration.step1') }}" class="btn btn-sm btn-outline-secondary float-right">
            <i class="fas fa-edit mr-1"></i>Edit
          </a>
        </h5>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <div class="info-item">
          <strong class="info-label">Type of Resident:</strong>
          <div class="info-value text-muted">{{ session('registration.step1.type_of_resident', 'N/A') }}</div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="info-item">
          <strong class="info-label">Full Name:</strong>
          <div class="info-value text-muted">
            {{ session('registration.step1.first_name', '') }} 
            {{ session('registration.step1.middle_name', '') }} 
            {{ session('registration.step1.last_name', '') }}
            {{ session('registration.step1.suffix', '') }}
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="info-item">
          <strong class="info-label">Birthdate:</strong>
          <div class="info-value text-muted">{{ session('registration.step1.birthdate', 'N/A') }}</div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="info-item">
          <strong class="info-label">Sex:</strong>
          <div class="info-value text-muted">{{ session('registration.step1.sex', 'N/A') }}</div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="info-item">
          <strong class="info-label">Civil Status:</strong>
          <div class="info-value text-muted">{{ session('registration.step1.civil_status', 'N/A') }}</div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="info-item">
          <strong class="info-label">Citizenship:</strong>
          <div class="info-value text-muted">
            {{ session('registration.step1.citizenship_type', 'N/A') }}
            @if(session('registration.step1.citizenship_country'))
              - {{ session('registration.step1.citizenship_country') }}
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Contact Information -->
  <div class="mb-4">
    <div class="row mb-3">
      <div class="col-md-12">
        <h5 class="text-primary border-bottom pb-2">
          <i class="fas fa-phone mr-2"></i>Contact Information
          <a href="{{ route('public.pre-registration.step2') }}" class="btn btn-sm btn-outline-secondary float-right">
            <i class="fas fa-edit mr-1"></i>Edit
          </a>
        </h5>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <div class="info-item">
          <strong class="info-label">Contact Number:</strong>
          <div class="info-value text-muted">{{ session('registration.step2.contact_number', 'N/A') }}</div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="info-item">
          <strong class="info-label">Email Address:</strong>
          <div class="info-value text-muted">{{ session('registration.step2.email_address', 'N/A') }}</div>
        </div>
      </div>
      <div class="col-md-12 mb-3">
        <div class="info-item">
          <strong class="info-label">Current Address:</strong>
          <div class="info-value text-muted">{{ session('registration.step2.current_address', 'N/A') }}</div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="info-item">
          <strong class="info-label">Emergency Contact Person:</strong>
          <div class="info-value text-muted">{{ session('registration.step2.emergency_contact_name', 'N/A') }}</div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="info-item">
          <strong class="info-label">Emergency Contact Number:</strong>
          <div class="info-value text-muted">{{ session('registration.step2.emergency_contact_number', 'N/A') }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Education & Work Information -->
  <div class="mb-4">
    <div class="row mb-3">
      <div class="col-md-12">
        <h5 class="text-primary border-bottom pb-2">
          <i class="fas fa-graduation-cap mr-2"></i>Education & Work Information
          <a href="{{ route('public.pre-registration.step3') }}" class="btn btn-sm btn-outline-secondary float-right">
            <i class="fas fa-edit mr-1"></i>Edit
          </a>
        </h5>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <div class="info-item">
          <strong class="info-label">Educational Attainment:</strong>
          <div class="info-value text-muted">{{ session('registration.step3.educational_attainment', 'N/A') }}</div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="info-item">
          <strong class="info-label">Education Status:</strong>
          <div class="info-value text-muted">{{ session('registration.step3.education_status', 'N/A') }}</div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="info-item">
          <strong class="info-label">Occupation:</strong>
          <div class="info-value text-muted">{{ session('registration.step3.occupation', 'N/A') }}</div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="info-item">
          <strong class="info-label">Employment Status:</strong>
          <div class="info-value text-muted">{{ session('registration.step3.employment_status', 'N/A') }}</div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="info-item">
          <strong class="info-label">Monthly Income:</strong>
          <div class="info-value text-muted">{{ session('registration.step3.monthly_income', 'N/A') }}</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Terms and Conditions -->
  <div class="mb-4">
    <div class="alert alert-info">
      <h6><i class="fas fa-info-circle mr-2"></i>Data Privacy Notice</h6>
      <p class="mb-0 small">
        By submitting this form, you consent to the collection and processing of your personal data 
        for the purpose of barangay resident registration in accordance with the Data Privacy Act of 2012 (RA 10173). 
        Your information will be kept confidential and will only be used for official barangay transactions and services.
      </p>
    </div>
    
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="agree_terms" name="agree_terms" required>
      <label class="form-check-label" for="agree_terms">
        I hereby certify that the information provided above is true and correct to the best of my knowledge. 
        I understand that any false information may result in the rejection of my application. 
        <span class="text-danger">*</span>
      </label>
    </div>
  </div>

  <!-- Submit Form -->
  <form role="form" id="residentPreRegStep4Form" method="POST" action="{{ route('public.pre-registration.step4.store') }}">
    @csrf
    <input type="hidden" name="agree_terms" id="agree_terms_hidden" value="0">
    
    <div class="row">
      <div class="col-md-6 mt-2">
        <a href="{{ route('public.pre-registration.step3') }}" class="btn btn-outline-secondary w-100">
          <i class="fas fa-arrow-left"></i> Previous
        </a>
      </div>
      <div class="col-md-6 mt-2">
        <button type="submit" class="btn bg-gradient-success w-100" id="submitBtn" disabled>
          <i class="fas fa-paper-plane mr-2"></i>Submit Registration
        </button>
      </div>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const agreeCheckbox = document.getElementById('agree_terms');
  const submitBtn = document.getElementById('submitBtn');
  const agreeHidden = document.getElementById('agree_terms_hidden');
  
  agreeCheckbox.addEventListener('change', function() {
    submitBtn.disabled = !this.checked;
    agreeHidden.value = this.checked ? '1' : '0';
    
    if (this.checked) {
      submitBtn.classList.remove('btn-secondary');
      submitBtn.classList.add('bg-gradient-success');
    } else {
      submitBtn.classList.remove('bg-gradient-success');
      submitBtn.classList.add('btn-secondary');
    }
  });
  
  // Form submission confirmation
  document.getElementById('residentPreRegStep4Form').addEventListener('submit', function(e) {
    if (!agreeCheckbox.checked) {
      e.preventDefault();
      alert('Please agree to the terms and conditions before submitting.');
      return false;
    }
    
    if (!confirm('Are you sure you want to submit your registration? Please make sure all information is correct.')) {
      e.preventDefault();
      return false;
    }
  });
});
</script>
@endpush
