@extends('layouts.admin.master')

@section('title', 'Health Record Details')

@push('styles')
<style>
.info-card {
    border-left: 4px solid #4e73df;
}
.vital-box {
    background: #f8f9fc;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}
.vital-label {
    font-weight: 600;
    color: #5a5c69;
    font-size: 0.9rem;
}
.vital-value {
    font-size: 1.5rem;
    color: #2e59d9;
    font-weight: 700;
}
.timeline-item {
    border-left: 2px solid #e3e6f0;
    padding-left: 1.5rem;
    padding-bottom: 1.5rem;
    position: relative;
}
.timeline-item::before {
    content: '';
    position: absolute;
    left: -6px;
    top: 0;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #4e73df;
}
</style>
@endpush

@section('content')
<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-file-medical me-2"></i>Health Record Details
                </h1>
                <p class="text-muted mb-0">Complete health information for {{ $healthRecord->resident->full_name ?? 'Unknown Resident' }}</p>
            </div>
            <div class="btn-group">
                <a href="{{ route('admin.health-monitoring.edit', $healthRecord->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit Record
                </a>
                <a href="{{ route('admin.health-monitoring.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Resident Information -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card info-card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Resident Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p><strong>Name:</strong> {{ $healthRecord->resident->full_name ?? 'Unknown' }}</p>
                        <p><strong>Barangay ID:</strong> {{ $healthRecord->barangay_id }}</p>
                        <p><strong>Age:</strong> {{ $healthRecord->resident->age ?? 'N/A' }} years</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Sex:</strong> {{ $healthRecord->resident->sex ?? 'N/A' }}</p>
                        <p><strong>Birthdate:</strong> {{ $healthRecord->resident->birthdate ? $healthRecord->resident->birthdate->format('F d, Y') : 'N/A' }}</p>
                        <p><strong>Contact:</strong> {{ $healthRecord->resident->contact_number ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p><strong>Address:</strong> {{ $healthRecord->resident->current_address ?? 'N/A' }}</p>
                        <p><strong>Blood Type:</strong> {{ $healthRecord->blood_type ?? 'Not recorded' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Demographics Status -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-body text-center">
                <i class="fas fa-user-clock fa-3x {{ $healthRecord->is_senior_citizen ? 'text-primary' : 'text-muted' }} mb-3"></i>
                <h6>Senior Citizen</h6>
                <h3>{{ $healthRecord->is_senior_citizen ? 'Yes' : 'No' }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-body text-center">
                <i class="fas fa-baby fa-3x {{ $healthRecord->is_pregnant ? 'text-info' : 'text-muted' }} mb-3"></i>
                <h6>Pregnancy Status</h6>
                <h3>{{ $healthRecord->pregnancy_status }}</h3>
                @if($healthRecord->expected_due_date)
                    <p class="small text-muted mb-0">Due: {{ $healthRecord->expected_due_date->format('M d, Y') }}</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x {{ $healthRecord->is_malnourished ? 'text-warning' : 'text-muted' }} mb-3"></i>
                <h6>Malnutrition Status</h6>
                <h3>{{ $healthRecord->is_malnourished ? 'Yes' : 'No' }}</h3>
                @if($healthRecord->malnutrition_type)
                    <p class="small text-muted mb-0">Type: {{ ucfirst($healthRecord->malnutrition_type) }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Vital Signs -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-heartbeat me-2"></i>Vital Signs & Measurements</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="vital-box">
                            <div class="vital-label">Height</div>
                            <div class="vital-value">{{ $healthRecord->height ?? '--' }} <small>cm</small></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="vital-box">
                            <div class="vital-label">Weight</div>
                            <div class="vital-value">{{ $healthRecord->weight ?? '--' }} <small>kg</small></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="vital-box">
                            <div class="vital-label">BMI</div>
                            <div class="vital-value">
                                {{ $healthRecord->bmi ?? '--' }}
                                @if($healthRecord->bmi)
                                    <br><small class="text-muted" style="font-size: 0.8rem;">{{ $healthRecord->bmi_category }}</small>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="vital-box">
                            <div class="vital-label">Blood Pressure</div>
                            <div class="vital-value" style="font-size: 1.2rem;">{{ $healthRecord->blood_pressure ?? '--' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Medical Information -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-notes-medical me-2"></i>Medical Conditions</h6>
            </div>
            <div class="card-body">
                @if($healthRecord->medical_conditions)
                    <p>{{ $healthRecord->medical_conditions }}</p>
                @else
                    <p class="text-muted">No medical conditions recorded</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow h-100">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-pills me-2"></i>Current Medications</h6>
            </div>
            <div class="card-body">
                @if($healthRecord->current_medications)
                    <p>{{ $healthRecord->current_medications }}</p>
                @else
                    <p class="text-muted">No medications recorded</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-allergies me-2"></i>Allergies</h6>
            </div>
            <div class="card-body">
                @if($healthRecord->allergies)
                    <p>{{ $healthRecord->allergies }}</p>
                @else
                    <p class="text-muted">No allergies recorded</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Check-up Information -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-stethoscope me-2"></i>Check-up Schedule</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Last Check-up:</strong>
                    <p>{{ $healthRecord->last_checkup_date ? $healthRecord->last_checkup_date->format('F d, Y') : 'No check-up recorded' }}</p>
                </div>
                <div class="mb-3">
                    <strong>Next Check-up:</strong>
                    <p class="{{ $healthRecord->checkup_overdue ? 'text-danger fw-bold' : '' }}">
                        {{ $healthRecord->next_checkup_date ? $healthRecord->next_checkup_date->format('F d, Y') : 'Not scheduled' }}
                        @if($healthRecord->checkup_overdue)
                            <span class="badge badge-danger">OVERDUE</span>
                        @endif
                    </p>
                </div>
                @if($healthRecord->checkup_notes)
                    <div>
                        <strong>Notes:</strong>
                        <p class="text-muted small">{{ $healthRecord->checkup_notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($healthRecord->is_pregnant)
    <div class="col-md-6">
        <div class="card shadow border-info">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="fas fa-baby me-2"></i>Prenatal Care</h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Prenatal Visits:</strong> {{ $healthRecord->prenatal_visits_count }}
                </div>
                <div class="mb-2">
                    <strong>Last Visit:</strong> {{ $healthRecord->last_prenatal_visit ? $healthRecord->last_prenatal_visit->format('F d, Y') : 'No visits yet' }}
                </div>
                <div class="mb-2">
                    <strong>Next Visit:</strong>
                    <span class="{{ $healthRecord->prenatal_overdue ? 'text-danger fw-bold' : '' }}">
                        {{ $healthRecord->next_prenatal_visit ? $healthRecord->next_prenatal_visit->format('F d, Y') : 'Not scheduled' }}
                        @if($healthRecord->prenatal_overdue)
                            <span class="badge badge-danger">OVERDUE</span>
                        @endif
                    </span>
                </div>
                @if($healthRecord->prenatal_notes)
                    <div class="mt-3">
                        <strong>Notes:</strong>
                        <p class="text-muted small">{{ $healthRecord->prenatal_notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Immunization Records -->
@if($healthRecord->immunizations)
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-syringe me-2"></i>Immunization Records</h6>
            </div>
            <div class="card-body">
                <p><strong>Last Immunization:</strong> {{ $healthRecord->last_immunization_type ?? 'None' }}</p>
                <p><strong>Date:</strong> {{ $healthRecord->last_immunization_date ? $healthRecord->last_immunization_date->format('F d, Y') : 'N/A' }}</p>
                <p><strong>Status:</strong> 
                    <span class="badge {{ $healthRecord->immunizations_up_to_date ? 'badge-success' : 'badge-warning' }}">
                        {{ $healthRecord->immunizations_up_to_date ? 'Up to Date' : 'Needs Update' }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Service History -->
@if(isset($serviceHistory) && $serviceHistory->count() > 0)
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-history me-2"></i>Service History</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($serviceHistory as $service)
                    <div class="timeline-item">
                        <p class="mb-1">
                            <strong>{{ $service->service_type }}</strong>
                            <span class="badge {{ $service->status == 'completed' ? 'badge-success' : 'badge-warning' }}">
                                {{ ucfirst($service->status) }}
                            </span>
                        </p>
                        <p class="text-muted small mb-1">{{ $service->requested_at->format('F d, Y g:i A') }}</p>
                        @if($service->purpose)
                            <p class="small mb-0">Purpose: {{ $service->purpose }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Emergency Contact -->
@if($healthRecord->emergency_contact_name)
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow border-danger">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0"><i class="fas fa-phone-alt me-2"></i>Emergency Contact</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Name:</strong> {{ $healthRecord->emergency_contact_name }}
                    </div>
                    <div class="col-md-4">
                        <strong>Relationship:</strong> {{ $healthRecord->emergency_contact_relationship ?? 'N/A' }}
                    </div>
                    <div class="col-md-4">
                        <strong>Contact Number:</strong> {{ $healthRecord->emergency_contact_number ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Notes -->
@if($healthRecord->notes)
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Additional Notes</h6>
            </div>
            <div class="card-body">
                <p>{{ $healthRecord->notes }}</p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
