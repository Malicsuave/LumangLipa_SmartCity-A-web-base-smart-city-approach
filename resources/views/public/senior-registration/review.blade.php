@extends('layouts.public.resident-registration')

@section('title', 'Senior Citizen Pre-Registration - Review')

@section('form-title')
<i class="fas fa-clipboard-check mr-2"></i>Review Your Information
@endsection

@section('step-indicator', 'Step 5 of 5 - Review')

@section('form-content')
<div class="card-header bg-white border-0 pb-0 pt-0">
  <p class="text-muted small mb-0">Please review all information before submitting</p>
</div>

@if ($errors->any())
<div class="alert alert-danger mx-3 mt-3">
  <h5><i class="fas fa-exclamation-triangle"></i> Validation Errors</h5>
  <ul class="mb-0">
    @foreach ($errors->all() as $error)
      <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif

<form role="form" id="seniorPreRegReviewForm" method="POST" action="{{ route('public.senior-registration.submit') }}" autocomplete="off">
  @csrf
  <div class="card-body">
    @php
      $step1 = session('senior_registration.step1', []);
      $step2 = session('senior_registration.step2', []);
      $step3 = session('senior_registration.step3', []);
      $step4 = session('senior_registration.step4', []);
    @endphp
    <!-- Step 1: Personal Information -->
    <div class="card mb-3" style="border: 1px solid #dee2e6; border-radius: 10px;">
      <div class="card-header" style="background-color: #007bff; color: white; padding: 0.75rem 1rem;">
        <div class="d-flex justify-content-between align-items-center">
          <span class="mb-0" style="font-weight: 600; font-size: 1rem;"><i class="fas fa-user mr-2"></i>Personal Information</span>
          <a href="{{ route('public.senior-registration.step1') }}" class="text-white" style="font-weight: 600; text-decoration: none; font-size: 0.95rem;">
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
            <p class="mb-0">{{ $step1['first_name'] ?? '' }} {{ $step1['middle_name'] ?? '' }} {{ $step1['last_name'] ?? '' }} {{ $step1['suffix'] ?? '' }}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <strong>Date of Birth:</strong>
            <p class="mb-0">{{ isset($step1['birthdate']) ? \Carbon\Carbon::parse($step1['birthdate'])->format('F d, Y') : 'N/A' }}</p>
          </div>
          <div class="col-md-6 mb-3">
            <strong>Age:</strong>
            <p class="mb-0">{{ isset($step1['birthdate']) ? \Carbon\Carbon::parse($step1['birthdate'])->age : 'N/A' }} years old</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <strong>Place of Birth:</strong>
            <p class="mb-0">{{ $step1['birthplace'] ?? 'N/A' }}</p>
          </div>
          <div class="col-md-6 mb-3">
            <strong>Sex:</strong>
            <p class="mb-0">{{ $step1['sex'] ?? 'N/A' }}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <strong>Civil Status:</strong>
            <p class="mb-0">{{ $step1['civil_status'] ?? 'N/A' }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Step 2: Contact & Address Information -->
    <div class="card mb-3" style="border: 1px solid #dee2e6; border-radius: 10px;">
      <div class="card-header" style="background-color: #007bff; color: white; padding: 0.75rem 1rem;">
        <div class="d-flex justify-content-between align-items-center">
          <span class="mb-0" style="font-weight: 600; font-size: 1rem;"><i class="fas fa-phone mr-2"></i>Contact & Address Information</span>
          <a href="{{ route('public.senior-registration.step2') }}" class="text-white" style="font-weight: 600; text-decoration: none; font-size: 0.95rem;">
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
          <div class="col-md-6 mb-3">
            <strong>Current Address:</strong>
            <p class="mb-0">{{ $step2['address'] ?? 'N/A' }}</p>
          </div>
          <div class="col-md-3 mb-3">
            <strong>Purok:</strong>
            <p class="mb-0">{{ $step2['purok'] ?? 'N/A' }}</p>
          </div>
          <div class="col-md-3 mb-3">
            <strong>Specify Purok/Sitio:</strong>
            <p class="mb-0">{{ $step2['custom_purok'] ?? 'N/A' }}</p>
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

    <!-- Step 3: Photo, Signature & Documents -->
    <div class="card mb-3" style="border: 1px solid #dee2e6; border-radius: 10px;">
      <div class="card-header" style="background-color: #007bff; color: white; padding: 0.75rem 1rem;">
        <div class="d-flex justify-content-between align-items-center">
          <span class="mb-0" style="font-weight: 600; font-size: 1rem;"><i class="fas fa-camera mr-2"></i>Photo, Signature & Documents</span>
          <a href="{{ route('public.senior-registration.step3') }}" class="text-white" style="font-weight: 600; text-decoration: none; font-size: 0.95rem;">
            <i class="fas fa-edit" style="margin-right: 6px;"></i>Edit
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4 text-center">
            <strong>Photo:</strong>
            <div class="mt-2 mb-2">
              @if(isset($step3['photo']) && is_string($step3['photo']))
                <img src="{{ asset('storage/' . $step3['photo']) }}" alt="Photo Preview" style="max-width: 200px; max-height: 200px; border: 2px solid #dee2e6; border-radius: 10px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div class="alert alert-success" style="display: none;">
                  <i class="fas fa-check-circle"></i> Photo uploaded
                </div>
              @else
                <div class="alert alert-warning">
                  <i class="fas fa-exclamation-triangle"></i> No photo uploaded
                </div>
              @endif
            </div>
          </div>
          <div class="col-md-4 text-center">
            <strong>Signature:</strong>
            <div class="mt-2 mb-2">
              @if(isset($step3['signature']) && is_string($step3['signature']))
                <img src="{{ asset('storage/' . $step3['signature']) }}" alt="Signature Preview" style="max-width: 200px; max-height: 100px; border: 2px solid #dee2e6; border-radius: 10px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                <div class="alert alert-success" style="display: none;">
                  <i class="fas fa-check-circle"></i> Signature uploaded
                </div>
              @else
                <p class="mb-0 text-muted">No signature provided (Optional)</p>
              @endif
            </div>
          </div>
          <div class="col-md-4 text-center">
            <strong>Proof of Residency:</strong>
            <div class="mt-2 mb-2">
              @if(isset($step3['proof_of_residency']) && is_string($step3['proof_of_residency']))
                @php
                  $extension = pathinfo($step3['proof_of_residency'], PATHINFO_EXTENSION);
                  $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png']);
                  $isPdf = strtolower($extension) === 'pdf';
                  $proofPath = asset('storage/' . $step3['proof_of_residency']);
                @endphp
                @if($isImage)
                  <img src="{{ $proofPath }}" alt="Proof of Residency Preview" style="max-width: 200px; max-height: 200px; border: 2px solid #dee2e6; border-radius: 10px; object-fit: contain;">
                @elseif($isPdf)
                  <a href="{{ $proofPath }}" target="_blank" class="btn btn-sm btn-primary mt-2" style="background-color: #007bff; border-color: #007bff; color: #fff;">
                    <i class="fas fa-file-pdf"></i> View PDF
                  </a>
                @else
                  <span class="text-danger">Unsupported file type</span>
                @endif
              @else
                <div class="alert alert-warning">
                  <i class="fas fa-exclamation-triangle"></i> No document uploaded
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Step 4: Senior Citizen Specific Information -->
    <div class="card mb-3" style="border: 1px solid #dee2e6; border-radius: 10px;">
      <div class="card-header" style="background-color: #007bff; color: white; padding: 0.75rem 1rem;">
        <div class="d-flex justify-content-between align-items-center">
          <span class="mb-0" style="font-weight: 600; font-size: 1rem;"><i class="fas fa-heart mr-2"></i>Senior Citizen Specific Information</span>
          <a href="{{ route('public.senior-registration.step4') }}" class="text-white" style="font-weight: 600; text-decoration: none; font-size: 0.95rem;">
            <i class="fas fa-edit" style="margin-right: 6px;"></i>Edit
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 mb-3">
            <strong>Health Condition:</strong>
            <p class="mb-0">{{ isset($step4['health_condition']) ? ucfirst($step4['health_condition']) : 'Not specified' }}</p>
          </div>
          <div class="col-md-6 mb-3">
            <strong>Mobility Status:</strong>
            <p class="mb-0">{{ isset($step4['mobility_status']) ? ucfirst($step4['mobility_status']) : 'Not specified' }}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 mb-3">
            <strong>Medical Conditions & Health Details:</strong>
            <p class="mb-0">{{ $step4['medical_conditions'] ?? 'None specified' }}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <strong>Receiving Pension:</strong>
            <p class="mb-0">{{ isset($step4['receiving_pension']) && $step4['receiving_pension'] == '1' ? 'Yes' : 'No' }}</p>
          </div>
          <div class="col-md-6 mb-3">
            <strong>Pension Type:</strong>
            <p class="mb-0">{{ $step4['pension_type'] ?? 'N/A' }}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <strong>Monthly Pension Amount:</strong>
            <p class="mb-0">{{ isset($step4['pension_amount']) && $step4['pension_amount'] ? '₱' . number_format($step4['pension_amount'], 2) : 'N/A' }}</p>
          </div>
          <div class="col-md-6 mb-3">
            <strong>Has PhilHealth:</strong>
            <p class="mb-0">{{ isset($step4['has_philhealth']) && $step4['has_philhealth'] == '1' ? 'Yes' : 'No' }}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <strong>PhilHealth Number:</strong>
            <p class="mb-0">{{ $step4['philhealth_number'] ?? 'Not provided' }}</p>
          </div>
          <div class="col-md-6 mb-3">
            <strong>Has Senior Discount Card:</strong>
            <p class="mb-0">{{ isset($step4['has_senior_discount_card']) && $step4['has_senior_discount_card'] == '1' ? 'Yes' : 'No' }}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 mb-3">
            <strong>Requested Services:</strong>
            <div class="mt-2">
              @php
                $services = $step4['services'] ?? [];
              @endphp
              @if(count($services) > 0)
                @foreach($services as $service)
                  <span class="badge badge-info mr-2 mb-1" style="font-size: 0.9rem; padding: 0.4rem 0.8rem; background-color: #17a2b8; color: #fff;">
                    {{ ucwords(str_replace('_', ' ', $service)) }}
                  </span>
                @endforeach
              @else
                <p class="mb-0 text-muted">No services requested</p>
              @endif
            </div>
          </div>
        </div>
        @if(isset($step4['notes']) && $step4['notes'])
        <div class="row">
          <div class="col-md-12 mb-3">
            <strong>Additional Notes:</strong>
            <p class="mb-0">{{ $step4['notes'] }}</p>
          </div>
        </div>
        @endif
      </div>
    </div>

   <div class="card mb-4" style="border: 1px solid #dee2e6; border-radius: 10px; background-color: #f8f9fa;">
      <div class="card-body" style="padding: 1.5rem;">
        <div class="d-flex align-items-start">
          <input type="checkbox" id="confirmation" name="confirmation" value="1" required style="width: 18px; height: 18px; min-width: 18px; cursor: pointer; margin-top: 3px; margin-right: 12px; accent-color: #007bff;">
          <label for="confirmation" style="cursor: pointer; line-height: 1.6; margin: 0;">
            <strong style="color: #333;">I hereby certify that all information provided above is true and correct to the best of my knowledge.</strong>
            <span class="d-block mt-1" style="color: #6c757d; font-size: 0.95rem;">I understand that any false information may result in the rejection of my pre-registration.</span>
          </label>
        </div>
      </div>
    </div>
  
   <div class="row">
      <div class="col-md-6 mt-2">
        <a href="{{ route('public.senior-registration.step4') }}" class="btn btn-outline-secondary w-100">
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
@endsection
