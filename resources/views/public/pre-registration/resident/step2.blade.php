@extends('layouts.public.resident-registration')

@section('title', 'Resident Pre-Registration - Step 2')

@section('form-title', 'Resident Pre-Registration')
@section('step-indicator', 'Step 2 of 4')

@section('form-content')
<div class="card-header bg-white border-0 pb-0">
  <h5 class="personal-header"><i class="fas fa-phone mr-2"></i>Contact & Address Information</h5>
  <small class="text-muted">Provide your contact details, address, and emergency contact information.</small>
</div>
          <form role="form" id="residentPreRegStep2Form" method="POST" action="{{ route('public.pre-registration.step2.store') }}" autocomplete="off">
            @csrf
            <div class="card-body">
              <!-- Contact Information -->
              <div class="row mb-4">
                <div class="col-md-6 mb-3">
                  <label for="contact_number">Contact Number <span class="text-danger">*</span></label>
                  <input type="text" name="contact_number" id="contact_number" placeholder="09XXXXXXXXX"
                    class="form-control rounded-pill @error('contact_number') is-invalid @enderror" 
                    value="{{ old('contact_number', $step2['contact_number'] ?? '') }}" 
                    maxlength="11" pattern="[0-9]{11}" required>
                  @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="email_address">Email Address <small class="text-muted">(Optional)</small></label>
                  <input type="email" name="email_address" id="email_address" placeholder="email@example.com"
                    class="form-control rounded-pill @error('email_address') is-invalid @enderror" 
                    value="{{ old('email_address', $step2['email_address'] ?? '') }}">
                  <small class="form-text text-muted">
                    <i class="fas fa-info-circle text-info"></i> You can use a family member's email if you don't have one. Digital ID will be sent here when approved.
                  </small>
                  @error('email_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
              <!-- Current Address -->
              <div class="row mb-4">
                <div class="col-md-12">
                  <label for="address">Current Address <span class="text-danger">*</span></label>
                  <textarea name="address" id="address" rows="3" placeholder="Enter your complete address"
                    class="form-control @error('address') is-invalid @enderror" required>{{ old('address', $step2['address'] ?? '') }}</textarea>
                  @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
              <!-- Emergency Contact -->
              <hr>
              <h5 class="emergency-header mb-3"><i class="fas fa-user-shield mr-2"></i>Emergency Contact</h5>
              <small class="text-muted d-block mb-3">Person to contact in case of emergency</small>
              <div class="row mb-4">
                <div class="col-md-6 mb-3">
                  <label for="emergency_contact_name">Contact Person <span class="text-danger">*</span></label>
                  <input type="text" name="emergency_contact_name" id="emergency_contact_name" placeholder="Contact Person Name"
                    class="form-control rounded-pill @error('emergency_contact_name') is-invalid @enderror" 
                    value="{{ old('emergency_contact_name', $step2['emergency_contact_name'] ?? '') }}" required>
                  @error('emergency_contact_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="emergency_contact_relationship">Relationship <span class="text-danger">*</span></label>
                  <select name="emergency_contact_relationship" id="emergency_contact_relationship"
                    class="form-control selectpicker rounded-pill @error('emergency_contact_relationship') is-invalid @enderror" 
                    required data-style="btn-white">
                    <option value="">Select relationship</option>
                    <option value="Parent" {{ old('emergency_contact_relationship', $step2['emergency_contact_relationship'] ?? '') == 'Parent' ? 'selected' : '' }}>Parent</option>
                    <option value="Spouse" {{ old('emergency_contact_relationship', $step2['emergency_contact_relationship'] ?? '') == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                    <option value="Child" {{ old('emergency_contact_relationship', $step2['emergency_contact_relationship'] ?? '') == 'Child' ? 'selected' : '' }}>Child</option>
                    <option value="Sibling" {{ old('emergency_contact_relationship', $step2['emergency_contact_relationship'] ?? '') == 'Sibling' ? 'selected' : '' }}>Sibling</option>
                    <option value="Relative" {{ old('emergency_contact_relationship', $step2['emergency_contact_relationship'] ?? '') == 'Relative' ? 'selected' : '' }}>Relative</option>
                    <option value="Friend" {{ old('emergency_contact_relationship', $step2['emergency_contact_relationship'] ?? '') == 'Friend' ? 'selected' : '' }}>Friend</option>
                    <option value="Other" {{ old('emergency_contact_relationship', $step2['emergency_contact_relationship'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                  </select>
                  @error('emergency_contact_relationship')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
              <div class="row mb-4">
                <div class="col-md-6 mb-3">
                  <label for="emergency_contact_number">Contact Number <span class="text-danger">*</span></label>
                  <input type="text" name="emergency_contact_number" id="emergency_contact_number" placeholder="09XXXXXXXXX"
                    class="form-control rounded-pill @error('emergency_contact_number') is-invalid @enderror" 
                    value="{{ old('emergency_contact_number', $step2['emergency_contact_number'] ?? '') }}" 
                    maxlength="11" pattern="[0-9]{11}" required>
                  @error('emergency_contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="emergency_contact_address">Address <span class="text-danger">*</span></label>
                  <input type="text" name="emergency_contact_address" id="emergency_contact_address" placeholder="Enter address"
                    class="form-control rounded-pill @error('emergency_contact_address') is-invalid @enderror" 
                    value="{{ old('emergency_contact_address', $step2['emergency_contact_address'] ?? '') }}" required>
                  @error('emergency_contact_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6 mt-2">
                  <a href="{{ route('public.pre-registration.step1') }}" class="btn btn-outline-secondary w-100">Previous</a>
                </div>
                <div class="col-md-6 mt-2">
                  <button type="submit" class="btn bg-gradient-dark w-100">Next</button>
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
