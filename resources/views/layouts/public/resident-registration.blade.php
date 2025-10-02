@extends('layouts.public.master')

@push('styles')
<!-- Resident Registration CSS -->
<link rel="stylesheet" href="{{ asset('css/resident-registration.css') }}?v={{ time() }}">
@endpush

@section('content')
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
