@extends('layouts.public.resident-registration')

@section('title', 'Senior Citizen Pre-Registration - Step 4')

@section('form-title', 'Senior Citizen Pre-Registration')
@section('step-indicator', 'Step 4 of 5')

@section('form-content')
<div class="card-header bg-white border-0 pb-0">
  <h5 class="personal-header"><i class="fas fa-heart mr-2"></i>Senior Citizen Specific Information</h5>
  <small class="text-muted">Please provide health and benefit information to help us serve you better.</small>
</div>
<form role="form" id="seniorPreRegStep4Form" method="POST" action="{{ route('public.senior-registration.step4.store') }}" autocomplete="off">
  @csrf
  <div class="card-body">
    <!-- Health Information -->
    <div class="row mb-2">
      <div class="col-md-12">
        <h5 class="personal-header mb-2">
          <i class="fas fa-heartbeat mr-2"></i>Health Information
        </h5>
      </div>
    </div>
    <div class="row mb-3">
      <div class="col-md-6">
        <div class="form-group">
          <label for="health_condition" class="form-label mb-2">Health Condition</label>
          <select class="form-control custom-rounded-input @error('health_condition') is-invalid @enderror" 
                  id="health_condition" name="health_condition">
            <option value="">Select Health Condition</option>
            <option value="excellent" {{ old('health_condition', $step4['health_condition'] ?? '') == 'excellent' ? 'selected' : '' }}>Excellent</option>
            <option value="good" {{ old('health_condition', $step4['health_condition'] ?? '') == 'good' ? 'selected' : '' }}>Good</option>
            <option value="fair" {{ old('health_condition', $step4['health_condition'] ?? '') == 'fair' ? 'selected' : '' }}>Fair</option>
            <option value="poor" {{ old('health_condition', $step4['health_condition'] ?? '') == 'poor' ? 'selected' : '' }}>Poor</option>
            <option value="critical" {{ old('health_condition', $step4['health_condition'] ?? '') == 'critical' ? 'selected' : '' }}>Critical</option>
          </select>
          @error('health_condition')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="mobility_status" class="form-label mb-2">Mobility Status</label>
          <select class="form-control custom-rounded-input @error('mobility_status') is-invalid @enderror" 
                  id="mobility_status" name="mobility_status">
            <option value="">Select Mobility Status</option>
            <option value="independent" {{ old('mobility_status', $step4['mobility_status'] ?? '') == 'independent' ? 'selected' : '' }}>Independent</option>
            <option value="assisted" {{ old('mobility_status', $step4['mobility_status'] ?? '') == 'assisted' ? 'selected' : '' }}>Assisted</option>
            <option value="wheelchair" {{ old('mobility_status', $step4['mobility_status'] ?? '') == 'wheelchair' ? 'selected' : '' }}>Wheelchair Bound</option>
            <option value="bedridden" {{ old('mobility_status', $step4['mobility_status'] ?? '') == 'bedridden' ? 'selected' : '' }}>Bedridden</option>
          </select>
          @error('mobility_status')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
    </div>
    <div class="row mb-4">
      <div class="col-md-12">
        <div class="form-group">
          <label for="medical_conditions" class="form-label mb-2">Medical Conditions & Health Details</label>
          <textarea class="form-control custom-rounded-input @error('medical_conditions') is-invalid @enderror" 
                    id="medical_conditions" name="medical_conditions" rows="4" 
                    placeholder="List any medical conditions, medications, allergies, or health concerns...&#10;&#10;Example:&#10;- Hypertension (taking Amlodipine 5mg daily)&#10;- Diabetes Type 2 (insulin dependent)&#10;- Allergic to penicillin&#10;- Uses reading glasses">{{ old('medical_conditions', $step4['medical_conditions'] ?? '') }}</textarea>
          @error('medical_conditions')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="form-text text-muted">
            <strong>Optional but recommended:</strong> Include medical conditions, medications, allergies, and any special health requirements for better service delivery and emergency response.
          </small>
        </div>
      </div>
    </div>
    
    <!-- Pension and Benefits Information -->
    <div class="row mb-2 mt-5">
      <div class="col-md-12">
        <h5 class="personal-header mb-2">
          <i class="fas fa-money-check-alt mr-2"></i>Pension and Benefits Information
        </h5>
      </div>
    </div>
    <div class="row mb-3">
      <div class="col-md-6">
        <div class="form-group">
          <label for="receiving_pension" class="form-label mb-2">Are you currently receiving a pension?</label>
          <select class="form-control custom-rounded-input @error('receiving_pension') is-invalid @enderror" 
                  id="receiving_pension" name="receiving_pension">
            <option value="">Select option</option>
            <option value="1" {{ old('receiving_pension', $step4['receiving_pension'] ?? '') == '1' ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ old('receiving_pension', $step4['receiving_pension'] ?? '') == '0' ? 'selected' : '' }}>No</option>
          </select>
          @error('receiving_pension')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="pension_type" class="form-label text-muted mb-2">Type of Pension</label>
          <select class="form-control custom-rounded-input @error('pension_type') is-invalid @enderror" 
                  id="pension_type" name="pension_type" disabled>
            <option value="">Select pension type</option>
            <option value="SSS" {{ old('pension_type', $step4['pension_type'] ?? '') == 'SSS' ? 'selected' : '' }}>SSS Pension</option>
            <option value="GSIS" {{ old('pension_type', $step4['pension_type'] ?? '') == 'GSIS' ? 'selected' : '' }}>GSIS Pension</option>
            <option value="Government Employee" {{ old('pension_type', $step4['pension_type'] ?? '') == 'Government Employee' ? 'selected' : '' }}>Government Employee Pension</option>
            <option value="Private Company" {{ old('pension_type', $step4['pension_type'] ?? '') == 'Private Company' ? 'selected' : '' }}>Private Company Pension</option>
            <option value="Social Pension" {{ old('pension_type', $step4['pension_type'] ?? '') == 'Social Pension' ? 'selected' : '' }}>Social Pension</option>
            <option value="Other" {{ old('pension_type', $step4['pension_type'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
          </select>
          @error('pension_type')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
    </div>
    <div class="row mb-3">
      <div class="col-md-6">
        <div class="form-group">
          <label for="pension_amount" class="form-label text-muted mb-2">Monthly Pension Amount</label>
          <input type="number" class="form-control custom-rounded-input @error('pension_amount') is-invalid @enderror" 
                 id="pension_amount" name="pension_amount" 
                 value="{{ old('pension_amount', $step4['pension_amount'] ?? '') }}" 
                 placeholder="0.00" step="0.01" min="0" disabled>
          @error('pension_amount')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="form-text text-muted">Enter the approximate monthly pension amount (optional)</small>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="has_philhealth" class="form-label mb-2">Do you have PhilHealth?</label>
          <select class="form-control custom-rounded-input @error('has_philhealth') is-invalid @enderror" 
                  id="has_philhealth" name="has_philhealth">
            <option value="">Select option</option>
            <option value="1" {{ old('has_philhealth', $step4['has_philhealth'] ?? '') == '1' ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ old('has_philhealth', $step4['has_philhealth'] ?? '') == '0' ? 'selected' : '' }}>No</option>
          </select>
          @error('has_philhealth')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
    </div>
    <div class="row mb-4">
      <div class="col-md-6">
        <div class="form-group">
          <label for="philhealth_number" class="form-label text-muted mb-2">PhilHealth Number</label>
          <input type="text" class="form-control custom-rounded-input @error('philhealth_number') is-invalid @enderror" 
                 id="philhealth_number" name="philhealth_number" 
                 value="{{ old('philhealth_number', $step4['philhealth_number'] ?? '') }}" 
                 placeholder="XX-XXXXXXXXX-X" maxlength="14" disabled>
          @error('philhealth_number')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <small class="form-text text-muted">Format: XX-XXXXXXXXX-X (if available)</small>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group">
          <label for="has_senior_discount_card" class="form-label mb-2">Do you have a Senior Citizen Discount Card?</label>
          <select class="form-control custom-rounded-input @error('has_senior_discount_card') is-invalid @enderror" 
                  id="has_senior_discount_card" name="has_senior_discount_card">
            <option value="">Select option</option>
            <option value="1" {{ old('has_senior_discount_card', $step4['has_senior_discount_card'] ?? '') == '1' ? 'selected' : '' }}>Yes</option>
            <option value="0" {{ old('has_senior_discount_card', $step4['has_senior_discount_card'] ?? '') == '0' ? 'selected' : '' }}>No</option>
          </select>
          @error('has_senior_discount_card')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
    </div>
    
    <!-- Services -->
    <div class="row mb-2 mt-5">
      <div class="col-md-12">
        <h5 class="personal-header mb-2">
          <i class="fas fa-concierge-bell mr-2"></i>Requested Services
        </h5>
      </div>
    </div>
    <div class="row mb-4">
      <div class="col-md-6">
        <div class="custom-control custom-checkbox mb-1">
          <input type="checkbox" class="custom-control-input" id="service_health" name="services[]" value="healthcare" 
                 {{ in_array('healthcare', old('services', $step4['services'] ?? [])) ? 'checked' : '' }}>
          <label class="custom-control-label" for="service_health">Healthcare Services</label>
        </div>
        <div class="custom-control custom-checkbox mb-1">
          <input type="checkbox" class="custom-control-input" id="service_financial" name="services[]" value="financial_assistance" 
                 {{ in_array('financial_assistance', old('services', $step4['services'] ?? [])) ? 'checked' : '' }}>
          <label class="custom-control-label" for="service_financial">Financial Assistance</label>
        </div>
        <div class="custom-control custom-checkbox mb-1">
          <input type="checkbox" class="custom-control-input" id="service_education" name="services[]" value="education" 
                 {{ in_array('education', old('services', $step4['services'] ?? [])) ? 'checked' : '' }}>
          <label class="custom-control-label" for="service_education">Educational Programs</label>
        </div>
      </div>
      <div class="col-md-6">
        <div class="custom-control custom-checkbox mb-1">
          <input type="checkbox" class="custom-control-input" id="service_legal" name="services[]" value="legal_assistance" 
                 {{ in_array('legal_assistance', old('services', $step4['services'] ?? [])) ? 'checked' : '' }}>
          <label class="custom-control-label" for="service_legal">Legal Assistance</label>
        </div>
        <div class="custom-control custom-checkbox mb-1">
          <input type="checkbox" class="custom-control-input" id="service_transportation" name="services[]" value="transportation" 
                 {{ in_array('transportation', old('services', $step4['services'] ?? [])) ? 'checked' : '' }}>
          <label class="custom-control-label" for="service_transportation">Transportation Services</label>
        </div>
        <div class="custom-control custom-checkbox mb-1">
          <input type="checkbox" class="custom-control-input" id="service_discount" name="services[]" value="discount_privileges" 
                 {{ in_array('discount_privileges', old('services', $step4['services'] ?? [])) ? 'checked' : '' }}>
          <label class="custom-control-label" for="service_discount">Discount Privileges</label>
        </div>
        <div class="custom-control custom-checkbox mb-1">
          <input type="checkbox" class="custom-control-input" id="service_emergency" name="services[]" value="emergency_response" 
                 {{ in_array('emergency_response', old('services', $step4['services'] ?? [])) ? 'checked' : '' }}>
          <label class="custom-control-label" for="service_emergency">Emergency Response</label>
        </div>
      </div>
    </div>
    
    <!-- Additional Notes -->
    <div class="row mt-1">
      <div class="col-md-12">
        <div class="form-group">
          <label for="notes" class="form-label mb-2">Additional Notes</label>
          <textarea class="form-control custom-rounded-input @error('notes') is-invalid @enderror" 
                    id="notes" name="notes" rows="3" 
                    placeholder="Any additional information or special requirements...">{{ old('notes', $step4['notes'] ?? '') }}</textarea>
          @error('notes')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
    </div>
  </div>
  <div class="card-footer">
    <div class="row">
      <div class="col-md-6">
        <a href="{{ route('public.senior-registration.step3') }}" class="btn btn-outline-secondary w-100">
        
          Previous
        </a>
      </div>
      <div class="col-md-6">
        <button type="submit" class="btn bg-gradient-dark w-100">
          Next
        </button>
      </div>
    </div>
  </div>
</form>
@endsection

@push('scripts')
<script src="{{ asset('js/resident-registration.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wait for page to fully load
    setTimeout(function() {
        initializeFieldToggles();
    }, 100);
});

function initializeFieldToggles() {
    console.log('Initializing field toggles...');
    
    // Get elements
    const receivingPensionSelect = document.getElementById('receiving_pension');
    const pensionTypeSelect = document.getElementById('pension_type');
    const pensionAmountInput = document.getElementById('pension_amount');
    const hasPhilhealthSelect = document.getElementById('has_philhealth');
    const philhealthNumberInput = document.getElementById('philhealth_number');
    
    // Function to enable pension fields
    function enablePensionFields() {
        console.log('Enabling pension fields');
        pensionTypeSelect.disabled = false;
        pensionAmountInput.disabled = false;
        pensionTypeSelect.removeAttribute('disabled');
        pensionAmountInput.removeAttribute('disabled');
        
        // Remove muted class from labels
        const pensionTypeLabel = document.querySelector('label[for="pension_type"]');
        const pensionAmountLabel = document.querySelector('label[for="pension_amount"]');
        if (pensionTypeLabel) pensionTypeLabel.classList.remove('text-muted');
        if (pensionAmountLabel) pensionAmountLabel.classList.remove('text-muted');
        
        console.log('Pension type disabled:', pensionTypeSelect.disabled);
        console.log('Pension amount disabled:', pensionAmountInput.disabled);
    }
    
    // Function to disable pension fields
    function disablePensionFields() {
        console.log('Disabling pension fields');
        pensionTypeSelect.disabled = true;
        pensionAmountInput.disabled = true;
        
        // Add muted class to labels
        const pensionTypeLabel = document.querySelector('label[for="pension_type"]');
        const pensionAmountLabel = document.querySelector('label[for="pension_amount"]');
        if (pensionTypeLabel) pensionTypeLabel.classList.add('text-muted');
        if (pensionAmountLabel) pensionAmountLabel.classList.add('text-muted');
        
        // Clear values if "No" is selected
        if (receivingPensionSelect.value === '0') {
            pensionTypeSelect.value = '';
            pensionAmountInput.value = '';
        }
    }
    
    // Function to enable PhilHealth field
    function enablePhilhealthField() {
        console.log('Enabling PhilHealth field');
        philhealthNumberInput.disabled = false;
        philhealthNumberInput.removeAttribute('disabled');
        
        const philhealthLabel = document.querySelector('label[for="philhealth_number"]');
        if (philhealthLabel) philhealthLabel.classList.remove('text-muted');
        
        console.log('PhilHealth disabled:', philhealthNumberInput.disabled);
    }
    
    // Function to disable PhilHealth field
    function disablePhilhealthField() {
        console.log('Disabling PhilHealth field');
        philhealthNumberInput.disabled = true;
        
        const philhealthLabel = document.querySelector('label[for="philhealth_number"]');
        if (philhealthLabel) philhealthLabel.classList.add('text-muted');
        
        // Clear value if "No" is selected
        if (hasPhilhealthSelect.value === '0') {
            philhealthNumberInput.value = '';
        }
    }
    
    // Function to handle pension toggle
    function handlePensionToggle() {
        const value = receivingPensionSelect.value;
        console.log('Pension changed to:', value);
        
        if (value === '1') {
            enablePensionFields();
        } else {
            disablePensionFields();
        }
    }
    
    // Function to handle PhilHealth toggle
    function handlePhilhealthToggle() {
        const value = hasPhilhealthSelect.value;
        console.log('PhilHealth changed to:', value);
        
        if (value === '1') {
            enablePhilhealthField();
        } else {
            disablePhilhealthField();
        }
    }
    
    // Add event listeners
    if (receivingPensionSelect) {
        receivingPensionSelect.addEventListener('change', handlePensionToggle);
        // Initial check
        handlePensionToggle();
    }
    
    if (hasPhilhealthSelect) {
        hasPhilhealthSelect.addEventListener('change', handlePhilhealthToggle);
        // Initial check
        handlePhilhealthToggle();
    }
    
    // Check for server-side values
    @if(old('receiving_pension', $step4['receiving_pension'] ?? '') == '1')
        console.log('Server data: enabling pension fields');
        enablePensionFields();
    @endif
    
    @if(old('has_philhealth', $step4['has_philhealth'] ?? '') == '1')
        console.log('Server data: enabling PhilHealth field');
        enablePhilhealthField();
    @endif
    
    // Pension amount formatting with peso sign and .00
    if (pensionAmountInput) {
        pensionAmountInput.addEventListener('input', function() {
            // Remove all non-numeric characters except decimal point
            let value = this.value.replace(/[^\d.]/g, '');
            
            // Ensure only one decimal point
            let parts = value.split('.');
            if (parts.length > 2) {
                value = parts[0] + '.' + parts.slice(1).join('');
            }
            
            // Format as currency if there's a value
            if (value) {
                // Ensure two decimal places
                if (parts.length === 2) {
                    // Limit decimal places to 2
                    parts[1] = parts[1].substring(0, 2);
                    value = parts[0] + '.' + parts[1];
                }
                
                // Add .00 if no decimal part and user finished typing
                if (!value.includes('.') && value.length > 0) {
                    // Don't auto-add .00 while typing, only on blur
                }
                
                this.value = value;
            }
        });
        
        // Format with .00 on blur if needed
        pensionAmountInput.addEventListener('blur', function() {
            if (this.value && !this.disabled) {
                let value = parseFloat(this.value);
                if (!isNaN(value)) {
                    this.value = value.toFixed(2);
                }
            }
        });
        
        // Format initial value if exists
        if (pensionAmountInput.value) {
            let value = parseFloat(pensionAmountInput.value);
            if (!isNaN(value)) {
                pensionAmountInput.value = value.toFixed(2);
            }
        }
    }
    
    // PhilHealth number formatting
    if (philhealthNumberInput) {
        philhealthNumberInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, ''); // Remove non-digits
            if (value.length >= 2) {
                value = value.substring(0, 2) + '-' + value.substring(2);
            }
            if (value.length >= 12) {
                value = value.substring(0, 12) + '-' + value.substring(12);
            }
            this.value = value.substring(0, 14); // Limit to XX-XXXXXXXXX-X format
        });
    }
}
</script>
@endpush
