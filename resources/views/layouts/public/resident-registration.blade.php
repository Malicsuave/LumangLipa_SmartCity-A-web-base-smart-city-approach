@extends('layouts.public.master')

@push('styles')
<!-- FontAwesome Override - Load before other styles -->
<style>
/* Force FontAwesome to load and display properly */
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css');

/* FontAwesome Icon Override - Force display */
.fas, .far, .fab, .fal, .fad, .fa {
  font-family: "Font Awesome 6 Free" !important;
  font-weight: 900 !important;
  font-style: normal !important;
  font-variant: normal !important;
  text-rendering: auto !important;
  line-height: 1 !important;
  display: inline-block !important;
  -webkit-font-smoothing: antialiased !important;
  -moz-osx-font-smoothing: grayscale !important;
}

.far {
  font-weight: 400 !important;
}

.fab {
  font-weight: 400 !important;
  font-family: "Font Awesome 6 Brands" !important;
}

/* Force specific icons with Unicode content */
i.fa-phone::before, .fa-phone::before { content: "\f095" !important; font-family: "Font Awesome 6 Free" !important; }
i.fa-user-shield::before, .fa-user-shield::before { content: "\f505" !important; font-family: "Font Awesome 6 Free" !important; }
i.fa-heartbeat::before, .fa-heartbeat::before { content: "\f21e" !important; font-family: "Font Awesome 6 Free" !important; }
i.fa-money-check-alt::before, .fa-money-check-alt::before { content: "\f53d" !important; font-family: "Font Awesome 6 Free" !important; }
i.fa-concierge-bell::before, .fa-concierge-bell::before { content: "\f562" !important; font-family: "Font Awesome 6 Free" !important; }
i.fa-camera::before, .fa-camera::before { content: "\f030" !important; font-family: "Font Awesome 6 Free" !important; }
i.fa-file-upload::before, .fa-file-upload::before { content: "\f574" !important; font-family: "Font Awesome 6 Free" !important; }
i.fa-arrow-left::before, .fa-arrow-left::before { content: "\f060" !important; font-family: "Font Awesome 6 Free" !important; }
i.fa-arrow-right::before, .fa-arrow-right::before { content: "\f061" !important; font-family: "Font Awesome 6 Free" !important; }
i.fa-info-circle::before, .fa-info-circle::before { content: "\f05a" !important; font-family: "Font Awesome 6 Free" !important; }

/* Icon spacing - add consistent margin between icons and text */
.fas.mr-2, .far.mr-2, .fab.mr-2, .fa.mr-2,
i.fas.mr-2, i.far.mr-2, i.fab.mr-2, i.fa.mr-2 {
  margin-right: 8px !important;
}

.fas.ml-2, .far.ml-2, .fab.ml-2, .fa.ml-2,
i.fas.ml-2, i.far.ml-2, i.fab.ml-2, i.fa.ml-2 {
  margin-left: 8px !important;
}

/* Additional spacing for header icons */
h5 .fas, h5 .far, h5 .fab, h5 .fa,
h5 i.fas, h5 i.far, h5 i.fab, h5 i.fa {
  margin-right: 10px !important;
}

/* Button icon spacing */
.btn .fas, .btn .far, .btn .fab, .btn .fa,
.btn i.fas, .btn i.far, .btn i.fab, .btn i.fa {
  margin-right: 6px !important;
}

.btn .fas:last-child, .btn .far:last-child, .btn .fab:last-child, .btn .fa:last-child,
.btn i.fas:last-child, .btn i.far:last-child, .btn i.fab:last-child, .btn i.fa:last-child {
  margin-right: 0 !important;
  margin-left: 6px !important;
}
</style>
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
