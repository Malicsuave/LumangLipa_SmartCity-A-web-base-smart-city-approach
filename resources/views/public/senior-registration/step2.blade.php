@extends('layouts.public.resident-registration')

@section('title', 'Senior Citizen Pre-Registration - Step 2')

@section('form-title', 'Senior Citizen Pre-Registration')
@section('step-indicator', 'Step 2 of 5')

@section('form-content')
<div class="card-header bg-white border-0 pb-0">
  <h5 class="personal-header"><i class="fas fa-phone mr-2"></i>Contact & Address Information</h5>
  <small class="text-muted">Provide your contact details, address, and emergency contact information.</small>
</div>
<form role="form" id="seniorPreRegStep2Form" method="POST" action="{{ route('public.senior-registration.step2.store') }}" autocomplete="off">
  @csrf
  <div class="card-body">
    <!-- Contact Information -->
    <div class="row mb-4">
      <div class="col-md-6 mb-3">
        <label for="contact_number">Contact Number <span class="text-danger">*</span></label>
        <input type="text" 
               name="contact_number" 
               id="contact_number" 
               placeholder="09XXXXXXXXX"
               class="form-control rounded-pill"
               value="{{ old('contact_number', $step2['contact_number'] ?? '') }}"
               maxlength="11" 
               pattern="[0-9]{11}" 
               required>
        <div class="validation-error" data-field="contact_number" style="display: none; color: #dc3545; font-size: 0.875em; margin-top: 0.25rem;"></div>
      </div>
      <div class="col-md-6 mb-3">
        <label for="email_address">Email Address <small class="text-muted">(Optional)</small></label>
        <input type="email" 
               name="email_address" 
               id="email_address" 
               placeholder="email@example.com"
               class="form-control rounded-pill @error('email_address') is-invalid @enderror"
               value="{{ old('email_address', $step2['email_address'] ?? '') }}">
        <small class="form-text text-muted">
          <i class="fas fa-info-circle text-info"></i> You can use a family member's email if you don't have one. Digital ID will be sent here when approved.
        </small>
        @error('email_address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        <div id="email-validation-error">
         
        </div>
      </div>
    </div>
    <!-- Current Address -->
    <div class="row mb-4">
      <div class="col-md-8">
        <label for="address">Current Address <span class="text-danger">*</span></label>
        <textarea name="address" 
                  id="address" 
                  rows="3" 
                  placeholder="Enter your complete address"
                  class="form-control" 
                  required>{{ old('address', $step2['address'] ?? '') }}</textarea>
        <div class="validation-error" data-field="address" style="display: none; color: #dc3545; font-size: 0.875em; margin-top: 0.25rem;"></div>
      </div>
      <div class="col-md-4">
        <label for="purok">Purok <span class="text-danger">*</span></label>
        <select name="purok" 
                id="purok"
                class="form-control selectpicker rounded-pill" 
                required 
                data-style="btn-white">
          <option value="">Select Purok</option>
          <option value="Purok 1" {{ old('purok', $step2['purok'] ?? '') == 'Purok 1' ? 'selected' : '' }}>Purok 1</option>
          <option value="Purok 2" {{ old('purok', $step2['purok'] ?? '') == 'Purok 2' ? 'selected' : '' }}>Purok 2</option>
          <option value="Purok 3" {{ old('purok', $step2['purok'] ?? '') == 'Purok 3' ? 'selected' : '' }}>Purok 3</option>
          <option value="Purok 4" {{ old('purok', $step2['purok'] ?? '') == 'Purok 4' ? 'selected' : '' }}>Purok 4</option>
          <option value="Purok 5" {{ old('purok', $step2['purok'] ?? '') == 'Purok 5' ? 'selected' : '' }}>Purok 5</option>
          <option value="Purok 6" {{ old('purok', $step2['purok'] ?? '') == 'Purok 6' ? 'selected' : '' }}>Purok 6</option>
          <option value="Purok 7" {{ old('purok', $step2['purok'] ?? '') == 'Purok 7' ? 'selected' : '' }}>Purok 7</option>
          <option value="Purok 8" {{ old('purok', $step2['purok'] ?? '') == 'Purok 8' ? 'selected' : '' }}>Purok 8</option>
          <option value="Purok 9" {{ old('purok', $step2['purok'] ?? '') == 'Purok 9' ? 'selected' : '' }}>Purok 9</option>
          <option value="Purok 10" {{ old('purok', $step2['purok'] ?? '') == 'Purok 10' ? 'selected' : '' }}>Purok 10</option>
          <option value="Other" {{ old('purok', $step2['purok'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
        </select>
        <div class="validation-error" data-field="purok" style="display: none; color: #dc3545; font-size: 0.875em; margin-top: 0.25rem;"></div>
      </div>
    </div>
    <!-- Custom Purok Input (shown when "Other" is selected) -->
    <div class="row mb-5" id="custom-purok-row" style="display: none;">
      <div class="col-md-12">
        <div class="form-group">
          <label for="custom_purok" class="form-label">Specify Purok/Sitio <span class="text-danger">*</span></label>
          <input type="text" class="form-control @error('custom_purok') is-invalid @enderror" 
                 id="custom_purok" name="custom_purok" 
                 value="{{ old('custom_purok', $step2['custom_purok'] ?? '') }}" 
                 placeholder="Enter purok or sitio name">
          @error('custom_purok')
              <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
    </div>
    <!-- Emergency Contact -->
    <div class="row">
      <div class="col-md-12">
        <h5 class="personal-header">
          <i class="fas fa-user-shield mr-2"></i>Emergency Contact
        </h5>
        <small class="text-muted d-block mb-3">Person to contact in case of emergency</small>
      </div>
    </div>
    <div class="row mb-4">
      <div class="col-md-6 mb-3">
        <label for="emergency_contact_name">Contact Person <span class="text-danger">*</span></label>
        <input type="text" 
               name="emergency_contact_name" 
               id="emergency_contact_name" 
               placeholder="Contact Person Name"
               class="form-control rounded-pill"
               value="{{ old('emergency_contact_name', $step2['emergency_contact_name'] ?? '') }}" 
               required>
        <div class="validation-error" data-field="emergency_contact_name" style="display: none; color: #dc3545; font-size: 0.875em; margin-top: 0.25rem;"></div>
      </div>
      <div class="col-md-6 mb-3">
        <label for="emergency_contact_relationship">Relationship <span class="text-danger">*</span></label>
        <select name="emergency_contact_relationship" 
                id="emergency_contact_relationship"
                class="form-control selectpicker rounded-pill" 
                required 
                data-style="btn-white">
          <option value="">Select relationship</option>
          <option value="Parent" {{ old('emergency_contact_relationship', $step2['emergency_contact_relationship'] ?? '') == 'Parent' ? 'selected' : '' }}>Parent</option>
          <option value="Spouse" {{ old('emergency_contact_relationship', $step2['emergency_contact_relationship'] ?? '') == 'Spouse' ? 'selected' : '' }}>Spouse</option>
          <option value="Child" {{ old('emergency_contact_relationship', $step2['emergency_contact_relationship'] ?? '') == 'Child' ? 'selected' : '' }}>Child</option>
          <option value="Sibling" {{ old('emergency_contact_relationship', $step2['emergency_contact_relationship'] ?? '') == 'Sibling' ? 'selected' : '' }}>Sibling</option>
          <option value="Relative" {{ old('emergency_contact_relationship', $step2['emergency_contact_relationship'] ?? '') == 'Relative' ? 'selected' : '' }}>Relative</option>
          <option value="Friend" {{ old('emergency_contact_relationship', $step2['emergency_contact_relationship'] ?? '') == 'Friend' ? 'selected' : '' }}>Friend</option>
          <option value="Other" {{ old('emergency_contact_relationship', $step2['emergency_contact_relationship'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
        </select>
        <div class="validation-error" data-field="emergency_contact_relationship" style="display: none; color: #dc3545; font-size: 0.875em; margin-top: 0.25rem;"></div>
      </div>
    </div>
    <div class="row mb-4">
      <div class="col-md-6 mb-3">
        <label for="emergency_contact_number">Contact Number <span class="text-danger">*</span></label>
        <input type="text" 
               name="emergency_contact_number" 
               id="emergency_contact_number" 
               placeholder="09XXXXXXXXX"
               class="form-control rounded-pill"
               value="{{ old('emergency_contact_number', $step2['emergency_contact_number'] ?? '') }}" 
               maxlength="11" 
               pattern="[0-9]{11}" 
               required>
        <div class="validation-error" data-field="emergency_contact_number" style="display: none; color: #dc3545; font-size: 0.875em; margin-top: 0.25rem;"></div>
      </div>
      <div class="col-md-6 mb-3">
        <label for="emergency_contact_address">Address <span class="text-danger">*</span></label>
        <input type="text" 
               name="emergency_contact_address" 
               id="emergency_contact_address" 
               placeholder="Enter address"
               class="form-control rounded-pill"
               value="{{ old('emergency_contact_address', $step2['emergency_contact_address'] ?? '') }}" 
               required>
        <div class="validation-error" data-field="emergency_contact_address" style="display: none; color: #dc3545; font-size: 0.875em; margin-top: 0.25rem;"></div>
      </div>
    </div>
    
    
    <div class="row">
      <div class="col-md-6 mt-2">
        <a href="{{ route('public.senior-registration.step1') }}" class="btn btn-outline-secondary w-100">Previous</a>
      </div>
      <div class="col-md-6 mt-2">
        <button type="submit" class="btn bg-gradient-dark w-100">Next</button>
      </div>
    </div>
  </div>
</form>

@push('scripts')
<script>
  // Provide server-side validation data to the unified JavaScript
  window.serverErrors = @json($errors->toArray());
  window.hasFormSubmission = {{ old('_token') ? 'true' : 'false' }};
  
  // Email validation
  document.addEventListener('DOMContentLoaded', function() {
      const emailInput = document.getElementById('email_address');
      const emailError = document.getElementById('email-validation-error');
      const form = document.getElementById('seniorPreRegStep2Form');
      
      // Email validation regex
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      
      // Real-time validation on input
      emailInput.addEventListener('input', function() {
          const value = this.value.trim();
          
          if (value === '') {
              // Empty is allowed (optional field)
              this.classList.remove('is-invalid');
              emailError.style.display = 'none';
          } else if (!emailRegex.test(value)) {
              // Invalid email format
              this.classList.add('is-invalid');
              emailError.style.display = 'block';
          } else {
              // Valid email
              this.classList.remove('is-invalid');
              emailError.style.display = 'none';
          }
      });
      
      // Validate on blur (when user leaves the field)
      emailInput.addEventListener('blur', function() {
          const value = this.value.trim();
          
          if (value !== '' && !emailRegex.test(value)) {
              this.classList.add('is-invalid');
              emailError.style.display = 'block';
          }
      });
      
      // Validate on form submit
      form.addEventListener('submit', function(e) {
          const value = emailInput.value.trim();
          
          if (value !== '' && !emailRegex.test(value)) {
              e.preventDefault();
              emailInput.classList.add('is-invalid');
              emailError.style.display = 'block';
              emailInput.focus();
              return false;
          }
      });
  });
  
  // Show/hide custom purok input based on selection
  document.addEventListener('DOMContentLoaded', function() {
      const purokSelect = document.getElementById('purok');
      const customPurokRow = document.getElementById('custom-purok-row');
      const customPurokInput = document.getElementById('custom_purok');
      
      purokSelect.addEventListener('change', function() {
          if (this.value === 'Other') {
              customPurokRow.style.display = 'block';
              customPurokInput.setAttribute('required', 'required');
          } else {
              customPurokRow.style.display = 'none';
              customPurokInput.removeAttribute('required');
              customPurokInput.value = ''; // Clear the input
          }
      });
  });
</script>
@endpush
@endsection
