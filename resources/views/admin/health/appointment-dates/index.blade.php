@extends('layouts.admin.master')

@section('title', 'Health Appointment Dates')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4 d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0 text-gray-800">Health Appointment Dates</h1>
        <a href="{{ route('admin.health.appointment-dates.create') }}" class="btn btn-primary">
            <i class="fe fe-plus me-2"></i>Create New Appointment Date
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Manage Health Check-up Appointment Dates</strong>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fe fe-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fe fe-alert-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover table-borderless">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Title</th>
                                <th>Location</th>
                                <th>Time</th>
                                <th>Slots</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($appointmentDates as $date)
                            <tr>
                                <td>
                                    <strong>{{ $date->appointment_date->format('M d, Y') }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $date->appointment_date->format('l') }}</small>
                                </td>
                                <td>{{ $date->title }}</td>
                                <td>
                                    <i class="fe fe-map-pin text-primary me-1"></i>
                                    {{ $date->location }}
                                </td>
                                <td>
                                    <i class="fe fe-clock text-muted me-1"></i>
                                    {{ \Carbon\Carbon::parse($date->start_time)->format('h:i A') }} - 
                                    {{ \Carbon\Carbon::parse($date->end_time)->format('h:i A') }}
                                </td>
                                <td>
                                    <span class="badge {{ $date->is_full ? 'badge-danger' : 'badge-success' }}">
                                        {{ $date->booked_slots }}/{{ $date->max_slots }}
                                    </span>
                                    @if($date->is_full)
                                        <small class="text-danger d-block">FULL</small>
                                    @else
                                        <small class="text-success d-block">{{ $date->available_slots }} available</small>
                                    @endif
                                </td>
                                <td>{!! $date->status_badge !!}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.health.appointment-dates.view', $date->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="View Appointments">
                                            <i class="fe fe-eye"></i> View ({{ $date->booked_slots }})
                                        </a>
                                        
                                        @if($date->status === 'open')
                                            <button type="button" 
                                                    class="btn btn-sm btn-warning status-btn" 
                                                    data-id="{{ $date->id }}" 
                                                    data-status="closed"
                                                    title="Close Bookings">
                                                <i class="fe fe-lock"></i>
                                            </button>
                                        @endif

                                        @if($date->status === 'closed')
                                            <button type="button" 
                                                    class="btn btn-sm btn-success status-btn" 
                                                    data-id="{{ $date->id }}" 
                                                    data-status="open"
                                                    title="Open Bookings">
                                                <i class="fe fe-unlock"></i>
                                            </button>
                                        @endif

                                        @if(in_array($date->status, ['open', 'closed']))
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary status-btn" 
                                                    data-id="{{ $date->id }}" 
                                                    data-status="completed"
                                                    title="Mark as Completed">
                                                <i class="fe fe-check"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fe fe-calendar fe-32 text-muted mb-3 d-block"></i>
                                    <h6 class="text-muted">No appointment dates created yet</h6>
                                    <p class="text-muted">Create an appointment date to allow residents to book health check-ups.</p>
                                    <a href="{{ route('admin.health.appointment-dates.create') }}" class="btn btn-primary mt-2">
                                        <i class="fe fe-plus me-2"></i>Create First Appointment Date
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($appointmentDates->hasPages())
                    <div class="mt-4">
                        {{ $appointmentDates->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle status change
    document.querySelectorAll('.status-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const status = this.dataset.status;
            
            if (confirm(`Are you sure you want to change the status to "${status}"?`)) {
                updateStatus(id, status);
            }
        });
    });
});

function updateStatus(id, status) {
    fetch(`/admin/health/appointment-dates/${id}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error updating status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the status.');
    });
}
</script>
@endpush
