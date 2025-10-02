@extends('layouts.public.resident-registration')

@section('title', 'Registration Successful')

@section('form-title', 'Registration Successful!')
@section('step-indicator', 'Complete')

@section('form-content')
<div class="card-body text-center py-5">
  <!-- Success Icon -->
  <div class="mb-4">
    <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
  </div>

  <!-- Success Message -->
  <h3 class="text-success mb-3">Registration Submitted Successfully!</h3>
  <p class="text-muted mb-2">
    Thank you for completing your pre-registration. Your application has been submitted and is now pending review.
  </p>

  @if(session('registration_id'))
  <!-- Registration ID Display -->
  <div class="card bg-primary text-white mx-auto mb-4" style="max-width: 500px;">
    <div class="card-body py-3">
      <h6 class="mb-1 text-white-50">Your Registration ID:</h6>
      <h3 class="mb-0 font-weight-bold">{{ session('registration_id') }}</h3>
      <small class="text-white-50">Please save this ID for reference</small>
    </div>
  </div>
  @endif

  <!-- Registration Details -->
  <div class="alert alert-info mx-auto" style="max-width: 600px;">
    <h5 class="mb-3"><i class="fas fa-info-circle"></i> What's Next?</h5>
    <div class="text-left">
      <ol class="mb-0">
        <li class="mb-2">Our staff will review your submitted documents within 2-3 business days</li>
        <li class="mb-2">You will receive an email notification about your application status</li>
        <li class="mb-2">If approved, you will be notified when your Resident ID is ready for pickup</li>
        <li class="mb-0">Please bring valid identification when claiming your ID at the barangay office</li>
      </ol>
    </div>
  </div>

  <!-- Reference Information -->
  @if(session('registration_email') || session('registration_phone'))
  <div class="card bg-light mx-auto mb-4" style="max-width: 500px;">
    <div class="card-body">
      <h6 class="mb-3"><i class="fas fa-bell"></i> Confirmation Sent</h6>
      
      @if(session('registration_email'))
      <p class="mb-2 text-muted">
        <i class="fas fa-envelope text-primary"></i> Email sent to:<br>
        <strong>{{ session('registration_email') }}</strong>
      </p>
      @endif
      
      @if(session('registration_phone'))
      <p class="mb-0 text-muted">
        <i class="fas fa-sms text-success"></i> SMS sent to:<br>
        <strong>{{ session('registration_phone') }}</strong>
      </p>
      <small class="text-muted d-block mt-2" style="font-size: 0.85rem;">
        <i class="fas fa-info-circle"></i> Check your phone for SMS confirmation (works for Globe, Smart, Sun, TNT, TM)
      </small>
      @endif
    </div>
  </div>
  @endif

  <!-- Important Reminders -->
  <div class="alert alert-warning mx-auto" style="max-width: 600px;">
    <h6 class="mb-2"><i class="fas fa-exclamation-triangle"></i> Important Reminders</h6>
    <ul class="text-left mb-0 small">
      <li>Check your email regularly for updates on your application</li>
      <li>Keep your email and contact number accessible</li>
      <li>If you don't receive a confirmation email within 24 hours, please check your spam folder</li>
      <li>For inquiries, contact the barangay office during business hours</li>
    </ul>
  </div>

  <!-- Action Buttons -->
  <div class="mt-4">
    <a href="{{ route('public.home') }}" class="btn btn-primary btn-lg">
      <i class="fas fa-home mr-2"></i> Return to Home
    </a>
    <a href="{{ route('public.pre-registration.step1') }}" class="btn btn-outline-secondary btn-lg ml-2">
      <i class="fas fa-plus mr-2"></i> Submit Another Registration
    </a>
  </div>

  <!-- Contact Information -->
  <div class="mt-5 pt-4 border-top">
    <h6 class="text-muted mb-3">Need Help?</h6>
    <p class="text-muted small mb-2">
      <i class="fas fa-phone mr-2"></i> Contact us: [Barangay Office Phone Number]<br>
      <i class="fas fa-envelope mr-2"></i> Email: [Barangay Email Address]<br>
      <i class="fas fa-clock mr-2"></i> Office Hours: Monday to Friday, 8:00 AM - 5:00 PM
    </p>
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
