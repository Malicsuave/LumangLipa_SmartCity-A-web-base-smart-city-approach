@extends('layouts.public.resident-registration')

@section('title', 'Resident Pre-Registration - Review')

@section('form-title')
<i class="fas fa-clipboard-check mr-2"></i>Review Your Information
@endsection

@section('step-indicator', 'Step 4 of 4 - Review')

@section('form-content')
<div class="card-header bg-white border-0 pb-0 pt-0">
  <p class="text-muted small mb-0">Please review all information before submitting</p>
</div>
<form role="form" id="residentPreRegReviewForm" method="POST" action="{{ route('public.pre-registration.submit') }}" autocomplete="off">
  @csrf
  <div class="card-body">
    @php
      $step1 = session('pre_registration.step1', []);
      $step2 = session('pre_registration.step2', []);
      $step3 = session('pre_registration.step3', []);
    @endphp

    <!-- Step 1: Personal Information -->
    <div class="card mb-3" style="border: 1px solid #dee2e6; border-radius: 10px;">
      <div class="card-header" style="background-color: #007bff; color: white; padding: 0.75rem 1rem;">
        <div class="d-flex justify-content-between align-items-center">
          <span class="mb-0" style="font-weight: 600; font-size: 1rem;"><i class="fas fa-user mr-2"></i>Personal Information</span>
          <a href="{{ route('public.pre-registration.step1') }}" class="text-white" style="font-weight: 600; text-decoration: none; font-size: 0.95rem;">
            <i class="fas fa-edit" style="margin-right: 6px;"></i>Edit
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 mb-3">
            <strong>Type of Resident:</strong>
            <p class="mb-0">{{ $step1['type_of_resident'] ?? 'N/A' }}</p>
          </div>
          <div class="col-md-6 mb-3">
            <strong>Full Name:</strong>
            <p class="mb-0">
              {{ $step1['first_name'] ?? '' }} 
              {{ $step1['middle_name'] ?? '' }} 
              {{ $step1['last_name'] ?? '' }} 
              {{ $step1['suffix'] ?? '' }}
            </p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <strong>Date of Birth:</strong>
            <p class="mb-0">{{ isset($step1['birthdate']) ? \Carbon\Carbon::parse($step1['birthdate'])->format('F d, Y') : 'N/A' }}</p>
          </div>
          <div class="col-md-6 mb-3">
            <strong>Place of Birth:</strong>
            <p class="mb-0">{{ $step1['birthplace'] ?? 'N/A' }}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <strong>Gender:</strong>
            <p class="mb-0">{{ $step1['sex'] ?? 'N/A' }}</p>
          </div>
          <div class="col-md-6 mb-3">
            <strong>Civil Status:</strong>
            <p class="mb-0">{{ $step1['civil_status'] ?? 'N/A' }}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <strong>Citizenship:</strong>
            <p class="mb-0">
              {{ $step1['citizenship_type'] ?? 'N/A' }}
              @if(isset($step1['citizenship_country']) && $step1['citizenship_country'])
                ({{ $step1['citizenship_country'] }})
              @endif
            </p>
          </div>
          <div class="col-md-6 mb-3">
            <strong>Religion:</strong>
            <p class="mb-0">{{ $step1['religion'] ?? 'N/A' }}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <strong>Educational Attainment:</strong>
            <p class="mb-0">{{ $step1['educational_attainment'] ?? 'N/A' }}</p>
          </div>
          <div class="col-md-6 mb-3">
            <strong>Education Status:</strong>
            <p class="mb-0">{{ $step1['education_status'] ?? 'N/A' }}</p>
          </div>
        </div>
        @if(isset($step1['profession_occupation']) && $step1['profession_occupation'])
        <div class="row">
          <div class="col-md-12 mb-3">
            <strong>Profession/Occupation:</strong>
            <p class="mb-0">{{ $step1['profession_occupation'] }}</p>
          </div>
        </div>
        @endif
      </div>
    </div>

    <!-- Step 2: Contact & Address Information -->
    <div class="card mb-3" style="border: 1px solid #dee2e6; border-radius: 10px;">
      <div class="card-header" style="background-color: #007bff; color: white; padding: 0.75rem 1rem;">
        <div class="d-flex justify-content-between align-items-center">
          <span class="mb-0" style="font-weight: 600; font-size: 1rem;"><i class="fas fa-phone mr-2"></i>Contact & Address Information</span>
          <a href="{{ route('public.pre-registration.step2') }}" class="text-white" style="font-weight: 600; text-decoration: none; font-size: 0.95rem;">
            <i class="fas fa-edit" style="margin-right: 6px;"></i>Edit
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 mb-3">
            <strong>Contact Number:</strong>
            <p class="mb-0">{{ $step2['contact_number'] ?? 'N/A' }}</p>
          </div>
          <div class="col-md-6 mb-3">
            <strong>Email Address:</strong>
            <p class="mb-0">{{ $step2['email_address'] ?? 'Not provided' }}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 mb-3">
            <strong>Current Address:</strong>
            <p class="mb-0">{{ $step2['address'] ?? 'N/A' }}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <strong>Emergency Contact:</strong>
            <p class="mb-0">{{ $step2['emergency_contact_name'] ?? 'N/A' }}</p>
          </div>
          <div class="col-md-6 mb-3">
            <strong>Relationship:</strong>
            <p class="mb-0">{{ $step2['emergency_contact_relationship'] ?? 'N/A' }}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <strong>Emergency Contact Number:</strong>
            <p class="mb-0">{{ $step2['emergency_contact_number'] ?? 'N/A' }}</p>
          </div>
          <div class="col-md-6 mb-3">
            <strong>Emergency Contact Address:</strong>
            <p class="mb-0">{{ $step2['emergency_contact_address'] ?? 'N/A' }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Step 3: Photo & Signature -->
    <div class="card mb-3" style="border: 1px solid #dee2e6; border-radius: 10px;">
      <div class="card-header" style="background-color: #007bff; color: white; padding: 0.75rem 1rem;">
        <div class="d-flex justify-content-between align-items-center">
          <span class="mb-0" style="font-weight: 600; font-size: 1rem;"><i class="fas fa-camera mr-2"></i>Photo & Signature</span>
          <a href="{{ route('public.pre-registration.step3') }}" class="text-white" style="font-weight: 600; text-decoration: none; font-size: 0.95rem;">
            <i class="fas fa-edit" style="margin-right: 6px;"></i>Edit
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 text-center">
            <strong>Photo:</strong>
            <div class="mt-2 mb-2">
              @if(session('temp_photo_preview'))
                <img src="{{ session('temp_photo_preview') }}" alt="Photo Preview" style="max-width: 200px; max-height: 200px; border: 2px solid #dee2e6; border-radius: 10px;">
              @elseif(isset($step3['photo']))
                <div class="alert alert-success">
                  <i class="fas fa-check-circle"></i> Photo uploaded
                </div>
              @else
                <div class="alert alert-warning">
                  <i class="fas fa-exclamation-triangle"></i> No photo uploaded
                </div>
              @endif
            </div>
          </div>
          <div class="col-md-6 text-center">
            <strong>Signature:</strong>
            <div class="mt-2 mb-2">
              @if(session('temp_signature_preview'))
                <img src="{{ session('temp_signature_preview') }}" alt="Signature Preview" style="max-width: 200px; max-height: 100px; border: 2px solid #dee2e6; border-radius: 10px;">
              @elseif(isset($step3['signature']))
                <div class="alert alert-success">
                  <i class="fas fa-check-circle"></i> Signature uploaded
                </div>
              @else
                <div class="alert alert-info">
                  <i class="fas fa-info-circle"></i> No signature provided (Optional)
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Confirmation -->
    <div class="card mb-4" style="border: 2px solid #007bff; border-radius: 10px;">
      <div class="card-body">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="final_confirmation" name="final_confirmation" value="1" required>
          <label class="form-check-label" for="final_confirmation">
            <strong>I hereby certify that all information provided above is true and correct to the best of my knowledge.</strong>
            I understand that any false information may result in the rejection of my pre-registration.
          </label>
        </div>
      </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="row">
      <div class="col-md-6 mt-2">
        <a href="{{ route('public.pre-registration.step3') }}" class="btn btn-outline-secondary w-100">
         Previous
        </a>
      </div>
      <div class="col-md-6 mt-2">
        <button type="submit" class="btn bg-gradient-dark w-100">
          Continue 
        </button>
      </div>
    </div>
  </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Form validation for confirmation checkbox
  const form = document.getElementById('residentPreRegStep4Form');
  const confirmationCheckbox = document.getElementById('final_confirmation');
  const submitButton = form.querySelector('button[type="submit"]');
  
  form.addEventListener('submit', function(e) {
    if (!confirmationCheckbox.checked) {
      e.preventDefault();
      alert('Please confirm that all information provided is true and correct.');
      return false;
    }
  });
});
</script>
@endpush
@endsection
