@extends('layouts.public.resident-registration')

@section('title', 'Registration Successful')

@section('form-title', 'Registration Successful!')
@section('step-indicator', 'Complete')

@section('form-content')
<div class="card-body text-center py-5">
  <div class="success-icon mb-4">
    <i class="fas fa-check-circle" style="font-size: 5rem; color: #28a745;"></i>
  </div>
  
  <h2 class="mb-3" style="color: #28a745; font-weight: 600;">Registration Submitted Successfully!</h2>
  
  <p class="lead mb-4">Thank you for completing your resident pre-registration.</p>
  
  @if(session('registration_id'))
  <!-- Registration ID Display -->
  <div class="mx-auto mb-4" style="max-width: 500px; border: 2px solid #007bff; border-radius: 10px; padding: 1.5rem; background-color: #fff;">
    <h6 class="mb-1" style="color: #6c757d;">Your Registration ID:</h6>
    <h3 class="mb-0 font-weight-bold" style="color: #007bff;">{{ session('registration_id') }}</h3>
    <small style="color: #6c757d;">Please save this ID for reference</small>
  </div>
  @endif
  
  <div class="mx-auto" style="max-width: 600px; border: 2px solid #17a2b8; border-radius: 10px; padding: 1.5rem; background-color: #fff;">
    <h5 style="color: #17a2b8; font-weight: 600; margin-bottom: 1rem;">
      <i class="fas fa-info-circle mr-2"></i>What's Next?
    </h5>
    <hr style="border-top: 1px solid #dee2e6;">
    <ul class="text-left mb-0" style="list-style-type: none; padding-left: 0;">
      <li class="mb-2"><i class="fas fa-check mr-2" style="color: #28a745;"></i> Your application has been submitted to the Barangay Office</li>
      <li class="mb-2"><i class="fas fa-envelope mr-2" style="color: #007bff;"></i> You will receive updates via email</li>
      <li class="mb-2"><i class="fas fa-clock mr-2" style="color: #ffc107;"></i> Review process takes 2-3 business days</li>
      <li class="mb-0"><i class="fas fa-building mr-2" style="color: #17a2b8;"></i> You may visit the barangay office for follow-up</li>
    </ul>
  </div>
  
  
  
  <div class="mt-4">
    <a href="{{ route('public.home') }}" class="btn btn-lg mr-2" style="background-color: #007bff; color: #fff; border: none; padding: 0.75rem 2rem; font-weight: 600; border-radius: 0.5rem; text-decoration: none;">
    Go to Homepage
    </a>
  </div> 
  
  <div class="mt-4">
    <small class="text-muted">
      <i class="fas fa-phone mr-1"></i> For inquiries, please contact the barangay office or call our hotline.
    </small>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Clear session storage used during registration
  if (typeof(Storage) !== "undefined") {
    sessionStorage.removeItem('pre_registration_step1');
    sessionStorage.removeItem('pre_registration_step2');
    sessionStorage.removeItem('pre_registration_step3');
  }
</script>
@endpush
