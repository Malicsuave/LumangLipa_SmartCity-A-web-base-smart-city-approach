@extends('layouts.admin.master')

@section('title', 'View Appointments - ' . $appointmentDate->title)

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $appointmentDate->title }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.health') }}">Health Services</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.health.appointment-dates.index') }}">Appointment Dates</a></li>
                <li class="breadcrumb-item active">{{ $appointmentDate->appointment_date->format('M d, Y') }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <i class="fe fe-calendar fe-32 text-primary mb-2"></i>
                <h6 class="text-muted mb-1">Date</h6>
                <h5 class="mb-0">{{ $appointmentDate->appointment_date->format('M d, Y') }}</h5>
                <small class="text-muted">{{ $appointmentDate->appointment_date->format('l') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <i class="fe fe-clock fe-32 text-info mb-2"></i>
                <h6 class="text-muted mb-1">Time</h6>
                <h5 class="mb-0">{{ \Carbon\Carbon::parse($appointmentDate->start_time)->format('h:i A') }}</h5>
                <small class="text-muted">to {{ \Carbon\Carbon::parse($appointmentDate->end_time)->format('h:i A') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <i class="fe fe-users fe-32 text-success mb-2"></i>
                <h6 class="text-muted mb-1">Bookings</h6>
                <h5 class="mb-0">{{ $appointmentDate->booked_slots }}</h5>
                <small class="text-muted">of {{ $appointmentDate->max_slots }} slots</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <i class="fe fe-map-pin fe-32 text-warning mb-2"></i>
                <h6 class="text-muted mb-1">Location</h6>
                <h5 class="mb-0">{{ $appointmentDate->location }}</h5>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong class="card-title">Resident Appointments ({{ $appointmentDate->appointments->count() }})</strong>
                <div>
                    {!! $appointmentDate->status_badge !!}
                    <button class="btn btn-sm btn-outline-primary ms-2" onclick="window.print()">
                        <i class="fe fe-printer me-1"></i>Print List
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($appointmentDate->description)
                    <div class="alert alert-info mb-4">
                        <i class="fe fe-info me-2"></i>{{ $appointmentDate->description }}
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Barangay ID</th>
                                <th>Resident Name</th>
                                <th>Age</th>
                                <th>Sex</th>
                                <th>Contact</th>
                                <th>Booked Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($appointmentDate->appointments as $index => $appointment)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $appointment->barangay_id }}</strong></td>
                                <td>
                                    @if($appointment->resident)
                                        {{ $appointment->resident->first_name }} 
                                        {{ $appointment->resident->middle_name }} 
                                        {{ $appointment->resident->last_name }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($appointment->resident && $appointment->resident->birthdate)
                                        {{ \Carbon\Carbon::parse($appointment->resident->birthdate)->age }} yrs
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($appointment->resident)
                                        {{ ucfirst($appointment->resident->sex ?? 'N/A') }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($appointment->resident && $appointment->resident->contact_number)
                                        {{ $appointment->resident->contact_number }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $appointment->requested_at->format('M d, Y h:i A') }}
                                    </small>
                                </td>
                                <td>{!! $appointment->status_badge !!}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if($appointment->status === 'pending')
                                            <button type="button" 
                                                    class="btn btn-success btn-sm approve-btn" 
                                                    data-id="{{ $appointment->id }}"
                                                    title="Approve">
                                                <i class="fe fe-check"></i>
                                            </button>
                                        @endif
                                        @if($appointment->status === 'approved')
                                            <button type="button" 
                                                    class="btn btn-primary btn-sm complete-btn" 
                                                    data-id="{{ $appointment->id }}"
                                                    title="Mark Complete">
                                                <i class="fe fe-check-circle"></i>
                                            </button>
                                        @endif
                                        <a href="{{ route('admin.health-services.show', $appointment->id) }}" 
                                           class="btn btn-info btn-sm"
                                           title="View Details">
                                            <i class="fe fe-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fe fe-users fe-32 text-muted mb-3 d-block"></i>
                                    <h6 class="text-muted">No appointments yet</h6>
                                    <p class="text-muted">Residents haven't booked for this date yet.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Approve appointment
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            if (confirm('Approve this appointment?')) {
                updateAppointmentStatus(id, 'approve');
            }
        });
    });

    // Complete appointment
    document.querySelectorAll('.complete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            if (confirm('Mark this appointment as completed?')) {
                updateAppointmentStatus(id, 'complete');
            }
        });
    });
});

function updateAppointmentStatus(id, action) {
    fetch(`/admin/health-services/${id}/${action}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred.');
    });
}
</script>
@endpush

@push('styles')
<style>
@media print {
    .navbar, .sidebar, .card-header button, .btn-group, nav, .breadcrumb {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endpush
