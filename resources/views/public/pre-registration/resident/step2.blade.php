@extends('layouts.public.resident-registration')

@section('title', 'Resident Pre-Registration - Step 2')

@section('form-title', 'Resident Pre-Registration')
@section('step-indicator', 'Step 2 of 4')

@section('form-content')
<div class="card-header bg-white border-0 pb-0">
  <h5 class="personal-header"><i class="fas fa-phone mr-2"></i>Contact & Education Information</h5>
</div>
          <form role="form" id="residentPreRegStep2Form" method="POST" action="{{ route('public.pre-registration.step2.store') }}" autocomplete="off">
            @csrf
            <div class="card-body">
              <!-- Contact Information -->
              <div class="row mb-4">
                <div class="col-md-6 mb-3">
                  <label for="contact_number">Contact Number <span class="text-danger">*</span></label>
                  <input type="tel" name="contact_number" id="contact_number" placeholder="09XXXXXXXXX"
                    class="form-control @error('contact_number') is-invalid @enderror" 
                    value="{{ old('contact_number', $step2['contact_number'] ?? '') }}" required>
                  @error('contact_number')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="email_address">Email Address <small class="text-muted">(Optional)</small></label>
                  <input type="email" name="email_address" id="email_address" placeholder="email@example.com (Optional)"
                    class="form-control @error('email_address') is-invalid @enderror" 
                    value="{{ old('email_address', $step2['email_address'] ?? '') }}">
                  @error('email_address')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                  <small class="form-text text-muted">Used for notifications and document delivery</small>
                </div>
              </div>
              
              <!-- Current Address -->
              <div class="row mb-4">
                <div class="col-md-12">
                  <label for="current_address">Current Address <span class="text-danger">*</span></label>
                  <textarea name="current_address" id="current_address" rows="3" placeholder="House No., Street, Purok/Sitio"
                    class="form-control @error('current_address') is-invalid @enderror" required>{{ old('current_address', $step2['current_address'] ?? '') }}</textarea>
                  @error('current_address')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
              </div>
              
              <!-- Emergency Contact -->
              <hr>
              <h5 class="emergency-header mb-3"><i class="fas fa-user-shield mr-2"></i>Emergency Contact</h5>
              <div class="row mb-4">
                <div class="col-md-6 mb-3">
                  <label for="emergency_contact_name">Contact Person <span class="text-danger">*</span></label>
                  <input type="text" name="emergency_contact_name" id="emergency_contact_name" placeholder="Full name of emergency contact"
                    class="form-control @error('emergency_contact_name') is-invalid @enderror" 
                    value="{{ old('emergency_contact_name', $step2['emergency_contact_name'] ?? '') }}" required>
                  @error('emergency_contact_name')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="emergency_contact_relationship">Relationship <span class="text-danger">*</span></label>
                  <select name="emergency_contact_relationship" id="emergency_contact_relationship"
                    class="form-control @error('emergency_contact_relationship') is-invalid @enderror" 
                    required>
                    <option value="">Select relationship</option>
                    <option value="Parent" {{ old('emergency_contact_relationship', $step2['emergency_contact_relationship'] ?? '') == 'Parent' ? 'selected' : '' }}>Parent</option>
                    <option value="Spouse" {{ old('emergency_contact_relationship', $step2['emergency_contact_relationship'] ?? '') == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                    <option value="Child" {{ old('emergency_contact_relationship', $step2['emergency_contact_relationship'] ?? '') == 'Child' ? 'selected' : '' }}>Child</option>
                    <option value="Sibling" {{ old('emergency_contact_relationship', $step2['emergency_contact_relationship'] ?? '') == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                    <option value="Relative" {{ old('emergency_contact_relationship', $step2['emergency_contact_relationship'] ?? '') == 'Relative' ? 'selected' : '' }}>Relative</option>
                    <option value="Friend" {{ old('emergency_contact_relationship', $step2['emergency_contact_relationship'] ?? '') == 'Friend' ? 'selected' : '' }}>Friend</option>
                    <option value="Other" {{ old('emergency_contact_relationship', $step2['emergency_contact_relationship'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                  </select>
                  @error('emergency_contact_relationship')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
              </div>
              
              <div class="row mb-4">
                <div class="col-md-6 mb-3">
                  <label for="emergency_contact_number">Emergency Contact Number <span class="text-danger">*</span></label>
                  <input type="tel" name="emergency_contact_number" id="emergency_contact_number" placeholder="09XXXXXXXXX"
                    class="form-control @error('emergency_contact_number') is-invalid @enderror" 
                    value="{{ old('emergency_contact_number', $step2['emergency_contact_number'] ?? '') }}" required>
                  @error('emergency_contact_number')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="emergency_contact_address">Emergency Contact Address</label>
                  <textarea name="emergency_contact_address" id="emergency_contact_address" rows="2" placeholder="Address of emergency contact (optional)"
                    class="form-control @error('emergency_contact_address') is-invalid @enderror">{{ old('emergency_contact_address', $step2['emergency_contact_address'] ?? '') }}</textarea>
                  @error('emergency_contact_address')<div class="invalid-feedback" data-server>{{ $message }}</div>@enderror
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6 mt-2">
                  <a href="{{ route('public.pre-registration.step1') }}" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-arrow-left"></i> Previous
                  </a>
                </div>
                <div class="col-md-6 mt-2">
                  <button type="submit" class="btn bg-gradient-dark w-100">
                    Continue to Step 3 <i class="fas fa-arrow-right ml-2"></i>
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
