@extends('layouts.admin.master')

@section('title', 'Create Appointment Date')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create Health Appointment Date</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.health') }}">Health Services</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.health.appointment-dates.index') }}">Appointment Dates</a></li>
                <li class="breadcrumb-item active">Create</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Announcement Details</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.health.appointment-dates.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Announcement Title <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title') }}"
                               placeholder="e.g., Monthly Health Check-up"
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">This will appear in the announcement and booking form</small>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3"
                                  placeholder="Additional details about this health check-up...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="appointment_date" class="form-label">Appointment Date <span class="text-danger">*</span></label>
                            <input type="date" 
                                   class="form-control @error('appointment_date') is-invalid @enderror" 
                                   id="appointment_date" 
                                   name="appointment_date" 
                                   value="{{ old('appointment_date') }}"
                                   min="{{ date('Y-m-d') }}"
                                   required>
                            @error('appointment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('location') is-invalid @enderror" 
                                   id="location" 
                                   name="location" 
                                   value="{{ old('location', 'Barangay Health Center') }}"
                                   required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                            <input type="time" 
                                   class="form-control @error('start_time') is-invalid @enderror" 
                                   id="start_time" 
                                   name="start_time" 
                                   value="{{ old('start_time', '08:00') }}"
                                   required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                            <input type="time" 
                                   class="form-control @error('end_time') is-invalid @enderror" 
                                   id="end_time" 
                                   name="end_time" 
                                   value="{{ old('end_time', '17:00') }}"
                                   required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="max_slots" class="form-label">Maximum Slots <span class="text-danger">*</span></label>
                        <input type="number" 
                               class="form-control @error('max_slots') is-invalid @enderror" 
                               id="max_slots" 
                               name="max_slots" 
                               value="{{ old('max_slots', 50) }}"
                               min="1"
                               max="500"
                               required>
                        @error('max_slots')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Maximum number of residents who can book for this date</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fe fe-save me-2"></i>Create Appointment Date
                        </button>
                        <a href="{{ route('admin.health.appointment-dates.index') }}" class="btn btn-secondary">
                            <i class="fe fe-x me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <strong><i class="fe fe-info me-2"></i>Information</strong>
            </div>
            <div class="card-body">
                <h6 class="mb-3">How it works:</h6>
                <ol class="ps-3">
                    <li class="mb-2">Create an appointment date with the form</li>
                    <li class="mb-2">You can announce it on the announcements page</li>
                    <li class="mb-2">Residents will see it in the health appointment booking form</li>
                    <li class="mb-2">Residents book using their Barangay ID or QR code</li>
                    <li class="mb-2">View all bookings for this date from the list</li>
                </ol>

                <hr>

                <h6 class="mb-3">Tips:</h6>
                <ul class="ps-3">
                    <li class="mb-2">Choose dates at least 3-5 days in advance</li>
                    <li class="mb-2">Set realistic slot limits based on available staff</li>
                    <li class="mb-2">You can close bookings anytime if slots fill up early</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
