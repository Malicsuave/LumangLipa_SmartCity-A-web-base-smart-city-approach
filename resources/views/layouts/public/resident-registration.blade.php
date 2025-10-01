@extends('layouts.public.master')

@section('content')
<style>
/* Override Material Kit to force bordered inputs - Match Material Kit "Input success" example */
#residentPreRegStep1Form input.form-control,
#residentPreRegStep1Form textarea.form-control,
#residentPreRegStep1Form select.form-control,
#residentPreRegStep1Form .input-group-static .form-control,
#residentPreRegStep1Form .input-group-static input.form-control,
#residentPreRegStep1Form .input-group-static select.form-control,
form[id*="PreReg"] input.form-control,
form[id*="PreReg"] textarea.form-control,
form[id*="PreReg"] select.form-control,
form[id*="PreReg"] .input-group-static .form-control,
form[id*="PreReg"] .input-group-static input.form-control,
form[id*="PreReg"] .input-group-static select.form-control {
  display: block !important;
  width: 100% !important;
  padding: 0.5rem 0.75rem !important;
  font-size: 0.875rem !important;
  font-weight: 400 !important;
  line-height: 1.4rem !important;
  color: #495057 !important;
  background-color: #fff !important;
  background-clip: padding-box !important;
  border: 1px solid #d2d6da !important;
  appearance: none !important;
  border-radius: 0.375rem !important;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out !important;
}

#residentPreRegStep1Form input.form-control:focus,
#residentPreRegStep1Form textarea.form-control:focus,
#residentPreRegStep1Form select.form-control:focus,
form[id*="PreReg"] input.form-control:focus,
form[id*="PreReg"] textarea.form-control:focus {
  color: #495057 !important;
  background-color: #fff !important;
  border-color: #344767 !important;
  outline: 0 !important;
  box-shadow: 0 0 0 2px rgba(52, 71, 103, 0.15) !important;
}

/* Success validation state */
#residentPreRegStep1Form input.form-control.is-valid,
#residentPreRegStep1Form textarea.form-control.is-valid,
#residentPreRegStep1Form select.form-control.is-valid,
#residentPreRegStep1Form .input-group-static input.form-control.is-valid,
#residentPreRegStep1Form .input-group-static select.form-control.is-valid,
form[id*="PreReg"] input.form-control.is-valid,
form[id*="PreReg"] textarea.form-control.is-valid,
form[id*="PreReg"] select.form-control.is-valid,
form[id*="PreReg"] .input-group-static input.form-control.is-valid,
form[id*="PreReg"] .input-group-static select.form-control.is-valid {
  border-color: #66bb6a !important;
  border-width: 2px !important;
  padding-right: 2.5rem !important;
  background-color: #fff !important;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 10 8'%3e%3cpath fill='%2366bb6a' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e") !important;
  background-repeat: no-repeat !important;
  background-position: right 0.75rem center !important;
  background-size: 1rem 1rem !important;
}

#residentPreRegStep1Form input.form-control.is-valid:focus,
#residentPreRegStep1Form textarea.form-control.is-valid:focus,
#residentPreRegStep1Form select.form-control.is-valid:focus,
#residentPreRegStep1Form .input-group-static input.form-control.is-valid:focus,
#residentPreRegStep1Form .input-group-static select.form-control.is-valid:focus,
form[id*="PreReg"] input.form-control.is-valid:focus,
form[id*="PreReg"] textarea.form-control.is-valid:focus,
form[id*="PreReg"] select.form-control.is-valid:focus,
form[id*="PreReg"] .input-group-static input.form-control.is-valid:focus,
form[id*="PreReg"] .input-group-static select.form-control.is-valid:focus {
  border-color: #66bb6a !important;
  box-shadow: 0 0 0 2px rgba(102, 187, 106, 0.25) !important;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 10 8'%3e%3cpath fill='%2366bb6a' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e") !important;
}

/* Error validation state */
#residentPreRegStep1Form input.form-control.is-invalid,
#residentPreRegStep1Form textarea.form-control.is-invalid,
#residentPreRegStep1Form select.form-control.is-invalid,
#residentPreRegStep1Form .input-group-static input.form-control.is-invalid,
#residentPreRegStep1Form .input-group-static select.form-control.is-invalid,
form[id*="PreReg"] input.form-control.is-invalid,
form[id*="PreReg"] textarea.form-control.is-invalid,
form[id*="PreReg"] select.form-control.is-invalid,
form[id*="PreReg"] .input-group-static input.form-control.is-invalid,
form[id*="PreReg"] .input-group-static select.form-control.is-invalid {
  border-color: #f44336 !important;
  border-width: 2px !important;
  padding-right: 2.5rem !important;
  background-color: #fff !important;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23f44336'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23f44336' stroke='none'/%3e%3c/svg%3e") !important;
  background-repeat: no-repeat !important;
  background-position: right 0.75rem center !important;
  background-size: 1rem 1rem !important;
}

#residentPreRegStep1Form input.form-control.is-invalid:focus,
#residentPreRegStep1Form textarea.form-control.is-invalid:focus,
#residentPreRegStep1Form select.form-control.is-invalid:focus,
#residentPreRegStep1Form .input-group-static input.form-control.is-invalid:focus,
#residentPreRegStep1Form .input-group-static select.form-control.is-invalid:focus,
form[id*="PreReg"] input.form-control.is-invalid:focus,
form[id*="PreReg"] textarea.form-control.is-invalid:focus,
form[id*="PreReg"] select.form-control.is-invalid:focus,
form[id*="PreReg"] .input-group-static input.form-control.is-invalid:focus,
form[id*="PreReg"] .input-group-static select.form-control.is-invalid:focus {
  border-color: #f44336 !important;
  box-shadow: 0 0 0 2px rgba(244, 67, 54, 0.25) !important;
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23f44336'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23f44336' stroke='none'/%3e%3c/svg%3e") !important;
}

/* Labels */
#residentPreRegStep1Form label,
form[id*="PreReg"] label {
  display: block;
  margin-bottom: 0.5rem;
  font-size: 0.875rem;
  font-weight: 500;
  color: #344767;
}

/* Validation feedback messages */
.invalid-feedback {
  display: block !important;
  width: 100%;
  margin-top: 0.25rem;
  font-size: 0.875rem;
  color: #f44336;
}

.valid-feedback {
  display: block !important;
  width: 100%;
  margin-top: 0.25rem;
  font-size: 0.875rem;
  color: #66bb6a;
}
</style>

<!-- Add top padding to push content down -->
<div style="padding-top: 100px;"></div>

<!-- Hero Section with Full Image Background -->
<section class="public-form-hero">
  <div class="public-form-hero-overlay"></div>
  <div class="container h-100 public-form-hero-content">
    <div class="row justify-content-center align-items-center h-100">
      <div class="col-lg-8 mx-auto">
        <!-- Hero content can be added here if needed -->
      </div>
    </div>
  </div>
</section>

<section class="public-form-outer">
  <div class="container">
    <div class="row">
      <div class="col-12 mx-auto">
        <div class="public-form-card card shadow">
          <!-- Card Header with Title and Step Indicator -->
          <div class="card-header bg-white border-0 pb-0 personal-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0" style="color: #1976d2; font-weight:700; font-size:1.5rem;">@yield('form-title', 'Resident Pre-Registration')</h4>
            <span class="badge bg-light text-dark">@yield('step-indicator', 'Step 1 of 4')</span>
          </div>

          <!-- Form Content -->
          @yield('form-content')
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
<!-- Resident Registration JavaScript -->
<script src="{{ asset('js/resident-registration.js') }}"></script>
@endpush
